<?php
include "../db.php";
include "../filelog.php";
if (empty($_SESSION['loginkasir'])) {
  header("Location: ../index.php");
}
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
            </table><br>
            <span class="text-right">
              <h3>Total Bayar : Rp. <?= $dataTransaksi['total_harga'] ?></h3>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/js/bootstrap.js"></script>
  <script src="../assets/js/app.js"></script>
</body>

</html>