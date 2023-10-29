<?php

session_start();

$conn = mysqli_connect('localhost', 'root', '', 'laundry_lava');

if (!$conn) {
    echo mysqli_connect_error();
    exit;
}
