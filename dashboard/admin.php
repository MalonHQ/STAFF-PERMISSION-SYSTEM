<?php
require '../config.php';
if (!check_role('admin')) { header('Location: ../index.php'); exit; }

// Get all permissions, use full_name from permissions table
$res = $mysqli->query("SELECT * FROM permissions ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Staff Permission System</title>
<style>
  * { margin:0; padding:0; box-sizing:border-box; font-family: Arial, sans-serif; }
  body { background: #f0f4f8; color: #333; min-height: 100vh; }
  .container { width: 95%; max-width: 1200px; margin: 40px auto; }
  header { display:flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
  header h2 { color: #163c66; }
  header a { text-decoration:none; background:#d9534f; color:white; padding:8px 15px; border-radius:6px; transition:0.3s; }
  header a:hover { background:#c9302c; }
  table { width: 100%; border-collapse: collapse; background:white; border-radius: 8px; overflow: hidden; box-shadow:0 0 20px rgba(0,0,0,0.1); }
  th, td { padding: 12px 15px; text-align: left; }
  th { background:#163c66; color:white; }
  tr:nth-child(even) { background: #f7f9fb; }
  tr:hover { background: #e6f0ff; }
  td a { text-decoration:none; margin-right:5px; padding:4px 8px; border-radius:4px; color:white; }
  td a.approve { background:#28a745; }
  td a.approve:hover { background:#218838; }
  td a.reject { background:#dc3545; }
  td a.reject:hover { background:#c82333; }
  td.status-approved { color: #28a745; font-weight: bold; text-align:center; }
  td.status-rejected { color: #dc3545; font-weight: bold; text-align:center; }
  td.status-pending { color: #ff9800; font-weight: bold; text-align:center; }

  @media(max-width:768px){
    table, thead, tbody, th, td, tr { display:block; }
    tr { margin-bottom: 15px; }
    th { display:none; }
    td { padding-left: 50%; position: relative; text-align:left; }
    td:before {
      position:absolute; left:15px; width:45%; padding-right:10px; white-space:nowrap; font-weight:bold;
    }
    td:nth-of-type(1):before { content: "Full Name"; }
    td:nth-of-type(2):before { content: "Start"; }
    td:nth-of-type(3):before { content: "End"; }
    td:nth-of-type(4):before { content: "Reason"; }
    td:nth-of-type(5):before { content: "Status"; }
    td:nth-of-type(6):before { content: "Action"; }
  }
</style>
</head>
<body>
<div class="container">
  <header>
    <h2>Admin Dashboard</h2>
    <a href="../logout.php">Logout</a>
  </header>

  <h3>Permission Requests</h3>
  <table>
    <thead>
      <tr>
        <th>Full Name</th>
        <th>Start</th>
        <th>End</th>
        <th>Reason</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row=$res->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['full_name']) ?></td>
        <td><?= $row['start_date'] ?></td>
        <td><?= $row['end_date'] ?></td>
        <td><?= htmlspecialchars($row['reason']) ?></td>
        <td class="status-<?= strtolower($row['status']) ?>"><?= ucfirst($row['status']) ?></td>
        <td>
          <?php if($row['status']=='pending'): ?>
            <a class="approve" href="../requests/process_request.php?id=<?= $row['id'] ?>&action=approved">Approve</a>
            <a class="reject" href="../requests/process_request.php?id=<?= $row['id'] ?>&action=rejected">Reject</a>
          <?php else: echo '-'; endif; ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
