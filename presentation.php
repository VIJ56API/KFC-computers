<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KFC Computers | Presentation Deck</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: #f1f5f9;
      display: flex;
      flex-direction: column;
      height: 100vh;
      overflow: hidden;
    }
    .slide-deck {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      position: relative;
    }
    .slide {
      background: #ffffff;
      border: 3px solid #cbd5e1;
      border-radius: 16px;
      width: 100%;
      max-width: 1100px;
      height: 600px;
      padding: 3rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      display: none;
      flex-direction: column;
      justify-content: space-between;
      animation: fadeIn 0.3s ease;
    }
    .slide.active {
      display: flex;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.98); }
      to { opacity: 1; transform: scale(1); }
    }
    .slide-tag {
      color: var(--primary-blue);
      font-weight: 800;
      font-size: 0.85rem;
      letter-spacing: 2px;
      text-transform: uppercase;
      margin-bottom: 0.5rem;
    }
    .slide-title {
      font-size: 2.2rem;
      color: #000000;
      font-weight: 900;
      margin-bottom: 1.5rem;
    }
    .slide-controls {
      background: #ffffff;
      border-top: 2px solid #cbd5e1;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
    }
    .grid-3 {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 1.2rem;
    }
    .card-box {
      background: #f8fafc;
      border: 2px solid #cbd5e1;
      border-radius: 10px;
      padding: 1.2rem;
    }
    .card-box h4 {
      color: var(--accent-gold);
      font-size: 1.1rem;
      font-weight: 800;
      margin-bottom: 0.4rem;
    }
    .card-box p {
      color: #000000;
      font-size: 0.9rem;
      font-weight: 600;
    }
  </style>
</head>
<body>

<header class="navbar">
  <div class="nav-container">
    <div class="logo">KFC <span>COMPUTERS</span> <span class="logo-badge">PRESENTATION</span></div>
    <div style="font-weight: 700; color: #000000;">Press Left / Right Arrow Keys to Navigate Slides</div>
    <a href="index.php" class="btn btn-outline btn-sm"><i class="fa-solid fa-store"></i> Open Website</a>
  </div>
</header>

<div class="slide-deck">
  <!-- Slide 1: Title -->
  <div class="slide active">
    <div>
      <div class="slide-tag">PROJECT PRESENTATION</div>
      <h1 class="slide-title" style="font-size: 3rem;">KFC COMPUTERS E-COMMERCE PLATFORM</h1>
      <p style="font-size: 1.2rem; color: #000000; font-weight: 600; margin-bottom: 2rem;">
        High-Performance Gaming PCs, 12-Category Custom Builder & File-Based Admin Management System
      </p>

      <div class="grid-3" style="margin-top: 2rem;">
        <div class="card-box">
          <h4>🛒 Pre-Loaded Catalog</h4>
          <p>8 Ready-Made Gaming PCs & 52 components in Indian Rupee (₹) pricing.</p>
        </div>
        <div class="card-box">
          <h4>🛠️ Custom PC Builder</h4>
          <p>Real-time socket, RAM, and PSU wattage compatibility checking engine.</p>
        </div>
        <div class="card-box">
          <h4>🔐 Admin Control Panel</h4>
          <p>No-code product CRUD, stock management, and secure image uploads.</p>
        </div>
      </div>
    </div>
    <div style="text-align: right; font-weight: 800; color: var(--primary-blue);">Slide 1 of 7</div>
  </div>

  <!-- Slide 2: Executive Summary -->
  <div class="slide">
    <div>
      <div class="slide-tag">EXECUTIVE SUMMARY</div>
      <h2 class="slide-title">System Overview & Objectives</h2>
      <div style="display: flex; flex-direction: column; gap: 1rem;">
        <div class="card-box">
          <h4 style="color: var(--primary-blue);">1. Complete E-Commerce Customer Flow</h4>
          <p>Browse Ready-Made PCs, filter components, view technical specifications, customize builds, add to cart, and place orders with 18% GST tax receipts.</p>
        </div>
        <div class="card-box">
          <h4 style="color: var(--primary-blue);">2. Intelligent Hardware Compatibility</h4>
          <p>Prevents common build errors by warning users if CPU socket != Motherboard socket, RAM type != slot type, or PSU wattage is below system draw.</p>
        </div>
        <div class="card-box">
          <h4 style="color: var(--primary-blue);">3. Dual File & Database Architecture</h4>
          <p>Products reside in `data/products.json` & `data/components.json` as requested. User credentials and order records reside in SQLite PDO database.</p>
        </div>
      </div>
    </div>
    <div style="text-align: right; font-weight: 800; color: var(--primary-blue);">Slide 2 of 7</div>
  </div>

  <!-- Slide 3: Ready-Made PCs -->
  <div class="slide">
    <div>
      <div class="slide-tag">PRODUCT CATALOG</div>
      <h2 class="slide-title">Pre-Configured Ready-Made PC Lineup</h2>
      <div class="grid-3">
        <div class="card-box">
          <h4>KFC Starter Gaming PC</h4>
          <div style="font-size: 1.3rem; font-weight: 900; color: var(--accent-gold);">₹45,999</div>
          <p>Ryzen 5 5600 • RTX 3050 6GB • 16GB DDR4 • 512GB SSD</p>
        </div>
        <div class="card-box">
          <h4>KFC Gaming Pro</h4>
          <div style="font-size: 1.3rem; font-weight: 900; color: var(--accent-gold);">₹69,999</div>
          <p>Ryzen 5 7600 • RTX 4060 8GB • 16GB DDR5 • 1TB SSD</p>
        </div>
        <div class="card-box">
          <h4>KFC Creator PC</h4>
          <div style="font-size: 1.3rem; font-weight: 900; color: var(--accent-gold);">₹89,999</div>
          <p>Core i7 14700K • RTX 4060 Ti 8GB • 32GB DDR5 • 240mm AIO</p>
        </div>
        <div class="card-box">
          <h4>KFC Ultimate Gaming PC</h4>
          <div style="font-size: 1.3rem; font-weight: 900; color: var(--accent-gold);">₹1,29,999</div>
          <p>Ryzen 7 7800X3D • RTX 4070 12GB • 32GB DDR5 • 2TB SSD</p>
        </div>
        <div class="card-box">
          <h4>KFC Student PC</h4>
          <div style="font-size: 1.3rem; font-weight: 900; color: var(--accent-gold);">₹32,999</div>
          <p>Core i3 14100 • Integrated UHD • 16GB RAM • 512GB SSD</p>
        </div>
        <div class="card-box">
          <h4>KFC Office PC</h4>
          <div style="font-size: 1.3rem; font-weight: 900; color: var(--accent-gold);">₹38,999</div>
          <p>Core i5 14400 • Integrated Graphics • 16GB RAM • 512GB SSD</p>
        </div>
      </div>
    </div>
    <div style="text-align: right; font-weight: 800; color: var(--primary-blue);">Slide 3 of 7</div>
  </div>

  <!-- Slide 4: PC Builder -->
  <div class="slide">
    <div>
      <div class="slide-tag">CUSTOM BUILDER</div>
      <h2 class="slide-title">12 Component Tabs & Real-Time Compatibility</h2>
      <div class="grid-2">
        <div class="card-box">
          <h4 style="color: var(--primary-blue);">12 Hardware Categories</h4>
          <p style="line-height: 1.8;">
            1. CPU (Ryzen & Intel)<br>
            2. Motherboard (AM4/AM5/LGA1700)<br>
            3. RAM (DDR4 / DDR5)<br>
            4. GPU (NVIDIA RTX & AMD RX)<br>
            5. SSD (NVMe Gen4)<br>
            6. HDD (Mass Storage)<br>
            7. PSU (550W - 850W)<br>
            8. Cabinet (Airflow & RGB)<br>
            9. CPU Cooler (Air & Liquid AIO)<br>
            10. Monitors (75Hz - 165Hz QHD)<br>
            11. Keyboards (Mechanical)<br>
            12. Gaming Mice
          </p>
        </div>
        <div class="card-box">
          <h4 style="color: var(--accent-gold);">Automated System Rules</h4>
          <p style="line-height: 1.8;">
            ✔ <strong>Socket Check:</strong> CPU AM4/AM5/LGA1700 vs Motherboard socket.<br><br>
            ✔ <strong>Memory Check:</strong> Motherboard RAM slots vs DDR4 / DDR5 RAM.<br><br>
            ✔ <strong>Power Check:</strong> Calculated total draw (CPU TDP + GPU TDP + 100W base) vs PSU capacity.<br><br>
            ✔ <strong>Your Build Sidebar:</strong> Live itemized total in Indian Rupees (₹).
          </p>
        </div>
      </div>
    </div>
    <div style="text-align: right; font-weight: 800; color: var(--primary-blue);">Slide 4 of 7</div>
  </div>

  <!-- Slide 5: Admin Panel -->
  <div class="slide">
    <div>
      <div class="slide-tag">ADMIN MANAGEMENT</div>
      <h2 class="slide-title">No-Code Product CRUD & Stock Control</h2>
      <div class="grid-2">
        <div class="card-box">
          <h4>Admin Capabilities</h4>
          <p style="line-height: 1.8;">
            • <strong>Product Dashboard:</strong> View catalog items with images, stock counts, categories, and prices.<br>
            • <strong>Add Product:</strong> Add Ready-Made PCs or components with category, brand, price, stock, specs, and socket parameters.<br>
            • <strong>Edit Product:</strong> Change prices, stock, specs, or upload new images.<br>
            • <strong>Delete Product:</strong> Safely delete items from JSON catalog.
          </p>
        </div>
        <div class="card-box">
          <h4>Secure File Uploads & Orders</h4>
          <p style="line-height: 1.8;">
            • <strong>Image Upload:</strong> Accepts `.jpg`, `.png`, `.webp`, `.svg` images saved in `assets/images/products/`.<br>
            • <strong>Customer Orders:</strong> Manage orders placed by customers, view shipping addresses, and print tax invoices.
          </p>
        </div>
      </div>
    </div>
    <div style="text-align: right; font-weight: 800; color: var(--primary-blue);">Slide 5 of 7</div>
  </div>

  <!-- Slide 6: UI & Visibility -->
  <div class="slide">
    <div>
      <div class="slide-tag">DESIGN SYSTEM</div>
      <h2 class="slide-title">Ultra-Clear High Visibility Light Theme</h2>
      <div class="grid-3">
        <div class="card-box">
          <h4>Pure White Surfaces</h4>
          <p>Off-white canvas background (`#f1f5f9`) with crisp pure white cards (`#ffffff`).</p>
        </div>
        <div class="card-box">
          <h4>Bold Black Text</h4>
          <p>High contrast solid black font (`#000000`) for maximum legibility and clarity.</p>
        </div>
        <div class="card-box">
          <h4>Distinct 2px Borders</h4>
          <p>Clear 2px slate borders (`#cbd5e1`) defining all cards, forms, and tables.</p>
        </div>
      </div>
    </div>
    <div style="text-align: right; font-weight: 800; color: var(--primary-blue);">Slide 6 of 7</div>
  </div>

  <!-- Slide 7: Verification & Login -->
  <div class="slide">
    <div>
      <div class="slide-tag">PROJECT VERIFICATION</div>
      <h2 class="slide-title">Verification Results & Credentials</h2>
      <div class="card-box" style="margin-bottom: 1.5rem;">
        <h4 style="color: var(--accent-green);">✓ 100% Automated Test Suite Passed</h4>
        <p style="line-height: 1.8;">
          8 Ready-Made PCs & 52 Components Loaded • Search & Filters Verified • Admin CRUD Verified • Database User & Order Invoice Generation Verified.
        </p>
      </div>

      <div class="grid-2">
        <div class="card-box">
          <h4>Website URL</h4>
          <p style="font-size: 1.1rem; font-weight: 800; color: var(--primary-blue);">http://localhost:8000</p>
        </div>
        <div class="card-box">
          <h4>Default Admin Login</h4>
          <p>Email: <strong>admin@litcomputers.in</strong><br>Password: <strong>admin123</strong></p>
        </div>
      </div>
    </div>
    <div style="text-align: right; font-weight: 800; color: var(--primary-blue);">Slide 7 of 7</div>
  </div>
</div>

<div class="slide-controls">
  <button class="btn btn-outline" onclick="prevSlide()"><i class="fa-solid fa-arrow-left"></i> Previous</button>
  <span id="slide-indicator" style="font-weight: 800; font-size: 1.1rem; color: #000000;">Slide 1 of 7</span>
  <button class="btn btn-primary" onclick="nextSlide()">Next <i class="fa-solid fa-arrow-right"></i></button>
</div>

<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.slide');

function showSlide(index) {
    if (index < 0) index = 0;
    if (index >= slides.length) index = slides.length - 1;
    currentSlide = index;

    slides.forEach((s, idx) => {
        s.classList.toggle('active', idx === currentSlide);
    });

    document.getElementById('slide-indicator').textContent = `Slide ${currentSlide + 1} of ${slides.length}`;
}

function nextSlide() { showSlide(currentSlide + 1); }
function prevSlide() { showSlide(currentSlide - 1); }

document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowRight' || e.key === 'PageDown') nextSlide();
    if (e.key === 'ArrowLeft' || e.key === 'PageUp') prevSlide();
});
</script>
</body>
</html>
