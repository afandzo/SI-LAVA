<?php
include "db.php";
include "filelog.php";

$log = $_SESSION['nama'] . "  " . "(" . $_SESSION['role'] . ")" . "  " . "Telah Melakukan Logout.";
logger($log, "../../../../");

session_destroy();

header('location: index.php');
