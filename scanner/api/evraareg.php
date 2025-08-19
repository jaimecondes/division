<?php 
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow specified HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Allow specified headers
include '../classes/register.php';
 require ('../includes/config.php');
// if(isset($_POST['fname'])){
//     
   
//     
// }

$jsonData = file_get_contents('php://input');

// Decode the JSON data into a PHP associative array
$data = json_decode($jsonData, true);

// Check if decoding was successful
if ($data === null) {
    // Failed to decode JSON data
    echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
    exit;
}

// Access the decoded data
$fname = $data['fName'];
$lname = $data['lName'];
$birthdate = $data['birthdate'];
$gender = $data['gender'];
$role = $data['role'];
$division = $data['division'];
$school = $data['school'];
$event = $data['event'];
$mobile= $data['mobile'];

echo evraaregister($data,$evraahost,$evraauser,$evraadb,$evraapassword);
// Now you can use $fname, $lname, $birthdate, $gender, $role, $division, $school, $event as needed

// Example response
// $response = ['success' => true, 'message' => 'Data received successfully'];
// echo json_encode($response);

?>