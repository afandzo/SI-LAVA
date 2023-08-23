<?php
include "../db.php";
include "../filelog.php";
if (empty($_SESSION['loginadmin'])) {
  header("Location: ../index.php");
}

// $page = "riwayat_transaksi";

//ambil semua data pelanggan
$querySemuaPelanggan = "SELECT * FROM tb_pelanggan";
$execSemuaPelanggan = mysqli_query($conn, $querySemuaPelanggan);
$dataSemuaPelanggan = mysqli_fetch_all($execSemuaPelanggan, MYSQLI_ASSOC);
// var_dump($dataSemuaPelanggan);

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/main/app.css">
  <link rel="shortcut icon" href="../assets/images/logo/favicon.svg" type="image/x-icon">
  <link rel="shortcut icon" href="../assets/images/logo/favicon.png" type="image/png">
  <title>Cetak Detail</title>
  <style>
    @media print {
      @page {
        size: landscape;
      }
    }
  </style>
</head>

<body onload="window.print();">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header py-3">
        <div class="row">
          <div class="col-sm-2 float-left">
            <img src="../assets/images/logo/logo2.png" width="75px" alt="brand">
          </div>
          <div class="col-sm-10 float-left">
            <h3>AVA LAUNDRY</h3>
            <h6>Jl. Jalan, Kebak, Kec. Jumantono, Kabupaten Karanganyar, Telp 0812-1591-2946</h6>
            <h6>@avalaundry</h6>
          </div>
        </div>
      </div>
      <div class="card-body">
        <!--rows -->
        <div class="row">
          <div class="col-sm-4">
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
                  <tr>
                    <td>Pembayaran</td>
                    <td>: <?= $dataTransaksi['dibayar'] ?></td>
                  </tr>
                  <tr>
                    <td>Status</td>
                    <td>: <?= $dataTransaksi['status'] ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-sm-8">

          </div>
        </div>
        <!--rows -->
        <div>
          <div class="col-sm-12">
            <table class="table table-bordered">
              <thead class="text-center">
                <tr>
                  <th rowspan="2">No</th>
                  <th rowspan="2">Nama Paket</th>
                  <th rowspan="2">Jenis Paket</th>
                  <th rowspan="2">Tarif</th>
                  <th rowspan="2">Berat</th>
                  <th rowspan="2">Total Biaya</th>
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
            </table><br>
            <span class="text-right">
              <?php
              $idTransaksi = $_GET['idtransaksi'];
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

              <h3>Total Bayar : Rp. <?= $hargaA ?></h3>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/js/bootstrap.js"></script>
  <script src="../assets/js/app.js"></script>

  <!-- Need: Apexcharts -->
  <script src="../assets/extensions/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/js/pages/dashboard.js"></script>
</body>

</html>