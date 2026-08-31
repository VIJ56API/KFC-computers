<?php
require_once __DIR__ . '/../includes/data_manager.php';
require_once __DIR__ . '/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $stock = (int)($_POST['stock'] ?? 10);

    if (empty($name) || empty($category) || $price <= 0) {
        $error = 'Please fill in all required fields (Name, Category, Price).';
    } else {
        $fileInput = $_FILES['product_image'] ?? null;
        $added = addCatalogItem($_POST, $fileInput);

        if ($added) {
            $success = "Successfully added product '{$added['name']}'!";
        } else {
            $error = 'Failed to add product. Please check your image upload or file permissions.';
        }
    }
}

$allCategories = [
    'Ready-Made PC', 'CPU', 'Motherboard', 'RAM', 
    'GPU', 'SSD', 'HDD', 'PSU', 
    'Cabinet', 'CPU Cooler', 'Monitor', 'Keyboard', 'Mouse'
];
?>

<div class="container" style="max-width: 800px;">
  <div style="margin-bottom: 2rem;">
    <a href="products.php" style="color: var(--text-muted); font-size: 0.9rem;">← Back to Product List</a>
    <h2 style="font-size: 2rem; margin-top: 0.5rem;">Add New <span class="gradient-text">Product / Component</span></h2>
  </div>

  <?php if (!empty($error)): ?>
    <div style="background: rgba(239,68,68,0.15); border: 1px solid var(--accent-red); color: var(--accent-red); padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem;">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <div style="background: rgba(16,185,129,0.15); border: 1px solid var(--accent-green); color: var(--accent-green); padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem;">
      <?= htmlspecialchars($success) ?>
      <div style="margin-top: 0.5rem;">
        <a href="products.php" class="btn btn-primary btn-sm">View All Products</a>
        <a href="add-product.php" class="btn btn-outline btn-sm">Add Another Product</a>
      </div>
    </div>
  <?php endif; ?>

  <div class="form-card" style="max-width: 100%; margin: 0;">
    <form action="add-product.php" method="POST" enctype="multipart/form-data">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem;">
        <div class="form-group">
          <label>Product Name *</label>
          <input type="text" name="name" class="form-control" placeholder="e.g. LIT RTX 4060 Gaming Beast" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label>Category *</label>
          <select name="category" id="category-select" class="form-control" required onchange="toggleCompatFields(this.value)">
            <option value="">-- Select Category --</option>
            <?php foreach ($allCategories as $cat): ?>
              <option value="<?= $cat ?>" <?= ($_POST['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.2rem;">
        <div class="form-group">
          <label>Brand</label>
          <input type="text" name="brand" class="form-control" placeholder="e.g. AMD, NVIDIA, Corsair, LIT" value="<?= htmlspecialchars($_POST['brand'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label>Price in Indian Rupee (₹) *</label>
          <input type="number" name="price" step="1" class="form-control" placeholder="e.g. 45999" required value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
        </div>

        <div class="form-group">
          <label>Stock Count *</label>
          <input type="number" name="stock" class="form-control" placeholder="e.g. 15" value="<?= htmlspecialchars($_POST['stock'] ?? 10) ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Short Description (Catalog Preview)</label>
        <input type="text" name="short_description" class="form-control" placeholder="e.g. An affordable gaming PC designed for 1080p gaming" value="<?= htmlspecialchars($_POST['short_description'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Full Overview Description</label>
        <textarea name="description" class="form-control" rows="3" placeholder="Detailed product marketing overview..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label>Technical Specifications (One per line or formatted)</label>
        <textarea name="specifications" class="form-control" rows="4" placeholder="CPU: AMD Ryzen 5 5600&#10;GPU: RTX 3050 6GB&#10;RAM: 16GB DDR4..."><?= htmlspecialchars($_POST['specifications'] ?? '') ?></textarea>
      </div>

      <!-- Component Compatibility Fields -->
      <div id="compat-fields" style="background: var(--bg-surface); padding: 1.2rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); margin-bottom: 1.5rem;">
        <strong style="color: var(--primary-cyan); font-size: 0.9rem; display: block; margin-bottom: 0.8rem;">Component Builder Compatibility Parameters:</strong>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem;">
          <div>
            <label style="font-size: 0.78rem; color: var(--text-muted);">CPU/MB Socket</label>
            <input type="text" name="socket" class="form-control" placeholder="AM4, AM5, LGA1700">
          </div>
          <div>
            <label style="font-size: 0.78rem; color: var(--text-muted);">RAM Type</label>
            <input type="text" name="ram_type" class="form-control" placeholder="DDR4, DDR5">
          </div>
          <div>
            <label style="font-size: 0.78rem; color: var(--text-muted);">Draw Wattage (W)</label>
            <input type="number" name="wattage" class="form-control" placeholder="65, 115, 200">
          </div>
          <div>
            <label style="font-size: 0.78rem; color: var(--text-muted);">PSU Wattage (W)</label>
            <input type="number" name="psu_wattage" class="form-control" placeholder="550, 650, 750">
          </div>
        </div>
      </div>

      <div class="form-group">
        <label>Product Image Upload (JPG, PNG, WEBP, SVG)</label>
        <input type="file" name="product_image" accept="image/*" class="form-control">
        <small style="color: var(--text-dim); font-size: 0.78rem; margin-top: 4px; display: block;">
          File will be saved securely in <code>assets/images/products/</code>.
        </small>
      </div>

      <button type="submit" class="btn btn-primary btn-block" style="padding: 0.8rem; margin-top: 1.5rem;">
        <i class="fa-solid fa-plus-circle"></i> Add Product to Website
      </button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
