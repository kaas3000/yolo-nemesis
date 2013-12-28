<?php
session_start();

define("EVERYONE", 0);
define("VIEWER", 1);
define("ADMIN", 2);

// required
if (
	$_SESSION['accesslevel'] != ADMIN ||
	!isset($_GET['html']))
break;

require_once(__DIR__ . "/../mysql.php");

$html = $_GET['html'];
$contentID = $_GET['id'];
$page = $_GET['page'];
$pdo = db_setup_connection();

foreach($pdo->query("SELECT PageID FROM PAGE WHERE Name = '" . $page . "';") as $row) {
	$pageID = $row[0];
}
$contentQuery = $pdo->prepare("UPDATE CONTENT SET Html = ? WHERE ContentID IN (SELECT ContentID FROM ARTICLE WHERE articleID = ? AND PageID = ?)");
$success = $contentQuery->execute(array($html, $contentID, $pageID));

echo "UPDATE CONTENT SET Html = '$html' WHERE ContentID IN (SELECT ContentID FROM ARTICLE WHERE articleID = $contentID)";

if ($success) {
	echo "true";
} else { 
	$contentQuery = $pdo->prepare("INSERT INTO CONTENT SET Html = ?;");
	$contentQuery->execute(array($html));

	$result = $pdo->query("SELECT MAX(ContentID) FROM CONTENT;");
	$row = $result->fetch();
	$contentID = $row[0];

	$result = $pdo->query("SELECT PageID FROM PAGE WHERE Name = '" . $page . "';");
	$row = $result->fetch();
	$contentID = $row[0];
	
	$articleQuery = $pdo->prepare("INSERT INTO ARTICLE (PageID, ContentID, UserID) VALUES(?, ?, ?);");
	if ($articleQuery->execute(array())) {}
}
//$articleQuery = $pdo->prepare("INSERT INTO ARTICLE(PageID, ContentID, UserID) VALUES(SELECT PageID FROM PAGE WHERE Name = ?;, ?, ?);");

?>
