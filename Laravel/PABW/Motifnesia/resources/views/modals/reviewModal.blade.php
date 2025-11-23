<div id="reviewModal" class="custom-modal">
    <div class="modal-content modal-sm">
        <div class="modal-header">
            <h2>Beri Ulasan</h2>
            <span class="close-btn">&times;</span>
        </div>
        
        <div class="modal-body review-form-body">
            <h3 class="review-product-name">Batik Parang</h3> 
            
            <div class="rating-section">
                <p>Beri Rating:</p>
                {{-- Dummy Rating Stars --}}
                <div class="stars">
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star"></span>
                </div>
            </div>
            
            <div class="form-group">
                <p>Deskripsi Ulasan:</p>
                <textarea class="review-textarea" rows="4"></textarea>
            </div>

            <div class="modal-footer">
                <button class="btn-submit-review">Kirim Ulasan</button>
            </div>
        </div>
    </div>
</div>