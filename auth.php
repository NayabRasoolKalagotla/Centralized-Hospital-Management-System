<?php
session_start();
include "config.php";

/* ===========================
   LOGIN
=========================== */
if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    // check user
    $res = mysqli_query($conn, 
        "SELECT * FROM users WHERE email='$email' AND password='$password'"
    );

    if(mysqli_num_rows($res) > 0){
        $_SESSION['user'] = $email;

        // redirect to patient portal
        header("Location: patients.php");
        exit();
    } else {
        echo "<script>alert('Invalid Email or Password');</script>";
    }
}


/* ===========================
   SIGNUP (NO DUPLICATES)
=========================== */
if(isset($_POST['signup'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    // check if already exists
    $check = mysqli_query($conn, 
        "SELECT * FROM users WHERE email='$email'"
    );

    if(mysqli_num_rows($check) > 0){
        echo "<script>alert('Account already exists! Please login');</script>";
    } else {

        // insert new user (Patient_ID = NULL initially)
        mysqli_query($conn, 
            "INSERT INTO users (email, password, Patient_ID) 
             VALUES ('$email', '$password', NULL)"
        );

        echo "<script>alert('Account created successfully! Please login');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login / Signup</title>

<style>
body{
    font-family: Arial;
    background: linear-gradient(to right,#2196f3,#0d47a1);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:white;
    padding:30px;
    border-radius:10px;
    width:300px;
}

input{
    width:100%;
    padding:10px;
    margin:8px 0;
}

button{
    width:100%;
    padding:10px;
    background:#0d47a1;
    color:white;
    border:none;
}

/* toggle links */
.link{
    text-align:center;
    margin-top:10px;
    cursor:pointer;
    color:#0d47a1;
}
</style>

<script>
function showSignup(){
    document.getElementById("loginForm").style.display="none";
    document.getElementById("signupForm").style.display="block";
}
function showLogin(){
    document.getElementById("signupForm").style.display="none";
    document.getElementById("loginForm").style.display="block";
}
</script>

</head>

<body>

<div class="box">

<!-- LOGIN -->
<div id="loginForm">
<h2>Login</h2>

<form method="POST">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button name="login">Login</button>
</form>

<div class="link" onclick="showSignup()">
Don't have account? Create Account
</div>
</div>

<!-- SIGNUP -->
<div id="signupForm" style="display:none;">
<h2>Signup</h2>

<form method="POST">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button name="signup">Create Account</button>
</form>

<div class="link" onclick="showLogin()">
Already have account? Login
</div>
</div>

</div>

</body>
</html>