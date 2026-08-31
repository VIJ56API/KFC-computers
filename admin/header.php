<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentUser = $_SESSION['user'] ?? null;

if (!$currentUser || $currentUser['role'] !== 'admin') {
    header('Location: ../login.php?redirect=admin/products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | KFC Computers</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header class="navbar" style="border-bottom: 2px solid var(--accent-gold);">
  <div class="nav-container">
    <a href="products.php" class="logo">
      KFC <span>COMPUTERS</span> <span class="logo-badge" style="background: var(--accent-gold);">ADMIN</span>
    </a>

    <ul class="nav-links">
      <li><a href="products.php"><i class="fa-solid fa-boxes-stacked"></i> Products Catalog</a></li>
      <li><a href="add-product.php" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Add Product / Part</a></li>
      <li><a href="orders.php"><i class="fa-solid fa-file-invoice-dollar"></i> Customer Orders</a></li>
      <li><a href="../index.php" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> Open Storefront</a></li>
    </ul>

    <div style="display: flex; align-items: center; gap: 1rem;">
      <span style="font-size: 0.85rem; color: #000; font-weight: 700;">Admin: <?= htmlspecialchars($currentUser['name']) ?></span>
      <a href="../logout.php" class="btn btn-outline btn-sm">Logout</a>
    </div>
  </div>
</header>
