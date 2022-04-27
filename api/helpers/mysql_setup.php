<?php
session_start();

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
		} catch (exception $e) {
			exit($e->getMessage());
		}
	}
	public function PDO()
{
	$charset = 'utf8mb4';
	$dsn = "mysql:host=$this->host;dbname=$this->db;charset=$charset";
	$options = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
	];

	$pdo = new PDO($dsn, $this->user, $this->pass, $options);

	return $pdo;
}
}


	
?>