<?php
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'Azizul Hoque Shiplu';
$_SESSION['user_email'] = 'admin@arentertainment.bd';
$_SESSION['user_role'] = 'admin';
require_once __DIR__ . '/../admin/settings/index.php';
