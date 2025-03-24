<?php
require "fungsi.php";
require "head.html"; // Menyertakan header agar tampilan seragam

$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$dataPerHalaman = isset($_GET['dataPerHalaman']) ? (int)$_GET['dataPerHalaman'] : 10;
$halAktif = isset($_GET['hal']) ? (int)$_GET['hal'] : 1;
$offset = ($halAktif - 1) * $dataPerHalaman;

// Query data user
$query = "SELECT iduser, username, status FROM user WHERE username LIKE ? LIMIT ?, ?";
$cariLike = "%$cari%";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("sii", $cariLike, $offset, $dataPerHalaman);
$stmt->execute();
$hasil = $stmt->get_result();

// Query total data untuk pagination
$totalQuery = "SELECT COUNT(*) as total FROM user WHERE username LIKE ?";
$stmtTotal = $koneksi->prepare($totalQuery);
$stmtTotal->bind_param("s", $cariLike);
$stmtTotal->execute();
$resultTotal = $stmtTotal->get_result();
$totalData = $resultTotal->fetch_assoc()['total'];
$jmlHal = ceil($totalData / $dataPerHalaman);

$stmtTotal->close();
?>

<div class="container mt-4">
    <h2 class="text-center">CEK DATA USER</h2>

    <form action="" method="get" class="d-flex mb-3">
        <input class="form-control me-2" type="text" name="cari" placeholder="Cari user..." value="<?php echo htmlspecialchars($cari); ?>">
        <button class="btn btn-primary" type="submit">Cari</button>
    </form>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID User</th>
                <th>Username</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($hasil->num_rows == 0) { ?>
                <tr>
                    <td colspan="4" class="text-center alert alert-info">Data tidak ditemukan</td>
                </tr>
                <?php } else {
                while ($row = $hasil->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["iduser"]); ?></td>
                        <td><?php echo htmlspecialchars($row["username"]); ?></td>
                        <td><?php echo htmlspecialchars($row["status"]); ?></td>
                        <td>
                            <a href="editUser.php?username=<?php echo urlencode($row["username"]); ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="hpsUser.php?username=<?php echo urlencode($row["username"]); ?>" class="btn btn-danger btn-sm" onclick="confirmDelete('<?php echo $row["username"]; ?>')">Hapus</a>
                        </td>
                    </tr>
            <?php }
            } ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if ($jmlHal > 1) { ?>
        <nav>
            <ul class="pagination justify-content-center">
                <?php if ($halAktif > 1) { ?>
                    <li class="page-item"><a class="page-link" href="?hal=<?php echo $halAktif - 1; ?>&cari=<?php echo urlencode($cari); ?>">Previous</a></li>
                <?php }
                for ($i = 1; $i <= $jmlHal; $i++) { ?>
                    <li class="page-item <?php echo ($i == $halAktif) ? 'active' : ''; ?>">
                        <a class="page-link" href="?hal=<?php echo $i; ?>&cari=<?php echo urlencode($cari); ?>"> <?php echo $i; ?> </a>
                    </li>
                <?php }
                if ($halAktif < $jmlHal) { ?>
                    <li class="page-item"><a class="page-link" href="?hal=<?php echo $halAktif + 1; ?>&cari=<?php echo urlencode($cari); ?>">Next</a></li>
                <?php } ?>
            </ul>
        </nav>
    <?php } ?>
</div>

<script>
    function confirmDelete(username) {
        if (confirm("Apakah Anda yakin ingin menghapus user " + username + "?")) {
            window.location.href = "hapusUser.php?username=" + encodeURIComponent(username);
        }
    }
</script>