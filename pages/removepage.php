<?php
require_once("require.php");

if (!isset($_POST['update']) && !isset($_POST['remove'])) {
	die();
}

function accesslevelToInt($level) {
	switch(strtoupper($level)) {
		case "EVERYONE":
			return 0;
			break;
		case "VIEWER":
			return 1;
			break;
		case "ADMIN":
			return 2;
			break;
	}
}
	
$pdo = db_setup_connection();
if (isset($_POST['update'])) {
	$sql = "UPDATE PAGE SET Name = ?, AccessLevel = ?, Theme = ? WHERE PageID = ?";
	$query = $pdo->prepare($sql);
	$query->execute(array($_POST['Name'], accesslevelToInt($_POST['AccessLevel']), $_POST['Theme'], $_POST['id']));
	header("location: /pages/settings.php");
} else {
	$sql = "DELETE FROM PAGE WHERE PageID=?";
	$query = $pdo->prepare($sql);
	$query->execute(array($_POST['id']));
	header("location: /pages/settings.php");
}
?>
