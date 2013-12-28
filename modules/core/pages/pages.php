<?php
require_once("../mysql.php");
if (!isset($_POST['Name']) || !isset($_POST['accesslevel']) || !isset($_POST['Theme'])) {
	header("location: /index.php");
	die();
}

function generateThemePages($pageName) {
	$pdo = db_setup_connection();
	$sql = "SELECT `folderName` FROM THEME WHERE `default`=1";
	foreach ($pdo->query($sql) as $row) {
		$themeFolder = $row['folderName'];
	}
	$dir = opendir(__DIR__ . "/../../themes/$themeFolder/");
	echo '<select name="Theme">' . "\n";
	while ($file = readdir($dir)) {
		if (strstr($file, ".html")) {
			if ($file == $pageName) {
				echo '<option value="' . $file . '" selected>' . $file . "</option>\n";
			} else {
				echo '<option value="' . $file . '">' . $file . "</option>\n";
			}
		}
	}
	echo "</select>";
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
$sql = "INSERT INTO PAGE (Name, AccessLevel, Theme) VALUES (?, ?, ?)";
$query = $pdo->prepare($sql);
$query->execute(array($_POST['Name'], accesslevelToInt($_POST['accesslevel']), $_POST['Theme']));
header("location: /index.php");
?>
