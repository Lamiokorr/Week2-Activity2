// checkout.js
document.addEventListener('DOMContentLoaded', function() {
    const payBtn = document.getElementById('simulate-pay');
    if (!payBtn) return;

    payBtn.addEventListener('click', function(e) {
        e.preventDefault();

        const email = prompt("Enter your email for payment:");
        if (!email || !email.includes('@')) return alert("Please enter a valid email.");

        const totalEl = document.querySelector(".total-section strong");
        if (!totalEl) return alert("Total amount not found.");

        let totalText = totalEl.textContent.replace(/[^\d.]/g, "");
        let totalAmount = parseFloat(totalText);

        if (isNaN(totalAmount) || totalAmount <= 0) return alert("Invalid amount.");

        // Fix for Ghana (GHS): Paystack wants amount in whole pesewas (integer only)
        const amountInPesewas = Math.round(totalAmount * 100);

        let handler = PaystackPop.setup({
            key: 'pk_test_2389054a47afefda526108beeac5d4f9be527215',
            email: email,
            amount: amountInPesewas,        // Correct for GHS
            currency: 'GHS',
            ref: 'REF' + Math.floor((Math.random() * 1000000000) + 1), // optional

            callback: function(response) {
                fetch('../actions/process_checkout_action.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ reference: response.reference })
                })
                .then(r => r.json())
                .then(resp => {
                    if (resp.status === 'success') {
                        alert('Payment successful! Order #' + resp.order_id);
                        window.location.href = 'payment_success.php?order_id=' + resp.order_id;
                    } else {
                        alert('Payment failed: ' + (resp.message || 'Please try again'));
                        window.location.href = 'payment_failed.php';
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Network error. Please try again.');
                });
            },
            onClose: function() {
                alert('Payment window closed.');
            }
        });

        handler.openIframe();
    });
});