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
	//$pdo = db_setup_connection();
	if (file_exists($this->rootLocation . "home.html")) {
		$this->HTML = file_get_contents($this->rootLocation . "home.html");
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
		$query = $pdo->prepare('SELECT * FROM ARTICLE WHERE PageID = ? AND articleID = ?;');

		if ($query->execute(array($this->pageID, $contentID))) {
			$result = $query->fetch(PDO::FETCH_ASSOC);
		} else {
			$result["Content"] = '<p class="content">&nbsp;</p>"';
		}

		$pattern = "{content id=$contentID}";
		$this->HTML = str_replace($pattern, $result["Content"], $this->HTML);
	}
	/*
	 * Search for the first instance of {menu}, and replace
	 * it with the site menu
	 */

}

public function getDefaultThemeFolder() {
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
