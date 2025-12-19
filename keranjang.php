<?php
session_start();
include 'koneksi.php';
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
        .btn-upload { background: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;}
        .total-row { font-weight: bold; background-color: #e9ecef; }
        .status-ok { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
    </style>
</head>
<body>

    <h2>🛒 Keranjang Belanja Anda</h2>
    <a href="index.php" class="btn-back">← Kembali Belanja</a>

    <?php if(isset($_GET['pesan'])) { echo "<p style='color:blue;'>" . htmlspecialchars($_GET['pesan']) . "</p>"; } ?>

    <table>
        <thead>
            <tr>
                <th>No. Reservasi</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Status & Bukti Bayar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT k.*, p.NAMA_PRODUK, p.HARGA_PRODUK 
                      FROM keranjang k 
                      JOIN daftar_produk p ON k.ID_PRODUK = p.ID_PRODUK 
                      WHERE k.ID_USER = '$id_user'
                      ORDER BY k.ID_PESANAN DESC"; 
            
            $result = mysqli_query($koneksi, $query);
            $grand_total = 0;

            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)) {
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
                    <?php if ($row['STATUS'] == 'Pending') { ?>
                        <div class="status-pending">⏳ Belum Lunas</div>
                        <br>
                        
                        <?php if (empty($row['BUKTI_PEMBAYARAN'])) { ?>
                            <form action="proses_upload.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id_pesanan" value="<?php echo $row['ID_PESANAN']; ?>">
                                <input type="file" name="bukti_foto" required style="font-size: 12px;">
                                <button type="submit" name="upload" class="btn-upload">Upload Bukti</button>
                            </form>
                        <?php } else { ?>
                            <small>✅ Bukti Terkirim</small><br>
                            <a href="uploads/<?php echo $row['BUKTI_PEMBAYARAN']; ?>" target="_blank">Lihat Foto</a>
                        <?php } ?>

                    <?php } else { ?>
                        <div class="status-ok">✅ Lunas / Dikirim</div>
                    <?php } ?>
                </td>

                <td>
                    <?php if ($row['STATUS'] == 'Pending') { ?>
                        <a href="hapus_keranjang.php?id=<?php echo $row['ID_PESANAN']; ?>" 
                        class="btn-hapus" 
                        onclick="return confirm('Batalkan pesanan ini?')">Batal</a>
                    <?php } else { ?>
                        -
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
                echo "<tr><td colspan='7' style='text-align:center;'>Keranjang kosong.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>