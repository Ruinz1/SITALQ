<div>
    <div id="snap-container"></div>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        // Mendengarkan event ketika modal dibuka
        window.addEventListener('payment-modal-ready', function (event) {
            const snapToken = event.detail.snapToken;
            
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    // Kirim event ke Filament untuk memperbarui status
                    window.dispatchEvent(new CustomEvent('payment-success', {
                        detail: result
                    }));
                    // Tutup modal
                    window.closeModal();
                    // Refresh halaman
                    window.location.reload();
                },
                onPending: function(result) {
                    window.dispatchEvent(new CustomEvent('payment-pending', {
                        detail: result
                    }));
                    window.closeModal();
                    window.location.reload();
                },
                onError: function(result) {
                    window.dispatchEvent(new CustomEvent('payment-error', {
                        detail: result
                    }));
                    window.closeModal();
                },
                onClose: function() {
                    window.closeModal();
                }
            });
        });
    </script>
</div> 