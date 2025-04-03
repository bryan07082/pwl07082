<?php
session_start();
require "fungsi.php"; // Pastikan koneksi ke database sudah benar

// Jika sudah login, langsung redirect ke home
if (isset($_SESSION['username'])) {
	header("location:homeAdmin.php");
	exit;
}

// Inisialisasi variabel untuk menampilkan error
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = trim($_POST['username']);
	$passw = trim($_POST['password']);

	if (!empty($username) && !empty($passw)) {
		// Gunakan prepared statement untuk menghindari SQL Injection
		$sql1 = "SELECT * FROM user WHERE username = ?";
		$stmt = $koneksi->prepare($sql1);
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows == 1) {
			$user1 = $result->fetch_assoc();
			// Verifikasi password dengan password_verify
			if (password_verify($passw, $user1['password'])) {
				$_SESSION['username'] = $user1['username'];
				$_SESSION['status'] = $user1['status']; // Simpan status user
				header("Location: homeAdmin.php");
				exit();
			} else {
				$error = "Password salah.";
			}
		} else {
			$error = "User tidak ditemukan.";
		}

		$stmt->close();
	} else {
		$error = "Username dan password wajib diisi.";
	}
}
$koneksi->close();
?>

<!DOCTYPE html>
<html>

<head>
	<title>Login Sistem</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap-5.3.3-dist/css/bootstrap.css">
	<script src="bootstrap-5.3.3-dist/js/bootstrap.js"></script>
	<script src="bootstrap-5.3.3-dist/jquery/jquery-3.7.1.min.js"></script>
</head>

<body>
	<div class="container">
		<div class="w-25 mx-auto text-center mt-5">
			<div class="card bg-dark text-light">
				<div class="card-body">
					<h2 class="card-title">LOGIN</h2>
					<?php if (!empty($error)) {
						echo "<div class='alert alert-danger'>$error</div>";
					} ?>
					<form method="post" action="">
						<div class="form-group">
							<label for="username">Nama user</label>
							<input class="form-control" type="text" name="username" id="username" required>
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input class="form-control" type="password" name="password" id="password" required>
						</div>
						<div class="mt-3">
							<button class="btn btn-info" type="submit">Login</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>

</html>