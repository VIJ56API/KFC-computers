<?php
require_once __DIR__ . '/../includes/data_manager.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$adminUser = $_SESSION['user'] ?? null;
if (!$adminUser || ($adminUser['role'] !== 'admin')) {
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'] ?? 0;
if ($id > 0) {
    deleteCatalogItem($id);
}

header('Location: products.php');
exit;
