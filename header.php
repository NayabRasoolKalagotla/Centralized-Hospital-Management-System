<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
.back-btn{
    position:fixed;
    top:20px;
    left:20px;
    padding:10px 15px;
    background:#0d47a1;
    color:white;
    text-decoration:none;
    border-radius:8px;
    font-weight:bold;
    box-shadow:0 4px 10px rgba(0,0,0,0.2);
    z-index:9999;
}
.back-btn:hover{
    background:#1565c0;
}
</style>

<!-- ✅ ALWAYS GO TO INDEX -->
<a href="index.php" class="back-btn">⬅ Home</a>