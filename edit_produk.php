<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'pemilik') {
    echo "<script>alert('Akses Ditolak!'); window.location='login.php';</script>";
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: page_pemilik.php");
    exit;
}

$id = $_GET['id'];
$query_ambil = "SELECT * FROM daftar_produk WHERE ID_PRODUK = '$id'";
$result_ambil = mysqli_query($koneksi, $query_ambil);
$data = mysqli_fetch_assoc($result_ambil);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='page_pemilik.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $nama_baru = $_POST['nama_produk'];
    $harga_baru = $_POST['harga_produk'];
    $stok_baru = $_POST['stok_produk'];

    $query_update = "UPDATE daftar_produk SET 
                     NAMA_PRODUK = '$nama_baru', 
                     HARGA_PRODUK = '$harga_baru', 
                     STOK_PRODUK = '$stok_baru' 
                     WHERE ID_PRODUK = '$id'";

    $result_update = mysqli_query($koneksi, $query_update);

    if ($result_update) {
        echo "<script>alert('Data Berhasil Diubah!'); window.location='page_pemilik.php';</script>";
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        form { max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ccc; }
        input { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 10px;}
        button { padding: 10px 20px; background-color: #ffc107; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Edit Produk</h2>
    
    <form method="POST">
        <label>Nama Produk:</label>
        <input type="text" name="nama_produk" value="<?php echo $data['NAMA_PRODUK']; ?>" required>

        <label>Harga Produk:</label>
        <input type="number" name="harga_produk" value="<?php echo $data['HARGA_PRODUK']; ?>" required>

        <label>Stok Produk:</label>
        <input type="number" name="stok_produk" value="<?php echo $data['STOK_PRODUK']; ?>" required>

        <button type="submit" name="update">Update Data</button>
        <a href="page_pemilik.php">Batal</a>
    </form>
</body>
</html>