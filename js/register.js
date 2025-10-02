$(document).ready(function() {
    $('#register-form').submit(function(e) {
        e.preventDefault();

        customer_name = $('#customer_name').val();
        email = $('#email').val();
        password = $('#password').val();
        country = $('#country').val();
        city =$('#city').val();
        phone_number = $('#phone_number').val();
        role = $('input[name="role"]:checked').val();

        if (customer_name == '' || email == '' || password == '' || country =='' || city =='' || phone_number == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });

            return;
        } else if (password.length < 6 || !password.match(/[a-z]/) || !password.match(/[A-Z]/) || !password.match(/[0-9]/)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password must be at least 6 characters long and contain at least one lowercase letter, one uppercase letter, and one number!',
            });

            return;
        }

        $.ajax({
            url: '../actions/register_customer_action.php',
            type: 'POST',
            data: {
                name: customer_name,
                customer_email: email,
                customer_pass: password,
                customer_country: country,
                customer_city: city,
                customer_contact: phone_number,
                role: role
            },
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'login.php';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An error occurred! Please try again later.',
                });
            }
        });
    });
});