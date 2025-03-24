<?php
require "fungsi.php";

if (!isset($_GET['username']) || empty(trim($_GET['username']))) {
    echo "<script>alert('Error: Username tidak ditemukan.'); window.history.back();</script>";
    exit;
}

$username = trim($_GET['username']);

// Cek apakah user ada dalam database
$cekStmt = $koneksi->prepare("SELECT username FROM user WHERE username = ?");
$cekStmt->bind_param("s", $username);
$cekStmt->execute();
$cekResult = $cekStmt->get_result();

if ($cekResult->num_rows == 0) {
    echo "<script>alert('Error: User tidak ditemukan.'); window.location.href='cekDataKembarUser.php';</script>";
    exit;
}

$cekStmt->close();

// Hapus user
$deleteStmt = $koneksi->prepare("DELETE FROM user WHERE username = ?");
$deleteStmt->bind_param("s", $username);

if ($deleteStmt->execute()) {
    echo "<script>alert('User berhasil dihapus!'); window.location.href='cekDataKembarUser.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus user. Coba lagi!'); window.history.back();</script>";
}

$deleteStmt->close();
$koneksi->close();
