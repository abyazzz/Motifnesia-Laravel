# Order Review System Implementation

## Overview
Sistem review pesanan yang memungkinkan user memberikan ulasan (rating + deskripsi) untuk produk yang sudah mereka beli dan sampai ke tujuan.

## Database Structure

### 1. Table `order_reviews`
```sql
CREATE TABLE order_reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    order_item_id INT UNSIGNED,
    checkout_id INT UNSIGNED,
    product_id INT UNSIGNED,
    rating TINYINT COMMENT 'Rating 1-5',
    deskripsi_ulasan TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (order_item_id) REFERENCES checkout_items(id) ON DELETE CASCADE,
    FOREIGN KEY (checkout_id) REFERENCES checkout(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES produk(id) ON DELETE CASCADE,
    
    INDEX (user_id, product_id),
    INDEX (order_item_id)
);
```

### 2. Column `rating` added to `produk` table
```sql
ALTER TABLE produk ADD COLUMN rating DECIMAL(3,2) DEFAULT 0 COMMENT 'Average rating 0-5.00';
```

### 3. Supporting Tables Created
- `status_transaksi`: Status pengiriman (Menunggu Konfirmasi, Diproses, Dikirim, Sampai)
- `checkout`: Order header
- `checkout_items`: Order line items

## Key Features

### 1. **Modal Riwayat Pembelian**
- Location: `resources/views/customer/modals/purchaseHistoryModal.blade.php`
- Triggered by: Button "Riwayat Pembelian" in User Profile
- Displays: All checkout items from user's orders
- Each item shows:
  - Product image, name, size, quantity, price
  - Delivery status badge
  - Review button (conditional)

### 2. **Review Button Logic**
```php
if (status_id == 4 && !has_review) {
    // Button "Beri Ulasan" - enabled
} else if (has_review) {
    // Button "Lihat Ulasan" - show existing review
} else {
    // Button "Beri Ulasan" - disabled (gray)
    // Alert: "Pesanan belum sampai" when clicked
}
```

### 3. **Modal Beri Ulasan**
- Location: `resources/views/customer/modals/reviewModal.blade.php`
- Features:
  - 5-star rating selector (★★★★★)
  - Textarea for review description
  - Submit button
- API: `POST /customer/reviews/store`
- Data sent:
  ```json
  {
    "order_item_id": 123,
    "product_id": 45,
    "rating": 5,
    "deskripsi_ulasan": "Produk sangat bagus!"
  }
  ```

### 4. **Modal Lihat Ulasan**
- Location: `resources/views/customer/modals/viewReviewModal.blade.php`
- Features:
  - Shows user's existing review
  - Displays rating as stars
  - Shows review description
  - "← Back" button to return to purchase history
- API: `GET /customer/reviews/{orderItemId}`

### 5. **Automatic Product Rating Calculation**
When a review is submitted:
```php
private function updateProductRating($productId)
{
    // Calculate average rating from all reviews
    $avgRating = OrderReview::where('product_id', $productId)->avg('rating');
    
    // Update produk.rating column
    Produk::where('id', $productId)->update([
        'rating' => round($avgRating, 2)
    ]);
}
```

## Files Created/Modified

### New Files Created:
1. **Migrations:**
   - `2025_12_11_135959_create_status_transaksi_table.php`
   - `2025_12_11_140000_create_checkout_table.php`
   - `2025_12_11_140001_create_checkout_items_table.php`
   - `2025_12_11_150000_create_order_reviews_table.php`
   - `2025_12_11_150001_add_rating_to_produk_table.php`

2. **Models:**
   - `app/Models/OrderReview.php`
   - `app/Models/CheckoutItem.php`
   - `app/Models/Checkout.php`
   - `app/Models/StatusTransaksi.php`

3. **Controllers:**
   - `app/Http/Controllers/Customer/OrderReviewController.php`

4. **Seeders:**
   - `database/seeders/StatusTransaksiSeeder.php`

### Modified Files:
1. **Controllers:**
   - `app/Http/Controllers/Customer/PurchaseHistoryController.php` - Updated to fetch real data from database
   - `app/Http/Controllers/Customer/UserProfileController.php` - Updated to use Auth

2. **Views:**
   - `resources/views/customer/modals/purchaseHistoryModal.blade.php` - Complete redesign with real data
   - `resources/views/customer/modals/reviewModal.blade.php` - Updated for order reviews
   - `resources/views/customer/modals/viewReviewModal.blade.php` - Updated to show user's review

3. **Routes:**
   - `routes/web.php` - Added order review routes

## API Endpoints

### 1. Store Review
```
POST /customer/reviews/store
Content-Type: application/json

Request Body:
{
    "order_item_id": 123,
    "product_id": 45,
    "rating": 5,
    "deskripsi_ulasan": "Excellent product!"
}

Response:
{
    "success": true,
    "message": "Ulasan berhasil dikirim!",
    "review": { ... }
}
```

### 2. Get Review
```
GET /customer/reviews/{orderItemId}

Response:
{
    "success": true,
    "review": {
        "rating": 5,
        "deskripsi_ulasan": "Excellent product!",
        "created_at": "11 Des 2025"
    }
}
```

## User Flow

```
1. User → Profile Page
2. Click "Riwayat Pembelian" button
3. Modal opens showing all purchased items
4. For each item:
   a. If status != "Sampai": Button disabled (gray)
   b. If status == "Sampai" && no review: "Beri Ulasan" (green, enabled)
   c. If already reviewed: "Lihat Ulasan" (blue)
   
5. Click "Beri Ulasan":
   → Opens review modal
   → Select 1-5 stars
   → Write review description
   → Click "Kirim Ulasan"
   → Data saved to order_reviews table
   → Product rating updated automatically
   → Return to purchase history modal
   → Button changes to "Lihat Ulasan"

6. Click "Lihat Ulasan":
   → Opens view review modal
   → Shows rating (stars) and description
   → "← Back" button to return
```

## Business Logic

### Review Rules:
1. **One review per order item**: User can review the same product multiple times if purchased in different orders
2. **Review only after delivery**: Button only enabled when status_id = 4 (Sampai)
3. **No edit/delete**: Once submitted, review is permanent (can be extended later)
4. **Automatic rating**: Product rating auto-calculated as average of all reviews

### Product Rating Formula:
```
produk.rating = ROUND(AVG(order_reviews.rating WHERE product_id = X), 2)
```

Example:
- Product A has 3 reviews: 5, 4, 5
- Average = (5+4+5)/3 = 4.67
- `produk.rating` = 4.67

## Testing Checklist

### Database:
- [x] Migrations run successfully
- [x] Foreign keys work correctly
- [x] Status transaksi seeded (1-4)

### UI:
- [ ] Purchase history modal opens on button click
- [ ] Items display correctly with status badges
- [ ] "Beri Ulasan" button disabled when status != Sampai
- [ ] "Beri Ulasan" button enabled when status = Sampai
- [ ] Star rating selector works (1-5 stars)
- [ ] Review submission successful
- [ ] Button changes to "Lihat Ulasan" after submission
- [ ] "Lihat Ulasan" shows correct review data
- [ ] Back button returns to purchase history

### Backend:
- [ ] Review stores correctly with all fields
- [ ] Product rating updates automatically
- [ ] User can only review their own orders
- [ ] Cannot review same order_item twice
- [ ] Average rating calculation correct

## Next Steps (Optional Enhancements)

1. **Edit/Delete Review**: Allow users to modify their reviews
2. **Review Images**: Let users upload product photos
3. **Admin Moderation**: Admin can approve/reject reviews
4. **Helpful Votes**: Other users can vote if review is helpful
5. **Reply to Reviews**: Seller can reply to customer reviews
6. **Review Sorting**: Sort by rating, date, helpfulness
7. **Review Filter**: Filter by rating (5-star, 4-star, etc.)

## Notes

- User must be authenticated (Auth) to access review features
- All modals use inline styles for simplicity (can be refactored to external CSS later)
- Rating displayed as stars (★) using Font Awesome
- Product rating column uses DECIMAL(3,2) to store values like 4.67
- Review description is optional but recommended for better user experience
