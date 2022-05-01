<?php

if(!isset($_SESSION)) {
    session_start();
}

$user = $_SESSION['user'] ?? false;
if($user == false) {
    header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden", true, 403);
    $response = new Response();
    $response->outputJSON("{}");
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

$put = [];
parse_str(file_get_contents("php://input"), $put);

$conn = new Connection();
$db = $conn->PDO();
$response = new Response();

$method($_REQUEST, $db, $response);


function GET($req, PDO $db, $response)
{
    try {

        $perms = new Permissions(1, 1, 1);

        $user = $_SESSION['user'];
        $userPerms = $user['permissions'];
    
        $uri = $_SERVER['REQUEST_URI'];
        $hasFullAccess = $perms->verify($uri, $userPerms);

        if(!$hasFullAccess) {
            $req['id'] = $_SESSION['user']['user_id'];
        }

        $singleQuery = "call get_user_by_uname(?)";
        $listQuery = "call get_all_users(?)";
        $query = isset($req['id']) ? $singleQuery : $listQuery;
        $param = isset($req['id']) ? $user['username'] : ($req['sort_by'] ?? "username-asc");

        $statement = $db->prepare($query);
        
        $statement->execute([$param]);
        
        $result = $statement->fetchAll();

        $userResult = $result[0];
        
        if(isset($userResult['error'])) {
            throw new Exception($userResult['error']);
        }

        $response->status = "OK";
    } catch (Exception $error) {
        $msg = $error->getMessage();

        $result[0] = ["error" => $error->getMessage()];

        $response->status = "FAIL: $msg";
    }
    $response->outputJSON($result);
}

function POST($req, PDO $db, $response) 
{
    try {

        $perms = new Permissions(1, 1, 1);

        $user = $_SESSION['user'];
        $userPerms = $user['permissions'];
    
        $uri = $_SERVER['REQUEST_URI'];
        $hasFullAccess = $perms->verify($uri, $userPerms);

        if(!$hasFullAccess) {
            header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden", true, 403);
            $response = new Response();
            $response->outputJSON("{}");
        }

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

        $statement = $db->prepare('CALL create_user(:username,:email,:first_name,:last_name,:password)');
        
        $statement->execute($params);
        
        $result = $statement->fetchAll();

        $userResult = $result[0];
        
        if(isset($userResult['error'])) {
            throw new Exception($userResult['error']);
        }

        $response->status = "OK";
    } catch (Exception $error) {
        $msg = $error->getMessage();

        $result[0] = ["error" => $error->getMessage()];

        $response->status = "FAIL: $msg";
    }
    $response->outputJSON($result);
}

function PUT($req, PDO $db, $response)
{
    $put = [];
    parse_str(file_get_contents("php://input"), $put);
    try {

        $perms = new Permissions(1, 1, 1);

        $user = $_SESSION['user'];
        $userPerms = $user['permissions'];
    
        $uri = $_SERVER['REQUEST_URI'];
        $perms->verify($uri, $userPerms);

        $putJson = $put['json'] ?? false;

        if($putJson){
            $put = json_decode($putJson, true);
            // keep the 'json' property for backwards compatability
            $put['json'] = $putJson;
        }
        

        $params = array(
            ':id' => $put['id'],
            ':username' => $put['username'],
            ':email' => $put['email'],
            ':first_name' => $put['first_name'],
            ':last_name' => $put['last_name'],
            ':password'  => $put['password'],
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

function DELETE($req, PDO $db, $response) {
    try {

        $perms = new Permissions(1, 1, 1);

        $user = $_SESSION['user'];
        $userPerms = $user['permissions'];
    
        $uri = $_SERVER['REQUEST_URI'];
        $perms->verify($uri, $userPerms);

        $param = $req['id'];

        $statement = $db->prepare("CALL delete_user(?)");

        $statement->execute([$param]);

        $result = $statement->fetchAll();

        $response->status = "OK";

    } catch (Exception $error) {
        $msg = $error->getMessage();

        $result = ["error" => $error->getMessage()];

        $response->status = "FAIL: $msg";
    }

    $response->outputJSON();
}