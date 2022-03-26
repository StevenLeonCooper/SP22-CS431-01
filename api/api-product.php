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
$response = new Response();

$method($_REQUEST, $db, $response);


function GET($req, PDO $db, $response)
{
    try {
        $singleQuery = "call get_product(?)";
        $listQuery = "call get_all_products(?)";
        $query = isset($req['id']) ? $singleQuery : $listQuery;
        $param = isset($req['id']) ? $req['id'] : ($req['sort_by'] ?? "title-asc");

        $statement = $db->prepare($query);
        
        $statement->execute([$param]);
        
        $result = $statement->fetchAll();

        $response->status = "OK";
    } catch (Exception $error) {
        $msg = $error->getMessage();

        $result = ["error" => $error->getMessage()];

        $this->$response->status = "FAIL: $msg";
    }

    $response->outputJSON($result);
}

function POST($req, PDO $db, $response)
{

    try {
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

        $result = $statement->fetchAll();

        $response->status = "OK";
    } catch (Exception $error) {
        $msg = $error->getMessage();

        $result = ["error" => $error->getMessage()];

        $response->status = "FAIL: $msg";
    }

    $response->outputJSON($result);
}

