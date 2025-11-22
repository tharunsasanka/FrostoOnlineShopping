<?php
require '../includes/db.php';
session_start();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name  = trim($_POST['name'] ?? '');
  $cat   = trim($_POST['category'] ?? '');
  $desc  = trim($_POST['description'] ?? '');
  $price = (float)($_POST['price'] ?? 0);
  $stock = (int)($_POST['stock'] ?? 0);

  if ($name === '' || $cat === '' || $desc === '') {
    $err = 'Please fill all required fields.';
  } elseif ($price < 0 || $stock < 0) {
    $err = 'Price/Stock cannot be negative.';
  } else {
    $imageFile = '';
    if (!empty($_FILES['image']['name'])) {
      $orig = $_FILES['image']['name'];
      $ext  = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
      $allowed = ['jpg','jpeg','png','gif','webp'];
      if (!in_array($ext, $allowed, true)) {
        $err = 'Invalid image type. Allowed: jpg, jpeg, png, gif, webp.';
      } else {
        $base = preg_replace('/[^a-zA-Z0-9_\-]/', '_', pathinfo($orig, PATHINFO_FILENAME));
        $imageFile = $base . '_' . time() . '.' . $ext;

        $dest = dirname(__DIR__) . '/images/' . $imageFile; 
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
          $err = 'Failed to upload image.';
        }
      }
    } else {
      $err = 'Image is required.';
    }

    if ($err === '') {
       $stmt = $conn->prepare(
  "INSERT INTO products (name, category, description, price, image_path, stock)
   VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("sssdsi", $name, $cat, $desc, $price, $imageFile, $stock);
$stmt->execute();


      header("Location: dashboard.php?added=1");
      exit;
    }
  }
}

$categories = mysqli_query($conn, "SELECT DISTINCT category FROM products ORDER BY category");
$fallbackCats = ['Study & Essentials', 'Snacks', 'Clothings'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Add Product</title>
  <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
  <a href="dashboard.php" class="btn" style="margin:20px;display:inline-block;">Main Page</a>

  <div class="form-container">
    <h2>Add New Product</h2>

    <?php if ($msg): ?><p style="color:green;"><?= $msg ?></p><?php endif; ?>
    <?php if ($err): ?><p style="color:#e74c3c;"><?= htmlspecialchars($err) ?></p><?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <input name="name" placeholder="Name" required><br>

      <select name="category" required>
        <option value="">Select Category</option>
        <?php if ($categories && mysqli_num_rows($categories) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($categories)) { ?>
            <option value="<?php echo htmlspecialchars($row['category']); ?>">
              <?php echo htmlspecialchars($row['category']); ?>
            </option>
          <?php } ?>
        <?php else: ?>
          <?php foreach ($fallbackCats as $c): ?>
            <option value="<?= htmlspecialchars($c) ?>"><?= htmlspecialchars($c) ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select><br>

      <textarea name="description" placeholder="Description" required></textarea><br>
      <input name="price" placeholder="Price" type="number" step="0.01" min="0" required><br>
      <input name="stock" placeholder="Stock" type="number" min="0" required><br>
      <input name="image" type="file" accept="image/*" required><br>

      <button type="submit" class="btn">Add Product</button>
    </form>
  </div>
</body>
</html>
