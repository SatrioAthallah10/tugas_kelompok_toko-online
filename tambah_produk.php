<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'pemilik') {
    echo "<script>alert('Akses Ditolak! Anda bukan Pemilik.'); window.location='login.php';</script>";
    exit;
}

if (isset($_POST['simpan'])) {
    $nama_produk = $_POST['nama_produk'];
    $harga_produk = $_POST['harga_produk'];
    $stok_produk = $_POST['stok_produk'];
    $query = "INSERT INTO daftar_produk (NAMA_PRODUK, HARGA_PRODUK, STOK_PRODUK) 
              VALUES ('$nama_produk', '$harga_produk', '$stok_produk')";
    
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        echo "<script>alert('Produk berhasil ditambahkan!'); window.location='page_pemilik.php';</script>";
    } else {
        echo "Gagal menambahkan produk: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk - Halaman Pemilik</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        form { max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ccc; }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 15px; padding: 10px 20px; background-color: #28a745; color: white; border: none; cursor: pointer; }
        .back-link { display: block; margin-top: 10px; text-align: center; }
    </style>
</head>
<body>

    <h2 style="text-align: center;">Tambah Barang Baru</h2>
    
    <form method="POST" action="">
        <label for="nama">Nama Produk:</label>
        <input type="text" name="nama_produk" id="nama" required placeholder="Contoh: Laptop Gaming">

        <label for="harga">Harga Produk (Rp):</label>
        <input type="number" name="harga_produk" id="harga" required placeholder="Contoh: 5000000">

        <label for="stok">Stok Produk:</label>
        <input type="number" name="stok_produk" id="stok" required placeholder="Contoh: 10">

        <button type="submit" name="simpan">Simpan Produk</button>
        
        <a href="page_pemilik.php" class="back-link">Kembali ke Dashboard</a>
    </form>

</body>
</html>