document.addEventListener("DOMContentLoaded", function () {
    const productForm = document.getElementById("productForm");
    const updateBtn = document.getElementById("updateBtn");
    const addBtn = document.getElementById("addBtn");
    const fileInput = document.getElementById("productImage");
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
        const name = document.getElementById("productName").value.trim();
        const price = document.getElementById("productPrice").value.trim();
        const brand = document.getElementById("brandSelect").value;
        const category = document.getElementById("categorySelect").value;

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

    // Handle Add Product
    addBtn?.addEventListener("click", async function (e) {
        e.preventDefault();

        if (!validateProductForm()) return;

        const formData = new FormData(productForm);

        try {
            const response = await fetch("../actions/add_product_action.php", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showModal("Product added successfully!");
                productForm.reset();
            } else {
                showModal("Failed to add product: " + result.message, false);
            }
        } catch (error) {
            console.error(error);
            showModal("Error adding product. Please try again.", false);
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
            showModal("⚠️ Error updating product.", false);
        }
    });

    // 🧩 Handle Image Upload
    fileInput?.addEventListener("change", async function () {
        const file = fileInput.files[0];
        if (!file) return;

        const formData = new FormData();
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
