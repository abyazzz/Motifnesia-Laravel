# DOKUMENTASI FITUR - PRESENTASI PROJECT MOTIFNESIA

> **Developer:** Abay  
> **Tanggal:** 29 Desember 2025  
> **Tujuan:** Dokumentasi file-file yang terhubung untuk setiap halaman yang akan dipresentasikan

---

## 1. HomePage (Halaman Utama Customer)

### View:
- `resources/views/customer/pages/homePage.blade.php`
- `resources/views/customer/layouts/mainLayout.blade.php` (layout utama)

### Controller:
- `app/Http/Controllers/Customer/CustomerProductController.php`
  - Method: `index()` - Menampilkan homepage dengan slideshow dan list produk (dengan filter search, gender, jenis lengan, price range)

### Model:
- `app/Models/Produk.php` - Model produk utama
- `app/Models/KontenSlideShow.php` - Model untuk slideshow banner
- `app/Models/Review.php` - Model review untuk rating produk

### Service:
- `app/Services/ProductService.php` - Service untuk format produk dengan rating

### Migration:
- `database/migrations/2025_11_23_000001_create_produk_table.php`
- `database/migrations/2025_11_23_000006_create_konten_slide_show_table.php`
- `database/migrations/2025_11_24_000012_create_reviews_table.php`

### Route:
- `GET /homePage` → `customer.home`

---

## 2. Detail Produk

### View:
- `resources/views/customer/pages/detailProduct.blade.php`

### Controller:
- `app/Http/Controllers/Customer/CustomerProductController.php`
  - Method: `show($id)` - Menampilkan detail produk, reviews, dan related products

### Model:
- `app/Models/Produk.php` - Model produk
- `app/Models/Review.php` - Model review produk
- `app/Models/User.php` - Model user (untuk data reviewer)

### Service:
- `app/Services/ProductService.php` - Service untuk format produk dengan rating

### Migration:
- `database/migrations/2025_11_23_000001_create_produk_table.php`
- `database/migrations/2025_11_24_000012_create_reviews_table.php`
- `database/migrations/0001_01_01_000000_create_users_table.php`

### Route:
- `GET /products/{id}` → `customer.product.detail`

---

## 3. Checkout (Halaman Checkout)

### View:
- `resources/views/customer/pages/checkOut.blade.php`

### Controller:
- `app/Http/Controllers/Customer/CheckOutController.php`
  - Method: `index()` - Menampilkan halaman checkout dengan data keranjang yang dipilih
  - Method: `store()` - Proses checkout dan simpan data ke session

### Model:
- `app/Models/ShoppingCard.php` - Model keranjang belanja
- `app/Models/Produk.php` - Model produk
- `app/Models/MetodePengiriman.php` - Model metode pengiriman
- `app/Models/MetodePembayaran.php` - Model metode pembayaran
- `app/Models/User.php` - Model user (untuk alamat pengiriman)

### Migration:
- `database/migrations/2025_12_06_000015_create_shopping_card_table.php`
- `database/migrations/2025_11_23_000001_create_produk_table.php`
- `database/migrations/2025_12_07_000016_create_metode_pengiriman_table.php`
- `database/migrations/2025_12_07_000017_create_metode_pembayaran_table.php`
- `database/migrations/0001_01_01_000000_create_users_table.php`
- `database/migrations/2025_11_24_000011_add_address_to_users.php`

### Route:
- `GET /checkout` → `customer.checkout.index`
- `POST /checkout/store` → `customer.checkout.store`

---

## 4. Payment (Halaman Pembayaran)

### View:
- `resources/views/customer/pages/payment.blade.php`

### Controller:
- `app/Http/Controllers/Customer/PaymentController.php`
  - Method: `index()` - Menampilkan halaman payment dengan ringkasan checkout
  - Method: `store()` - Proses pembayaran dan simpan transaksi ke database
  - Method: `success($orderId)` - Menampilkan halaman sukses transaksi

### Model:
- `app/Models/Order.php` - Model order/transaksi utama
- `app/Models/OrderItem.php` - Model item dalam order
- `app/Models/ShoppingCard.php` - Model keranjang (untuk hapus setelah checkout)
- `app/Models/Checkout.php` - Model data checkout
- `app/Models/CheckoutItem.php` - Model item checkout

### Service:
- `app/Services/OrderService.php` - Service untuk proses pembuatan order

### Observer:
- `app/Observers/OrderObserver.php` - Observer untuk notifikasi dan history status

### Migration:
- `database/migrations/2025_12_11_140000_create_orders_table.php`
- `database/migrations/2025_12_11_140001_create_order_items_table.php`
- `database/migrations/2025_12_11_140000_create_checkout_table.php`
- `database/migrations/2025_12_11_140001_create_checkout_items_table.php`
- `database/migrations/2025_12_06_000015_create_shopping_card_table.php`

### Route:
- `GET /payment` → `customer.payment.index`
- `POST /payment/store` → `customer.payment.store`
- `GET /transaction/success/{orderId}` → `customer.transaction.success`

---

## 5. Payment Confirmation (Konfirmasi Pembayaran)

### View:
- `resources/views/customer/pages/paymentConfirmation.blade.php`
- `resources/views/customer/pages/transactionSuccess.blade.php` (halaman sukses)
- `resources/views/customer/pages/paymentSuccess.blade.php` (alternatif)

### Controller:
- `app/Http/Controllers/Customer/PaymentController.php`
  - Method: `success($orderId)` - Menampilkan konfirmasi pembayaran berhasil
- `app/Http/Controllers/Customer/TransactionController.php`
  - Method: `showPaymentConfirmation()` - Menampilkan detail konfirmasi pembayaran

### Model:
- `app/Models/Order.php` - Model order untuk data transaksi
- `app/Models/OrderItem.php` - Model item dalam order
- `app/Models/MetodePembayaran.php` - Model metode pembayaran

### Migration:
- `database/migrations/2025_12_11_140000_create_orders_table.php`
- `database/migrations/2025_12_11_140001_create_order_items_table.php`
- `database/migrations/2025_12_07_000017_create_metode_pembayaran_table.php`

### Route:
- `GET /transaction/success/{orderId}` → `customer.transaction.success`

---

## 6. Admin - Kelola Produk (Product Management)

### View:
- `resources/views/admin/pages/productManagement.blade.php` - Halaman utama management (grid view + modal)
- `resources/views/admin/pages/addProduct.blade.php` - Form tambah produk

### Controller:
- `app/Http/Controllers/Admin/AdminProductController.php`
  - Method: `manage()` - Menampilkan halaman product management
  - Method: `createOrStore()` - Form tambah produk + proses simpan
  - Method: `update($id)` - Update produk via modal
  - Method: `destroy($id)` - Hapus produk

### Model:
- `app/Models/Produk.php` - Model produk

### Service:
- `app/Services/ProductService.php` - Service untuk upload gambar dan prepare data produk

### Observer:
- `app/Observers/ProductObserver.php` - Observer untuk notifikasi saat produk berubah

### Request Validation:
- `app/Http/Requests/StoreProductRequest.php` - Validasi tambah produk
- `app/Http/Requests/UpdateProductRequest.php` - Validasi update produk

### Migration:
- `database/migrations/2025_11_23_000001_create_produk_table.php`
- `database/migrations/2025_11_23_000003_add_ukuran_to_produk_table.php`

### Route:
- `GET /admin/product-management` → `admin.product.management.index`
- `GET|POST /admin/products/create` → `admin.products.create`
- `POST /admin/products/{id}/update` → `admin.products.update`
- `POST /admin/products/{id}/delete` → `admin.products.delete`

---

## 7. Admin - Status Pengiriman (Order Status)

### View:
- `resources/views/admin/pages/orderStatus.blade.php`

### Controller:
- `app/Http/Controllers/Admin/OrderStatusController.php`
  - Method: `index()` - Menampilkan semua order dengan status pengiriman
  - Method: `updateStatus($id)` - Update status pengiriman order

### Model:
- `app/Models/Order.php` - Model order
- `app/Models/OrderItem.php` - Model item dalam order
- `app/Models/OrderStatusHistory.php` - Model history perubahan status
- `app/Models/DeliveryStatus.php` - Model status pengiriman
- `app/Models/User.php` - Model user (customer)
- `app/Models/Produk.php` - Model produk
- `app/Models/MetodePengiriman.php` - Model metode pengiriman
- `app/Models/MetodePembayaran.php` - Model metode pembayaran

### Observer:
- `app/Observers/OrderObserver.php` - Observer untuk notifikasi perubahan status

### Migration:
- `database/migrations/2025_12_11_140000_create_orders_table.php`
- `database/migrations/2025_12_11_140001_create_order_items_table.php`
- `database/migrations/2025_12_10_115919_create_delivery_status_table.php`
- `database/migrations/2025_12_11_140002_create_order_status_history_table.php`
- `database/migrations/0001_01_01_000000_create_users_table.php`
- `database/migrations/2025_11_23_000001_create_produk_table.php`
- `database/migrations/2025_12_07_000016_create_metode_pengiriman_table.php`
- `database/migrations/2025_12_07_000017_create_metode_pembayaran_table.php`

### Route:
- `GET /admin/order-status` → `admin.orders.status`
- `POST /admin/order-status/{id}/update` → `admin.orders.status.update`

---

## 8. Admin - Laporan Penjualan (Sales Report)

### View:
- `resources/views/admin/pages/salesReport.blade.php`

### Controller:
- `app/Http/Controllers/Admin/ReportController.php`
  - Method: `index()` - Menampilkan laporan penjualan dengan filter periode, metrics, chart, dan top products
  - Method: `export()` - Export laporan ke Excel/PDF

### Model:
- `app/Models/Order.php` - Model order untuk data transaksi
- `app/Models/OrderItem.php` - Model item untuk hitung produk terjual
- `app/Models/Produk.php` - Model produk untuk top products
- `app/Models/User.php` - Model user (customer)
- `app/Models/MetodePembayaran.php` - Model metode pembayaran
- `app/Models/MetodePengiriman.php` - Model metode pengiriman

### Migration:
- `database/migrations/2025_12_11_140000_create_orders_table.php`
- `database/migrations/2025_12_11_140001_create_order_items_table.php`
- `database/migrations/2025_11_23_000001_create_produk_table.php`
- `database/migrations/0001_01_01_000000_create_users_table.php`
- `database/migrations/2025_12_07_000017_create_metode_pembayaran_table.php`
- `database/migrations/2025_12_07_000016_create_metode_pengiriman_table.php`

### Route:
- `GET /admin/sales-report` → `admin.reports.sales`
- `GET /admin/sales-report/export` → `admin.reports.export`

---

## CATATAN TAMBAHAN

### File Konfigurasi Penting:
- `config/order.php` - Konfigurasi untuk order (payment deadline, etc)
- `config/product.php` - Konfigurasi untuk produk
- `config/cart.php` - Konfigurasi untuk shopping cart

### Middleware yang Digunakan:
- `middleware => 'customer'` - Middleware untuk halaman customer yang butuh login
- `middleware => 'admin'` - Middleware untuk halaman admin
- `middleware => 'block.admin'` - Middleware untuk block admin akses homepage customer

### Assets (CSS/JS):
- `public/css/` - Folder CSS untuk styling
- `public/JS/` - Folder JavaScript untuk interaksi
- `public/images/` - Folder untuk gambar produk dan konten

---

**Good Luck untuk Presentasinya, Bro! 🚀**
