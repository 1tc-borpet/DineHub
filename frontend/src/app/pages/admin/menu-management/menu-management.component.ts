import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { MenuService } from '../../../services/menu.service';
import { MenuCategory, MenuItem } from '../../../models';

@Component({
  selector: 'app-menu-management',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './menu-management.component.html',
  styleUrl: './menu-management.component.scss',
})
export class MenuManagementComponent implements OnInit {
  private menuService = inject(MenuService);
  categories: MenuCategory[] = [];
  itemsByCategory: Map<number, MenuItem[]> = new Map();
  loading = true;
  error: string | null = null;
  
  editingItem: MenuItem | null = null;
  editPrice: number = 0;
  editRating: number = 0;
  isSaving = false;

  ngOnInit(): void {
    this.loadData();
  }

  loadData(): void {
    console.log('Adatok betöltésének indítása...');
    this.menuService.getCategoriesByRestaurant(1).subscribe({
      next: (response: any) => {
        console.log('Kategóriák válasz:', response);
        const cats = response.data || response;
        this.categories = Array.isArray(cats) ? cats : [];
        console.log('Betöltött kategóriák:', this.categories);
        
        if (this.categories.length === 0) {
          this.loading = false;
          return;
        }
        
        let loadedCount = 0;
        this.categories.forEach(cat => {
          this.menuService.getItemsByCategory(cat.id).subscribe({
            next: (itemResponse: any) => {
              console.log(`Kategória ${cat.id} ételei:`, itemResponse);
              const items = itemResponse.data || itemResponse;
              this.itemsByCategory.set(cat.id, Array.isArray(items) ? items : []);
              loadedCount++;
              if (loadedCount === this.categories.length) {
                this.loading = false;
              }
            },
            error: (err) => {
              console.error(`Hiba kategória ${cat.id} ételei betöltésékor:`, err);
              loadedCount++;
              if (loadedCount === this.categories.length) {
                this.loading = false;
              }
            }
          });
        });
      },
      error: (err) => {
        console.error('Hiba az adatok betöltésekor:', err);
        this.error = `API hiba: ${err.message || 'Ismeretlen hiba'}. URL: ${err.url || 'N/A'}`;
        this.loading = false;
      }
    });
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
