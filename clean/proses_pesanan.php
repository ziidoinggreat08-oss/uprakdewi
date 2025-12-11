<?php
session_start();
require_once __DIR__ . '/koneksi.php';

 $action = $_GET['action'] ?? 'none';

function executeQuery($conn, $query, $types, ...$params) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    return $stmt->execute() ? true : $stmt->error;
}

switch ($action) {
    case 'register':
        if ($_POST['password'] !== $_POST['confirm_password']) {
            $_SESSION['error'] = "Password tidak cocok!";
            exit(header("Location: register.php"));
        }
        
        $success = executeQuery($conn, "INSERT INTO users (username, password) VALUES (?, ?)", 
            "ss", $_POST['username'], password_hash($_POST['password'], PASSWORD_DEFAULT));
        
        $_SESSION[$success ? 'success' : 'error'] = $success ? 
            "Registrasi berhasil!" : "Error: " . $conn->error;
        header("Location: login.php");
        break;
        
    case 'login':
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $_POST['username']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION = array_merge($_SESSION, [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ]);
            header("Location: " . ($user['role'] == 'admin' ? 'admin.php' : 'dashboard.php'));
        } else {
            $_SESSION['error'] = "Username/password salah!";
            header("Location: login.php");
        }
        break;
        
    case 'pesan':
        if (!isset($_SESSION['user_id'])) exit(header("Location: login.php"));
        
        $success = executeQuery($conn, 
            "INSERT INTO pesanan (user_id, nama_layanan, alamat, tanggal, jam, catatan) VALUES (?, ?, ?, ?, ?, ?)",
            "isssss", $_SESSION['user_id'], ...array_values($_POST));
        
        $_SESSION[$success ? 'success' : 'error'] = $success ? 
            "Pesanan berhasil!" : "Error: " . $conn->error;
        header("Location: dashboard.php");
        break;
        
    case 'edit':
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') 
            exit(header("Location: login.php"));
        
        $success = executeQuery($conn, 
            "UPDATE pesanan SET nama_layanan=?, alamat=?, tanggal=?, jam=?, catatan=?, status=? WHERE id=?",
            "ssssssi", ...array_values($_POST));
        
        $_SESSION[$success ? 'success' : 'error'] = $success ? 
            "Pesanan diperbarui!" : "Error: " . $conn->error;
        header("Location: admin.php");
        break;
        
    case 'edit_user':
        if (!isset($_SESSION['user_id'])) 
            exit(header("Location: login.php"));
        
        $stmt = $conn->prepare("SELECT user_id FROM pesanan WHERE id = ?");
        $stmt->bind_param("i", $_POST['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $pesanan = $result->fetch_assoc();
        
        if (!$pesanan || $pesanan['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Anda tidak memiliki akses untuk mengedit pesanan ini!";
            header("Location: dashboard.php");
            exit;
        }
        
        $success = executeQuery($conn, 
            "UPDATE pesanan SET nama_layanan=?, alamat=?, tanggal=?, jam=?, catatan=? WHERE id=?",
            "sssssi", $_POST['nama_layanan'], $_POST['alamat'], $_POST['tanggal'], $_POST['jam'], $_POST['catatan'], $_POST['id']);
        
        $_SESSION[$success ? 'success' : 'error'] = $success ? 
            "Pesanan berhasil diperbarui!" : "Error: " . $conn->error;
        header("Location: dashboard.php");
        break;
        
    case 'hapus_user':
        if (!isset($_SESSION['user_id'])) 
            exit(header("Location: login.php"));
        
        $id = $_GET['id'];
        
        $stmt = $conn->prepare("SELECT user_id, status FROM pesanan WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $pesanan = $result->fetch_assoc();
        
        if (!$pesanan || $pesanan['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Anda tidak memiliki akses untuk menghapus pesanan ini!";
            header("Location: dashboard.php");
            exit;
        }
        
        if ($pesanan['status'] != 'menunggu') {
            $_SESSION['error'] = "Pesanan tidak bisa dihapus karena status sudah " . $pesanan['status'];
            header("Location: dashboard.php");
            exit;
        }
        
        $success = executeQuery($conn, "DELETE FROM pesanan WHERE id = ?", "i", $id);
        
        $_SESSION[$success ? 'success' : 'error'] = $success ? 
            "Pesanan berhasil dibatalkan dan dihapus!" : "Error: " . $conn->error;
        header("Location: dashboard.php");
        break;
        
    default:
        header("Location: index.php");
}

 $conn->close();
?>