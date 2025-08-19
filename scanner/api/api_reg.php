<?php 
if(isset($_POST['reg'])){
    include '../classes/register.php';
    extract($_POST);
    register($fname,$lname,$email,$password);
}
?>