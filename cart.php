<?php
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <div style="margin-bottom: 2rem;">
    <h2>Shopping <span class="gradient-text">Cart</span></h2>
    <p style="color: var(--text-muted);">Review your selected ready-made PCs, components, or custom builds.</p>
  </div>

  <div id="cart-container" class="catalog-wrapper" style="grid-template-columns: 1fr 340px;">
    <!-- Left: Cart Items List -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius); padding: 1.5rem;">
      <div id="cart-items-wrapper">
        <!-- Rendered by client JS -->
      </div>
    </div>

    <!-- Right: Summary & Order Calculation -->
    <aside>
      <div class="build-summary-card" style="top: 90px;">
        <h3>Order Summary</h3>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem; font-size: 0.9rem; color: var(--text-muted);">
          <span>Subtotal:</span>
          <span id="cart-subtotal" style="color: var(--text-main); font-weight: 600;">₹0</span>
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem; font-size: 0.9rem; color: var(--text-muted);">
          <span>Estimated GST (18%):</span>
          <span id="cart-tax" style="color: var(--text-main); font-weight: 600;">₹0</span>
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 1.2rem; font-size: 0.9rem; color: var(--text-muted);">
          <span>Insured Express Shipping:</span>
          <span style="color: var(--accent-green); font-weight: 700;">FREE</span>
        </div>

        <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
          <span style="font-weight: 700; font-size: 1.1rem;">Grand Total:</span>
          <strong id="cart-grand-total" style="font-family: var(--font-heading); color: var(--primary-cyan); font-size: 1.6rem;">₹0</strong>
        </div>

        <a href="checkout.php" id="checkout-btn" class="btn btn-primary btn-block">
          <i class="fa-solid fa-credit-card"></i> Proceed to Checkout
        </a>
      </div>
    </aside>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    renderCartPage();
});

function renderCartPage() {
    const cart = getCart();
    const wrapper = document.getElementById('cart-items-wrapper');
    const subtotalEl = document.getElementById('cart-subtotal');
    const taxEl = document.getElementById('cart-tax');
    const totalEl = document.getElementById('cart-grand-total');
    const checkoutBtn = document.getElementById('checkout-btn');

    if (!wrapper) return;

    if (cart.length === 0) {
        wrapper.innerHTML = `
            <div style="text-align: center; padding: 3rem 1rem;">
                <i class="fa-solid fa-cart-arrow-down" style="font-size: 3rem; color: var(--text-dim); margin-bottom: 1rem;"></i>
                <h3>Your shopping cart is empty</h3>
                <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Add Ready-Made PCs or components to start building.</p>
                <a href="products.php" class="btn btn-primary">Browse Catalog</a>
            </div>
        `;
        subtotalEl.textContent = '₹0';
        taxEl.textContent = '₹0';
        totalEl.textContent = '₹0';
        if (checkoutBtn) checkoutBtn.style.pointerEvents = 'none';
        return;
    }

    let subtotal = 0;
    wrapper.innerHTML = `
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                ${cart.map((item, idx) => {
                    const itemTotal = Number(item.price) * (item.qty || 1);
                    subtotal += itemTotal;
                    return `
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <img src="${item.image}" style="width: 50px; height: 50px; object-fit: contain; background: #0b1120; border-radius: 6px; padding: 4px;">
                                    <div>
                                        <strong style="color: var(--text-main); font-size: 0.95rem;">${item.name}</strong>
                                        <div style="font-size: 0.75rem; color: var(--text-dim);">${item.category || 'Hardware'}</div>
                                    </div>
                                </div>
                            </td>
                            <td>₹${Number(item.price).toLocaleString('en-IN')}</td>
                            <td>
                                <input type="number" value="${item.qty || 1}" min="1" max="10" onchange="updateCartQty(${idx}, this.value)" style="width: 55px; padding: 4px; background: var(--bg-surface); border: 1px solid var(--border-color); color: #fff; border-radius: 4px; text-align: center;">
                            </td>
                            <td style="font-weight: 700; color: var(--primary-cyan);">₹${itemTotal.toLocaleString('en-IN')}</td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="removeCartItem(${idx})"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                }).join('')}
            </tbody>
        </table>
    `;

    const tax = Math.round(subtotal * 0.18);
    const grandTotal = subtotal + tax;

    subtotalEl.textContent = '₹' + subtotal.toLocaleString('en-IN');
    taxEl.textContent = '₹' + tax.toLocaleString('en-IN');
    totalEl.textContent = '₹' + grandTotal.toLocaleString('en-IN');
    if (checkoutBtn) checkoutBtn.style.pointerEvents = 'auto';
}

function updateCartQty(index, qty) {
    let cart = getCart();
    if (cart[index]) {
        cart[index].qty = parseInt(qty) || 1;
        saveCart(cart);
        renderCartPage();
    }
}

function removeCartItem(index) {
    let cart = getCart();
    cart.splice(index, 1);
    saveCart(cart);
    renderCartPage();
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
