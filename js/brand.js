document.addEventListener("DOMContentLoaded", () => {
    // CREATE BRAND 
    const brandForm = document.querySelector("form[action='../actions/add_brand_action.php']");
    if (brandForm) {
        brandForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            const brandName = document.getElementById("brand_name").value.trim();
            const categoryId = document.getElementById("cat_id").value;

            //Validate
            if (brandName === "" || categoryId === "") {
                Swal.fire("Error", "Please enter a brand name and select a category.", "error");
                return;
            }

            // Send request
            try {
                const formData = new FormData(brandForm);
                const response = await fetch("../actions/add_brand_action.php", {
                    method: "POST",
                    body: formData
                });

                if (response.ok) {
                    const result = await response.text();
                    Swal.fire("Success", "Brand added successfully!", "success").then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire("Error", "Failed to add brand.", "error");
                }
            } catch (err) {
                Swal.fire("Error", "Something went wrong while adding the brand.", "error");
            }
        });
    }

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
