<?php
session_start();
if($_SESSION['role'] != 'admin'){
    die("Anda bukan Admin!");
}
?>
<h1>Selamat Datang Yang Mulia Admin</h1>
<p>Di sini tempat hapus-hapus data.</p>
<a href="logout.php">Logout</a>