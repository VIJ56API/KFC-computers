<?php
require_once __DIR__ . '/includes/data_manager.php';
require_once __DIR__ . '/includes/header.php';

$components = getComponents();
?>

<div class="container">
  <div style="margin-bottom: 2rem; text-align: center;">
    <span style="color: var(--primary-cyan); font-weight: 700; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 2px;">
      🛠️ Interactive PC Customizer
    </span>
    <h1 style="font-size: 2.5rem;">CUSTOM <span class="gradient-text">PC BUILDER</span></h1>
    <p style="color: var(--text-muted); max-width: 700px; margin: 0.5rem auto 0;">
      Select components across 12 hardware categories. Our intelligent system checks socket, RAM, and PSU power compatibility in real-time.
    </p>
  </div>

  <!-- Category Selection Tabs -->
  <div id="builder-tabs" class="category-tabs"></div>

  <div class="builder-layout">
    <!-- Left Column: Component Cards Selection -->
    <div>
      <div id="builder-grid" class="product-grid">
        <!-- Rendered dynamically by builder.js -->
      </div>
    </div>

    <!-- Right Column: Sticky Your Build Summary Sidebar -->
    <aside>
      <div class="build-summary-card">
        <h3>YOUR BUILD</h3>

        <ul id="build-summary-list" class="build-item-list">
          <!-- Rendered dynamically by builder.js -->
        </ul>

        <!-- Compatibility Warning / OK Box -->
        <div id="compatibility-status" class="compatibility-box">
          Select components to check system compatibility.
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; font-size: 1.2rem;">
          <span style="color: var(--text-muted);">Total Price:</span>
          <strong id="build-total-price" style="font-family: var(--font-heading); color: var(--primary-cyan); font-size: 1.6rem;">₹0</strong>
        </div>

        <button id="confirm-build-btn" class="btn btn-primary btn-block" onclick="confirmCustomBuild()" disabled>
          <i class="fa-solid fa-check"></i> Confirm Build & Proceed
        </button>
      </div>
    </aside>
  </div>
</div>

<script>
  window.RAW_COMPONENTS = <?= json_encode($components) ?>;
</script>
<script src="assets/js/builder.js"></script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
