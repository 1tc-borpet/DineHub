# DineHub Frontend Dokumentáció

## 1. Áttekintés

A **DineHub** frontend egy **Angular** alapú modern single-page alkalmazás , amely lehetővé teszi a felhasználók számára az éttermek böngészését, ételrendeléseket leadását, asztalfoglalásokat végzését és a teljes rendelési folyamat kezelését. Az alkalmazás **standalone komponenseket** használ az Angular legújabb architektúrájával megvalósított.

**Fő modulok:**
- **Autentifikáció** (login, register, token-alapú authentikáció)
- **Éttermi katalógus** (éttermi lista, részletes adatok)
- **Menü kezelés** (ételek böngészése, szűrés)
- **Kosár** (ételek hozzáadása, mennyiség módosítása)
- **Checkout** (rendelés feldolgozása)
- **Rendelések** (aktív és korábbi rendelések nyomon követése)
- **Fizetési feldolgozás** (payment integráció)
- **Foglalások** (asztalfoglalási rendszer)
- **Profil kezelés** (felhasználói információk)
- **Admin panel** (étteremkezelés, rendeléskezelés, foglaláskezelés)

---

## 2. Technológiai Stack

| Technológia | Verzió | Felhasználás |
|---|---|---|
| **Angular** | ^20.2.0 | Frontend framework |
| **TypeScript** | - | Programozási nyelv |
| **CSS** | - | Stílusozás |

---

## 2. Projekt Struktúra

```
frontend/
├── src/
│   ├── app/
│   │   ├── app.config.ts              
│   │   ├── app.routes.ts              
│   │   ├── app.html                   
│   │   ├── app.ts                     
│   │   ├── app.scss                   
│   │   │
│   │   ├── guards/                    
│   │   │   ├── auth.guard.ts          
│   │   │   └── admin.guard.ts         
│   │   │
│   │   ├── interceptors/              
│   │   │   └── auth.interceptor.ts    
│   │   │
│   │   ├── models/                    
│   │   │   ├── api-response.model.ts  
│   │   │   ├── user.model.ts          
│   │   │   ├── restaurant.model.ts    
│   │   │   ├── menu.model.ts          
│   │   │   ├── order.model.ts         
│   │   │   ├── payment.model.ts       
│   │   │   ├── reservation.model.ts   
│   │   │   └── index.ts               
│   │   │
│   │   ├── services/                  
│   │   │   ├── auth.service.ts        
│   │   │   ├── restaurant.service.ts  
│   │   │   ├── menu.service.ts        
│   │   │   ├── cart.service.ts        
│   │   │   ├── order.service.ts       
│   │   │   ├── payment.service.ts     
│   │   │   ├── reservation.service.ts
│   │   │   └── dashboard.service.ts   
│   │   │
│   │   ├── pages/                     
│   │   │   ├── auth/
│   │   │   │   ├── login/
│   │   │   │   │   ├── login.component.ts
│   │   │   │   │   ├── login.component.html
│   │   │   │   │   └── login.component.scss
│   │   │   │   └── register/
│   │   │   │       ├── register.component.ts
│   │   │   │       ├── register.component.html
│   │   │   │       └── register.component.scss
│   │   │   ├── home/
│   │   │   │   ├── home.component.ts
│   │   │   │   ├── home.component.html
│   │   │   │   └── home.component.scss
│   │   │   ├── menu/
│   │   │   │   ├── menu.component.ts
│   │   │   │   ├── menu.component.html
│   │   │   │   └── menu.component.scss
│   │   │   ├── cart/
│   │   │   │   ├── cart.component.ts
│   │   │   │   ├── cart.component.html
│   │   │   │   └── cart.component.scss
│   │   │   ├── checkout/
│   │   │   │   ├── checkout.component.ts
│   │   │   │   ├── checkout.component.html
│   │   │   │   └── checkout.component.scss
│   │   │   ├── orders/
│   │   │   │   ├── orders.component.ts
│   │   │   │   ├── orders.component.html
│   │   │   │   └── orders.component.scss
│   │   │   ├── payment/
│   │   │   │   ├── payment.component.ts
│   │   │   │   ├── payment.component.html
│   │   │   │   └── payment.component.scss
│   │   │   ├── profile/
│   │   │   │   ├── profile.component.ts
│   │   │   │   ├── profile.component.html
│   │   │   │   └── profile.component.scss
│   │   │   ├── reservation/
│   │   │   │   ├── reservation.component.ts
│   │   │   │   ├── reservation.component.html
│   │   │   │   └── reservation.component.scss
│   │   │   └── admin/
│   │   │       ├── dashboard/
│   │   │       │   ├── dashboard.component.ts
│   │   │       │   ├── dashboard.component.html
│   │   │       │   └── dashboard.component.scss
│   │   │       ├── menu-management/
│   │   │       │   ├── menu-management.component.ts
│   │   │       │   ├── menu-management.component.html
│   │   │       │   └── menu-management.component.scss
│   │   │       ├── orders/
│   │   │       │   ├── admin-orders.component.ts
│   │   │       │   ├── admin-orders.component.html
│   │   │       │   └── admin-orders.component.scss
│   │   │       └── reservations/
│   │   │           ├── admin-reservations.component.ts
│   │   │           ├── admin-reservations.component.html
│   │   │           └── admin-reservations.component.scss
│   │   │
│   │   └── shared/                    
│   │       └── components/
│   │           ├── navbar.component.ts
│   │           ├── navbar.component.html
│   │           └── navbar.component.scss
│   │
│   ├── environments/                  
│   │   ├── environment.ts            
│   │   └── environment.prod.ts        
│   │
│   ├── styles.scss                    
│   ├── index.html                     
│   └── main.ts                        
│
├── angular.json                       
├── tsconfig.json                     
├── tsconfig.spec.json                 
├── tsconfig.app.json                  
├── package.json                       
├── karma.conf.js                      
└── README.md                          
```

---

## 3. Komponenseк Áttekintése

### 3.1. Auth Komponenseк (Autentifikáció)

#### LoginComponent
**Útvonal:** `auth/login`  
**Mit tartalmaz:**
- Email és jelszó input mezők
- Bejelentkezési logika (AuthService.login)
- Token kezelés és tárolás
- Hibakezelés (érvénytelen hitelesítés)
- Regisztráció linkje

**Megjelenik ha:** Felhasználó nincs bejelentkezve

#### RegisterComponent
**Útvonal:** `auth/register`  
**Mit tartalmaz:**
- Regisztrációs form (email, jelszó, jelszó megerősítés)
- Felhasználói adatok (név, telefon)
- Form validálás
- Email duplikáció ellenőrzése
- Bejelentkezés linkje

**Megjelenik ha:** Felhasználó nincs bejelentkezve

---

### 3.2. Home Komponens

#### HomeComponent
**Útvonal:** `/` (root)  
**Mit tartalmaz:**
- Éttermi lista megjelenítése
- Keresési/szűrési lehetőség
- Éttermi kártyák (kép, név, értékelés, szállítási idő)
- Kategória szűrés
- Kedvencek kezelés

**Megjelenik ha:** Mindenki számára elérhető

---

### 3.3. Menu Komponens

#### MenuComponent
**Útvonal:** `/menu` (összes étterem) vagy `/menu/:restaurantId` (specifikus étterem)  
**Mit tartalmaz:**
- Éttermi adatok (név, cím, nyitvatartás, értékelés)
- Menü kategóriák lapfülei
- Ételek listázása kategóriánként
- Étel adatok (kép, név, ár, leírás)
- "Kosárhoz hozzáadás" gomb

**Megjelenik ha:** Mindenki számára elérhető

---

### 3.4. Cart Komponens (Kosár)

#### CartComponent
**Útvonal:** `/cart`  
**Mit tartalmaz:**
- Kosár tételek listázása
- Mennyiség módosítása (+ / - gombok)
- Tétel eltávolítása
- Étterem szerinti csoportosítás
- Összesen ár kalkuláció
- "Checkout" gomb
- Üres kosár üzenet

**Megjelenik ha:** Bárki elérhet, de tartalom csak érvénytelen rendeléskeztől

**Védelme:** Nincs (de checkout-nál auth szükséges)

---

### 3.5. Checkout Komponens

#### CheckoutComponent
**Útvonal:** `/checkout`  
**Mit tartalmaz:**
- Szállítási cím megadása
- Szállítási módok (házhozszállítás, elvétel, étteremben)
- Rendelési megjegyzések
- Összefoglaló (tételek, összesen ár)
- "Rendelés leadása" gomb

**Megjelenik ha:** Hitelesített felhasználók  
**Védelme:** authGuard (bejelentkezés szükséges)

---

### 3.6. Orders Komponens (Rendelések)

#### OrdersComponent
**Útvonal:** `/orders`  
**Mit tartalmaz:**
- Saját rendelések listázása
- Rendelés szűrés (státusz alapján: aktív, befejezett, törölt)
- Rendelés adatok (étterem, dátum, ár, státusz)
- Rendelés státusza (pending, confirmed, preparing, ready, delivered, cancelled)
- Rendelés részletei linkje

**Megjelenik ha:** Hitelesített felhasználók  
**Védelme:** authGuard

---

### 3.7. Payment Komponens (Fizetés)

#### PaymentComponent
**Útvonal:** `/payment/:orderId`  
**Mit tartalmaz:**
- Fizetési módok kiválasztása
- Fizetési adatok megadása (kártyaszám, lejárati dátum, CVC)
- Fizetési feldolgozás
- Siker/hiba üzenetek
- Megrendelés száma

**Megjelenik ha:** Hitelesített felhasználók  
**Védelme:** authGuard

---

### 3.8. Profile Komponens (Profil)

#### ProfileComponent
**Útvonal:** `/profile`  
**Mit tartalmaz:**
- Felhasználói adatok megjelenítése (név, email, telefon, cím)
- Profil adatok szerkesztése
- Jelszó módosítása
- Kijelentkezés gomb

**Megjelenik ha:** Hitelesített felhasználók  
**Védelme:** authGuard

---

### 3.9. Reservation Komponens (Foglalás)

#### ReservationComponent
**Útvonal:** `/reservation`  
**Mit tartalmaz:**
- Étterem kiválasztása
- Dátum és idő kiválasztása
- Vendégszám kiválasztása
- Asztal kiválasztása (ha elérhető)
- Speciális kérések
- "Foglalás megerősítése" gomb
- Korábbi foglalások listázása

**Megjelenik ha:** Hitelesített felhasználók  
**Védelme:** authGuard

---

### 3.10. Admin Komponenseк

#### DashboardComponent (Admin)
**Útvonal:** `/admin` vagy `/admin/dashboard`  
**Mit tartalmaz:**
- Étterem statisztikái (mai rendelések, jövedelem)
- Grafikonok és diagramok
- Legutóbbi rendelések listája
- Ma érkező foglalások
- Havi összesítés

**Megjelenik ha:** Admin felhasználók  
**Védelme:** adminGuard

#### MenuManagementComponent (Admin)
**Útvonal:** `/admin/menu`  
**Mit tartalmaz:**
- Menü kategóriák kezelése (CREATE, READ, UPDATE, DELETE)
- Ételek kezelése (CRUD)
- Étel kép feltöltése
- Étel ár módosítása
- Étel leírásának szerkesztése
- Elérhetőség módosítása

**Megjelenik ha:** Admin felhasználók  
**Védelme:** adminGuard

#### AdminOrdersComponent (Admin)
**Útvonal:** `/admin/orders`  
**Mit tartalmaz:**
- Összes rendelés listázása
- Státusz módosítása
- Rendelés részletei
- Szűrés (dátum, státusz, ügyfél)
- Nyomtatás lehetőség

**Megjelenik ha:** Admin felhasználók  
**Védelme:** adminGuard

#### AdminReservationsComponent (Admin)
**Útvonal:** `/admin/reservations`  
**Mit tartalmaz:**
- Összes foglalás listázása
- Foglalás részletei
- Újabb foglalások listázása
- Zárolt asztalok kezelése
- Szűrés (dátum, asztal, vendégszám)

**Megjelenik ha:** Admin felhasználók  
**Védelme:** adminGuard

---

### 3.11. Shared Komponenseк (Megosztott)

#### NavbarComponent
**Helyezkedik el:** Az App.ts (minden oldal fejléc)  
**Mit tartalmaz:**
- DineHub logó
- Keresőmező (éttermi keresés)
- Felhasználó menü (login, logout, profil)
- Kosár ikon (tétel szám kijelzésével)
- Admin link (admin felhasználók számára)
- Mobilmenü (hamburger menu)

---

## 4. Szervizeк (Services)

### 4.1. AuthService
**Célja:** Felhasználó autentifikáció kezelése

**Mit tartalmaz:**
- **login()** - Bejelentkezés email/jelszóval
- **register()** - Regisztráció
- **logout()** - Kijelentkezés
- **isLoggedIn()** - Bejelentkezési státusz ellenőrzése
- **getCurrentUser()** - Aktuális felhasználó adatai
- **getToken()** - Tárolt token lekérése
- **Token management** - localStorage-ben tárol (TOKEN_KEY)

**Fontos:** `currentUser` és `isLoggedIn` Signal-okként implementálva az automatikus UI frissítéshez

---

### 4.2. RestaurantService
**Célja:** Éttermi adatok - fetch

**Mit tartalmaz:**
- **getAll()** - Összes étterem listázása
- **getById(id)** - Specifikus étterem részletei
- **search(query)** - Éttermi keresés

---

### 4.3. MenuService
**Célja:** Menü és ételek kezelése

**Mit tartalmaz:**
- **getByRestaurant(restaurantId)** - Étterem menüje
- **getCategory(categoryId)** - Specifikus kategória
- **getItems(categoryId)** - Kategória ételei
- **addItem()** - Ételem hozzáadása (admin)
- **updateItem()** - Étel módosítása (admin)
- **deleteItem()** - Étel törlése (admin)

---

### 4.4. CartService
**Célja:** Kosár állapot kezelése (localStorage alapú)

**Mit tartalmaz:**
- **getCart()** - Kosár tartalmának lekérése
- **addItem(itemId, quantity)** - Tétel hozzáadása
- **updateQuantity(itemId, quantity)** - Mennyiség módosítása
- **removeItem(itemId)** - Tétel eltávolítása
- **clearCart()** - Teljes kosár törlése
- **getTotal()** - Összesen ár kalkuláció

**Megjegyzés:** Lokális tárulás (localStorage) az állapotkezeléshez

---

### 4.5. OrderService
**Célja:** Rendelések kezelése

**Mit tartalmaz:**
- **getAll()** - Összes rendelés (admin)
- **getById(orderId)** - Specifikus rendelés adatai
- **create(orderData)** - Rendelés létrehozása
- **getUserOrders()** - Felhasználó saját rendelései
- **updateStatus(orderId, status)** - Státusz módosítása (admin)
- **getStatus(orderId)** - Rendelés státusz lekérése

**Státuszok:** pending, confirmed, preparing, ready, delivered, cancelled

---

### 4.6. PaymentService
**Célja:** Fizetési feldolgozás

**Mit tartalmaz:**
- **processPayment(paymentData)** - Fizetés feldolgozása
- **getPaymentHistory(userId)** - Fizetési történet

---

### 4.7. ReservationService
**Célja:** Asztalfoglalások kezelése

**Mit tartalmaz:**
- **getAll()** - Összes foglalás (admin)
- **getById(reservationId)** - Speccifikus foglalás adatai
- **create(reservationData)** - Foglalás létrehozása
- **getUserReservations(userId)** - Felhasználó saját foglalásai
- **update(reservationId, data)** - Foglalás módosítása
- **delete(reservationId)** - Foglalás törlése
- **checkAvailability(restaurantId, date, time)** - Asztal eléérhetőség ellenőrzése

---

### 4.8. DashboardService
**Célja:** Admin dashboard adatok - fetch

**Mit tartalmaz:**
- **getStatistics()** - Alapstatisztikák (rendelések, jövedelem)
- **getRecentOrders(limit)** - Legutóbbi rendelések
- **getTodayReservations()** - Mai foglalások
- **getSalesChart()** - Eladási grafikonok adatai

---

## 5. Guards (Útvonal Protekcióк)

### 5.1. AuthGuard
**Célja:** Bejelentkezés szükséges útvonalak védelme

**Mit tesz:**
- Ellenőrzi: `AuthService.isLoggedIn()` 
- Ha nincs bejelentkezve: átirányít `/auth/login`-re
- Ha bejelentkezve: engedélyezi az útvonal访问

**Felhasználása:**
```typescript
{
  path: 'checkout',
  loadComponent: () => import('./pages/checkout/checkout.component'),
  canActivate: [authGuard]
}
```

---

### 5.2. AdminGuard
**Célja:** Admin felhasználók számára fenntartott útvonalak védelme

**Mit tesz:**
- Ellenőrzi: `AuthService.isLoggedIn()` 
- Ellenőrzi: az aktuális felhasználó admin jogai
- Ha nem admin: átirányít `/` (home) oldalra
- Ha admin: engedélyezi az útvonal elérhetőségét

**Felhasználása:**
```typescript
{
  path: 'admin',
  canActivate: [adminGuard],
  children: [
    { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
    { path: 'dashboard', loadComponent: () => ... },
  ]
}
```

---

## 6. Interceptors (HTTP Közvetítők)

### 6.1. AuthInterceptor
**Célja:** Autentifikációs token automatikus hozzáadása HTTP kérésekhez

**Mit tesz:**
- Lekéri az aktuális tokent: `AuthService.getToken()`
- Hozzáadja az `Authorization: Bearer {token}` header-t
- Ha 401 (Unauthorized) hiba: `AuthService.logout()`
- Az összes HTTP requestre automatikusan alkalmazódik

**Regisztráció:** App.config.ts-ben definiálva

---
---

## 7. Routing (Útvonalak)

**Fájl:** `src/app/app.routes.ts`  
**Megvalósítás:** Standalone routing (lazy loading)

### 7.1. Nyilvános Útvonalak (Auth nélkül elérhető)

| Útvonal | Komponens | Védelem |
|---|---|---|
| `/` | HomeComponent | Nincs |
| `/menu` | MenuComponent | Nincs |
| `/menu/:restaurantId` | MenuComponent | Nincs |
| `/auth/login` | LoginComponent | Nincs |
| `/auth/register` | RegisterComponent | Nincs |

### 7.2. Autentifikált Útvonalak (Auth szükséges)

| Útvonal | Komponens | Védelem |
|---|---|---|
| `/cart` | CartComponent | authGuard |
| `/checkout` | CheckoutComponent | authGuard |
| `/orders` | OrdersComponent | authGuard |
| `/payment/:orderId` | PaymentComponent | authGuard |
| `/reservation` | ReservationComponent | authGuard |
| `/profile` | ProfileComponent | authGuard |

### 7.3. Admin Útvonalak (Admin jog szükséges)

| Útvonal | Komponens | Védelem |
|---|---|---|
| `/admin` | AdminDashboardComponent (redirect alapértelmezetten) | adminGuard |
| `/admin/dashboard` | DashboardComponent | adminGuard |
| `/admin/menu` | MenuManagementComponent | adminGuard |
| `/admin/orders` | AdminOrdersComponent | adminGuard |
| `/admin/reservations` | AdminReservationsComponent | adminGuard |

---

## 8. HTTP API Kommunikáció

### 8.1. API Alapcím

**Fájl:** `src/environments/`

```typescript
export const environment = {
  production: false,
  apiUrl: 'http://localhost:8000/api'
};

// environment.prod.ts (Production)
export const environment = {
  production: true,
  apiUrl: 'https://api.dinehub.com/api'
};
```

### 8.2. Típikus HTTP Hívások

#### Bejelentkezés
```
POST /auth/login
{
  "email": "user@example.com",
  "password": "password123"
}
Response:
{
  "token": "eyJ0eXAi...",
  "user": { id, name, email, is_admin, ... }
}
```

#### Kijelentkezés
```
POST /auth/logout
Header: Authorization: Bearer {token}
```

#### Regisztráció
```
POST /auth/register
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Éttermek listázása
```
GET /restaurants
Response: Restaurant[]
```

#### Specifikus étterem
```
GET /restaurants/:id
Response: Restaurant
```

#### Menü lekérése
```
GET /restaurants/:restaurantId/menu
Response: MenuCategory[]
```

#### Kosárhoz tétel hozzáadása
```
POST /cart
{
  "menu_item_id": 5,
  "quantity": 2
}
Header: Authorization: Bearer {token}
```

#### Rendelés létrehozása
```
POST /orders
{
  "delivery_type": "delivery",
  "delivery_address": "123 Main St, City",
  "notes": "No onions please"
}
Header: Authorization: Bearer {token}
Response: Order
```

#### Rendelések lekérése
```
GET /orders
Header: Authorization: Bearer {token}
Response: Order[]
```

#### Foglalás létrehozása
```
POST /reservations
{
  "restaurant_id": 1,
  "reservation_date": "2026-04-15",
  "reservation_time": "19:30",
  "party_size": 4,
  "special_requests": "Highchair needed"
}
Header: Authorization: Bearer {token}
Response: Reservation
```

#### Fizetés feldolgozása
```
POST /orders/:orderId/payment
{
  "payment_method": "card",
  "card_number": "4111111111111111",
  "exp_month": 12,
  "exp_year": 2026,
  "cvc": "123"
}
Header: Authorization: Bearer {token}
Response: Payment
```

---


### 9. LocalStorage

A **Cart** és **Auth token** az `localStorage`-ben tárolódnak:
---

## 10. Autentifikáció Menete

### 10.1. Bejelentkezési Folyamat

1. Felhasználó megy a `/auth/login` oldalra
2. Beírja email + jelszó
3. Form submit → `AuthService.login(credentials)`
4. Backend válaszol JWT token-nel
5. Token mentése: `localStorage.setItem('auth_token', token)`
6. `AuthService.isLoggedIn()` = true
7. `AuthService.currentUser` frissítése
8. Redirect `/` (home) vagy előző oldal

### 10.2. Token Automatikus Küldése

Az `AuthInterceptor` automatikusan:
- Lekéri a token-t az `AuthService`-ből
- Hozzáadja az `Authorization: Bearer {token}` header-t
- Az összes HTTP kérésre alkalmazódik
---

## 11. State Kezelés (Kosár Adatok)

### 11.1. Kosár Tárulás

```typescript
getCart(): Observable<Cart> {
  return this.http.get<Cart>('/cart');
}

cart$ = this.cartService.getCart();
```

### 11.2. Kosár Szinkronizálás

- Kezdetben: backend-ből fetch
- Módosítás: POST/PUT → backend
---

**Készítette:** Boros Péter, Morzsa Milán Dominik  
**Készítve:** 2026. március 31.  