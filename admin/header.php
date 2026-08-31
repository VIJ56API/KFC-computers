<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$adminUser = $_SESSION['user'] ?? null;
if (!$adminUser || ($adminUser['role'] !== 'admin')) {
    header('Location: ../login.php?redirect=admin/products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LIT Computers | Admin Catalog Control Panel</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background-color: #070b12;">

<header class="navbar" style="background: rgba(15, 23, 42, 0.95); border-bottom: 1px solid rgba(245, 158, 11, 0.3);">
  <div class="nav-container">
    <a href="products.php" class="logo">
      LIT <span>ADMIN</span> <span class="logo-badge" style="background: var(--accent-warning);">CONTROL</span>
    </a>

    <ul class="nav-links">
      <li><a href="products.php"><i class="fa-solid fa-boxes-stacked"></i> Product Catalog</a></li>
      <li><a href="add-product.php" style="color: var(--primary-cyan); font-weight: 700;"><i class="fa-solid fa-plus-circle"></i> Add New Product</a></li>
      <li><a href="orders.php"><i class="fa-solid fa-receipt"></i> Orders List</a></li>
      <li><a href="../index.php" target="_blank" style="color: var(--text-muted);"><i class="fa-solid fa-globe"></i> View Website</a></li>
    </ul>

    <div style="display: flex; align-items: center; gap: 1rem;">
      <span style="font-size: 0.85rem; color: var(--text-muted);">Admin: <?= htmlspecialchars($adminUser['name']) ?></span>
      <a href="../logout.php" class="btn btn-outline btn-sm">Logout</a>
    </div>
  </div>
</header>
