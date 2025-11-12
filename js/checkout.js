$(document).ready(function() {
    
    const paymentModal = $('#paymentModal');
    const simulatePayBtn = $('#simulatePayBtn');
    const confirmPayBtn = $('#confirmPayBtn');
    const cancelPayBtn = $('#cancelPayBtn');
    const messageContainer = $('#checkoutMessage');

    // Show payment modal when simulate payment button is clicked
    simulatePayBtn.on('click', function() {
        paymentModal.addClass('active');
    });

    // Cancel payment
    cancelPayBtn.on('click', function() {
        paymentModal.removeClass('active');
        messageContainer.html(`
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation-triangle"></i> Payment cancelled.
            </div>
        `);
    });

    // Confirm payment and process checkout
    confirmPayBtn.on('click', function() {
        // Disable button to prevent double submission
        confirmPayBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: '../actions/process_checkout_action.php',
            method: 'POST',
            dataType: 'json',
            success: function(response) {
                paymentModal.removeClass('active');
                
                if (response.status === 'success') {
                    // Show success message with order details
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Placed Successfully!',
                        html: `
                            <p><strong>Order Reference:</strong> ${response.invoice_no}</p>
                            <p><strong>Total Amount:</strong> $${response.total_amount}</p>
                            <p>Thank you for your purchase!</p>
                        `,
                        confirmButtonText: 'Continue Shopping',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'all_product.php';
                        }
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Payment Failed',
                        text: response.message,
                        confirmButtonText: 'Try Again'
                    });
                    confirmPayBtn.prop('disabled', false).html('<i class="fas fa-check"></i> Yes, I\'ve Paid');
                }
            },
            error: function(xhr, status, error) {
                paymentModal.removeClass('active');
                console.error('Checkout error:', error);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred during checkout. Please try again.',
                    confirmButtonText: 'OK'
                });
                
                confirmPayBtn.prop('disabled', false).html('<i class="fas fa-check"></i> Yes, I\'ve Paid');
            }
        });
    });

    // Close modal when clicking outside
    paymentModal.on('click', function(e) {
        if ($(e.target).is('#paymentModal')) {
            paymentModal.removeClass('active');
            messageContainer.html(`
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> Payment cancelled.
                </div>
            `);
        }
    });
});