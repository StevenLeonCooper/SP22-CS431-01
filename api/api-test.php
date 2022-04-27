<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Find out which HTTP RESTful Verb was used
$method = $_SERVER['REQUEST_METHOD']; //Zach: built-in variable

// If it's an unknown value, return an error and exit
if (!function_exists($method)) { //Zach: built-in function
    header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed", true, 405);
    exit;
}
// If the value was good, we don't exit and the script continues below

// Get the mysql_setup.php file for the Connection class. Use "require" since we NEED it. 
require("helpers/mysql_setup.php");

// Create a new database connection object
$db = new Connection();

// Execute the function that matches the RESTful verb we received. 
// Pass the request values and the connection object (dependency injection)
// $_REQUEST is global but this gives us more flexibility. 
$method($_REQUEST, $db);

// This creates the final output for the app to consume. Our verb functions all use this. 
function Respond($output)
{
    Header("Content-Type: application/json; charset=utf-8");
    exit(json_encode($output));
}

// Function that processes as "GET" request. 
function GET($req, $db)
{
    $query = "CALL get_test_data";

    $result = $db->mysqli->query($query);

    $row = $result->fetch_all(MYSQLI_ASSOC);

    $output = $row[0];

    Respond($output);
}

// Function that processes as "POST" request.
function POST($req, $db)
{
    $output = array("method" => "POST", "status" => "200");
    Respond($output);
}

// Function that processes as "DELETE" request.
function DELETE($req, $db)
{
    $output = array("method" => "DELETE", "status" => "200");
    Respond($output);
}

// Function that processes as "PUT" request.
function PUT($req, $db)
{
    $output = array("method" => "PUT", "status" => "200");
    Respond($output);
}
