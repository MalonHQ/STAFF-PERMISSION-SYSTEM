<?php
require 'config.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $mysqli->prepare("SELECT id, username, password, role, full_name FROM users WHERE username=? LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        // simple plain-text check kwa sasa
        if ($password === $row['password']) {
            $_SESSION['user'] = [
                'id' => $row['id'],
                'username' => $row['username'],
                'role' => $row['role'],
                'full_name' => $row['full_name']
            ];

            // redirect based on role
            switch ($row['role']) {
                case 'admin': header('Location: dashboard/admin.php'); break;
                case 'staff': header('Location: dashboard/staff.php'); break;
                case 'user': header('Location: dashboard/user.php'); break;
            }
            exit;
        } else {
            $error = 'Incorrect password!';
        }
    } else {
        $error = 'Username not found!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Staff Permission System Login</title>
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

    .logo-container {
      text-align: center;
    }

    h2 {
      color: #1a1a1a;
      margin-bottom: 10px;
    }

    .logo {
      width: 70px;
      height: 66px;
      margin: 10px auto;
      background-size: contain;
      border-radius: 50%;
      background-color: #fff;
      padding: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      margin-bottom: -40px;
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

    .login-form input[type="text"],
    .login-form input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .login-form label {
      font-size: 14px;
      display: block;
      margin-bottom: 15px;
      color: #333;
      text-align: left;
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

    .error-message {
      color: red;
      margin-top: 10px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>STAFF PERMISSION SYSTEM</h2>
    <div class="logo-container">
    </div>
    <form class="login-form" method="POST">
      <h3>LOGIN TO YOUR ACCOUNT</h3>
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" id="password" name="password" placeholder="Password" required />
      <label><input type="checkbox" onclick="togglePassword()"> Show Password</label>
      <button type="submit">LOGIN</button>
      <!-- <div class="links">
        <a href="#">Forgot Password?</a> | <a href="#">Register</a>
      </div> -->
      <p class="footer">© 2025 Your University – All rights reserved</p>
      <?php if ($error) : ?>
        <p class="error-message"><?= $error ?></p>
      <?php endif; ?>
    </form>
  </div>

  <script>
    function togglePassword() {
      const pwd = document.getElementById("password");
      pwd.type = pwd.type === "password" ? "text" : "password";
    }
  </script>
</body>

</html>
