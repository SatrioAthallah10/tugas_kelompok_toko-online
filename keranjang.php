<?php
session_start();
include 'koneksi.php';

// 1. Cek Login Pembeli
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'pembeli') {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Belanja</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-hapus { background: #dc3545; color: white; text-decoration: none; padding: 5px 10px; border-radius: 4px; }
        .btn-back { background: #6c757d; color: white; text-decoration: none; padding: 10px 15px; border-radius: 4px; }
        .total-row { font-weight: bold; background-color: #e9ecef; }
    </style>
</head>
<body>

    <h2>🛒 Keranjang Belanja Anda</h2>
    <a href="index.php" class="btn-back">← Kembali Belanja</a>

    <table>
        <thead>
            <tr>
                <th>No. Reservasi</th>
                <th>Nama Produk</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // QUERY PENTING: JOIN TABLE
            // Kita ambil data dari 'keranjang' (k) dan gabungkan dengan 'daftar_produk' (p)
            // Kuncinya ada di: ON k.ID_PRODUK = p.ID_PRODUK
            $query = "SELECT k.*, p.NAMA_PRODUK, p.HARGA_PRODUK 
                      FROM keranjang k 
                      JOIN daftar_produk p ON k.ID_PRODUK = p.ID_PRODUK 
                      WHERE k.ID_USER = '$id_user'
                      ORDER BY k.ID_PESANAN DESC"; // Barang terbaru di atas
            
            $result = mysqli_query($koneksi, $query);
            $grand_total = 0;

            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)) {
                    // Hitung Subtotal per baris
                    $subtotal = $row['HARGA_PRODUK'] * $row['JUMLAH_PRODUK'];
                    $grand_total += $subtotal;
            ?>
            <tr>
                <td><small><?php echo $row['NOMOR_RESERVASI']; ?></small></td>
                <td><?php echo $row['NAMA_PRODUK']; ?></td>
                <td>Rp <?php echo number_format($row['HARGA_PRODUK']); ?></td>
                <td style="text-align: center;"><?php echo $row['JUMLAH_PRODUK']; ?></td>
                <td>Rp <?php echo number_format($subtotal); ?></td>
                <td>
                    <?php 
                    if ($row['STATUS'] == 'Pending') {
                        echo "<span style='color: orange; font-weight: bold;'>⏳ Menunggu Konfirmasi</span>";
                    } else {
                        echo "<span style='color: green; font-weight: bold;'>✅ Sudah Dibayar</span>";
                    }
                    ?>
                </td>
                <td>
                    <?php if ($row['STATUS'] == 'Pending') { ?>
                        <a href="hapus_keranjang.php?id=<?php echo $row['ID_PESANAN']; ?>" 
                        class="btn-hapus" 
                        onclick="return confirm('Batalkan pesanan ini?')">Batal</a>
                    <?php } else { ?>
                        <span style="color: gray; font-style: italic;">Selesai</span>
                    <?php } ?>
                </td>
            </tr>
            <?php 
                } 
            ?>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">Total Belanja:</td>
                <td colspan="3">Rp <?php echo number_format($grand_total); ?></td>
            </tr>
            <?php
            } else {
                echo "<tr><td colspan='7' style='text-align:center;'>Keranjang masih kosong. Yuk belanja!</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>