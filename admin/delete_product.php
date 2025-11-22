<?php
require '../includes/db.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM products WHERE id=$id");
header("Location: dashboard.php");
?>

<?php
$conn = mysqli_connect("localhost", "root", "", "nsbm_shop");
?>
