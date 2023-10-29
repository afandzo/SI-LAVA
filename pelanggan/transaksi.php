<?php
include "../db.php";
include "../filelog.php";
if (empty($_SESSION['loginpelanggan'])) {
  header("Location: ../index.php");
}
$page = "transaksi";
//ambil data transaksi
$queryTransaksi = "SELECT * FROM tb_transaksi";
$execTransaksi = mysqli_query($conn, $queryTransaksi);
$dataTransaksi = mysqli_fetch_all($execTransaksi, MYSQLI_ASSOC);
//ambil data pelanggan
$queryKasir = "SELECT * FROM user WHERE role = 'kasir'";
$execKasir = mysqli_query($conn, $queryKasir);
$dataKasir = mysqli_fetch_all($execKasir, MYSQLI_ASSOC);
// Ambil data paket
$queryPaket = "SELECT * FROM tb_paket";
$execPaket = mysqli_query($conn, $queryPaket);
$dataPaket = mysqli_fetch_all($execPaket, MYSQLI_ASSOC);

$semuaHarga = [];
foreach ($dataPaket as $paket) {
  $semuaHarga[] += $paket['harga'];
}
// var_dump($semuaHarga);
$semuaId = [];
foreach ($dataPaket as $paket) {
  $semuaId[] += $paket['id'];
}
// var_dump($semuaId);
$muatan = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$random = "INV-" . substr(str_shuffle($muatan), 0, 7);
if (isset($_POST['simpan'])) {
  $queryTambah = "SELECT * FROM tb_transaksi";
  $execTambah = mysqli_query($conn, $queryTambah);
  $dataTambah = mysqli_fetch_array($execTambah, MYSQLI_ASSOC);
  $kode = $_POST['random'];
  $tgl = $_POST['tgl'];
  $batas_waktu = $_POST['batas_waktu'];
  $tgl_bayar = null;
  $status = 'baru';
  $status_bayar = 'belum_dibayar';
  $id_kasir = $_POST['kasir'];
  $id_pelanggan = $_SESSION['id'];
  $insert = "INSERT INTO `tb_transaksi` (`id`, `kode_invoice`, `id_pelanggan`, `tgl`, `batas_waktu`, `tgl_bayar`, `diskon`, `status`, `dibayar`, `id_user`) VALUES (NULL, '$kode', '$id_pelanggan', '$tgl', '$batas_waktu', '$tgl_bayar', '', '$status', '$status_bayar', '$id_kasir');";
  $sql = mysqli_query($conn, $insert);

  $kode = $_POST['random'];
  $queryCek = "SELECT * FROM tb_transaksi WHERE kode_invoice = '$kode'";
  $execCek = mysqli_query($conn, $queryCek);
  if (mysqli_num_rows($execCek) == 1) {
    $dataTransaksi = mysqli_fetch_assoc($execCek);
    $idTransaksi = $dataTransaksi['id'];
    foreach ($dataPaket as $paket) {
      $namaPaket = $paket['nama_paket'];
      $idPaket = $paket['id'];
      $qty = $_POST["qty" . "$idPaket"];
      $keterangan = $_POST["keterangan" . "$idPaket"];
      if ($qty > 0) {
        $queryTambah = "INSERT INTO tb_detail_transaksi (id, id_transaksi, id_paket, qty, keterangan) VALUES (NULL, '$idTransaksi', '$idPaket', '$qty', '$keterangan');";
        $execTambah = mysqli_query($conn, $queryTambah);
      }
    }
    if ($execTambah) {
      echo "<script>alert('Transaksi Berhasil');</script>";
      // Check if the customer has reached 10 transactions
      $queryCountTransactions = "SELECT COUNT(*) AS total FROM tb_transaksi WHERE id_pelanggan = '$id_pelanggan'";
      $execCountTransactions = mysqli_query($conn, $queryCountTransactions);
      $dataCountTransactions = mysqli_fetch_assoc($execCountTransactions);
      $totalTransactions = $dataCountTransactions['total'];
      if ($totalTransactions % 10 == 0) {
        $customerQuery = "SELECT nama FROM tb_pelanggan WHERE id = '$id_pelanggan'";
        $customerResult = mysqli_query($conn, $customerQuery);
        $customerData = mysqli_fetch_assoc($customerResult);
        $customerName = $customerData['nama'];
        echo "<script>alert('Selamat! Kepada Pelanggan `" . $customerName . "` mendapatkan hadiah.');</script>";
    }
    } else {
      echo "
        <script>
        alert('Transaksi Gagal');        
        </script>
        ";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "head_css.php"; ?>
  <title>Transaksi</title>
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
                  <center>
                    <div class="card-header">
                      <h4 class="card-title">Transaksi</h4>
                    </div>
                  </center>
                  <div class="card-content">
                    <div class="card-body">
                      <form class="form" action="" method="post">
                        <div class="row">
                          <center>
                            <h3>Kode Transaksi</h3>
                            <div class="col-md-6">
                              <input class="form-control alert alert-secondary" name="kode-pemesanan" value="<?= $random; ?>" style="text-align: center;" readonly="">
                            </div>
                          </center>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label>Nama Pelanggan</label>
                              <input value="<?= $_SESSION['nama'] ?>" type="text" id="user" class="form-control" readonly="">
                              <input value="<?= $_SESSION['id'] ?>" type="text" name="nama_plgn" hidden>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="tanggal">Tanggal</label>
                              <input type="datetime-local" id="tanggal" class="form-control" placeholder="Tanggal" name="tgl">
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="batas">Batas Waktu</label>
                              <input type="datetime-local" id="batas" class="form-control" placeholder="Batas Waktu" name="batas_waktu">
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="user">Pilih Kasir</label>
                              <div class="form-group">
                                <select class="form-select" id="nama_pelanggan" name="kasir">
                                  <?php foreach ($dataKasir as $kasir) { ?>
                                    <option value="<?= $kasir['id'] ?>"><?= $kasir['nama'] ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-12">
                            <div class="form-group">
                              <label for="">
                                <h6>Paket yang dipesan</h6>
                              </label>
                              <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                  <thead>
                                    <tr>
                                      <th>No</th>
                                      <th>Nama Paket</th>
                                      <th>Jenis</th>
                                      <th>Harga</th>
                                      <th>Berat(Kg)</th>
                                      <th>Keterangan</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php $i = 0; ?>
                                    <?php foreach ($dataPaket as $paket) : ?>
                                      <?php $i++; ?>
                                      <tr>
                                        <td><?= $i; ?></td>
                                        <td><?= $paket['nama_paket']; ?></td>
                                        <td><?= $paket['jenis']; ?></td>
                                        <td><?= $paket['harga']; ?></td>
                                        <td>
                                          <input type="number" value="0" name="qty<?= $paket['id'] ?>" id="qty<?= $paket['id'] ?>" class="form-control" onkeyup="hitung()">
                                        </td>
                                        <td><input type="text" name="keterangan<?= $paket['id'] ?>" id="" value=" " class="form-control"></td>
                                      </tr>
                                    <?php endforeach ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="user">Total Harga</label>
                              <input class="form-control" type="text" name="harga" id="hasil_paket_akhir" onkeyup="hitung()" readonly="">
                            </div>
                          </div>
                          <input type="text" name="random" id="" class="visually-hidden" value="<?= $random; ?>">
                          <div class="col-12 d-flex justify-content-end">
                            <button name="simpan" id="simpan" type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                          </div>
                        </div>
                      </form>
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
  <script>
    function hitung() {
      var tampil = document.getElementById('hasil_paket_akhir');
      <?php $b = 0 ?>
      var harga = [];
      <?php foreach ($semuaHarga as $harga) { ?>
        harga[<?= $b ?>] = <?= $harga ?>;
        <?php $b++ ?>
      <?php } ?>
      <?php $s = 0 ?>
      var totalHarga = 0;
      <?php foreach ($dataPaket as $paket) : ?>
        <?php $idKetPa = $paket['id'] ?>
        var inputan = document.getElementById('qty' + '<?= $idKetPa ?>').value
        if (inputan > 0) {
          totalHarga += (parseInt(inputan) * parseInt(harga[<?= $s ?>]));
        }
        <?php $s++ ?>
      <?php endforeach ?>
      tampil.value = totalHarga
      console.log(harga);
    }
  </script>
</body>
</html>