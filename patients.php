<?php
session_start();
include "config.php";
include "header.php";

/* 🔐 LOGIN CHECK */
if(!isset($_SESSION['patient_id'])){
    echo "<script>alert('Please login first'); window.location='register.php';</script>";
    exit();
}

$patient_id = $_SESSION['patient_id'];

/* 📌 PATIENT DETAILS */
$patient = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM Patients WHERE Patient_ID='$patient_id'
"));

/* 🔔 NOTIFICATIONS */
$notifications = [];
$notify = mysqli_query($conn,"
SELECT * FROM appointments 
WHERE Patient_ID='$patient_id' 
AND Notified=0 
AND Status IN ('Approved','Rejected')
");

while($n = mysqli_fetch_assoc($notify)){
    $notifications[] = $n;

    mysqli_query($conn,"
    UPDATE appointments 
    SET Notified=1 
    WHERE Appointment_ID='".$n['Appointment_ID']."'
    ");
}

/* 📌 APPOINTMENTS */
$app_query = mysqli_query($conn,"
SELECT * FROM appointments 
WHERE Patient_ID='$patient_id'
ORDER BY Appointment_Date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Patient Dashboard</title>

<style>
body{
    font-family:Segoe UI;
    background:#f5f7fa;
    margin:0;
}

.container{
    width:90%;
    margin:30px auto;
}

/* ✅ HOME BUTTON */
.home-btn{
    position:fixed;
    top:15px;
    left:15px;
    background:#0d47a1;
    color:white;
    padding:8px 15px;
    text-decoration:none;
    border-radius:6px;
    font-size:14px;
}

.home-btn:hover{
    background:#08306b;
}

.card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    padding:12px;
    text-align:center;
}

th{
    background:#0d47a1;
    color:white;
}

tr:nth-child(even){
    background:#f2f2f2;
}

.cancel-btn{
    color:red;
    text-decoration:none;
    font-weight:bold;
}

.pdf-btn{
    color:green;
    text-decoration:none;
    font-weight:bold;
}

.bell{
    float:right;
    cursor:pointer;
    font-size:20px;
    position:relative;
}

.badge{
    position:absolute;
    top:-5px;
    right:-10px;
    background:red;
    color:white;
    font-size:12px;
    padding:3px 6px;
    border-radius:50%;
}

.popup{
    display:none;
    position:absolute;
    right:20px;
    top:50px;
    background:white;
    padding:10px;
    width:250px;
    box-shadow:0 5px 15px rgba(0,0,0,0.2);
    border-radius:8px;
}
</style>

<script>
function toggleNotifications(){
    var x = document.getElementById("notifyBox");
    x.style.display = (x.style.display === "block") ? "none" : "block";
}
</script>

</head>

<body>

<!-- 🔥 HOME BUTTON FIXED -->
<a href="index.php" class="home-btn">← Home</a>

<div class="container">

<!-- 🔔 BELL -->
<div class="bell" onclick="toggleNotifications()">
🔔
<?php if(count($notifications)>0){ ?>
<span class="badge"><?php echo count($notifications); ?></span>
<?php } ?>
</div>

<div id="notifyBox" class="popup">
<?php
if(count($notifications)>0){
    foreach($notifications as $n){
        echo "<p>Appointment <b>".$n['Appointment_ID']."</b> ".$n['Status']."</p>";
    }
}else{
    echo "No new notifications";
}
?>
</div>

<!-- PATIENT DETAILS -->
<div class="card">
<h2>Welcome, <?php echo $patient['Patient_Name']; ?></h2>
<p><b>ID:</b> <?php echo $patient['Patient_ID']; ?></p>
<p><b>Phone:</b> <?php echo $patient['Contact_Number']; ?></p>
<p><b>District:</b> <?php echo $patient['District']; ?></p>
</div>

<!-- APPOINTMENTS -->
<div class="card">
<h3>My Appointments</h3>

<table>
<tr>
<th>ID</th>
<th>Doctor</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($app_query)){ ?>

<tr>
<td><?php echo $row['Appointment_ID']; ?></td>
<td><?php echo $row['Doctor_ID']; ?></td>
<td><?php echo $row['Appointment_Date']; ?></td>
<td><?php echo $row['Slot_Time']; ?></td>

<td>
<?php
if($row['Status']=="Approved") echo "<span style='color:green'>Approved</span>";
elseif($row['Status']=="Pending") echo "<span style='color:orange'>Pending</span>";
elseif($row['Status']=="Rejected") echo "<span style='color:red'>Rejected</span>";
else echo "<span style='color:red'>Cancelled</span>";
?>
</td>

<td>
<a class="pdf-btn" href="appointment_letter.php?id=<?php echo $row['Appointment_ID']; ?>">📄 PDF</a>
|
<?php if($row['Status']!="Cancelled"){ ?>
<a class="cancel-btn" href="cancel.php?id=<?php echo $row['Appointment_ID']; ?>">Cancel</a>
<?php } else echo "-"; ?>
</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>