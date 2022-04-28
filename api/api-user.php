<?php

if(!isset($_SESSION)) {
    session_start();
}

$user = $_SESSION['user'] ?? false;
if($user == false) {
    exit;
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

        $perms = new Permissions(1, 0, 0);

        $user = $_SESSION['user'];
        $userPerms = $user['permissions'];
    
        $uri = $_REQUEST['REQUEST_URI'];
        $hasFullAccess = $perms->verify($uri, $userPerms);

        if(!$hasFullAccess) {
            $req['id'] = $_SESSION['user']['id'];
        }

        $singleQuery = "call get_user_by_uname(?)";
        $listQuery = "call get_all_users(?)";
        $query = isset($req['id']) ? $singleQuery : $listQuery;
        $param = isset($req['id']) ? $req['id'] : ($req['sort_by'] ?? "username-asc");

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