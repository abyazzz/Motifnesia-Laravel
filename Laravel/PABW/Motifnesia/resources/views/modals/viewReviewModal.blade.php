<div id="viewReviewModal" class="custom-modal" style="display:none;">
    <div class="modal-content modal-sm">
        <div class="modal-header">
            <h2 id="vrTitle">Lihat Ulasan</h2>
            <span class="close-btn" onclick="closeViewReviewModal()">&times;</span>
        </div>

        <div class="modal-body review-form-body">
            <h3 id="vrProductName" class="review-product-name">Produk</h3>
            <div id="vrList">
                <!-- reviews will be loaded here -->
                <p style="color:#666;">Memuat ulasan...</p>
            </div>
        </div>
    </div>
</div>

<script>
    const VR_CSRF = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';

    function openViewReviewModal(productId, productName) {
        document.getElementById('vrProductName').textContent = productName || 'Produk';
        document.getElementById('viewReviewModal').style.display = 'block';
        loadReviews(productId);
    }

    function closeViewReviewModal() {
        document.getElementById('viewReviewModal').style.display = 'none';
    }

    function loadReviews(productId) {
        const list = document.getElementById('vrList');
        list.innerHTML = '<p style="color:#666;">Memuat ulasan...</p>';
        fetch('/products/' + productId + '/reviews')
            .then(r=>r.json())
            .then(data=>{
                if (!Array.isArray(data) || data.length === 0) {
                    list.innerHTML = '<p>Tidak ada ulasan untuk produk ini.</p>';
                    return;
                }
                let html = '';
                data.forEach(rv => {
                    html += '<div style="border-bottom:1px solid #eee; padding:8px 0;">';
                    html += '<div style="font-weight:600;">' + (rv.user ? rv.user.name : 'Pengguna') + ' <span style="color:#999; font-weight:400; font-size:12px;">' + new Date(rv.created_at).toLocaleString() + '</span></div>';
                    html += '<div class="stars">';
                    for (let i=1;i<=5;i++) { html += '<span class="fa fa-star ' + (i<=rv.rating ? 'checked' : '') + '"></span>'; }
                    html += '</div>';
                    html += '<div style="margin-top:6px; color:#333;">' + (rv.comment ? rv.comment : '') + '</div>';
                    html += '</div>';
                });
                list.innerHTML = html;
            }).catch(err=>{ console.error(err); list.innerHTML = '<p>Error memuat ulasan.</p>'; });
    }
</script>