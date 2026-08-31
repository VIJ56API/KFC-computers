<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/data_manager.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? null;

if (!$currentUser) {
    header('Location: login.php?redirect=checkout.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartData = json_decode($_POST['cart_json'] ?? '[]', true);
    $fullName = trim($_POST['customer_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $pincode = trim($_POST['pincode'] ?? '');
    $paymentMethod = trim($_POST['payment_method'] ?? 'UPI / NetBanking');

    if (empty($cartData) || !is_array($cartData)) {
        $error = 'Your cart is empty.';
    } elseif (empty($fullName) || empty($email) || empty($phone) || empty($address) || empty($city) || empty($state) || empty($pincode)) {
        $error = 'Please complete all required delivery details.';
    } else {
        $subtotal = 0;
        foreach ($cartData as $item) {
            $subtotal += (float)$item['price'] * (int)($item['qty'] ?? 1);
        }
        $tax = round($subtotal * 0.18, 2);
        $total = $subtotal + $tax;
        $orderNum = 'KFC-' . date('Ymd') . '-' . rand(1000, 9999);

        try {
            $pdo = getDb();
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO orders 
                (order_number, user_id, customer_name, email, phone, address, city, state, pincode, payment_method, subtotal, tax, total, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Processing')");
            $stmt->execute([
                $orderNum,
                $currentUser['id'],
                $fullName,
                $email,
                $phone,
                $address,
                $city,
                $state,
                $pincode,
                $paymentMethod,
                $subtotal,
                $tax,
                $total
            ]);
            $orderId = $pdo->lastInsertId();

            $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_name, category, price, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($cartData as $item) {
                $qty = (int)($item['qty'] ?? 1);
                $lineTotal = (float)$item['price'] * $qty;
                $stmtItem->execute([
                    $orderId,
                    $item['name'],
                    $item['category'] ?? 'Hardware',
                    (float)$item['price'],
                    $qty,
                    $lineTotal
                ]);
            }

            $pdo->commit();

            // Clear local storage via JS and redirect
            echo "<script>
                localStorage.removeItem('lit_cart');
                window.location.href = 'order-confirmation.php?id={$orderId}';
            </script>";
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Order processing failed: ' . $e->getMessage();
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
  <div style="margin-bottom: 2rem;">
    <h2>Order <span class="gradient-text">Checkout</span></h2>
    <p style="color: #000000; font-weight: 500;">Enter shipping destination and complete your purchase securely.</p>
  </div>

  <?php if (!empty($error)): ?>
    <div style="background: #fee2e2; border: 2px solid var(--accent-red); color: var(--accent-red); padding: 0.8rem; border-radius: var(--radius-sm); font-size: 0.88rem; margin-bottom: 1.5rem; font-weight: 700;">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <form action="checkout.php" method="POST" id="checkout-form">
    <input type="hidden" name="cart_json" id="cart-json-input">

    <div class="catalog-wrapper" style="grid-template-columns: 1fr 360px;">
      <!-- Left: Delivery Address & Payment Method -->
      <div style="background: var(--bg-card); border: 2px solid #cbd5e1; border-radius: var(--radius); padding: 2rem; box-shadow: var(--shadow-card);">
        <h3 style="font-size: 1.3rem; margin-bottom: 1.2rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; color: #000000; font-weight: 800;">
          <i class="fa-solid fa-truck-ram" style="color: var(--primary-blue);"></i> Delivery Address
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="customer_name" class="form-control" required value="<?= htmlspecialchars($currentUser['name']) ?>">
          </div>
          <div class="form-group">
            <label>Email Address *</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($currentUser['email']) ?>">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="form-group">
            <label>Phone Number *</label>
            <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210" required>
          </div>
          <div class="form-group">
            <label>Pincode *</label>
            <input type="text" name="pincode" class="form-control" placeholder="e.g. 560001" required>
          </div>
        </div>

        <div class="form-group">
          <label>Street Address / Door No. *</label>
          <textarea name="address" class="form-control" rows="2" placeholder="Building, Street, Landmark" required></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="form-group">
            <label>City *</label>
            <input type="text" name="city" class="form-control" placeholder="e.g. Bengaluru" required>
          </div>
          <div class="form-group">
            <label>State *</label>
            <input type="text" name="state" class="form-control" placeholder="e.g. Karnataka" required>
          </div>
        </div>

        <h3 style="font-size: 1.3rem; margin: 1.8rem 0 1rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; color: #000000; font-weight: 800;">
          <i class="fa-solid fa-credit-card" style="color: var(--primary-blue);"></i> Payment Method
        </h3>

        <div style="display: flex; flex-direction: column; gap: 0.8rem;">
          <label style="background: var(--bg-surface); padding: 1rem; border: 2px solid #cbd5e1; border-radius: var(--radius-sm); cursor: pointer; display: flex; align-items: center; gap: 1rem;">
            <input type="radio" name="payment_method" value="UPI / Instant QR Code" checked>
            <div>
              <strong style="color: #000000; font-weight: 800;">UPI / Instant QR Code (GPay, PhonePe, Paytm)</strong>
              <div style="font-size: 0.8rem; color: #334155; font-weight: 600;">Zero extra transaction fee • Instant Order Processing</div>
            </div>
          </label>

          <label style="background: var(--bg-surface); padding: 1rem; border: 2px solid #cbd5e1; border-radius: var(--radius-sm); cursor: pointer; display: flex; align-items: center; gap: 1rem;">
            <input type="radio" name="payment_method" value="Credit / Debit Card">
            <div>
              <strong style="color: #000000; font-weight: 800;">Credit / Debit Card / NetBanking</strong>
              <div style="font-size: 0.8rem; color: #334155; font-weight: 600;">Visa, MasterCard, RuPay, HDFC, ICICI, SBI</div>
            </div>
          </label>

          <label style="background: var(--bg-surface); padding: 1rem; border: 2px solid #cbd5e1; border-radius: var(--radius-sm); cursor: pointer; display: flex; align-items: center; gap: 1rem;">
            <input type="radio" name="payment_method" value="Cash on Delivery (COD)">
            <div>
              <strong style="color: #000000; font-weight: 800;">Cash on Delivery (COD)</strong>
              <div style="font-size: 0.8rem; color: #334155; font-weight: 600;">Pay upon delivery at your doorstep</div>
            </div>
          </label>
        </div>
      </div>

      <!-- Right: Review Bill -->
      <aside>
        <div class="build-summary-card">
          <h3>Review Bill</h3>

          <div id="checkout-items-list" style="margin-bottom: 1.5rem; max-height: 250px; overflow-y: auto;">
            <!-- Populated via JS -->
          </div>

          <div style="border-top: 2px dashed #cbd5e1; padding-top: 1rem; font-size: 0.9rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #000000; font-weight: 600;">
              <span>Subtotal:</span>
              <span id="checkout-subtotal" style="color: #000; font-weight: 700;">₹0</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #000000; font-weight: 600;">
              <span>GST (18%):</span>
              <span id="checkout-tax" style="color: #000; font-weight: 700;">₹0</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; color: #000000; font-weight: 600;">
              <span>Express Shipping:</span>
              <span style="color: var(--accent-green); font-weight: 800;">FREE</span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 2px solid #cbd5e1; padding-top: 0.8rem; margin-bottom: 1.5rem;">
              <span style="font-weight: 800; font-size: 1.1rem; color: #000000;">Total Payable:</span>
              <strong id="checkout-grand-total" style="font-family: var(--font-heading); color: var(--accent-gold); font-size: 1.6rem;">₹0</strong>
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-block" style="padding: 0.9rem;">
            <i class="fa-solid fa-lock"></i> Place Order & Pay
          </button>
        </div>
      </aside>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const cart = getCart();
    document.getElementById('cart-json-input').value = JSON.stringify(cart);

    const listEl = document.getElementById('checkout-items-list');
    const subtotalEl = document.getElementById('checkout-subtotal');
    const taxEl = document.getElementById('checkout-tax');
    const grandTotalEl = document.getElementById('checkout-grand-total');

    let subtotal = 0;
    listEl.innerHTML = cart.map(item => {
        const itemTotal = Number(item.price) * (item.qty || 1);
        subtotal += itemTotal;
        return `
            <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 0.6rem;">
                <div>
                    <div style="font-weight: 800; color: #000;">${item.name}</div>
                    <div style="color: #334155; font-size: 0.78rem; font-weight: 600;">Qty: ${item.qty || 1}</div>
                </div>
                <div style="font-weight: 800; color: var(--accent-gold);">₹${itemTotal.toLocaleString('en-IN')}</div>
            </div>
        `;
    }).join('');

    const tax = Math.round(subtotal * 0.18);
    const grandTotal = subtotal + tax;

    subtotalEl.textContent = '₹' + subtotal.toLocaleString('en-IN');
    taxEl.textContent = '₹' + tax.toLocaleString('en-IN');
    grandTotalEl.textContent = '₹' + grandTotal.toLocaleString('en-IN');
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
