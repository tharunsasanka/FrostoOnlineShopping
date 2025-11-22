<?php
require '../includes/db.php';
$id = $_GET['id'];
$prod = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id=$id"));
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $cat = $_POST['category'];
  $desc = $_POST['description'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  mysqli_query($conn, "UPDATE products SET name='$name', category='$cat', description='$desc', price='$price', stock='$stock' WHERE id=$id");
  header("Location: dashboard.php");
}
?>
<form method="POST">
  <input name="name" value="<?php echo $prod['name']; ?>"><br>
  <input name="category" value="<?php echo $prod['category']; ?>"><br>
  <textarea name="description"><?php echo $prod['description']; ?></textarea><br>
  <input name="price" value="<?php echo $prod['price']; ?>"><br>
  <input name="stock" value="<?php echo $prod['stock']; ?>"><br>
  <button type="submit">Update</button>
</form>