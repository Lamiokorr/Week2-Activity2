document.addEventListener("DOMContentLoaded", function () {
    const productForm = document.getElementById("productForm");
    const saveBtn = document.getElementById("saveProductBtn");
    const fileInput = document.getElementById("product_image");
    const modal = document.getElementById("feedbackModal");
    const modalMessage = document.getElementById("modalMessage");

    //Show feedback modal
    function showModal(message, success = true) {
        modalMessage.textContent = message;
        modalMessage.style.color = success ? "green" : "red";
        modal.style.display = "block";

        setTimeout(() => {
            modal.style.display = "none";
        }, 3000);
    }

    //Validate form inputs
    function validateProductForm() {
        const name = document.getElementById("product_title").value.trim();
        const price = document.getElementById("product_price").value.trim();
        const brand = document.getElementById("brand_id").value;
        const category = document.getElementById("category_id").value;

        if (!name || !price || !brand || !category) {
            showModal("Please fill all required fields!", false);
            return false;
        }

        if (isNaN(price) || parseFloat(price) <= 0) {
            showModal("Please enter a valid price!", false);
            return false;
        }

        return true;
    }

    // Handle Add/Update Product via form submit
    productForm?.addEventListener("submit", async function (e) {
        e.preventDefault();

        if (!validateProductForm()) return;

        const formData = new FormData(productForm);

        // determine whether this is an update or create by product_id
        const productId = formData.get('product_id') || '';
        const url = productId ? "../actions/update_product_action.php" : "../actions/add_product_action.php";

        try {
            const response = await fetch(url, {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showModal(productId ? "Product updated successfully!" : "Product added successfully!");
                if (!productId) productForm.reset();
            } else {
                showModal((productId ? "Failed to update product: " : "Failed to add product: ") + result.message, false);
            }
        } catch (error) {
            console.error(error);
            showModal((productId ? "Error updating product." : "Error adding product. Please try again."), false);
        }
    });

    //Handle Update Product
    updateBtn?.addEventListener("click", async function (e) {
        e.preventDefault();

        if (!validateProductForm()) return;

        const formData = new FormData(productForm);

        try {
            const response = await fetch("../actions/update_product_action.php", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showModal("Product updated successfully!");
            } else {
                showModal("Failed to update product: " + result.message, false);
            }
        } catch (error) {
            console.error(error);
            showModal("Error updating product.", false);
        }
    });

    //Handle Image Upload (optional separate upload)
    fileInput?.addEventListener("change", async function () {
        const file = fileInput.files[0];
        if (!file) return;

        const formData = new FormData();
        // accept either name expected by server
        formData.append("product_image", file);
        formData.append("productImage", file);

        try {
            const response = await fetch("../actions/upload_product_image_action.php", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showModal("Image uploaded successfully!");
            } else {
                showModal("Image upload failed: " + result.message, false);
            }
        } catch (error) {
            console.error(error);
            showModal("Error uploading image.", false);
        }
    });
});
