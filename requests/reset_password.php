<?php
require '../config.php';
$error = '';
$success = false;

// Pata token kutoka URL
$token = $_GET['token'] ?? '';

if (!$token) {
    die('Token invalid or missing!');
}

// Angalia token kwenye database na kama bado haijapita
$stmt = $mysqli->prepare("SELECT id, username, reset_expires FROM users WHERE reset_token=? LIMIT 1");
$stmt->bind_param('s', $token);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

if (!$user || strtotime($user['reset_expires']) < time()) {
    die('Token invalid or expired!');
}

// Kama POST, update password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    if ($password !== $confirm) {
        $error = 'Passwords do not match!';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters!';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt2 = $mysqli->prepare("UPDATE users SET password=?, reset_token=NULL, reset_expires=NULL WHERE id=?");
        $stmt2->bind_param('si', $hash, $user['id']);
        if ($stmt2->execute()) {
            $success = true;
        } else {
            $error = 'Something went wrong. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
<style>
body {margin:0;padding:0;font-family:Arial,sans-serif;background:#dceeff;display:flex;justify-content:center;align-items:center;height:100vh;}
.container {text-align:center;}
.login-form {background:white;padding:30px;border-radius:12px;width:320px;box-shadow:0 0 20px rgba(0,0,0,0.15);}
.login-form h3 {margin-bottom:20px;color:#1a1a1a;}
.input-container {position:relative;width:100%;margin:10px 0;}
.input-container input {width:90%;padding:10px 40px 10px 10px;border:1px solid #ccc;border-radius:6px;}
.toggle-eye {position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;font-size:18px;color:#555;}
.login-form button {width:100%;padding:10px;background-color:#163c66;color:white;border:none;border-radius:6px;font-weight:bold;cursor:pointer;}
.login-form button:hover {background-color:#1e90ff;}
.error-message {color:red;margin-top:10px;}
.success-box {background:#d4edda;color:#155724;padding:15px;margin-top:15px;border-radius:8px;box-shadow:0 0 10px rgba(0,0,0,0.1);}
.success-box a {display:inline-block;margin-top:10px;padding:8px 15px;background:#163c66;color:white;border-radius:6px;text-decoration:none;font-weight:bold;}
.success-box a:hover {background:#1e90ff;}
.footer {font-size:12px;color:#777;margin-top:15px;}
</style>
</head>
<body>
<div class="container">
  <form class="login-form" method="POST" <?php if($success) echo 'style="display:none;"'; ?>>
    <h3>Reset Password for <?= htmlspecialchars($user['username']) ?></h3>

    <div class="input-container">
        <input type="password" id="password" name="password" placeholder="New Password" required />
        <span class="toggle-eye" onclick="togglePassword('password')">&#128065;</span>
    </div>

    <div class="input-container">
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required />
        <span class="toggle-eye" onclick="togglePassword('confirm_password')">&#128065;</span>
    </div>

    <button type="submit">Reset Password</button>
    <?php if ($error) : ?>
        <p class="error-message"><?= $error ?></p>
    <?php endif; ?>
    <p class="footer">© 2025 Your University – All rights reserved</p>
  </form>

  <?php if($success): ?>
    <div class="success-box">
      Your password has been reset successfully!
      <br>
      <a href="../index.php">Back to Login</a>
    </div>
  <?php endif; ?>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
