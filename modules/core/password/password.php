<?php
class Password {
	public __CONTRUCT() {
	}

	private function hashPassword($password) {
		$salt = substr(str_replace('+', '.', base64_encode(sha1(microtime(true), true))), 0, 22);
		$hashedPassword = crypt($password, '$2a$12$' . $salt);
		return $hashedPassword;
	}
}
?>
