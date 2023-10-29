<?php
include "../db.php";
include "../filelog.php";
if (empty($_SESSION['loginadmin'])) {
  header("Location: ../index.php");
}
$page = "data_pelanggan";
$queryPelanggan = "SELECT * FROM tb_pelanggan ORDER BY id DESC";
$execPelanggan = mysqli_query($conn, $queryPelanggan);
$dataPelanggan = mysqli_fetch_all($execPelanggan, MYSQLI_ASSOC);
// var_dump($dataPelanggan);
if (isset($_POST['hps'])) {
  $idPelanggan = $_POST['idhapus'];
  // Periksa apakah ada transaksi untuk pelanggan
  $queryCari = "SELECT * FROM tb_transaksi WHERE id_pelanggan = $idPelanggan";
  $execCari = mysqli_query($conn, $queryCari);
  $dataCari = mysqli_fetch_all($execCari, MYSQLI_ASSOC);
  if ($dataCari) {
    // Jika ada transaksi, hapus detail transaksi
    $idTransaksi = [];
    foreach ($dataCari as $cari) {
      $idTransaksi[] += $cari['id'];
    }
    foreach ($idTransaksi as $idTransaksiValue) {
      $queryHapusDetail = "DELETE FROM tb_detail_transaksi WHERE id_transaksi = $idTransaksiValue";
      $execHapusDetail = mysqli_query($conn, $queryHapusDetail);
    }
    // Hapus data transaksi
    $queryHapusTransaksi = "DELETE FROM tb_transaksi WHERE id_pelanggan = $idPelanggan";
    $execHapusTransaksi = mysqli_query($conn, $queryHapusTransaksi);
    if ($execHapusDetail && $execHapusTransaksi) {
      // Hapus data pelanggan
      $queryDeletePelanggan = "DELETE FROM tb_pelanggan WHERE `id` = $idPelanggan";
      $execDeletePelanggan = mysqli_query($conn, $queryDeletePelanggan);
      if ($execDeletePelanggan) {
        $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Menghapus pelanggan. Dengan id pelanggan (" . $id . "). Pada Data Pelanggan.";
        logger($log, "../../../../../");
        echo "<script>alert('Pelanggan berhasil dihapus'); window.location.href='data_pelanggan.php';</script>";
      }
    }
  } else {
    // Jika tidak ada transaksi, langsung hapus data pelanggan
    $queryDeletePelanggan = "DELETE FROM tb_pelanggan WHERE `id` = $idPelanggan";
    $execDeletePelanggan = mysqli_query($conn, $queryDeletePelanggan);
    if ($execDeletePelanggan) {
      $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Menghapus pelanggan. Dengan id pelanggan (" . $id . "). Pada Data Pelanggan.";
      logger($log, "../../../../../");
      echo "<script>alert('Pelanggan berhasil dihapus'); window.location.href='data_pelanggan.php';</script>";
    }
  }
}
if (isset($_POST['edit'])) {
  $id = $_POST['id'];
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $tlp = $_POST['tlp'];
  $queryUpdatePelanggan = "UPDATE tb_pelanggan SET `nama` = '$nama',`alamat` = '$alamat', `jenis_kelamin`='$jenis_kelamin',`tlp` = '$tlp' WHERE `tb_pelanggan`.`id` = $id";
  $execUpdatePelanggan = mysqli_query($conn, $queryUpdatePelanggan);
  if ($execUpdatePelanggan) {
    $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Mengubah pelanggan. Dengan id pelanggan (" . $id . "). Pada Data Pelanggan.";
    logger($log, "../../../../../");
    echo "<script>alert('Pelanggan berhasil diupdate'); window.location.href='data_pelanggan.php';</script>";
  }
}
if (isset($_POST['simpan'])) {
  $nama = $_POST['nama'];
  $namaArray = explode(' ', $nama);
  $namaDepan = strtolower($namaArray[0]); // Ambil nama depan dan konversi ke huruf kecil
  $username = $namaDepan;
  $password = password_hash(1234, PASSWORD_ARGON2I);
  $alamat = $_POST['alamat'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $tlp = $_POST['tlp'];
  // Cek apakah username sudah ada, jika ada tambahkan angka
  $queryCekUsername = "SELECT * FROM tb_pelanggan WHERE username LIKE '$username%'";
  $execCekUsername = mysqli_query($conn, $queryCekUsername);
  $jumlahUsernameSama = mysqli_num_rows($execCekUsername);
  if ($jumlahUsernameSama > 0) {
    $username = $username . ($jumlahUsernameSama + 1); // Tambahkan angka jika sudah ada
  }
  // Insert data ke database
  $insert = "INSERT INTO `tb_pelanggan` (`id`, `nama`, `username`, `password`, `alamat`, `jenis_kelamin`, `tlp`) VALUES (NULL, '$nama', '$username', '$password', '$alamat', '$jenis_kelamin', '$tlp');";
  $sql = mysqli_query($conn, $insert);
  if ($sql) {
    $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Menambahkan pelanggan. Dengan nama pelanggan '" . $nama . "' . Pada Data Pelanggan.";
    logger($log, "../../../../../");
    echo "<script>alert('Pelanggan berhasil ditambahkan'); window.location.href='data_pelanggan.php';</script>";
  }
}
if (isset($_POST['setPassDef'])) {
  $id = $_POST['id'];
  $passwordDefault = password_hash(1234, PASSWORD_ARGON2I);
  $queryUpdateUser = "UPDATE tb_pelanggan SET `password` = '$passwordDefault' WHERE `tb_pelanggan`.`id` = $id";
  $execUpdateUser = mysqli_query($conn, $queryUpdateUser);
  if ($execUpdateUser) {
    $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Men-setting password ke default. Dengan id pelanggan (" . $id . "). Pada Data Pelanggan.";
    logger($log, "../../../../../");
    echo "<script>alert('Password berhasil diatur ulang ke default'); window.location.href='data_pelanggan.php';</script>";
  } else {
    echo "<script>alert('Gagal mengatur ulang password'); window.location.href='data_pelanggan.php';</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "head_css.php"; ?>
  <title>Data Pelanggan</title>
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
                    <h4 class="card-title">Daftar Pelanggan</h4>
                  </div>
                  <div class="card-content">
                    <div class="table-responsive">
                      <table class="table mb-3" id="table1">
                        <thead class="thead-dark">
                          <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th>Jmlh Transaksi</th>
                            <th>Jmlh Hadiah</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 0 ?>
                          <?php foreach ($dataPelanggan as $pelanggan) : ?>
                            <?php $no++ ?>
                            <tr>
                              <td><?= $no ?></td>
                              <td><?= $pelanggan['nama'] ?></td>
                              <td><?= $pelanggan['username'] ?></td>
                              <td><?= $pelanggan['alamat'] ?></td>
                              <td><?= $pelanggan['tlp'] ?></td>
                              <td>
                                <?php
                                $idPelanggan = $pelanggan['id'];
                                $queryCountTransactions = "SELECT COUNT(*) AS total FROM tb_transaksi WHERE id_pelanggan = '$idPelanggan'";
                                $execCountTransactions = mysqli_query($conn, $queryCountTransactions);
                                $dataCountTransactions = mysqli_fetch_assoc($execCountTransactions);
                                echo $dataCountTransactions['total'];
                                ?>
                              </td>
                              <td>
                                <?php
                                $totalTransactions = $dataCountTransactions['total'];
                                $jumlahHadiah = floor($totalTransactions / 10);
                                echo $jumlahHadiah;
                                ?>
                              </td>
                              <td>
                                <a href="" class="btn icon icon-left btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#normal<?= $pelanggan['id']; ?>">
                                <i class="bi bi-pencil-square"></i></a>
                                <div class="modal fade text-left" id="normal<?= $pelanggan['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel33">Update Pelanggan</h4>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                          <i data-feather="x"></i>
                                        </button>
                                      </div>
                                      <form action="" method="post">
                                        <div class="modal-body">
                                          <label>Nama : </label>
                                          <div class="form-group">
                                            <input type="text" placeholder="Nama" class="form-control" name="nama" value="<?= $pelanggan['nama'] ?>">
                                          </div>
                                          <label>Alamat: </label>
                                          <div class=" form-group">
                                            <input type="text" placeholder="Alamat" class="form-control" name="alamat" value="<?= $pelanggan['alamat'] ?>">
                                          </div>
                                          <label>Jenis Kelamin: </label>
                                          <div class=" form-group">
                                            <select class="form-select" id="basicSelect" name="jenis_kelamin">
                                              <option value="<?= $pelanggan['jenis_kelamin'] ?>"><?= $pelanggan['jenis_kelamin'] ?></option>
                                            </select>
                                          </div>
                                          <label>No. Tlp: </label>
                                          <div class=" form-group">
                                            <input type="text" placeholder="No. Tlp" class="form-control" name="tlp" value="<?= $pelanggan['tlp'] ?>">
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                          </button>
                                          <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal" name="edit" value="EDIT DATA">
                                            <input type="text" class="visually-hidden" value="<?= $pelanggan['id'] ?>" name="id">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Update</span>
                                          </button>
                                          <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal" name="setPassDef" onclick="return confirmReset()">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Set Password To Default</span>
                                          </button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#defaultSize<?= $pelanggan['id'] ?>">
                                  <i class="bi bi-trash"></i>
                                </button>
                                <div class="modal fade text-left" id="defaultSize<?= $pelanggan['id'] ?>" tabindex="-1" aria-labelledby="myModalLabel18" aria-hidden="true" style="display: none;">
                                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel18">Hapus Data Pelanggan</h4>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                          </svg>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <h5>Yakin Ingin Menghapus Data Pelanggan ?</h5>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                          <i class="bx bx-x d-block d-sm-none"></i>
                                          <span class="d-none d-sm-block">Tidak</span>
                                        </button>
                                        <form method="post">
                                          <input type="text" class="visually-hidden" value="<?= $pelanggan['id']; ?>" name="idhapus">
                                          <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal" name="hps">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Ya</span>
                                          </button>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                </div>
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
                              <label>Alamat: </label>
                              <div class=" form-group">
                                <input type="text" placeholder="Alamat" class="form-control" name="alamat" value="">
                              </div>
                              <label>Jenis Kelamin: </label>
                              <div class=" form-group">
                                <select class="form-select" id="basicSelect" name="jenis_kelamin">
                                  <option value="Laki-laki">Laki-laki</option>
                                  <option value="Perempuan">Perempuan</option>
                                </select>
                              </div>
                              <label>No. Tlp: </label>
                              <div class=" form-group">
                                <input type="text" placeholder="No. Tlp" class="form-control" name="tlp" value="">
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                              </button>
                              <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal" name="simpan" value="SIMPAN DATA">
                                <!-- <input type="text" class="visually-hidden" value="" name="id"> -->
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