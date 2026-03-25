import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { environment } from '../../environments/environment';
import { MenuCategory, MenuItem } from '../models';

interface ApiResponse<T> {
  success: boolean;
  data: T;
}

@Injectable({ providedIn: 'root' })
export class MenuService {
  constructor(private http: HttpClient) {}

  getCategoriesByRestaurant(restaurantId: number): Observable<MenuCategory[]> {
    return this.http.get<ApiResponse<MenuCategory[]>>(`${environment.apiUrl}/restaurants/${restaurantId}/categories`)
      .pipe(map(res => res.data ?? (res as any)));
  }

  getItemsByCategory(categoryId: number): Observable<MenuItem[]> {
    return this.http.get<ApiResponse<MenuItem[]>>(`${environment.apiUrl}/categories/${categoryId}/items`)
      .pipe(map(res => res.data ?? (res as any)));
  }

  getItemById(id: number): Observable<MenuItem> {
    return this.http.get<ApiResponse<MenuItem>>(`${environment.apiUrl}/menu-items/${id}`)
      .pipe(map(res => res.data ?? (res as any)));
  }

  updateMenuItem(id: number, data: Partial<MenuItem>): Observable<ApiResponse<MenuItem>> {
    return this.http.patch<ApiResponse<MenuItem>>(`${environment.apiUrl}/admin/menu-items/${id}`, data);
  }
}
