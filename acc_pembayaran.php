<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_pesanan = $_GET['id'];
    
    $query_update = "UPDATE keranjang SET STATUS = 'Sudah Dibayar' WHERE ID_PESANAN = '$id_pesanan'";
    $catatan_sejarah = "Pembayaran dikonfirmasi Admin pada " . date("Y-m-d H:i:s");
    $query_history = "INSERT INTO sejarah_pembayaran (SEJARAH, ID_PESANAN) VALUES ('$catatan_sejarah', '$id_pesanan')";
    $update_ok = mysqli_query($koneksi, $query_update);
    $history_ok = mysqli_query($koneksi, $query_history);

    if ($update_ok && $history_ok) {
        echo "<script>alert('Pesanan Berhasil di-ACC!'); window.location='page_admin.php';</script>";
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
}
?>