<?php include "../db.php";
if (!isset($_SESSION['loginadmin'])) {
  header("location: ../index.php");
  exit;
}
$page = "laporan_transaksi";
// Cari
if (isset($_POST['cari'])) {
  $awal = date("Y-m-d H:i:s", strtotime($_POST["awal"]));
  $akhir = date("Y-m-d H:i:s", strtotime($_POST["akhir"]));
  // var_dump($awal, $akhir);
  $queryTransaksi = "SELECT id,id_pelanggan FROM tb_transaksi WHERE tgl BETWEEN '$awal' AND '$akhir'";
  $execTransaksi = mysqli_query($conn, $queryTransaksi);
  $dataTransaksi = mysqli_fetch_all($execTransaksi, MYSQLI_ASSOC);
  // var_dump($dataTransaksi);
  $semuaId = [];
  $semuaPelanggan = [];
  foreach ($dataTransaksi as $transaksi) {
    $semuaId[] += $transaksi['id'];
    $semuaPelanggan[] += $transaksi['id_pelanggan'];
  }
  if (mysqli_num_rows($execTransaksi) == 0) {
    $error = true;
  }
  $listQuery = [];
  $i = 0;
  foreach ($semuaId as $id) {
    $listQuery[$i] = "SELECT * FROM tb_detail_transaksi WHERE id_transaksi = $id";
    $i++;
  }
  if (!@$error) {
    $coba = true;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "head_css.php"; ?>
  <title>Laporan Transaksi</title>
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
</head>
<body>
  <div id="app">
    <?php include "sidebar.php";?>
    <div id="main">
      <p class="fs-4">Laporan Transaksi</p>
      <div class="row mb-3">
        <div class="col-12">
          <div class="card">
            <form action="" method="post">
              <div class="card-content">
                <div class="col-12">
                  <div class="row p-4">
                    <div class="col-4">
                      <input type="datetime-local" class="form-control" name="awal" id="" value="<?= @$awal ?>">
                    </div>
                    <div class="col-4">
                      <input type="datetime-local" class="form-control" name="akhir" id="" value="<?= @$akhir ?>">
                    </div>
                    <div class="col-3">
                      <button type="submit" class="btn btn-secondary" name="cari">Tampilkan</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php if (@$coba) :  ?>
        <div class="row mb-3">
          <div class="col-12">
            <div class="card p-4">
              <div class="card-content">
                <div class="table-responsive">
                  <table class="table mb-3" id="table1">
                    <tr>
                      <th class="col-1">No</th>
                      <th class="col-2">Tanggal</th>
                      <th class="col-2">Kode Invoice</th>
                      <th class="col-1">Pelanggan</th>
                      <th class="col-3 ">Layanan</th>
                      <th class="col-2">Total Biyaya</th>
                    </tr>
                    <?php $no = 1; ?>
                    <?php $i = 0; ?>
                    <?php $b = 0; ?>
                    <?php $bayar = []; ?>
                    <?php foreach ($listQuery as $query) {
                      // Detail Transaksi
                      $execQuery = mysqli_query($conn, $query);
                      $dataQuery = mysqli_fetch_assoc($execQuery);
                      // Transaksi
                      $idTransaksi = $semuaId[$i];
                      $queryTransaksiSatu = "SELECT * FROM tb_transaksi WHERE id = $idTransaksi";
                      $execTransaksiSatu = mysqli_query($conn, $queryTransaksiSatu);
                      $dataTransaksiSatu = mysqli_fetch_assoc($execTransaksiSatu);
                      // Pelanggan
                      $idPelanggan = $semuaPelanggan[$i];
                      $queryPelanggan = "SELECT * FROM tb_pelanggan WHERE id = $idPelanggan";
                      $execPelanggan = mysqli_query($conn, $queryPelanggan);
                      $dataPelanggan = mysqli_fetch_assoc($execPelanggan);
                      // Paket
                      $queryPaket = "SELECT * FROM tb_detail_transaksi WHERE id_transaksi = $idTransaksi";
                      $execPaket = mysqli_query($conn, $queryPaket);
                      $dataPaket = mysqli_fetch_all($execPaket, MYSQLI_ASSOC);
                      $beratPaket = [];
                      $semuaPaket = [];
                      foreach ($dataPaket as $paket) {
                        $beratPaket[] += $paket['qty'];
                        $semuaPaket[] += $paket['id_paket'];
                      }
                      $c = 0;
                      foreach ($dataPaket as $hrg) {
                        if ($hrg['id_paket'] == $semuaPaket[$c] && $hrg['id_transaksi'] == $idTransaksi) {
                          $idPaket = $hrg['id_paket'];
                          $queryHargaPaket = "SELECT * FROM tb_paket WHERE id = $idPaket";
                          $execHargaPaket = mysqli_query($conn, $queryHargaPaket);
                          $dataHargaPaket = mysqli_fetch_assoc($execHargaPaket);
                          $hargaPaket = $dataHargaPaket['harga'];
                          @$bayar[$i] += $beratPaket[$c] * $hargaPaket;
                          $c++;
                        }
                      }
                    ?>
                      <tr>
                        <td><?= $no ?></td>
                        <td><?= $dataTransaksiSatu['tgl'] ?></td>
                        <td><?= $dataTransaksiSatu['kode_invoice'] ?></td>
                        <td><?= $dataPelanggan['nama'] ?></td>
                        <td>
                          <ul class="list-group">
                            <?php $a = 0;
                            foreach ($semuaPaket as $pkt) :
                              $idAmbilPaket = $semuaPaket[$a];
                              $queryAmbilPaket = "SELECT * FROM tb_paket WHERE id = $idAmbilPaket";
                              $execAmbilPaket = mysqli_query($conn, $queryAmbilPaket);
                              $dataAmbilPaket = mysqli_fetch_assoc($execAmbilPaket);
                            ?>
                              <li class="list-group-item"><?= $dataAmbilPaket['nama_paket'] ?> (<?= $beratPaket[$a] ?> Kg)</li>
                              <?php $a++ ?>
                            <?php endforeach ?>
                          </ul>
                        </td>
                        <td>Rp. <?= $bayar[$i]; ?></td>
                      </tr>
                      <?php $i++ ?>
                      <?php $no++ ?>
                    <?php } ?>
                    <?php $totalHarga?>
                    <?php foreach ($bayar as $tes) {
                      @$totalHarga += $tes;
                    }
                    ?>
                    <tr>
                      <td colspan="5">Total</td>
                      <td>Rp. <?= $totalHarga ?></td>
                    </tr>
                  </table>
                </div>
              </div>
              <div class="card-footer d-flex justify-content-between">
                <a href="cetak.php?awal=<?= $awal ?>&akhir=<?= $akhir ?>"><button class="btn btn-danger" type="button">PRINT</button></a>
              </div>
            <?php endif; ?>
            <?php if (@$error) : ?>
              <div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i>Tidak ada data transaksi yang ditemukan</div>
            <?php endif ?>
            </div>
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