// checkout.js
document.addEventListener('DOMContentLoaded', function() {
    const payBtn = document.getElementById('simulate-pay');
    if (!payBtn) return;

    payBtn.addEventListener('click', function(e) {
        e.preventDefault();

        // Get user email and total amount
        const email = prompt("Enter your email for payment:");
        if (!email) return alert("Email is required to proceed.");

        // Get total amount from the page (adjust selector if needed)
        const totalEl = document.querySelector(".total-section strong");
        if (!totalEl) return alert("Total amount not found.");
        let totalText = totalEl.textContent.replace(/[^\d.]/g, ""); 
        const amount = parseFloat(totalText);
        if (isNaN(amount) || amount <= 0) return alert("Invalid amount.");

        // Setup Paystack
        let handler = PaystackPop.setup({
            key: 'pk_test_2389054a47afefda526108beeac5d4f9be527215', 
            email: email,
            amount: amount * 100, 
            currency: 'GHS',
            callback: function(response) {
                // Send reference to PHP backend for verification
                fetch('../actions/process_checkout_action.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ reference: response.reference })
                })
                .then(r => r.json())
                .then(resp => {
                    if (resp.status === 'success') {
                        alert('Payment successful! Order #' + resp.order_id + ' Invoice: ' + resp.invoice_no + ' Amount: GHS ' + resp.amount.toFixed(2));
                        window.location.href = 'payment_success.php?order_id=' + resp.order_id;
                    } else {
                        alert('Payment verification failed: ' + (resp.message || 'Unknown error'));
                        window.location.href = 'payment_failed.php';
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error verifying payment.');
                });
            },
            onClose: function() {
                alert('Payment window closed.');
            }
        });

        handler.openIframe();
    });
});
