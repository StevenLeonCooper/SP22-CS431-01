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


function Respond($output)
{
    Header("Content-Type: application/json; charset=utf-8");
    exit(json_encode($output));
}


function GET($req, PDO $db)
{
    $singleQuery = "call get_product(?)";
    $listQuery = "call get_all_products(?)";
    $query = isset($req['id']) ? $singleQuery : $listQuery;
    $param = isset($req['id']) ? $req['id'] : ($req['sort_by'] ?? "title-asc");

    $statement = $db->prepare($query);
    
    $statement->execute([$param]);

    $output = $statement->fetchAll();


    Respond($output);
}
