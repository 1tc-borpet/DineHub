<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\MenuCategoryController;
use App\Http\Controllers\Api\MenuItemController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\DashboardController;

// Nyilvános auth route-ok (autentifikáció nélkül)
Route::prefix('v1/auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// DEBUG: Összes user listázása
Route::get('v1/debug/users', function () {
    return response()->json([
        'total' => \App\Models\User::count(),
        'users' => \App\Models\User::select('id', 'name', 'email', 'phone', 'role', 'is_active', 'created_at')->get(),
    ]);
});

// Nyilvános route-ok (autentifikáció nélkül)
Route::prefix('v1')->group(function () {
    // Éttermek listázása és részletei
    Route::get('restaurants', [RestaurantController::class, 'index']);
    Route::get('restaurants/{id}', [RestaurantController::class, 'show']);

    // Menükategóriák és ételek
    Route::get('restaurants/{id}/categories', [MenuCategoryController::class, 'byRestaurant']);
    Route::get('categories/{id}/items', [MenuItemController::class, 'byCategory']);
    Route::get('menu-items/{id}', [MenuItemController::class, 'show']);

    // Autentifikáció szükséges route-ok
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::post('auth/refresh-token', [AuthController::class, 'refreshToken']);
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/change-password', [AuthController::class, 'changePassword']);

        // User profil
        Route::get('user/profile', [UserController::class, 'show']);
        Route::patch('user/profile', [UserController::class, 'update']);

        // Rendelések
        Route::post('orders/checkout-cart', [OrderController::class, 'checkoutCart']);
        Route::post('orders', [OrderController::class, 'store']);
        Route::get('orders/{id}', [OrderController::class, 'show']);
        Route::get('my-orders', [OrderController::class, 'userOrders']);
        Route::patch('orders/{id}', [OrderController::class, 'update']);

        // Kosár (több étteremből lehet rendelni)
        Route::get('cart', [CartController::class, 'show']);
        Route::post('cart/items', [CartController::class, 'addItem']);
        Route::patch('cart/items/{cartItemId}', [CartController::class, 'updateItem']);
        Route::delete('cart/items/{cartItemId}', [CartController::class, 'removeItem']);
        Route::post('cart/clear', [CartController::class, 'clear']);
        
        // DEBUG endpoint
        Route::get('cart/debug/status', function () {
            $cart = \App\Models\Cart::where('user_id', auth()->id())
                ->with('items.menuItem', 'items.restaurant')
                ->first();
            
            if (!$cart) {
                return response()->json(['error' => 'No cart found']);
            }
            
            return response()->json([
                'cart_id' => $cart->id,
                'user_id' => $cart->user_id,
                'items_count' => $cart->items->count(),
                'items_raw_count' => \App\Models\CartItem::where('cart_id', $cart->id)->count(),
                'items' => $cart->items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'menu_item_id' => $item->menu_item_id,
                        'restaurant_id' => $item->restaurant_id,
                        'quantity' => $item->quantity,
                        'menuItem_id' => $item->menuItem?->id,
                        'menuItem_name' => $item->menuItem?->name,
                        'restaurant_id_check' => $item->restaurant?->id,
                    ];
                }),
                'subtotal' => $cart->subtotal,
                'tax' => $cart->tax,
                'total' => $cart->total,
            ]);
        });
        
        // Test add CartItem directly
        Route::get('cart/debug/test-add', function () {
            $cart = \App\Models\Cart::where('user_id', auth()->id())->first();
            if (!$cart) {
                $cart = \App\Models\Cart::create([
                    'user_id' => auth()->id(),
                    'subtotal' => 0,
                    'tax' => 0,
                    'total' => 0,
                ]);
            }
            
            // Direct test add
            \App\Models\CartItem::create([
                'cart_id' => $cart->id,
                'menu_item_id' => 1,
                'restaurant_id' => 1,
                'quantity' => 1,
                'price' => 2490,
                'subtotal' => 2490,
            ]);
            
            \App\Models\CartItem::create([
                'cart_id' => $cart->id,
                'menu_item_id' => 16,
                'restaurant_id' => 2,
                'quantity' => 1,
                'price' => 2990,
                'subtotal' => 2990,
            ]);
            
            return response()->json([
                'created' => true,
                'items_count' => \App\Models\CartItem::where('cart_id', $cart->id)->count(),
            ]);
        });

        // Asztalfoglalások
        Route::post('reservations', [ReservationController::class, 'store']);
        Route::get('my-reservations', [ReservationController::class, 'userReservations']);
        Route::patch('reservations/{id}', [ReservationController::class, 'update']);
        Route::delete('reservations/{id}', [ReservationController::class, 'destroy']);

        // Fizetések
        Route::post('payments', [PaymentController::class, 'store']);
        Route::get('payments/{id}', [PaymentController::class, 'show']);
    });
});

// Admin route-ok
Route::prefix('v1/admin')->middleware('auth:sanctum')->group(function () {
    // Dashboard statisztikák és jelentések
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('dashboard/recent-orders', [DashboardController::class, 'recentOrders']);
    Route::get('dashboard/recent-reservations', [DashboardController::class, 'recentReservations']);
    Route::get('dashboard/revenue-report', [DashboardController::class, 'revenueReport']);
    Route::get('dashboard/order-status-breakdown', [DashboardController::class, 'orderStatusBreakdown']);
    Route::get('dashboard/reservation-status-breakdown', [DashboardController::class, 'reservationStatusBreakdown']);
    Route::get('dashboard/popular-items', [DashboardController::class, 'popularMenuItems']);
    Route::get('dashboard/top-customers', [DashboardController::class, 'topCustomers']);

    // Felhasználók kezelése
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::patch('users/{id}', [UserController::class, 'update']);
    Route::post('users/{id}/deactivate', [UserController::class, 'deactivate']);
    Route::post('users/{id}/activate', [UserController::class, 'activate']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    // Rendeléskezelés
    Route::get('orders', [OrderController::class, 'index']);
    Route::patch('orders/{id}/status', [OrderController::class, 'updateStatus']);

    // Asztalfoglalások kezelése
    Route::get('reservations', [ReservationController::class, 'index']);
    Route::patch('reservations/{id}/confirm', [ReservationController::class, 'confirm']);

    // Étterem kezelés
    Route::resource('restaurants', RestaurantController::class)->except(['show']);
    Route::resource('restaurants.categories', MenuCategoryController::class);
    Route::resource('categories.items', MenuItemController::class);
});

