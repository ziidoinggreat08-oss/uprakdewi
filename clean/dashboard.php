<?php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: login.php") && exit();

require_once __DIR__ . '/koneksi.php';

 $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
 $stmt->bind_param("i", $_SESSION['user_id']);
 $stmt->execute();
 $user = $stmt->get_result()->fetch_assoc();


 $stmt = $conn->prepare("SELECT * FROM pesanan WHERE user_id = ? ORDER BY id DESC");
 $stmt->bind_param("i", $_SESSION['user_id']);
 $stmt->execute();
 $pesanan_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Cleaning Service Panggilan</h1>
            <p>Selamat datang, <?= $user['username']; ?>!</p>
        </div>
    </header>
    
    <nav>
        <div class="container">
            <ul>
                <?php if ($user['role'] == 'admin'): ?>
                    <li><a href="admin.php">Admin Panel</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <div class="card">
            <h2>Pesan Layanan Cleaning</h2>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            
            <form action="proses_pesanan.php?action=pesan" method="post">
                <div class="form-group">
                    <label for="nama_layanan">Jenis Layanan:</label>
                    <select id="nama_layanan" name="nama_layanan" required>
                        <option value="">Pilih Layanan</option>
                        <option value="Cleaning Rumah">Cleaning Rumah (200.000 ribu)</option>
                        <option value="Cleaning Apartemen">Cleaning Apartemen (250.000 ribu)</option>
                        <option value="Cleaning Kantor">Cleaning Kantor (300.000 ribu)</option>
                        <option value="Cleaning Pascabanjir">Cleaning Pasca banjir (350.000)</option>
                        <option value="Cleaning Gudang">Cleaning Gudang (400.000)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="alamat">Alamat Lengkap:</label>
                    <textarea id="alamat" name="alamat" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="tanggal">Tanggal:</label>
                    <input type="date" id="tanggal" name="tanggal" required>
                </div>
                
                <div class="form-group">
                    <label for="jam">Jam:</label>
                    <input type="time" id="jam" name="jam" required>
                </div>
                
                <div class="form-group">
                    <label for="catatan">Catatan Tambahan:</label>
                    <textarea id="catatan" name="catatan" rows="2"></textarea>
                </div>
                
                <button type="submit" class="btn">Pesan Sekarang</button>
            </form>
        </div>
        
        <div class="card">
            <h2>Riwayat Pesanan Anda</h2>
            
            <?php if ($pesanan_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Layanan</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $pesanan_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td><?= $row['nama_layanan']; ?></td>
                                <td><?= $row['tanggal']; ?></td>
                                <td><?= $row['jam']; ?></td>
                                <td><?= $row['status']; ?></td>
                                <td>
                                    <?php if ($row['status'] == 'menunggu'): ?>
                                        <a href="edit_pesanan_user.php?id=<?= $row['id']; ?>" class="btn">Edit</a>
                                        <a href="proses_pesanan.php?action=hapus_user&id=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin membatalkan dan menghapus pesanan ini?')">Batalkan</a>
                                    <?php else: ?>
                                        <span>Tidak bisa diubah</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Belum ada pesanan yang dibuat.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>