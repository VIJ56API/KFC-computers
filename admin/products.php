<?php
require_once __DIR__ . '/../includes/data_manager.php';
require_once __DIR__ . '/header.php';

$searchQuery = $_GET['search'] ?? '';
$selectedCategory = $_GET['category'] ?? '';

$items = filterCatalog($searchQuery, $selectedCategory);
$productsCount = count(getProducts());
$componentsCount = count(getComponents());

$allCategories = [
    'Ready-Made PC', 'CPU', 'Motherboard', 'RAM', 
    'GPU', 'SSD', 'HDD', 'PSU', 
    'Cabinet', 'CPU Cooler', 'Monitor', 'Keyboard', 'Mouse'
];
?>

<div class="container">
  <!-- Top Action & Overview Header -->
  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h2 style="font-size: 2rem;">Admin <span class="gradient-text">Product Management</span></h2>
      <p style="color: var(--text-muted); font-size: 0.9rem;">
        Add, edit, delete, and manage stock for Ready-Made PCs and hardware components.
      </p>
    </div>

    <a href="add-product.php" class="btn btn-primary" style="padding: 0.8rem 1.6rem;">
      <i class="fa-solid fa-plus"></i> + Add New Product
    </a>
  </div>

  <!-- Summary Cards -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.2rem; margin-bottom: 2rem;">
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 1.2rem; border-radius: var(--radius);">
      <div style="color: var(--text-dim); font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">Total Ready-Made PCs</div>
      <div style="font-size: 1.8rem; font-weight: 800; color: var(--primary-cyan);"><?= $productsCount ?></div>
    </div>
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 1.2rem; border-radius: var(--radius);">
      <div style="color: var(--text-dim); font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">Total Components</div>
      <div style="font-size: 1.8rem; font-weight: 800; color: var(--accent-purple);"><?= $componentsCount ?></div>
    </div>
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); padding: 1.2rem; border-radius: var(--radius);">
      <div style="color: var(--text-dim); font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">Catalog Storage</div>
      <div style="font-size: 1.1rem; font-weight: 700; color: var(--accent-green); margin-top: 6px;">JSON File Based</div>
    </div>
  </div>

  <!-- Filter & Search Bar -->
  <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius); padding: 1.2rem; margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
    <form action="products.php" method="GET" style="display: flex; gap: 1rem; flex: 1; flex-wrap: wrap;">
      <input type="text" name="search" placeholder="Search by name, brand, or specs..." value="<?= htmlspecialchars($searchQuery) ?>" class="form-control" style="flex: 1; min-width: 200px;">
      
      <select name="category" class="form-control" style="width: 200px;" onchange="this.form.submit()">
        <option value="">All Categories</option>
        <?php foreach ($allCategories as $cat): ?>
          <option value="<?= $cat ?>" <?= $selectedCategory === $cat ? 'selected' : '' ?>><?= $cat ?></option>
        <?php endforeach; ?>
      </select>

      <button type="submit" class="btn btn-secondary">Filter</button>
      <a href="products.php" class="btn btn-outline btn-sm" style="display: flex; align-items: center;">Reset</a>
    </form>
  </div>

  <!-- Product Management Table -->
  <div class="admin-table-wrapper">
    <table class="admin-table">
      <thead>
        <tr>
          <th style="width: 70px;">Image</th>
          <th>Product Name & Brand</th>
          <th>Category</th>
          <th>Price (₹)</th>
          <th>Stock</th>
          <th>Specifications</th>
          <th style="text-align: right; width: 140px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($items) === 0): ?>
          <tr>
            <td colspan="7" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
              No products found matching your search.
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($items as $item): ?>
            <tr>
              <td>
                <img src="../<?= htmlspecialchars($item['image']) ?>" class="admin-thumb" alt="<?= htmlspecialchars($item['name']) ?>">
              </td>
              <td>
                <strong style="color: #fff; font-size: 0.95rem; display: block;"><?= htmlspecialchars($item['name']) ?></strong>
                <span style="font-size: 0.78rem; color: var(--text-dim);"><?= htmlspecialchars($item['brand'] ?? 'LIT Computers') ?></span>
              </td>
              <td>
                <span class="badge-category" style="position: static;"><?= htmlspecialchars($item['category']) ?></span>
              </td>
              <td style="font-weight: 700; color: var(--primary-cyan);">
                ₹<?= number_format($item['price']) ?>
              </td>
              <td>
                <span style="color: <?= ($item['stock'] ?? 10) < 5 ? 'var(--accent-warning)' : 'var(--accent-green)' ?>; font-weight: 700;">
                  <?= (int)($item['stock'] ?? 10) ?> in stock
                </span>
              </td>
              <td style="max-width: 250px; font-size: 0.8rem; color: var(--text-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                <?= htmlspecialchars($item['specifications'] ?? '') ?>
              </td>
              <td style="text-align: right;">
                <div style="display: flex; gap: 0.4rem; justify-content: flex-end;">
                  <a href="edit-product.php?id=<?= $item['id'] ?>" class="btn btn-secondary btn-sm" title="Edit Product">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                  </a>
                  <a href="delete-product.php?id=<?= $item['id'] ?>" class="btn btn-danger btn-sm" title="Delete Product" onclick="return confirm('Are you sure you want to delete this product?');">
                    <i class="fa-solid fa-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
