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


$conn = new Connection();
$db = $conn->PDO();

$method($_REQUEST, $db);


function Respond($output)
{
    $output->checkEmpty();
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

    $data = $statement->fetchAll();

    $output = new Response();

    $output->data($data);

    //$output = new ServerResponse($req, $data);

    Respond($output);
}

function POST($req, PDO $db)
{

    $postJson = $_POST['json'] ?? false;

    if($postJson){
        $_POST = json_decode($postJson, true);
        // keep the 'json' property for backwards compatability
        $_POST['json'] = $postJson;
    }
    

    $params = array(
        ':title' => $_POST['title'],
        ':desc'  => $_POST['description'],
        ':image' => $_POST['image_url'],
        ':price' => $_POST['price'],
        ':stock'   => $_POST['stock']
    );

    $statement = $db->prepare('CALL post_product(:title,:desc,:image,:price,:stock)');

    $statement->execute($params);

    $data = $statement->fetchAll();

    $output = new Response();

    $output->data($data);

    Respond($output);
}

