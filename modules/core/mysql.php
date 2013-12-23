<?php
$host = "localhost";
$dbname = "cms";
$username = "cms";
$password = "secretpassword";

function db_setup_connection() {
	$arrDBSettings = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/mysql.ini");
	$host = $arrDBSettings['host'];
	$dbname = $arrDBSettings['database'];
	$username = $arrDBSettings['username'];
	$password = $arrDBSettings['password'];

	return new PDO("mysql:host=" . $host . ";dbname=$dbname", $username, $password);
}
?>
