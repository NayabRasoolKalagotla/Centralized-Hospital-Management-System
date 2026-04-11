<?php
session_start();
include "config.php";

/* 🔐 (OPTIONAL LOGIN)
if(!isset($_SESSION['doctor_id'])){
    header("Location: doctor_login.php");
    exit();
}
*/

/* 🔔 NOTIFICATIONS (NEW BOOKINGS) */
$notifications = [];

$notify = mysqli_query($conn,"
SELECT * FROM appointments 
WHERE Status='Pending' AND Doc_Notified=0
");

while($n = mysqli_fetch_assoc($notify)){
    $notifications[] = $n;

    // mark as seen
    mysqli_query($conn,"
    UPDATE appointments 
    SET Doc_Notified=1 
    WHERE Appointment_ID='".$n['Appointment_ID']."'
    ");
}

/* 📌 GET ALL APPOINTMENTS */
$result = mysqli_query($conn,"
SELECT * FROM appointments 
ORDER BY Appointment_Date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Doctor Dashboard</title>

<style>

body{
font-family:Segoe UI;
background:#f0f4f8;
margin:0;
}

/* CONTAINER */
.container{
width:90%;
margin:40px auto;
background:white;
padding:25px;
border-radius:12px;
box-shadow:0 8px 25px rgba(0,0,0,0.2);
}

/* TITLE */
h2{
color:#0d47a1;
margin-bottom:15px;
}

/* TABLE */
table{
width:100%;
border-collapse:collapse;
margin-top:20px;
}

th,td{
border:1px solid #ddd;
padding:10px;
text-align:center;
}

th{
background:#0d47a1;
color:white;
}

/* BUTTONS */
.btn{
padding:6px 12px;
border:none;
border-radius:5px;
cursor:pointer;
color:white;
text-decoration:none;
display:inline-block;
}

.approve{ background:green; }
.reject{ background:red; }
.pdf{ background:#1565c0; }

/* STATUS COLORS */
.status-approved{ color:green;font-weight:bold; }
.status-rejected{ color:red;font-weight:bold; }
.status-pending{ color:orange;font-weight:bold; }

/* 🔔 BELL */
.bell{
float:right;
cursor:pointer;
font-size:22px;
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
top:60px;
background:white;
padding:10px;
width:250px;
box-shadow:0 5px 15px rgba(0,0,0,0.2);
border-radius:8px;
}

</style>

<script>
function toggleNotifications(){
    var box = document.getElementById("notifyBox");
    box.style.display = (box.style.display === "block") ? "none" : "block";
}
</script>

</head>

<body>

<div class="container">

<!-- 🔔 NOTIFICATION BELL -->
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
        echo "<p>New Appointment: <b>".$n['Appointment_ID']."</b></p>";
    }
}else{
    echo "No new notifications";
}
?>
</div>

<h2>🩺 Doctor Dashboard</h2>

<table>

<tr>
<th>ID</th>
<th>Patient ID</th>
<th>Doctor ID</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php

if($result && mysqli_num_rows($result)>0){

while($row = mysqli_fetch_assoc($result)){

$statusClass = "";

if($row['Status']=="Approved") $statusClass="status-approved";
else if($row['Status']=="Rejected") $statusClass="status-rejected";
else $statusClass="status-pending";

echo "<tr>
<td>{$row['Appointment_ID']}</td>
<td>{$row['Patient_ID']}</td>
<td>{$row['Doctor_ID']}</td>
<td>{$row['Appointment_Date']}</td>
<td>{$row['Slot_Time']}</td>
<td class='$statusClass'>{$row['Status']}</td>
<td>";

/* 📄 PDF BUTTON */
echo "
<a class='btn pdf' href='appointment_letter.php?id={$row['Appointment_ID']}' target='_blank'>
PDF
</a><br><br>
";

/* ACTION BUTTONS */
if($row['Status']=="Pending"){

echo "
<a href='update_status.php?id={$row['Appointment_ID']}&status=Approved'>
<button class='btn approve'>Approve</button>
</a>

<a href='update_status.php?id={$row['Appointment_ID']}&status=Rejected'>
<button class='btn reject'>Reject</button>
</a>
";

}else{
echo "No Action";
}

echo "</td></tr>";

}

}else{
echo "<tr><td colspan='7'>No appointments found</td></tr>";
}
?>

</table>

</div>

</body>
</html>