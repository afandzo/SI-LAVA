<?php
include "../db.php";
include "../filelog.php";
if (empty($_SESSION['loginkasir'])) {
  header("Location: ../index.php");
}
$page = "riwayat_transaksi";
//ambil data transaksi
$queryTransaksi = "SELECT * FROM tb_transaksi";
$execTransaksi = mysqli_query($conn, $queryTransaksi);
$dataTransaksi = mysqli_fetch_all($execTransaksi, MYSQLI_ASSOC);
//ambil data detail transaksi
$queryDetailTransaksi = "SELECT * FROM tb_detail_transaksi";
$execDetailTransaksi = mysqli_query($conn, $queryDetailTransaksi);
$dataDetailTransaksi = mysqli_fetch_all($execDetailTransaksi, MYSQLI_ASSOC);

$querryPaket = "SELECT * FROM tb_paket";
$execPaket = mysqli_query($conn, $querryPaket);
$dataPaket = mysqli_fetch_all($execPaket, MYSQLI_ASSOC);
if (isset($_POST['hps'])) {
  $id = $_POST['idhapus'];
  $queryHapusData = "DELETE FROM tb_detail_transaksi WHERE id_transaksi = $id";
  $execHapusData = mysqli_query($conn, $queryHapusData);
  $queryHapusTransaksi = "DELETE FROM tb_transaksi WHERE id = $id";
  $execHapusTransaksi = mysqli_query($conn, $queryHapusTransaksi);
  if ($execHapusData && $execHapusTransaksi) {
    header("location: riwayat_transaksi.php");
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
  <title>Riwayat Transaksi</title>
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
                    <h4 class="card-title">Daftar Paket</h4>
                  </div>
                  <div class="card-content">
                    <div class="table-responsive">
                      <table class="table mb-3" id="table1">
                        <thead class="thead-dark">
                          <tr>
                            <th>No</th>
                            <th>Tgl Transaksi</th>
                            <th>Kode Invoice</th>
                            <th>Pelanggan</th>
                            <th>Total Biaya</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 0 ?>
                          <?php foreach ($dataTransaksi as $transaksi) : ?>
                            <?php $no++ ?>
                            <?php
                            if ($transaksi['dibayar'] == 'belum_dibayar') {
                              $bayar = "Belum Dibayar";
                            } if ($transaksi['dibayar'] == 'dibayar') {
                              $bayar  = "Dibayar";
                            } if ($transaksi['status'] == 'baru') {
                              $status  = "Baru";
                            } if ($transaksi['status'] == 'proses') {
                              $status  = "Proses";
                            } if ($transaksi['status'] == 'selesai') {
                              $status  = "Selesai";
                            } if ($transaksi['status'] == 'diambil') {
                              $status  = "Diambil";
                            }
                            ?>
                            <tr>
                              <td><?= $no ?></td>
                              <td><?= $transaksi['tgl'] ?></td>
                              <td><?= $transaksi['kode_invoice'] ?></td>
                              <td><?php
                                  $idPlgn = $transaksi['id_pelanggan'];
                                  $queryNamaPelanggan = "SELECT * FROM tb_pelanggan WHERE id = $idPlgn";
                                  $execPelanggan = mysqli_query($conn, $queryNamaPelanggan);
                                  $dataPelanggan = mysqli_fetch_assoc($execPelanggan);
                                  $namaPelanggan = $dataPelanggan['nama'];
                                  ?>
                                <?= $namaPelanggan ?>
                              </td>
                              <td>
                                <?php
                                $idTransaksi = $transaksi['id'];
                                $queryAmbil = "SELECT * FROM tb_detail_transaksi WHERE id_transaksi = $idTransaksi";
                                $execAmbil = mysqli_query($conn, $queryAmbil);
                                $dataAmbil = mysqli_fetch_all($execAmbil, MYSQLI_ASSOC);
                                $total = [];
                                foreach ($dataAmbil as $ambil) {
                                  $qty = $ambil['qty'];
                                  $idPaket = $ambil['id_paket'];
                                  $queryHarga = "SELECT * FROM tb_paket WHERE id = $idPaket";
                                  $execHarga = mysqli_query($conn, $queryHarga);
                                  $dataHarga = mysqli_fetch_assoc($execHarga);
                                  $total[] += $dataHarga['harga'] * $qty;
                                }
                                $jumlah = count($total);
                                $hargaA = "0";
                                $hargaA;
                                for ($i = 0; $i < $jumlah; $i++) {
                                  $hargaA += $total[$i];
                                }
                                ?>
                                <?= $hargaA ?>
                              </td>
                              <td><?= $bayar ?></td>
                              <td><?= $status ?></td>
                              <td>
                                <a href="detail.php?idtransaksi=<?= $transaksi['id'] ?>&kode=<?= $transaksi['kode_invoice'] ?>" class="btn icon icon-left btn-primary" type="button">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                  </svg></a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#defaultSize<?= $transaksi['id'] ?>">
                                  <i class="bi bi-trash"></i>
                                </button>
                                <div class="modal fade text-left" id="defaultSize<?= $transaksi['id'] ?>" tabindex="-1" aria-labelledby="myModalLabel18" aria-hidden="true" style="display: none;">
                                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel18">Hapus Data Transaksi</h4>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                          </svg>
                                        </button>
                                      </div>
                                      <div class="modal-body">
                                        <h5>Yakin Ingin Menghapus Data Transaksi ?</h5>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                                          <i class="bx bx-x d-block d-sm-none"></i>
                                          <span class="d-none d-sm-block">Tidak</span>
                                        </button>
                                        <form method="post">
                                          <input type="text" class="visually-hidden" value="<?= $transaksi['id']; ?>" name="idhapus">
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
                    <a href="transaksi.php" class="btn icon icon-left btn-success"><i class="bi bi-person-plus-fill"></i>
                      TAMBAH</a>
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