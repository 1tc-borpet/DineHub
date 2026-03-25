import { Injectable, signal } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { CartItem, MenuItem } from '../models';
import { environment } from '../../environments/environment';

@Injectable({ providedIn: 'root' })
export class CartService {
  private items = signal<CartItem[]>([]);
  private loading = signal(false);
  private error = signal<string | null>(null);

  cartItems = this.items.asReadonly();
  isLoading = this.loading.asReadonly();
  cartError = this.error.asReadonly();

  constructor(private http: HttpClient) {
    this.loadCart();
  }

  loadCart(): void {
    this.loading.set(true);
    console.log('Loading cart from API...');
    this.http.get<any>(`${environment.apiUrl}/cart`).subscribe({
      next: (response) => {
        console.log('Cart API response:', response);
        if (response.items && Array.isArray(response.items)) {
          const newItems = response.items.map((item: any) => ({
            menuItem: item.menuItem || {
              id: item.menu_item_id,
              name: 'N/A',
              price: item.price,
              image_url: null,
              description: null,
              preparation_time: 0,
              is_available: true,
              rating: 0,
              category_id: null,
            },
            quantity: item.quantity,
            restaurantId: item.restaurant_id,
          }));
          console.log('Parsed cart items:', newItems);
          this.items.set(newItems);
        } else {
          console.log('No items in response');
          this.items.set([]);
        }
        this.loading.set(false);
      },
      error: (err) => {
        console.error('Error loading cart:', err);
        this.items.set([]);
        this.loading.set(false);
      },
    });
  }

  addToCart(menuItem: MenuItem, quantity = 1, restaurantId: number = 1): void {
    this.loading.set(true);
    this.error.set(null);

    console.log('addToCart - előtte:', {
      currentItems: this.items().length,
      restaurants: [...new Set(this.items().map(i => i.restaurantId))],
    });

    this.http
      .post<any>(`${environment.apiUrl}/cart/items`, {
        menu_item_id: menuItem.id,
        restaurant_id: restaurantId,
        quantity,
      })
      .subscribe({
        next: (response) => {
          console.log('API válasz tételei:', response.items?.length || 0);
          console.log('API items:', response.items);
          
          if (response.items && Array.isArray(response.items)) {
            const newItems = response.items.map((item: any) => ({
              menuItem: item.menuItem || {
                id: item.menu_item_id,
                name: `Étel ${item.menu_item_id}`,
                price: item.price,
                image_url: null,
                description: null,
                preparation_time: 0,
                is_available: true,
                rating: 0,
                category_id: null,
              },
              quantity: item.quantity,
              restaurantId: item.restaurant_id,
            }));
            
            console.log('addToCart utánna - API-ból jövő tételei:', {
              newItems: newItems.length,
              restaurants: [...new Set(newItems.map((i: CartItem) => i.restaurantId))],
              items: newItems.map((i: CartItem) => `Étterem ${i.restaurantId}: étel ${i.menuItem.id}`),
            });
            
            // HELYES: Az API már tartalmazza az összes tételt, csak felülírjuk az items signal-t
            this.items.set(newItems);
          } else {
            console.log('No items in response, reloading cart');
            this.loadCart();
            return;
          }
          this.loading.set(false);
        },
        error: (err) => {
          console.error('Error adding to cart:', err);
          this.error.set(err.error?.message || 'Hiba az étel hozzáadásakor');
          this.loading.set(false);
          this.loadCart();
        },
      });
  }

  updateQuantity(menuItemId: number, quantity: number, restaurantId: number = 1): void {
    if (quantity <= 0) {
      this.removeFromCart(menuItemId, restaurantId);
      return;
    }

    // Lokálisan frissítjük rögtön
    this.items.update((current) =>
      current.map((i) =>
        i.menuItem.id === menuItemId && i.restaurantId === restaurantId
          ? { ...i, quantity }
          : i
      )
    );
  }

  removeFromCart(menuItemId: number, restaurantId: number = 1): void {
    // Lokálisan rögtön eltávolítjuk
    this.items.update((current) =>
      current.filter(
        (i) => !(i.menuItem.id === menuItemId && i.restaurantId === restaurantId)
      )
    );
  }

  clearCart(): void {
    this.loading.set(true);
    this.error.set(null);

    this.http.post<any>(`${environment.apiUrl}/cart/clear`, {}).subscribe({
      next: () => {
        this.items.set([]);
        this.loading.set(false);
      },
      error: () => {
        this.items.set([]);
        this.loading.set(false);
      },
    });
  }

  getTotalItems(): number {
    return this.items().reduce((sum, item) => sum + item.quantity, 0);
  }

  getTotalPrice(): number {
    const total = this.items().reduce((sum, item) => sum + (item.menuItem?.price || 0) * item.quantity, 0);
    console.log('getTotalPrice():', {
      items: this.items().length,
      restaurants: [...new Set(this.items().map(i => i.restaurantId))],
      total: total,
    });
    return total;
  }
}
