<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Profile | Frosto</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .profile-box {
      width: 400px;
      margin: 50px auto;
      background: #f9f9f9;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: center;
    }
    .profile-box h2 {
      margin-bottom: 20px;
    }
    .profile-box p {
      margin: 10px 0;
      font-size: 16px;
    }
    .btn {
      display: inline-block;
      padding: 10px 15px;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      margin-top: 20px;
    }
    .btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

  <?php include 'nav.php'; ?>

  <div class="profile-box">
    <h2>Hello, <?= htmlspecialchars($user['full_name']) ?> 👋</h2>
    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <a href="logout.php" class="btn">Logout</a>
  </div>

</body>
</html>
