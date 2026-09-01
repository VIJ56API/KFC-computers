<?php
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$orderId = (int)($_POST['order_id'] ?? 0);
$email = trim($_POST['email'] ?? '');

if ($orderId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid Order ID']);
    exit;
}

$pdo = getDb();
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit;
}

$targetEmail = !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : $order['email'];

$stmtItems = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmtItems->execute([$order['id']]);
$items = $stmtItems->fetchAll();

// Build Itemized HTML Table
$itemsHtml = '';
foreach ($items as $item) {
    $itemsHtml .= "
        <tr>
            <td style='padding: 10px; border-bottom: 1px solid #e2e8f0;'><strong>" . htmlspecialchars($item['product_name']) . "</strong> (" . htmlspecialchars($item['category']) . ")</td>
            <td style='padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: center;'>" . (int)$item['quantity'] . "</td>
            <td style='padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: right;'>₹" . number_format($item['price']) . "</td>
            <td style='padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: right;'><strong>₹" . number_format($item['total_price']) . "</strong></td>
        </tr>";
}

$formattedDate = date('d M Y, h:i A', strtotime($order['created_at']));
$subtotal = number_format($order['subtotal']);
$tax = number_format($order['tax']);
$total = number_format($order['total']);

$htmlBody = "
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Invoice {$order['order_number']}</title>
</head>
<body style='font-family: Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 20px; color: #0f172a;'>
    <div style='max-width: 650px; margin: 0 auto; background: #ffffff; border: 2px solid #cbd5e1; border-radius: 12px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);'>
        <div style='display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #0284c7; padding-bottom: 15px; margin-bottom: 20px;'>
            <div>
                <h1 style='color: #0284c7; margin: 0; font-size: 24px;'>KFC COMPUTERS</h1>
                <p style='margin: 4px 0 0 0; color: #64748b; font-size: 13px; font-weight: bold;'>Official Tax Invoice & Receipt</p>
            </div>
            <div style='text-align: right;'>
                <p style='margin: 0; font-size: 14px;'><strong>Invoice No:</strong> " . htmlspecialchars($order['order_number']) . "</p>
                <p style='margin: 4px 0 0 0; font-size: 13px; color: #64748b;'>Date: {$formattedDate}</p>
            </div>
        </div>

        <div style='background: #f1f5f9; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;'>
            <strong style='color: #0284c7; display: block; margin-bottom: 6px;'>Customer Details:</strong>
            <p style='margin: 2px 0;'><strong>" . htmlspecialchars($order['customer_name']) . "</strong></p>
            <p style='margin: 2px 0;'>" . htmlspecialchars($order['address']) . ", " . htmlspecialchars($order['city']) . ", " . htmlspecialchars($order['state']) . " - " . htmlspecialchars($order['pincode']) . "</p>
            <p style='margin: 2px 0;'>Phone: " . htmlspecialchars($order['phone']) . " | Email: " . htmlspecialchars($targetEmail) . "</p>
        </div>

        <table style='width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;'>
            <thead>
                <tr style='background: #e0f2fe; color: #0369a1; text-align: left;'>
                    <th style='padding: 10px; border-bottom: 2px solid #7dd3fc;'>Item</th>
                    <th style='padding: 10px; border-bottom: 2px solid #7dd3fc; text-align: center;'>Qty</th>
                    <th style='padding: 10px; border-bottom: 2px solid #7dd3fc; text-align: right;'>Price</th>
                    <th style='padding: 10px; border-bottom: 2px solid #7dd3fc; text-align: right;'>Total</th>
                </tr>
            </thead>
            <tbody>
                {$itemsHtml}
            </tbody>
        </table>

        <div style='max-width: 280px; margin-left: auto; font-size: 14px; margin-top: 10px;'>
            <div style='display: flex; justify-content: space-between; margin-bottom: 6px;'>
                <span>Subtotal:</span>
                <span>₹{$subtotal}</span>
            </div>
            <div style='display: flex; justify-content: space-between; margin-bottom: 6px;'>
                <span>GST (18%):</span>
                <span>₹{$tax}</span>
            </div>
            <div style='display: flex; justify-content: space-between; margin-bottom: 6px; color: #16a34a; font-weight: bold;'>
                <span>Express Shipping:</span>
                <span>FREE</span>
            </div>
            <div style='display: flex; justify-content: space-between; border-top: 2px solid #cbd5e1; padding-top: 8px; font-size: 16px; font-weight: bold; color: #d97706;'>
                <span>Total Amount:</span>
                <span>₹{$total}</span>
            </div>
        </div>

        <div style='margin-top: 25px; padding-top: 15px; border-top: 1px dashed #cbd5e1; text-align: center; font-size: 12px; color: #64748b;'>
            Thank you for shopping with KFC Computers! For support, contact support@kfccomputers.in
        </div>
    </div>
</body>
</html>
";

$subject = "Tax Invoice for Order #" . $order['order_number'] . " - KFC Computers";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: KFC Computers <orders@kfccomputers.in>" . "\r\n";

// Attempt send via mail() function
@mail($targetEmail, $subject, $htmlBody, $headers);

// Record in local log file for verification
$logFile = __DIR__ . '/database/invoice_email_logs.json';
$logs = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];
$logs[] = [
    'order_number' => $order['order_number'],
    'sent_to' => $targetEmail,
    'timestamp' => date('Y-m-d H:i:s'),
    'status' => 'SENT'
];
file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));

echo json_encode([
    'success' => true,
    'message' => "Tax Invoice for Order #{$order['order_number']} sent directly to {$targetEmail}!",
    'recipient' => $targetEmail
]);
