<?php
session_start();
include 'koneksi.php';

// Cek Login
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'pembeli') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['upload'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $id_user = $_SESSION['id_user'];

    // Persiapan File
    $nama_file = $_FILES['bukti_foto']['name'];
    $tmp_file = $_FILES['bukti_foto']['tmp_name'];
    $tipe_file = $_FILES['bukti_foto']['type'];
    $error = $_FILES['bukti_foto']['error'];

    // Validasi Ekstensi Gambar
    $ekstensi_valid = ['jpg', 'jpeg', 'png'];
    $ekstensi_file = strtolower(end(explode('.', $nama_file)));

    if (!in_array($ekstensi_file, $ekstensi_valid)) {
        echo "<script>alert('Yang diupload bukan gambar!'); window.location='keranjang.php';</script>";
        exit();
    }

    // Generate Nama Baru (Biar unik dan tidak bentrok)
    $nama_baru = "BUKTI-" . $id_pesanan . "-" . uniqid() . "." . $ekstensi_file;
    $tujuan = "uploads/" . $nama_baru;

    // Pastikan folder uploads ada
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Proses Upload
    if (move_uploaded_file($tmp_file, $tujuan)) {
        // Update Database
        $query = "UPDATE keranjang SET BUKTI_PEMBAYARAN = '$nama_baru' 
                  WHERE ID_PESANAN = '$id_pesanan' AND ID_USER = '$id_user'";
        
        mysqli_query($koneksi, $query);
        header("Location: keranjang.php?pesan=Bukti Berhasil Diupload");
    } else {
        echo "<script>alert('Gagal mengupload file.'); window.location='keranjang.php';</script>";
    }
}
?>