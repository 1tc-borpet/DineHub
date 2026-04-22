import { Component, OnInit, inject, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, ActivatedRoute, Router } from '@angular/router';
import { MenuService } from '../../services/menu.service';
import { RestaurantService } from '../../services/restaurant.service';
import { CartService } from '../../services/cart.service';
import { MenuCategory, MenuItem, Restaurant } from '../../models';

@Component({
  selector: 'app-menu',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './menu.component.html',
  styleUrl: './menu.component.scss',
})
export class MenuComponent implements OnInit {
  private menuService = inject(MenuService);
  private restaurantService = inject(RestaurantService);
  public cart = inject(CartService);
  private route = inject(ActivatedRoute);
  private router = inject(Router);

  restaurant: Restaurant | null = null;
  allRestaurants: Restaurant[] = [];
  categories: MenuCategory[] = [];
  itemsByCategory: { [catId: number]: MenuItem[] } = {};
  activeCategory: number | null = null;
  loading = true;
  restaurantId = 1; // default

  ngOnInit(): void {
    this.route.paramMap.subscribe(params => {
      const paramId = params.get('restaurantId');
      this.restaurantId = paramId ? +paramId : 1;
      this.loading = true;
      this.categories = [];
      this.itemsByCategory = {};
      this.loadData();
    });

    this.restaurantService.getAll().subscribe({
      next: (data) => { this.allRestaurants = data; },
      error: () => {}
    });
  }

  switchRestaurant(id: number): void {
    this.router.navigate(['/menu', id]);
  }

  loadData(): void {
    this.restaurantService.getById(this.restaurantId).subscribe({
      next: (r) => { this.restaurant = r; },
      error: () => {}
    });

    this.menuService.getCategoriesByRestaurant(this.restaurantId).subscribe({
      next: (cats) => {
        this.categories = cats;
        if (cats.length > 0) {
          this.activeCategory = cats[0].id;
          cats.forEach(cat => this.loadItems(cat.id));
        }
        this.loading = false;
      },
      error: () => { this.loading = false; }
    });
  }

  loadItems(categoryId: number): void {
    this.menuService.getItemsByCategory(categoryId).subscribe({
      next: (items) => { this.itemsByCategory = { ...this.itemsByCategory, [categoryId]: items }; }
    });
  }

  getItems(categoryId: number): MenuItem[] {
    return this.itemsByCategory[categoryId] || [];
  }

  addToCart(item: MenuItem): void {
    console.log('MenuComponent.addToCart called:', { item: item.name, restaurantId: this.restaurantId });
    this.cart.addToCart(item, 1, this.restaurantId);
  }

  getCartQuantity(itemId: number): number {
    const cartItem = this.cart.cartItems().find(i => i.menuItem.id === itemId && i.restaurantId === this.restaurantId);
    return cartItem?.quantity || 0;
  }

  scrollTo(categoryId: number): void {
    this.activeCategory = categoryId;
    const el = document.getElementById(`cat-${categoryId}`);
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}
