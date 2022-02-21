<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('helpers/mysql_setup.php');

function get_test_string() {
	$connect = new Connection();
	$result = $connect -> mysqli -> query('call get_test_data');
	return $result->fetch_array()[0];
}

?>