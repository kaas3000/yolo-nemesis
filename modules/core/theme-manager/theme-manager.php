<?php
require_once(__DIR__ . "/../mysql.php");

class themeManager {
private $HTML;
private $rootLocation;
private $themeLocation;
private $pageID;
private $menu;

public function __construct($pageID) {
	$themesPath = "/themes/";
	$defaultThemeFolder = $this->getDefaultThemeFolder();

	$this->rootLocation = $_SERVER['DOCUMENT_ROOT'] . $themesPath .  $defaultThemeFolder . "/";
	$this->themeLocation = $themesPath . $defaultThemeFolder . "/";
	$this->pageID = $pageID;
	$this->HTML = "";
}

public function setMenu($menu) {
	if ($this->HTML != "") {
		$menu->prepareHtml();

		$this->HTML = str_replace("{menu}", $menu->getHTML(), $this->HTML);
	}
}

public function prepareTheme() {
	$pageTheme = "home.html";

	$pdo = db_setup_connection();
	$sql = "SELECT Theme FROM PAGE WHERE PageID = ?";
	$query = $pdo->prepare($sql);
	if ($query->execute(array($this->pageID))) {
		$result = $query->fetch();
		$pageTheme = $result['Theme'];
	}

	if (file_exists($this->rootLocation . $pageTheme)) {
		$this->HTML = file_get_contents($this->rootLocation . $pageTheme);
	}
	
	// Correct the locations. http:// is unnessecary corrected, that is undone later
	$arrHtmlLocations = array('src="', 'href="', 'url("');
	$arrCorrectedLocations = array('src="' . $this->themeLocation, 'href="' . $this->themeLocation, 'url("' . $this->themeLocation);
	$this->HTML = str_replace($arrHtmlLocations, $arrCorrectedLocations, $this->HTML);

	$arrWrongLocations = array($this->themeLocation . "http://", $this->themeLocation . "https://", $this->themeLocation . "ftp://");
	$arrCorrectedLocations = array("http://", "https://", "ftp://");
	$this->HTML = str_replace($arrWrongLocations, $arrCorrectedLocations, $this->HTML);
}

public function display() {
	echo $this->HTML;
}

public function fillContent() {
	/*
	 * Search for every instance of {content} in the theme source
	 * and replace it with the matching article.
	 */
	$pattern = "/{content id\=[0-9]+}/";
	preg_match_all($pattern, $this->HTML, $result);
	
	foreach($result[0] as $key => $value) {
		$pattern = "/[0-9]+/";
		preg_match($pattern, $value, $IDResult);
		
		$contentID = $IDResult[0];
		
		$pdo = db_setup_connection();
		$query = $pdo->prepare('SELECT c.Html FROM ARTICLE AS a, CONTENT AS c WHERE a.PageID = ? AND articleID = ? AND a.ContentID = c.ContentID;');

		if ($query->execute(array($this->pageID, $contentID))) {
			$result = $query->fetch(PDO::FETCH_ASSOC);
		} else {
			$result["Html"] = '<p class="content">&nbsp;</p>"';
		}
		
		// if admin, add div.edit tags
		if ($_SESSION['accesslevel'] == ADMIN) {
			$result["Html"] = "<div class=\"edit\" id=\"$contentID\" contenteditable=\"false\">" . $result["Html"] . "</div>";
		}
		
		$pattern = "{content id=$contentID}";
		$this->HTML = str_replace($pattern, $result["Html"], $this->HTML);
	}
}

public function includeModule() {
	/*
	 * If a module-page exists, (db.PAGE not NULL), include it in {content id=1}
	 */
	$sql = "SELECT modulePage FROM PAGE WHERE PageID = ?";
	$pdo = db_setup_connection();
	$query = $pdo->prepare($sql);
	$query->execute(array($this->pageID));
	$result = $query->fetch();
	
	if ($result[0] != null) {
		include_once("pages/" . $result[0] . ".php");
	}
}

private function includeAdminPanel() {
	}

private function getDefaultThemeFolder() {
	$arrThemes = array();
	
	$pdo = db_setup_connection();
	$query = $pdo->prepare('SELECT folderName FROM THEME WHERE `default` = 1');

	if ($query->execute()) {
		$row = $query->fetch();
		return $row[0];
	} else {
		throw new Exception('No default theme specified');
		die();
	}
}
}
?>
