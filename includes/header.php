<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentUser = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LIT Computers | High-Performance PCs & Components</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header class="navbar">
  <div class="nav-container">
    <a href="index.php" class="logo">
      LIT <span>COMPUTERS</span> <span class="logo-badge">INDIA</span>
    </a>

    <form action="products.php" method="GET" class="search-bar">
      <i class="fa-solid fa-magnifying-glass search-icon"></i>
      <input type="text" name="search" placeholder="Search RTX 4060, Ryzen, Gaming PC, DDR5..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    </form>

    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="products.php?category=Ready-Made+PC">Ready-Made PCs</a></li>
      <li><a href="products.php">Components</a></li>
      <li><a href="builder.php" style="color: var(--primary-cyan); font-weight: 700;"><i class="fa-solid fa-screwdriver-wrench"></i> PC Builder</a></li>
      <?php if ($currentUser && ($currentUser['role'] === 'admin')): ?>
        <li><a href="admin/products.php" style="color: var(--accent-warning);"><i class="fa-solid fa-user-shield"></i> Admin Panel</a></li>
      <?php endif; ?>
    </ul>

    <div style="display: flex; align-items: center; gap: 1rem;">
      <a href="cart.php" class="cart-btn">
        <i class="fa-solid fa-cart-shopping"></i>
        <span>Cart</span>
        <span class="cart-badge">0</span>
      </a>

      <?php if ($currentUser): ?>
        <div style="display: flex; align-items: center; gap: 0.5rem;">
          <span style="font-size: 0.85rem; color: var(--text-muted);">Hi, <?= htmlspecialchars($currentUser['name']) ?></span>
          <a href="logout.php" class="btn btn-outline btn-sm">Logout</a>
        </div>
      <?php else: ?>
        <a href="login.php" class="btn btn-primary btn-sm">Login</a>
      <?php endif; ?>
    </div>
  </div>
</header>
