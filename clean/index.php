<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cleaning Service Panggilan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Cleaning Service Panggilan</h1>
            <p>Layanan cleaning profesional untuk rumah dan kantor Anda</p>
        </div>
    </header>
    
    <nav>
        <div class="container">
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Daftar</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <div class="card">
            <h2>Selamat Datang di Cleaning Service Kami</h2>
            <p>Kami menyediakan layanan cleaning service panggilan untuk:</p>
            <ul>
                <li>Rumah Tinggal</li>
                <li>Apartemen</li>
                <li>Kantor</li>
                <li>Ruko</li>
            </ul>
            <p>Silakan <a href="register.php">daftar</a> atau <a href="login.php">login</a> untuk memesan layanan kami.</p>
        </div>
        
        <div class="card">
            <h3>Cara Pemesanan:</h3>
            <ol>
                <li>Daftar akun atau login jika sudah punya akun</li>
                <li>Pilih jenis layanan yang Anda butuhkan</li>
                <li>Isi alamat lengkap dan waktu yang diinginkan</li>
                <li>Tambahkan catatan khusus jika perlu</li>
                <li>Kirim pesanan dan tunggu konfirmasi dari admin</li>
                <li>untuk pembayaran bisa diberikan ketika pelayanan sudah selesai</li>
            </ol>
        </div>
    </div>
</body>
</html>