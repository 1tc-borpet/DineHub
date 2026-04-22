import { Component, OnInit, inject } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { Router, RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';
import { CartService } from '../../services/cart.service';
import { OrderService } from '../../services/order.service';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-checkout',
  standalone: true,
  imports: [ReactiveFormsModule, RouterLink, CommonModule],
  templateUrl: './checkout.component.html',
  styleUrl: './checkout.component.scss',
})
export class CheckoutComponent implements OnInit {
  private fb = inject(FormBuilder);
  public cart = inject(CartService);
  private orderService = inject(OrderService);
  private router = inject(Router);
  public auth = inject(AuthService);

  form: FormGroup = this.fb.group({
    order_type: ['dine_in', Validators.required],
    delivery_address: [''],
    notes: [''],
  });

  loading = false;
  error = '';

  ngOnInit(): void {
    if (this.cart.cartItems().length === 0) {
      this.router.navigate(['/menu']);
    }
    this.form.get('order_type')?.valueChanges.subscribe(type => {
      const addr = this.form.get('delivery_address');
      if (type === 'delivery') {
        addr?.setValidators(Validators.required);
      } else {
        addr?.clearValidators();
      }
      addr?.updateValueAndValidity();
    });
  }

  submit(): void {
    if (this.form.invalid) return;
    this.loading = true;
    this.error = '';

    const payload = {
      type: this.form.value.order_type,
      notes: this.form.value.notes,
    };

    this.orderService.checkoutCart(payload).subscribe({
      next: (response: any) => {
        this.cart.clearCart();
        // Ha több rendelés is van, az első rendelésre navigálunk
        const orders = response.orders || [response];
        if (orders.length > 0) {
          this.router.navigate(['/payment', orders[0].id]);
        } else {
          this.router.navigate(['/menu']);
        }
      },
      error: (err) => {
        const errors = err.error?.errors;
        if (errors) {
          this.error = Object.values(errors).flat().join(' ');
        } else {
          this.error = err.error?.message || 'Hiba a rendelés leadásakor.';
        }
        this.loading = false;
      },
    });
  }
}
