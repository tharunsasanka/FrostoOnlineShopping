<?php
// verify.php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require __DIR__ . '/includes/db.php';

$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';

function out($msg){ echo $msg; exit; }

if ($email === '' || $token === '') out('Missing email or token.');

$email = trim($email);
$token = trim($token);

// Look up by email
$chk = $conn->prepare("SELECT id, is_verified, verification_token FROM users WHERE email = ?");
$chk->bind_param("s", $email);
$chk->execute();
$user = $chk->get_result()->fetch_assoc();

if (!$user) out('❌ Invalid email.');
if ((int)$user['is_verified'] === 1) out("✅ Already verified. <a href='login.php'>Login</a>");
if (!hash_equals((string)$user['verification_token'], (string)$token)) {
  out('❌ Invalid or expired verification link.');
}

// Mark verified
$upd = $conn->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
$upd->bind_param("i", $user['id']);
$upd->execute();

out("✅ Email verified successfully. <a href='login.php'>Login here</a>");
