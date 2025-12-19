<?php
// FILE: hapus_keranjang.php
session_start();
include 'koneksi.php';

// 1. CEK LOGIN
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'pembeli') {
    header("Location: login.php");
    exit();
}

// 2. CEK PARAMETER ID
if (isset($_GET['id'])) {
    $id_pesanan = $_GET['id'];
    $id_user = $_SESSION['id_user'];

    // 3. EKSEKUSI QUERY HAPUS YANG AMAN (SECURE DELETE)
    // Syarat hapus: ID cocok, User cocok (pemilik asli), dan Status masih Pending.
    $query = "DELETE FROM keranjang 
              WHERE ID_PESANAN = '$id_pesanan' 
              AND ID_USER = '$id_user' 
              AND STATUS = 'Pending'";

    if (mysqli_query($koneksi, $query)) {
        // Cek apakah ada baris yang terhapus?
        if (mysqli_affected_rows($koneksi) > 0) {
            echo "<script>alert('Pesanan berhasil dibatalkan.'); window.location='keranjang.php';</script>";
        } else {
            // Jika ID ada tapi bukan punya user ini, atau status sudah bukan pending
            echo "<script>alert('Gagal menghapus! Pesanan tidak ditemukan atau sudah diproses.'); window.location='keranjang.php';</script>";
        }
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    header("Location: keranjang.php");
}
?>