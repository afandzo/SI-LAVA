<?php
function logger($log, $lokasi)
{
  if (!file_exists("$lokasi" . "log.txt")) {
    file_put_contents("$lokasi" . "log.txt", '');
  }
  $ip = $_SERVER['REMOTE_ADDR'];
  date_default_timezone_set('Asia/Jakarta');
  $time = date('m/d/y h:iA', time());

  $contents = file_get_contents("$lokasi/log.txt");
  $contents .= "$time\t$log\r";

  file_put_contents("$lokasi/log.txt", $contents);
}
