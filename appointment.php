<?php
session_start();
include "config.php";
include "header.php";

// ✅ CHECK HOSPITAL ID
if(!isset($_GET['hospital_id'])){
    echo "Invalid Access";
    exit();
}

$hospital_id = $_GET['hospital_id'];

// ✅ CHECK LOGIN
if(!isset($_SESSION['patient_id'])){
    echo "<script>alert('Please register/login first'); window.location='register.php';</script>";
    exit();
}

$patient_id = $_SESSION['patient_id'];

/* ✅ SLOT LIST */
$slots = [
    "09:00 AM",
    "11:00 AM",
    "03:00 PM",
    "06:00 PM",
    "08:00 PM"
];

// ✅ FETCH DOCTORS
$doctors = mysqli_query($conn,"
SELECT * FROM doctors 
WHERE Hospital_ID='$hospital_id'
");

/* =========================
   ✅ BOOK APPOINTMENT
========================= */
if(isset($_POST['book']))
{
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['date'];
    $slot = $_POST['slot'];

    /* 🔥 CHECK SLOT LIMIT (MAX 5) */
    $check = mysqli_query($conn,"
    SELECT COUNT(*) as total 
    FROM appointments 
    WHERE Appointment_Date='$date'
    AND Slot_Time='$slot'
    AND Status!='Cancelled'
    ");

    $data = mysqli_fetch_assoc($check);

    if($data['total'] >= 5)
    {
        echo "<script>alert('❌ Slot Full! Choose another time');</script>";
    }
    else
    {
        /* 🔢 GENERATE UNIQUE APPOINTMENT ID */
        $res = mysqli_query($conn,"SELECT COUNT(*) as total FROM appointments");
        $row = mysqli_fetch_assoc($res);
        $next = $row['total'] + 1;

        $appointment_id = "APT" . str_pad($next,4,"0",STR_PAD_LEFT);

        /* ✅ INSERT (WITH NOTIFICATIONS) */
        $insert = mysqli_query($conn,"
        INSERT INTO appointments 
        (Appointment_ID, Patient_ID, Doctor_ID, Appointment_Date, Slot_Time, Status, Notified, Doctor_Notified)
        VALUES 
        ('$appointment_id','$patient_id','$doctor_id','$date','$slot','Pending',0,0)
        ");

        if($insert)
        {
            echo "<script>alert('✅ Appointment Booked Successfully'); window.location='patients.php';</script>";
        }
        else
        {
            die("Error: " . mysqli_error($conn));
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Book Appointment</title>

<style>
body{
    font-family:Arial;
    background:#f4f7fb;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:white;
    padding:30px;
    border-radius:10px;
    width:350px;
    box-shadow:0 0 15px rgba(0,0,0,0.2);
    text-align:center;
}

h2{
    color:#0d47a1;
}

select,input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border-radius:5px;
    border:1px solid #ccc;
}

button{
    width:100%;
    padding:10px;
    background:#0d47a1;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

button:hover{
    background:#1565c0;
}
</style>

</head>

<body>

<div class="box">

<h2>📅 Book Appointment</h2>

<form method="POST">

<!-- 👨‍⚕️ DOCTOR -->
<select name="doctor_id" required>
<option value="">-- Select Doctor --</option>

<?php
while($doc = mysqli_fetch_assoc($doctors))
{
    echo "<option value='".$doc['Doctor_ID']."'>".$doc['Doctor_Name']."</option>";
}
?>
</select>

<!-- 📅 DATE -->
<input type="date" name="date" required>

<!-- ⏰ SLOT -->
<select name="slot" required>
<option value="">-- Select Slot --</option>

<?php
foreach($slots as $s)
{
    echo "<option value='$s'>$s</option>";
}
?>
</select>

<button type="submit" name="book">Book Appointment</button>

</form>

</div>

</body>
</html>