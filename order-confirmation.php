<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

$orderId = $_GET['id'] ?? 0;
$pdo = getDb();

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    echo "<div class='container' style='padding: 4rem; text-align: center;'><h2>Order Not Found</h2><a href='index.php' class='btn btn-primary' style='margin-top: 1rem;'>Back to Home</a></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$stmtItems = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmtItems->execute([$order['id']]);
$items = $stmtItems->fetchAll();
?>

<div class="container" style="max-width: 900px;">
  <div style="text-align: center; margin-bottom: 2.5rem; background: var(--bg-card); border: 2px solid #cbd5e1; padding: 3rem 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-card);">
    <div style="width: 70px; height: 70px; background: #dcfce7; border: 2px solid var(--accent-green); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2rem;">
      <i class="fa-solid fa-check" style="font-size: 2rem; color: var(--accent-green);"></i>
    </div>

    <h1 style="font-size: 2.3rem; margin-bottom: 0.5rem; color: #000000; font-weight: 900;">THANK YOU FOR YOUR ORDER!</h1>
    <p style="color: #000000; font-size: 1.05rem; font-weight: 600;">
      Your order <strong style="color: var(--primary-blue);"><?= htmlspecialchars($order['order_number']) ?></strong> has been confirmed and sent to our build technicians.
    </p>

    <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 1.8rem; flex-wrap: wrap;">
      <button onclick="window.print()" class="btn btn-outline btn-sm">
        <i class="fa-solid fa-print"></i> Print Invoice
      </button>
      <button onclick="scrollToEmailInvoice()" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-envelope"></i> Email Invoice
      </button>
      <a href="index.php" class="btn btn-primary btn-sm">Continue Shopping</a>
    </div>
  </div>

  <!-- Invoice Breakdown Card -->
  <div style="background: var(--bg-card); border: 2px solid #cbd5e1; border-radius: var(--radius); padding: 2rem; margin-bottom: 2rem; box-shadow: var(--shadow-card);">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #e2e8f0; padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
      <div>
        <div class="logo" style="font-size: 1.4rem;">KFC <span>COMPUTERS</span></div>
        <div style="font-size: 0.85rem; color: #000000; font-weight: 600; margin-top: 4px;">Tax Invoice & Order Receipt</div>
      </div>

      <div style="text-align: right; font-size: 0.88rem; color: #000000; font-weight: 600;">
        <div><strong>Order No:</strong> <?= htmlspecialchars($order['order_number']) ?></div>
        <div><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></div>
        <div><strong>Payment:</strong> <?= htmlspecialchars($order['payment_method']) ?></div>
        <div><strong>Status:</strong> <span style="color: var(--accent-green); font-weight: 800;"><?= htmlspecialchars($order['status']) ?></span></div>
      </div>
    </div>

    <!-- Shipping Address Summary -->
    <div style="background: var(--bg-surface); padding: 1.2rem; border-radius: var(--radius-sm); border: 2px solid #cbd5e1; margin-bottom: 1.5rem; font-size: 0.9rem;">
      <strong style="color: var(--primary-blue); display: block; margin-bottom: 0.4rem; font-size: 0.95rem;">Delivery Address:</strong>
      <div style="color: #000000; font-weight: 800;"><?= htmlspecialchars($order['customer_name']) ?></div>
      <div style="color: #000000; font-weight: 500;"><?= htmlspecialchars($order['address']) ?>, <?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?> - <?= htmlspecialchars($order['pincode']) ?></div>
      <div style="color: #000000; font-weight: 500;">Phone: <?= htmlspecialchars($order['phone']) ?> | Email: <?= htmlspecialchars($order['email']) ?></div>
    </div>

    <!-- Itemized Table -->
    <table class="admin-table" style="margin-bottom: 1.5rem;">
      <thead>
        <tr>
          <th>Item Description</th>
          <th>Category</th>
          <th>Unit Price</th>
          <th>Qty</th>
          <th style="text-align: right;">Total Price</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><strong style="color: #000; font-weight: 800;"><?= htmlspecialchars($item['product_name']) ?></strong></td>
            <td><span class="badge-category" style="position: static;"><?= htmlspecialchars($item['category']) ?></span></td>
            <td style="font-weight: 600;">₹<?= number_format($item['price']) ?></td>
            <td style="font-weight: 700;"><?= $item['quantity'] ?></td>
            <td style="text-align: right; font-weight: 800; color: var(--accent-gold);">₹<?= number_format($item['total_price']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div style="max-width: 320px; margin-left: auto; font-size: 0.95rem; font-weight: 600;">
      <div style="display: flex; justify-content: space-between; margin-bottom: 0.4rem; color: #000000;">
        <span>Subtotal:</span>
        <span style="color: #000; font-weight: 700;">₹<?= number_format($order['subtotal']) ?></span>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 0.4rem; color: #000000;">
        <span>GST (18%):</span>
        <span style="color: #000; font-weight: 700;">₹<?= number_format($order['tax']) ?></span>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem; color: #000000;">
        <span>Insured Express Shipping:</span>
        <span style="color: var(--accent-green); font-weight: 800;">FREE</span>
      </div>
      <div style="display: flex; justify-content: space-between; align-items: center; border-top: 2px solid #cbd5e1; padding-top: 0.8rem; font-size: 1.15rem; font-weight: 800;">
        <span>Total Paid:</span>
        <span style="font-family: var(--font-heading); color: var(--accent-gold); font-size: 1.6rem;">₹<?= number_format($order['total']) ?></span>
      </div>
    </div>
  </div>

  <!-- Direct Email Invoice Section -->
  <div id="email-invoice-card" style="background: var(--bg-card); border: 2px solid var(--primary-blue); border-radius: var(--radius); padding: 2rem; margin-bottom: 3rem; box-shadow: var(--shadow-card);">
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
      <div style="width: 46px; height: 46px; background: #e0f2fe; border: 1.5px solid var(--primary-blue); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        <i class="fa-solid fa-paper-plane" style="font-size: 1.2rem; color: var(--primary-blue);"></i>
      </div>
      <div>
        <h3 style="font-size: 1.25rem; color: #000000; font-weight: 900; margin: 0;">Send Tax Invoice Directly to Email</h3>
        <p style="color: #000000; font-size: 0.9rem; font-weight: 600; margin: 2px 0 0 0;">Enter your email address to send a copy of your tax invoice and order receipt.</p>
      </div>
    </div>

    <div id="invoice-email-status" style="display: none; padding: 0.8rem 1rem; border-radius: var(--radius-sm); font-size: 0.9rem; font-weight: 700; margin-bottom: 1rem;"></div>

    <form id="email-invoice-form" onsubmit="sendInvoiceEmail(event, <?= (int)$order['id'] ?>)" style="display: flex; gap: 0.8rem; flex-wrap: wrap;">
      <input type="email" id="invoice-email-input" class="form-control" style="flex: 1; min-width: 250px;" value="<?= htmlspecialchars($order['email']) ?>" placeholder="e.g. customer@example.com" required>
      <button type="submit" id="send-invoice-btn" class="btn btn-primary" style="padding: 0.75rem 1.6rem;">
        <i class="fa-solid fa-envelope-circle-check"></i> Send Invoice to Email
      </button>
    </form>
  </div>
</div>

<script>
function scrollToEmailInvoice() {
  const el = document.getElementById('email-invoice-card');
  if (el) el.scrollIntoView({ behavior: 'smooth' });
}

function sendInvoiceEmail(e, orderId) {
  e.preventDefault();
  const btn = document.getElementById('send-invoice-btn');
  const input = document.getElementById('invoice-email-input');
  const status = document.getElementById('invoice-email-status');

  const emailVal = input.value.trim();
  if (!emailVal) return;

  const originalHtml = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Sending...`;
  status.style.display = 'none';

  const formData = new FormData();
  formData.append('order_id', orderId);
  formData.append('email', emailVal);

  fetch('send-invoice.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    btn.disabled = false;
    btn.innerHTML = originalHtml;
    status.style.display = 'block';

    if (data.success) {
      status.style.background = '#dcfce7';
      status.style.border = '2px solid var(--accent-green)';
      status.style.color = '#14532d';
      status.innerHTML = `<i class="fa-solid fa-circle-check"></i> ${data.message}`;
      if (typeof showToast === 'function') {
        showToast(`Invoice sent to ${emailVal}`);
      }
    } else {
      status.style.background = '#fee2e2';
      status.style.border = '2px solid var(--accent-red)';
      status.style.color = '#7f1d1d';
      status.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${data.message || 'Failed to send invoice.'}`;
    }
  })
  .catch(err => {
    btn.disabled = false;
    btn.innerHTML = originalHtml;
    status.style.display = 'block';
    status.style.background = '#fee2e2';
    status.style.border = '2px solid var(--accent-red)';
    status.style.color = '#7f1d1d';
    status.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> An error occurred while sending the email.`;
  });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
