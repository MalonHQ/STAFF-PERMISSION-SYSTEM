<?php
require '../config.php';
if (!is_logged_in()) { header('Location: ../index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']); // jina limeongezwa
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $reason = $_POST['reason'];
    $user_id = $_SESSION['user']['id'];

    // Hakikisha database ina column full_name
    $stmt = $mysqli->prepare("INSERT INTO permissions (user_id, full_name, start_date, end_date, reason) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('issss', $user_id, $full_name, $start, $end, $reason);
    $stmt->execute();

    header('Location: ../dashboard/' . $_SESSION['user']['role'] . '.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Permission Request</title>
<style>
  body {
    margin:0;
    padding:0;
    font-family: Arial, sans-serif;
    background: #dceeff;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
  }
  .form-container {
    background:white;
    padding:25px 30px;
    border-radius:12px;
    box-shadow:0 0 15px rgba(0,0,0,0.1);
    width:300px;
  }
  .form-container h2 {
    text-align:center;
    color:#163c66;
    margin-bottom:15px;
  }
  .form-container label {
    display:block;
    margin:8px 0 5px;
    font-size:14px;
    color:#333;
  }
  .form-container input[type="text"],
  .form-container input[type="date"],
  .form-container textarea {
    width:100%;
    padding:8px;
    border:1px solid #ccc;
    border-radius:6px;
    margin-bottom:10px;
    font-size:14px;
  }
  .form-container textarea { resize:vertical; }
  .form-container button {
    width:100%;
    padding:10px;
    background-color:#163c66;
    color:white;
    border:none;
    border-radius:6px;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
  }
  .form-container button:hover { background-color:#1e90ff; }
</style>
</head>
<body>
<div class="form-container">
  <h2>New Permission Request</h2>
  <form method="POST">
    <label>Full Name:</label>
    <input type="text" name="full_name" placeholder="Enter your full name" required>

    <label>Start Date:</label>
    <input type="date" name="start_date" required>
    
    <label>End Date:</label>
    <input type="date" name="end_date" required>
    
    <label>Reason:</label>
    <textarea name="reason" rows="4" required></textarea>
    
    <button type="submit">Submit Request</button>
  </form>
</div>
</body>
</html>
