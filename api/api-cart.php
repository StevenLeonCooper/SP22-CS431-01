<?php
if(!isset($_SESSION)) {
    session_start();
}

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
require("helpers/permissions.php");

$conn = new Connection();
$db = $conn->PDO();
$response = new Response();


$method($_REQUEST, $db, $response);

function GET($req, PDO $db, $response) {
    global $user;
    $result = [];

    try {
        $query = "CALL get_cart(?)";
        $param = $_SESSION['user']['id'];
        $statement = $db->prepare($query);
        $statement->execute([$param]);
        $result = $statement->fetchAll();
        $response->status = "OK";
    } catch (Exception $error) {
        $msg = $error->getMessage();
        $result[0] = ["error" => getMessage()];
        $response->status = "FAIL: $msg";
    }

	$response->outputJSON($result);
}

function POST($req, PDO $db, $response) {
    try {
        $postJson = $_POST['json'] ?? false;

        if($postJson){
            $_POST = json_decode($postJson, true);
            // keep the 'json' property for backwards compatability
            $_POST['json'] = $postJson;
        }

        $params = array(
            ':id' => $_POST['id'],
            ':product_id' => $_POST['product_id'],
            ':quantity' => $_POST['quantity']
        );

        $statement = $db->prepare('CALL add_to_cart(?)');

        $statement->execute($params);

        $result = $statement->fetchAll();

        
    }
    catch(Exception $error) {
        $msg = $error->getMessage();

        $result = ["error" => $error->getMessage()];

        $response->status = "FAIL: $msg";
    }

    $response->outputJSON($result);
}

function PUT($req, PDO $db, $response) {
    $put = [];
    parse_str(file_get_contents("php://input"), $put);
	$putJson = $put['json'] ?? false;
        if($putJson){
            $put = json_decode($putJson, true);
            // keep the 'json' property for backwards compatability
            $put['json'] = $putJson;
        }
    try {
       $params = array(
        ':id' => $put['id'],
        ':quantity' => intval($put['quantity'])
    );

    $statement = $db->prepare('CALL update_cart(:id, :quantity)');
    $statement->execute($params);
    $result = $statement->fetchAll(); 
    } catch (Exception $error) {
        $msg = $error->getMessage();
        $result[0] = ["error" => $error->getMessage()];
        $response->status = "FAIL: $msg";
    }
    $response->outputJSON($result);
}

function DELETE($req, PDO $db, $response) {

}
