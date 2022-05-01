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