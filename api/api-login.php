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

$conn = new Connection();
$db = $conn->PDO();
$response = new Response();

$method($_REQUEST, $db, $response);

function GET($req, PDO $db, $response) {
	$json = "{}";
	header("Content-Type: application/json; charset=utf-8");
    exit($json);
}

function POST($req, PDO $db, $response) {
    try {
        $postJson = $_POST['json'] ?? false;

        if($postJson){
            $_POST = json_decode($postJson, true);
            // keep the 'json' property for backwards compatability
            $_POST['json'] = $postJson;
        }

        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $params = array(
            ':username' => $_POST['username'],
            ':password'  => $hashed_password,
            ':email' => $_POST['email'],
            ':first_name' => $_POST['first_name'],
            ':last_name' => $_POST['last_name'],
        );

        $statement = $db->prepare('CALL create_user(:username,:password,:email,:first_name,:last_name)');

        $statement->execute($params);

        $result = $statement->fetchAll()[0];

        $db = null;
        $statement = null;

        $conn = new Connection();
        $db = $conn->PDO();

        $req->put = [
            "username" => $result["username"],
            "password" => $result["password"]
        ];

        PUT($req, $db, $response);
    }
    catch(Exception $error) {
        $msg = $error->getMessage();

        $result = ["error" => $error->getMessage()];

        $response->status = "FAIL: $msg";
    }

    $response->outputJSON();
}

function PUT($req, PDO $db, $response) {
    $put = [];
    parse_str(file_get_contents("php://input"), $put);

    try{
        $putJson = $put['json'] ?? $req->$put ?? false;

        if($putJson){
            $put = json_decode($putJson, true);
            // keep the 'json' property for backwards compatability
            $put['json'] = $putJson;
        }

        $username = $put['username'];
        $password = $put['password'];

        $statement = $db->prepare('CALL get_user_by_uname(?)');

        $statement->execute([$username]);

        $userResult = $statement->fetchAll()[0];
        $statement->nextRowset();
        $permissionsResult = $statement->fetchAll();

        if(isset($userResult['error'])) {
            throw new Exception($userResult['error']);
        }

        $success = password_verify($password, $userResult['hash']);

        if(!$success) throw new Exception("Invalid password.");

        $userResult[$userResult['role']] = true;

        $_SESSION['user'] = $userResult;

        $_SESSION['user']['permissions'] = $permissionsResult;

        $result = $_SESSION['user'];

        $response->status = 'OK';
    } catch(Exception $error) {
        $msg = $error->getMessage();

        $result = ["error" => $error->getMessage()];

        $response->status = "FAIL: $msg";
    }

    $response->outputJSON();
}

function DELETE($req, PDO $db, $response) {
    session_destroy();
    $response->status = "OK";
    $response->outputJSON([]);
}