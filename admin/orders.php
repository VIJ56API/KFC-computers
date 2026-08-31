<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/header.php';

$pdo = getDb();
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC");
$orders = $stmt->fetchAll();
?>

<div class="container">
  <div style="margin-bottom: 2rem;">
    <h2 style="font-size: 2rem; color: #000000; font-weight: 900;">Customer <span class="gradient-text">Orders & Invoices</span></h2>
    <p style="color: #000000; font-size: 0.95rem; font-weight: 700;">View customer orders, delivery addresses, item breakdowns, and revenue.</p>
  </div>

  <div class="admin-table-wrapper">
    <table class="admin-table">
      <thead>
        <tr>
          <th style="color: #000000; font-weight: 900;">Order #</th>
          <th style="color: #000000; font-weight: 900;">Customer Info</th>
          <th style="color: #000000; font-weight: 900;">Delivery Location</th>
          <th style="color: #000000; font-weight: 900;">Payment</th>
          <th style="color: #000000; font-weight: 900;">Total (₹)</th>
          <th style="color: #000000; font-weight: 900;">Status</th>
          <th style="color: #000000; font-weight: 900;">Date</th>
          <th style="color: #000000; font-weight: 900;">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($orders) === 0): ?>
          <tr>
            <td colspan="8" style="text-align: center; padding: 2.5rem; color: #000000; font-weight: 700;">
              No customer orders placed yet.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td><strong style="color: var(--primary-blue); font-weight: 900;"><?= htmlspecialchars($o['order_number']) ?></strong></td>
              <td>
                <strong style="color: #000000 !important; font-size: 0.98rem; font-weight: 900; display: block;"><?= htmlspecialchars($o['customer_name']) ?></strong>
                <span style="font-size: 0.82rem; color: #000000 !important; font-weight: 700;"><?= htmlspecialchars($o['email']) ?> | <?= htmlspecialchars($o['phone']) ?></span>
              </td>
              <td style="font-size: 0.88rem; color: #000000 !important; font-weight: 700;">
                <?= htmlspecialchars($o['city']) ?>, <?= htmlspecialchars($o['state']) ?> (<?= htmlspecialchars($o['pincode']) ?>)
              </td>
              <td style="font-size: 0.88rem; color: #000000 !important; font-weight: 700;"><?= htmlspecialchars($o['payment_method']) ?></td>
              <td style="font-weight: 900; color: var(--accent-gold) !important; font-size: 1.1rem;">₹<?= number_format($o['total']) ?></td>
              <td><span style="background: #dcfce7; color: #000000 !important; border: 1.5px solid #86efac; padding: 3px 8px; border-radius: 4px; font-size: 0.78rem; font-weight: 900;"><?= htmlspecialchars($o['status']) ?></span></td>
              <td style="font-size: 0.85rem; color: #000000 !important; font-weight: 700;"><?= date('d M Y', strtotime($o['created_at'])) ?></td>
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
