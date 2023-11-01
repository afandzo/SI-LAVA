<?php
include "../db.php";
include "../filelog.php";
if (empty($_SESSION['loginpelanggan'])) {
  header("Location: ../index.php");
}
$page = "riwayat_transaksi";
//ambil semua data pelanggan
$querySemuaPelanggan = "SELECT * FROM tb_pelanggan";
$execSemuaPelanggan = mysqli_query($conn, $querySemuaPelanggan);
$dataSemuaPelanggan = mysqli_fetch_all($execSemuaPelanggan, MYSQLI_ASSOC);
//ambil data transaksi
$kode = $_GET['kode'];
$idTransaksi = $_GET['idtransaksi'];
$queryTransaksi = "SELECT * FROM tb_transaksi WHERE id = $idTransaksi";
$execTransaksi = mysqli_query($conn, $queryTransaksi);
$dataTransaksi = mysqli_fetch_assoc($execTransaksi);
// ambil data pelayanan antar
$queryLayanan = "SELECT * FROM tb_layanan WHERE layanan = 'antar'";
$execLayanan = mysqli_query($conn, $queryLayanan);
$dataLayanan = mysqli_fetch_assoc($execLayanan);
// ambil data pelayanan jemput
$queryLayananJ = "SELECT * FROM tb_layanan WHERE layanan = 'jemput'";
$execLayananJ = mysqli_query($conn, $queryLayananJ);
$dataLayananJ = mysqli_fetch_assoc($execLayananJ);
//ambil data pelanggan
$idPelanggan = $dataTransaksi['id_pelanggan'];
$querypelanggan = "SELECT * FROM tb_pelanggan WHERE id = $idPelanggan";
$execPelanggan = mysqli_query($conn, $querypelanggan);
$dataPelanggan = mysqli_fetch_assoc($execPelanggan);
//ambil data detail
$queryDetailTransaksi = "SELECT * FROM tb_detail_transaksi WHERE id_transaksi = $idTransaksi";
$execDetailTransaksi = mysqli_query($conn, $queryDetailTransaksi);
$dataDetailTransaksi = mysqli_fetch_all($execDetailTransaksi, MYSQLI_ASSOC);
//ambil data paket
$queryPaket = "SELECT * FROM tb_paket";
$execPaket = mysqli_query($conn, $queryPaket);
$dataPaket = mysqli_fetch_all($execPaket, MYSQLI_ASSOC);

if ((!isset($_GET['idtransaksi']) || !isset($_GET['kode'])) || ($kode !== $dataTransaksi['kode_invoice'] || $idTransaksi !== $dataTransaksi['id'])) {
  header("Location: riwayat_transaksi.php");
  exit;
}
if ($dataTransaksi['dibayar'] == 'belum_dibayar') {
  $bayarBadge = "badge bg-danger";
} if ($dataTransaksi['dibayar'] == 'dibayar') {
  $bayarBadge  = "badge bg-success";
} if ($dataTransaksi['status'] == 'baru') {
  $statusBadge  = "badge bg-secondary";
} if ($dataTransaksi['status'] == 'proses') {
  $statusBadge  = "badge bg-info";
} if ($dataTransaksi['status'] == 'selesai') {
  $statusBadge  = "badge bg-primary";
} if ($dataTransaksi['status'] == 'diambil') {
  $statusBadge  = "badge bg-success";
} if ($dataTransaksi['status_antar'] == 'blm_diantar') {
  $antarBadge  = "badge bg-danger";
} if ($dataTransaksi['status_antar'] == 'diantar') {
  $antarBadge  = "badge bg-success";
} if ($dataTransaksi['status_antar'] == '') {
  $antarBadge  = "badge bg-secondary";
} if ($dataTransaksi['status_jemput'] == 'blm_dijemput') {
  $jemputBadge  = "badge bg-danger";
} if ($dataTransaksi['status_jemput'] == 'dijemput') {
  $jemputBadge  = "badge bg-success";
} if ($dataTransaksi['status_jemput'] == '') {
  $jemputBadge  = "";
}
if ($dataTransaksi['dibayar'] == 'belum_dibayar') {
  $bayar = "Belum Dibayar";
} if ($dataTransaksi['dibayar'] == 'dibayar') {
  $bayar  = "Dibayar";
} if ($dataTransaksi['status'] == 'baru') {
  $status  = "Baru";
} if ($dataTransaksi['status'] == 'proses') {
  $status  = "Proses";
} if ($dataTransaksi['status'] == 'selesai') {
  $status  = "Selesai";
} if ($dataTransaksi['status'] == 'diambil') {
  $status  = "Diambil";
} if ($dataTransaksi['status_antar'] == 'blm_diantar') {
  $antar  = "Belum Antar";
} if ($dataTransaksi['status_antar'] == 'diantar') {
  $antar  = "Diantar";
} if ($dataTransaksi['status_antar'] == '') {
  $antar  = "-";
} if ($dataTransaksi['status_jemput'] == 'blm_dijemput') {
  $jemput  = "Belum Jemput";
} if ($dataTransaksi['status_jemput'] == 'dijemput') {
  $jemput  = "Dijemput";
} if ($dataTransaksi['status_jemput'] == '') {
  $jemput  = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "head_css.php"; ?>
  <style>
    @media print {
      @page {
        size: landscape;
      }
      #sidebar {
        display: none;
      }
    }
  </style>
  <title>Detail Paket</title>
</head>
<body class="theme-dark" style="overflow-y: auto;">
  <div id="app">
    <?php include "sidebar.php" ?>
  </div>
  <div id="main">
    <div class="page-heading">
      <div class="page-title">
        <div class="row">
          <section id="multiple-column-form">
            <div class="row match-height">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <div class="row col-12">
                      <div class="col-6">
                        <h4 class="card-title"> Detail Transaksi</h4>
                      </div>
                    </div>
                    <div class="row col-12">
                      <div class="col-12">
                        <div class="float-end col-auto mb-0 pb-0">
                          <span class="<?= $bayarBadge ?>"><?= $bayar ?></span>
                          <span class="<?= $statusBadge ?>"><?= $status ?></span>
                          <?php if ($antarBadge != ''): ?>
                            <span class="<?= $antarBadge ?>"><?= $antar ?></span>
                          <?php endif; ?>
                          <?php if ($jemputBadge != ''): ?>
                            <span class="<?= $jemputBadge ?>"><?= $jemput ?></span>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-content">
                    <div class="card-body">
                      <div class="card-body">
                        <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <table class="table">
                              <tbody>
                                <tr>
                                  <td>No Invoice</td>
                                  <td>: <?= $kode ?> </td>
                                </tr>
                                <tr>
                                  <td>Pelanggan</td>
                                  <td>: <?= $dataPelanggan['nama'] ?></td>
                                </tr>
                                <tr>
                                  <td>Username</td>
                                  <td>: <?= $dataPelanggan['username'] ?></td>
                                </tr>
                                <tr>
                                  <td>No. Telp</td>
                                  <td>: <?= $dataPelanggan['tlp'] ?></td>
                                </tr>
                                <tr>
                                  <td>Pelanggan</td>
                                  <td>: <?= $dataPelanggan['alamat'] ?></td>
                                </tr>
                                <tr>
                                  <td>Kasir</td>
                                  <?php
                                  $id_kasir = $dataTransaksi['id_user'];
                                  // Ambil data kasir berdasarkan id_kasir
                                  $queryKasir = "SELECT * FROM user WHERE id = $id_kasir";
                                  $execKasir = mysqli_query($conn, $queryKasir);
                                  $dataKasir = mysqli_fetch_assoc($execKasir);
                                  // Tampilkan nama kasir
                                  $namaKasir = ($dataKasir) ? $dataKasir['nama'] : 'Tidak Diketahui';
                                  ?>
                                  <td>: <?= $namaKasir ?></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <table class="table">
                              <tbody>
                                <tr>
                                  <td>Tanggal Transaksi</td>
                                  <td>: <?= $dataTransaksi['tgl'] ?></td>
                                </tr>
                                <tr>
                                  <td>Batas Tanggal</td>
                                  <td>: <?= $dataTransaksi['batas_waktu'] ?></td>
                                </tr>
                                <tr>
                                  <td>Status Bayar</td>
                                  <td>: <?= $dataTransaksi['dibayar'] ?></td>
                                </tr>
                                <tr>
                                  <td>Status Paket</td>
                                  <td>: <?= $dataTransaksi['status'] ?></td>
                                </tr>
                                <tr>
                                  <td>Layanan Antar</td>
                                  <td>: <?= $dataTransaksi['layanan_antar'] ?></td>
                                </tr>
                                <tr>
                                  <td>Layanan Jemput</td>
                                  <td>: <?= $dataTransaksi['layanan_jemput'] ?></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>  
                        </div>
                        <div>
                          <div class="col-sm-12">
                            <table class="table table-bordered">
                              <thead class="text-center">
                                <tr>
                                  <th>No</th>
                                  <th>Nama Paket</th>
                                  <th>Jenis Paket</th>
                                  <th>Tarif</th>
                                  <th>Berat</th>
                                  <th>Total Biaya</th>
                                </tr>
                              </thead>
                              <tbody class="text-center">
                                <?php $no = 0 ?>
                                <?php foreach ($dataDetailTransaksi as $detail) : ?>
                                  <?php
                                  $idPaket = $detail['id_paket'];
                                  $queryAmbilPaket = "SELECT * FROM tb_paket WHERE id = $idPaket";
                                  $execAmbilPaket = mysqli_query($conn, $queryAmbilPaket);
                                  $dataAmbilPaket = mysqli_fetch_assoc($execAmbilPaket);
                                  $namaPaket = $dataAmbilPaket['nama_paket'];
                                  $jenisPaket = $dataAmbilPaket['jenis'];
                                  $hargaPaket = $dataAmbilPaket['harga'];
                                  $totalHarga = $detail['qty'] * $hargaPaket;
                                  ?>
                                  <?php $no++ ?>
                                  <tr>
                                    <td><?= $no ?></td>
                                    <td><?= $namaPaket ?></td>
                                    <td><?= $jenisPaket ?></td>
                                    <td>Rp. <?= $hargaPaket ?>/KG</td>
                                    <td> <?= $detail['qty'] ?>KG</td>
                                    <td>Rp. <?= $totalHarga ?></td>
                                  </tr>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                          </div>
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
</body>

</html>
