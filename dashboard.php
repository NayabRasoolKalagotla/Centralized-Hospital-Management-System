<?php
include "config.php";
include "header.php";

/* 📊 COUNTS */
$hospital_result = mysqli_query($conn,"SELECT COUNT(*) AS total FROM Hospitals");
$hospital_count = mysqli_fetch_assoc($hospital_result)['total'];

$doctor_result = mysqli_query($conn,"SELECT COUNT(*) AS total FROM Doctors");
$doctor_count = mysqli_fetch_assoc($doctor_result)['total'];

$patient_result = mysqli_query($conn,"SELECT COUNT(*) AS total FROM Patients");
$patient_count = mysqli_fetch_assoc($patient_result)['total'];

$appointment_result = mysqli_query($conn,"SELECT COUNT(*) AS total FROM Appointments");
$appointment_count = mysqli_fetch_assoc($appointment_result)['total'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:#f4f7fb;
}

.container{
    width:90%;
    margin:40px auto;
}

h1{
    color:#0d47a1;
}

/* CARDS */
.cards{
    display:flex;
    gap:20px;
    margin-top:30px;
}

.card{
    flex:1;
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.2);
    text-align:center;
}

.card h2{
    color:#0d47a1;
}

.card p{
    font-size:28px;
    font-weight:bold;
    margin-top:10px;
}
</style>

</head>

<body>

<div class="container">

<h1>🏥 Hospital Management Dashboard</h1>

<div class="cards">

<div class="card">
<h2>Hospitals</h2>
<p><?php echo $hospital_count; ?></p>
</div>

<div class="card">
<h2>Doctors</h2>
<p><?php echo $doctor_count; ?></p>
</div>

<div class="card">
<h2>Patients</h2>
<p><?php echo $patient_count; ?></p>
</div>

<div class="card">
<h2>Appointments</h2>
<p><?php echo $appointment_count; ?></p>
</div>

</div>

</div>

</body>
</html>