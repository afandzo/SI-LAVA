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
}
// Edit detail transaksi
if (isset($_POST['simpan'])) {
  // Ubah data tb transaksi
  $pelanggan = $_POST['pelanggan'];
  $tgl = $_POST['tgl'];
  $batastgl = $_POST['batastgl'];
  $tglbayar = $_POST['tglbayar'];
  $status = $_POST['status'];
  $status_bayar = $_POST['status_bayar'];
  if ($tgl == '0000-00-00 00:00:00') {
    exit;
  }
  $queryEditData = "UPDATE `tb_transaksi` SET `id_pelanggan` = '$pelanggan', `tgl` = '$tgl', `batas_waktu` = '$batastgl', `tgl_bayar` = '$tglbayar', `status` = '$status', `dibayar` = '$status_bayar' WHERE `tb_transaksi`.`id` = $idTransaksi;";
  $execEditData = mysqli_query($conn, $queryEditData);
  // Ubah data tb detail transaksi
  $jumlahPaketDipesan = [];
  $kuan = [];
  foreach ($dataDetailTransaksi as $detail) {
    foreach ($dataPaket as $paket) {
      if ($detail['id_paket'] == $paket['id']) {
        $jumlahPaketDipesan[] += $paket['id'];
        $kuan[] += $detail['qty'];
      }
    }
  }
  // Cek sudah ada isinya tau belum,
  $jumlahRow = mysqli_num_rows($execDetailTransaksi);
  $jumlahPaket = mysqli_num_rows($execPaket);
  foreach ($dataDetailTransaksi as $detail) {
    // Jika sudah, ganti isinya dengan yang baru
    foreach ($dataPaket as $paket) {
      if ($paket['id'] == $detail['id_paket']) {
        $idPaket = $paket['id'];
        $qty = $_POST['qty' . "$idPaket"];
        $keterangan = $_POST['ket' . "$idPaket"];
        if ($detail['qty'] !== $qty) {
          $queryUpdate = "UPDATE `tb_detail_transaksi` SET `qty` = '$qty', `keterangan` = '$keterangan' WHERE id_paket = $idPaket AND id_transaksi = $idTransaksi";
          $execUpdate = mysqli_query($conn, $queryUpdate);
        }
      } else {
        continue;
      }
    }
    // Jika belum ada, masukan data tersebut dgn insert
    foreach ($dataPaket as $paket) {
      // global $idTransaksi;
      $idPaket = $paket['id'];
      $queryPilih = "SELECT * FROM tb_detail_transaksi WHERE id_paket = $idPaket AND id_transaksi = $idTransaksi";
      $execPilih = mysqli_query($conn, $queryPilih);
      $jumlahPilih = mysqli_num_rows($execPilih);
      if ($paket['id'] !== $detail['id_paket']) {
        $idPaket = $paket['id'];
        $qty = $_POST['qty' . "$idPaket"];
        $keterangan = $_POST['ket' . "$idPaket"];
        if ($qty !== 0 && $jumlahPilih == 0) {
          $queryTambahPesanan = "INSERT INTO `tb_detail_transaksi` (`id`, `id_transaksi`, `id_paket`, `qty`, `keterangan`) VALUES (NULL, '$idTransaksi', '$idPaket', '$qty', '$keterangan')";
          $execTambahPesanan = mysqli_query($conn, $queryTambahPesanan);
        }
      }
    }
  }
  foreach ($dataPaket as $paket) {
    $idPaket = $paket['id'];
    $queryDeleteBug = "DELETE FROM tb_detail_transaksi WHERE id_transaksi = $idTransaksi AND qty = 0";
    $execDeleteBug = mysqli_query($conn, $queryDeleteBug);
  }
  if ($queryUpdate || $queryTambahPesanan) {
    header("location: detail.php?idtransaksi=$idTransaksi&kode=$kode");
  }
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
                      <div class="col-9">
                        <h4 class="card-title"> Detail Transaksi</h4>
                      </div>
                      <div class="float-end col-auto mb-0 pb-0">
                        <span class="<?= $bayarBadge ?>"><?= $bayar ?></span>
                        <span class="<?= $statusBadge ?>"><?= $status ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="card-content">
                    <div class="card-body">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-sm-5">
                            <div class="form-group">
                              <table class="table">
                                <tbody>
                                  <tr>
                                    <td>No Invoice</td>
                                    <td>: <?= $kode ?> </td>
                                    <input type="hidden" name="kode_invoice" id="kode_invoice" value="<?= $kode ?>">
                                  </tr>
                                  <tr>
                                    <td>Tanggal Transaksi</td>
                                    <td>: <?= $dataTransaksi['tgl'] ?></td>
                                  </tr>
                                  <tr>
                                    <td>Pelanggan</td>
                                    <td>: <?= $dataPelanggan['nama'] ?></td>
                                  </tr>
                                  <tr>
                                    <td>No Telp</td>
                                    <td>: <?= $dataPelanggan['tlp'] ?></td>
                                  </tr>
                                  <tr>
                                    <td>Alamat</td>
                                    <td>: <?= $dataPelanggan['alamat'] ?></td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="col-sm-8">
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
                          <!-- Tombol cetak invoice -->
                          <a href="cetak_detail.php?idtransaksi=<?= $idTransaksi ?>&kode=<?= $kode ?>"><button class="btn btn-danger" type="button">Cetak Invoice</button></a>
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
