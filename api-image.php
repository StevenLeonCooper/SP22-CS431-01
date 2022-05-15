<?php
require("helpers/mysql_setup.php");
require("helpers/server.php");
$target_dir = __DIR__ . '/../assets/uploads/';

$target_file = $target_dir . basename($_FILES['file']['name']);
$target_file = str_replace("\\", "", $target_file);

$uploadOk = 1;

$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

$log = [];

$output = [];
$response = new Response();

$log[] = "target: " . $target_dir;

$log[] = "file: " . $target_file;

if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check !== false) {
        $log[] = "File is an image . " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        $log[] ="File is not an image.";
        $uploadOk = 0;
    }
}

if (file_exists($target_file)) {
    $log[] = "Sorry, file already exists.";
    $uploadOk = 0;
}

if ($_FILES["file"]["size"] > 500000) {
    $log[] = "Sorry, your file is too large.";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    $log[] = "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $output["URL"] = "assets/uploads/" . $_FILES["file"]["name"];
        $log[] = "The file " . htmlspecialchars(basename($_FILES["file"]["name"])) . $_FILES["file"]["error"];
    } else {
        $log[] = "Not uploaded because of error #" . $_FILES["file"]["error"];
    }
}

$output["log"] = $log;
exit(json_encode($output));
# $response->outputJSON($output);
?>