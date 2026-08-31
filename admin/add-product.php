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
    <a href="products.php" style="color: #000000; font-size: 0.9rem; font-weight: 800;">← Back to Product List</a>
    <h2 style="font-size: 2rem; margin-top: 0.5rem; color: #000000; font-weight: 900;">Add New <span class="gradient-text">Product / Component</span></h2>
  </div>

  <?php if (!empty($error)): ?>
    <div style="background: #fee2e2; border: 2px solid var(--accent-red); color: var(--accent-red); padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 800;">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <div style="background: #dcfce7; border: 2px solid var(--accent-green); color: var(--accent-green); padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 800;">
      <?= htmlspecialchars($success) ?>
      <div style="margin-top: 0.5rem;">
        <a href="products.php" class="btn btn-primary btn-sm">View All Products</a>
        <a href="add-product.php" class="btn btn-outline btn-sm">Add Another Product</a>
      </div>
    </div>
  <?php endif; ?>

  <div class="form-card" style="max-width: 100%; margin: 0; border: 2px solid #cbd5e1; box-shadow: var(--shadow-card);">
    <form action="add-product.php" method="POST" enctype="multipart/form-data">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem;">
        <div class="form-group">
          <label style="color: #000000; font-weight: 900;">Product Name *</label>
          <input type="text" name="name" class="form-control" placeholder="e.g. KFC RTX 4060 Gaming Beast" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" style="color: #000000; font-weight: 700;">
        </div>

        <div class="form-group">
          <label style="color: #000000; font-weight: 900;">Category *</label>
          <select name="category" id="category-select" class="form-control" required onchange="toggleCompatFields(this.value)" style="color: #000000; font-weight: 700;">
            <option value="">-- Select Category --</option>
            <?php foreach ($allCategories as $cat): ?>
              <option value="<?= $cat ?>" <?= ($_POST['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.2rem;">
        <div class="form-group">
          <label style="color: #000000; font-weight: 900;">Brand</label>
          <input type="text" name="brand" class="form-control" placeholder="e.g. AMD, NVIDIA, Corsair, KFC" value="<?= htmlspecialchars($_POST['brand'] ?? '') ?>" style="color: #000000; font-weight: 700;">
        </div>

        <div class="form-group">
          <label style="color: #000000; font-weight: 900;">Price in Indian Rupee (₹) *</label>
          <input type="number" name="price" step="1" class="form-control" placeholder="e.g. 45999" required value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" style="color: #000000; font-weight: 700;">
        </div>

        <div class="form-group">
          <label style="color: #000000; font-weight: 900;">Stock Count *</label>
          <input type="number" name="stock" class="form-control" placeholder="e.g. 15" value="<?= htmlspecialchars($_POST['stock'] ?? 10) ?>" style="color: #000000; font-weight: 700;">
        </div>
      </div>

      <div class="form-group">
        <label style="color: #000000; font-weight: 900;">Short Description (Catalog Preview)</label>
        <input type="text" name="short_description" class="form-control" placeholder="e.g. An affordable gaming PC designed for 1080p gaming" value="<?= htmlspecialchars($_POST['short_description'] ?? '') ?>" style="color: #000000; font-weight: 700;">
      </div>

      <div class="form-group">
        <label style="color: #000000; font-weight: 900;">Full Overview Description</label>
        <textarea name="description" class="form-control" rows="3" placeholder="Enter detailed product description..." style="color: #000000; font-weight: 700;"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label style="color: #000000; font-weight: 900;">Technical Specifications (One per line)</label>
        <textarea name="specifications" class="form-control" rows="4" placeholder="CPU: AMD Ryzen 5&#10;GPU: RTX 4060&#10;RAM: 16GB DDR5" style="color: #000000; font-weight: 700;"><?= htmlspecialchars($_POST['specifications'] ?? '') ?></textarea>
      </div>

      <!-- Conditional Builder Compatibility Fields -->
      <div id="compat-fields" style="background: var(--bg-surface); padding: 1.2rem; border-radius: var(--radius-sm); border: 2px solid #cbd5e1; margin-bottom: 1.5rem;">
        <h4 style="margin-bottom: 0.8rem; font-size: 0.95rem; color: #000000; font-weight: 900;">Custom PC Builder Compatibility Rules (Optional)</h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="form-group">
            <label style="color: #000000; font-weight: 800;">Socket Type (CPU / Motherboard)</label>
            <input type="text" name="socket" class="form-control" placeholder="AM4, AM5, LGA1700" style="color: #000000; font-weight: 700;">
          </div>
          <div class="form-group">
            <label style="color: #000000; font-weight: 800;">RAM Standard (Motherboard / RAM)</label>
            <input type="text" name="ram_type" class="form-control" placeholder="DDR4, DDR5" style="color: #000000; font-weight: 700;">
          </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="form-group">
            <label style="color: #000000; font-weight: 800;">TDP / Power Draw Wattage (CPU / GPU)</label>
            <input type="number" name="wattage" class="form-control" placeholder="e.g. 65, 115, 200" style="color: #000000; font-weight: 700;">
          </div>
          <div class="form-group">
            <label style="color: #000000; font-weight: 800;">PSU Wattage Capacity (PSU only)</label>
            <input type="number" name="psu_wattage" class="form-control" placeholder="e.g. 550, 650, 750" style="color: #000000; font-weight: 700;">
          </div>
        </div>
      </div>

      <div class="form-group">
        <label style="color: #000000; font-weight: 900;">Upload Product Image (JPG, PNG, WEBP, SVG)</label>
        <input type="file" name="product_image" class="form-control" accept="image/*" style="color: #000000; font-weight: 700;">
      </div>

      <button type="submit" class="btn btn-primary btn-block" style="padding: 0.8rem; margin-top: 1rem; color: #000000 !important; font-weight: 900;">
        <i class="fa-solid fa-cloud-arrow-up"></i> Save Product to Catalog
      </button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
