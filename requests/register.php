<?php
require_once __DIR__ . '/../config.php';

$errors = [];
$success_message = ''; // variable ya success message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username   = trim($_POST['username'] ?? '');
    $password   = $_POST['password'] ?? '';
    $confirm    = $_POST['confirm_password'] ?? '';
    $full_name  = '';
    $role       = $_POST['role'] ?? '';

    // Validation
    if ($username === '' || strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters.';
    }
    if (strlen($password) < 5) {
        $errors[] = 'Password must be at least 5 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }
    if ($role === '') {
        $errors[] = 'Please choose your role.';
    }

    // Check duplicate username
    if (!$errors) {
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Username already exists.";
        }
        $stmt->close();
    }

    // Insert if no errors
    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (username, password, role, full_name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hash, $role, $full_name);

        if ($stmt->execute()) {
            $success_message = "Account created successfully! Please login below.";
            $username = '';
            $role = '';
        } else {
            $errors[] = "Failed to register user.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Staff Permission System</title>
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

    .form-box {
      background: white;
      padding: 30px;
      border-radius: 12px;
      width: 340px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
      margin-top: 20px;
    }

    .form-box h3 {
      margin-bottom: 20px;
      color: #1a1a1a;
    }

    .form-box input[type="text"],
    .form-box input[type="password"],
    .form-box select {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
    }

    .form-box button {
      width: 100%;
      padding: 10px;
      background-color: #163c66;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }

    .form-box button:hover {
      background-color: #1e90ff;
    }

    .links {
      margin: 15px 0;
      font-size: 14px;
    }

    .links a {
      color: #163c66;
      text-decoration: none;
    }

    .links a:hover {
      color: #1e90ff;
    }

    .error-message {
      color: red;
      margin: 10px 0;
      font-size: 14px;
      text-align: left;
    }

    .success-message {
      color: green;
      margin: 10px 0;
      font-size: 14px;
      text-align: left;
      font-weight: bold;
    }

    .footer {
      font-size: 12px;
      color: #777;
      margin-top: 15px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>STAFF PERMISSION SYSTEM</h2>
    <form class="form-box" method="post" action="register.php">
      <h3>CREATE ACCOUNT</h3>

      <!-- Success message -->
      <?php if ($success_message): ?>
        <div class="success-message">
          <?= htmlspecialchars($success_message) ?>
        </div>
      <?php endif; ?>

      <!-- Error messages -->
      <?php if ($errors): ?>
        <div class="error-message">
          <ul>
            <?php foreach ($errors as $err): ?>
              <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <!-- Username -->
      <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($username ?? '') ?>" required />

      <!-- Role dropdown -->
      <select name="role" required>
        <option value="" disabled <?= empty($role) ? 'selected' : '' ?>>Choose if you are User or Staff</option>
        <option value="user" <?= ($role ?? '') === 'user' ? 'selected' : '' ?>>User</option>
        <option value="staff" <?= ($role ?? '') === 'staff' ? 'selected' : '' ?>>Staff</option>
      </select>

      <!-- Password -->
      <input type="password" name="password" placeholder="Password" required />
      <input type="password" name="confirm_password" placeholder="Confirm Password" required />

      <button type="submit">REGISTER</button>

      <div class="links">
        Already have an account? <a href="http://localhost/STAFF-PERMISSION-SYSTEM/index.php">Login</a>
      </div>
      <p class="footer">© 2025 Your University – All rights reserved</p>
    </form>
  </div>
</body>
</html>
