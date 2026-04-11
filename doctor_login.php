<?php
session_start();
include "config.php";
include "header.php";

/* 🔐 LOGIN */
if(isset($_POST['login']))
{
    $doctor_id = $_POST['doctor_id'];
    $doctor_name = $_POST['doctor_name'];

    $sql = "SELECT * FROM Doctors 
            WHERE Doctor_ID='$doctor_id' 
            AND Doctor_Name='$doctor_name'";

    $result = mysqli_query($conn, $sql);

    if($result && mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_assoc($result);

        $_SESSION['doctor_id'] = $row['Doctor_ID'];
        $_SESSION['doctor_name'] = $row['Doctor_Name'];

        header("Location: doctor_dashboard.php");
        exit();
    }
    else
    {
        echo "<script>alert('❌ Invalid Doctor ID or Name');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Doctor Login</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f7fb;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.login-box{
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

input{
    width:90%;
    padding:10px;
    margin:10px 0;
    border-radius:5px;
    border:1px solid #ccc;
}

button{
    width:95%;
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

.back{
    margin-top:15px;
    display:block;
    text-decoration:none;
    color:#0d47a1;
}
</style>

</head>

<body>

<div class="login-box">

<h2>👨‍⚕️ Doctor Login</h2>

<form method="POST">

<input type="text" name="doctor_id" placeholder="Enter Doctor ID (e.g., DOC0001)" required>

<input type="text" name="doctor_name" placeholder="Enter Doctor Name (exact)" required>

<button type="submit" name="login">Login</button>

</form>

<a href="index.php" class="back">⬅ Back to Home</a>

</div>

</body>
</html>