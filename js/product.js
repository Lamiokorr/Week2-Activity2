/* jQuery-based product form submit and file upload
   Submits FormData via AJAX (processData:false, contentType:false)
   Matches field names used by `add_product_action.php` and `update_product_action.php`.
*/
$(function() {
    var $form = $('#productForm');
    var $save = $('#saveProductBtn');
    var $file = $('#product_image');
    var $modal = $('#feedbackModal');
    var $modalMessage = $('#modalMessage');

    function showModal(message, success = true) {
        if ($modalMessage.length) {
            $modalMessage.text(message).css('color', success ? 'green' : 'red');
            $modal.show();
            setTimeout(function() { $modal.hide(); }, 3000);
        } else {
            alert(message);
        }
    }

    function validateProductForm() {
        var name = $.trim($('#product_title').val() || '');
        var price = $.trim($('#product_price').val() || '');
        var brand = $('#brand_id').val() || '';
        var category = $('#category_id').val() || '';

        if (!name || !price || !brand || !category) {
            showModal('Please fill all required fields!', false);
            return false;
        }
        if (isNaN(price) || parseFloat(price) <= 0) {
            showModal('Please enter a valid price!', false);
            return false;
        }
        return true;
    }

    $form.on('submit', function(e) {
        e.preventDefault();

        if (!validateProductForm()) return;

        var fd = new FormData(this);

        // Add compatibility keys the server may expect
        if (fd.has('category_id') && !fd.has('product_cat')) fd.append('product_cat', fd.get('category_id'));
        if (fd.has('brand_id') && !fd.has('product_brand')) fd.append('product_brand', fd.get('brand_id'));

        var productId = fd.get('product_id') || '';
        var url = productId ? '../actions/update_product_action.php' : '../actions/add_product_action.php';

        $save.prop('disabled', true);

        $.ajax({
            url: url,
            method: 'POST',
            data: fd,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(res) {
                if (res && res.success) {
                    showModal(productId ? 'Product updated successfully!' : 'Product added successfully!');
                    if (!productId) $form[0].reset();
                } else {
                    var msg = (res && res.message) ? res.message : 'Server returned an error';
                    showModal((productId ? 'Failed to update product: ' : 'Failed to add product: ') + msg, false);
                }
            },
            error: function(xhr, status, err) {
                console.error('AJAX Error:', status, err, xhr.responseText);
                showModal('Request failed. See console for details.', false);
            },
            complete: function() {
                $save.prop('disabled', false);
            }
        });
    });

    // Optional: image-only upload handler (keeps compatibility)
    $file.on('change', function() {
        var f = this.files && this.files[0];
        if (!f) return;
        var upload = new FormData();
        upload.append('product_image', f);
        upload.append('productImage', f);

        $.ajax({
            url: '../actions/upload_product_image_action.php',
            method: 'POST',
            data: upload,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(res) {
                if (res && res.success) showModal('Image uploaded successfully!');
                else showModal('Image upload failed: ' + (res && res.message ? res.message : 'unknown'), false);
            },
            error: function() {
                showModal('Image upload failed (request error)', false);
            }
        });
    });
});
