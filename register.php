<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "config.php";

/* 🔐 LOGIN CHECK */
if(!isset($_SESSION['user'])){
    header("Location: auth.php");
    exit();
}

$email = $_SESSION['user'];

/* 🔴 CHECK IF ALREADY REGISTERED */
$check_user = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

if(!$check_user){
    die("User Fetch Error: " . mysqli_error($conn));
}

$user_data = mysqli_fetch_assoc($check_user);

/* ✅ IF ALREADY REGISTERED */
if(!empty($user_data['Patient_ID']) && $user_data['Patient_ID'] != NULL){
    $_SESSION['patient_id'] = $user_data['Patient_ID'];
    header("Location: patients.php");
    exit();
}

/* 📌 REGISTER */
if(isset($_POST['register']))
{
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $age = mysqli_real_escape_string($conn,$_POST['age']);
    $gender = mysqli_real_escape_string($conn,$_POST['gender']);
    $phone = mysqli_real_escape_string($conn,$_POST['phone']);
    $address = mysqli_real_escape_string($conn,$_POST['address']);
    $district = mysqli_real_escape_string($conn,$_POST['district']);

    /* 🔢 GENERATE PATIENT ID */
    $res = mysqli_query($conn,"SELECT COUNT(*) as total FROM patients"); // ✅ fixed lowercase

    if(!$res){
        die("Count Error: " . mysqli_error($conn));
    }

    $data = mysqli_fetch_assoc($res);
    $next = $data['total'] + 1;
    $patient_id = "PAT" . str_pad($next,4,"0",STR_PAD_LEFT);

    /* ✅ INSERT INTO patients */
    $insert = mysqli_query($conn,"
    INSERT INTO patients
    (Patient_ID, Patient_Name, Age, Gender, Contact_Number, Address, District)
    VALUES
    ('$patient_id','$name','$age','$gender','$phone','$address','$district')
    ");

    if(!$insert){
        die("Insert Error: " . mysqli_error($conn));
    }

    /* ✅ UPDATE users TABLE */
    $update = mysqli_query($conn,"
    UPDATE users 
    SET Patient_ID='$patient_id'
    WHERE email='$email'
    ");

    if(!$update){
        die("Update Error: " . mysqli_error($conn));
    }

    /* ✅ SESSION SET */
    $_SESSION['patient_id'] = $patient_id;
    $_SESSION['patient_name'] = $name;

    header("Location: patients.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Patient Registration</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:#f0f4f8;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

/* ✅ HOME BUTTON */
.home-btn{
    position:fixed;
    top:20px;
    left:20px;
    background:#0d47a1;
    color:white;
    padding:10px 15px;
    text-decoration:none;
    border-radius:8px;
    font-weight:bold;
}

.home-btn:hover{
    background:#1565c0;
}

.container{
    width:400px;
    background:white;
    padding:30px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,0.2);
}

h2{
    text-align:center;
    color:#0d47a1;
}

input, select{
    width:100%;
    padding:12px;
    margin:10px 0;
    border-radius:8px;
    border:1px solid #ccc;
}

button{
    width:100%;
    padding:12px;
    background:#0d47a1;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

button:hover{
    background:#1565c0;
}
</style>

</head>

<body>

<!-- 🔥 ALWAYS GO HOME -->
<a href="index.php" class="home-btn">← Home</a>

<div class="container">

<h2>Patient Registration</h2>

<form method="POST">

<input name="name" placeholder="Patient Name" required>

<input name="age" type="number" placeholder="Age" required>

<select name="gender" required>
<option value="">Select Gender</option>
<option>Male</option>
<option>Female</option>
<option>Other</option>
</select>

<input name="phone" placeholder="Phone Number" required>

<input name="address" placeholder="Address" required>

<input name="district" placeholder="District" required>

<button name="register">Register</button>

</form>

</div>

</body>
</html>