<?php
// register.php
session_start();
require __DIR__ . '/includes/db.php';

require __DIR__ . '/includes/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/includes/PHPMailer/src/SMTP.php';
require __DIR__ . '/includes/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$msg = '';
$isError = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username   = trim($_POST['username'] ?? '');
  $email      = trim($_POST['email'] ?? '');
  $fullName   = trim($_POST['full_name'] ?? '');
  $password   = $_POST['password'] ?? '';
  $role       = 'user'; // only users via this form
  $token      = bin2hex(random_bytes(32)); // 64 chars

  if ($username === '' || $email === '' || $fullName === '' || $password === '') {
    $msg = 'Please fill in all fields.'; $isError = true;
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $msg = 'Please enter a valid email address.'; $isError = true;
  } else {
    // Check duplicates
    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $msg = 'Username or Email already exists.'; $isError = true;
    } else {
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);

      // Insert (note: is_verified column)
      $stmt = $conn->prepare("
        INSERT INTO users (username, email, password, full_name, role, verification_token, is_verified)
        VALUES (?, ?, ?, ?, ?, ?, 0)
      ");
      $stmt->bind_param("ssssss", $username, $email, $passwordHash, $fullName, $role, $token);
      $stmt->execute();

      // Live verify link
      $verifyLink = 'https://www.frosto.shop/verify.php?email=' . urlencode($email) . '&token=' . urlencode($token);

      // Send email
      $mail = new PHPMailer(true);
      try {
        // $mail->SMTPDebug = 2; // debug only
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'contact.teamfrosto@gmail.com';
        $mail->Password   = 'pqraphdrztsymfun'; // Gmail App Password (NO SPACES)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('contact.teamfrosto@gmail.com', 'Frosto');
        $mail->addAddress($email, $fullName);

        $mail->isHTML(true);
        $mail->Subject = 'Verify your Frosto account';
        $mail->Body = "
          Hi " . htmlspecialchars($fullName) . ",<br><br>
          Please verify your account by clicking the link below:<br><br>
          <a href=\"$verifyLink\">$verifyLink</a><br><br>
          If you didn't request this, you can ignore this email.<br><br>
          — Frosto Team
        ";
        $mail->AltBody = "Hi $fullName,\n\nVerify your account:\n$verifyLink\n\n— Frosto Team";

        $mail->send();
        $msg = "✅ Registered! A verification email was sent to <strong>" . htmlspecialchars($email) . "</strong>.";
      } catch (Exception $e) {
        $isError = true;
        $msg = "❌ Registration succeeded but email could not be sent. Error: " . htmlspecialchars($mail->ErrorInfo);
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register | Frosto</title>
  <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
  <div class="form-container">
    <h2>Create your account</h2>
    <?php if ($msg): ?>
      <p class="<?= $isError ? 'err' : 'ok' ?>" style="text-align:center;">
        <?= $msg ?>
      </p>
    <?php endif; ?>

    <form method="POST" novalidate>
      <input type="text" name="full_name" placeholder="Full Name" required value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
      <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
      <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a>.</p>
  </div>
</body>
</html>
