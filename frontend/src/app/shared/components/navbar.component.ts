import { Component, inject, OnInit } from '@angular/core';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { CommonModule } from '@angular/common';
import { AuthService } from '../../services/auth.service';
import { CartService } from '../../services/cart.service';
import { RestaurantService } from '../../services/restaurant.service';
import { Restaurant } from '../../models';

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [RouterLink, RouterLinkActive, CommonModule],
  templateUrl: './navbar.component.html',
  styleUrl: './navbar.component.scss',
})
export class NavbarComponent implements OnInit {
  readonly auth: AuthService = inject(AuthService);
  readonly cart: CartService = inject(CartService);
  private restaurantService = inject(RestaurantService);

  mobileMenuOpen = false;
  menuDropdownOpen = false;
  restaurants: Restaurant[] = [];

  ngOnInit(): void {
    this.restaurantService.getAll().subscribe({
      next: (data) => { this.restaurants = data; },
      error: () => {}
    });
  }

  toggleMenu(): void {
    this.mobileMenuOpen = !this.mobileMenuOpen;
  }

  closeMenu(): void {
    this.mobileMenuOpen = false;
    this.menuDropdownOpen = false;
  }

  toggleMenuDropdown(event: Event): void {
    event.preventDefault();
    this.menuDropdownOpen = !this.menuDropdownOpen;
  }
}
