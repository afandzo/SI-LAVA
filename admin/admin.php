<?php
include "../db.php";
$page = "dashboard";
if (empty($_SESSION['loginadmin'])) {
  header("Location: ../index.php");
}
$queryUser = "SELECT * FROM user";
$execUser = mysqli_query($conn, $queryUser);
$dataUser = mysqli_fetch_all($execUser, MYSQLI_ASSOC);
// Harian
$queryTransaksiHarian = "SELECT SUM(total_harga) as total FROM tb_transaksi WHERE CAST(tgl AS DATE) = CURRENT_DATE";
$execTransaksiHarian = mysqli_query($conn, $queryTransaksiHarian);
$dataTransaksiHarian = mysqli_fetch_assoc($execTransaksiHarian);
$totalHarian = $dataTransaksiHarian['total'] ?? 0;
// Bulanan
$queryTransaksiBulanan = 'SELECT SUM(total_harga) as total FROM tb_transaksi WHERE DATE_FORMAT(tgl, "%m") = DATE_FORMAT(CURRENT_DATE, "%m")';
$execTransaksiBulanan = mysqli_query($conn, $queryTransaksiBulanan);
$dataTransaksiBulanan = mysqli_fetch_assoc($execTransaksiBulanan);
$totalBulanan = $dataTransaksiBulanan['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "head_css.php"; ?>
  <title>Document</title>
</head>
<body class="theme-dark" style="overflow-y: auto;">
  <div id="app">
    <?php include "sidebar.php" ?>
    <div id="main">
      <div class="page-heading">
        <h3>Sistem Informasi Manajemen Laundry</h3>
      </div>
      <div class="col-12 col-lg-12">
        <div class="row">
          <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">transaksi hari ini</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. <?= $totalHarian ?></div>
                  </div>
                  <div class="col-auto">
                    <i class="bi bi-currency-dollar" style="font-size: 2.8rem;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
              <div class="card-body">
                <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">transaksi bulan ini</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. <?= $totalBulanan ?></div>
                  </div>
                  <div class="col-auto">
                    <i class="bi bi-currency-dollar" style="font-size: 2.8rem;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4>Halaman Hak Akses Admin</h4>
                <hr>
                <h4 class="fs-5">Sistem Informasi Manajemen Laundry</h4>
              </div>
              <div class="card-body">
                <div class="col-md-10">
                  <p class="fs-4">Merupakan sebuah sistem yang digunakan untuk mengelola data kebutuhan Laundry mulai dari pemesanan, status, data penjualan, data kasir, data pengguna, dan laporan transaksi.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="main">
    <div class="page-heading">
      <div class="page-title">
        <div class="row">
          <section class="section">
            <div class="container-fluid">
              <div id="content">
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