<?php
// Product & Component JSON Data Manager - KFC Computers

define('PRODUCTS_FILE', __DIR__ . '/../data/products.json');
define('COMPONENTS_FILE', __DIR__ . '/../data/components.json');
define('UPLOAD_DIR', __DIR__ . '/../assets/images/products/');

// Load Ready-Made PCs
function getProducts() {
    if (!file_exists(PRODUCTS_FILE)) return [];
    $data = json_decode(file_get_contents(PRODUCTS_FILE), true);
    return is_array($data) ? $data : [];
}

// Load Custom PC Components
function getComponents() {
    if (!file_exists(COMPONENTS_FILE)) return [];
    $data = json_decode(file_get_contents(COMPONENTS_FILE), true);
    return is_array($data) ? $data : [];
}

// Get all items combined
function getAllCatalogItems() {
    return array_merge(getProducts(), getComponents());
}

// Find item by ID (searches products then components)
function getItemById($id) {
    $id = (int)$id;
    foreach (getProducts() as $p) {
        if ($p['id'] === $id) {
            $p['type'] = 'product';
            return $p;
        }
    }
    foreach (getComponents() as $c) {
        if ($c['id'] === $id) {
            $c['type'] = 'component';
            return $c;
        }
    }
    return null;
}

// Save Products JSON
function saveProducts($products) {
    file_put_contents(PRODUCTS_FILE, json_encode(array_values($products), JSON_PRETTY_PRINT));
}

// Save Components JSON
function saveComponents($components) {
    file_put_contents(COMPONENTS_FILE, json_encode(array_values($components), JSON_PRETTY_PRINT));
}

// Get Next Available ID
function getNextId() {
    $all = getAllCatalogItems();
    $max = 0;
    foreach ($all as $item) {
        if ($item['id'] > $max) $max = $item['id'];
    }
    return $max + 1;
}

// Add New Product or Component
function addCatalogItem($data, $fileInput = null) {
    $id = getNextId();
    $imagePath = 'assets/images/products/starter-gaming-pc.svg'; // fallback default

    if ($fileInput && isset($fileInput['name']) && $fileInput['error'] === UPLOAD_ERR_OK) {
        $uploaded = handleImageUpload($fileInput);
        if ($uploaded) {
            $imagePath = $uploaded;
        }
    } elseif (!empty($data['image'])) {
        $imagePath = $data['image'];
    }

    $isReadyMade = ($data['category'] === 'Ready-Made PC');

    if ($isReadyMade) {
        $products = getProducts();
        $newItem = [
            'id' => $id,
            'name' => trim($data['name']),
            'category' => 'Ready-Made PC',
            'brand' => !empty($data['brand']) ? trim($data['brand']) : 'KFC Computers',
            'price' => (float)$data['price'],
            'image' => $imagePath,
            'short_description' => trim($data['short_description'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'specifications' => trim($data['specifications'] ?? ''),
            'stock' => (int)($data['stock'] ?? 10)
        ];
        $products[] = $newItem;
        saveProducts($products);
        return $newItem;
    } else {
        $components = getComponents();
        $compat = [];
        if (!empty($data['socket'])) $compat['socket'] = trim($data['socket']);
        if (!empty($data['ram_type'])) $compat['ram_type'] = trim($data['ram_type']);
        if (!empty($data['wattage'])) $compat['wattage'] = (int)$data['wattage'];
        if (!empty($data['psu_wattage'])) $compat['psu_wattage'] = (int)$data['psu_wattage'];

        $newItem = [
            'id' => $id,
            'name' => trim($data['name']),
            'category' => trim($data['category']),
            'brand' => !empty($data['brand']) ? trim($data['brand']) : 'KFC Computers',
            'price' => (float)$data['price'],
            'image' => $imagePath,
            'specifications' => trim($data['specifications'] ?? ''),
            'compatibility' => $compat,
            'stock' => (int)($data['stock'] ?? 10)
        ];
        $components[] = $newItem;
        saveComponents($components);
        return $newItem;
    }
}

// Update Existing Product or Component
function updateCatalogItem($id, $data, $fileInput = null) {
    $id = (int)$id;
    $existing = getItemById($id);
    if (!$existing) return false;

    $imagePath = $existing['image'];
    if ($fileInput && isset($fileInput['name']) && $fileInput['error'] === UPLOAD_ERR_OK) {
        $uploaded = handleImageUpload($fileInput);
        if ($uploaded) {
            $imagePath = $uploaded;
        }
    }

    $category = trim($data['category']);
    $isReadyMade = ($category === 'Ready-Made PC');

    if ($existing['type'] === 'product') {
        $products = getProducts();
        foreach ($products as &$p) {
            if ($p['id'] === $id) {
                $p['name'] = trim($data['name']);
                $p['category'] = $category;
                $p['brand'] = trim($data['brand']);
                $p['price'] = (float)$data['price'];
                $p['stock'] = (int)$data['stock'];
                $p['short_description'] = trim($data['short_description'] ?? '');
                $p['description'] = trim($data['description'] ?? '');
                $p['specifications'] = trim($data['specifications'] ?? '');
                $p['image'] = $imagePath;
                break;
            }
        }
        saveProducts($products);
        return true;
    } else {
        $components = getComponents();
        foreach ($components as &$c) {
            if ($c['id'] === $id) {
                $c['name'] = trim($data['name']);
                $c['category'] = $category;
                $c['brand'] = trim($data['brand']);
                $c['price'] = (float)$data['price'];
                $c['stock'] = (int)$data['stock'];
                $c['specifications'] = trim($data['specifications'] ?? '');
                $c['image'] = $imagePath;
                
                $compat = $c['compatibility'] ?? [];
                if (isset($data['socket'])) $compat['socket'] = trim($data['socket']);
                if (isset($data['ram_type'])) $compat['ram_type'] = trim($data['ram_type']);
                if (isset($data['wattage'])) $compat['wattage'] = (int)$data['wattage'];
                if (isset($data['psu_wattage'])) $compat['psu_wattage'] = (int)$data['psu_wattage'];
                $c['compatibility'] = $compat;
                break;
            }
        }
        saveComponents($components);
        return true;
    }
}

// Delete Item by ID
function deleteCatalogItem($id) {
    $id = (int)$id;
    $products = getProducts();
    $filteredProducts = array_filter($products, function($p) use ($id) {
        return $p['id'] !== $id;
    });
    if (count($filteredProducts) !== count($products)) {
        saveProducts($filteredProducts);
        return true;
    }

    $components = getComponents();
    $filteredComponents = array_filter($components, function($c) use ($id) {
        return $c['id'] !== $id;
    });
    if (count($filteredComponents) !== count($components)) {
        saveComponents($filteredComponents);
        return true;
    }

    return false;
}

// Secure Product Image Upload
function handleImageUpload($file) {
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
    if (!in_array($ext, $allowed)) {
        return false;
    }

    // Generate safe unique filename
    $filename = 'product_' . time() . '_' . rand(100, 999) . '.' . $ext;
    $target = UPLOAD_DIR . $filename;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        return 'assets/images/products/' . $filename;
    }
    return false;
}

// Filter and Search Products/Components
function filterCatalog($query = '', $category = '', $minPrice = 0, $maxPrice = 0, $brand = '', $sort = 'default') {
    $items = getAllCatalogItems();

    if (!empty($query)) {
        $q = strtolower(trim($query));
        $items = array_filter($items, function($item) use ($q) {
            $name = strtolower($item['name']);
            $desc = strtolower($item['description'] ?? '');
            $specs = strtolower($item['specifications'] ?? '');
            $cat = strtolower($item['category']);
            $brandName = strtolower($item['brand'] ?? '');
            return (strpos($name, $q) !== false || strpos($desc, $q) !== false || strpos($specs, $q) !== false || strpos($cat, $q) !== false || strpos($brandName, $q) !== false);
        });
    }

    if (!empty($category)) {
        $items = array_filter($items, function($item) use ($category) {
            return strcasecmp($item['category'], $category) === 0;
        });
    }

    if (!empty($brand)) {
        $items = array_filter($items, function($item) use ($brand) {
            return strcasecmp($item['brand'] ?? '', $brand) === 0;
        });
    }

    if ($minPrice > 0) {
        $items = array_filter($items, function($item) use ($minPrice) {
            return $item['price'] >= $minPrice;
        });
    }

    if ($maxPrice > 0) {
        $items = array_filter($items, function($item) use ($maxPrice) {
            return $item['price'] <= $maxPrice;
        });
    }

    $items = array_values($items);

    if ($sort === 'price_asc') {
        usort($items, function($a, $b) { return $a['price'] <=> $b['price']; });
    } elseif ($sort === 'price_desc') {
        usort($items, function($a, $b) { return $b['price'] <=> $a['price']; });
    } elseif ($sort === 'newest') {
        usort($items, function($a, $b) { return $b['id'] <=> $a['id']; });
    }

    return $items;
}
