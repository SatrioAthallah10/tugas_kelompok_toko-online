<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    exit("Akses Ditolak");
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($koneksi, "DELETE FROM keluhan_user WHERE ID_KELUHAN = '$id'");
}

header("Location: page_admin.php");
?>