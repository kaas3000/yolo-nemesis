<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
?>
<?php
// Set page id to home if it doesn't exist
if (!isset($_GET['page'])) {
	header("location: index.php?page=home");
} else {
	// First include mysql module
	// After that, retrieve every other module from database and include it
	$arrDBSettings = parse_ini_file("mysql.ini");
	require_once($arrDBSettings['moduleLocation']);

	$pdo = db_setup_connection();
	$sql = "SELECT * FROM MODULE";

	foreach ($pdo->query($sql) as $row) {
		require_once($_SERVER['DOCUMENT_ROOT'] . "/" . $row['Location']);
	}

	$sql = "SELECT PageID FROM PAGE WHERE Name = ?";
	$query = $pdo->prepare($sql);
	if ($query->execute(array($_GET['page']))) {
		$result = $query->fetch();
		$pageID = $result['PageID'];
	} else {
		header("location: index.php?page=home");
	}
}

session_start();
if (!isset($_SESSION['accesslevel'])) $_SESSION['accesslevel'] = 1; // set user to viewer

define("EVERYONE", 0);
define("VIEWER", 1);
define("ADMIN", 2);

$path = $_SERVER['DOCUMENT_ROOT'];
$root = __DIR__;
$modulesPath = __DIR__ . "/modules/";
$themesPath = "/themes/";


$theme = new themeManager($pageID);
$theme->prepareTheme();
$theme->includeModule();
$theme->setMenu(new menu());
$theme->fillContent();
$theme->display();

$panel = new Panel();
echo $panel->getHtml();
?>
<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';
?>
