<?php
include "../db.php";
include "../filelog.php";
if (empty($_SESSION['loginpelanggan'])) {
  header("Location: ../index.php");
}
$page = "dashboard";
if (isset($_POST['edit'])) {
  $id = $_POST['id'];
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $tlp = $_POST['tlp'];
  $queryUpdateUser = "UPDATE tb_pelanggan SET `nama` = '$nama', `alamat` = '$alamat', `jenis_kelamin` = '$jenis_kelamin', `tlp` = '$tlp' WHERE `tb_pelanggan`.`id` = $id";
  $execUpdateUser = mysqli_query($conn, $queryUpdateUser);
  if ($execUpdateUser) {
    $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Mengubah profile.";
    logger($log, "../../../../../");
    echo "<script>alert('Berhasil update profile'); window.location.href='profile_pelanggan.php';</script>";
  }
}
if (isset($_POST['ubahPassword'])) {
  $id = $_POST['id'];
  $passLama = $_POST['passLama'];
  $passBaru = $_POST['passBaru'];
  $passKonfir = $_POST['passKonfir'];
  // Ambil password lama dari database
  $queryGetPassword = "SELECT `password` FROM tb_pelanggan WHERE `id` = $id";
  $resultGetPassword = mysqli_query($conn, $queryGetPassword);
  $dataPassword = mysqli_fetch_assoc($resultGetPassword);
  // Verifikasi password lama
  if (password_verify($passLama, $dataPassword['password'])) {
    // Password lama cocok
    if ($passBaru == $passKonfir) {
      // Password baru dan konfirmasi password cocok
      $hashedPassBaru = password_hash($passBaru, PASSWORD_ARGON2I);
      $queryUpdatePassword = "UPDATE tb_pelanggan SET `password` = '$hashedPassBaru' WHERE `tb_pelanggan`.`id` = $id";
      $execUpdatePassword = mysqli_query($conn, $queryUpdatePassword);
      if ($execUpdatePassword) {
        $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Mengubah password.";
        logger($log, "../../../../../");
        echo "<script>alert('Password berhasil diubah'); window.location.href='profile_pelanggan.php';</script>";
      } else {
        echo "<script>alert('Gagal mengubah password'); window.location.href='profile_pelanggan.php';</script>";
      }
    } else {
      echo "<script>alert('Password baru dan konfirmasi password tidak cocok'); window.location.href='profile_pelanggan.php';</script>";
    }
  } else {
    echo "<script>alert('Password lama tidak cocok'); window.location.href='profile_pelanggan.php';</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "head_css.php"; ?>
  <title>Profile</title>
</head>
<body class="theme-dark" style="overflow-y: auto;">
  <div id="app">
    <?php include "sidebar.php"; ?>
    <div id="main">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Profil Pengguna</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <form action="" method="post">
            <div class="modal-body">
              <input type="text" class="visually-hidden" value="<?= $dataUserProfile['id'] ?>" name="id">
              <label>Nama : </label>
              <div class="form-group col-md-6 col-12">
                <input type="text" placeholder="Nama" class="form-control" name="nama" value="<?= $dataUserProfile['nama'] ?>">
              </div>
              <label>Jenis Kelamin : </label>
              <div class="form-group col-md-6 col-12">
                <select class="form-select" name="jenis_kelamin">
                  <option value="Laki-laki" <?= ($dataUserProfile['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                  <option value="Perempuan" <?= ($dataUserProfile['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
              </div>
              <label>Alamat : </label>
              <div class="form-group col-md-6 col-12">
                <textarea name="alamat" class="form-control" cols="20" rows="3"><?= $dataUserProfile['alamat'] ?></textarea>
              </div>
              <label>No.Telp : </label>
              <div class="form-group col-md-6 col-12">
                <input type="text" placeholder="Nama" class="form-control" name="tlp" value="<?= $dataUserProfile['tlp'] ?>">
              </div>
              <label>Username: </label>
              <div class="form-group col-md-6 col-12">
                <input type="text" class="form-control" name="username" value="<?= $dataUserProfile['username'] ?>" readonly>
              </div>
              <label>Role: </label>
              <div class="form-group col-md-6 col-12">
                <input type="text" class="form-control" value="<?= $_SESSION['role'] ?>" readonly>
              </div>
            </div>
            <div class="modal-footer">
              <!-- <a href="" class="btn icon icon-left btn-primary m-2" type="submit">Edit Profile</a> -->
              <button class="btn icon icon-left btn-primary m-2" type="submit" name="edit">Edit Profile</button>
              <a href="" class="btn icon icon-left btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#ubahPass">Ubah Password</a>
            </div>
          </form>
          <div class="modal fade text-left" id="ubahPass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="myModalLabel33">Ubah Password</h4>
                  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                  </button>
                </div>
                <form action="" method="post">
                  <div class="modal-body">
                    <label>Password Lama : </label>
                    <div class="form-group">
                      <input type="password" class="form-control" name="passLama">
                    </div>
                    <label>Password Baru : </label>
                    <div class="form-group">
                      <input type="password" class="form-control" name="passBaru">
                    </div>
                    <label>Konfirmasi Password Baru : </label>
                    <div class="form-group">
                      <input type="password" class="form-control" name="passKonfir">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                      <i class="bx bx-x d-block d-sm-none"></i>
                      <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal" name="ubahPassword" value="EDIT DATA">
                      <input type="text" class="visually-hidden" value="<?= $dataUserProfile['id'] ?>" name="id">
                      <i class="bx bx-check d-block d-sm-none"></i>
                      <span class="d-none d-sm-block">Update</span>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/app.js"></script>
</body>
</html>