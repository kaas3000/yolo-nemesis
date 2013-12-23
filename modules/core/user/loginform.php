<?php
require_once("login.php");

if (isset($_SESSION['username'])) {
	Login::logout();
	header("location: /index.php");
	die();
}

if (!isset($_POST['username']) || !isset($_POST['password'])) {
	header("location: /index.php");
	die();
}

Login::logUserIn($_POST['username'], $_POST['password']);
header("location: /index.php");
?>
