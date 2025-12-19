<?php
session_start();
include 'koneksi.php';

// Cek keamanan: Hanya Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; max-width: 800px; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-hapus { background-color: #ff4444; color: white; text-decoration: none; padding: 5px 10px; border-radius: 4px; }
        .btn-hapus:hover { background-color: #cc0000; }
    </style>
</head>
<body>

    <h1>Dashboard Admin</h1>
    <p>Halo, Admin! Berikut adalah daftar pengguna aplikasi.</p>

    <h3>Daftar Pengguna (Pemilik & Pembeli)</h3>
    
    <table>
        <thead>
            <tr>
                <th>ID User</th>
                <th>Username</th>
                <th>Role (Peran)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil semua user KECUALI admin yang sedang login (biar gak hapus diri sendiri)
            $id_saya = $_SESSION['id_user']; // Asumsi saat login kamu simpan id_user di session
            $query = "SELECT * FROM user WHERE ROLE != 'admin'"; 
            
            $result = mysqli_query($koneksi, $query);

            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['ID_USER'] . "</td>";
                    echo "<td>" . $row['USERNAME'] . "</td>";
                    echo "<td>" . ucfirst($row['ROLE']) . "</td>"; // ucfirst biar huruf depan besar
                    echo "<td>
                            <a href='hapus_user.php?id=" . $row['ID_USER'] . "' 
                               class='btn-hapus'
                               onclick='return confirm(\"Yakin ingin menghapus user " . $row['USERNAME'] . "? Semua riwayat belanja mereka juga akan hilang.\")'>
                               Hapus Akun
                            </a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center'>Belum ada user lain.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <hr>
    <h3>📋 Daftar Pesanan Masuk (Butuh ACC)</h3>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr style="background-color: #ddd;">
                <th>No. Reservasi</th>
                <th>Pembeli</th>
                <th>Barang (Jml)</th>
                <th>Total Harga</th>
                <th>Bukti Transfer</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query_pesanan = "SELECT k.*, u.USERNAME, p.NAMA_PRODUK, p.HARGA_PRODUK 
                            FROM keranjang k 
                            JOIN user u ON k.ID_USER = u.ID_USER 
                            JOIN daftar_produk p ON k.ID_PRODUK = p.ID_PRODUK 
                            WHERE k.STATUS = 'Pending' 
                            ORDER BY k.ID_PESANAN DESC";
            
            $result_pesanan = mysqli_query($koneksi, $query_pesanan);

            if (mysqli_num_rows($result_pesanan) > 0) {
                while ($row = mysqli_fetch_assoc($result_pesanan)) {
                    $total_bayar = $row['HARGA_PRODUK'] * $row['JUMLAH_PRODUK'];
            ?>
                <tr>
                    <td><?php echo $row['NOMOR_RESERVASI']; ?></td>
                    <td><?php echo $row['USERNAME']; ?></td>
                    <td><?php echo $row['NAMA_PRODUK'] . " (" . $row['JUMLAH_PRODUK'] . ")"; ?></td>
                    <td>Rp <?php echo number_format($total_bayar); ?></td>
                    
                    <td style="text-align: center;">
                        <?php if (!empty($row['BUKTI_PEMBAYARAN'])) { ?>
                            <a href="uploads/<?php echo $row['BUKTI_PEMBAYARAN']; ?>" target="_blank" 
                               style="background: orange; padding: 5px; color: black; text-decoration: none; border-radius: 4px;">
                                Lihat Bukti
                            </a>
                        <?php } else { ?>
                            <span style="color: red; font-style: italic;">Belum Upload</span>
                        <?php } ?>
                    </td>

                    <td>
                        <a href="acc_pembayaran.php?id=<?php echo $row['ID_PESANAN']; ?>" 
                        onclick="return confirm('Sudah cek bukti pembayaran?')"
                        style="background: blue; color: white; padding: 5px; text-decoration: none; border-radius: 4px;">
                        ✅ Verifikasi
                        </a>
                    </td>
                </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center'>Tidak ada pesanan baru.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <br>
    <a href="logout.php">Logout</a>

    <hr>
    <h3 style="color: darkred;">📩 Pesan User</h3>
    <p>Daftar pertanyaan user yang tidak ada di database keyword. Perlu respon manual.</p>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr style="background-color: #ffcccc;">
                <th>No</th>
                <th>Nama User</th>
                <th>Isi Pesan / Pertanyaan</th>
                <th>Waktu Masuk</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query_keluhan = "SELECT k.*, u.USERNAME 
                              FROM keluhan_user k 
                              JOIN user u ON k.ID_USER = u.ID_USER 
                              ORDER BY k.WAKTU_KIRIM DESC";
            
            $result_keluhan = mysqli_query($koneksi, $query_keluhan);
            $no_k = 1;

            if (mysqli_num_rows($result_keluhan) > 0) {
                while ($row_k = mysqli_fetch_assoc($result_keluhan)) {
            ?>
                <tr>
                    <td><?php echo $no_k++; ?></td>
                    <td><?php echo $row_k['USERNAME']; ?></td>
                    <td>"<?php echo $row_k['ISI_PESAN']; ?>"</td>
                    <td><?php echo $row_k['WAKTU_KIRIM']; ?></td>
                    <td>
                        <a href="hapus_keluhan.php?id=<?php echo $row_k['ID_KELUHAN']; ?>" 
                           onclick="return confirm('Sudah dibaca? Hapus pesan ini?')"
                           style="color: red;">
                           [Hapus]
                        </a>
                    </td>
                </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center'>Tidak ada pesan keluhan baru.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <br><br>

</body>
</html>