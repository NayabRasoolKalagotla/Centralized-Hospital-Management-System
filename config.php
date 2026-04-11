<?php
$conn = mysqli_connect("localhost", "root", "", "hospitaldb");

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>