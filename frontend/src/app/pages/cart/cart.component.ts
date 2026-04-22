import { Component, inject, OnInit, signal } from '@angular/core';
import { RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';
import { CartService } from '../../services/cart.service';
import { RestaurantService } from '../../services/restaurant.service';
import { CartItem, Restaurant } from '../../models';

@Component({
  selector: 'app-cart',
  standalone: true,
  imports: [RouterLink, CommonModule],
  templateUrl: './cart.component.html',
  styleUrl: './cart.component.scss',
})
export class CartComponent implements OnInit {
  cart = inject(CartService);
  restaurantService = inject(RestaurantService);
  
  private restaurants = signal<Restaurant[]>([]);

  ngOnInit(): void {
    // FONTOS: NE töltsöm le újra a kosárt!
    // Ha ezt meghívnám, akkor az API üres kosárat töltene be és felülírná az addToCart() által betöltött adatokat!
    // this.cart.loadCart();
    
    this.restaurantService.getAll().subscribe(restaurants => {
      console.log('Restaurants loaded:', restaurants.map(r => r.name));
      this.restaurants.set(restaurants);
    });
    
    // Monitor kosár értékeket
    setInterval(() => {
      if (this.cart.cartItems().length > 0) {
        const rests = this.getRestaurantIds().join(', ');
        console.log(`Kosár: ${this.cart.cartItems().length} tétel | Restaurantok: ${rests}`);
      }
    }, 1000);
  }

  getRestaurantIds(): number[] {
    const items = this.cart.cartItems();
    return [...new Set(items.map(item => item.restaurantId))].sort();
  }

  getItemsByRestaurant(restaurantId: number): CartItem[] {
    return this.cart.cartItems().filter(item => item.restaurantId === restaurantId);
  }

  getRestaurantName(restaurantId: number): string {
    const restaurant = this.restaurants().find(r => r.id === restaurantId);
    return restaurant?.name || `Étterem #${restaurantId}`;
  }

  getTotalItems(): number {
    return this.cart.getTotalItems();
  }

  getTotalPrice(): number {
    return this.cart.getTotalPrice();
  }
}
