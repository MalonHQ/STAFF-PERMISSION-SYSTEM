<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$DB_HOST = 'localhost';
$DB_NAME = 'staff_permission_system';
$DB_USER = 'root';
$DB_PASS = '';
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_error) die('DB Connection failed: ' . $mysqli->connect_error);
session_start();
function base_url($path=''){return 'http://localhost/STAFF-PERMISSION-SYSTEM/'.ltrim($path,'/');}
function is_logged_in(){return isset($_SESSION['user']);}
function check_role($role){return is_logged_in() && $_SESSION['user']['role']===$role;}
