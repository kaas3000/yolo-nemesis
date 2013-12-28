<?php
echo '<h1 style="padding-left: 40px;">Settings</h1>

		<div class="users">
			<h2>users</h2>
				<ul>';
					$pdo = db_setup_connection();
					foreach($pdo->query("SELECT userID, username FROM USER;") as $row) {
						echo "<li>" . $row['username'] . " <a href=\"/modules/core/user/registerform.php?remove=" . $row['userID'] . "\">[remove]</a></li>\n";
					}
					echo "<li><a id=\"registerformLink\" href=\"javascript:setregister()\">New user</a></li>";
					echo '<li id="registerform" style="display: none;">' . str_replace('<input type="hidden" name="location">', '<input type="hidden" name="location" value="/pages/settings.php">', file_get_contents("../modules/core/user/registerform.html")) . "</li>";
				echo '
				</ul>
		</div>
		<div class="pages">
			<h2>pages</h2>
			<table>';
					$sql = "SELECT * FROM PAGE";
					foreach($pdo->query($sql) as $row) {
						echo '<form method="post" action="removepage.php"><tr>' . "\n\n";
						echo '<input type="hidden" name="id" value="' . $row['PageID'] . '">';
						echo '<td><input type="text" name="Name" value="' . $row['Name'] . '">' . "</td>\n\n";
						echo '<td><input type="text" name="AccessLevel" value="' . accesslevelToName($row['AccessLevel']) . '">' . "</td>\n\n";
						echo "<td>";
						generateThemePages($row['Theme']);
						echo "</td>";
						echo '<td><input type="submit" name="update" value="update"></td>';
						echo '<td><input type="submit" name="remove" value="remove"></td>';
						echo '</tr></form>';
					}
echo '
			</table>
			<a id="newPageLink" href="javascript:showNewPage(this)">New page</a>
			<div id="newPage" style="display: none;">';
					$action = "/modules/core/pages/pages.php";
					include(__DIR__ . "/../modules/core/pages/default.php");
			echo '</div>
		</div>';
