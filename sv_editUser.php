<?php
require "fungsi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$oldUsername = $_POST["old_username"] ?? null;
	$newUsername = $_POST["username"] ?? null;
	$password = $_POST["password"] ?? null;
	$status = $_POST["status"] ?? null;

	if (empty($oldUsername) || empty($newUsername) || empty($status)) {
		die("Error: Data tidak boleh kosong.");
	}

	// Cek apakah username baru sudah digunakan oleh user lain
	$cekStmt = $koneksi->prepare("SELECT username FROM user WHERE username = ? AND username != ?");
	$cekStmt->bind_param("ss", $newUsername, $oldUsername);
	$cekStmt->execute();
	$cekResult = $cekStmt->get_result();

	if ($cekResult->num_rows > 0) {
		echo "<script>alert('Username sudah digunakan!'); window.history.back();</script>";
		exit;
	}

	$cekStmt->close();

	$query = "UPDATE user SET username = ?, status = ? WHERE username = ?";
	$stmt = $koneksi->prepare($query);
	$stmt->bind_param("sss", $newUsername, $status, $oldUsername);
	$stmt->execute();

	echo "<script>alert('Data berhasil diperbarui!'); window.location.href='cekDataKembarUser.php';</script>";

	$stmt->close();
	$koneksi->close();
}
