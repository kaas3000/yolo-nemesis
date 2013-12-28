<?php
$arrDBSettings = parse_ini_file("../mysql.ini");
require_once(__DIR__ . "/../" . $arrDBSettings['moduleLocation']);

$pdo = db_setup_connection();
$sql = "SELECT * FROM MODULE";

foreach ($pdo->query($sql) as $row) {
	require_once($_SERVER['DOCUMENT_ROOT'] . "/" . $row['Location']);
}

define("EVERYONE", 0);
define("VIEWER", 1);
define("ADMIN", 2);

session_start();
if (!isset($_SESSION['accesslevel'])) $_SESSION['accesslevel'] = 1; // set user to viewer
if ($_SESSION['accesslevel'] != ADMIN) {
	header("location: /index.php");
	die();
}
?>
