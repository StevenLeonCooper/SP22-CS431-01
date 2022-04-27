<?php

session_start();

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

function POST($req, PDO $db, $response, $put) {
    try {
        $postJson = $_POST['json'] ?? false;

        if($postJson){
            $_POST = json_decode($postJson, true);
            // keep the 'json' property for backwards compatability
            $_POST['json'] = $postJson;
        }
        

        $params = array(
            ':username' => $_POST['username'],
            ':password'  => $_POST['password'],
            ':email' => $_POST['email'],
            ':first_name' => $_POST['first_name'],
            ':last_name' => $_POST['last_name'],
            ':picture' => $_POST['picture'],
            ':role_id' => $_POST['role_id']
        );

        $statement = $db->prepare('CALL create_user(:username,:password,:email,:first_name,:last_name,:picture,:role_id)');

        $statement->execute($params);

        $result = $statement->fetchAll();

        $response->status = "OK";
    }
    catch(Exception $error) {
        $msg = $error->getMessage();

        $result = ["error" => $error->getMessage()];

        $response->status = "FAIL: $msg";
    }

    $response->outputJSON($result);
}

function PUT($req, PDO $db, $response, $put) {

}