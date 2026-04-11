<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Centralized Hospital & Healthcare Management System</title>

<style>

/* ---------- BODY BACKGROUND ---------- */
body{
margin:0;
font-family:Segoe UI,Arial;
color:white;

background:
linear-gradient(rgba(0,70,140,0.65),rgba(0,70,140,0.65)),
url('https://images.unsplash.com/photo-1586773860418-d37222d8fce3');

background-size:cover;
background-position:center;
background-attachment:fixed;
}

/* ---------- HEADER ---------- */
header{
display:flex;
justify-content:space-between;
align-items:center;
padding:15px 30px;
background:rgba(255,255,255,0.9);
color:black;
position:fixed;
width:100%;
top:0;
box-shadow:0 3px 10px rgba(0,0,0,0.2);
z-index:1000;
}

.logo{
font-size:22px;
font-weight:bold;
color:#0d47a1;
}

.top-menu a{
margin-right:20px;
text-decoration:none;
color:#0d47a1;
font-weight:500;
}

.top-menu a:hover{
color:#1565c0;
}

/* ---------- AUTH BUTTON ---------- */
.auth button{
margin-left:10px;
padding:8px 18px;
border:none;
border-radius:20px;
background:#1976d2;
color:white;
cursor:pointer;
}

.auth button:hover{
background:#0d47a1;
}

/* ---------- SIDEBAR ---------- */
.sidebar{
position:fixed;
left:0;
top:70px;
width:220px;
height:100%;
background:#0d47a1;
padding-top:30px;
}

.sidebar a{
display:block;
padding:15px 20px;
color:white;
text-decoration:none;
font-size:16px;
}

.sidebar a:hover{
background:#1565c0;
}

/* ---------- MAIN CONTENT ---------- */
.main{
margin-left:240px;
margin-top:120px;
text-align:center;
}

.title{
font-size:36px;
font-weight:bold;
}

.subtitle{
font-size:18px;
margin-top:10px;
}

/* ---------- SEARCH BAR ---------- */
.search-box{
margin-top:40px;
}

.search-box input{
width:500px;
max-width:90%;
padding:18px;
border-radius:40px;
border:none;
font-size:18px;
outline:none;
box-shadow:0 6px 25px rgba(0,0,0,0.4);
}

.search-box button{
padding:15px 25px;
border:none;
border-radius:30px;
background:#0d47a1;
color:white;
font-size:16px;
cursor:pointer;
margin-left:10px;
}

.search-box button:hover{
background:#1565c0;
}

/* ---------- FOOTER QUOTE ---------- */
.quote{
margin-top:100px;
font-style:italic;
font-size:18px;
}

</style>
</head>

<body>

<header>

<div class="logo">
🏥 Healthcare Management System
</div>

<div class="top-menu">
<a href="dashboard.php">Dashboard</a>
</div>

<!-- AUTH SECTION -->
<div class="auth">

<?php if(isset($_SESSION['user'])) { ?>

    <span style="color:#0d47a1;font-weight:bold;">
        <?php echo $_SESSION['user']; ?>
    </span>

    <a href="logout.php"><button>Logout</button></a>

<?php } else { ?>

    <a href="auth.php"><button>Sign In</button></a>
    <a href="auth.php"><button>Sign Up</button></a>

<?php } ?>

</div>

</header>

<!-- SIDEBAR -->
<div class="sidebar">

<!-- ✅ DIRECT LINKS (NO LOGIN BLOCK) -->
<a href="doctor_login.php">👨‍⚕ Doctor Portal</a>

<a href="register.php">🧑 Patient Portal</a>

<a href="hospitals.php">🏥 Hospital Directory</a>

</div>

<div class="main">

<div class="title">
Centralized Hospital & Healthcare Management System
</div>

<div class="subtitle">
Connecting Hospitals, Doctors and Patients Across Districts
</div>

<div class="search-box">

<form action="search.php" method="GET">

<input type="text" name="search"
placeholder="Search hospitals, doctors, specialization..." required>

<button type="submit">Search</button>

</form>

</div>

<div class="quote">
“Healthcare should be accessible, transparent and connected.”
</div>

</div>

</body>
</html>