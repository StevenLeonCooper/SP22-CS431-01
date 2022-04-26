<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$method = $_SERVER['REQUEST_METHOD']; 

if (!function_exists($method)) { 
    header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed", true, 405);
    exit;
}

require("helpers/mysql_setup.php");
require("helpers/server.php");

$put = [];
parse_str(file_get_contents("php://input"), $put);

$conn = new Connection();
$db = $conn->PDO();
$response = new Response();

$method($_REQUEST, $db, $response, $put);

function GET($req, PDO $db, $response, $put) {
   
}

function POST($req, PDO $db, $response, $put) {

}

function PUT($req, PDO $db, $response, $put) {

}

function DELETE($req, PDO $db, $response, $put) {
	
}