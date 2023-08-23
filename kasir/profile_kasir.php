<?php
include "../db.php";
include "../filelog.php";
if (empty($_SESSION['loginkasir'])) {
  header("Location: ../index.php");
}

$page = "dashboard";

if (isset($_POST['edit'])) {
  $id = $_POST['id'];
  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $rolee = $_POST['role'];
  $queryUpdateUser = "UPDATE user SET `nama` = '$nama',`username` = '$username', `password`='$password',`role` = '$rolee' WHERE `user`.`id` = $id";
  $execUpdateUser = mysqli_query($conn, $queryUpdateUser);
  if ($execUpdateUser) {
    $log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Mengubah user. Dengan id user (" . $id . "). Pada Data Kasir.";
    logger($log, "../../../../../");
    header("location:profile_kasir.php");
  }
}
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
  <title>Profile</title>
</head>

<body class="theme-dark" style="overflow-y: auto;">
  <div id="app">
    <?php include "sidebar.php"; ?>
    <div id="main">


      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Profil Pengguna</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <!-- <img class="card-img-top" src="../assets/images/faces/3.jpg" alt="Card image"> -->
          <table class="table">
            <tbody>
              <tr>
                <td>Id</td>
                <td width="80%">: <?= $dataUserProfile['id'] ?></td>
              </tr>
              <tr>
                <td>Nama</td>
                <td width="80%">: <?= $dataUserProfile['nama'] ?></td>
              </tr>
              <tr>
                <td>Username</td>
                <td width="80%">: <?= $dataUserProfile['username'] ?></td>
              </tr>
              <tr>
                <td>Password</td>
                <td width="80%">: <?= $dataUserProfile['password'] ?></td>
              </tr>
              <tr>
                <td>Role</td>
                <td width="80%">: <?= $dataUserProfile['role'] ?></td>
              </tr>
              <tr>
                <td>Status</td>
                <td width="80%">: Active</td>
              </tr>
            </tbody>
          </table>
          <a href="" class="btn icon icon-left btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#normal<?= $dataUserProfile['id']; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg> Edit Profile</a>
          <div class="modal fade text-left" id="normal<?= $dataUserProfile['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title" id="myModalLabel33">Update Anggota</h4>
                  <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                  </button>
                </div>

                <form action="" method="post">
                  <div class="modal-body">
                    <label>Nama : </label>
                    <div class="form-group">
                      <input type="text" placeholder="Nama" class="form-control" name="nama" value="<?= $dataUserProfile['nama'] ?>">
                    </div>
                    <label>Username: </label>
                    <div class=" form-group">
                      <input type="text" placeholder="Username" class="form-control" name="username" value="<?= $dataUserProfile['username'] ?>">
                    </div>
                    <label>Password: </label>
                    <div class=" form-group">
                      <input type="password" placeholder="Password" class="form-control" name="password" value="<?= $dataUserProfile['password'] ?>">
                    </div>
                    <label>Role: </label>
                    <div class=" form-group">
                      <select class="form-select" id="basicSelect" name="role">
                        <option value="<?= $dataUserProfile['role'] ?>"><?= $dataUserProfile['role'] ?></option>
                        <!-- <option value="kasir">Kasir</option> -->
                      </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                      <i class="bx bx-x d-block d-sm-none"></i>
                      <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="submit" class="btn btn-primary ml-1" data-bs-dismiss="modal" name="edit" value="EDIT DATA">
                      <input type="text" class="visually-hidden" value="<?= $dataUserProfile['id'] ?>" name="id">
                      <i class="bx bx-check d-block d-sm-none"></i>
                      <span class="d-none d-sm-block">Update</span>
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/app.js"></script>




</body>

</html>