// checkout.js
document.addEventListener('DOMContentLoaded', function() {
    const payBtn = document.getElementById('simulate-pay');
    if (!payBtn) return;

    payBtn.addEventListener('click', function() {
        // Confirm dialog (replace with modal if desired)
        const ok = confirm('Simulate payment? Click OK to confirm payment.');
        if (!ok) return;

        // send request to backend
        fetch('../actions/process_checkout_action.php', {
            method: 'POST'
        }).then(r => r.json()).then(resp => {
            if (resp.status === 'success') {
                // Show success with invoice/order id
                alert('Payment successful! Order #' + resp.order_id + ' Invoice: ' + resp.invoice_no + ' Amount: GHS ' + resp.amount.toFixed(2));
                // redirect to a simple success page or back to products
                window.location.href = 'payment_success.php?order_id=' + resp.order_id;
            } else {
                alert('Payment failed: ' + (resp.message || 'Unknown error'));
                // optionally redirect to failure
                window.location.href = 'payment_failed.php';
            }
        }).catch(err => {
            alert('Error during checkout');
        });
    });
});
