<?php
include "config/db_conn.php";

// function to fetch data
if ($_GET["action"] === "fetchData") {
  $sql = "SELECT * FROM user";
  $result = mysqli_query($conn, $sql);
  $data = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
  }
  mysqli_close($conn);
  header('Content-Type: application/json');
  echo json_encode([
    "data" => $data
  ]);
}
