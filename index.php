<?php
include 'koneksi.php';
$query = "SELECT * FROM daftar_produk";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Toko Online Kelompok Kami</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

    <h1>Selamat Datang di Toko Komputer</h1>
    
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            // Looping data dari database
            while($row = mysqli_fetch_assoc($result)) { 
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['NAMA_PRODUK']; ?></td>
                <td>Rp <?php echo number_format($row['HARGA_PRODUK']); ?></td>
                <td><?php echo $row['STOK_PRODUK']; ?> pcs</td>
                <td>
                    <a href="beli.php?id=<?php echo $row['ID_PRODUK']; ?>" class="btn-beli">Beli Sekarang</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>