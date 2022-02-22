<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Connection {
	private $host = null;
	private $user = null;
	private $pass = null;
	private $db = null;
	public mysqli $mysqli;

	public function __construct (string $db = null) {
		try {
			$json = file_get_contents("helpers/mysql_credentials.json");
			$settings = json_decode($json, true);
			$this->host = $settings['host'] ?? null;
			$this->user = $settings['username'] ?? null;
			$this->pass = $settings ['password'] ?? null;
			$this->db = $settings['schema'] ?? null;
			$this->mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db);

			if ($this->mysqli-> connect_errno) {
				echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
				exit();
			}
			// echo $mysqli -> query('call get_test_data');
		} catch (exception $e) {
			exit($e->getMessage());
		}
	}
}
	
?>