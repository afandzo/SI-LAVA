<?php
include "../db.php";
include "../filelog.php";
if (empty($_SESSION['loginkasir'])) {
  header("Location: ../index.php");
}
$page = "transaksi";
//ambil data transaksi
$queryTransaksi = "SELECT * FROM tb_transaksi";
$execTransaksi = mysqli_query($conn, $queryTransaksi);
$dataTransaksi = mysqli_fetch_all($execTransaksi, MYSQLI_ASSOC);
//ambil data pelanggan
$querypelanggan = "SELECT * FROM tb_pelanggan";
$execPelanggan = mysqli_query($conn, $querypelanggan);
$dataPelanggan = mysqli_fetch_all($execPelanggan, MYSQLI_ASSOC);
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
  $nama_pelanggan = $_POST['nama_plgn'];
  $kode = $_POST['random'];
  $tgl = $_POST['tgl'];
  $batas_waktu = $_POST['batas_waktu'];
  $tgl_bayar = $_POST['tgl_bayar'];
  $status = $_POST['status'];
  $status_bayar = $_POST['bayar'];
  $layanan_antar = $_POST['layanan_antar'];
  $status_antar = ($layanan_antar == '0') ? null : 'blm_diantar';
  // var_dump($status_antar);die;
  $layanan_jemput = $_POST['layanan_jemput'];;
  $status_jemput = ($layanan_jemput == '0') ? null : 'blm_dijemput';
  // $nama_kasir = $_POST['kasir'];
  $total_harga = $_POST['total_harga'];
  $iduser = $_SESSION['id'];
  $insert = "INSERT INTO `tb_transaksi` (`id`, `kode_invoice`, `id_pelanggan`, `tgl`, `batas_waktu`, `tgl_bayar`, `diskon`, `status`, `dibayar`, `layanan_antar`, `status_antar`, `layanan_jemput`, `status_jemput`, `total_harga`, `id_user`) VALUES (NULL, '$kode', '$nama_pelanggan', '$tgl', '$batas_waktu', '$tgl_bayar', '', '$status', '$status_bayar', '$layanan_antar', '$status_antar', '$layanan_jemput', '$status_jemput', '$total_harga', '$iduser');";
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
      $queryCountTransactions = "SELECT COUNT(*) AS total FROM tb_transaksi WHERE id_pelanggan = '$nama_pelanggan'";
      $execCountTransactions = mysqli_query($conn, $queryCountTransactions);
      $dataCountTransactions = mysqli_fetch_assoc($execCountTransactions);
      $totalTransactions = $dataCountTransactions['total'];
      if ($totalTransactions % 10 == 0) {
        $customerQuery = "SELECT nama FROM tb_pelanggan WHERE id = '$nama_pelanggan'";
        $customerResult = mysqli_query($conn, $customerQuery);
        $customerData = mysqli_fetch_assoc($customerResult);
        $customerName = $customerData['nama'];
        echo "<script>alert('Selamat! Pelanggan " . $customerName . " mendapatkan hadiah.');</script>";
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
                              <div class="form-group">
                                <select class="form-select" id="nama_pelanggan" name="nama_plgn">
                                  <?php foreach ($dataPelanggan as $pelanggan) { ?>
                                    <option value="<?= $pelanggan['id'] ?>"><?= $pelanggan['nama'] ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="tanggal">Tanggal</label>
                              <input type="datetime-local" id="tanggal" class="form-control" placeholder="Tanggal" name="tgl" onchange="setBatasWaktu()" required>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="batas">Batas Waktu</label>
                              <input type="datetime-local" id="batas" class="form-control" placeholder="Batas Waktu" name="batas_waktu" onchange="hitung()">
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="bayar">Tanggal Bayar</label>
                              <input type="datetime-local" id="bayar" class="form-control" name="tgl_bayar" placeholder="Tanggal Bayar">
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label>Status</label>
                              <div class=" form-group">
                                <select class="form-select" id="basicSelect" name="status">
                                  <option value="baru">Baru</option>
                                  <option value="proses">Proses</option>
                                  <option value="selesai">Selesai</option>
                                  <option value="diambil">Diambil</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label>Status Bayar</label>
                              <div class=" form-group">
                                <select class="form-select" id="basicSelect" name="bayar">
                                  <option value="belum_dibayar">Belum Dibayar</option>
                                  <option value="dibayar">Dibayar</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label>Layanan Antar</label>
                              <div class="form-group">
                                <select class="form-select" id="layananAntar" name="layanan_antar" onchange="hitung()">
                                  <?php
                                  $queryLayanan = "SELECT * FROM tb_layanan WHERE layanan = 'antar'";
                                  $execLayanan = mysqli_query($conn, $queryLayanan);
                                  $dataLayanan = mysqli_fetch_assoc($execLayanan);
                                  ?>
                                  <option value="0">TIDAK</option>
                                  <option value="<?= $dataLayanan['harga'] ?>">YA</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label>Layanan Jemput</label>
                              <div class=" form-group">
                                <select class="form-select" id="layananJemput" name="layanan_jemput" onchange="hitung()">
                                  <?php
                                  // data layanan
                                  $queryLayananJ = "SELECT * FROM tb_layanan WHERE layanan = 'jemput'";
                                  $execLayananJ = mysqli_query($conn, $queryLayananJ);
                                  $dataLayananJ = mysqli_fetch_assoc($execLayananJ);
                                  ?>
                                  <option value="0">TIDAK</option>
                                  <option value="<?= $dataLayananJ['harga'] ?>">YA</option>
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
                                          <input type="number" value="0" name="qty<?= $paket['id'] ?>" id="qty<?= $paket['id'] ?>" class="form-control" onchange="hitung()">
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
                              <label for="user">Nama Kasir</label>
                              <input value="<?= $_SESSION['nama'] ?>" type="text" id="user" class="form-control" name="kasir" placeholder="Nama Kasir" readonly="">
                            </div>
                          </div>
                          <div class="col-md-6 col-12">
                            <div class="form-group">
                              <label for="user">Total Harga</label>
                              <input class="form-control" type="text" name="total_harga" id="hasil_paket_akhir" onkeyup="hitung()" readonly="">
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
  var harga = [];
  <?php $b = 0 ?>
  <?php foreach ($semuaHarga as $harga) { ?>
    harga[<?= $b ?>] = <?= $harga ?>;
    <?php $b++ ?>
  <?php } ?>
  var totalHarga = 0;
  <?php $s = 0 ?>
  <?php foreach ($dataPaket as $paket) : ?>
    <?php $idKetPa = $paket['id'] ?>
    var inputan = document.getElementById('qty' + '<?= $idKetPa ?>').value
    if (inputan > 0) {
      totalHarga += (parseInt(inputan) * parseInt(harga[<?= $s ?>]));
    }
    <?php $s++ ?>
  <?php endforeach ?>

  // Ambil tanggal transaksi
  var tanggalTransaksi = new Date(document.getElementById('tanggal').value);

  // Ambil tanggal batas waktu
  var batasWaktu = new Date(document.getElementById('batas').value);

  // Hitung perbedaan dalam hari
  var timeDiff = batasWaktu - tanggalTransaksi;
  var diffDays = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

  // Terapkan peningkatan persentase yang berbeda berdasarkan perbedaan hari
  if (diffDays >= 3) {
    // Tidak ada biaya tambahan
  } else if (diffDays === 2) {
    // Tambahkan total harga sebesar 10%
    totalHarga += totalHarga * 0.05;
  } else if (diffDays === 1) {
    // Tambahkan total harga sebesar 20%
    totalHarga += totalHarga * 0.1;
  }

  // Tambahkan logika untuk layanan antar dan jemput
  var layananAntar = document.getElementById('layananAntar');
  var hargaLayananAntar = layananAntar.options[layananAntar.selectedIndex].value;
  var layananJemput = document.getElementById('layananJemput');
  var hargaLayananJemput = layananJemput.options[layananJemput.selectedIndex].value;

  totalHarga += parseInt(hargaLayananAntar);
  totalHarga += parseInt(hargaLayananJemput);

  tampil.value = totalHarga;
}


  function setBatasWaktu() {
    var tanggalTransaksi = document.getElementById('tanggal').value;
    var tanggal = new Date(tanggalTransaksi);
    tanggal.setDate(tanggal.getDate() + 3); // Tambah 3 hari

    var tahun = tanggal.getFullYear();
    var bulan = (tanggal.getMonth() + 1).toString().padStart(2, '0');
    var hari = tanggal.getDate().toString().padStart(2, '0');
    var jam = tanggal.getHours().toString().padStart(2, '0');
    var menit = tanggal.getMinutes().toString().padStart(2, '0');

    var batasWaktu = `${tahun}-${bulan}-${hari}T${jam}:${menit}`;
    document.getElementById('batas').value = batasWaktu;
  }
  </script>
</body>
</html>