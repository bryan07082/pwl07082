<?php
include "fungsi.php"; // Pastikan koneksi ke database

// Cek apakah form telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $status = $_POST['status'];

    // Query simpan ke database (tanpa kolom foto)
    $sql = "INSERT INTO user (username, password, status) VALUES ('$username', '$password', '$status')";

    if (mysqli_query($koneksi, $sql)) {
        echo "Data berhasil disimpan.";
        require "addUser.php"; // Kembali ke form tambah user
    } else {
        echo "Data gagal disimpan: " . mysqli_error($koneksi);
    }
}
