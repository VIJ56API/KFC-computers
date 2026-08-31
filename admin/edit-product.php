<?php
require_once __DIR__ . '/../includes/data_manager.php';
require_once __DIR__ . '/header.php';

$id = $_GET['id'] ?? 0;
$product = getItemById($id);

if (!$product) {
    echo "<div class='container' style='padding: 4rem; text-align: center;'><h2 style='color:#000;'>Product Not Found</h2><a href='products.php' class='btn btn-primary'>Back to List</a></div>";
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
    <a href="products.php" style="color: #000000; font-size: 0.9rem; font-weight: 800;">← Back to Product List</a>
    <h2 style="font-size: 2rem; margin-top: 0.5rem; color: #000000; font-weight: 900;">Edit <span class="gradient-text"><?= htmlspecialchars($product['name']) ?></span></h2>
  </div>

  <?php if (!empty($error)): ?>
    <div style="background: #fee2e2; border: 2px solid var(--accent-red); color: var(--accent-red); padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 800;">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <div style="background: #dcfce7; border: 2px solid var(--accent-green); color: var(--accent-green); padding: 1rem; border-radius: var(--radius-sm); margin-bottom: 1.5rem; font-weight: 800;">
      <?= htmlspecialchars($success) ?>
    </div>
  <?php endif; ?>

  <div class="form-card" style="max-width: 100%; margin: 0; border: 2px solid #cbd5e1; box-shadow: var(--shadow-card);">
    <form action="edit-product.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem;">
        <div class="form-group">
          <label style="color: #000000; font-weight: 900;">Product Name *</label>
          <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($product['name']) ?>" style="color: #000000; font-weight: 700;">
        </div>

        <div class="form-group">
          <label style="color: #000000; font-weight: 900;">Category *</label>
          <select name="category" class="form-control" required style="color: #000000; font-weight: 700;">
            <?php foreach ($allCategories as $cat): ?>
              <option value="<?= $cat ?>" <?= $product['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.2rem;">
        <div class="form-group">
          <label style="color: #000000; font-weight: 900;">Brand</label>
          <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($product['brand'] ?? 'KFC Computers') ?>" style="color: #000000; font-weight: 700;">
        </div>

        <div class="form-group">
          <label style="color: #000000; font-weight: 900;">Price in Indian Rupee (₹) *</label>
          <input type="number" name="price" step="1" class="form-control" required value="<?= (float)$product['price'] ?>" style="color: #000000; font-weight: 700;">
        </div>

        <div class="form-group">
          <label style="color: #000000; font-weight: 900;">Stock Count *</label>
          <input type="number" name="stock" class="form-control" value="<?= (int)($product['stock'] ?? 10) ?>" style="color: #000000; font-weight: 700;">
        </div>
      </div>

      <div class="form-group">
        <label style="color: #000000; font-weight: 900;">Short Description</label>
        <input type="text" name="short_description" class="form-control" value="<?= htmlspecialchars($product['short_description'] ?? '') ?>" style="color: #000000; font-weight: 700;">
      </div>

      <div class="form-group">
        <label style="color: #000000; font-weight: 900;">Full Overview Description</label>
        <textarea name="description" class="form-control" rows="3" style="color: #000000; font-weight: 700;"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label style="color: #000000; font-weight: 900;">Technical Specifications</label>
        <textarea name="specifications" class="form-control" rows="4" style="color: #000000; font-weight: 700;"><?= htmlspecialchars($product['specifications'] ?? '') ?></textarea>
      </div>

      <div style="background: var(--bg-surface); padding: 1.2rem; border-radius: var(--radius-sm); border: 2px solid #cbd5e1; margin-bottom: 1.5rem;">
        <h4 style="margin-bottom: 0.8rem; font-size: 0.95rem; color: #000000; font-weight: 900;">PC Builder Compatibility</h4>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="form-group">
            <label style="color: #000000; font-weight: 800;">Socket (AM4, AM5, LGA1700)</label>
            <input type="text" name="socket" class="form-control" value="<?= htmlspecialchars($compat['socket'] ?? '') ?>" style="color: #000000; font-weight: 700;">
          </div>
          <div class="form-group">
            <label style="color: #000000; font-weight: 800;">RAM Type (DDR4, DDR5)</label>
            <input type="text" name="ram_type" class="form-control" value="<?= htmlspecialchars($compat['ram_type'] ?? '') ?>" style="color: #000000; font-weight: 700;">
          </div>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div class="form-group">
            <label style="color: #000000; font-weight: 800;">Component Power Draw (W)</label>
            <input type="number" name="wattage" class="form-control" value="<?= htmlspecialchars($compat['wattage'] ?? '') ?>" style="color: #000000; font-weight: 700;">
          </div>
          <div class="form-group">
            <label style="color: #000000; font-weight: 800;">PSU Wattage Capacity (W)</label>
            <input type="number" name="psu_wattage" class="form-control" value="<?= htmlspecialchars($compat['psu_wattage'] ?? '') ?>" style="color: #000000; font-weight: 700;">
          </div>
        </div>
      </div>

      <div class="form-group">
        <label style="color: #000000; font-weight: 900;">Current Product Image</label>
        <div style="margin-bottom: 0.8rem; display: flex; align-items: center; gap: 1rem;">
          <img src="../<?= htmlspecialchars($product['image']) ?>" style="height: 70px; object-fit: contain; border: 1px solid #cbd5e1; border-radius: 6px; padding: 4px; background: #fff;" alt="Current Image">
          <span style="font-size: 0.85rem; color: #000000; font-weight: 700;"><?= htmlspecialchars($product['image']) ?></span>
        </div>
        <label style="color: #000000; font-weight: 900;">Upload New Image (Optional)</label>
        <input type="file" name="product_image" class="form-control" accept="image/*" style="color: #000000; font-weight: 700;">
      </div>

      <button type="submit" class="btn btn-primary btn-block" style="padding: 0.8rem; margin-top: 1rem; color: #000000 !important; font-weight: 900;">
        <i class="fa-solid fa-floppy-disk"></i> Update Product Details
      </button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
