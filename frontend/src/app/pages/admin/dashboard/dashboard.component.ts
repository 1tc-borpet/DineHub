import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { DashboardService, DashboardStats } from '../../../services/dashboard.service';
import { MenuService } from '../../../services/menu.service';
import { MenuCategory, MenuItem } from '../../../models';

@Component({
  selector: 'app-admin-dashboard',
  standalone: true,
  imports: [CommonModule, RouterLink, FormsModule],
  templateUrl: './dashboard.component.html',
  styleUrl: './dashboard.component.scss',
})
export class AdminDashboardComponent implements OnInit {
  private dashboardService = inject(DashboardService);
  private menuService = inject(MenuService);

  stats: DashboardStats | null = null;
  recentOrders: any[] = [];
  recentReservations: any[] = [];
  popularItems: any[] = [];
  loading = true;

  // Menu management
  categories: MenuCategory[] = [];
  itemsByCategory: Map<number, MenuItem[]> = new Map();

  // Edit modal
  editingItem: MenuItem | null = null;
  editPrice: number = 0;
  editRating: number = 0;
  isSaving = false;
  showMenuEditor = false;

  ngOnInit(): void {
    this.dashboardService.getStats().subscribe({ next: (s) => { this.stats = s; } });
    this.dashboardService.getRecentOrders().subscribe({ next: (o) => { this.recentOrders = o; } });
    this.dashboardService.getRecentReservations().subscribe({ next: (r) => { this.recentReservations = r; } });
    this.dashboardService.getPopularItems().subscribe({
      next: (i) => { this.popularItems = i; this.loading = false; },
      error: () => { this.loading = false; }
    });
    
    // Etlap adatainak betöltése
    this.loadMenuData();
  }

  loadMenuData(): void {
    console.log('Étlap adatok betöltése...');
    this.menuService.getCategoriesByRestaurant(1).subscribe({
      next: (response: any) => {
        console.log('Kategóriák válasz:', response);
        const cats = response.data || response;
        this.categories = Array.isArray(cats) ? cats : [];
        console.log('Betöltött kategóriák:', this.categories);
        
        if (this.categories.length === 0) {
          console.warn('Nincsenek kategóriák');
          return;
        }
        
        let loadedCount = 0;
        this.categories.forEach((cat: MenuCategory) => {
          this.menuService.getItemsByCategory(cat.id).subscribe({
            next: (itemResponse: any) => {
              console.log(`Kategória ${cat.id} ételei:`, itemResponse);
              const items = itemResponse.data || itemResponse;
              const itemArray = Array.isArray(items) ? items : [];
              this.itemsByCategory.set(cat.id, itemArray);
              loadedCount++;
              console.log(`${loadedCount}/${this.categories.length} kategória betöltve`);
            },
            error: (err) => {
              console.error(`Hiba kategória ${cat.id} ételei betöltésekor:`, err);
              loadedCount++;
            }
          });
        });
      },
      error: (err) => {
        console.error('Hiba az étlap betöltésekor:', err);
      }
    });
  }

  toggleMenuEditor(): void {
    this.showMenuEditor = !this.showMenuEditor;
    if (this.showMenuEditor && this.categories.length === 0) {
      this.loadMenuData();
    }
  }

  getItems(categoryId: number): MenuItem[] {
    return this.itemsByCategory.get(categoryId) || [];
  }

  openEditModal(item: MenuItem): void {
    this.editingItem = item;
    this.editPrice = item.price;
    this.editRating = item.rating || 0;
  }

  closeModal(): void {
    this.editingItem = null;
    this.editPrice = 0;
    this.editRating = 0;
  }

  saveChanges(): void {
    if (!this.editingItem) return;
    
    this.isSaving = true;
    const updateData = {
      price: this.editPrice,
      rating: this.editRating,
    };

    console.log('Mentés:', { itemId: this.editingItem.id, updateData });

    this.menuService.updateMenuItem(this.editingItem.id, updateData).subscribe({
      next: (response: any) => {
        console.log('Sikeres mentés:', response);
        const categoryId = this.editingItem!.category_id;
        const items = this.itemsByCategory.get(categoryId) || [];
        const index = items.findIndex(i => i.id === this.editingItem!.id);
        if (index > -1) {
          items[index] = { ...items[index], price: this.editPrice, rating: this.editRating };
          this.itemsByCategory.set(categoryId, [...items]);
        }
        this.closeModal();
        this.isSaving = false;
        alert('Étel adatai sikeresen frissítve!');
      },
      error: (err) => {
        console.error('Hiba:', err);
        alert('Hiba a mentéskor: ' + (err.error?.message || 'Ismeretlen hiba'));
        this.isSaving = false;
      }
    });
  }
}
