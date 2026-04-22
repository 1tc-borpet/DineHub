import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { environment } from '../../environments/environment';
import { Reservation, CreateReservationRequest } from '../models';

@Injectable({ providedIn: 'root' })
export class ReservationService {
  constructor(private http: HttpClient) {}

  create(data: CreateReservationRequest): Observable<Reservation> {
    return this.http.post<Reservation>(`${environment.apiUrl}/reservations`, data);
  }

  getMyReservations(): Observable<Reservation[]> {
    return this.http.get<Reservation[]>(`${environment.apiUrl}/my-reservations`);
  }

  cancel(id: number): Observable<Reservation> {
    return this.http.delete<Reservation>(`${environment.apiUrl}/reservations/${id}`);
  }

  getBookedSlots(restaurantId: number, date: string): Observable<string[]> {
    return this.http.get<{ success: boolean; data: string[] }>(
      `${environment.apiUrl}/reservations/booked-slots`,
      { params: { restaurant_id: restaurantId.toString(), date } }
    ).pipe(map(res => res.data));
  }

  // Admin
  getAllReservations(): Observable<Reservation[]> {
    return this.http.get<Reservation[]>(`${environment.apiUrl}/admin/reservations`);
  }

  confirm(id: number): Observable<Reservation> {
    return this.http.patch<Reservation>(`${environment.apiUrl}/admin/reservations/${id}/confirm`, {});
  }
}
