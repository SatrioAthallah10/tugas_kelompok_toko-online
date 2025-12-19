<?php
// FILE: acc_pembayaran.php
session_start();
include 'koneksi.php';

// Cek apakah yang akses adalah Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_pesanan = $_GET['id'];
    
    // 1. UPDATE STATUS DI KERANJANG
    $query_update = "UPDATE keranjang SET STATUS = 'Sudah Dibayar' WHERE ID_PESANAN = '$id_pesanan'";
    
    // 2. CATAT KE SEJARAH PEMBAYARAN (Sesuai ERD)
    // Kita buat catatan string sederhana untuk kolom 'SEJARAH'
    $catatan_sejarah = "Pembayaran dikonfirmasi Admin pada " . date("Y-m-d H:i:s");
    $query_history = "INSERT INTO sejarah_pembayaran (SEJARAH, ID_PESANAN) VALUES ('$catatan_sejarah', '$id_pesanan')";

    // Jalankan Query (Gunakan Transaction biar aman, tapi pakai cara sederhana dulu)
    $update_ok = mysqli_query($koneksi, $query_update);
    $history_ok = mysqli_query($koneksi, $query_history);

    if ($update_ok && $history_ok) {
        echo "<script>alert('Pesanan Berhasil di-ACC!'); window.location='page_admin.php';</script>";
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
}
?>