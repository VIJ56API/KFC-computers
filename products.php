<?php
require_once __DIR__ . '/includes/data_manager.php';
require_once __DIR__ . '/includes/header.php';

$searchQuery = $_GET['search'] ?? '';
$selectedCategory = $_GET['category'] ?? '';
$selectedBrand = $_GET['brand'] ?? '';
$minPrice = (float)($_GET['min_price'] ?? 0);
$maxPrice = (float)($_GET['max_price'] ?? 0);
$sort = $_GET['sort'] ?? 'default';

$items = filterCatalog($searchQuery, $selectedCategory, $minPrice, $maxPrice, $selectedBrand, $sort);

$allCategories = [
    'Ready-Made PC', 'CPU', 'Motherboard', 'RAM', 
    'GPU', 'SSD', 'HDD', 'PSU', 
    'Cabinet', 'CPU Cooler', 'Monitor', 'Keyboard', 'Mouse'
];

$allBrands = [
    'AMD', 'NVIDIA', 'Intel', 'Corsair', 'Kingston', 
    'Samsung', 'MSI', 'Gigabyte', 'ASUS', 'DeepCool', 
    'Seagate', 'Western Digital', 'KFC Computers'
];
?>

<div class="container">
  <div style="margin-bottom: 2rem;">
    <h2>Product <span class="gradient-text">Catalog & Components</span></h2>
    <p style="color: #000000; font-weight: 500;">Explore genuine hardware, custom parts, and pre-built gaming systems.</p>
  </div>

  <div class="catalog-wrapper">
    <!-- Filter Sidebar -->
    <aside class="filter-panel">
      <form action="products.php" method="GET" id="filter-form">
        <div class="filter-group">
          <h4>Search</h4>
          <input type="text" name="search" placeholder="e.g. RTX 4060, Ryzen..." value="<?= htmlspecialchars($searchQuery) ?>">
        </div>

        <div class="filter-group">
          <h4>Category</h4>
          <select name="category" onchange="document.getElementById('filter-form').submit()">
            <option value="">All Categories</option>
            <?php foreach ($allCategories as $cat): ?>
              <option value="<?= $cat ?>" <?= $selectedCategory === $cat ? 'selected' : '' ?>><?= $cat ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="filter-group">
          <h4>Brand</h4>
          <select name="brand" onchange="document.getElementById('filter-form').submit()">
            <option value="">All Brands</option>
            <?php foreach ($allBrands as $b): ?>
              <option value="<?= $b ?>" <?= $selectedBrand === $b ? 'selected' : '' ?>><?= $b ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="filter-group">
          <h4>Price Range (₹)</h4>
          <div class="price-inputs">
            <input type="number" name="min_price" placeholder="Min ₹" value="<?= $minPrice > 0 ? $minPrice : '' ?>">
            <input type="number" name="max_price" placeholder="Max ₹" value="<?= $maxPrice > 0 ? $maxPrice : '' ?>">
          </div>
        </div>

        <div class="filter-group">
          <h4>Sort By</h4>
          <select name="sort" onchange="document.getElementById('filter-form').submit()">
            <option value="default" <?= $sort === 'default' ? 'selected' : '' ?>>Featured</option>
            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low → High</option>
            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High → Low</option>
            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest Arrivals</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="margin-bottom: 0.5rem;">Apply Filters</button>
        <a href="products.php" class="btn btn-secondary btn-block btn-sm" style="text-align: center;">Reset Filters</a>
      </form>
    </aside>

    <!-- Catalog Main Area -->
    <main>
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; background: var(--bg-card); padding: 0.8rem 1.2rem; border-radius: var(--radius); border: 2px solid #cbd5e1; box-shadow: var(--shadow-card);">
        <div style="color: #000000; font-size: 0.95rem; font-weight: 700;">
          Showing <strong><?= count($items) ?></strong> products
          <?php if (!empty($searchQuery)): ?> for "<strong><?= htmlspecialchars($searchQuery) ?></strong>"<?php endif; ?>
        </div>
      </div>

      <?php if (count($items) === 0): ?>
        <div style="background: var(--bg-card); border: 2px solid #cbd5e1; border-radius: var(--radius); padding: 3rem; text-align: center; box-shadow: var(--shadow-card);">
          <i class="fa-solid fa-box-open" style="font-size: 3rem; color: #334155; margin-bottom: 1rem;"></i>
          <h3 style="color: #000000; font-weight: 800;">No matching products found</h3>
          <p style="color: #000000; margin-bottom: 1.5rem; font-weight: 500;">Try adjusting your search terms or clearing your filter selections.</p>
          <a href="products.php" class="btn btn-primary">Clear Filters</a>
        </div>
      <?php else: ?>
        <div class="product-grid">
          <?php foreach ($items as $item): ?>
            <div class="product-card">
              <div class="product-img-wrapper">
                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                <span class="badge-category"><?= htmlspecialchars($item['category']) ?></span>
                <span class="badge-stock">In Stock</span>
              </div>

              <div class="product-info">
                <div class="product-brand"><?= htmlspecialchars($item['brand'] ?? 'KFC') ?></div>
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <div class="product-specs-summary"><?= htmlspecialchars($item['specifications']) ?></div>

                <div class="product-footer">
                  <div class="product-price">₹<?= number_format($item['price']) ?></div>
                  <div class="product-actions">
                    <a href="product-details.php?id=<?= $item['id'] ?>" class="btn btn-secondary btn-sm">
                      <i class="fa-solid fa-eye"></i> Details
                    </a>
                    <button class="btn btn-primary btn-sm" onclick='addToCart(<?= json_encode([
                      "id" => $item["id"],
                      "name" => $item["name"],
                      "price" => $item["price"],
                      "image" => $item["image"],
                      "category" => $item["category"]
                    ]) ?>)'>
                      <i class="fa-solid fa-cart-plus"></i> Add
                    </button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </main>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
