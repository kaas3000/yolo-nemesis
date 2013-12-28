<?php
require_once(__DIR__ . "/../mysql.php");

class User {
	private function hashPassword($password) {
		$salt = substr(str_replace('+', '.', base64_encode(sha1(microtime(true), true))), 0, 22);
		$hashedPassword = crypt($password, '$2a$12$' . $salt);
		return $hashedPassword;
	}

	public function isLoggedIn() {
		if ($_SESSION['username']) {
			return false;
		} else {
			return true;
		}
	}

	public function buildUserForm($modulesPath) {
		echo (file_get_contents($modulesPath . "/login/loginform.html"));
	}

	public function buildRegistrationForm($modulesPath) {
		echo (file_get_contents($modulesPath . "/login/registerform.html"));
	}

	public function register($username, $password) {
		$pdo = db_setup_connection();
		$query = $pdo->prepare('INSERT INTO USER (username, password) VALUES( ? , ? )');
		$hashedPassword = User::hashPassword($password);

		$query->execute(array($username, $hashedPassword));
	}

	public function login($username, $password) {
		$pdo = db_setup_connection();
		$username = $pdo->quote($username);
		$passwordHash = User::hashPassword($password);

		$sql = "SELECT * FROM USER WHERE username = $username";
		$result = $pdo->query($sql);
		// Kijk of er iemand bestaat met die username, en check daarna het wachtwoord
		if ($result) {
			$row = $result->fetch(PDO::FETCH_ASSOC);

			if ($row['password'] == crypt($password, $row['password'])) {
				session_start();
				$_SESSION['username'] = $row['username'];
				$_SESSION['userid'] = $row['userID'];
				$_SESSION['accesslevel'] = (int) $row['AccessLevel'];
			}
		}
	}
}
?>
