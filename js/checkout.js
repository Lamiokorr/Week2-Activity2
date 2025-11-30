// checkout.js
document.addEventListener('DOMContentLoaded', function () {
    const payBtn = document.getElementById('simulate-pay');
    if (!payBtn) return;

    payBtn.addEventListener('click', function (e) {
        e.preventDefault();

        const email = prompt("Enter your email for payment:");
        if (!email || !email.includes('@')) return alert("Please enter a valid email.");

        const totalEl = document.querySelector(".total-section strong");
        if (!totalEl) return alert("Total amount not found.");

        let totalText = totalEl.textContent.replace(/[^\d.]/g, "");
        let totalAmount = parseFloat(totalText);

        if (isNaN(totalAmount) || totalAmount <= 0) return alert("Invalid amount.");

        const amountInPesewas = Math.round(totalAmount * 100);

        let handler = PaystackPop.setup({
            key: 'pk_test_2389054a47afefda526108beeac5d4f9be527215',
            email: email,
            amount: amountInPesewas,
            currency: 'GHS',
            ref: 'REF' + Math.floor((Math.random() * 1000000000) + 1),

            callback: function (response) {
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
                        // PASS ALL DEBUG INFO TO FAILED PAGE
                        const debugData = {
                            server_error: resp.message || 'Unknown error',
                            paystack_response: resp.debug?.paystack_response || null,
                            cart_total: resp.debug?.cart_total || null,
                            paid_amount: resp.debug?.paid_amount || null,
                            amount_match: resp.debug?.difference <= 0.01,
                            sql_error: resp.debug?.sql_error || null
                        };

                        const encoded = btoa(JSON.stringify(debugData));
                        const url = new URL('payment_failed.php');
                        url.searchParams.set('debug', encoded);
                        if (resp.order_id) url.searchParams.set('order', resp.order_id);
                        if (resp.amount) url.searchParams.set('amount', resp.amount);

                        window.location.href = url.toString();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Network error. Please try again.');
                    window.location.href = 'payment_failed.php';
                });
            },

            onClose: function () {
                alert('Payment window closed.');
            }
        });

        handler.openIframe();
    });
});