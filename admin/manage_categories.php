<?php
session_start();
if (!isset($_SESSION['admin'])) header("Location: login.php");
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = trim($_POST['name']);
    $image = $_FILES['image']['name'];

    if ($name && $image) {
        $imagePath = 'categories/' . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], '../images/' . $imagePath);
        $stmt = $conn->prepare("INSERT INTO categories (name, image_path) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $imagePath);
        $stmt->execute();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_image'])) {
    $id = $_POST['id'];
    $newImage = $_FILES['new_image']['name'];

    if ($newImage) {
        $newPath = 'categories/' . basename($newImage);
        move_uploaded_file($_FILES['new_image']['tmp_name'], '../images/' . $newPath);
        $stmt = $conn->prepare("UPDATE categories SET image_path=? WHERE id=?");
        $stmt->bind_param("si", $newPath, $id);
        $stmt->execute();
    }
}

$categories = mysqli_query($conn, "SELECT * FROM categories");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Manage Categories</title>
  <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>
    <a href="dashboard.php" class="btn" style="margin:20px;display:inline-block;">Main Page</a>
<div class="dashboard-container">
  <h2>Manage Categories</h2>

  <form method="POST" enctype="multipart/form-data" class="form">
    <h3>Add New Category</h3>
    <input type="text" name="name" placeholder="Category Name" required>
    <input type="file" name="image" accept="image/*" required>
    <button type="submit" name="add">Add Category</button>
  </form>

  <h3>Existing Categories</h3>
  <table class="product-table">
    <thead>
      <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Update Image</th>
      </tr>
    </thead>
    <tbody>
      <?php while($cat = mysqli_fetch_assoc($categories)) { ?>
        <tr>
          <td><img src="../images/<?php echo $cat['image_path']; ?>" alt="" width="60"></td>
          <td><?php echo htmlspecialchars($cat['name']); ?></td>
          <td>
            <form method="POST" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
              <input type="file" name="new_image" accept="image/*" required>
              <button type="submit" name="update_image">Update</button>
            </form>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
</body>
</html>
