<?php
require_once __DIR__ . '/includes/data_manager.php';
require_once __DIR__ . '/includes/header.php';

$products = getProducts();
$components = getComponents();
$featuredComponents = array_slice($components, 0, 8);
?>

<div class="container">
  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <span style="color: var(--primary-blue); font-weight: 800; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 2px;">
        🔥 Next-Gen PC Building
      </span>
      <h1>DOMINATE WITH <span class="gradient-text">KFC COMPUTERS</span></h1>
      <p>
        Pre-built gaming beasts & custom PC configurations powered by NVIDIA RTX, AMD Ryzen, and Intel Core. Designed for gamers, creators, and professionals in India.
      </p>
      <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="products.php?category=Ready-Made+PC" class="btn btn-primary">
          <i class="fa-solid fa-desktop"></i> Explore Ready-Made PCs
        </a>
        <a href="builder.php" class="btn btn-outline">
          <i class="fa-solid fa-screwdriver-wrench"></i> Launch Custom Builder
        </a>
      </div>

      <div class="hero-stats">
        <div class="stat-item">
          <h4>₹32.9K+</h4>
          <p>Starting Price</p>
        </div>
        <div class="stat-item">
          <h4>3 Years</h4>
          <p>On-Site Warranty</p>
        </div>
        <div class="stat-item">
          <h4>100%</h4>
          <p>Compatibility Check</p>
        </div>
      </div>
    </div>

    <div style="text-align: center;">
      <img src="assets/images/products/ultimate-gaming-pc.png" alt="KFC Ultimate Gaming PC" style="max-width: 100%;">
    </div>
  </section>

  <!-- Trust Badges -->
  <div class="trust-bar">
    <div class="trust-card">
      <i class="fa-solid fa-truck-fast trust-icon"></i>
      <div>
        <h4>Pan-India Shipping</h4>
        <p>Insured & safe delivery nationwide</p>
      </div>
    </div>
    <div class="trust-card">
      <i class="fa-solid fa-shield-halved trust-icon"></i>
      <div>
        <h4>3-Year Warranty</h4>
        <p>Complete peace of mind hardware guarantee</p>
      </div>
    </div>
    <div class="trust-card">
      <i class="fa-solid fa-microchip trust-icon"></i>
      <div>
        <h4>100% Genuine Parts</h4>
        <p>Directly sourced from authorized brand distributors</p>
      </div>
    </div>
    <div class="trust-card">
      <i class="fa-solid fa-screwdriver-wrench trust-icon"></i>
      <div>
        <h4>Expert Stress Tested</h4>
        <p>24-hour benchmark & temperature verification</p>
      </div>
    </div>
  </div>

  <!-- Featured Ready-Made PCs -->
  <section style="margin-bottom: 3.5rem;">
    <div class="section-header">
      <div>
        <h2>Ready-Made <span class="gradient-text">Gaming & Office PCs</span></h2>
        <p>Pre-assembled, stress-tested, and ready to plug-and-play immediately.</p>
      </div>
      <a href="products.php?category=Ready-Made+PC" class="btn btn-outline btn-sm">View All PCs →</a>
    </div>

    <div class="product-grid">
      <?php foreach ($products as $p): ?>
        <div class="product-card">
          <div class="product-img-wrapper">
            <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
            <span class="badge-category"><?= htmlspecialchars($p['category']) ?></span>
            <span class="badge-stock">In Stock</span>
          </div>

          <div class="product-info">
            <div class="product-brand"><?= htmlspecialchars($p['brand'] ?? 'KFC Computers') ?></div>
            <h3><?= htmlspecialchars($p['name']) ?></h3>
            <p class="product-desc"><?= htmlspecialchars($p['short_description']) ?></p>
            <div class="product-specs-summary"><?= htmlspecialchars($p['specifications']) ?></div>

            <div class="product-footer">
              <div class="product-price">₹<?= number_format($p['price']) ?></div>
              <div class="product-actions">
                <a href="product-details.php?id=<?= $p['id'] ?>" class="btn btn-secondary btn-sm" title="View Specifications">
                  <i class="fa-solid fa-eye"></i> Details
                </a>
                <button class="btn btn-primary btn-sm" onclick='addToCart(<?= json_encode([
                  "id" => $p["id"],
                  "name" => $p["name"],
                  "price" => $p["price"],
                  "image" => $p["image"],
                  "category" => $p["category"]
                ]) ?>)'>
                  <i class="fa-solid fa-cart-plus"></i> Add
                </button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Featured Hardware Components -->
  <section>
    <div class="section-header">
      <div>
        <h2>Trending <span class="gradient-text">PC Components</span></h2>
        <p>Upgrade your existing setup with high-performance processors, graphics cards, and storage.</p>
      </div>
      <a href="products.php" class="btn btn-outline btn-sm">Browse Catalog →</a>
    </div>

    <div class="product-grid">
      <?php foreach ($featuredComponents as $c): ?>
        <div class="product-card">
          <div class="product-img-wrapper">
            <img src="<?= htmlspecialchars($c['image']) ?>" alt="<?= htmlspecialchars($c['name']) ?>">
            <span class="badge-category"><?= htmlspecialchars($c['category']) ?></span>
            <span class="badge-stock">In Stock</span>
          </div>

          <div class="product-info">
            <div class="product-brand"><?= htmlspecialchars($c['brand'] ?? 'KFC') ?></div>
            <h3><?= htmlspecialchars($c['name']) ?></h3>
            <div class="product-specs-summary"><?= htmlspecialchars($c['specifications']) ?></div>

            <div class="product-footer">
              <div class="product-price">₹<?= number_format($c['price']) ?></div>
              <div class="product-actions">
                <a href="product-details.php?id=<?= $c['id'] ?>" class="btn btn-secondary btn-sm">
                  <i class="fa-solid fa-eye"></i> Details
                </a>
                <button class="btn btn-primary btn-sm" onclick='addToCart(<?= json_encode([
                  "id" => $c["id"],
                  "name" => $c["name"],
                  "price" => $c["price"],
                  "image" => $c["image"],
                  "category" => $c["category"]
                ]) ?>)'>
                  <i class="fa-solid fa-cart-plus"></i> Add
                </button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
