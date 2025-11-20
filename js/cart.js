// cart.js
document.addEventListener('DOMContentLoaded', function() {
    // delegate remove buttons
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const pid = this.dataset.pid;
            fetch('../actions/remove_from_cart_action.php', {
                method: 'POST',
                body: new URLSearchParams({ p_id: pid })
            }).then(r => r.json()).then(resp => {
                if (resp.status === 'success') {
                    // remove row
                    const row = document.querySelector('tr[data-pid="'+pid+'"]');
                    if (row) row.remove();
                } else {
                    alert(resp.message || 'Failed');
                }
            });
        });
    });

    // qty change
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            let qty = parseInt(this.value);
            if (qty < 1) { this.value = 1; qty = 1; }
            const pid = this.dataset.pid;
            fetch('../actions/update_quantity_action.php', {
                method: 'POST',
                body: new URLSearchParams({ p_id: pid, qty: qty })
            }).then(r => r.json()).then(resp => {
                if (resp.status === 'success') {
                    // update subtotal
                    const row = document.querySelector('tr[data-pid="'+pid+'"]');
                    const priceText = row.querySelector('td:nth-child(3)').innerText.replace(/[^0-9.]/g,'');
                    const newSubtotal = (parseFloat(priceText) * qty).toFixed(2);
                    row.querySelector('.subtotal').innerText = 'GHS ' + newSubtotal;
                    // optionally update total by reloading or recalculating
                    location.reload(); // simplest to show updated totals
                } else {
                    alert(resp.message || 'Failed to update');
                }
            });
        });
    });

    // empty cart
    const emptyBtn = document.getElementById('empty-cart');
    if (emptyBtn) {
        emptyBtn.addEventListener('click', function() {
            if (!confirm('Empty your cart?')) return;
            fetch('../actions/empty_cart_action.php', {
                method: 'POST'
            }).then(r => r.json()).then(resp => {
                if (resp.status === 'success') {
                    location.reload();
                } else {
                    alert(resp.message || 'Failed to empty cart');
                }
            });
        });
    }
});
