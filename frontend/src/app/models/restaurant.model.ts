export interface Restaurant {
  id: number;
  name: string;
  description: string;
  address: string;
  phone: string;
  email: string;
  image_url?: string;
  logo_url?: string;
  cover_image_url?: string;
  rating?: number;
  delivery_time?: number;
  delivery_fee?: number;
  is_open?: boolean;
  is_active?: boolean;
  opening_time?: string;
  closing_time?: string;
  opening_hours?: string;
  created_at: string;
  updated_at: string;
}
