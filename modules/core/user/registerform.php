<?php
require_once("users.php");
session_start();

if (isset($_GET['remove']) && $_SESSION['accesslevel'] == 2) {
	User::remove($_GET['remove']);
} else {
	if (!isset($_POST['username']) || !isset($_POST['password'])) {
		header("location: index.php");
		die();
	}
}

User::register($_POST['username'], $_POST['password'], $_POST['accesslevel']);
if (isset($_POST['location'])) {
	header("location: " . $_POST['location']);
} else {
	header("location: /index.php");
}
?>
