<?php
session_start();
require __DIR__ . '/includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($user = $result->fetch_assoc()) {
    if ((int)$user['is_verified'] !== 1) {
      $error = "Please verify your email before logging in.";
    } elseif (password_verify($password, $user['password'])) {
      $_SESSION['user'] = [
        'id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email'],
        'full_name' => $user['full_name'],
        'role' => $user['role']
      ];
      if ($user['role'] === 'admin') {
        $_SESSION['admin'] = true;
        header("Location: admin/dashboard.php");
      } else {
        header("Location: profile.php");
      }
      exit;
    } else {
      $error = "Incorrect password.";
    }
  } else {
    $error = "User not found.";
  }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Login</title><link rel="stylesheet" href="css/style.css"></head>
<body>
  <div class="form-container">
    <h2>Login</h2>
    <?php if ($error): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="POST">
      <input name="username" placeholder="Username" required>
      <input name="password" type="password" placeholder="Password" required>
      <button type="submit" class="btn">Login</button>
    </form>
  </div>
</body>
</html>
