<?php
require "fungsi.php";

if (!isset($_GET['username'])) {
	die("Error: Username tidak ditemukan.");
}

$username = $_GET['username'];

// Ambil data user
$stmt = $koneksi->prepare("SELECT username, status FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
	die("Error: Data user tidak ditemukan.");
}

$row = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap533/css/bootstrap.min.css">
</head>

<body>
	<div class="container mt-4">
		<h2>Edit User</h2>
		<form method="post" action="sv_editUser.php">
			<input type="hidden" name="old_username" value="<?php echo htmlspecialchars($row['username']); ?>">

			<div class="mb-3">
				<label>Username:</label>
				<input class="form-control" type="text" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
			</div>
			<div class="mb-3">
				<label>Password (Kosongkan jika tidak ingin mengubah):</label>
				<input class="form-control" type="password" name="password">
			</div>
			<div class="mb-3">
				<label>Status:</label>
				<input class="form-control" type="text" name="status" value="<?php echo htmlspecialchars($row['status']); ?>" required>
			</div>
			<button class="btn btn-primary" type="submit">Simpan</button>
		</form>
	</div>
</body>

</html>