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
                        Swal.fire('Success', res.message || 'Brand added', 'success').then(function() {
                            location.reload();
                        });
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

    // Keep existing update/delete handlers (vanilla JS using fetch) for now
    // UPDATE BRAND
    document.querySelectorAll("form[action='../actions/update_brand_action.php']").forEach(form => {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const brandNameInput = form.querySelector("input[name='brand_name']");
            const brandName = brandNameInput.value.trim();

            if (brandName === "") {
                Swal.fire("Error", "Brand name cannot be empty.", "error");
                return;
            }

            try {
                const formData = new FormData(form);
                const response = await fetch("../actions/update_brand_action.php", {
                    method: "POST",
                    body: formData
                });

                if (response.ok) {
                    Swal.fire("Success", "Brand updated successfully!", "success").then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire("Error", "Failed to update brand.", "error");
                }
            } catch (err) {
                Swal.fire("Error", "Something went wrong while updating the brand.", "error");
            }
        });
    });

    // DELETE BRAND
    document.querySelectorAll("form[action='../actions/delete_brand_action.php']").forEach(form => {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            Swal.fire({
                title: "Are you sure?",
                text: "This will permanently delete the brand.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel"
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const formData = new FormData(form);
                        const response = await fetch("../actions/delete_brand_action.php", {
                            method: "POST",
                            body: formData
                        });

                        if (response.ok) {
                            Swal.fire("Deleted!", "Brand has been deleted.", "success").then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire("Error", "Failed to delete brand.", "error");
                        }
                    } catch (err) {
                        Swal.fire("Error", "Brand failed to delete.", "error");
                    }
                }
            });
        });
    });
});
