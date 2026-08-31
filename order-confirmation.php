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
  <div style="text-align: center; margin-bottom: 2.5rem; background: var(--bg-card); border: 1px solid var(--border-color); padding: 3rem 2rem; border-radius: var(--radius-lg);">
    <div style="width: 70px; height: 70px; background: rgba(16,185,129,0.2); border: 2px solid var(--accent-green); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2rem;">
      <i class="fa-solid fa-check" style="font-size: 2rem; color: var(--accent-green);"></i>
    </div>

    <h1 style="font-size: 2.3rem; margin-bottom: 0.5rem;">THANK YOU FOR YOUR ORDER!</h1>
    <p style="color: var(--text-muted); font-size: 1.05rem;">
      Your order <strong style="color: var(--primary-cyan);"><?= htmlspecialchars($order['order_number']) ?></strong> has been confirmed and sent to our build technicians.
    </p>

    <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 1.8rem;">
      <button onclick="window.print()" class="btn btn-outline btn-sm">
        <i class="fa-solid fa-print"></i> Print Invoice
      </button>
      <a href="index.php" class="btn btn-primary btn-sm">Continue Shopping</a>
    </div>
  </div>

  <!-- Invoice Breakdown Card -->
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius); padding: 2rem; margin-bottom: 3rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
      <div>
        <div class="logo" style="font-size: 1.4rem;">LIT <span>COMPUTERS</span></div>
        <div style="font-size: 0.82rem; color: var(--text-muted); margin-top: 4px;">Tax Invoice & Order Receipt</div>
      </div>

      <div style="text-align: right; font-size: 0.85rem; color: var(--text-muted);">
        <div><strong>Order No:</strong> <?= htmlspecialchars($order['order_number']) ?></div>
        <div><strong>Date:</strong> <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></div>
        <div><strong>Payment:</strong> <?= htmlspecialchars($order['payment_method']) ?></div>
        <div><strong>Status:</strong> <span style="color: var(--accent-green); font-weight: 700;"><?= htmlspecialchars($order['status']) ?></span></div>
      </div>
    </div>

    <!-- Shipping Address Summary -->
    <div style="background: var(--bg-surface); padding: 1.2rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); margin-bottom: 1.5rem; font-size: 0.88rem;">
      <strong style="color: var(--primary-cyan); display: block; margin-bottom: 0.4rem;">Delivery Address:</strong>
      <div style="color: var(--text-main); font-weight: 600;"><?= htmlspecialchars($order['customer_name']) ?></div>
      <div style="color: var(--text-muted);"><?= htmlspecialchars($order['address']) ?>, <?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?> - <?= htmlspecialchars($order['pincode']) ?></div>
      <div style="color: var(--text-muted);">Phone: <?= htmlspecialchars($order['phone']) ?> | Email: <?= htmlspecialchars($order['email']) ?></div>
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
            <td><strong style="color: #fff;"><?= htmlspecialchars($item['product_name']) ?></strong></td>
            <td><span class="badge-category" style="position: static;"><?= htmlspecialchars($item['category']) ?></span></td>
            <td>₹<?= number_format($item['price']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td style="text-align: right; font-weight: 700; color: var(--primary-cyan);">₹<?= number_format($item['total_price']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div style="max-width: 320px; margin-left: auto; font-size: 0.9rem;">
      <div style="display: flex; justify-content: space-between; margin-bottom: 0.4rem; color: var(--text-muted);">
        <span>Subtotal:</span>
        <span style="color: #fff;">₹<?= number_format($order['subtotal']) ?></span>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 0.4rem; color: var(--text-muted);">
        <span>GST (18%):</span>
        <span style="color: #fff;">₹<?= number_format($order['tax']) ?></span>
      </div>
      <div style="display: flex; justify-content: space-between; margin-bottom: 0.8rem; color: var(--text-muted);">
        <span>Insured Express Shipping:</span>
        <span style="color: var(--accent-green); font-weight: 700;">FREE</span>
      </div>
      <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 0.8rem; font-size: 1.1rem; font-weight: 700;">
        <span>Total Paid:</span>
        <span style="font-family: var(--font-heading); color: var(--primary-cyan); font-size: 1.5rem;">₹<?= number_format($order['total']) ?></span>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
