<?php
if ($_SERVER['HTTP_HOST'] == "localhost") {
  $host = "localhost";
  $user = "root";
  $password = "";
  $db = "info_cast";
} else {
  $host = "localhost";
  $user = "id21207474_infocast";
  $password = "Dummy44what!";
  $db = "id21207474_infocast";
}

$response = array(
  "success" => false,
  "message" => ""
);

try {
  $conn = mysqli_connect($host, $user, $password, $db);
} catch (Exception $e) {
  echo "<script>alert('" . ($e->getMessage()) . "')</script>";
}
