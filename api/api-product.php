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

function PUT($req, PDO $db, $response)
{
    $put = [];
    parse_str(file_get_contents("php://input"), $put);
    try {
        $putJson = $put['json'] ?? false;

        if($putJson){
            $put = json_decode($putJson, true);
            // keep the 'json' property for backwards compatability
            $put['json'] = $putJson;
        }
        

        $params = array(
            ':id' => $put['id'],
            ':title' => $put['title'],
            ':desc'  => $put['description'],
            ':image' => $put['image_url'],
            ':price' => $put['price'],
            ':stock'   => $put['stock']
        );

        $statement = $db->prepare('CALL update_product(:id,:title,:desc,:image,:price,:stock)');

        $statement->execute($params);

        $result = $statement->fetchAll();

        $response->status = "OK";

    }   catch (Exception $error) {
        $msg = $error->getMessage();

        $result = ["error" => $error->getMessage()];

        $response->status = "FAIL: $msg";
    }

        $response->outputJSON($result);
}

function DELETE($req, PDO $db, $response)
{
    try {
        $recordId = $req['id'];
        $statement = $db->prepare("CALL delete_product(?)");
        $statement ->execute($recordId);
        $result = $statement->fetchAll();
        $response->outputJSON($result);
    }

    catch (Exception $error) {
        $msg = $error->getMessage();
        $output = array("status" => $msg);
        $response->outputJSON($output);
    }
}