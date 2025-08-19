<?php 
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow specified HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Allow specified headers

include '../classes/register.php';
require ('../includes/config.php');
$res=getSchools($evraahost,$evraauser,$evraadb,$evraapassword);
$options = [];

foreach ($res as $row) {
    $options[] = [
        'value' => $row['id'],
        'label' => $row['schoolName']
    ];
}

echo json_encode($options);
?>