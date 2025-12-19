<?php
// Nyalakan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'koneksi.php';

// 1. CEK KEAMANAN: Hanya Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses Ditolak!'); window.location='login.php';</script>";
    exit;
}

if (isset($_GET['id'])) {
    $id_user = $_GET['id'];
    $id_user = mysqli_real_escape_string($koneksi, $id_user);

    // --- LOGIKA PEMBERSIHAN DATA (CLEANUP) ---
    
    // TAHAP 1: Hapus Sejarah Pembayaran terkait user ini
    // Kita perlu mencari ID_PESANAN milik user ini dulu di tabel keranjang
    // Query ini menghapus sejarah_pembayaran dimana ID_PESANAN-nya milik si User
    $query_hapus_sejarah = "DELETE sejarah_pembayaran 
                            FROM sejarah_pembayaran 
                            INNER JOIN keranjang ON sejarah_pembayaran.ID_PESANAN = keranjang.ID_PESANAN 
                            WHERE keranjang.ID_USER = '$id_user'";
    
    mysqli_query($koneksi, $query_hapus_sejarah); // Eksekusi (biarkan walau kosong/gagal)

    // TAHAP 2: Hapus Keranjang milik user ini
    $query_hapus_keranjang = "DELETE FROM keranjang WHERE ID_USER = '$id_user'";
    $cek_keranjang = mysqli_query($koneksi, $query_hapus_keranjang);

    if (!$cek_keranjang) {
         die("Gagal membersihkan keranjang user: " . mysqli_error($koneksi));
    }

    // TAHAP 3: Akhirnya, Hapus User dari tabel User
    $query_hapus_user = "DELETE FROM user WHERE ID_USER = '$id_user'";
    $result = mysqli_query($koneksi, $query_hapus_user);

    if ($result) {
        echo "<script>alert('User berhasil dihapus beserta seluruh datanya.'); window.location='page_admin.php';</script>";
    } else {
        echo "Gagal menghapus user: " . mysqli_error($koneksi);
        echo "<br><a href='page_admin.php'>Kembali</a>";
    }

} else {
    header("Location: page_admin.php");
}
?>