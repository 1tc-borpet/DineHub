<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    /**
     * Get user's cart or create if doesn't exist
     */
    public function show()
    {
        try {
            $cart = Cart::where('user_id', auth()->id())
                ->with('items.menuItem', 'items.restaurant')
                ->first();

            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => auth()->id(),
                    'subtotal' => 0,
                    'tax' => 0,
                    'total' => 0,
                ]);
                $cart = $cart->load('items.menuItem', 'items.restaurant');
            }

            return response()->json($this->formatCartResponse($cart), Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba a kosár lekérésekor.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Add item to cart (supports multiple restaurants)
     */
    public function addItem(Request $request)
    {
        try {
            $validated = $request->validate([
                'menu_item_id' => 'required|exists:menu_items,id',
                'restaurant_id' => 'required|exists:restaurants,id',
                'quantity' => 'required|integer|min:1|max:100',
            ]);

            // Get or create cart
            $cart = Cart::where('user_id', auth()->id())->first();
            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => auth()->id(),
                    'subtotal' => 0,
                    'tax' => 0,
                    'total' => 0,
                ]);
            }

            // Get menu item
            $menuItem = MenuItem::findOrFail($validated['menu_item_id']);

            if (!$menuItem->is_available) {
                return response()->json([
                    'success' => false,
                    'message' => 'Az ' . $menuItem->name . ' étel nem elérhető.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Check if item already in cart
            $existingItem = CartItem::where('cart_id', $cart->id)
                ->where('menu_item_id', $validated['menu_item_id'])
                ->where('restaurant_id', $validated['restaurant_id'])
                ->first();

            if ($existingItem) {
                // Update quantity
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $validated['quantity'],
                    'subtotal' => ($existingItem->quantity + $validated['quantity']) * $menuItem->price,
                ]);
            } else {
                // Create new cart item
                CartItem::create([
                    'cart_id' => $cart->id,
                    'menu_item_id' => $validated['menu_item_id'],
                    'restaurant_id' => $validated['restaurant_id'],
                    'quantity' => $validated['quantity'],
                    'price' => $menuItem->price,
                    'subtotal' => $menuItem->price * $validated['quantity'],
                ]);
            }

            // Recalculate cart totals
            $this->updateCartTotals($cart);

            // Reload with relations
            $cart = Cart::findOrFail($cart->id)->load('items.menuItem', 'items.restaurant');
            
            // Debug log
            \Log::info('Cart after add:', [
                'user_id' => auth()->id(),
                'items_count' => count($cart->items),
                'items' => $cart->items->pluck(['menu_item_id', 'restaurant_id'])->toArray(),
            ]);

            return response()->json(
                $this->formatCartResponse($cart),
                Response::HTTP_OK
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validációs hiba.',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba az tétel hozzáadásakor.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request, string $cartItemId)
    {
        try {
            $cartItem = CartItem::findOrFail($cartItemId);
            $cart = $cartItem->cart;

            // Verify ownership
            if ($cart->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nincs jogosultság.',
                ], Response::HTTP_FORBIDDEN);
            }

            $cartItem->delete();

            // Recalculate cart totals
            $this->updateCartTotals($cart);

            // Reload cart with all relations
            $cart = Cart::findOrFail($cart->id)->load('items.menuItem', 'items.restaurant');

            return response()->json(
                $this->formatCartResponse($cart),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba a tétel eltávolításakor.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update item quantity in cart
     */
    public function updateItem(Request $request, string $cartItemId)
    {
        try {
            $validated = $request->validate([
                'quantity' => 'required|integer|min:1|max:100',
            ]);

            $cartItem = CartItem::findOrFail($cartItemId);
            $cart = $cartItem->cart;

            // Verify ownership
            if ($cart->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nincs jogosultság.',
                ], Response::HTTP_FORBIDDEN);
            }

            $cartItem->update([
                'quantity' => $validated['quantity'],
                'subtotal' => $validated['quantity'] * $cartItem->price,
            ]);

            // Recalculate cart totals
            $this->updateCartTotals($cart);

            // Reload cart with all relations
            $cart = Cart::findOrFail($cart->id)->load('items.menuItem', 'items.restaurant');

            return response()->json(
                $this->formatCartResponse($cart),
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba a tétel módosításakor.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        try {
            $cart = Cart::where('user_id', auth()->id())->first();

            if ($cart) {
                $cart->items()->delete();
                $cart->update([
                    'subtotal' => 0,
                    'tax' => 0,
                    'total' => 0,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'A kosár ürítve.',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba a kosár ürítésekor.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Helper function to update cart totals
     */
    private function updateCartTotals(Cart $cart)
    {
        $subtotal = $cart->items()->sum('subtotal');

        $cart->update([
            'subtotal' => $subtotal,
            'tax' => 0,
            'total' => $subtotal,
        ]);
    }

    /**
     * Helper function to format cart response
     */
    private function formatCartResponse(Cart $cart)
    {
        \Log::info('formatCartResponse - items count:', [
            'items_count' => $cart->items->count(),
            'items' => $cart->items->map(fn($i) => ['id' => $i->id, 'menu_item_id' => $i->menu_item_id, 'restaurant_id' => $i->restaurant_id])->toArray(),
        ]);
        
        return [
            'id' => $cart->id,
            'user_id' => $cart->user_id,
            'subtotal' => $cart->subtotal,
            'tax' => $cart->tax,
            'total' => $cart->total,
            'items' => $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'cart_id' => $item->cart_id,
                    'menu_item_id' => $item->menu_item_id,
                    'restaurant_id' => $item->restaurant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                    'menuItem' => $item->menuItem ? [
                        'id' => $item->menuItem->id,
                        'name' => $item->menuItem->name,
                        'description' => $item->menuItem->description,
                        'price' => $item->menuItem->price,
                        'image_url' => $item->menuItem->image_url,
                        'preparation_time' => $item->menuItem->preparation_time,
                        'is_available' => $item->menuItem->is_available,
                        'rating' => $item->menuItem->rating,
                        'category_id' => $item->menuItem->category_id,
                    ] : null,
                    'restaurant' => $item->restaurant ? [
                        'id' => $item->restaurant->id,
                        'name' => $item->restaurant->name,
                    ] : null,
                ];
            })->toArray(),
        ];
    }
}
