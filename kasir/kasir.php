<?php
include "../db.php";
include "../filelog.php";
if (empty($_SESSION['loginkasir'])) {
  header("Location: ../index.php");
}

$page = "dashboard";

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/main/app.css">
  <link rel="stylesheet" href="../assets/css/main/app-dark.css">
  <link rel="shortcut icon" href="../assets/images/logo/favicon.svg" type="image/x-icon">
  <link rel="shortcut icon" href="../assets/images/logo/favicon.png" type="image/png">


  <link rel="stylesheet" href="../assets/css/shared/iconly.css">
  <title>Dashboard</title>
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
            <div class="container-fluid">
              <div class="row">
                <div class="col-12">
                  <div class="card">
                    <div class="card-header">
                      <h4>Halaman Hak Akses Kasir</h4>
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
          </section>
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