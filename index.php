<?php
require 'includes/db.php';
include 'nav.php';

$products = mysqli_query($conn, "SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NSBM Shop</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
 
<div class="homepage-background">
  <header class="hero-section">
    <div class="hero-text">
      <h1>NEW <br><span>ARRIVALS</span></h1>
      <a href="products.php" class="btn">GO SHOPPING</a>
    </div>
    <img src="images/arr.png" alt="Model" class="hero-img">
  </header>
   </div>
<div style="text-align: center; font-size: 30px; font-weight: bold; color: #333; margin: 10px 0;">
  Categories
</div>

    <section class="categories">
  <?php
$categoryQuery = mysqli_query($conn, "SELECT * FROM categories");
while ($cat = mysqli_fetch_assoc($categoryQuery)) {
  ?>
  <div class="category-card">
    <img src="images/<?php echo $cat['image_path']; ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>">
    <h3><?php echo strtoupper(htmlspecialchars($cat['name'])); ?></h3>
    <a href="products.php?category=<?php echo urlencode($cat['name']); ?>">View Collection</a>
  </div>
<?php } ?>

</section>

  </section>

  <section class="featured">
    <h2><center>OUR PRODUCTS</center></h2>
    <div class="products-grid">
      
    </div>
  </section>

  <div class="products">
    <?php while($row = mysqli_fetch_assoc($products)) { ?>
      <div class="product-card">
        <img src="images/<?php echo $row['image_path']; ?>" alt="">
        <h3><?php echo $row['name']; ?></h3>
        <p><?php echo $row['category']; ?></p>
        <p>Rs. <?php echo $row['price']; ?></p>
        <a href="product_detail.php?id=<?php echo $row['id']; ?>">View</a>
      </div>
    <?php } ?>
  </div>

  <footer>
    <div class="footer-logos">
      <a href=""><img src="images/ig.png" alt="Instagram"></a>
      <a href=""><img src="images/fb.png" alt="Facebook"></a>
      <a href=""><img src="images/wa.png" alt="Whatsapp"></a>
    </div>
    <p>&copy; 2025 FROSTO | All Rights Reserved</p>
  </footer>

  <button id="scrollTopBtn" title="Go to top">🠉</button>
  <script src="script.js"></script>
  <script src="darkmode.js"></script>
</body>
</html>

</body>
</html>