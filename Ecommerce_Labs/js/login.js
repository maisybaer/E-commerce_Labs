$(document).ready(function() {
    $('#login-form').submit(function(e) {
        e.preventDefault();

        let email = $('#email').val();
        let password = $('#password').val();

        //checks if fields are empty
        if (email == '' || password == '') {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });

            return;
        } 

        $.ajax({
            url: '../actions/login_customer_action.php',
            type: 'POST',
            dataType:"json",
            data: {
                email: email,
                password: password,
            },

            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../index.php';
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
                    text: 'An error occurred! Please try again later. Troubleshoot: Login.js_ajax',
                });
            }
        });
    });
});