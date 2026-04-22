# DineHub Backend Dokumentáció

## 1. Áttekintés

A **DineHub** backend egy **Laravel** alapú restaurant-kezelő és ételrendelő rendszer, amely lehetővé teszi az éttermi műveletek kezelését, ételrendeléseket feldolgozását és asztalfoglalásokat kezelését. A rendszer két szerepkört támogat: **admin** (étterem tulajdonos) és **user** (vásárló), eltérő jogosultságokkal.

**Főbb funkcionalitások:**
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

---

## Technológiai Stack

| Technológia | Verzió | Felhasználás |
|---|---|---|
| **PHP** | ^8.2 | Backend nyelvezet |
| **Laravel** | ^12.0 | Web framework API autentifikáció és token kezelés |
| **MySQL** | - | Adatbázis tárrolás |

---

## 2. Adatbázis Struktúra

### 2.1. Users tábla

A rendszer felhasználóit tartalmazza. Tartalmaz admin (restaurant owner) és normál felhasználókat (vásárlókat) is.

**Mit tartalmaz:**
- Felhasználó azonosítás (name, email)
- Jelszó kezelés (password - titkosított)
- Felhasználó típusa (role: user, restaurant_owner, admin)
- Felhasználó adatok (phone, avatar_url)
- Aktív státusz (is_active flag)
- Soft delete támogatás (deleted_at)

---

### 2.2. Restaurants tábla

Az étterem adatokat tartalmazza a platformon regisztrált éttermeket.

**Mit tartalmaz:**
- Étterem azonosítása (name, location, phone)
- Nyitvatartási adatok (opening_hours, closing_hours)
- Étterem képe és leírása (image_url, description)
- Éttermek értékelése (rating)
- Aktív státusz (is_active)
- Soft delete támogatás (deleted_at)

---

### 2.3. MenuCategory tábla

Az étterem menükategóriáit tartalmazza (pl. előételek, főételek, desszert).

**Mit tartalmaz:**
- Kategória azonosítása (name, description)
- Étterem kapcsolat (restaurant_id)
- Sorrend megjelölés (position)
- Soft delete támogatás (deleted_at)

---

### 2.4. MenuItem tábla

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

### 2.5. Orders tábla

A felhasználók által teljesített rendeléseket tartalmazza.

**Mit tartalmaz:**
- Rendelés azonosítása (id, user_id, restaurant_id)
- Rendelés státusza (status: pending, confirmed, preparing, ready, completed, cancelled)
- Pénzügyi adatok (total_amount)
- Szállítási információ (delivery_type, delivery_address, estimated_time)
- Megjegyzés (note)
- Soft delete támogatás (deleted_at)

---

### 2.6. OrderItem tábla

A rendelés egyes tételeit tartalmazza.

**Mit tartalmaz:**
- Tételazonosítás (order_id, menu_item_id)
- Mennyiség (quantity)
- Az adott időpontban érvényes ár (price)
- Soft delete támogatás (deleted_at)

---

### 2.7. Cart tábla

A felhasználó aktív kosarát reprezentálja.

**Mit tartalmaz:**
- Kosár azonosítása (id, user_id)
- Létrehozási és módosítási adatok (timestamps)

---

### 2.8. CartItem tábla

A kosár egyes tételeit tartalmazza.

**Mit tartalmaz:**
- Tétel azonosítása (menu_item_id, restaurant_id)
- Kosár kapcsolat (cart_id)
- Mennyiség (quantity)
- Soft delete támogatás (deleted_at)

---

### 2.9. Reservations tábla

Az asztal foglalásokat tartalmazza.

**Mit tartalmaz:**
- Foglalás azonosítása (user_id, restaurant_id, restaurant_table_id)
- Foglalás időpontjai (reservation_date, reservation_time)
- Vendégek száma (party_size)
- Foglalás státusza (status: pending, confirmed, cancelled, completed)
- Speciális kérések (special_requests)
- Soft delete támogatás (deleted_at)

---

### 2.10. RestaurantTable tábla

Az étterem asztalait reprezentálja.

**Mit tartalmaz:**
- Asztal azonosítása (table_number, capacity)
- Étterem kapcsolat (restaurant_id)
- Elérhetőség (is_available)
- Soft delete támogatás (deleted_at)

---

### 2.11. Payments tábla

A rendelésekhez tartozó fizetéseket tartalmazza.

**Mit tartalmaz:**
- Fizetés azonosítása (order_id, amount)
- Fizetési mód (payment_method: credit_card, debit_card, paypal, cash)
- Fizetés státusza (status: pending, completed, failed, refunded)
- Tranzakció azonosítása (transaction_id)
- Soft delete támogatás (deleted_at)

---

## 3. Jogosultságkezelés

### 3.1. Admin Szerepkör

Az admin (étterem tulajdonos) felhasználók a következő jogosultságokkal rendelkeznek:

- **Felhasználók kezelése** - Felhasználók listázása, kezelése
- **Éttermek kezelése** - Saját étterme adatainak módosítása
- **Menükategóriák** - Étterem menükategóriáinak CRUD kezelése
- **Ételek kezelése** - Étterem ételinek (menüpontok) CRUD kezelése
- **Rendelések kezelése** - Megpróbálhat és feldolgozni rendeléseket
- **Asztalok kezelése** - Éttermi asztalok és foglalások kezelése
- **Admin webes felület** - A Blade alapú admin felület elérése

### 3.2. User Szerepkör (Vásárló)

A normál felhasználók (vásárlók) a következő jogosultságokkal rendelkeznek:

- **Saját profil** - Saját adatok megtekintése és módosítása
- **Éttermek böngészése** - Nyilvános éttermi katalógus megtekintése
- **Étlapok megtekintése** - Éttermek menüinak megtekintése
- **Kosár kezelés** - Tételek hozzáadása/eltávolítása a kosárból
- **Rendeléseket** - Saját rendeléseit megtekintése és kezelése
- **Asztalfoglalás** - Asztal foglalása az étteremben
- **Fizetés feldolgozása** - Rendelések fizetése

### 3.3. Jogosultságkezelés Megvalósítása

A jogosultságkezelés megvalósítható:
- **Middleware** segítségével (IsAdmin, IsRestaurantOwner middleware)
- **Controller-ben** if ellenőrzésekkel
- **Gate/Policy** Laravel mechanizmussal

---

## 4. CRUD és Soft Delete Szabályok

### 4.1. User Törlés

Amikor egy felhasználó törlésre kerül:
- A **users** táblában **soft delete** történik (deleted_at értéket kap)
- A felhasználóhoz tartozó **orders** és **reservations** rekordok is **soft delete**-re kerülnek
- A felhasználó kosara és tételei is **soft delete**-re kerülnek

### 4.2. Restaurant Törlés

Amikor egy étterem törlésre kerül:
- A **restaurants** táblában **soft delete** történik (deleted_at értéket kap)
- Az étteremhez tartozó összes **menu_items**, **orders**, **reservations** soft delete-re kerülnek
- A **restaurant_tables** szintén soft delete-re kerülnek

### 4.3. MenuItem Törlés

Amikor egy étel törlésre kerül:
- A **menu_items** táblában **soft delete** történik (deleted_at értéket kap)
- Az étel a kosárban és a rendelésekben is jelölésre kerül

### 4.4. Order Törlés

Amikor egy rendelés törlésre kerül:
- A **orders** és kapcsolódó **order_items** soft delete-re kerülnek
- A fizetési információk is soft delete-re kerülnek

### 4.5. Reservation Törlés

Amikor egy foglalás törlésre kerül:
- A **reservations** táblában **soft delete** történik (deleted_at értéket kap)

---

## 5. API Funkciók

### 5.1. Autentifikáció

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

### 5.2. Restaurants (Éttermek)

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
- **Jogosultság:** Az étterem админa
- **Mit tesz:** Étterem adatait módosítja

#### Delete Restaurant (Soft Delete)
- **Végpont:** `DELETE /api/restaurants/{id}`
- **Jogosultság:** Az étterem adminja
- **Mit tesz:** Éttermet soft delete-re jelöli

---

### 5.3. Menu Categories (Menükategóriák)

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

### 5.4. Menu Items (Ételek)

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

### 5.5. Orders (Rendelések)

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

### 5.6. Cart (Kosár)

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

### 5.7. Reservations (Asztalfoglalások)

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

### 5.8. Payments (Fizetések)

#### Create Payment
- **Végpont:** `POST /api/payments`
- **Jogosultság:** Autentifikált
- **Mit tesz:** Fizetést feldolgoz

#### Get Payment
- **Végpont:** `GET /api/payments/{id}`
- **Jogosultság:** A rendelés tulajdonosa vagy étterem adminja
- **Mit tesz:** Fizetés adatait mutatja

---

## 6. Tesztelés

### 6.1. Postman Tesztek

A projekthez mellékelt Postman collection tartalmazza:

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

---

## 7. Admin Webes Felület

Az admin felület Blade sablonok segítségével van megvalósítva. Ez egy egyszerű web-alapú felület az étterem kezeléséhez, amely **nem igényel dizájnos megjelenést**, a funkcionalitás a fontos.

### 7.1. Dashboard

A fő oldal, amely megmutatja:
- Étterem adatai (név, nyitvatartás, értékelés)
- Aktív rendelések száma
- Ma érkező foglalások
- Pénzügyi összegzés

### 7.2. Menük Kezelés

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

### 7.3. Rendelések Kezelés

**Mit lehet tenni:**
- Rendelések listázása (táblázat formában, státusz szerinti szűrés)
- Rendelés megtekintése (részletes adatok)
- Rendelés státuszának módosítása (pending → processing → ready)
- Rendelés törlése (soft delete - gomb)

**Oldalak:**
- `GET /admin/orders` - Rendelések listázása
- `GET /admin/orders/{id}` - Rendelés részletei
- `PATCH /admin/orders/{id}` - Rendelés státusza módosítása

### 7.4. Asztal Kezelés

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

### 7.5. Admin Middleware

Az admin felület elérésére szükséges:
- `auth` middleware - Bejelentkezés szükséges
- `restaurant_owner` middleware - Étterem tulajdonos jogosultság szükséges

---

## 8. Autentifikáció és Token Kezelés

### 8.1. Laravel Sanctum

A backend **Laravel Sanctum** token-alapú autentifikációt használ az API-hoz.

**Token folyamat:**
1. Bejelentkezéskor a szerver egy API token-t generál
2. Frontend a token-t a request headerben küldi: `Authorization: Bearer {token}`
3. Server validálja a token-t minden request-en
4. Token érvényvessége konfigurálható

---

## 9. Tesztek Futtatása

### 9.1. Összes teszt futtatása

```bash
php artisan test
```

### 9.2. Konkrét teszt futtatása

```bash
php artisan test tests/Feature/BasicFeatureTest.php
```

---

## 10. Postman Collection

A Postman collection a `tests/postman-collection.json` fájlban található.

**Importálás:**
1. Postman megnyitása
2. Import gomb kattintás
3. JSON fájl kiválasztása

---

## 11. Projekt Struktúra

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

---

**Készítette:** Morzsa Milán Dominik, Boros Péter  
**Készítve:** 2026. március 28.
