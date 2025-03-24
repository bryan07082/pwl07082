<?php
require "fungsi.php";
require "head.html";

$dataPerHalaman = isset($_GET['dataPerHalaman']) ? (int)$_GET['dataPerHalaman'] : 10;
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';

// Query untuk menghitung jumlah data
if ($cari) {
   $sql = "SELECT COUNT(*) FROM mhs WHERE nim LIKE ? OR nama LIKE ? OR email LIKE ?";
   $search = "%$cari%";
   $stmt = mysqli_prepare($koneksi, $sql);
   mysqli_stmt_bind_param($stmt, "sss", $search, $search, $search);
} else {
   $sql = "SELECT COUNT(*) FROM mhs";
   $stmt = mysqli_prepare($koneksi, $sql);
}

mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $jmlData);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$jmlHal = ceil($jmlData / $dataPerHalaman);
$halAktif = isset($_GET['hal']) ? (int)$_GET['hal'] : 1;
$awalData = ($dataPerHalaman * $halAktif) - $dataPerHalaman;

// Query untuk mengambil data sesuai pagination
if ($cari) {
   $sql = "SELECT * FROM mhs WHERE nim LIKE ? OR nama LIKE ? OR email LIKE ? LIMIT ?, ?";
   $stmt = mysqli_prepare($koneksi, $sql);
   mysqli_stmt_bind_param($stmt, "sssii", $search, $search, $search, $awalData, $dataPerHalaman);
} else {
   $sql = "SELECT * FROM mhs LIMIT ?, ?";
   $stmt = mysqli_prepare($koneksi, $sql);
   mysqli_stmt_bind_param($stmt, "ii", $awalData, $dataPerHalaman);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="id">

<head>
   <title>Daftar Mahasiswa</title>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="bootstrap533/css/bootstrap.min.css">
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   <script src="bootstrap533/js/bootstrap.bundle.min.js"></script>
</head>

<body>

   <div class="container mt-4">
      <h2 class="text-center">Daftar Mahasiswa</h2>
      <div class="text-center mb-3">
         <a href="cetakMhsmPdf.php" class="btn btn-secondary"><i class="fas fa-print"></i> Cetak</a>
         <a href="addMhs.php" class="btn btn-success">Tambah Data</a>
      </div>

      <!-- Form Pencarian -->
      <form action="" method="get" class="row g-2">
         <div class="col-md-6">
            <input type="text" name="cari" class="form-control" placeholder="Cari mahasiswa..." autofocus autocomplete="off" value="<?= htmlspecialchars($cari) ?>">
         </div>
         <div class="col-md-2">
            <button class="btn btn-primary" type="submit">Cari</button>
         </div>
         <div class="col-md-4 text-end">
            <select name="dataPerHalaman" class="form-select" onchange="this.form.submit()">
               <?php foreach ([5, 10, 30, 70, 100] as $option) : ?>
                  <option value="<?= $option ?>" <?= $dataPerHalaman == $option ? 'selected' : '' ?>><?= $option ?></option>
               <?php endforeach; ?>
            </select>
         </div>
      </form>

      <div id="container">
         <table class="table table-hover mt-3">
            <thead class="table-light">
               <tr>
                  <th>No.</th>
                  <th>NIM</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Foto</th>
                  <th>Aksi</th>
               </tr>
            </thead>
            <tbody>
               <?php if ($jmlData == 0) : ?>
                  <tr>
                     <td colspan="6" class="text-center">Data tidak ditemukan</td>
                  </tr>
                  <?php else :
                  $no = $awalData + 1;
                  while ($row = mysqli_fetch_assoc($result)) : ?>
                     <tr id="row-<?= $row['id'] ?>">
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row["nim"]) ?></td>
                        <td><?= htmlspecialchars($row["nama"]) ?></td>
                        <td><?= htmlspecialchars($row["email"]) ?></td>
                        <td><img src="foto/<?= htmlspecialchars($row["foto"]) ?>" height="50"></td>
                        <td>
                           <a class="btn btn-outline-primary btn-sm" href="editMhs.php?kode=<?= $row['id'] ?>">Edit</a>
                           <button class="btn btn-outline-danger btn-sm delete-btn" data-id="<?= $row['id'] ?>">Hapus</button>
                        </td>
                     </tr>
               <?php endwhile;
               endif; ?>
            </tbody>
         </table>

         <!-- Pagination -->
         <?php if ($jmlHal > 1) : ?>
            <nav>
               <ul class="pagination justify-content-center">
                  <li class="page-item <?= ($halAktif == 1) ? 'disabled' : '' ?>">
                     <a class="page-link" href="?hal=<?= $halAktif - 1 ?>&cari=<?= $cari ?>&dataPerHalaman=<?= $dataPerHalaman ?>">Previous</a>
                  </li>

                  <?php for ($i = 1; $i <= $jmlHal; $i++) : ?>
                     <li class="page-item <?= ($i == $halAktif) ? 'active' : '' ?>">
                        <a class="page-link" href="?hal=<?= $i ?>&cari=<?= $cari ?>&dataPerHalaman=<?= $dataPerHalaman ?>"><?= $i ?></a>
                     </li>
                  <?php endfor; ?>

                  <li class="page-item <?= ($halAktif == $jmlHal) ? 'disabled' : '' ?>">
                     <a class="page-link" href="?hal=<?= $halAktif + 1 ?>&cari=<?= $cari ?>&dataPerHalaman=<?= $dataPerHalaman ?>">Next</a>
                  </li>
               </ul>
            </nav>
         <?php endif; ?>
      </div>
   </div>

   <script>
      $(document).ready(function() {
         $('.delete-btn').on('click', function() {
            let id = $(this).data('id');
            if (confirm('Yakin ingin menghapus?')) {
               $.ajax({
                  url: 'hpsMhs.php?kode=' + id,
                  type: 'GET',
                  success: function(response) {
                     $('#row-' + id).fadeOut();
                  },
                  error: function() {
                     alert('Gagal menghapus data');
                  }
               });
            }
         });
      });
   </script>

</body>

</html>