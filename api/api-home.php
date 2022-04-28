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

$conn = new Connection();
$db = $conn->PDO();

$method($_REQUEST, $db);

// Function that processes as "GET" request. 
function GET($req, PDO $db)
{
    // This trick is necessary since the json file is in a directly 1 folder up.
    $filePath = __DIR__.'/../_data/home.json';

    $json = file_get_contents($filePath);

    header("Content-Type: application/json; charset=utf-8");
    exit($json);
}