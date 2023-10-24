<?php
include "db.php";
include "filelog.php";
if (!empty($_SESSION['username'])) {
  header("Location: logout.php");
}

if (isset($_POST['submit'])) {
  $username = $_POST['username'] ?? null;
  $password = $_POST['password'] ?? null;
  $result = mysqli_query(
    $conn,
    "SELECT * FROM user WHERE username = '$username'"
  );
  $row = mysqli_fetch_assoc($result);

  if (empty($row)) {
    $error = true;
  } elseif ($username == $row['username'] && $password == $row['password'] && $row['role'] == 'admin') {
    $log = $row['nama'] . "  " . "(" . $row['role'] . ")" . "  " . "Melakukan Login";
    logger($log, "../../../../");
    $_SESSION['id'] = $row['id'];
    $_SESSION['nama'] = $row['nama'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['password'] = $row['password'];
    $_SESSION['role'] = $row['role'];
    $_SESSION['loginadmin'] = true;
    header('location: admin/admin.php');
    exit;
  } elseif ($username == $row['username'] && $password == $row['password'] && $row['role'] == 'kasir') {
    $log = $row['nama'] . "  " . "(" . $row['role'] . ")" . "  " . "Melakukan Login";
    logger($log, "../../../../");
    $_SESSION['id'] = $row['id'];
    $_SESSION['nama'] = $row['nama'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['password'] = $row['password'];
    $_SESSION['role'] = $row['role'];
    $_SESSION['loginkasir'] = true;
    header('location: kasir/kasir.php');
    exit;
  } else {
    $salah = true;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/main/app.css">
  <link rel="stylesheet" href="assets/css/main/app-dark.css">
  <link rel="stylesheet" href="assets/css/main/app.css">
  <link rel="stylesheet" href="assets/css/pages/auth.css">
  <link rel="stylesheet" href="view/assets/css/templatemo-chain-app-dev.css">
  <link rel="stylesheet" href="view/assets/css/animated.css">
  <link rel="stylesheet" href="view/assets/css/owl.css">
  <?php if (isset($error)) : ?>
    <link rel="stylesheet" type="text/css" href="toastify/script.css" />
    <link rel="stylesheet" type="text/css" href="toastify/toastify.css" />
  <?php endif; ?>
  <?php if (isset($salah)) : ?>
    <link rel="stylesheet" type="text/css" href="toastify/script.css" />
    <link rel="stylesheet" type="text/css" href="toastify/toastify.css" />
  <?php endif; ?>
  <title>Login</title>
</head>

<body>
  <div id="auth">
    <div class="row h-100">
      <div class="col-lg-5 col-12">
        <div id="auth-left">
          <div class="col-sm-3">
            <img src="assets/images/logo/logo2.png" width="100px" alt="brand">
          </div>
          <h1 class="auth-title">Log in.</h1>
          <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p>
          <form action="" method="post"">
            <div class=" form-group position-relative has-icon-left mb-4">
              <input type="text" class="form-control form-control-xl" placeholder="Username" name="username">
              <div class="form-control-icon">
                <i class="bi bi-person"></i>
              </div>
              </div>
              <div class="form-group position-relative has-icon-left mb-4">
                <input type="password" class="form-control form-control-xl" placeholder="Password" name="password">
                <div class="form-control-icon">
                  <i class="bi bi-shield-lock"></i>
                </div>
              </div>
              <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" type="submit" name="submit"">Log in</button>
          </form>
          <div class=" text-center mt-5 text-lg fs-4">
      </div>
    </div>
  </div>
  <div class="col-lg-7 d-none d-lg-block">
    <div id="auth-right">
      <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
      <footer id="newsletter" style="margin: 0px 0px 0px 0px;">
      </footer>
    </div>
  </div>

  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/app.js"></script>
  <!-- Need: Apexcharts -->
  <script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
  <script src="assets/js/pages/dashboard.js"></script>
  <?php if (isset($error)) : ?>
    <script type="text/javascript" src="toastify/toastify.js"></script>
    <script type="text/javascript" src="toastify/script.js"></script>
  <?php endif; ?>
  <?php if (isset($salah)) : ?>
    <script type="text/javascript" src="toastify/toastify.js"></script>
    <script type="text/javascript" src="toastify/script2.js"></script>
  <?php endif; ?>
</body>

</html>