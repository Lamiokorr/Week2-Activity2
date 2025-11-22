$(function() {
    // CREATE BRAND - uses jQuery and expects input name='name' in #addBrandForm
    var $addForm = $('#addBrandForm');
    if ($addForm.length) {
        $addForm.on('submit', function(e) {
            e.preventDefault();
            var name = $.trim($('#brand_name').val());
            if (!name) {
                Swal.fire('Validation error', 'Please enter a brand name', 'error');
                return;
            }

            $.ajax({
                url: '../actions/add_brand_action.php',
                method: 'POST',
                dataType: 'json',
                data: { name: name },
                success: function(res) {
                    if (res && res.status === 'success') {
                        var brandId = res.brand_id || '';
                        var $firstCard = $('.card.mb-4.shadow-sm').first();
                        var $tbody;
                        if ($firstCard.length) {
                            $tbody = $firstCard.find('tbody');
                        } else {
                            var cardHtml = '\n                                <div class="card mb-4 shadow-sm">\n                                    <div class="card-header bg-info text-white">\n                                        <strong>Uncategorized</strong>\n                                    </div>\n                                    <div class="card-body">\n                                        <table class="table align-middle">\n                                            <thead>\n                                                <tr>\n                                                    <th>#</th>\n                                                    <th>Brand Name</th>\n                                                    <th>Actions</th>\n                                                </tr>\n                                            </thead>\n                                            <tbody></tbody>\n                                        </table>\n                                    </div>\n                                </div>\n                            ';
                            $('.container.py-5').first().append(cardHtml);
                            $tbody = $('.card.mb-4.shadow-sm').last().find('tbody');
                        }

                        var escName = $('<div>').text(name).html();
                        var actionsHtml = '' +
                            '<form action="../actions/update_brand_action.php" method="POST" class="d-inline">' +
                                '<input type="hidden" name="brand_id" value="' + brandId + '">' +
                                '<input type="hidden" name="id" value="' + brandId + '">' +
                                '<input type="text" name="brand_name" value="' + escName + '" class="form-control d-inline w-auto" required>' +
                                '<button type="submit" class="btn btn-sm btn-warning">Update</button>' +
                            '</form>' +
                            '<form action="../actions/delete_brand_action.php" method="POST" class="d-inline ms-1">' +
                                '<input type="hidden" name="brand_id" value="' + brandId + '">' +
                                '<button type="submit" class="btn btn-sm btn-danger">Delete</button>' +
                            '</form>';

                        var $newRow = $('<tr>')
                            .append($('<td>').text(1))
                            .append($('<td>').text(name))
                            .append($('<td>').html(actionsHtml));

                        $tbody.prepend($newRow);
                        $tbody.find('tr').each(function(i) { $(this).find('td').first().text(i + 1); });

                        Swal.fire('Success', res.message || 'Brand added', 'success');
                    } else {
                        Swal.fire('Error', (res && res.message) || 'Unable to add brand', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Request failed', 'error');
                }
            });
        });
    }

    // UPDATE BRAND (delegated, jQuery)
    $(document).on('submit', "form[action='../actions/update_brand_action.php']", function(e) {
        e.preventDefault();
        var $form = $(this);
        var brandName = $.trim($form.find("input[name='brand_name'], input[name='name']").val());
        var brandId = $form.find("input[name='brand_id'], input[name='id']").val();

        if (!brandName) {
            Swal.fire('Error', 'Brand name cannot be empty.', 'error');
            return;
        }

        $.ajax({
            url: '../actions/update_brand_action.php',
            method: 'POST',
            dataType: 'json',
            // send both key names for compatibility with various handlers
            data: { id: brandId, name: brandName, brand_id: brandId, brand_name: brandName },
            success: function(res) {
                if (res && res.status === 'success') {
                    Swal.fire('Success', res.message || 'Brand updated successfully!', 'success');
                    // update the displayed brand name cell (2nd td)
                    $form.closest('tr').find('td').eq(1).text(brandName);
                } else {
                    Swal.fire('Error', (res && res.message) || 'Failed to update brand.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Something went wrong while updating the brand.', 'error');
            }
        });
    });

    // DELETE BRAND (delegated, jQuery)
    $(document).on('submit', "form[action='../actions/delete_brand_action.php']", function(e) {
        e.preventDefault();
        var $form = $(this);

        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the brand.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../actions/delete_brand_action.php',
                    method: 'POST',
                    data: $form.serialize(),
                    success: function(res) {
                        var text = (typeof res === 'object') ? JSON.stringify(res) : String(res || '');
                        if (text.toLowerCase().indexOf('success') !== -1) {
                            Swal.fire('Deleted!', 'Brand has been deleted.', 'success');
                            // remove row
                            var $tbody = $form.closest('tbody');
                            $form.closest('tr').remove();
                            // re-number rows in this tbody
                            $tbody.find('tr').each(function(i) { $(this).find('td').first().text(i + 1); });

                            // if no more rows in this tbody, remove the whole card and show alert if no cards left
                            if ($tbody.find('tr').length === 0) {
                                var $card = $tbody.closest('.card');
                                $card.remove();
                                if ($('.card.mb-4.shadow-sm').length === 0) {
                                    $('.container.py-5').first().append('<div class="alert alert-warning">No brands found. Add one above!</div>');
                                }
                            }
                        } else {
                            Swal.fire('Error', text || 'Unable to delete brand', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Brand failed to delete.', 'error');
                    }
                });
            }
        });
    });
});
