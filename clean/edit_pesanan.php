<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

 $id = $_GET['id'];


 $query = "SELECT * FROM pesanan WHERE id = $id";
 $result = $conn->query($query);
 $pesanan = $result->fetch_assoc();

if (!$pesanan) {
    $_SESSION['error'] = "Pesanan tidak ditemukan!";
    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Edit Pesanan</h1>
        </div>
    </header>
    
    <nav>
        <div class="container">
            <ul>
                <li><a href="admin.php">Kembali ke Admin Panel</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <div class="card">
            <h2>Edit Pesanan #<?php echo $pesanan['id']; ?></h2>
            
            <form action="proses_pesanan.php?action=edit" method="post">
                <div class="form-group">
                    <label for="nama_layanan">Jenis Layanan:</label>
                    <select id="nama_layanan" name="nama_layanan" required>
                        <option value="Cleaning Rumah" <?php echo ($pesanan['nama_layanan'] == 'Cleaning Rumah') ? 'selected' : ''; ?>>Cleaning Rumah</option>
                        <option value="Cleaning Apartemen" <?php echo ($pesanan['nama_layanan'] == 'Cleaning Apartemen') ? 'selected' : ''; ?>>Cleaning Apartemen</option>
                        <option value="Cleaning Kantor" <?php echo ($pesanan['nama_layanan'] == 'Cleaning Kantor') ? 'selected' : ''; ?>>Cleaning Kantor</option>
                        <option value="Cleaning Pascabanjir" <?php echo ($pesanan['nama_layanan'] == 'Cleaning Pascabanjir') ? 'selected' : ''; ?>>Cleaning Pascabanjir</option>
                        <option value="Cleaning Gudang" <?php echo ($pesanan['nama_layanan'] == 'Cleaning Gudang') ? 'selected' : ''; ?>>Cleaning Gudang</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap:</label>
                    <textarea id="alamat" name="alamat" rows="3" required><?php echo $pesanan['alamat']; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="tanggal">Tanggal:</label>
                    <input type="date" id="tanggal" name="tanggal" value="<?php echo $pesanan['tanggal']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="jam">Jam:</label>
                    <input type="time" id="jam" name="jam" value="<?php echo $pesanan['jam']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="catatan">Catatan Tambahan:</label>
                    <textarea id="catatan" name="catatan" rows="2"><?php echo $pesanan['catatan']; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="menunggu" <?php echo ($pesanan['status'] == 'menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                        <option value="diproses" <?php echo ($pesanan['status'] == 'diproses') ? 'selected' : ''; ?>>Diproses</option>
                        <option value="selesai" <?php echo ($pesanan['status'] == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                        <option value="dibatalkan" <?php echo ($pesanan['status'] == 'dibatalkan') ? 'selected' : ''; ?>>Dibatalkan</option>
                    </select>
                </div>
                
                
                <input type="hidden" name="id" value="<?php echo $pesanan['id']; ?>">
                
                <button type="submit" class="btn">Update Pesanan</button>
            </form>
        </div>
    </div>
</body>
</html>