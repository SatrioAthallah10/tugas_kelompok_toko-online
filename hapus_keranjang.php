<?php

session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'pembeli') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_pesanan = $_GET['id'];
    $id_user = $_SESSION['id_user'];
    $query = "DELETE FROM keranjang 
              WHERE ID_PESANAN = '$id_pesanan' 
              AND ID_USER = '$id_user' 
              AND STATUS = 'Pending'";

    if (mysqli_query($koneksi, $query)) {
        if (mysqli_affected_rows($koneksi) > 0) {
            echo "<script>alert('Pesanan berhasil dibatalkan.'); window.location='keranjang.php';</script>";
        } else {

            echo "<script>alert('Gagal menghapus! Pesanan tidak ditemukan atau sudah diproses.'); window.location='keranjang.php';</script>";
        }
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    header("Location: keranjang.php");
}
?>