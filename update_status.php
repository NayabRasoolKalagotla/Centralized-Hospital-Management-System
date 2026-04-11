<?php
session_start();
include "config.php";

/* 🔐 OPTIONAL: DOCTOR LOGIN CHECK */
/*
if(!isset($_SESSION['doctor'])){
    header("Location: doctor_login.php");
    exit();
}
*/

/* ✅ CHECK PARAMETERS */
if(!isset($_GET['id']) || !isset($_GET['status'])){
    echo "Invalid Request";
    exit();
}

$id = $_GET['id'];
$status = $_GET['status'];

/* ✅ VALIDATE STATUS */
$allowed = ['Approved','Rejected'];

if(!in_array($status, $allowed)){
    echo "Invalid Status";
    exit();
}

/* =========================
   🔥 UPDATE STATUS + NOTIFICATION
========================= */

$update = mysqli_query($conn,"
UPDATE appointments 
SET Status='$status', Notified=0
WHERE Appointment_ID='$id'
");

if($update){
    echo "<script>
    alert('Status Updated Successfully');
    window.location='doctors.php';
    </script>";
}else{
    echo "<script>
    alert('Error updating status');
    window.history.back();
    </script>";
}
?>