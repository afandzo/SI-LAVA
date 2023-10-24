<?php
include "../db.php";
include "../filelog.php";
if (empty($_SESSION['loginadmin'])) {
  header("Location: ../index.php");
}
$page = "data_pelanggan";
$queryPelanggan = "SELECT * FROM tb_pelanggan";
$execPelanggan = mysqli_query($conn, $queryPelanggan);
$dataPelanggan = mysqli_fetch_all($execPelanggan, MYSQLI_ASSOC);
// var_dump($dataPelanggan);
if (isset($_POST['hps'])) {
  $idPelanggan = $_POST['idhapus'];
  $queryCari = "SELECT * FROM tb_transaksi WHERE id_pelanggan=$idPelanggan";
  $execCari = mysqli_query($conn, $queryCari);
  $dataCari = mysqli_fetch_all($execCari, MYSQLI_ASSOC);
  $idTransaksi = [];
  foreach ($dataCari as $cari) {
    $idTransaksi[] += $cari['id'];
  }
  foreach ($idTransaksi as $idTransaksiValue) {
    $queryHapusDetail = "DELETE FROM tb_detail_transaksi WHERE id_transaksi = $idTransaksiValue";
    $execHapusDetail = mysqli_query($conn, $queryHapusDetail);
  }
  $queryHapusTransaksi = "DELETE FROM tb_transaksi WHERE id_pelanggan = $idPelanggan";
  $execHapusTransaksi = mysqli_query($conn, $queryHapusTransaksi);
  if ($execHapusDetail && $execHapusTransaksi) {
    $queryDeletePelanggan = "DELETE FROM tb_pelanggan WHERE `id` = $idPelanggan";
    $execDeletePelanggan = mysqli_query($conn, $queryDeletePelanggan);
    if ($execDeletePelanggan) {
      $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Menghapus pelanggan. Dengan id pelanggan (" . $id . "). Pada Data Pelanggan.";
      logger($log, "../../../../../");
      header("location:data_pelanggan.php");
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
    header("location:data_pelanggan.php");
  }
}
if (isset($_POST['simpan'])) {
  $queryTambahPelanggan = "SELECT * FROM tb_pelanggan";
  $execTambahPelanggan = mysqli_query($conn, $queryTambahPelanggan);
  $dataTambahPelanggan = mysqli_fetch_array($execTambahPelanggan, MYSQLI_ASSOC);
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $tlp = $_POST['tlp'];
  $insert = "INSERT INTO `tb_pelanggan` (`id`, `nama`, `alamat`, `jenis_kelamin`, `tlp`) VALUES (NULL, '$nama', '$alamat', '$jenis_kelamin', '$tlp');";
  // var_dump($kode);
  $sql = mysqli_query($conn, $insert);
  // var_dump($dataTambahPelanggan);
  if ($sql) {
    $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Menambahkan pelanggan. Dengan nama pelanggan '" . $nama . "' . Pada Data Pelanggan.";
    logger($log, "../../../../../");
    header("location:data_pelanggan.php");
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/main/app.css">
  <link rel="stylesheet" href="../assets/css/main/app-dark.css">
  <link rel="shortcut icon" href="../assets/images/logo/favicon.svg" type="image/x-icon">
  <link rel="shortcut icon" href="../assets/images/logo/favicon.png" type="image/png">
  <link rel="stylesheet" href="../assets/extensions/simple-datatables/style.css">
  <link rel="stylesheet" href="../assets/css/pages/simple-datatables.css">
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
                            <th>ID Pelanggan</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Jenis Kelamin</th>
                            <th>No. Telepon</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 0 ?>
                          <?php foreach ($dataPelanggan as $pelanggan) : ?>
                            <?php $no++ ?>
                            <tr>
                              <td><?= $no ?></td>
                              <td><?= $pelanggan['id'] ?></td>
                              <td><?= $pelanggan['nama'] ?></td>
                              <td><?= $pelanggan['alamat'] ?></td>
                              <td><?= $pelanggan['jenis_kelamin'] ?></td>
                              <td><?= $pelanggan['tlp'] ?></td>
                              <td>
                                <a href="" class="btn icon icon-left btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#normal<?= $pelanggan['id']; ?>">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                  </svg> UPDATE</a>
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
</body>

</html>