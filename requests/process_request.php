<?php
require '../config.php';
if (!check_role('admin')) { header('Location: ../index.php'); exit; }
$id=intval($_GET['id']); $action=$_GET['action'];
if (in_array($action,['approved','rejected'])){
$stmt=$mysqli->prepare("UPDATE permissions SET status=? WHERE id=?");
$stmt->bind_param('si',$action,$id); $stmt->execute();
}
header('Location: ../dashboard/admin.php'); exit;
