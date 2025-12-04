<?php
session_start();
include 'koneksi.php';

if (isset($_POST['btn_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $username = mysqli_real_escape_string($koneksi, $username);
    $password = mysqli_real_escape_string($koneksi, $password);

    $query = "SELECT * FROM user WHERE USERNAME = '$username' AND PASSWORD = '$password'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        $_SESSION['username'] = $username;
        $_SESSION['role'] = $data['role'];
        $_SESSION['id_user'] = $data['ID_USER'];
        $_SESSION['status'] = "login";

        if ($data['role'] == "admin") {
            header("Location: page_admin.php");
            
        } else if ($data['role'] == "pemilik") {
            header("Location: page_pemilik.php");
            
        } else if ($data['role'] == "pembeli") {
            header("Location: index.php");
            
        } else {
            echo "Role tidak dikenali!";
        }

    } else {
        echo "<script>alert('Username atau Password Salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Toko</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-box { width: 300px; margin: 100px auto; border: 1px solid #ccc; padding: 20px; border-radius: 5px; }
        input { width: 100%; padding: 10px; margin: 5px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: blue; color: white; border: none; }
    </style>
</head>
<body>

    <div class="login-box">
        <h2 style="text-align:center;">Login User</h2>
        <form method="POST" action="">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required>
            
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
            
            <button type="submit" name="btn_login">MASUK</button>
        </form>
    </div>

</body>
</html>