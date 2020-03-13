<?php
header('Content-Type: application/json');
$json = json_decode(file_get_contents('php://input'), true);
include_once '/var/www/html/task/config/database.php';
include_once '/var/www/html/task/objects/user.php';
$db = new Database();
$db = $db->establishConnection();
$user = new User($db);
echo $user->getUsers();
