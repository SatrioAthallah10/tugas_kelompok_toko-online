<?php
session_start();
include 'koneksi.php';

// Cek apakah user sudah login, jika belum lempar ke login
// Opsional: Hapus blok IF ini jika user boleh lihat barang tanpa login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

// Ambil data produk
$query = "SELECT * FROM daftar_produk";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Toko Online - Halaman Pembeli</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        /* CSS Sederhana untuk Chatbot */
        .chat-container { border: 2px solid #333; padding: 20px; width: 60%; margin: 20px auto; background: #fff; border-radius: 8px; }
        .bot-response { background-color: #e6f7ff; padding: 10px; border: 1px solid #1890ff; margin-top: 10px; border-radius: 5px; }
        .user-label { font-weight: bold; color: #555; }
    </style>
</head>
<body>

    <div style="text-align:center; padding: 20px;">
    <h1>Halo, <?php echo $_SESSION['username']; ?>! (Pembeli)</h1>
    
    <a href="keranjang.php" style="
        background-color: #007bff; 
        color: white; 
        padding: 10px 15px; 
        text-decoration: none; 
        border-radius: 5px; 
        margin-right: 10px; 
        font-weight: bold;">
        🛒 Lihat Keranjang
    </a>

    <a href="logout.php" style="
        background-color: #dc3545;
        color: white; 
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;">
        Logout
    </a>
</div>

    <!-- DAFTAR PRODUK -->
    <h2 style="text-align:center;">Daftar Produk</h2>
    <table border="1" style="width: 80%; margin: 0 auto;">
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
    if($result && mysqli_num_rows($result) > 0){
        $no = 1;
        while($row = mysqli_fetch_assoc($result)) { 
    ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $row['NAMA_PRODUK']; ?></td>
        <td>Rp <?php echo number_format($row['HARGA_PRODUK']); ?></td>
        <td><?php echo $row['STOK_PRODUK']; ?> pcs</td>
        
        <td>
            <form action="proses_beli.php" method="POST">
                <input type="hidden" name="id_produk" value="<?php echo $row['ID_PRODUK']; ?>">
                
                <input type="number" name="jumlah_produk" value="1" min="1" max="<?php echo $row['STOK_PRODUK']; ?>" style="width: 50px; text-align: center;">
                
                <button type="submit" name="beli_sekarang" style="background-color: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer;">Beli</button>
            </form>
        </td>
    </tr>
    <?php 
        }
    } else {
        echo "<tr><td colspan='5' style='text-align:center;'>Belum ada produk</td></tr>";
    }
    ?>
</tbody>
    </table>

    <br><hr><br>

    <!-- FITUR CHAT ASSISTANT (DATABASE BASED) -->
    <div class="chat-container">
        <h3>💬 Tanya Asisten Toko</h3>
        <p><i>Coba ketik kata kunci: "paket", "lama", "resi", atau "halo"</i></p>
        
        <form method="POST" action="">
            <input type="text" name="pertanyaan" placeholder="Ketik pesan kamu..." style="width: 70%; padding: 10px;" required>
            <button type="submit" name="btn_chat" style="padding: 10px; background: #28a745; color: white; border: none;">Kirim Pesan</button>
        </form>

        <?php
        if (isset($_POST['btn_chat'])) {
            $input_user = $_POST['pertanyaan'];
            $input_user_aman = mysqli_real_escape_string($koneksi, $input_user);

            // LOGIKA PENCARIAN KEYWORD
            // Mencari apakah di kalimat user mengandung kata kunci dari database
            $query_bot = "SELECT JAWABAN FROM BOT 
                          WHERE '$input_user_aman' LIKE CONCAT('%', KATA_KUNCI, '%') 
                          LIMIT 1";
            
            $result_bot = mysqli_query($koneksi, $query_bot);

            echo "<div class='bot-response'>";
            echo "<span class='user-label'>Kamu:</span> " . htmlspecialchars($input_user) . "<br><br>";
            
            if ($result_bot && mysqli_num_rows($result_bot) > 0) {
                $data_bot = mysqli_fetch_assoc($result_bot);
                echo "<span class='user-label' style='color:blue;'>Bot:</span> " . $data_bot['JAWABAN'];
            } else {
                echo "<span class='user-label' style='color:blue;'>Bot:</span> Maaf, saya belum mengerti. Silakan hubungi admin manual.";
            }
            echo "</div>";
        }
        ?>
    </div>

</body>
</html>