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
    <div class="page-content">
      <section class="row">
        <div class="col-12 col-lg-9">
          <div class="row">
            <div class="col-md-4 col-12">
              <div class="card">
                <div class="card-body px-4 py-4-5" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                  <div class="row">
                    <a href="data_pelanggan.php" class="btn btn-lg btn-primary" style="font-size: 30px;"><i class="bi bi-people-fill"></i></a>
                  </div>
                  <div class="row" style="margin-top: 10px;">
                    <span style="font-size: 20px;">Data Pelanggan</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-12">
              <div class="card">
                <div class="card-body px-4 py-4-5" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                  <div class="row">
                    <a href="transaksi.php" class="btn btn-lg btn-success" style="font-size: 30px;"><i class="bi bi-cart-plus-fill"></i></a>
                  </div>
                  <div class="row" style="margin-top: 10px;">
                    <span style="font-size: 20px;">Data Transaksi</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-12">
              <div class="card">
                <div class="card-body px-4 py-4-5" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                  <div class="row">
                    <a href="riwayat_transaksi.php" class="btn btn-lg btn-secondary" style="font-size: 30px;"><i class="bi bi-clock-history"></i></a>
                  </div>
                  <div class="row" style="margin-top: 10px;">
                    <span style="font-size: 20px;">Riwayat Transaksi</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
  <script src="../assets/js/bootstrap.js"></script>
  <script src="../assets/js/app.js"></script>
</body>

</html>