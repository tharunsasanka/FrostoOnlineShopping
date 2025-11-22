<?php
session_start();

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
} else {
  $rebuilt = [];
  foreach ($_SESSION['cart'] as $k => $v) {
    $id   = isset($v['id']) ? (int)$v['id'] : (is_numeric($k) ? (int)$k : null);
    $name = isset($v['name']) ? (string)$v['name'] : '';
    $price = isset($v['price']) ? (float)$v['price'] : 0.0;
    $qty   = isset($v['quantity']) ? (int)$v['quantity'] : (isset($v['qty']) ? (int)$v['qty'] : 1);

    if ($id !== null && $id > 0) {
      if ($qty < 1) $qty = 1;
      $rebuilt[$id] = [
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'quantity' => $qty
      ];
    }
  }
  $_SESSION['cart'] = $rebuilt;
}

if (isset($_POST['ajax']) && $_POST['ajax'] === '1') {
  $id  = isset($_POST['id']) ? (int)$_POST['id'] : null;
  $qty = max(1, (int)($_POST['qty'] ?? 1));

  if ($id !== null && isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['quantity'] = $qty;

    $itemTotal = $_SESSION['cart'][$id]['price'] * $_SESSION['cart'][$id]['quantity'];
    $grandTotal = 0;
    foreach ($_SESSION['cart'] as $it) {
      $grandTotal += ($it['price'] * $it['quantity']);
    }

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
  header("Location: cartmain.php");
  exit;
}

$purchaseMsg = '';
if (isset($_POST['purchase_selected'])) {
  $selected = $_POST['selected'] ?? [];
  $selected = array_map('intval', $selected);
  $selected = array_filter($selected, fn($x) => $x > 0);

  if (empty($selected)) {
    $purchaseMsg = "<p style='color:red;text-align:center;'>No items selected for purchase.</p>";
  } else {
    $purchaseMsg = "<p style='color:green;text-align:center;'>✅ Purchase successful for items: " . implode(', ', $selected) . "</p>";
    foreach ($selected as $id) unset($_SESSION['cart'][$id]);
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Your Cart</title>
  <link rel="stylesheet" href="css/cart.css">
  <style>
    table{width:80%;margin:20px auto;border-collapse:collapse}
    th,td{border:1px solid #ddd;padding:12px;text-align:center}
    .btn{padding:10px 15px;margin:10px 5px;background:#007bff;color:#fff;border:none;border-radius:6px;cursor:pointer}
    .btn:hover{background:#0056b3}
    .qty-wrap{display:inline-flex;align-items:center;gap:6px}
    .qty-btn{width:34px;height:34px;border:none;border-radius:6px;background:#eee;cursor:pointer;font-size:18px;line-height:34px}
    .qty-btn:hover{background:#e2e2e2}
    .qty-input{width:60px;padding:7px 8px;border:1px solid #ccc;border-radius:6px;text-align:center}
    .remove-link{color:#e74c3c;text-decoration:none;font-weight:600}
    #grandTotal{font-weight:700}
  </style>
</head>
<body>
  <h2 style="text-align:center;">Your Shopping Cart</h2>

  <?php if (!empty($purchaseMsg)) echo $purchaseMsg; ?>

  <?php if (!empty($_SESSION['cart'])): ?>
    <form method="POST">
      <table>
        <tr>
          <th>Select</th>
          <th>Name</th>
          <th>Price (Rs.)</th>
          <th>Quantity</th>
          <th>Total (Rs.)</th>
          <th>Action</th>
        </tr>

        <?php
        $grand = 0;
        foreach ($_SESSION['cart'] as $item):
          $id = $item['id'] ?? null;
          $name = $item['name'] ?? '';
          $price = isset($item['price']) ? (float)$item['price'] : 0.0;
          $qty = isset($item['quantity']) ? (int)$item['quantity'] : 1;
          if (!$id) { continue; }

          $rowTotal = $price * $qty;
          $grand += $rowTotal;
        ?>
          <tr data-id="<?= $id ?>">
            <td><input type="checkbox" name="selected[]" value="<?= $id ?>"></td>
            <td><?= htmlspecialchars($name) ?></td>
            <td class="unit-price"><?= $price ?></td>
            <td>
              <div class="qty-wrap">
                <button type="button" class="qty-btn minus">−</button>
                <input class="qty-input" type="number" min="1" value="<?= $qty ?>">
                <button type="button" class="qty-btn plus">+</button>
              </div>
            </td>
            <td class="item-total"><?= $rowTotal ?></td>
            <td><a class="remove-link" href="cartmain.php?remove=<?= $id ?>">Remove</a></td>
          </tr>
        <?php endforeach; ?>

        <tr>
          <td colspan="4" style="text-align:right;"><strong>Grand Total:</strong></td>
          <td colspan="2" id="grandTotal">Rs. <?= $grand ?></td>
        </tr>
      </table>

      <div style="text-align:center; margin-top: 20px;">
        <button type="submit" name="purchase_selected" class="btn">Proceed to Payment</button>
      </div>
    </form>
  <?php else: ?>
    <p style="text-align:center;">Your cart is empty.</p>
  <?php endif; ?>

  <script>
    function sendUpdate(id, qty, row) {
      const body = new URLSearchParams();
      body.append('ajax','1'); body.append('id', id); body.append('qty', qty);
      fetch('cartmain.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body
      })
      .then(r => r.json())
      .then(data => {
        if (!data.ok) return;
        row.querySelector('.item-total').textContent = data.itemTotal;
        document.getElementById('grandTotal').textContent = "Rs. " + data.grandTotal;
      })
      .catch(() => {});
    }

    document.querySelectorAll('tr[data-id]').forEach(row => {
      const id = row.getAttribute('data-id');
      const minus = row.querySelector('.qty-btn.minus');
      const plus  = row.querySelector('.qty-btn.plus');
      const input = row.querySelector('.qty-input');

      function clamp() {
        let v = parseInt(input.value) || 1;
        if (v < 1) v = 1;
        input.value = v;
        return v;
      }

      minus.addEventListener('click', () => {
        input.value = Math.max(1, (parseInt(input.value)||1) - 1);
        sendUpdate(id, clamp(), row);
      });

      plus.addEventListener('click', () => {
        input.value = (parseInt(input.value)||1) + 1;
        sendUpdate(id, clamp(), row);
      });

      input.addEventListener('input', () => {
        sendUpdate(id, clamp(), row);
      });
    });
  </script>
</body>
</html>
