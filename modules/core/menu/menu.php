<?php
class menu {
	private $template;
	private $HTML;

	public function __CONSTRUCT() {
		$this->template = __DIR__ . "/menu.html";
		$this->HTML = file_get_contents(__DIR__ . "/menu.html");
	}

	public function prepareHtml() {
		$arrHtml = explode("\n", $this->HTML);
		$strHtml = "";

		unset($arrHtml[count($arrHtml) - 1]);
		
		for ($i = 0; $i < count($arrHtml); $i++) {
			if (strstr($arrHtml[$i], "{title}") && strstr($arrHtml[$i], "{location}")) {
				$pdo = db_setup_connection();
				$query = "SELECT * FROM PAGE"; // Acceslevel needs to be added!
				$line = $arrHtml[$i];
				$j = $i;
				foreach($pdo->query($query) as $row) {
					if ($_SESSION['accesslevel'] == $row['AccessLevel'] || $row['AccessLevel'] == EVERYONE) {
						$lineWTitle = str_replace("{title}", $row['Name'], $line);
						$lineWUrl = str_replace("{location}", "index.php?page=" . $row['Name'], $lineWTitle);
						if ($_GET['page'] == $row['Name']) {
							$lineWActive = str_replace("{ifactive=", "", $lineWUrl);
							$lineFinal = str_replace("}", "", $lineWActive);
						} else {
							$lineFinal = preg_replace("{ifactive=+}", "", $lineWUrl);
						}
						
					} else {
						$lineFinal = "";
					}
					$arrHtml[$j] = $lineFinal;
					$j++;
				}
			}

			$strHtml .= $arrHtml[$i] . "\n";
		}

		$this->HTML = $strHtml;
	}

	public function display() {
		echo $this->HTML;
	}
	
	public function getHTML() {
		return $this->HTML;
	}
}
?>
