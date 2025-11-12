document.addEventListener("DOMContentLoaded", () => {

    const cartContainer = document.getElementById("cartItems");
    const emptyCartBtn = document.getElementById("emptyCartBtn");
    const checkoutBtn = document.getElementById("checkoutBtn");

    // Function to fetch cart items
    async function loadCart() {
        const response = await fetch('get_cart_action.php'); // We'll create this in backend to get cart items
        const data = await response.json();

        if (data.status === 'success') {
            renderCartItems(data.items);
        } else {
            cartContainer.innerHTML = "<p>Your cart is empty.</p>";
        }
    }

    // Render cart items dynamically
    function renderCartItems(items) {
        if (!items.length) {
            cartContainer.innerHTML = "<p>Your cart is empty.</p>";
            return;
        }

        cartContainer.innerHTML = '';
        items.forEach(item => {
            const subtotal = item.qty * item.product_price;
            const row = document.createElement('div');
            row.classList.add('cart-item');
            row.innerHTML = `
                <img src="${item.product_image}" alt="${item.product_title}" width="100">
                <div class="details">
                    <h4>${item.product_title}</h4>
                    <p>$${item.product_price.toFixed(2)}</p>
                    <input type="number" value="${item.qty}" min="1" data-id="${item.product_id}" class="qtyInput">
                    <p>Subtotal: $${subtotal.toFixed(2)}</p>
                    <button class="removeBtn" data-id="${item.product_id}">Remove</button>
                </div>
            `;
            cartContainer.appendChild(row);
        });

        attachEventListeners();
    }

    // Attach event listeners for quantity and remove buttons
    function attachEventListeners() {
        const qtyInputs = document.querySelectorAll(".qtyInput");
        qtyInputs.forEach(input => {
            input.addEventListener("change", async (e) => {
                const id = e.target.dataset.id;
                const qty = parseInt(e.target.value);
                await updateQuantity(id, qty);
                loadCart();
            });
        });

        const removeBtns = document.querySelectorAll(".removeBtn");
        removeBtns.forEach(btn => {
            btn.addEventListener("click", async (e) => {
                const id = e.target.dataset.id;
                await removeFromCart(id);
                loadCart();
            });
        });
    }

    // Add to cart
    async function addToCart(product_id, qty) {
        const formData = new FormData();
        formData.append("product_id", product_id);
        formData.append("qty", qty);

        const res = await fetch('add_to_cart_action.php', { method: 'POST', body: formData });
        const data = await res.json();
        alert(data.message);
        loadCart();
    }

    // Remove from cart
    async function removeFromCart(product_id) {
        const formData = new FormData();
        formData.append("product_id", product_id);

        const res = await fetch('remove_from_cart_action.php', { method: 'POST', body: formData });
        const data = await res.json();
        alert(data.message);
    }

    // Update quantity
    async function updateQuantity(product_id, qty) {
        const formData = new FormData();
        formData.append("product_id", product_id);
        formData.append("qty", qty);

        const res = await fetch('update_quantity_action.php', { method: 'POST', body: formData });
        const data = await res.json();
        if (data.status !== 'success') alert(data.message);
    }

    // Empty cart
    emptyCartBtn?.addEventListener("click", async () => {
        const res = await fetch('empty_cart_action.php', { method: 'POST' });
        const data = await res.json();
        alert(data.message);
        loadCart();
    });

    // Checkout redirect
    checkoutBtn?.addEventListener("click", () => {
        window.location.href = "checkout.php";
    });

    // Initial load
    loadCart();
});
