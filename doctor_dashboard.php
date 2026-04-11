<?php
include "config.php";
include "header.php";
session_start();

/* 🔐 LOGIN CHECK */
if(!isset($_SESSION['doctor_id'])){
    header("Location: doctor_login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

/* =========================
   🔔 DOCTOR NOTIFICATIONS
========================= */
$notifications = [];

$notify = mysqli_query($conn,"
SELECT * FROM appointments 
WHERE Doctor_ID='$doctor_id' 
AND Doctor_Notified=0
");

while($n = mysqli_fetch_assoc($notify)){
    $notifications[] = $n;

    // mark as seen
    mysqli_query($conn,"
    UPDATE appointments 
    SET Doctor_Notified=1 
    WHERE Appointment_ID='".$n['Appointment_ID']."'
    ");
}

/* =========================
   📊 FETCH APPOINTMENTS
========================= */
$sql = "SELECT 
A.Appointment_ID,
A.Patient_ID,
A.Appointment_Date,
A.Slot_Time,
A.Status

FROM appointments A
WHERE A.Doctor_ID='$doctor_id'
ORDER BY A.Appointment_Date DESC";

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Doctor Dashboard</title>

<style>

body{
    font-family:Segoe UI;
    background:#f4f7fb;
    margin:0;
}

/* 🔔 BELL */
.bell-container{
    position:absolute;
    top:20px;
    right:40px;
}

.bell{
    font-size:24px;
    cursor:pointer;
    position:relative;
}

.badge{
    position:absolute;
    top:-8px;
    right:-10px;
    background:red;
    color:white;
    border-radius:50%;
    padding:3px 7px;
    font-size:12px;
}

.notify-dropdown{
    display:none;
    position:absolute;
    right:0;
    top:35px;
    width:260px;
    background:white;
    box-shadow:0 5px 15px rgba(0,0,0,0.2);
    border-radius:10px;
    padding:10px;
}

.notify-item{
    padding:10px;
    margin-bottom:8px;
    background:#e3f2fd;
    border-radius:6px;
}

/* CONTAINER */
.container{
    width:90%;
    margin:60px auto;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

th,td{
    padding:12px;
    border:1px solid #ddd;
    text-align:center;
}

th{
    background:#0d47a1;
    color:white;
}

/* BUTTON */
.btn{
    padding:6px 12px;
    border:none;
    border-radius:5px;
    cursor:pointer;
    color:white;
}

.approve{
    background:green;
}

.reject{
    background:red;
}

.status-approved{
    color:green;
    font-weight:bold;
}

.status-rejected{
    color:red;
    font-weight:bold;
}

.status-pending{
    color:orange;
    font-weight:bold;
}

</style>

</head>

<body>

<!-- 🔔 NOTIFICATION BELL -->
<div class="bell-container">

<div class="bell" onclick="toggleNotifications()">
🔔
<?php if(count($notifications)>0){ ?>
<span class="badge"><?php echo count($notifications); ?></span>
<?php } ?>
</div>

<div id="notifyBox" class="notify-dropdown">

<?php if(count($notifications)>0){ ?>

<?php foreach($notifications as $n){ ?>

<div class="notify-item">
New Appointment from <?php echo $n['Patient_ID']; ?>
</div>

<?php } ?>

<?php } else { ?>

<div class="notify-item">No new notifications</div>

<?php } ?>

</div>

</div>

<div class="container">

<h2>Doctor Appointments</h2>

<table>
<tr>
<th>ID</th>
<th>Patient</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
if(mysqli_num_rows($result) > 0){

while($row=mysqli_fetch_assoc($result))
{
echo "<tr>";
echo "<td>".$row['Appointment_ID']."</td>";
echo "<td>".$row['Patient_ID']."</td>";
echo "<td>".$row['Appointment_Date']."</td>";
echo "<td>".$row['Slot_Time']."</td>";

$statusClass = "";
if($row['Status']=="Approved") $statusClass="status-approved";
elseif($row['Status']=="Rejected") $statusClass="status-rejected";
else $statusClass="status-pending";

echo "<td class='$statusClass'>".$row['Status']."</td>";

echo "<td>";

if($row['Status']=="Pending"){
echo "
<a href='update_status.php?id=".$row['Appointment_ID']."&status=Approved'>
<button class='btn approve'>Approve</button>
</a>

<a href='update_status.php?id=".$row['Appointment_ID']."&status=Rejected'>
<button class='btn reject'>Reject</button>
</a>
";
}else{
echo "No Action";
}

echo "</td>";
echo "</tr>";
}

}else{
echo "<tr><td colspan='6'>No appointments found</td></tr>";
}
?>

</table>

</div>

<!-- 🔧 JS -->
<script>
function toggleNotifications(){
    var box = document.getElementById("notifyBox");

    if(box.style.display === "block"){
        box.style.display = "none";
    } else {
        box.style.display = "block";
    }
}
</script>

</body>
</html>