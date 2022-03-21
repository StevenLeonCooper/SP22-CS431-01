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


$db = new Connection();


$method($_REQUEST, $db);


function Respond($output)
{
    Header("Content-Type: application/json; charset=utf-8");
    exit(json_encode($output));
}


function GET($req, $db)
{
    $query = "CALL get_all_products";

    $result = $db->mysqli->query($query);

    $output = $result->fetch_all(MYSQLI_ASSOC);

    Respond($output);
}

