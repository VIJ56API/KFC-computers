<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/header.php';

$pdo = getDb();
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll();
?>

<div class="container">
  <div style="margin-bottom: 2rem;">
    <h2>Customer <span class="gradient-text">Orders & Invoices</span></h2>
    <p style="color: var(--text-muted);">View customer orders, delivery addresses, item breakdowns, and revenue.</p>
  </div>

  <div class="admin-table-wrapper">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Order #</th>
          <th>Customer Info</th>
          <th>Delivery Location</th>
          <th>Payment</th>
          <th>Total (₹)</th>
          <th>Status</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($orders) === 0): ?>
          <tr>
            <td colspan="8" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
              No customer orders placed yet.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td><strong style="color: var(--primary-cyan);"><?= htmlspecialchars($o['order_number']) ?></strong></td>
              <td>
                <strong style="color: #fff; display: block;"><?= htmlspecialchars($o['customer_name']) ?></strong>
                <span style="font-size: 0.78rem; color: var(--text-dim);"><?= htmlspecialchars($o['email']) ?> | <?= htmlspecialchars($o['phone']) ?></span>
              </td>
              <td style="font-size: 0.82rem; color: var(--text-muted);">
                <?= htmlspecialchars($o['city']) ?>, <?= htmlspecialchars($o['state']) ?> (<?= htmlspecialchars($o['pincode']) ?>)
              </td>
              <td style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($o['payment_method']) ?></td>
              <td style="font-weight: 700; color: var(--primary-cyan);">₹<?= number_format($o['total']) ?></td>
              <td><span style="background: rgba(16,185,129,0.2); color: var(--accent-green); padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 700;"><?= htmlspecialchars($o['status']) ?></span></td>
              <td style="font-size: 0.82rem; color: var(--text-dim);"><?= date('d M Y', strtotime($o['created_at'])) ?></td>
              <td>
                <a href="../order-confirmation.php?id=<?= $o['id'] ?>" target="_blank" class="btn btn-secondary btn-sm">
                  <i class="fa-solid fa-file-invoice"></i> View Invoice
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
