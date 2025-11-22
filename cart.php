<?php
session_start();
require 'includes/db.php'; 

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

if (isset($_POST['add_to_cart'])) {
  $id   = (int)($_POST['product_id'] ?? 0);
  $name = trim($_POST['product_name'] ?? '');
  $price = $_POST['product_price'] ?? '';
  $qty   = max(1, (int)($_POST['quantity'] ?? 1));

  if ($price !== '') {
    $price = (float)preg_replace('/[^\d.]/', '', (string)$price);
  }

  if ($id > 0 && ($name === '' || $price === '' || $price <= 0)) {
    $res = $conn->prepare("SELECT id, name, price FROM products WHERE id = ?");
    $res->bind_param("i", $id);
    $res->execute();
    $p = $res->get_result()->fetch_assoc();
    if ($p) {
      if ($name === '')  $name  = $p['name'];
      if ($price <= 0)   $price = (float)$p['price'];
    }
  }

  if ($id > 0 && $name !== '' && $price >= 0) {
    if (!isset($_SESSION['cart'][$id])) {
      $_SESSION['cart'][$id] = [
        'id' => $id,
        'name' => $name,
        'price' => (float)$price,
        'quantity' => $qty
      ];
    } else {
      $_SESSION['cart'][$id]['quantity'] += $qty;
    }
  }

  header('Location: cartmain.php');
  exit;
}

if (isset($_POST['ajax']) && $_POST['ajax'] === '1') {
  $id  = (int)($_POST['id'] ?? 0);
  $qty = max(1, (int)($_POST['qty'] ?? 1));

  if ($id > 0 && isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['quantity'] = $qty;

    $itemTotal = $_SESSION['cart'][$id]['price'] * $_SESSION['cart'][$id]['quantity'];
    $grandTotal = 0;
    foreach ($_SESSION['cart'] as $it) $grandTotal += ($it['price'] * $it['quantity']);

    header('Content-Type: application/json');
    echo json_encode(['ok'=>true,'itemTotal'=>$itemTotal,'grandTotal'=>$grandTotal]);
    exit;
  }

  header('Content-Type: application/json');
  echo json_encode(['ok'=>false]);
  exit;
}

if (isset($_GET['remove'])) {
  $removeId = (int)$_GET['remove'];
  unset($_SESSION['cart'][$removeId]);
  header('Location: cartmain.php');
  exit;
}
