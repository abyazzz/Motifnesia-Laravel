# Class Diagram Motifnesia

## Cara Pakai:
1. Buka website: **https://mermaid.live**
2. Copy script di bawah ini
3. Paste ke editor di website tersebut
4. Diagram akan muncul otomatis
5. Bisa di-export ke PNG/SVG

---

## Script Mermaid Class Diagram:

```mermaid
classDiagram
    %% ==================== MODELS ====================
    
    class User {
        +int id
        +string name
        +string full_name
        +string email
        +string password
        +string role
        +string profile_pic
        +string phone_number
        +date birth_date
        +string gender
        +string address_line
        +string city
        +string province
        +string postal_code
        +timestamp email_verified_at
        +orders()
        +shoppingCards()
        +favorites()
        +reviews()
        +notifications()
    }

    class Produk {
        +int id
        +string gambar
        +string nama_produk
        +decimal harga
        +string material
        +string proses
        +string sku
        +string tags
        +int stok
        +string ukuran
        +string kategori
        +string gender
        +string jenis_lengan
        +int terjual
        +text deskripsi
        +int diskon_persen
        +decimal harga_diskon
        +reviews()
        +orderItems()
        +favorites()
        +shoppingCards()
    }

    class Order {
        +int id
        +int user_id
        +string order_number
        +text alamat
        +int metode_pengiriman_id
        +int metode_pembayaran_id
        +int delivery_status_id
        +decimal total_ongkir
        +decimal total_bayar
        +string payment_number
        +user()
        +metodePengiriman()
        +metodePembayaran()
        +deliveryStatus()
        +orderItems()
        +reviews()
        +returns()
    }

    class OrderItem {
        +int id
        +int order_id
        +int produk_id
        +string nama_produk
        +string ukuran
        +int qty
        +decimal harga
        +decimal subtotal
        +order()
        +produk()
        +review()
        +productReturns()
    }

    class OrderReview {
        +int id
        +int user_id
        +int order_item_id
        +int order_id
        +int produk_id
        +int rating
        +text deskripsi_ulasan
        +user()
        +orderItem()
        +order()
        +produk()
    }

    class ShoppingCard {
        +int id
        +int user_id
        +int product_id
        +string ukuran
        +int qty
        +user()
        +produk()
    }

    class ProductFavorite {
        +int id
        +int user_id
        +int produk_id
        +user()
        +produk()
    }

    class ProductReturn {
        +int id
        +int user_id
        +int order_id
        +int order_item_id
        +int produk_id
        +string reason
        +text description
        +string photo_proof
        +string status
        +text admin_note
        +string action_type
        +decimal refund_amount
        +string refund_status
        +user()
        +order()
        +orderItem()
        +produk()
        +getStatusColorAttribute()
        +getTimeAgoAttribute()
        +canReturnOrder()
    }

    class MetodePembayaran {
        +int id
        +string nama_pembayaran
        +text deskripsi_pembayaran
        +orders()
    }

    class MetodePengiriman {
        +int id
        +string nama_pengiriman
        +text deskripsi_pengiriman
        +decimal harga
        +orders()
    }

    class DeliveryStatus {
        +int id
        +string nama_status
        +text deskripsi
        +orders()
    }

    class Notification {
        +int id
        +int user_id
        +string type
        +string title
        +text message
        +string link
        +string priority
        +boolean is_read
        +user()
        +getIconAttribute()
        +getPriorityColorAttribute()
        +getTimeAgoAttribute()
    }

    %% ==================== SERVICES ====================
    
    class OrderService {
        +createOrder(array checkoutData, string paymentNumber) Order
        +calculateTotals(array products, float shippingCost) array
    }

    class ProductService {
        +createProduct(array data)
        +updateProduct(int id, array data)
        +deleteProduct(int id)
    }

    %% ==================== CONTROLLERS ====================
    
    class AdminProductController {
        +index()
        +create()
        +store(StoreProductRequest)
        +manage()
        +update(UpdateProductRequest, id)
        +destroy(id)
    }

    class AdminOrderController {
        +index()
        +show(id)
        +updateStatus(id)
        +viewDetails(id)
    }

    class AdminReturnController {
        +index()
        +show(id)
        +approve(id)
        +reject(id)
    }

    class CustomerProductController {
        +index()
        +show(id)
        +search()
        +filter()
    }

    class ShoppingCardController {
        +index()
        +store()
        +update(id)
        +destroy(id)
        +clear()
    }

    class CheckOutController {
        +index()
        +process()
        +confirm()
    }

    class PaymentController {
        +show()
        +confirm()
        +verify()
    }

    class ReviewController {
        +index()
        +store()
        +show(id)
    }

    class ReturnController {
        +create()
        +store()
        +index()
        +show(id)
    }

    class NotificationController {
        +index()
        +markAsRead(id)
        +markAllAsRead()
    }

    class ProductFavoriteController {
        +index()
        +toggle(produk_id)
        +destroy(id)
    }

    class UserProfileController {
        +show()
        +update()
        +updatePassword()
    }

    %% ==================== RELATIONSHIPS ====================
    
    %% User Relationships
    User "1" --> "0..*" Order : has
    User "1" --> "0..*" ShoppingCard : has
    User "1" --> "0..*" ProductFavorite : has
    User "1" --> "0..*" OrderReview : writes
    User "1" --> "0..*" Notification : receives
    User "1" --> "0..*" ProductReturn : requests

    %% Order Relationships
    Order "1" --> "1..*" OrderItem : contains
    Order "*" --> "1" MetodePembayaran : uses
    Order "*" --> "1" MetodePengiriman : uses
    Order "*" --> "1" DeliveryStatus : has
    Order "1" --> "0..*" OrderReview : has
    Order "1" --> "0..*" ProductReturn : has

    %% OrderItem Relationships
    OrderItem "*" --> "1" Produk : references
    OrderItem "1" --> "0..1" OrderReview : has
    OrderItem "1" --> "0..*" ProductReturn : has

    %% Produk Relationships
    Produk "1" --> "0..*" OrderItem : in
    Produk "1" --> "0..*" ShoppingCard : in
    Produk "1" --> "0..*" ProductFavorite : favorited
    Produk "1" --> "0..*" OrderReview : has
    Produk "1" --> "0..*" ProductReturn : has

    %% OrderReview Relationships
    OrderReview "*" --> "1" Produk : reviews

    %% Service Dependencies
    AdminProductController ..> ProductService : uses
    CheckOutController ..> OrderService : uses

    %% Controller Dependencies
    AdminProductController ..> Produk : manages
    ShoppingCardController ..> ShoppingCard : manages
    CheckOutController ..> Order : creates
    ReviewController ..> OrderReview : manages
    ReturnController ..> ProductReturn : manages
    NotificationController ..> Notification : manages
    ProductFavoriteController ..> ProductFavorite : manages
    UserProfileController ..> User : manages
```

---

## Alternative: PlantUML

Kalau mau pakai PlantUML, bisa juga di: **https://plantuml.com/plantuml**

```plantuml
@startuml Motifnesia Class Diagram

' ==================== MODELS ====================

class User {
    +id: int
    +name: string
    +email: string
    +role: string
    +phone_number: string
    +address_line: string
    --
    +orders()
    +shoppingCards()
    +favorites()
    +reviews()
}

class Produk {
    +id: int
    +nama_produk: string
    +harga: decimal
    +stok: int
    +kategori: string
    +diskon_persen: int
    --
    +reviews()
    +orderItems()
}

class Order {
    +id: int
    +order_number: string
    +total_bayar: decimal
    +delivery_status_id: int
    --
    +user()
    +orderItems()
}

class OrderItem {
    +id: int
    +order_id: int
    +produk_id: int
    +qty: int
    +subtotal: decimal
    --
    +order()
    +produk()
    +review()
}

class OrderReview {
    +id: int
    +rating: int
    +deskripsi_ulasan: text
    --
    +user()
    +produk()
}

class ShoppingCard {
    +id: int
    +user_id: int
    +product_id: int
    +qty: int
    --
    +user()
    +produk()
}

class ProductReturn {
    +id: int
    +reason: string
    +status: string
    +refund_amount: decimal
    --
    +user()
    +order()
}

class MetodePembayaran {
    +id: int
    +nama_pembayaran: string
}

class MetodePengiriman {
    +id: int
    +nama_pengiriman: string
    +harga: decimal
}

class DeliveryStatus {
    +id: int
    +nama_status: string
}

' ==================== RELATIONSHIPS ====================

User "1" -- "*" Order
User "1" -- "*" ShoppingCard
User "1" -- "*" OrderReview
User "1" -- "*" ProductReturn

Order "1" -- "*" OrderItem
Order "*" -- "1" MetodePembayaran
Order "*" -- "1" MetodePengiriman
Order "*" -- "1" DeliveryStatus

OrderItem "*" -- "1" Produk
OrderItem "1" -- "0..1" OrderReview

Produk "1" -- "*" ShoppingCard

@enduml
```

---

## ERD Database (Bonus)

Kalau mau ERD khusus database, pakai: **https://dbdiagram.io**

```dbml
Table users {
  id int [pk, increment]
  name varchar
  email varchar [unique]
  password varchar
  role varchar
  phone_number varchar
  address_line text
  created_at timestamp
}

Table produk {
  id int [pk, increment]
  nama_produk varchar
  harga decimal
  stok int
  kategori varchar
  diskon_persen int
  created_at timestamp
}

Table orders {
  id int [pk, increment]
  user_id int [ref: > users.id]
  order_number varchar [unique]
  metode_pengiriman_id int [ref: > metode_pengiriman.id]
  metode_pembayaran_id int [ref: > metode_pembayaran.id]
  delivery_status_id int [ref: > delivery_status.id]
  total_bayar decimal
  created_at timestamp
}

Table order_items {
  id int [pk, increment]
  order_id int [ref: > orders.id]
  produk_id int [ref: > produk.id]
  qty int
  harga decimal
  subtotal decimal
}

Table order_reviews {
  id int [pk, increment]
  user_id int [ref: > users.id]
  order_item_id int [ref: > order_items.id]
  produk_id int [ref: > produk.id]
  rating int
  deskripsi_ulasan text
  created_at timestamp
}

Table shopping_card {
  id int [pk, increment]
  user_id int [ref: > users.id]
  product_id int [ref: > produk.id]
  qty int
  ukuran varchar
}

Table product_favorite {
  id int [pk, increment]
  user_id int [ref: > users.id]
  produk_id int [ref: > produk.id]
}

Table returns {
  id int [pk, increment]
  user_id int [ref: > users.id]
  order_id int [ref: > orders.id]
  order_item_id int [ref: > order_items.id]
  produk_id int [ref: > produk.id]
  reason varchar
  status varchar
  refund_amount decimal
  created_at timestamp
}

Table metode_pembayaran {
  id int [pk, increment]
  nama_pembayaran varchar
}

Table metode_pengiriman {
  id int [pk, increment]
  nama_pengiriman varchar
  harga decimal
}

Table delivery_status {
  id int [pk, increment]
  nama_status varchar
}

Table notifications {
  id int [pk, increment]
  user_id int [ref: > users.id]
  type varchar
  title varchar
  message text
  is_read boolean
  created_at timestamp
}
```
