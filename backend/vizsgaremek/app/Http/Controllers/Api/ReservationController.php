<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use App\Events\ReservationStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $reservations = Reservation::with('restaurant', 'user')
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json($reservations, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba a foglalások lekérésekor.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'restaurant_id'    => 'required|exists:restaurants,id',
                'reservation_date' => 'required|date|after_or_equal:today',
                'reservation_time' => 'required|string',
                'party_size'       => 'required|integer|min:1|max:20',
                'notes'            => 'nullable|string|max:500',
            ]);

            // Ellenőrizzük, hogy az adott étteremben az adott időpontra van-e már aktív foglalás
            $existingReservation = Reservation::where('restaurant_id', $validated['restaurant_id'])
                ->where('reservation_date', $validated['reservation_date'])
                ->where('reservation_time_only', $validated['reservation_time'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();

            if ($existingReservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erre az időpontra már van foglalás. Kérlek válassz másik időpontot!',
                ], Response::HTTP_CONFLICT);
            }

            // Kombináljuk a dátumot és az időt egy datetime-ba
            $reservationDatetime = $validated['reservation_date'] . ' ' . $validated['reservation_time'] . ':00';

            $reservation = Reservation::create([
                'restaurant_id'       => $validated['restaurant_id'],
                'user_id'             => auth()->id(),
                'guest_name'          => auth()->user()->name ?? 'Vendég',
                'reservation_date'    => $validated['reservation_date'],
                'reservation_time_only' => $validated['reservation_time'],
                'reservation_time'    => $reservationDatetime,
                'party_size'          => $validated['party_size'],
                'guest_count'         => $validated['party_size'],
                'notes'               => $validated['notes'] ?? null,
                'status'              => 'pending',
            ]);

            return response()->json($reservation->load('restaurant'), Response::HTTP_CREATED);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validációs hiba.',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba a foglalás létrehozásakor.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $reservation = Reservation::with('restaurant', 'table', 'user')
                ->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $reservation,
            ], Response::HTTP_OK);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'A foglalás nem található.',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba a foglalás lekérésekor.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            
            // Validáció: csak pending és confirmed foglalások módosíthatók
            if (!in_array($reservation->status, ['pending', 'confirmed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Csak aktív foglalások módosíthatók.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
            $validated = $request->validate([
                'guest_name' => 'sometimes|string|max:255',
                'guest_email' => 'nullable|email',
                'guest_phone' => 'nullable|string|regex:/^[0-9\+\-\(\)\s]+$/',
                'guest_count' => 'sometimes|integer|min:1|max:20',
                'reservation_time' => 'sometimes|date_format:Y-m-d H:i',
                'notes' => 'nullable|string|max:500',
            ]);

            // Ha módosítottak valamit, re-validálás szükséges
            if (isset($validated['guest_count']) || isset($validated['reservation_time'])) {
                $guestCount = $validated['guest_count'] ?? $reservation->guest_count;
                $reservationTime = $validated['reservation_time'] ?? $reservation->reservation_time;
                
                $table = $reservation->table;
                if ($table->capacity < $guestCount) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Az asztal kapacitása nem elegendő.',
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }

            $reservation->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Foglalás sikeresen frissítve.',
                'data' => $reservation,
            ], Response::HTTP_OK);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'A foglalás nem található.',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba a foglalás frissítésekor.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            
            // Validáció: csak pending és confirmed foglalás törölhető
            if (!in_array($reservation->status, ['pending', 'confirmed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Csak aktív foglalások törölhetők.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $reservation->update(['status' => 'cancelled']);
            
            return response()->json([
                'success' => true,
                'message' => 'Foglalás sikeresen törölve.',
            ], Response::HTTP_OK);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'A foglalás nem található.',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba a foglalás törléskor.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get user's reservations
     */
    public function userReservations()
    {
        try {
            $reservations = Reservation::where('user_id', auth()->id())
                ->with('restaurant')
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json($reservations, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba a foglalások lekérésekor.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Confirm reservation (admin)
     */
    public function confirm(Request $request, string $id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            
            if ($reservation->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Csak pending foglalás erősíthető meg.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $oldStatus = $reservation->status;

            $reservation->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            // WebSocket broadcast: foglalás státusz változás
            ReservationStatusChanged::dispatch($reservation, $oldStatus, 'confirmed');

            return response()->json([
                'success' => true,
                'message' => 'Foglalás sikeresen megerősítve.',
                'data' => $reservation,
            ], Response::HTTP_OK);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'A foglalás nem található.',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba a megerősítéskor.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get booked time slots for a restaurant on a given date.
     */
    public function bookedSlots(Request $request)
    {
        try {
            $request->validate([
                'restaurant_id' => 'required|exists:restaurants,id',
                'date' => 'required|date',
            ]);

            $bookedTimes = Reservation::where('restaurant_id', $request->restaurant_id)
                ->where('reservation_date', $request->date)
                ->whereIn('status', ['pending', 'confirmed'])
                ->pluck('reservation_time_only')
                ->unique()
                ->values();

            return response()->json([
                'success' => true,
                'data' => $bookedTimes,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba a foglalt időpontok lekérésekor.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

