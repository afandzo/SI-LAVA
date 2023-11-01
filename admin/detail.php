<?php
include "../db.php";
include "../filelog.php";
if (empty($_SESSION['loginadmin'])) {
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
$semuaHarga = [];
foreach ($dataPaket as $paket) {
  $semuaHarga[] += $paket['harga'];
}
// var_dump($semuaHarga);
$semuaId = [];
foreach ($dataPaket as $paket) {
  $semuaId[] += $paket['id'];
}
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
// Edit detail transaksi
if (isset($_POST['simpan'])) {
  // Ubah data tb transaksi
  $pelanggan = $_POST['pelanggan'];
  $tgl = $_POST['tgl'];
  $batastgl = $_POST['btstgl'];
  $tglbayar = $_POST['tglbayar'];
  $status = $_POST['status'];
  $status_bayar = $_POST['status_bayar'];
  $layanan_antar = $_POST['layanan_antar'];
  $status_antar = ($layanan_antar == '0') ? null : $_POST['status_antar'];
  $layanan_jemput = $_POST['layanan_jemput'];;
  $status_jemput = ($layanan_jemput == '0') ? null : $_POST['status_jemput'];
  $total_harga = $_POST['total_harga'];
  if ($tgl == '0000-00-00 00:00:00') {
    exit;
  }
  $queryEditData = "UPDATE `tb_transaksi` SET `id_pelanggan` = '$pelanggan', `tgl` = '$tgl', `batas_waktu` = '$batastgl', `tgl_bayar` = '$tglbayar', `status` = '$status', `dibayar` = '$status_bayar', `layanan_antar` = '$layanan_antar', `status_antar` = '$status_antar', `layanan_jemput` = '$layanan_jemput', `status_jemput` = '$status_jemput', `total_harga` = '$total_harga' WHERE `tb_transaksi`.`id` = $idTransaksi;";
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
                                    <input type="hidden" name="kode_invoice" id="kode_invoice" value="<?= $kode ?>">
                                  </tr>
                                  <tr>
                                    <td>Pelanggan</td>
                                    <td>: <?= $dataPelanggan['nama'] ?></td>
                                  </tr>
                                  <tr>
                                    <td>No. Telp</td>
                                    <td>: <?= $dataPelanggan['tlp'] ?></td>
                                  </tr>
                                  <tr>
                                    <td>Pelanggan</td>
                                    <td>: <?= $dataPelanggan['alamat'] ?></td>
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
  <script>
    function hitung() {
    var tampil = document.getElementById('update_hasil_paket_akhir');
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
      var inputan = document.getElementById('update_qty' + '<?= $idKetPa ?>').value;
      if (inputan > 0) {
        totalHarga += (parseInt(inputan) * parseInt(harga[<?= $s ?>]));
      }
      <?php $s++ ?>
    <?php endforeach ?>

    // Ambil tanggal transaksi
    var tanggalTransaksi = new Date(document.getElementById('update_tanggal').value);

    // Ambil tanggal batas waktu
    var batasWaktu = new Date(document.getElementById('update_batas').value);

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
    var layananAntar = document.getElementById('update_layananAntar');
    var hargaLayananAntar = layananAntar.options[layananAntar.selectedIndex].value;
    var layananJemput = document.getElementById('update_layananJemput');
    var hargaLayananJemput = layananJemput.options[layananJemput.selectedIndex].value;

    totalHarga += parseInt(hargaLayananAntar);
    totalHarga += parseInt(hargaLayananJemput);

    tampil.value = totalHarga;
  }


  function setBatasWaktu() {
    var tanggalTransaksi = document.getElementById('update_tanggal').value;
    var tanggal = new Date(tanggalTransaksi);
    tanggal.setDate(tanggal.getDate() + 3); // Tambah 3 hari

    var tahun = tanggal.getFullYear();
    var bulan = (tanggal.getMonth() + 1).toString().padStart(2, '0');
    var hari = tanggal.getDate().toString().padStart(2, '0');
    var jam = tanggal.getHours().toString().padStart(2, '0');
    var menit = tanggal.getMinutes().toString().padStart(2, '0');

    var batasWaktu = `${tahun}-${bulan}-${hari}T${jam}:${menit}`;
    document.getElementById('update_batas').value = batasWaktu;
  }
  </script>
</body>

</html>
