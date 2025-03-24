<!DOCTYPE html>
<html lang="id">

<head>
	<title>Sistem Informasi Manajemen User::Tambah Data User</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap533/css/bootstrap.min.css">
	<script src="bootstrap533/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
	<?php require "head.html"; ?>

	<div class="container mt-4">
		<h2 class="text-center">TAMBAH DATA USER</h2>

		<div class="alert alert-success alert-dismissible fade show" id="success" style="display:none;">
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			Data berhasil ditambahkan!
		</div>

		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card shadow p-4 rounded">
					<form method="post" action="sv_addUser.php" enctype="multipart/form-data">
						<div class="mb-3">
							<label for="username" class="form-label">Username:</label>
							<input class="form-control" type="text" name="username" id="username" required>
						</div>
						<div class="mb-3">
							<label for="password" class="form-label">Password:</label>
							<input class="form-control" type="password" name="password" id="password" required>
						</div>
						<div class="mb-3">
							<label for="status" class="form-label">Status:</label>
							<select class="form-select" name="status" id="status">
								<option value="admin">Admin</option>
								<option value="user">User</option>
								<option value="mhs">Mhs</option>
								<option value="tu">TU</option>
							</select>
						</div>
						<div class="d-grid">
							<button type="submit" class="btn btn-primary">Simpan</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>

</html>