# 📚 DOKUMENTASI LENGKAP: ALUR KERANJANG → CHECKOUT → TRANSAKSI

## 🎯 Overview

Sistem e-commerce dengan alur lengkap dari keranjang belanja hingga transaksi pembayaran menggunakan **kombinasi Database & Session** untuk optimasi performa dan data persistence.

---

## 📊 DIAGRAM ALUR DATA FLOW

```
┌─────────────────────────────────────────────────────────────────────┐
│                         1. SHOPPING CART                             │
│                      (Database: shopping_card)                       │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                    User klik "Checkout" dengan item terpilih
                                    │
                                    ▼
                    Simpan ID items ke SESSION: checkout_items
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│                          2. CHECKOUT PAGE                            │
│                      (Session: checkout_items)                       │
│                  Read data lengkap dari DB cart + produk             │
└─────────────────────────────────────────────────────────────────────┘
                                    │
        User pilih alamat, pengiriman, pembayaran → klik "Bayar"
                                    │
                                    ▼
                Simpan semua data checkout ke SESSION: checkout_data
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│                        3. PAYMENT/TRANSAKSI                          │
│                      (Session: checkout_data)                        │
│                  Display ringkasan, input nomor bayar                │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                    User submit nomor pembayaran
                                    │
                                    ▼
                      COMMIT TO DATABASE (Transaction)
                                    │
                    ├─ INSERT product_order
                    ├─ INSERT product_order_items
                    ├─ DELETE items dari shopping_card
                    └─ CLEAR SESSION checkout
                                    │
                                    ▼
                          ✅ Transaction Success
```

---

## 🗄️ STRUKTUR DATABASE

### 1. **shopping_card** (Keranjang Belanja)
Menyimpan item yang ditambahkan user ke keranjang.

```sql
CREATE TABLE shopping_card (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    product_id INT UNSIGNED,
    ukuran VARCHAR(10),
    qty INT DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES produk(id) ON DELETE CASCADE
);
```

**Kapan disimpan:**
- User klik "Tambah ke Keranjang" → INSERT/UPDATE
- User klik +/- qty → UPDATE qty
- User hapus item → DELETE

**Best Practice:**
- Simpan langsung ke DB agar keranjang persistent (tidak hilang saat refresh)
- Update qty real-time via AJAX tanpa reload halaman

---

### 2. **product_order** (Order/Transaksi)
Menyimpan header transaksi setelah user bayar.

```sql
CREATE TABLE product_order (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    alamat VARCHAR(255),
    metode_pengiriman_id BIGINT UNSIGNED,
    metode_pembayaran_id BIGINT UNSIGNED,
    subtotal_produk INT,
    total_ongkir INT,
    total_bayar INT,
    payment_number VARCHAR(255),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Kapan disimpan:**
- User submit nomor pembayaran di halaman Payment → INSERT

---

### 3. **product_order_items** (Detail Item Transaksi)
Menyimpan detail produk yang dibeli dalam transaksi.

```sql
CREATE TABLE product_order_items (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    product_order_id BIGINT UNSIGNED,
    produk_id INT UNSIGNED,
    nama VARCHAR(255),
    harga INT,
    qty INT,
    subtotal INT,
    ukuran VARCHAR(50),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (product_order_id) REFERENCES product_order(id) ON DELETE CASCADE
);
```

**Kapan disimpan:**
- Bersamaan dengan product_order → INSERT (dalam 1 transaksi DB)

---

## 🔄 SESSION MANAGEMENT

### 1. **checkout_items** (Array of Cart IDs)
Menyimpan ID item yang dipilih saat checkout dari keranjang.

```php
session(['checkout_items' => [1, 3, 5]]);  // ID dari shopping_card
```

**Kapan dibuat:**
- User centang item di keranjang → klik "Checkout"

**Kapan dihapus:**
- Setelah transaksi berhasil

---

### 2. **checkout_data** (Complete Checkout Information)
Menyimpan semua data checkout (alamat, pengiriman, pembayaran, produk, total).

```php
session(['checkout_data' => [
    'alamat' => 'Jl. Sudirman No. 123',
    'metode_pengiriman' => [...],
    'metode_pembayaran' => [...],
    'products' => [...],
    'subtotal_produk' => 800000,
    'total_ongkir' => 15000,
    'total_bayar' => 815000,
    'created_at' => '2025-12-10 10:00:00'
]]);
```

**Kapan dibuat:**
- User klik "Bayar" di halaman Checkout → redirect ke Payment

**Kapan dihapus:**
- Setelah transaksi berhasil disimpan ke DB

---

## 🔀 ALUR DETAIL STEP-BY-STEP

### **STEP 1: Shopping Cart (Keranjang Belanja)**

#### Controller: `ShoppingCardController`

**1.1 Tambah ke Keranjang**
```php
POST /cart/add
Input: product_id, ukuran, qty
Logic:
  - Cek apakah user sudah login
  - Cek apakah produk+ukuran sudah ada di keranjang user
  - Jika ada: UPDATE qty += qty_baru
  - Jika tidak: INSERT baris baru
Output: JSON success
```

**1.2 Update Qty**
```php
POST /cart/update
Input: cart_id, qty
Logic:
  - Validasi cart_id milik user yang login
  - UPDATE qty di DB
Output: JSON success
```

**1.3 Hapus Item**
```php
DELETE /cart/{id}
Logic:
  - Validasi cart_id milik user
  - DELETE dari DB
Output: JSON success
```

**1.4 Checkout (Pilih Item)**
```php
POST /cart/checkout
Input: selected_items[] (array of cart IDs)
Logic:
  - Validasi semua ID milik user
  - Simpan ke session: session(['checkout_items' => [1,3,5]])
  - Redirect ke halaman Checkout
```

---

### **STEP 2: Checkout Page**

#### Controller: `CheckOutController`

**2.1 Tampilkan Halaman Checkout**
```php
GET /checkout
Logic:
  - Ambil checkout_items dari session
  - Query DB: ambil data lengkap cart items + produk
  - Hitung subtotal produk
  - Load metode pengiriman & pembayaran dari DB
  - Ambil alamat default user
Output: View dengan data checkout
```

**2.2 Simpan Data Checkout ke Session**
```php
POST /checkout/store
Input: alamat, metode_pengiriman_id, metode_pembayaran_id
Logic:
  - Validasi input
  - Ambil data cart dari checkout_items session
  - Hitung total (subtotal + ongkir)
  - Simpan semua data ke session: checkout_data
  - Return redirect URL ke Payment
Output: JSON dengan redirect_url
```

---

### **STEP 3: Payment/Transaction Page**

#### Controller: `PaymentController`

**3.1 Tampilkan Halaman Payment**
```php
GET /payment
Logic:
  - Ambil checkout_data dari session
  - Jika session kosong: redirect ke cart
  - Generate payment deadline (now + 24 jam)
  - Display ringkasan transaksi
Output: View payment dengan countdown timer
```

**3.2 Submit Pembayaran (COMMIT TO DATABASE)**
```php
POST /payment/store
Input: payment_number
Logic:
  - Validasi nomor pembayaran
  - Ambil checkout_data dari session
  - BEGIN TRANSACTION
    ├─ INSERT product_order
    ├─ INSERT product_order_items (loop)
    ├─ DELETE shopping_card items (yang sudah dibeli)
    └─ CLEAR session (checkout_items, checkout_data)
  - COMMIT
Output: JSON success + redirect URL
```

---

## 🛠️ BEST PRACTICES & OPTIMASI

### ✅ **1. Database vs Session**

| Data Type           | Storage |               Reason                |
|---------------------|---------|-------------------------------------|
| Shopping Cart Items | **DATABASE** | Persistent, tidak hilang saat logout/refresh |
| Selected Checkout Items (IDs) | **SESSION** | Temporary, hanya untuk flow checkout |
| Checkout Data (alamat, total, dll) | **SESSION** | Temporary, dihapus setelah bayar |
| Final Transaction | **DATABASE** | Permanent record |

### ✅ **2. Transaction Safety**

Gunakan **DB Transaction** saat menyimpan order:
```php
DB::beginTransaction();
try {
    // INSERT order
    // INSERT order_items
    // DELETE cart
    // CLEAR session
    DB::commit();
} catch (Exception $e) {
    DB::rollBack();
    return error;
}
```

### ✅ **3. Validation**

- Validasi semua input di controller
- Pastikan cart_id/product_id milik user yang login
- Validasi stok produk sebelum checkout
- Validasi payment_number format

### ✅ **4. Session Management**

- Gunakan session untuk data temporary
- Clear session setelah transaction success
- Jangan simpan data sensitif di session

### ✅ **5. Error Handling**

- Return JSON response untuk AJAX call
- Use try-catch untuk DB operations
- Redirect dengan message jika session expired

---

## 🚀 TESTING FLOW

### Test Case 1: Happy Path
1. User tambah 3 produk ke keranjang ✅
2. User update qty produk ✅
3. User centang 2 produk → checkout ✅
4. User pilih alamat, pengiriman, pembayaran ✅
5. User input nomor pembayaran → submit ✅
6. Cek DB: order tersimpan, cart terhapus ✅
7. Cek session: checkout_items & checkout_data terhapus ✅

### Test Case 2: Session Expired
1. User di halaman Payment
2. Clear session manual
3. Refresh page → redirect ke cart ✅

### Test Case 3: Concurrent Update
1. User A & B update qty produk yang sama
2. Pastikan qty tidak overwrite ✅

---

## 📝 CATATAN PENTING

### ⚠️ **Data Persistence**
- Cart items **HARUS** disimpan di DB (bukan session)
- Session hanya untuk temporary checkout flow
- Setelah bayar, data moved from cart → order

### ⚠️ **Security**
- Selalu validasi user_id == session user
- Jangan trust client-side calculation
- Re-calculate total di server sebelum commit

### ⚠️ **Performance**
- Use eager loading: `with('produk')` untuk menghindari N+1 query
- Index foreign keys (user_id, product_id)
- Cache metode pengiriman/pembayaran jika jarang berubah

---

## 🎓 SENIOR ENGINEER TIPS

1. **Clean Code**: Pisahkan logic validation ke Request class
2. **Single Responsibility**: 1 method 1 task
3. **DRY Principle**: Helper method untuk getUserId()
4. **Consistent Naming**: checkout_items, checkout_data (konsisten)
5. **Comments**: Tambahkan comment untuk business logic penting
6. **API Response**: Gunakan format JSON konsisten untuk AJAX
7. **Logging**: Log critical operations (payment, order creation)

---

## 📚 REFERENSI KODE

### Routes
```php
// Shopping Cart
Route::post('/cart/checkout', [ShoppingCardController::class, 'checkout']);

// Checkout
Route::get('/checkout', [CheckOutController::class, 'index']);
Route::post('/checkout/store', [CheckOutController::class, 'store']);

// Payment
Route::get('/payment', [PaymentController::class, 'index']);
Route::post('/payment/store', [PaymentController::class, 'store']);
```

### Models
- ShoppingCard (shopping_card table)
- ProductOrder (product_order table)
- ProductOrderItem (product_order_items table)
- MetodePengiriman
- MetodePembayaran

### Controllers
- ShoppingCardController: CRUD cart + checkout trigger
- CheckOutController: Display & save checkout data
- PaymentController: Display & commit transaction

---

**Dibuat dengan ❤️ untuk production-ready Laravel 11 e-commerce**
