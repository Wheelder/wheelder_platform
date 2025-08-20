<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/work/controllers/NotificationsController.php';
$notifications = new NotificationsController();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// Call the fetchContracts method from the NotificationsController
$notifications->fetchContracts($_SESSION['email']);

// Call the fetchJobs method from the NotificationsController
$notifications->fetchJobs($_SESSION['email']);

// Call the fetchGigs method from the NotificationsController
$notifications->fetchGigs($_SESSION['email']);

// wget -O /dev/null/ https://hubcenteral.xyz/api/notificationAPI.php
?>
