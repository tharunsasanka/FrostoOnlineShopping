<?php
require 'includes/db.php';
include 'nav.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';

if ($category) {
  $categories = [$category];
} else {
  $result = mysqli_query($conn, "SELECT DISTINCT category FROM products");
  $categories = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row['category'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Products | Mobile Palace</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>


  <div class="homepage-background">
    <main>
      <section class="product-category-list">
        <?php foreach ($categories as $cat): ?>
          <div class="product-category">
            <h2><?php echo htmlspecialchars($cat); ?></h2>
            <div class="product-grid">
              <?php
              $stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
              $stmt->bind_param("s", $cat);
              $stmt->execute();
              $products = $stmt->get_result();

              if (mysqli_num_rows($products) === 0) {
                echo "<p>No products available in this category.</p>";
              }

              while ($row = mysqli_fetch_assoc($products)): ?>
                <div class="product-card">
                  <img src="images/<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                  <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                  <p>Rs. <?php echo $row['price']; ?></p>
                  <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn">View</a>
                </div>
              <?php endwhile; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </section>
    </main>
  </div>
</body>
</html>
