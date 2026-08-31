<?php
require_once __DIR__ . '/../includes/data_manager.php';
require_once __DIR__ . '/header.php';

$id = $_GET['id'] ?? 0;
$product = getItemById($id);

if (!$product) {
    echo "<div class='container' style='padding: 4rem; text-align: center;'><h2>Product Not Found</h2><a href='products.php' class='btn btn-primary'>Back to List</a></div>";
    require_once __DIR__ . '/footer.php';
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileInput = $_FILES['product_image'] ?? null;
    $updated = updateCatalogItem($id, $_POST, $fileInput);

    if ($updated) {
        $success = "Successfully updated product '{$_POST['name']}'!";
        $product = getItemById($id); // Reload updated
    } else {
        $error = 'Failed to update product details.';
    }
}

$allCategories = [
    'Ready-Made PC', 'CPU', 'Motherboard', 'RAM', 
    'GPU', 'SSD', 'HDD', 'PSU', 
    'Cabinet', 'CPU Cooler', 'Monitor', 'Keyboard', 'Mouse'
];
$compat = $product['compatibility'] ?? [];
?>

<div class="container" style="max-width: 800px;">
  <div style="margin-bottom: 2rem;">
    <a href="products.php" style="color: var(--text-muted); font-size: 0.9rem;">← Back to Product List</a>
    <h2 style="font-size: 2rem; margin-top: 0.5rem;">Edit <span class="gradient-text"><?= htmlspecialchars($product['name']) ?></span></h2>
  </div>

  <?php if (!empty($error)): ?>
    <div style="background: rgba(239,68,68,0.15); border: 1px solid var(--accent-red); color: var(--accent-red); padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem;">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <div style="background: rgba(16,185,129,0.15); border: 1px solid var(--accent-green); color: var(--accent-green); padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem;">
      <?= htmlspecialchars($success) ?>
    </div>
  <?php endif; ?>

  <div class="form-card" style="max-width: 100%; margin: 0;">
    <form action="edit-product.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem;">
        <div class="form-group">
          <label>Product Name *</label>
          <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($product['name']) ?>">
        </div>

        <div class="form-group">
          <label>Category *</label>
          <select name="category" class="form-control" required>
            <?php foreach ($allCategories as $cat): ?>
              <option value="<?= $cat ?>" <?= $product['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.2rem;">
        <div class="form-group">
          <label>Brand</label>
          <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($product['brand'] ?? 'LIT Computers') ?>">
        </div>

        <div class="form-group">
          <label>Price in Indian Rupee (₹) *</label>
          <input type="number" name="price" step="1" class="form-control" required value="<?= (float)$product['price'] ?>">
        </div>

        <div class="form-group">
          <label>Stock Count *</label>
          <input type="number" name="stock" class="form-control" value="<?= (int)($product['stock'] ?? 10) ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Short Description</label>
        <input type="text" name="short_description" class="form-control" value="<?= htmlspecialchars($product['short_description'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Full Overview Description</label>
        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label>Technical Specifications</label>
        <textarea name="specifications" class="form-control" rows="4"><?= htmlspecialchars($product['specifications'] ?? '') ?></textarea>
      </div>

      <!-- Component Compatibility Fields -->
      <div style="background: var(--bg-surface); padding: 1.2rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color); margin-bottom: 1.5rem;">
        <strong style="color: var(--primary-cyan); font-size: 0.9rem; display: block; margin-bottom: 0.8rem;">Component Compatibility Settings:</strong>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem;">
          <div>
            <label style="font-size: 0.78rem; color: var(--text-muted);">CPU/MB Socket</label>
            <input type="text" name="socket" class="form-control" value="<?= htmlspecialchars($compat['socket'] ?? '') ?>">
          </div>
          <div>
            <label style="font-size: 0.78rem; color: var(--text-muted);">RAM Type</label>
            <input type="text" name="ram_type" class="form-control" value="<?= htmlspecialchars($compat['ram_type'] ?? '') ?>">
          </div>
          <div>
            <label style="font-size: 0.78rem; color: var(--text-muted);">Draw Wattage (W)</label>
            <input type="number" name="wattage" class="form-control" value="<?= (int)($compat['wattage'] ?? 0) ?>">
          </div>
          <div>
            <label style="font-size: 0.78rem; color: var(--text-muted);">PSU Wattage (W)</label>
            <input type="number" name="psu_wattage" class="form-control" value="<?= (int)($compat['psu_wattage'] ?? 0) ?>">
          </div>
        </div>
      </div>

      <div class="form-group">
        <label>Current Product Image</label>
        <div style="margin-bottom: 0.8rem; background: #0b1120; padding: 1rem; border-radius: 8px; display: inline-block;">
          <img src="../<?= htmlspecialchars($product['image']) ?>" style="height: 100px; object-fit: contain;">
        </div>
        <label>Replace Image (Optional Upload)</label>
        <input type="file" name="product_image" accept="image/*" class="form-control">
      </div>

      <button type="submit" class="btn btn-primary btn-block" style="padding: 0.8rem; margin-top: 1.5rem;">
        <i class="fa-solid fa-floppy-disk"></i> Save Product Changes
      </button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
