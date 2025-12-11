<style>
    .modal-sm {
        max-width: 500px !important;
    }

    .review-form-body {
        padding: 20px;
    }

    .review-product-name {
        font-size: 18px;
        margin-bottom: 20px;
        color: #333;
    }

    .rating-section {
        margin-bottom: 20px;
    }

    .rating-section p {
        margin-bottom: 8px;
        font-weight: 500;
    }

    .stars {
        display: flex;
        gap: 8px;
        font-size: 32px;
    }

    .stars .fa-star {
        cursor: pointer;
        color: #ddd;
        transition: color 0.2s;
    }

    .stars .fa-star:hover,
    .stars .fa-star.checked {
        color: #ffc107;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group p {
        margin-bottom: 8px;
        font-weight: 500;
    }

    .review-textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-family: inherit;
        font-size: 14px;
        resize: vertical;
    }

    .review-textarea:focus {
        outline: none;
        border-color: #4CAF50;
    }

    .btn-submit-review {
        width: 100%;
        padding: 12px;
        background: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-submit-review:hover {
        background: #45a049;
    }
</style>

<div id="reviewModal" class="custom-modal" style="display:none;">
    <div class="modal-content modal-sm">
        <div class="modal-header">
            <h2 id="rmTitle">Beri Ulasan</h2>
            <span class="close-btn" onclick="closeReviewModal()">&times;</span>
        </div>

        <div class="modal-body review-form-body">
            <h3 id="rmProductName" class="review-product-name">Produk</h3>

            <div class="rating-section">
                <p>Beri Rating:</p>
                <div class="stars" id="rmStars">
                    <span class="fa fa-star" data-value="1"></span>
                    <span class="fa fa-star" data-value="2"></span>
                    <span class="fa fa-star" data-value="3"></span>
                    <span class="fa fa-star" data-value="4"></span>
                    <span class="fa fa-star" data-value="5"></span>
                </div>
            </div>

            <div class="form-group">
                <p>Deskripsi Ulasan:</p>
                <textarea id="rmComment" class="review-textarea" rows="4" placeholder="Bagikan pengalaman Anda dengan produk ini..."></textarea>
            </div>

            <input type="hidden" id="rmOrderItemId" value="">
            <input type="hidden" id="rmProductId" value="">

            <div class="modal-footer">
                <button class="btn-submit-review" id="rmSubmit">Kirim Ulasan</button>
            </div>
        </div>
    </div>
</div>

<script>
    const RM_CSRF = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';
    let rmRating = 0;

    function openReviewModal(orderItemId, productName, productId) {
        console.log('Opening modal with:', { orderItemId, productName, productId });
        
        const modal = document.getElementById('reviewModal');
        document.getElementById('rmOrderItemId').value = orderItemId;
        document.getElementById('rmProductId').value = productId;
        document.getElementById('rmProductName').textContent = productName || 'Produk';
        
        console.log('Set hidden values - OrderItemId:', document.getElementById('rmOrderItemId').value, 'ProductId:', document.getElementById('rmProductId').value);
        
        modal.style.zIndex = 9999;
        modal.style.pointerEvents = 'auto';
        modal.style.display = 'block';
        
        // Reset form
        rmRating = 0;
        document.getElementById('rmComment').value = '';
        updateStarUI(0);
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').style.display = 'none';
    }

    function updateStarUI(value) {
        const stars = document.querySelectorAll('#rmStars .fa-star');
        stars.forEach(s => {
            const v = parseInt(s.getAttribute('data-value'));
            if (v <= value) {
                s.classList.add('checked');
            } else {
                s.classList.remove('checked');
            }
        });
    }

    // Star rating click handler
    document.addEventListener('DOMContentLoaded', function() {
        const starsContainer = document.getElementById('rmStars');
        if (starsContainer) {
            starsContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('fa-star')) {
                    rmRating = parseInt(e.target.getAttribute('data-value')) || 0;
                    console.log('Rating selected:', rmRating);
                    updateStarUI(rmRating);
                }
            });
        }
        
        // Button click handler for opening review modal
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-open-review') || e.target.closest('.btn-open-review')) {
                const btn = e.target.classList.contains('btn-open-review') ? e.target : e.target.closest('.btn-open-review');
                const orderItemId = btn.getAttribute('data-order-item-id');
                const productId = btn.getAttribute('data-produk-id');
                const productName = btn.getAttribute('data-product-name');
                console.log('Button clicked, opening modal with:', { orderItemId, productId, productName });
                openReviewModal(orderItemId, productName, productId);
            }
        });
    });

    document.getElementById('rmSubmit').addEventListener('click', function() {
        const orderItemId = document.getElementById('rmOrderItemId').value;
        const productId = document.getElementById('rmProductId').value;
        const comment = document.getElementById('rmComment').value.trim();
        const rating = rmRating;

        console.log('Submit clicked - Rating:', rating, 'OrderItemId:', orderItemId);

        if (!orderItemId || rating < 1) {
            alert('Pilih rating terlebih dahulu (1-5 bintang)');
            return;
        }

        if (!comment) {
            alert('Mohon isi deskripsi ulasan Anda');
            return;
        }

        // Disable button while submitting
        const submitBtn = document.getElementById('rmSubmit');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Mengirim...';

        fetch('{{ route('customer.order.reviews.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': RM_CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                order_item_id: orderItemId,
                product_id: productId,
                rating: rating,
                deskripsi_ulasan: comment
            })
        }).then(r => r.json()).then(data => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Kirim Ulasan';
            
            if (data.success) {
                alert('✅ Ulasan berhasil dikirim! Terima kasih atas feedback Anda.');
                closeReviewModal();
                closePurchaseHistoryModal();
                // Reload page to update purchase history
                window.location.reload();
            } else {
                alert(data.message || 'Gagal mengirim ulasan. Silakan coba lagi.');
            }
        }).catch(err => {
            console.error(err);
            submitBtn.disabled = false;
            submitBtn.textContent = 'Kirim Ulasan';
            alert('Gagal mengirim ulasan. Silakan coba lagi.');
        });
    });

    // Close modal on outside click
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('reviewModal');
        if (event.target === modal) {
            closeReviewModal();
        }
    });
</script>