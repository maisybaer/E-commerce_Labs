document.addEventListener("DOMContentLoaded", () => {

    const payBtn = document.getElementById("simulatePayBtn");
    const cancelBtn = document.getElementById("cancelPayBtn");
    const modal = document.getElementById("paymentModal");
    const messageContainer = document.getElementById("checkoutMessage");

    payBtn?.addEventListener("click", async () => {
        const res = await fetch('process_checkout_action.php', { method: 'POST' });
        const data = await res.json();

        if (data.status === 'success') {
            messageContainer.innerHTML = `<p>Payment successful! Your order reference: ${data.invoice_no}</p>`;
        } else {
            messageContainer.innerHTML = `<p>Payment failed: ${data.message}</p>`;
        }

        modal.style.display = 'none';
    });

    cancelBtn?.addEventListener("click", () => {
        modal.style.display = 'none';
        messageContainer.innerHTML = "<p>Payment cancelled.</p>";
    });

});
