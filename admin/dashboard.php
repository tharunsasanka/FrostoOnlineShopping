<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: login.php");
require '../includes/db.php';


$filter = isset($_GET['category']) ? $_GET['category'] : '';
$query = $filter ? 
    "SELECT * FROM products WHERE category = '$filter'" :
    "SELECT * FROM products";
$products = mysqli_query($conn, $query);


$categories = mysqli_query($conn, "SELECT DISTINCT category FROM products");


$total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM products"));
$lowStock = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM products WHERE stock < 5"));
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<div class="dashboard-container">
  <div class="dashboard-header">
    <h2>Admin Dashboard</h2>
    <a href="add_product.php" class="btn">Add New Product</a>
    <a href="manage_categories.php" class="btn">Manage Categories</a>
  </div>

  <div class="dashboard-stats">
    <p>Total Products: <?php echo $total; ?></p>
    <p>Low Stock: <?php echo $lowStock; ?></p>
  </div>

  <form method="GET" class="filter-form">
    <label>Filter by Category:</label>
    <select name="category" onchange="this.form.submit()">
      <option value="">All</option>
      <?php while($cat = mysqli_fetch_assoc($categories)) { ?>
        <option value="<?php echo $cat['category']; ?>" <?php if ($cat['category'] == $filter) echo 'selected'; ?>>
          <?php echo $cat['category']; ?>
        </option>
      <?php } ?>
    </select>
  </form>

  <table class="product-table">
    <thead>
      <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Category</th>
        <th>Price (Rs.)</th>
        <th>Stock</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = mysqli_fetch_assoc($products)) { ?>
        <tr>
          <td><img src="../uploads/<?php echo $row['image_path']; ?>" alt="Product"></td>
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['category']; ?></td>
          <td><?php echo $row['price']; ?></td>
          <td><?php echo $row['stock']; ?></td>
          <td class="actions">
            <a href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a> |
            <a href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this product?')">Delete</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

</body>
</html>
