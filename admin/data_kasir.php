<?php
include "../db.php";
include "../filelog.php";
$page = "data_kasir";
if (empty($_SESSION['loginadmin'])) {
  header("Location: ../index.php");
}
$username = $_SESSION['username'];
$queryUser = "SELECT * FROM user WHERE username != '$username' ORDER BY id DESC";
$execUser = mysqli_query($conn, $queryUser);
$dataUser = mysqli_fetch_all($execUser, MYSQLI_ASSOC);
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $queryDeleteUser = "DELETE FROM user WHERE `user`.`id` = $id";
  $execDeleteUser = mysqli_query($conn, $queryDeleteUser);
  if ($execDeleteUser) {
    $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Menghapus user. Dengan id user (" . $id . "). Pada Data Kasir.";
    logger($log, "../../../../../");
    echo "<script>alert('User berhasil dihapus'); window.location.href='data_kasir.php';</script>";
  } else {
    echo "<script>alert('User gagal dihapus'); window.location.href='data_kasir.php';</script>";
  }
}
if (isset($_POST['edit'])) {
  $id = $_POST['id'];
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $rolee = $_POST['role'];
  $queryUpdateUser = "UPDATE user SET `nama` = '$nama',`username` = '$username',`role` = '$rolee' WHERE `user`.`id` = $id";
  $execUpdateUser = mysqli_query($conn, $queryUpdateUser);
  if ($execUpdateUser) {
    $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Mengubah user. Dengan id user (" . $id . "). Pada Data Kasir.";
    logger($log, "../../../../../");
    echo "<script>alert('User berhasil diupdate'); window.location.href='data_kasir.php';</script>";
  } else {
    echo "<script>alert('User gagal diupdate'); window.location.href='data_kasir.php';</script>";
  }
}
if (isset($_POST['simpan'])) {
  $queryTambah = "SELECT * FROM user";
  $execTambah = mysqli_query($conn, $queryTambah);
  $dataTambah = mysqli_fetch_array($execTambah, MYSQLI_ASSOC);
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $password = password_hash(1234, PASSWORD_ARGON2I);
  $rolee = $_POST['role'];
  $insert = "INSERT INTO `user` (`id`, `nama`, `username`, `password`, `role`) VALUES (NULL, '$nama', '$username', '$password', '$rolee');";
  // var_dump($kode);
  $sql = mysqli_query($conn, $insert);
  // var_dump($dataTambah);
  if ($sql) {
    $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Menambahkan user. Dengan nama user '" . $nama . "' . Pada Data Kasir.";
    logger($log, "../../../../../");
    echo "<script>alert('User berhasil ditambahkan'); window.location.href='data_kasir.php';</script>";
  } else {
    echo "<script>alert('User gagal ditambahkan'); window.location.href='data_kasir.php';</script>";
  }
}
if (isset($_POST['setPassDef'])) {
  $id = $_POST['id'];
  $passwordDefault = password_hash(1234, PASSWORD_ARGON2I);
  $queryUpdateUser = "UPDATE user SET `password` = '$passwordDefault' WHERE `user`.`id` = $id";
  $execUpdateUser = mysqli_query($conn, $queryUpdateUser);
  if ($execUpdateUser) {
    $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Men-setting password ke default. Dengan id user (" . $id . "). Pada Data Kasir.";
    logger($log, "../../../../../");
    echo "<script>alert('Password berhasil diatur ulang ke default'); window.location.href='data_kasir.php';</script>";
  } else {
    echo "<script>alert('Gagal mengatur ulang password'); window.location.href='data_kasir.php';</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "head_css.php"; ?>
  <title>Data Kasir</title>
</head>
<body class="theme-dark" style="overflow-y: auto;">
  <div id="app">
    <?php include "sidebar.php" ?>
  </div>
  <div id="main">
    <div class="page-heading">
      <div class="page-title">
        <div class="row">
          <section class="section">
            <div class="row" id="table-head">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Daftar Anggota</h4>
                  </div>
                  <div class="card-content">
                    <div class="table-responsive">
                      <table class="table  mb-3" id="table1">
                        <thead class="thead-dark">
                          <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 0 ?>
                          <?php foreach ($dataUser as $user) : ?>
                            <?php $no++ ?>
                            <tr>
                              <td><?= $no ?></td>
                              <td><?= $user['nama'] ?></td>
                              <td><?= $user['username'] ?></td>
                              <td><?= $user['role'] ?></td>
                              <td>
                                <a href="" class="btn icon icon-left btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#normal<?= $user['id']; ?>">
                                <i class="bi bi-pencil-square"></i>UPDATE</a>
                                <div class="modal fade text-left" id="normal<?= $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel33">Update Anggota</h4>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                          <i data-feather="x"></i>
                                        </button>
                                      </div>
                                      <form action="" method="post">
                                        <div class="modal-body">
                                          <label>Nama : </label>
                                          <div class="form-group">
                                            <input type="text" placeholder="Nama" class="form-control" name="nama" value="<?= $user['nama'] ?>">
                                          </div>
                                          <label>Username: </label>
                                          <div class=" form-group">
                                            <input type="text" placeholder="Username" class="form-control" name="username" value="<?= $user['username'] ?>">
                                          </div>
                                          <label>Role: </label>
                                          <div class=" form-group">
                                            <select class="form-select" id="basicSelect" name="role">
                                              <option value="<?= $user['role'] ?>"><?= $user['role'] ?></option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                          </button>
                                          <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal" name="edit" value="EDIT DATA">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Update</span>
                                          </button>
                                          <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal" name="setPassDef" onclick="return confirmReset()">
                                            <input type="text" class="visually-hidden" value="<?= $user['id'] ?>" name="id">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Set Password To Default</span>
                                          </button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                                <a href="?delete=<?= $user['id'] ?>" class="btn icon icon-left btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')"><i class="bi bi-x"></i>
                                  DELETE</a>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="card-footer d-flex justify-content-between">
                    <a href="../register.php" class="btn icon icon-left btn-success" data-bs-toggle="modal" data-bs-target="#default"><i class="bi bi-person-plus-fill"></i>
                      TAMBAH</a>
                    <!-- ====MODAL TAMBAH==== -->
                    <div class="modal fade text-left" id="default" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel33">Tambah Anggota </h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                              <i data-feather="x"></i>
                            </button>
                          </div>
                          <form action="" method="post">
                            <div class="modal-body">
                              <label>Nama : </label>
                              <div class="form-group">
                                <input type="text" placeholder="Nama" class="form-control" name="nama" value="">
                              </div>
                              <label>Username: </label>
                              <div class=" form-group">
                                <input type="text" placeholder="Username" class="form-control" name="username" value="">
                              </div>
                              <label>Role: </label>
                              <div class=" form-group">
                                <select class="form-select" id="basicSelect" name="role">
                                  <option value="admin">Admin</option>
                                  <option value="kasir">Kasir</option>
                                </select>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                              </button>
                              <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal" name="simpan" value="SIMPAN DATA">
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Simpan</span>
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/js/bootstrap.js"></script>
  <script src="../assets/js/app.js"></script>
  <script src="../assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
  <script src="../assets/js/pages/simple-datatables.js"></script>
  <script>
    function confirmReset() {
      return confirm('Apakah Anda yakin ingin mengatur ulang kata sandi ke default pada user ini?');
    }
  </script>
</body>
</html>