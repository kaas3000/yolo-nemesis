<?php
require_once("require.php");

function generateThemePages($pageName) {
	$pdo = db_setup_connection();
	$sql = "SELECT `folderName` FROM THEME WHERE `default`=1";
	foreach ($pdo->query($sql) as $row) {
		$themeFolder = $row['folderName'];
	}
	$dir = opendir(__DIR__ . "/../themes/$themeFolder/");
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

function return_output($file){
    ob_start();
    include $file;
    return ob_get_clean();
}

function accesslevelToName($level) {
	switch ($level) {
		case 0:
			return "everyone";
			break;
		case 1:
			return "viewer";
			break;
		case 2:
			return "admin";
			break;
		default:
			return "everyone";
	}
}

$theme = new themeManager();
$theme->prepareTheme();
$theme->setMenu(new Menu());
$head = '<style type="text/css">
			div.users, div.pages {
				margin-right: 20px;
			}
			div.users ul, div.pages ul {
				list-style-type: none;
				padding: 0;
			}
		</style>

		<script type="text/javascript">
			function setregister(element) {
				document.getElementById("registerform").style.display = "inline";
				document.getElementById("registerformLink").style.display = "none";
			}
			
			function showNewPage() {
				document.getElementById("newPage").style.display = "inline";
				document.getElementById("newPageLink").style.display = "none";
			}
		</script>';
$theme->addToHead($head);
$body = return_output('settingsbody.php');
$theme->addToBody($body);
$theme->fillContent();
$theme->display();
?>
