document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("login-form");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        let formData = new FormData(form);

        // email = formData.get("customer_email").trim();
        // password = formData.get("customer_pass").trim();

        try {
            let response = await fetch("../actions/login_customer_action.php", {
                method: "POST",
                body: formData
            });

            let result = await response.text();

            // result = result.json();

            if (result.status === "success") {
                //Redirects to landing page
                window.location.href = "../admin/category.php";
            } else {
                // Show error with SweetAlert2
                Swal.fire({
                    icon: "error",
                    title: "Login Failed",
                    text: "Invalid email or password!"
                });
            }
        } catch (error) {
            console.error("Login error:", error);
        }
    });
});
