<?php
session_start();
include "config.php";

if(!isset($_GET['id'])){
    die("Invalid request");
}

$id = $_GET['id'];

mysqli_query($conn,"
UPDATE appointments 
SET Status='Cancelled' 
WHERE Appointment_ID='$id'
") or die(mysqli_error($conn));

header("Location: patients.php");
exit();
?>