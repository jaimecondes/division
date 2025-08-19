<?php 
include '../classes/register.php';
require ('../classes/config.php');
date_default_timezone_set('Asia/Manila');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$jsonData = file_get_contents('php://input');

// Decode the JSON data into a PHP associative array
$data = json_decode($jsonData, true);

// Check if decoding was successful
if ($data === null) {
    // Failed to decode JSON data
    echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
    exit;
}

$code = $data['code'];
$type = $data['type'];
$billetingID = $data['billetingID'];
$logdate = date("Y-m-d H:i:s");
echo recordLog($code,$type,$logdate,$host,$user,$db,$password);
?>