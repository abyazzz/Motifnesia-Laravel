<style>
    .back-btn {
        display: inline-block;
        padding: 8px 16px;
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .back-btn:hover {
        background: #5a6268;
    }

    .review-item {
        border-bottom: 1px solid #eee;
        padding: 16px 0;
    }

    .review-item:last-child {
        border-bottom: none;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .review-user {
        font-weight: 600;
        font-size: 15px;
    }

    .review-date {
        color: #999;
        font-size: 12px;
    }

    .review-stars {
        display: flex;
        gap: 4px;
        margin-bottom: 8px;
        font-size: 18px;
    }

    .review-text {
        color: #333;
        line-height: 1.6;
        font-size: 14px;
    }
</style>

<div id="viewReviewModal" class="custom-modal" style="display:none;">
    <div class="modal-content modal-sm">
        <div class="modal-header">
            <button class="back-btn" onclick="backToPurchaseHistory()">← Back</button>
            <h2 id="vrTitle">Ulasan Anda</h2>
            <span class="close-btn" onclick="closeViewReviewModal()">&times;</span>
        </div>

        <div class="modal-body review-form-body">
            <h3 id="vrProductName" class="review-product-name">Produk</h3>
            <div id="vrContent">
                <p style="color:#666;">Memuat ulasan...</p>
            </div>
        </div>
    </div>
</div>

<script>
    function openViewReviewModal(orderItemId, productName) {
        document.getElementById('vrProductName').textContent = productName || 'Produk';
        document.getElementById('viewReviewModal').style.display = 'block';
        loadUserReview(orderItemId);
    }

    function closeViewReviewModal() {
        document.getElementById('viewReviewModal').style.display = 'none';
    }

    function backToPurchaseHistory() {
        closeViewReviewModal();
        document.getElementById('purchaseHistoryModal').classList.add('show');
    }

    function loadUserReview(orderItemId) {
        const content = document.getElementById('vrContent');
        content.innerHTML = '<p style="color:#666;">Memuat ulasan...</p>';
        
        console.log('Loading review for orderItemId:', orderItemId);
        
        fetch('/order-reviews/' + orderItemId, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
            }
        })
        .then(r => {
            console.log('Response status:', r.status);
            return r.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success && data.review) {
                const rv = data.review;
                let html = '<div class="review-item">';
                html += '<div class="review-header">';
                html += '<span class="review-user">Anda</span>';
                html += '<span class="review-date">' + rv.created_at + '</span>';
                html += '</div>';
                html += '<div class="review-stars">';
                for (let i = 1; i <= 5; i++) {
                    html += '<span class="fa fa-star' + (i <= rv.rating ? ' checked' : '') + '"></span>';
                }
                html += '</div>';
                html += '<div class="review-text">' + (rv.deskripsi_ulasan || 'Tidak ada deskripsi') + '</div>';
                html += '</div>';
                content.innerHTML = html;
            } else {
                content.innerHTML = '<p>Ulasan tidak ditemukan.</p>';
            }
        })
        .catch(err => {
            console.error(err);
            content.innerHTML = '<p>Error memuat ulasan.</p>';
        });
    }

    // Close modal on outside click
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('viewReviewModal');
        if (event.target === modal) {
            closeViewReviewModal();
        }
    });
</script>