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
                <textarea id="rmComment" class="review-textarea" rows="4"></textarea>
            </div>

            <input type="hidden" id="rmProductId" value="">

            <div class="modal-footer">
                <button class="btn-submit-review" id="rmSubmit">Kirim Ulasan</button>
            </div>
        </div>
    </div>
</div>

<script>
    // CSRF token
    const RM_CSRF = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';
    let rmRating = 0;

    function openReviewModal(productId, productName) {
        const modal = document.getElementById('reviewModal');
        document.getElementById('rmProductId').value = productId;
        document.getElementById('rmProductName').textContent = productName || 'Produk';
        // ensure modal sits on top and accepts pointer events
        modal.style.zIndex = 9999;
        modal.style.pointerEvents = 'auto';
        modal.style.display = 'block';
        // reset
        rmRating = 0;
        const commentEl = document.getElementById('rmComment');
        commentEl.value = '';
        updateStarUI(0);
        // focus textarea so user can type immediately
        setTimeout(() => { try { commentEl.focus(); } catch(e){} }, 50);
    }


    function closeReviewModal() {
        document.getElementById('reviewModal').style.display = 'none';
    }

    function updateStarUI(value) {
        const stars = document.querySelectorAll('#rmStars .fa-star');
        stars.forEach(s => {
            const v = parseInt(s.getAttribute('data-value'));
            if (v <= value) s.classList.add('checked'); else s.classList.remove('checked');
        });
    }

    document.getElementById('rmStars').addEventListener('click', function(e){
        if (e.target.classList.contains('fa-star')){
            rmRating = parseInt(e.target.getAttribute('data-value')) || 0;
            updateStarUI(rmRating);
        }
    });

    document.getElementById('rmSubmit').addEventListener('click', function(){
        const rawProductId = document.getElementById('rmProductId').value;
        const productId = rawProductId ? parseInt(rawProductId) : null;
        const comment = document.getElementById('rmComment').value;
        // compute rating from checked stars as a fallback
        let rating = rmRating || document.querySelectorAll('#rmStars .fa-star.checked').length || 0;

        // Debug logs
        console.log('Submitting review', { productId, rating, comment });

        if (!productId || rating < 1) {
            alert('Pilih rating dan pastikan product terpilih.');
            return;
        }

        fetch('{{ route('reviews.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': RM_CSRF,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: productId, rating: rating, comment: comment })
        }).then(r=>r.json()).then(data=>{
            console.log('Review store response', data);
            if (data.success) {
                alert('Ulasan berhasil dikirim.');
                closeReviewModal();
                // Optionally refresh reviews UI if present
                if (typeof loadReviews === 'function') {
                    try { loadReviews(productId); } catch(e) { console.log(e); }
                }
            } else if (data.error) {
                alert(data.error);
            } else {
                alert('Terjadi kesalahan.');
            }
        }).catch(err=>{ console.error(err); alert('Gagal mengirim ulasan.'); });
    });
</script>