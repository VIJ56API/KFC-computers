<?php
require_once __DIR__ . '/includes/data_manager.php';
require_once __DIR__ . '/includes/header.php';

$id = $_GET['id'] ?? 0;
$product = getItemById($id);

if (!$product) {
    echo "<div class='container' style='padding: 4rem; text-align: center;'><h2>Product Not Found</h2><p>The requested product could not be located in our catalog.</p><a href='products.php' class='btn btn-primary' style='margin-top: 1rem;'>Back to Catalog</a></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$relatedItems = filterCatalog('', $product['category']);
$relatedItems = array_filter($relatedItems, function($i) use ($product) {
    return $i['id'] !== $product['id'];
});
$relatedItems = array_slice($relatedItems, 0, 4);
?>

<div class="container">
  <div style="margin-bottom: 1.5rem; color: var(--text-muted); font-size: 0.9rem;">
    <a href="index.php">Home</a> &nbsp;/&nbsp; 
    <a href="products.php?category=<?= urlencode($product['category']) ?>"><?= htmlspecialchars($product['category']) ?></a> &nbsp;/&nbsp; 
    <span style="color: var(--text-main);"><?= htmlspecialchars($product['name']) ?></span>
  </div>

  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; background: var(--bg-card); border: 1px solid var(--border-color); padding: 2.5rem; border-radius: var(--radius-lg); margin-bottom: 3rem;">
    <!-- Left: Product Image Visual -->
    <div style="background: #0b1120; border: 1px solid var(--border-color); border-radius: var(--radius); padding: 2rem; display: flex; align-items: center; justify-content: center; min-height: 380px;">
      <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="max-height: 320px; max-width: 100%; object-fit: contain;">
    </div>

    <!-- Right: Specifications & Purchasing -->
    <div style="display: flex; flex-direction: column;">
      <div style="margin-bottom: 0.5rem; display: flex; gap: 0.8rem; align-items: center;">
        <span class="badge-category" style="position: static;"><?= htmlspecialchars($product['category']) ?></span>
        <span class="badge-stock" style="position: static;">In Stock (<?= (int)($product['stock'] ?? 10) ?> Available)</span>
      </div>

      <h1 style="font-size: 2.2rem; margin-bottom: 0.5rem;"><?= htmlspecialchars($product['name']) ?></h1>
      <div style="color: var(--text-dim); font-size: 0.9rem; font-weight: 600; text-transform: uppercase; margin-bottom: 1rem;">
        Brand: <?= htmlspecialchars($product['brand'] ?? 'LIT Computers') ?>
      </div>

      <div style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 800; color: var(--primary-cyan); margin-bottom: 1.5rem;">
        ₹<?= number_format($product['price']) ?>
      </div>

      <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 1.5rem; line-height: 1.6;">
        <?= htmlspecialchars($product['short_description'] ?? $product['description'] ?? 'High performance genuine PC hardware engineered for reliability and extreme workloads.') ?>
      </p>

      <!-- Add to Cart Action Box -->
      <div style="background: var(--bg-surface); padding: 1.2rem; border-radius: var(--radius); border: 1px solid var(--border-color); margin-bottom: 2rem; display: flex; gap: 1rem; align-items: center;">
        <div>
          <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 4px;">Quantity</label>
          <input type="number" id="detail-qty" value="1" min="1" max="<?= (int)($product['stock'] ?? 10) ?>" style="width: 70px; padding: 0.5rem; background: var(--bg-dark); border: 1px solid var(--border-color); border-radius: 6px; color: #fff; text-align: center;">
        </div>

        <button class="btn btn-primary" style="flex: 1; padding: 0.8rem 1.5rem;" onclick='addToCart(<?= json_encode([
          "id" => $product["id"],
          "name" => $product["name"],
          "price" => $product["price"],
          "image" => $product["image"],
          "category" => $product["category"]
        ]) ?>, parseInt(document.getElementById("detail-qty").value))'>
          <i class="fa-solid fa-cart-shopping"></i> Add To Cart
        </button>
      </div>

      <div style="font-size: 0.85rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 0.5rem;">
        <div><i class="fa-solid fa-truck" style="color: var(--primary-cyan);"></i> Insured Pan-India Express Shipping</div>
        <div><i class="fa-solid fa-shield" style="color: var(--primary-cyan);"></i> Includes 3-Year Standard Warranty</div>
      </div>
    </div>
  </div>

  <!-- Detailed Specifications -->
  <section style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius); padding: 2rem; margin-bottom: 3rem;">
    <h2 style="margin-bottom: 1.2rem; font-size: 1.5rem;">Complete Technical Specifications</h2>
    
    <div style="background: var(--bg-surface); border-radius: var(--radius-sm); padding: 1.5rem; font-family: monospace; font-size: 0.95rem; white-space: pre-wrap; line-height: 1.8; color: var(--text-main); border: 1px solid var(--border-color);">
<?= htmlspecialchars($product['specifications'] ?? 'No technical specifications listed.') ?>
    </div>

    <?php if (!empty($product['description'])): ?>
      <div style="margin-top: 2rem;">
        <h3 style="font-size: 1.2rem; margin-bottom: 0.5rem;">Product Overview</h3>
        <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.7;"><?= htmlspecialchars($product['description']) ?></p>
      </div>
    <?php endif; ?>
  </section>

  <!-- Related Products -->
  <?php if (!empty($relatedItems)): ?>
    <section>
      <h2 style="font-size: 1.5rem; margin-bottom: 1.2rem;">Related <span class="gradient-text">Products & Accessories</span></h2>
      <div class="product-grid">
        <?php foreach ($relatedItems as $item): ?>
          <div class="product-card">
            <div class="product-img-wrapper">
              <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
              <span class="badge-category"><?= htmlspecialchars($item['category']) ?></span>
            </div>
            <div class="product-info">
              <div class="product-brand"><?= htmlspecialchars($item['brand'] ?? 'LIT') ?></div>
              <h3><?= htmlspecialchars($item['name']) ?></h3>
              <div class="product-footer">
                <div class="product-price">₹<?= number_format($item['price']) ?></div>
                <a href="product-details.php?id=<?= $item['id'] ?>" class="btn btn-outline btn-sm">View Details</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
