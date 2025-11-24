// public/js/modals.js

document.addEventListener('DOMContentLoaded', function() {
    // Definisikan semua variabel modal dan tombol
    const historyModal = document.getElementById('purchaseHistoryModal');
    const reviewModal = document.getElementById('reviewModal');
    const viewReviewModal = document.getElementById('viewReviewModal');
    const openHistoryBtn = document.getElementById('openHistoryModalBtn');

    // Cek apakah tombol Riwayat Pembelian ada sebelum menambahkan event listener
    if (openHistoryBtn) {
        // Fungsi membuka modal spesifik
        const openModal = (modal) => {
            modal.style.display = 'block';
        };

        // Fungsi menutup modal
        const closeModal = (modal) => {
            modal.style.display = 'none';
        };

        // Event listener untuk tombol Riwayat Pembelian
        openHistoryBtn.onclick = () => openModal(historyModal);
        
        // Event listeners untuk tombol tutup (X) di semua modal
        document.querySelectorAll('.custom-modal .close-btn').forEach(btn => {
            btn.onclick = (e) => closeModal(e.target.closest('.custom-modal'));
        });

        // Event listeners untuk tombol aksi di modal Riwayat Pembelian
        document.querySelectorAll('.btn-ulasan').forEach(btn => {
            btn.onclick = () => {
                const productId = btn.getAttribute('data-product-id');
                const productNameEl = btn.closest('.history-item') ? btn.closest('.history-item').querySelector('.product-name-hist') : null;
                const productName = productNameEl ? productNameEl.textContent.trim() : '';
                closeModal(historyModal);
                // prefer using modal helper that sets product context
                if (typeof openReviewModal === 'function') {
                    openReviewModal(productId, productName);
                } else {
                    openModal(reviewModal);
                }
            };
        });

        document.querySelectorAll('.btn-view-ulasan').forEach(btn => {
            btn.onclick = () => {
                const productId = btn.getAttribute('data-product-id');
                const productNameEl = btn.closest('.history-item') ? btn.closest('.history-item').querySelector('.product-name-hist') : null;
                const productName = productNameEl ? productNameEl.textContent.trim() : '';
                closeModal(historyModal);
                if (typeof openViewReviewModal === 'function') {
                    openViewReviewModal(productId, productName);
                } else {
                    openModal(viewReviewModal);
                }
            };
        });

        // Menutup modal jika klik di luar area konten modal
        window.onclick = function(event) {
            if (event.target.classList.contains('custom-modal')) {
                event.target.style.display = 'none';
            }
        }
    }
});