<?php
class Panel {
private $HTML;
public function __construct($theme = "/default.html") {
	$theme = __DIR__ . "/$theme";
	$this->prepare($theme);
}

public function getHtml() {
	if ($_SESSION['accesslevel'] == ADMIN) {
		return $this->HTML;
	} else {
		return "";
	}
}

public function prepare($theme) {
	$html = file_get_contents($theme);
	$pdo = db_setup_connection();
	$arrHtml = explode(">\n", $html);
	$strHtml = "";

	unset($arrHtml[count($arrHtml) - 1]);
	
	for ($i = 0; $i < count($arrHtml); $i++) {
		$arrHtml[$i] .= ">";
		if (strstr($arrHtml[$i], "{action}") && strstr($arrHtml[$i], "{title}")) {
			$pdo = db_setup_connection();
			$query = "SELECT * FROM PANEL";
			$line = $arrHtml[$i];
			$j = $i;
			foreach($pdo->query($query) as $row) {
					$lineWTitle = str_replace("{title}", $row['Title'], $line);
					$lineFinal = str_replace("{action}", $row['Action'], $lineWTitle);
				$arrHtml[$j] = $lineFinal;
				$j++;
			}
		}

		$html .= $arrHtml[$i] . "\n";
	}

	$html .= "\n\n" . file_get_contents(__DIR__ . "/javascript.html");
	$this->HTML = $html;
}
}
?>
