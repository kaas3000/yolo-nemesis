<?php
class menu {
	private $template;
	private $HTML;

	public function __CONSTRUCT() {
		$this->template = __DIR__ . "/menu.html";
		$this->HTML = file_get_contents(__DIR__ . "/menu.html");
	}

	/*
	 * credits to yuri at www.php.net/array_push
	 */
	function array_put_to_position(&$array, $object, $position, $name = null)
	{
		$count = 0;
		$return = array();
		foreach ($array as $k => $v)
		{  
			// insert new object
			if ($count == $position)
			{  
				if (!$name) $name = $count;
				$return[$name] = $object;
				$inserted = true;
			}  
			// insert old object
			$return[$k] = $v;
			$count++;
		}  
		if (!$name) $name = $count;
		if (!$inserted) $return[$name];
		$array = $return;
		return $array;
	}

	public function prepareHtml() {
		$arrHtml = explode("\n", $this->HTML);
		$strHtml = "";

		unset($arrHtml[count($arrHtml) - 1]);
		
		for ($i = 0; $i < count($arrHtml); $i++) {
			if (strstr($arrHtml[$i], "{title}") && strstr($arrHtml[$i], "{location}")) {
				$pdo = db_setup_connection();
				$query = "SELECT * FROM PAGE"; // Acceslevel needs to be added!
				foreach($pdo->query($query) as $row) {
					$lineWTitle = str_replace("{title}", $row['Name'], $arrHtml[$i]);
					$lineWUrl = str_replace("{location}", "index.php?page=" . $row['Name'], $lineWTitle);
					if ($_GET['id'] = $row['PageID']) {
						$lineWActive = str_replace("{ifactive=", "", $lineWUrl);
						$lineFinal = str_replace("}", "", $lineWActive);
					} else {
						$lineFinal = preg_replace("{ifactive=.+}", "", $lineWurl);
					}
					
					$arrHtml[$i] = $lineFinal;
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
