<?php
// FILE: proses_beli.php (UPDATED: AUTO DECREMENT STOCK)
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'pembeli') {
    echo "<script>alert('Akses Ditolak!'); window.location='login.php';</script>";
    exit();
}

if (isset($_POST['beli_sekarang'])) {
    $id_user = $_SESSION['id_user'];
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah_produk'];
    $status = 'Pending';
    
    // 1. CEK STOK TERLEBIH DAHULU (Validasi Backend)
    $cek_stok = mysqli_query($koneksi, "SELECT STOK_PRODUK FROM daftar_produk WHERE ID_PRODUK = '$id_produk'");
    $data_stok = mysqli_fetch_assoc($cek_stok);

    if ($data_stok['STOK_PRODUK'] < $jumlah) {
        echo "<script>alert('Stok habis atau tidak cukup!'); window.location='index.php';</script>";
        exit();
    }

    // 2. GENERATE NOMOR RESERVASI
    $nomor_reservasi = "RES-" . date("YmdHi") . "-" . $id_user . rand(100,999);

    // 3. MASUKKAN KE KERANJANG
    $query_insert = "INSERT INTO keranjang (ID_USER, ID_PRODUK, NOMOR_RESERVASI, JUMLAH_PRODUK, STATUS) 
                     VALUES ('$id_user', '$id_produk', '$nomor_reservasi', '$jumlah', '$status')";

    if (mysqli_query($koneksi, $query_insert)) {
        
        // --- LOGIKA BARU: KURANGI STOK ---
        // Rumus: Stok Sekarang = Stok Lama - Jumlah Beli
        $query_kurang_stok = "UPDATE daftar_produk 
                              SET STOK_PRODUK = STOK_PRODUK - $jumlah 
                              WHERE ID_PRODUK = '$id_produk'";
        
        mysqli_query($koneksi, $query_kurang_stok);
        // ---------------------------------

        echo "<script>alert('Barang berhasil ditambahkan ke keranjang!'); window.location='keranjang.php';</script>";
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
}
?>