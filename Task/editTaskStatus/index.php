<?php
header('Content-Type: application/json');
$json = json_decode(file_get_contents('php://input'), true);
include_once '/var/www/html/task/config/database.php';
include_once '/var/www/html/task/objects/task.php';
$db = new Database();
$db = $db->establishConnection();
$task = new Task($db);
echo $task->editTaskStatus($json["task_id"], $json["status"]);



