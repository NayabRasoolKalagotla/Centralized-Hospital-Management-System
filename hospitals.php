<?php
include "config.php";
include "header.php";

/* 🔍 GET VALUES */
$search = $_GET['search'] ?? '';
$location = $_GET['location'] ?? '';
$district = $_GET['district'] ?? '';
$selected_hospital = $_GET['hospital_id'] ?? '';

/* 🧠 QUERY */
$sql = "SELECT * FROM Hospitals WHERE 1=1";

if(!empty($search)){
    $sql .= " AND (
        Name LIKE '%$search%' OR
        Location LIKE '%$search%' OR
        District LIKE '%$search%'
    )";
}

if(!empty($location)){
    $sql .= " AND Location='$location'";
}

if(!empty($district)){
    $sql .= " AND District='$district'";
}

$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>

<title>Hospital Directory</title>

<style>

body{
font-family:Arial;
background:#eef3fb;
padding:40px;
}

h2{
color:#0d47a1;
}

/* SEARCH */
form{
margin-bottom:20px;
}

input, select{
padding:10px;
margin-right:10px;
border-radius:6px;
border:1px solid #ccc;
}

button{
padding:10px 15px;
background:#0d47a1;
color:white;
border:none;
border-radius:6px;
cursor:pointer;
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
text-align:left;
}

th{
background:#0d47a1;
color:white;
}

/* CLICKABLE NAME */
.hospital-link{
color:#0d47a1;
font-weight:bold;
text-decoration:none;
}

.hospital-link:hover{
text-decoration:underline;
}

/* DOCTOR CARD */
.card{
background:white;
padding:15px;
margin:10px 0;
border-radius:8px;
box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

.btn{
background:#0d47a1;
color:white;
padding:6px 10px;
border-radius:6px;
text-decoration:none;
display:inline-block;
margin-top:8px;
}

</style>

</head>

<body>

<h2>Hospital Directory</h2>

<!-- 🔍 SEARCH -->
<form method="GET">
<input type="text" name="search" placeholder="Search..."
value="<?php echo $search; ?>">

<select name="location">
<option value="">All Locations</option>
<option>Anakapalli</option>
<option>Payakaraopeta</option>
<option>Yelamanchili</option>
</select>

<select name="district">
<option value="">All Districts</option>
<option>Anakapalli</option>
<option>Prakasam</option>
</select>

<button type="submit">Search</button>
</form>

<!-- 📊 TABLE -->
<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Location</th>
<th>District</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($result)){
echo "<tr>";

echo "<td>".$row['Hospital_ID']."</td>";

/* 🔥 CLICKABLE NAME */
echo "<td>
<a class='hospital-link' href='?hospital_id=".$row['Hospital_ID']."'>
".$row['Name']."
</a>
</td>";

echo "<td>".$row['Location']."</td>";
echo "<td>".$row['District']."</td>";

echo "</tr>";
}
?>

</table>

<?php
/* 🔥 SHOW DOCTORS BELOW */
if(!empty($selected_hospital)){

$d_query = mysqli_query($conn,"
SELECT * FROM Doctors WHERE Hospital_ID='$selected_hospital'
");

echo "<h3>Doctors</h3>";

if(mysqli_num_rows($d_query)>0){

while($doc=mysqli_fetch_assoc($d_query)){

echo "<div class='card'>";
echo "<b>".$doc['Doctor_Name']."</b><br>";
echo "Specialization: ".$doc['Specialization']."<br>";
echo "Experience: ".$doc['Experience_Years']." years<br>";

echo "<a class='btn' href='appointment.php?hospital_id=".$selected_hospital."'>
Book Appointment</a>";

echo "</div>";

}

}else{
echo "No doctors found";
}
}
?>

</body>
</html>