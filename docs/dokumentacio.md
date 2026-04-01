# DineHub – Programozói Dokumentáció

---

## 1. Áttekintés

A **DineHub** backend egy **Laravel** alapú restaurant-kezelő és ételrendelő rendszer, amely lehetővé teszi az éttermi műveletek kezelését, ételrendeléseket feldolgozását és asztalfoglalásokat kezelését. A rendszer két szerepkört támogat: **admin** (étterem tulajdonos) és **user** (vásárló), eltérő jogosultságokkal.

A **DineHub** frontend egy **Angular** alapú modern single-page alkalmazás , amely lehetővé teszi a felhasználók számára az éttermek böngészését, ételrendeléseket leadását, asztalfoglalásokat végzését és a teljes rendelési folyamat kezelését. Az alkalmazás **standalone komponenseket** használ az Angular legújabb architektúrájával megvalósított.

**Backend főbb funkcionalitások:**
- Felhasználók kezelése és autentifikáció (regisztráció, bejelentkezés)
- Éttermek kezelése és katalogizálása
- Étlapok és menükategóriák szerkezete
- Ételrendelések CRUD kezelése
- Asztalfoglalások
- Fizetési feldolgozás és integráció
- Kosár kezelés (több étteremből)
- Soft delete mechanizmus az adatok helyreállítása érdekében
- Admin webes felület Blade sablonok használatával
- REST API autentifikáció (Laravel Sanctum)

**Frontend fő modulok:**
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

### Backend

| Technológia | Verzió | Felhasználás |
|---|---|---|
| **PHP** | ^8.2 | Backend nyelvezet |
| **Laravel** | ^12.0 | Web framework API autentifikáció és token kezelés |
| **MySQL** | - | Adatbázis tárrolás |

### Frontend

| Technológia | Verzió | Felhasználás |
|---|---|---|
| **Angular** | ^20.2.0 | Frontend framework |
| **TypeScript** | - | Programozási nyelv |
| **CSS** | - | Stílusozás |

---

## 3. Projekt Struktúra

### 3.1. Backend Struktúra

```
backend/vizsgaremek/
├── app/
│   ├── Http/
│   │   ├── Controllers/     
│   │   │   ├── AuthController.php
│   │   │   ├── RestaurantController.php
│   │   │   ├── MenuCategoryController.php
│   │   │   ├── MenuItemController.php
│   │   │   ├── OrderController.php
│   │   │   ├── CartController.php
│   │   │   ├── ReservationController.php
│   │   │   ├── PaymentController.php
│   │   │   └── AdminController.php
│   │   ├── Middleware/     
│   │   └── Requests/        
│   ├── Models/
│      ├── User.php
│      ├── Restaurant.php
│      ├── MenuCategory.php
│      ├── MenuItem.php
│      ├── Order.php
│      ├── OrderItem.php
│      ├── Cart.php
│      ├── CartItem.php
│      ├── Reservation.php
│      ├── RestaurantTable.php
│      └── Payment.php
|
├── database/
│   ├── migrations/          
│   ├── factories/
│   └── seeders/
├── routes/
│   ├── api.php             
│   └── web.php              
├── resources/
│   └── views/               
├── tests/
│   ├── Feature/             
│   ├── Unit/                
│   └── postman-collection.json
└── storage/
    └── logs/
```

### 3.2. Frontend Struktúra

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

## 4. Adatbázis Struktúra

### 4.1. Users tábla

A rendszer felhasználóit tartalmazza. Tartalmaz admin (restaurant owner) és normál felhasználókat (vásárlókat) is.

**Mit tartalmaz:**
- Felhasználó azonosítás (name, email)
- Jelszó kezelés (password - titkosított)
- Felhasználó típusa (role: user, restaurant_owner, admin)
- Felhasználó adatok (phone, avatar_url)
- Aktív státusz (is_active flag)
- Soft delete támogatás (deleted_at)

---

### 4.2. Restaurants tábla

Az étterem adatokat tartalmazza a platformon regisztrált éttermeket.

**Mit tartalmaz:**
- Étterem azonosítása (name, location, phone)
- Nyitvatartási adatok (opening_hours, closing_hours)
- Étterem képe és leírása (image_url, description)
- Éttermek értékelése (rating)
- Aktív státusz (is_active)
- Soft delete támogatás (deleted_at)

---

### 4.3. MenuCategory tábla

Az étterem menükategóriáit tartalmazza (pl. előételek, főételek, desszert).

**Mit tartalmaz:**
- Kategória azonosítása (name, description)
- Étterem kapcsolat (restaurant_id)
- Sorrend megjelölés (position)
- Soft delete támogatás (deleted_at)

---

### 4.4. MenuItem tábla

Az étterem ételeit reprezentálja a menükben.

**Mit tartalmaz:**
- Étel azonosítása (name, description)
- Ár információ (price)
- Étel képe (image_url)
- Kategória és étterem kapcsolat (menu_category_id, restaurant_id)
- Elérhetőség (is_available)
- Sorrend (position)
- Soft delete támogatás (deleted_at)

---

### 4.5. Orders tábla

A felhasználók által teljesített rendeléseket tartalmazza.

**Mit tartalmaz:**
- Rendelés azonosítása (id, user_id, restaurant_id)
- Rendelés státusza (status: pending, confirmed, preparing, ready, completed, cancelled)
- Pénzügyi adatok (total_amount)
- Szállítási információ (delivery_type, delivery_address, estimated_time)
- Megjegyzés (note)
- Soft delete támogatás (deleted_at)

---

### 4.6. OrderItem tábla

A rendelés egyes tételeit tartalmazza.

**Mit tartalmaz:**
- Tételazonosítás (order_id, menu_item_id)
- Mennyiség (quantity)
- Az adott időpontban érvényes ár (price)
- Soft delete támogatás (deleted_at)

---

### 4.7. Cart tábla

A felhasználó aktív kosarát reprezentálja.

**Mit tartalmaz:**
- Kosár azonosítása (id, user_id)
- Létrehozási és módosítási adatok (timestamps)

---

### 4.8. CartItem tábla

A kosár egyes tételeit tartalmazza.

**Mit tartalmaz:**
- Tétel azonosítása (menu_item_id, restaurant_id)
- Kosár kapcsolat (cart_id)
- Mennyiség (quantity)
- Soft delete támogatás (deleted_at)

---

### 4.9. Reservations tábla

Az asztal foglalásokat tartalmazza.

**Mit tartalmaz:**
- Foglalás azonosítása (user_id, restaurant_id, restaurant_table_id)
- Foglalás időpontjai (reservation_date, reservation_time)
- Vendégek száma (party_size)
- Foglalás státusza (status: pending, confirmed, cancelled, completed)
- Speciális kérések (special_requests)
- Soft delete támogatás (deleted_at)

---

### 4.10. RestaurantTable tábla

Az étterem asztalait reprezentálja.

**Mit tartalmaz:**
- Asztal azonosítása (table_number, capacity)
- Étterem kapcsolat (restaurant_id)
- Elérhetőség (is_available)
- Soft delete támogatás (deleted_at)

---

### 4.11. Payments tábla

A rendelésekhez tartozó fizetéseket tartalmazza.

**Mit tartalmaz:**
- Fizetés azonosítása (order_id, amount)
- Fizetési mód (payment_method: credit_card, debit_card, paypal, cash)
- Fizetés státusza (status: pending, completed, failed, refunded)
- Tranzakció azonosítása (transaction_id)
- Soft delete támogatás (deleted_at)

---

## 5. Jogosultságkezelés

### 5.1. Admin Szerepkör

Az admin (étterem tulajdonos) felhasználók a következő jogosultságokkal rendelkeznek:

- **Felhasználók kezelése** - Felhasználók listázása, kezelése
- **Éttermek kezelése** - Saját étterme adatainak módosítása
- **Menükategóriák** - Étterem menükategóriáinak CRUD kezelése
- **Ételek kezelése** - Étterem ételinek (menüpontok) CRUD kezelése
- **Rendelések kezelése** - Megpróbálhat és feldolgozni rendeléseket
- **Asztalok kezelése** - Éttermi asztalok és foglalások kezelése
- **Admin webes felület** - A Blade alapú admin felület elérése

### 5.2. User Szerepkör (Vásárló)

A normál felhasználók (vásárlók) a következő jogosultságokkal rendelkeznek:

- **Saját profil** - Saját adatok megtekintése és módosítása
- **Éttermek böngészése** - Nyilvános éttermi katalógus megtekintése
- **Étlapok megtekintése** - Éttermek menüinak megtekintése
- **Kosár kezelés** - Tételek hozzáadása/eltávolítása a kosárból
- **Rendeléseket** - Saját rendeléseit megtekintése és kezelése
- **Asztalfoglalás** - Asztal foglalása az étteremben
- **Fizetés feldolgozása** - Rendelések fizetése

### 5.3. Jogosultságkezelés Megvalósítása

A jogosultságkezelés megvalósítható:
- **Middleware** segítségével (IsAdmin, IsRestaurantOwner middleware)
- **Controller-ben** if ellenőrzésekkel
- **Gate/Policy** Laravel mechanizmussal

---

## 6. CRUD és Soft Delete Szabályok

### 6.1. User Törlés

Amikor egy felhasználó törlésre kerül:
- A **users** táblában **soft delete** történik (deleted_at értéket kap)
- A felhasználóhoz tartozó **orders** és **reservations** rekordok is **soft delete**-re kerülnek
- A felhasználó kosara és tételei is **soft delete**-re kerülnek

### 6.2. Restaurant Törlés

Amikor egy étterem törlésre kerül:
- A **restaurants** táblában **soft delete** történik (deleted_at értéket kap)
- Az étteremhez tartozó összes **menu_items**, **orders**, **reservations** soft delete-re kerülnek
- A **restaurant_tables** szintén soft delete-re kerülnek

### 6.3. MenuItem Törlés

Amikor egy étel törlésre kerül:
- A **menu_items** táblában **soft delete** történik (deleted_at értéket kap)
- Az étel a kosárban és a rendelésekben is jelölésre kerül

### 6.4. Order Törlés

Amikor egy rendelés törlésre kerül:
- A **orders** és kapcsolódó **order_items** soft delete-re kerülnek
- A fizetési információk is soft delete-re kerülnek

### 6.5. Reservation Törlés

Amikor egy foglalás törlésre kerül:
- A **reservations** táblában **soft delete** történik (deleted_at értéket kap)

---

## 7. API Funkciók

### 7.1. Autentifikáció

#### Register (Regisztráció)
- **Végpont:** `POST /api/auth/register`
- **Mit tesz:** Új felhasználót hoz létre (vásárló vagy étterem tulajdonos)
- **Folyamat:**
  - Felhasználó adatok validálása
  - Jelszó titkosítása
  - Válasz: user adatok

#### Login (Bejelentkezés)
- **Végpont:** `POST /api/auth/login`
- **Mit tesz:** Felhasználót bejelentkezteti
- **Folyamat:**
  - API token generálása (Sanctum token)
  - Válasz: user adatok és token

#### Logout (Kijelentkezés)
- **Végpont:** `POST /api/auth/logout`
- **Mit tesz:** Felhasználó tokenjét invalidálja

---

### 7.2. Restaurants (Éttermek)

#### List Restaurants
- **Végpont:** `GET /api/restaurants`
- **Jogosultság:** Nyilvános
- **Mit tesz:** Összes aktív éttermet listázza

#### Create Restaurant
- **Végpont:** `POST /api/restaurants`
- **Jogosultság:** Autentifikált admin
- **Mit tesz:** Új éttermet hoz létre

#### Show Restaurant
- **Végpont:** `GET /api/restaurants/{id}`
- **Jogosultság:** Nyilvános
- **Mit tesz:** Étterem adatait mutatja

#### Update Restaurant
- **Végpont:** `PATCH /api/restaurants/{id}`
- **Jogosultság:** Az étterem adminja
- **Mit tesz:** Étterem adatait módosítja

#### Delete Restaurant (Soft Delete)
- **Végpont:** `DELETE /api/restaurants/{id}`
- **Jogosultság:** Az étterem adminja
- **Mit tesz:** Éttermet soft delete-re jelöli

---

### 7.3. Menu Categories (Menükategóriák)

#### List Categories
- **Végpont:** `GET /api/restaurants/{id}/categories`
- **Jogosultság:** Nyilvános
- **Mit tesz:** Étterem kategóriáit listázza

#### Create Category
- **Végpont:** `POST /api/menu-categories`
- **Jogosultság:** Az étterem adminja
- **Mit tesz:** Új kategóriát hoz létre

#### Update Category
- **Végpont:** `PATCH /api/menu-categories/{id}`
- **Jogosultság:** Az étterem adminja
- **Mit tesz:** Kategóriát módosítja

#### Delete Category
- **Végpont:** `DELETE /api/menu-categories/{id}`
- **Jogosultság:** Az étterem adminja
- **Mit tesz:** Kategóriát soft delete-re jelöli

---

### 7.4. Menu Items (Ételek)

#### List Menu Items
- **Végpont:** `GET /api/categories/{id}/items`
- **Jogosultság:** Nyilvános
- **Mit tesz:** Kategória ételeit listázza

#### Create Menu Item
- **Végpont:** `POST /api/menu-items`
- **Jogosultság:** Az étterem adminja
- **Mit tesz:** Új ételöt hoz létre

#### Show Menu Item
- **Végpont:** `GET /api/menu-items/{id}`
- **Jogosultság:** Nyilvános
- **Mit tesz:** Étel adatait mutatja

#### Update Menu Item
- **Végpont:** `PATCH /api/menu-items/{id}`
- **Jogosultság:** Az étterem adminja
- **Mit tesz:** Étel adatait módosítja

#### Delete Menu Item
- **Végpont:** `DELETE /api/menu-items/{id}`
- **Jogosultság:** Az étterem adminja
- **Mit tesz:** Ételt soft delete-re jelöli

---

### 7.5. Orders (Rendelések)

#### List Orders
- **Végpont:** `GET /api/orders`
- **Jogosultság:** Autentifikált
- **Mit tesz:** 
  - Admin: összes rendelést
  - User: saját rendeléseit

#### Create Order
- **Végpont:** `POST /api/orders`
- **Jogosultság:** Autentifikált user
- **Mit tesz:** Új rendelést hoz létre

#### Show Order
- **Végpont:** `GET /api/orders/{id}`
- **Jogosultság:** A rendelés tulajdonosa vagy étterem adminja
- **Mit tesz:** Rendelés adatait mutatja

#### Update Order
- **Végpont:** `PATCH /api/orders/{id}`
- **Jogosultság:** Az étterem adminja (státusz módosítás)
- **Mit tesz:** Rendelés adatait módosítja

#### Delete Order
- **Végpont:** `DELETE /api/orders/{id}`
- **Jogosultság:** Az étterem adminja
- **Mit tesz:** Rendelést soft delete-re jelöli

---

### 7.6. Cart (Kosár)

#### Get Cart
- **Végpont:** `GET /api/cart`
- **Jogosultság:** Autentifikált
- **Mit tesz:** Felhasználó kosarát mutatja

#### Add to Cart
- **Végpont:** `POST /api/cart/items`
- **Jogosultság:** Autentifikált
- **Mit tesz:** Tételt hozzáadja a kosárhoz

#### Update Cart Item
- **Végpont:** `PATCH /api/cart/items/{id}`
- **Jogosultság:** Autentifikált
- **Mit tesz:** Kosár tételt módosítja (mennyiség)

#### Remove from Cart
- **Végpont:** `DELETE /api/cart/items/{id}`
- **Jogosultság:** Autentifikált
- **Mit tesz:** Tételt eltávolítja a kosárból

#### Clear Cart
- **Végpont:** `POST /api/cart/clear`
- **Jogosultság:** Autentifikált
- **Mit tesz:** Kosár tartalmát törli

---

### 7.7. Reservations (Asztalfoglalások)

#### Create Reservation
- **Végpont:** `POST /api/reservations`
- **Jogosultság:** Autentifikált
- **Mit tesz:** Új asztalfoglalást hoz létre

#### Get Reservations
- **Végpont:** `GET /api/reservations`
- **Jogosultság:** Autentifikált
- **Mit tesz:** 
  - Admin: étterme foglalásait
  - User: saját foglalásait

#### Update Reservation
- **Végpont:** `PATCH /api/reservations/{id}`
- **Jogosultság:** A foglalás tulajdonosa vagy étterem adminja
- **Mit tesz:** Foglalást módosítja

#### Delete Reservation
- **Végpont:** `DELETE /api/reservations/{id}`
- **Jogosultság:** A foglalás tulajdonosa vagy étterem adminja
- **Mit tesz:** Foglalást soft delete-re jelöli

---

### 7.8. Payments (Fizetések)

#### Create Payment
- **Végpont:** `POST /api/payments`
- **Jogosultság:** Autentifikált
- **Mit tesz:** Fizetést feldolgoz

#### Get Payment
- **Végpont:** `GET /api/payments/{id}`
- **Jogosultság:** A rendelés tulajdonosa vagy étterem adminja
- **Mit tesz:** Fizetés adatait mutatja

---

## 8. Frontend Komponensek Áttekintése

### 8.1. Auth Komponensek (Autentifikáció)

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

### 8.2. Home Komponens

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

### 8.3. Menu Komponens

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

### 8.4. Cart Komponens (Kosár)

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

### 8.5. Checkout Komponens

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

### 8.6. Orders Komponens (Rendelések)

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

### 8.7. Payment Komponens (Fizetés)

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

### 8.8. Profile Komponens (Profil)

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

### 8.9. Reservation Komponens (Foglalás)

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

### 8.10. Admin Komponensek

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

### 8.11. Shared Komponensek (Megosztott)

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

## 9. Frontend Szervizek (Services)

### 9.1. AuthService
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

### 9.2. RestaurantService
**Célja:** Éttermi adatok - fetch

**Mit tartalmaz:**
- **getAll()** - Összes étterem listázása
- **getById(id)** - Specifikus étterem részletei
- **search(query)** - Éttermi keresés

---

### 9.3. MenuService
**Célja:** Menü és ételek kezelése

**Mit tartalmaz:**
- **getByRestaurant(restaurantId)** - Étterem menüje
- **getCategory(categoryId)** - Specifikus kategória
- **getItems(categoryId)** - Kategória ételei
- **addItem()** - Ételem hozzáadása (admin)
- **updateItem()** - Étel módosítása (admin)
- **deleteItem()** - Étel törlése (admin)

---

### 9.4. CartService
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

### 9.5. OrderService
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

### 9.6. PaymentService
**Célja:** Fizetési feldolgozás

**Mit tartalmaz:**
- **processPayment(paymentData)** - Fizetés feldolgozása
- **getPaymentHistory(userId)** - Fizetési történet

---

### 9.7. ReservationService
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

### 9.8. DashboardService
**Célja:** Admin dashboard adatok - fetch

**Mit tartalmaz:**
- **getStatistics()** - Alapstatisztikák (rendelések, jövedelem)
- **getRecentOrders(limit)** - Legutóbbi rendelések
- **getTodayReservations()** - Mai foglalások
- **getSalesChart()** - Eladási grafikonok adatai

---

## 10. Guards (Útvonal Protekciók)

### 10.1. AuthGuard
**Célja:** Bejelentkezés szükséges útvonalak védelme

**Mit tesz:**
- Ellenőrzi: `AuthService.isLoggedIn()` 
- Ha nincs bejelentkezve: átirányít `/auth/login`-re
- Ha bejelentkezve: engedélyezi az útvonal elérését

**Felhasználása:**
```typescript
{
  path: 'checkout',
  loadComponent: () => import('./pages/checkout/checkout.component'),
  canActivate: [authGuard]
}
```

---

### 10.2. AdminGuard
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

## 11. Interceptors (HTTP Közvetítők)

### 11.1. AuthInterceptor
**Célja:** Autentifikációs token automatikus hozzáadása HTTP kérésekhez

**Mit tesz:**
- Lekéri az aktuális tokent: `AuthService.getToken()`
- Hozzáadja az `Authorization: Bearer {token}` header-t
- Ha 401 (Unauthorized) hiba: `AuthService.logout()`
- Az összes HTTP requestre automatikusan alkalmazódik

**Regisztráció:** App.config.ts-ben definiálva

---

## 12. Routing (Útvonalak)

**Fájl:** `src/app/app.routes.ts`  
**Megvalósítás:** Standalone routing (lazy loading)

### 12.1. Nyilvános Útvonalak (Auth nélkül elérhető)

| Útvonal | Komponens | Védelem |
|---|---|---|
| `/` | HomeComponent | Nincs |
| `/menu` | MenuComponent | Nincs |
| `/menu/:restaurantId` | MenuComponent | Nincs |
| `/auth/login` | LoginComponent | Nincs |
| `/auth/register` | RegisterComponent | Nincs |

### 12.2. Autentifikált Útvonalak (Auth szükséges)

| Útvonal | Komponens | Védelem |
|---|---|---|
| `/cart` | CartComponent | authGuard |
| `/checkout` | CheckoutComponent | authGuard |
| `/orders` | OrdersComponent | authGuard |
| `/payment/:orderId` | PaymentComponent | authGuard |
| `/reservation` | ReservationComponent | authGuard |
| `/profile` | ProfileComponent | authGuard |

### 12.3. Admin Útvonalak (Admin jog szükséges)

| Útvonal | Komponens | Védelem |
|---|---|---|
| `/admin` | AdminDashboardComponent (redirect alapértelmezetten) | adminGuard |
| `/admin/dashboard` | DashboardComponent | adminGuard |
| `/admin/menu` | MenuManagementComponent | adminGuard |
| `/admin/orders` | AdminOrdersComponent | adminGuard |
| `/admin/reservations` | AdminReservationsComponent | adminGuard |

---

## 13. HTTP API Kommunikáció

### 13.1. API Alapcím

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

### 13.2. Típikus HTTP Hívások

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

## 14. Autentifikáció és Token Kezelés

### 14.1. Laravel Sanctum

A backend **Laravel Sanctum** token-alapú autentifikációt használ az API-hoz.

**Token folyamat:**
1. Bejelentkezéskor a szerver egy API token-t generál
2. Frontend a token-t a request headerben küldi: `Authorization: Bearer {token}`
3. Server validálja a token-t minden request-en
4. Token érvényvessége konfigurálható

### 14.2. Bejelentkezési Folyamat

1. Felhasználó megy a `/auth/login` oldalra
2. Beírja email + jelszó
3. Form submit → `AuthService.login(credentials)`
4. Backend válaszol JWT token-nel
5. Token mentése: `localStorage.setItem('auth_token', token)`
6. `AuthService.isLoggedIn()` = true
7. `AuthService.currentUser` frissítése
8. Redirect `/` (home) vagy előző oldal

### 14.3. Token Automatikus Küldése

Az `AuthInterceptor` automatikusan:
- Lekéri a token-t az `AuthService`-ből
- Hozzáadja az `Authorization: Bearer {token}` header-t
- Az összes HTTP kérésre alkalmazódik

---

## 15. LocalStorage

A **Cart** és **Auth token** az `localStorage`-ben tárolódnak.

---

## 16. State Kezelés (Kosár Adatok)

### 16.1. Kosár Tárulás

```typescript
getCart(): Observable<Cart> {
  return this.http.get<Cart>('/cart');
}

cart$ = this.cartService.getCart();
```

### 16.2. Kosár Szinkronizálás

- Kezdetben: backend-ből fetch
- Módosítás: POST/PUT → backend

---

## 17. Admin Webes Felület (Backend Blade)

Az admin felület Blade sablonok segítségével van megvalósítva. Ez egy egyszerű web-alapú felület az étterem kezeléséhez, amely **nem igényel dizájnos megjelenést**, a funkcionalitás a fontos.

### 17.1. Dashboard

A fő oldal, amely megmutatja:
- Étterem adatai (név, nyitvatartás, értékelés)
- Aktív rendelések száma
- Ma érkező foglalások
- Pénzügyi összegzés

### 17.2. Menük Kezelés

**Mit lehet tenni:**
- Menükategóriák listázása (táblázat formában)
- Új kategória létrehozása (form)
- Kategória módosítása (form)
- Kategória törlése (soft delete - gomb)
- Ételek listázása (categorynként)
- Új étel létrehozása (form)
- Étel módosítása (form)
- Étel törlése (soft delete - gomb)

**Oldalak:**
- `GET /admin/menu` - Menük áttekintése
- `GET /admin/menu/categories` - Kategóriák listázása
- `GET /admin/menu/categories/create` - Új kategória form
- `PATCH /admin/menu/categories/{id}` - Kategória módosítása
- `DELETE /admin/menu/categories/{id}` - Kategória törlése
- `GET /admin/menu/items` - Ételek listázása
- `GET /admin/menu/items/create` - Új étel form
- `PATCH /admin/menu/items/{id}` - Étel módosítása
- `DELETE /admin/menu/items/{id}` - Étel törlése

### 17.3. Rendelések Kezelés

**Mit lehet tenni:**
- Rendelések listázása (táblázat formában, státusz szerinti szűrés)
- Rendelés megtekintése (részletes adatok)
- Rendelés státuszának módosítása (pending → processing → ready)
- Rendelés törlése (soft delete - gomb)

**Oldalak:**
- `GET /admin/orders` - Rendelések listázása
- `GET /admin/orders/{id}` - Rendelés részletei
- `PATCH /admin/orders/{id}` - Rendelés státusza módosítása

### 17.4. Asztal Kezelés

**Mit lehet tenni:**
- Asztalok listázása
- Új asztal felvétele
- Asztal módosítása
- Asztal törlése
- Foglalások kezelése (státusz módosítása)

**Oldalak:**
- `GET /admin/tables` - Asztalok listázása
- `GET /admin/tables/create` - Új asztal form
- `PATCH /admin/tables/{id}` - Asztal módosítása
- `GET /admin/reservations` - Foglalások listázása
- `PATCH /admin/reservations/{id}` - Foglalás módosítása

### 17.5. Admin Middleware

Az admin felület elérésére szükséges:
- `auth` middleware - Bejelentkezés szükséges
- `restaurant_owner` middleware - Étterem tulajdonos jogosultság szükséges

---

## 18. Tesztelés

### 18.1. Postman Collection

A Postman collection a `tests/postman-collection.json` fájlban található.

**Mit tartalmaz:**
- Auth műveletek (register, login, logout)
- Restaurant CRUD tesztek
- MenuItem CRUD tesztek
- Order kezelés tesztek
- Cart tesztek
- Reservation kezelés

**Használat:**
- Collection exportálható format-ben (JSON)
- Automata tesztek futtathatók
- Request-response ellenőrzés

**Importálás:**
1. Postman megnyitása
2. Import gomb kattintás
3. JSON fájl kiválasztása

---

**Készítette:** Boros Péter, Morzsa Milán Dominik  
**Készítve:** 2026. március 31.
