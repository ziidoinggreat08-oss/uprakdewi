<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

 $id = $_GET['id'];

 $query = "DELETE FROM pesanan WHERE id = $id";

if ($conn->query($query) === TRUE) {
    $_SESSION['success'] = "Pesanan berhasil dihapus!";
} else {
    $_SESSION['error'] = "Error: " . $conn->error;
}

header("Location: admin.php");
?>