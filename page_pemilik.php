<?php
session_start();
if($_SESSION['role'] != 'pemilik'){
    die("Anda bukan Pemilik Toko!");
}
?>
<h1>Halo Bos Pemilik Toko</h1>
<p>Di sini tempat atur stok dan harga.</p>
<a href="logout.php">Logout</a>