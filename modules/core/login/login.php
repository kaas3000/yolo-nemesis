<?php
require_once(__DIR__ . "/../mysql.php");

class Login {
    function __construct() {
    }
    
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
    
    public function buildLoginForm($modulesPath) {
        echo (file_get_contents($modulesPath . "/login/loginform.html"));
    }

    public function buildRegistrationForm($modulesPath) {
	echo (file_get_contents($modulesPath . "/login/registerform.html"));
    }

    public function registerUser($username, $password) {
	$pdo = db_setup_connection();
	$query = $pdo->prepare('INSERT INTO USERS (username, password) VALUES( ? , ? )');
	$hashedPassword = Login::hashPassword($password);

	$query->execute(array($username, $hashedPassword));
    }

    public function logUserIn($username, $password) {
	$pdo = db_setup_connection();
	$username = $pdo->quote($username);
	$passwordHash = Login::hashPassword($password);
	
	$sql = "SELECT * FROM USERS WHERE username = $username";
	$result = $pdo->query($sql);
	// Kijk of er iemand bestaat met die username, en check daarna het wachtwoord
	if ($result->rowCount() > 0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);

		if ($row['password'] == crypt($password, $row['password'])) {
			session_start();
			$_SESSION['username'] = $row['username'];
		}
	}
    }
}
?>
