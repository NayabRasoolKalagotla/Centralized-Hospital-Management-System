<?php
session_start();
include "config.php";
include "header.php";

/* 🔍 GET SEARCH VALUE */
$search = $_GET['search'] ?? "";

/* 🔐 BASIC SECURITY */
$search = mysqli_real_escape_string($conn, $search);

/* 📌 SEARCH QUERY */
$sql = "SELECT * FROM hospitals
        WHERE Name LIKE '%$search%'
        OR District LIKE '%$search%'
        OR Location LIKE '%$search%'";

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Search Results</title>

<style>
body{
    font-family:Arial;
    background:#f4f7fb;
    padding:40px;
}

h2{
    color:#0d47a1;
}

/* CARD */
.card{
    background:white;
    padding:20px;
    margin:20px;
    border-radius:10px;
    width:400px;
    box-shadow:0 0 10px rgba(0,0,0,0.2);
    display:inline-block;
    vertical-align:top;
}

.card h3{
    color:#0d47a1;
}

/* BUTTON */
button{
    padding:10px 15px;
    border:none;
    border-radius:5px;
    cursor:pointer;
    margin-top:10px;
}

.book{
    background:#0d47a1;
    color:white;
}

.full{
    background:gray;
    color:white;
}

/* FACILITY TAG */
.tag{
    background:#0d47a1;
    color:white;
    padding:4px 8px;
    margin:2px;
    display:inline-block;
    border-radius:10px;
    font-size:12px;
}
</style>

</head>

<body>

<h2>🏥 Hospital Search Results</h2>

<?php

if(mysqli_num_rows($result) == 0){
    echo "<p>No hospitals found.</p>";
}

while($row = mysqli_fetch_assoc($result))
{
    $today = date('Y-m-d');
    $hospital_id = $row['Hospital_ID'];

    /* ✅ FIXED SLOT QUERY (REMOVED Hospital_ID ERROR) */
    $slot_query = mysqli_query($conn,"
        SELECT COUNT(*) as total 
        FROM appointments 
        WHERE Appointment_Date='$today'
    ");

    $slot_data = mysqli_fetch_assoc($slot_query);
    $remaining = 20 - $slot_data['total'];

    /* ✅ FIXED FACILITY QUERY */
    $facility_query = mysqli_query($conn,"
        SELECT f.Facility_Name 
        FROM hospital_facilities hf
        INNER JOIN facilities f 
        ON hf.Facility_ID = f.Facility_ID
        WHERE hf.Hospital_ID = '$hospital_id'
    ");

    $facilities = [];

    if($facility_query){
        while($f = mysqli_fetch_assoc($facility_query))
        {
            $facilities[] = $f['Facility_Name'];
        }
    }
?>

<div class="card">

<h3><?php echo $row['Name']; ?></h3>

<p><b>Hospital ID:</b> <?php echo $row['Hospital_ID']; ?></p>
<p><b>Location:</b> <?php echo $row['Location']; ?></p>
<p><b>District:</b> <?php echo $row['District']; ?></p>
<p><b>Contact:</b> <?php echo $row['Contact_Number']; ?></p>
<p><b>Email:</b> <?php echo $row['Email']; ?></p>

<!-- 🏥 FACILITIES -->
<p><b>Facilities:</b><br>
<?php
if(isset($facilities) && count($facilities) > 0)
{
    foreach($facilities as $f)
    {
        echo "<span class='tag'>$f</span>";
    }
}
else
{
    echo "No facilities available";
}
?>
</p>

<p><b>Slots Available:</b> <?php echo ($remaining > 0 ? $remaining : 0); ?> / 20</p>

<?php if($remaining > 0) { ?>
<a href="appointment.php?hospital_id=<?php echo $hospital_id; ?>">
<button class="book">Appointment Booking</button>
</a>
<?php } else { ?>
<button class="full" disabled>Slots Full</button>
<?php } ?>

</div>

<?php
}
?>

</body>
</html>