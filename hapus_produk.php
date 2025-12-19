<?php
session_start();
include 'koneksi.php';

// 1. CEK KEAMANAN
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'pemilik') {
    echo "<script>alert('Akses Ditolak!'); window.location='login.php';</script>";
    exit;
}

// 2. CEK ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query DELETE
    $query = "DELETE FROM daftar_produk WHERE ID_PRODUK = '$id'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo "<script>alert('Produk berhasil dihapus!'); window.location='page_pemilik.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus produk!'); window.location='page_pemilik.php';</script>";
    }
} else {
    header("Location: page_pemilik.php");
}
?>