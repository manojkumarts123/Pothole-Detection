<?php
$pdo = new PDO('mysql:host = localhost; port = 3306; dbname=pms', 'Manoj', '123');
session_start();
session_destroy();
session_start();
$_SESSION["message"] = "Logged out Successfully";
header('location: login.php');

?>