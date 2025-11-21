// ...existing code...
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("login-form");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        let formData = new FormData(form);

        try {
            let response = await fetch("../actions/login_customer_action.php", {
                method: "POST",
                body: formData
            });

            // parse JSON safely
            let result;
            const contentType = response.headers.get("content-type") || "";
            if (contentType.includes("application/json")) {
                result = await response.json();
            } else {
                // fallback: try to parse text as JSON (helps if PHP emitted warnings)
                const text = await response.text();
                try {
                    result = JSON.parse(text);
                } catch (err) {
                    console.error("Invalid JSON from server:", text);
                    Swal.fire({
                        icon: "error",
                        title: "Login Failed",
                        text: "Server returned invalid response."
                    });
                    return;
                }
            }

            if (result && result.status === "success") {
                window.location.href = "../index.php";
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Login Failed",
                    text: (result && result.message) ? result.message : "Invalid email or password!"
                });
            }
        } catch (error) {
            console.error("Login error:", error);
            Swal.fire({
                icon: "error",
                title: "Login Failed",
                text: "Network or server error."
            });
        }
    });
});
