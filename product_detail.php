<?php
require 'includes/db.php';
include 'nav.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
$product = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo htmlspecialchars($product['name']); ?> | Frosto</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="product-detail-wrapper">
    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
    <img src="images/<?php echo htmlspecialchars($product['image_path']); ?>" alt="">
    <p>Category: <?php echo htmlspecialchars($product['category']); ?></p>
    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
    <p>Price: <strong>Rs. <?php echo $product['price']; ?></strong></p>

    <form action="cart.php" method="POST">
<form action="cart.php" method="POST">
  <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
  <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
  <input type="hidden" name="product_price" value="<?= $product['price'] ?>">
  <input type="number" name="quantity" value="1" min="1">
  <button type="submit" name="add_to_cart">Add to Cart</button>
</form>

    </form>
  </div>
</body>
</html>
