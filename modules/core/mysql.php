<?php
$host = "localhost";
$dbname = "cms";
$username = "cms";
$password = "secretpassword";

function db_setup_connection() {
	$host = "localhost";
	$dbname = "cms";
	$username = "cms";
	$password = "secretpassword";

	return new PDO("mysql:host=" . $host . ";dbname=$dbname", $username, $password);
}
?>
