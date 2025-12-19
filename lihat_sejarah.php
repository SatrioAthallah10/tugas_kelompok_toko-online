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
    <title>Riwayat Pembayaran</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-back { background: #6c757d; color: white; text-decoration: none; padding: 10px 15px; border-radius: 4px; }
        .success-text { color: green; font-weight: bold; }
    </style>
</head>
<body>

    <h2>📜 Riwayat Pembayaran Anda</h2>
    <a href="index.php" class="btn-back">← Kembali Belanja</a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Reservasi</th>
                <th>Produk</th>
                <th>Catatan Sejarah (Dari Admin)</th>
                <th>Bukti Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // QUERY JOIN 3 TABEL SESUAI ERD
            // Kita ambil sejarah pembayaran, tapi difilter HANYA milik user yang sedang login
            $query = "SELECT sp.SEJARAH, k.NOMOR_RESERVASI, k.BUKTI_PEMBAYARAN, p.NAMA_PRODUK 
                      FROM sejarah_pembayaran sp
                      JOIN keranjang k ON sp.ID_PESANAN = k.ID_PESANAN
                      JOIN daftar_produk p ON k.ID_PRODUK = p.ID_PRODUK
                      WHERE k.ID_USER = '$id_user'
                      ORDER BY sp.ID_PEMBAYARAN DESC";
            
            $result = mysqli_query($koneksi, $query);
            $no = 1;

            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['NOMOR_RESERVASI']; ?></td>
                <td><?php echo $row['NAMA_PRODUK']; ?></td>
                
                <td class="success-text">
                    ✅ <?php echo $row['SEJARAH']; ?>
                </td>

                <td>
                    <?php if(!empty($row['BUKTI_PEMBAYARAN'])) { ?>
                        <a href="uploads/<?php echo $row['BUKTI_PEMBAYARAN']; ?>" target="_blank">Lihat Bukti</a>
                    <?php } else { echo "-"; } ?>
                </td>
            </tr>
            <?php 
                } 
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>Belum ada riwayat pembayaran yang dikonfirmasi.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>