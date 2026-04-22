export type OrderStatus = 'pending' | 'confirmed' | 'preparing' | 'ready' | 'delivered' | 'served' | 'completed' | 'cancelled';

export interface OrderItem {
  id: number;
  menu_item_id: number;
  quantity: number;
  price: number;
  subtotal: number;
  menuItem?: {
    id: number;
    name: string;
    image_url?: string;
  };
  menu_item?: {
    id: number;
    name: string;
    image_url?: string;
  };
}

export interface Order {
  id: number;
  user_id: number;
  restaurant_id: number;
  order_number?: string;
  status: OrderStatus;
  total: number;
  total_amount?: number;
  subtotal?: number;
  tax?: number;
  notes?: string;
  delivery_address?: string;
  type: 'dine_in' | 'takeaway' | 'delivery';
  order_type?: 'dine_in' | 'takeaway' | 'delivery';
  items: OrderItem[];
  created_at: string;
  updated_at: string;
}

export interface CreateOrderRequest {
  restaurant_id: number;
  type: 'dine_in' | 'takeaway' | 'delivery';
  notes?: string;
  delivery_address?: string;
  items: {
    menu_item_id: number;
    quantity: number;
  }[];
}
