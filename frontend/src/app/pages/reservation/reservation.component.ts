import { Component, OnInit, inject } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { ReservationService } from '../../services/reservation.service';
import { RestaurantService } from '../../services/restaurant.service';
import { Reservation, Restaurant } from '../../models';

@Component({
  selector: 'app-reservation',
  standalone: true,
  imports: [ReactiveFormsModule, CommonModule],
  templateUrl: './reservation.component.html',
  styleUrl: './reservation.component.scss',
})
export class ReservationComponent implements OnInit {
  private fb = inject(FormBuilder);
  private reservationService = inject(ReservationService);
  private restaurantService = inject(RestaurantService);

  restaurants: Restaurant[] = [];
  myReservations: Reservation[] = [];
  loading = false;
  pageLoading = true;
  success = false;
  error = '';

  today = new Date().toISOString().split('T')[0];

  // Időpont-kezelés
  timeSlots: string[] = [];
  bookedSlots: string[] = [];
  selectedTime: string = '';

  form: FormGroup = this.fb.group({
    restaurant_id: [1, Validators.required],
    reservation_date: ['', Validators.required],
    reservation_time: ['', Validators.required],
    party_size: [2, [Validators.required, Validators.min(1), Validators.max(20)]],
    notes: [''],
  });

  ngOnInit(): void {
    this.generateTimeSlots();
    this.restaurantService.getAll().subscribe({ next: (r) => { this.restaurants = r; } });
    this.loadMyReservations();

    // Ha változik az étterem vagy a dátum, töltsük be a foglalt időpontokat
    this.form.get('restaurant_id')?.valueChanges.subscribe(() => this.loadBookedSlots());
    this.form.get('reservation_date')?.valueChanges.subscribe(() => this.loadBookedSlots());
  }

  generateTimeSlots(): void {
    this.timeSlots = [];
    for (let h = 11; h <= 21; h++) {
      this.timeSlots.push(`${h.toString().padStart(2, '0')}:00`);
      this.timeSlots.push(`${h.toString().padStart(2, '0')}:30`);
    }
  }

  loadBookedSlots(): void {
    const restaurantId = this.form.get('restaurant_id')?.value;
    const date = this.form.get('reservation_date')?.value;
    if (!restaurantId || !date) {
      this.bookedSlots = [];
      return;
    }

    this.reservationService.getBookedSlots(restaurantId, date).subscribe({
      next: (slots) => {
        this.bookedSlots = slots;
        // Ha a kiválasztott időpont foglalt, töröljük
        if (this.selectedTime && this.isBooked(this.selectedTime)) {
          this.selectedTime = '';
          this.form.patchValue({ reservation_time: '' });
        }
      },
      error: () => { this.bookedSlots = []; }
    });
  }

  isBooked(time: string): boolean {
    return this.bookedSlots.includes(time);
  }

  selectTime(time: string): void {
    if (this.isBooked(time)) return;
    this.selectedTime = time;
    this.form.patchValue({ reservation_time: time });
  }

  loadMyReservations(): void {
    this.reservationService.getMyReservations().subscribe({
      next: (r) => { this.myReservations = r; this.pageLoading = false; },
      error: () => { this.pageLoading = false; }
    });
  }

  submit(): void {
    if (this.form.invalid) return;
    this.loading = true;
    this.error = '';

    this.reservationService.create(this.form.value).subscribe({
      next: () => {
        this.success = true;
        this.loading = false;
        this.selectedTime = '';
        // Megőrizzük az éttermet és dátumot, csak a time/notes/party_size resetelődik
        const keepRestaurant = this.form.get('restaurant_id')?.value;
        const keepDate = this.form.get('reservation_date')?.value;
        this.form.patchValue({ reservation_time: '', notes: '', party_size: 2 });
        // Újratöltjük a foglalt időpontokat, így az épp lefoglalt slot azonnal pirosra vált
        this.loadBookedSlots();
        this.loadMyReservations();
      },
      error: (err) => {
        this.error = err.error?.message || 'Foglalás sikertelen. Próbáld újra!';
        this.loading = false;
      }
    });
  }

  cancel(id: number): void {
    if (!confirm('Biztosan törölni szeretnéd a foglalást?')) return;
    this.reservationService.cancel(id).subscribe({
      next: () => {
        this.loadMyReservations();
        this.loadBookedSlots();
      }
    });
  }

  statusLabel(status: string): string {
    const map: Record<string, string> = {
      pending: 'Várakozó', confirmed: 'Megerősítve',
      cancelled: 'Törölve', completed: 'Teljesítve'
    };
    return map[status] || status;
  }
}
