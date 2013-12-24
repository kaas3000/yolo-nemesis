<?php
require_once(__DIR__ . "/users.php");

if (!isset($_POST['username']) || !isset($_POST['password'])) {
	header("location: /index.php");
	die();
}

User::login($_POST['username'], $_POST['password']);
header("location: /index.php");
?>
