<?php
require_once("login.php");

if (!isset($_POST['username']) || !isset($_POST['password'])) {
	header("location: index.php");
	die();
}

Login::registerUser($_POST['username'], $_POST['password']);
header("location: ../../index.php");
?>
