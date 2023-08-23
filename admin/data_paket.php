<?php
include "../db.php";
include "../filelog.php";
if (empty($_SESSION['loginadmin'])) {
  header("Location: ../index.php");
}

$page = "data_paket";

$queryPaket = "SELECT * FROM tb_paket";
$execPaket = mysqli_query($conn, $queryPaket);
$dataPaket = mysqli_fetch_all($execPaket, MYSQLI_ASSOC);
// var_dump($dataPaket);
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $queryDeletePaket = "DELETE FROM tb_paket WHERE `tb_paket`.`id` = $id";
  $execDeletePaket = mysqli_query($conn, $queryDeletePaket);

  if ($execDeletePaket) {
    $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Menghapus paket. Dengan id paket (" . $id . "). Pada Data Paket Cucian.";
    logger($log, "../../../../../");
    header("location:data_paket.php");
  }
}
if (isset($_POST['edit'])) {
  $id = $_POST['id'];
  $jenis = $_POST['jenis'];
  $paket = $_POST['nama_paket'];
  $harga = $_POST['harga'];
  $queryUpdatePaket = "UPDATE tb_paket SET `jenis` = '$jenis',`nama_paket` = '$paket', `harga`='$harga' WHERE `tb_paket`.`id` = $id";
  $execUpdatePaket = mysqli_query($conn, $queryUpdatePaket);
  if ($execUpdatePaket) {
    $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Mengubah paket. Dengan id paket (" . $id . "). Pada Data Paket Cucian.";
    logger($log, "../../../../../");
    header("location:data_paket.php");
  }
}
if (isset($_POST['simpan'])) {
  $queryTambah = "SELECT * FROM tb_paket";
  $execTambah = mysqli_query($conn, $queryTambah);
  $dataTambah = mysqli_fetch_array($execTambah, MYSQLI_ASSOC);
  $jenis = $_POST['jenis'];
  $paket = $_POST['nama_paket'];
  $harga = $_POST['harga'];
  $insert = "INSERT INTO `tb_paket` (`id`, `jenis`, `nama_paket`, `harga`) VALUES (NULL, '$jenis', '$paket', '$harga');";
  // var_dump($kode);
  $sql = mysqli_query($conn, $insert);
  // var_dump($dataTambah);
  if ($sql) {
    $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Menambahkan paket. Dengan nama paket '" . $paket . "', dan dengan jenis '" . $jenis . "' . Pada Data Paket Cucian.";
    logger($log, "../../../../../");
    header("location:data_paket.php");
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

  <title>Cetak Data Paket</title>
</head>

<body class="theme-dark" style="overflow-y: auto;">
  <div id="app">
    <?php include "sidebar.php" ?>
  </div>

  <!-- Table head options start -->
  <div id="main">
    <div class="page-heading">
      <div class="page-title">
        <div class="row">
          <!-- <div class="col-12 col-md-6 order-md-1 order-last"> -->
          <section class="section">
            <div class="row" id="table-head">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Daftar Paket</h4>
                  </div>
                  <div class="card-content">
                    <!-- table head dark -->
                    <div class="table-responsive">
                      <table class="table mb-3" id="table1">
                        <thead class="thead-dark">
                          <tr>
                            <th>No</th>
                            <th>ID Paket</th>
                            <th>Jenis</th>
                            <th>Nama Paket</th>
                            <th>Harga/Kg</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 0 ?>
                          <?php foreach ($dataPaket as $paket) : ?>
                            <?php $no++ ?>
                            <tr>
                              <td><?= $no ?></td>
                              <td><?= $paket['id'] ?></td>
                              <td><?= $paket['jenis'] ?></td>
                              <td><?= $paket['nama_paket'] ?></td>
                              <td><?= $paket['harga'] ?></td>
                              <td>
                                <a href="" class="btn icon icon-left btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#normal<?= $paket['id']; ?>">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                  </svg> UPDATE</a>
                                <div class="modal fade text-left" id="normal<?= $paket['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel33">Update Paket </h4>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                          <i data-feather="x"></i>
                                        </button>
                                      </div>

                                      <form action="" method="post">
                                        <div class="modal-body">
                                          <label>Jenis Paket : </label>
                                          <div class=" form-group">
                                            <select class="form-select" id="basicSelect" name="jenis">
                                              <option value="<?= $paket['jenis'] ?>"><?= $paket['jenis'] ?></option>
                                              <!-- <option value="kasir">Kasir</option> -->
                                            </select>
                                          </div>
                                          <label>Nama Paket : </label>
                                          <div class="form-group">
                                            <input type="text" placeholder="Nama Paket" class="form-control" name="nama_paket" value="<?= $paket['nama_paket'] ?>">
                                          </div>
                                          <label>Harga: </label>
                                          <div class=" form-group">
                                            <input type="text" placeholder="Harga" class="form-control" name="harga" value="<?= $paket['harga'] ?>">
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Close</span>
                                          </button>
                                          <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal" name="edit" value="EDIT DATA">
                                            <input type="text" class="visually-hidden" value="<?= $paket['id'] ?>" name="id">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Update</span>
                                          </button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                                <a href="?delete=<?= $paket['id'] ?>" class="btn icon icon-left btn-danger"><i class="bi bi-x"></i>
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
                            <h4 class="modal-title" id="myModalLabel33">Tambah Paket </h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                              <i data-feather="x"></i>
                            </button>
                          </div>

                          <form action="" method="post">
                            <div class="modal-body">
                              <label>Jenis Paket : </label>
                              <div class=" form-group">
                                <select class="form-select" id="basicSelect" name="jenis">
                                  <option value="kiloan">Kiloan</option>
                                  <option value="selimut">Selimut</option>
                                  <option value="bed_cover">Bed Cover</option>
                                  <option value="kaos">Kaos</option>
                                  <option value="lain">Lainnya</option>
                                </select>
                              </div>
                              <label>Nama Paket : </label>
                              <div class="form-group">
                                <input type="text" placeholder="Nama Paket" class="form-control" name="nama_paket" value="">
                              </div>
                              <label>Harga: </label>
                              <div class=" form-group">
                                <input type="text" placeholder="Harga" class="form-control" name="harga" value="">
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
          <!--login form Modal -->


        </div>
        <!-- </div> -->
      </div>
    </div>
  </div>


  <!-- Form and scrolling Components start -->

  <script src="../assets/js/bootstrap.js"></script>
  <script src="../assets/js/app.js"></script>
  <script src="../assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
  <script src="../assets/js/pages/simple-datatables.js"></script>

</body>

</html>