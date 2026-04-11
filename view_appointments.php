<?php
include "config.php";
include "header.php";

/* 🔴 CANCEL APPOINTMENT */
if(isset($_GET['cancel']))
{
    $id = $_GET['cancel'];

    mysqli_query($conn,"
    UPDATE Appointments 
    SET Status='Cancelled' 
    WHERE Appointment_ID='$id'
    ");

    echo "<script>alert('Appointment Cancelled'); window.location='view_appointments.php';</script>";
}

/* FETCH APPOINTMENTS WITH DETAILS */
$sql = "SELECT 
A.Appointment_ID,
P.Patient_Name,
D.Doctor_Name,
H.Name AS Hospital_Name,
A.Appointment_Date,
A.Time,
A.Status

FROM Appointments A
JOIN Patients P ON A.Patient_ID = P.Patient_ID
JOIN Doctors D ON A.Doctor_ID = D.Doctor_ID
JOIN Hospitals H ON A.Hospital_ID = H.Hospital_ID

ORDER BY A.Appointment_Date DESC";

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Appointments</title>

<style>

body{
font-family:Arial;
background:#eef3fb;
padding:40px;
}

h2{
color:#0d47a1;
}

/* TABLE DESIGN */
table{
border-collapse:collapse;
width:100%;
background:white;
box-shadow:0 0 10px rgba(0,0,0,0.2);
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

tr:hover{
background:#f1f1f1;
}

/* BUTTON */
.cancel-btn{
background:red;
color:white;
border:none;
padding:6px 10px;
cursor:pointer;
border-radius:4px;
}

</style>

</head>

<body>

<h2>My Appointments</h2>

<table>

<tr>
<th>ID</th>
<th>Patient</th>
<th>Doctor</th>
<th>Hospital</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($result))
{
echo "<tr>";

echo "<td>".$row['Appointment_ID']."</td>";
echo "<td>".$row['Patient_Name']."</td>";
echo "<td>".$row['Doctor_Name']."</td>";
echo "<td>".$row['Hospital_Name']."</td>";
echo "<td>".$row['Appointment_Date']."</td>";
echo "<td>".$row['Time']."</td>";

/* STATUS COLOR */
$statusColor = ($row['Status'] == 'Cancelled') ? 'red' : 'green';
echo "<td style='color:$statusColor;font-weight:bold;'>".$row['Status']."</td>";

/* ACTION BUTTON */
echo "<td>";

if($row['Status'] != 'Cancelled')
{
    echo "<a href='view_appointments.php?cancel=".$row['Appointment_ID']."'>
    <button class='cancel-btn'>Cancel</button>
    </a>";
}
else
{
    echo "-";
}

echo "</td>";

echo "</tr>";
}
?>

</table>

</body>
</html>