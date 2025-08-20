<?php
require '../config.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);

    // Angalia kama user ipo
    $stmt = $mysqli->prepare("SELECT id, username FROM users WHERE username=? AND role!='admin' LIMIT 1");

    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        // generate temporary token
        $token = bin2hex(random_bytes(16));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // store token kwenye database
        $stmt2 = $mysqli->prepare("UPDATE users SET reset_token=?, reset_expires=? WHERE id=?");
        $stmt2->bind_param('ssi', $token, $expiry, $row['id']);
        $stmt2->execute();

        // Mockup link ya reset
        $resetLink = base_url("requests/reset_password.php?token=$token");
        $message = "Your password reset link has been generated: <a href='$resetLink'>$resetLink</a>";
    } else {
       $message = "Username does not exist or is an admin, you cannot reset the password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Forgot Password - Staff Permission System</title>
<style>
body {
  margin: 0;
  padding: 0;
  font-family: Arial, sans-serif;
  background: #dceeff;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.container {
  text-align: center;
}

h2 {
  color: #1a1a1a;
  margin-bottom: 10px;
}

.login-form {
  background: white;
  padding: 30px;
  border-radius: 12px;
  width: 320px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}

.login-form h3 {
  margin-bottom: 20px;
  color: #1a1a1a;
}

.login-form input[type="text"] {
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.login-form button {
  width: 100%;
  padding: 10px;
  background-color: #163c66;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
}

.login-form button:hover {
  background-color: #1e90ff;
}

.footer {
  font-size: 12px;
  color: #777;
  margin-top: 15px;
}

.links {
  margin: 15px 0;
  font-size: 14px;
  display: flex;
  justify-content: center;
  gap: 20px;
}

.links a {
  color: #163c66;
  text-decoration: none;
  transition: color 0.3s ease;
}

.links a:hover {
  color: #1e90ff;
}

.message {
  margin-top: 10px;
  color: green;
  font-size: 14px;
  word-wrap: break-word;
}
</style>
</head>
<body>
<div class="container">
  <h2>STAFF PERMISSION SYSTEM</h2>
  <form class="login-form" method="POST">
    <h3>Forgot Password</h3>
    <input type="text" name="username" placeholder="Enter your username" required />
    <button type="submit">Send Reset Link</button>
    <div class="links">
      <a href="../index.php">Back to Login</a>
    </div>
    <p class="footer">© 2025 Your University – All rights reserved</p>
    <?php if ($message) : ?>
      <p class="message"><?= $message ?></p>
    <?php endif; ?>
  </form>
</div>
</body>
</html>
