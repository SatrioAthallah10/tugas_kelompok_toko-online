<?php
session_start();
include 'koneksi.php';
if ($_SESSION['role'] != 'pemilik') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Pemilik</title>
</head>
<body>
    <h1>Selamat Datang, Pemilik Toko!</h1>
    
    <div style="margin-bottom: 20px;">
        <a href="tambah_produk.php">
            <button style="padding: 10px 20px; background-color: blue; color: white;">+ Tambah Barang Baru</button>
        </a>
    </div>

    <h3>Daftar Produk Saat Ini:</h3>
    <table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th> </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM daftar_produk";
        $result = mysqli_query($koneksi, $query);
        
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['ID_PRODUK'] . "</td>";
            echo "<td>" . $row['NAMA_PRODUK'] . "</td>";
            echo "<td>Rp " . number_format($row['HARGA_PRODUK'], 0, ',', '.') . "</td>";
            echo "<td>" . $row['STOK_PRODUK'] . "</td>";
            echo "<td>
                    <a href='edit_produk.php?id=" . $row['ID_PRODUK'] . "'>Edit</a> | 
                    <a href='hapus_produk.php?id=" . $row['ID_PRODUK'] . "' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

    <br>
    <a href="logout.php">Logout</a>
</body>
</html>