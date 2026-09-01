// LIT Computers Main JavaScript Utilities

document.addEventListener('DOMContentLoaded', () => {
    updateCartBadge();
});

// Update cart counter from localStorage or session
function updateCartBadge() {
    const cart = getCart();
    const totalCount = cart.reduce((sum, item) => sum + (item.qty || 1), 0);
    const badges = document.querySelectorAll('.cart-badge');
    badges.forEach(b => b.textContent = totalCount);
}

// Get Cart from localStorage
function getCart() {
    return JSON.parse(localStorage.getItem('lit_cart') || '[]');
}

// Save Cart to localStorage
function saveCart(cart) {
    localStorage.setItem('lit_cart', JSON.stringify(cart));
    updateCartBadge();
}

// Add item to cart
function addToCart(item, qty = 1, btnElement = null) {
    if (window.event) {
        window.event.stopPropagation();
    }
    let cart = getCart();
    const existingIndex = cart.findIndex(c => c.id === item.id);
    if (existingIndex > -1) {
        cart[existingIndex].qty += qty;
    } else {
        item.qty = qty;
        cart.push(item);
    }
    saveCart(cart);
    showToast(`Added ${item.name} to Cart!`);

    if (btnElement) {
        const originalHtml = btnElement.innerHTML;
        const hadPrimary = btnElement.classList.contains('btn-primary');
        const hadSecondary = btnElement.classList.contains('btn-secondary');
        
        btnElement.innerHTML = `<i class="fa-solid fa-check"></i> Added!`;
        btnElement.classList.remove('btn-primary', 'btn-secondary');
        btnElement.classList.add('btn-success');
        btnElement.disabled = true;

        setTimeout(() => {
            btnElement.innerHTML = originalHtml;
            btnElement.classList.remove('btn-success');
            if (hadPrimary) btnElement.classList.add('btn-primary');
            if (hadSecondary) btnElement.classList.add('btn-secondary');
            btnElement.disabled = false;
        }, 1200);
    }
}

// Remove item from cart
function removeFromCart(id) {
    let cart = getCart();
    cart = cart.filter(c => c.id !== id);
    saveCart(cart);
}

// Show temporary notification toast
function showToast(message) {
    let toast = document.getElementById('lit-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'lit-toast';
        toast.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #d97706;
            color: #ffffff;
            font-weight: 700;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.4);
            z-index: 1000;
            transition: all 0.3s ease;
            transform: translateY(100px);
            opacity: 0;
        `;
        document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.style.transform = 'translateY(0)';
    toast.style.opacity = '1';

    setTimeout(() => {
        toast.style.transform = 'translateY(100px)';
        toast.style.opacity = '0';
    }, 3000);
}
