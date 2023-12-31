<?php
include "../db.php";
include "../filelog.php";
if (empty($_SESSION['loginadmin'])) {
  header("Location: ../index.php");
}
$page = "riwayat_transaksi";
//ambil data transaksi
$queryTransaksi = "SELECT * FROM tb_transaksi ORDER BY id DESC";
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
  <?php include "head_css.php"; ?>
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
                            <th>Pembayaran</th>
                            <th>Status</th>
                            <th>Antar</th>
                            <th>Jemput</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 0 ?>
                          <?php foreach ($dataTransaksi as $transaksi) : ?>
                            <?php $no++ ?>
                            <?php
                            if ($transaksi['dibayar'] == 'belum_dibayar') {
                              $bayarBadge = "badge bg-danger";
                            } if ($transaksi['dibayar'] == 'dibayar') {
                              $bayarBadge  = "badge bg-success";
                            } if ($transaksi['status'] == 'baru') {
                              $statusBadge  = "badge bg-secondary";
                            } if ($transaksi['status'] == 'proses') {
                              $statusBadge  = "badge bg-info";
                            } if ($transaksi['status'] == 'selesai') {
                              $statusBadge  = "badge bg-primary";
                            } if ($transaksi['status'] == 'diambil') {
                              $statusBadge  = "badge bg-success";
                            } if ($transaksi['status_antar'] == 'blm_diantar') {
                              $antarBadge  = "badge bg-danger";
                            } if ($transaksi['status_antar'] == 'diantar') {
                              $antarBadge  = "badge bg-success";
                            } if ($transaksi['status_antar'] == '') {
                              $antarBadge  = "badge bg-secondary";
                            } if ($transaksi['status_jemput'] == 'blm_dijemput') {
                              $jemputBadge  = "badge bg-danger";
                            } if ($transaksi['status_jemput'] == 'dijemput') {
                              $jemputBadge  = "badge bg-success";
                            } if ($transaksi['status_jemput'] == '') {
                              $jemputBadge  = "badge bg-secondary";
                            }

                            if ($transaksi['dibayar'] == 'belum_dibayar') {
                              $bayar = "Blm Dibayar";
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
                            } if ($transaksi['status_antar'] == 'blm_diantar') {
                              $antar  = "Blm Antar";
                            } if ($transaksi['status_antar'] == 'diantar') {
                              $antar  = "Diantar";
                            } if ($transaksi['status_antar'] == '') {
                              $antar  = "-";
                            } if ($transaksi['status_jemput'] == 'blm_dijemput') {
                              $jemput  = "Blm Jemput";
                            } if ($transaksi['status_jemput'] == 'dijemput') {
                              $jemput  = "DiJemput";
                            } if ($transaksi['status_jemput'] == '') {
                              $jemput  = "-";
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
                                <?= $transaksi['total_harga'] ?>
                              </td>
                              <td><span class="<?= $bayarBadge ?>"><?= $bayar ?></span></td>
                              <td><span class="<?= $statusBadge ?>"><?= $status ?></span></td>
                              <td><span class="<?= $antarBadge ?>"><?= $antar ?></span></td>
                              <td><span class="<?= $jemputBadge ?>"><?= $jemput ?></span></td>
                              <td>
                                <a href="detail.php?idtransaksi=<?= $transaksi['id'] ?>&kode=<?= $transaksi['kode_invoice'] ?>" class="btn icon icon-left btn-primary " type="button">
                                <i class="bi bi-pencil-square"></i></a>
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