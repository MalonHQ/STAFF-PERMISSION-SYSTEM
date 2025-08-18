<?php
require '../config.php';
if (!is_logged_in()) { header('Location: ../index.php'); exit; }
$user_id = $_SESSION['user']['id'];

// Tumia directly permissions.full_name
$res = $mysqli->query("SELECT * FROM permissions WHERE user_id=$user_id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard - Staff Permission System</title>
<style>
  * { margin:0; padding:0; box-sizing:border-box; font-family: Arial,sans-serif; }
  body { background:#f0f4f8; color:#333; min-height:100vh; padding:20px; }
  .container { width:95%; max-width:1000px; margin:0 auto; }
  header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
  header h2 { color:#163c66; }
  header a { text-decoration:none; background:#d9534f; color:white; padding:6px 12px; border-radius:5px; transition:0.3s; }
  header a:hover { background:#c9302c; }

  .new-request { display:inline-block; margin-bottom:15px; text-decoration:none; background:#163c66; color:white; padding:6px 12px; border-radius:5px; transition:0.3s; }
  .new-request:hover { background:#1e90ff; }

  table { width:100%; border-collapse:collapse; background:white; border-radius:8px; overflow:hidden; box-shadow:0 0 15px rgba(0,0,0,0.1); }
  th, td { padding:12px 15px; text-align:left; }
  th { background:#163c66; color:white; }
  tr:nth-child(even) { background:#f7f9fb; }
  tr:hover { background:#e6f0ff; }

  /* Status colors (text only) */
  td.status-pending { color:#ff9800; font-weight:bold; }
  td.status-approved { color:#28a745; font-weight:bold; }
  td.status-rejected { color:#dc3545; font-weight:bold; }

  @media(max-width:768px){
    table, thead, tbody, th, td, tr { display:block; }
    tr { margin-bottom:15px; }
    th { display:none; }
    td { padding-left:50%; position:relative; text-align:left; }
    td:before { position:absolute; left:15px; width:45%; padding-right:10px; white-space:nowrap; font-weight:bold; }
    td:nth-of-type(1):before { content:"Full Name"; }
    td:nth-of-type(2):before { content:"Start"; }
    td:nth-of-type(3):before { content:"End"; }
    td:nth-of-type(4):before { content:"Reason"; }
    td:nth-of-type(5):before { content:"Status"; }
  }
</style>
</head>
<body>
<div class="container">
  <header>
    <h2>User Dashboard</h2>
    <a href="../logout.php">Logout</a>
  </header>

  <a class="new-request" href="../requests/create_request.php">New Request</a>

  <h3>My Requests</h3>
  <table>
    <thead>
      <tr>
        <th>Full Name</th>
        <th>Start</th>
        <th>End</th>
        <th>Reason</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $res->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['full_name']) ?></td>
        <td><?= $row['start_date'] ?></td>
        <td><?= $row['end_date'] ?></td>
        <td><?= htmlspecialchars($row['reason']) ?></td>
        <td class="status-<?= strtolower($row['status']) ?>"><?= ucfirst($row['status']) ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
