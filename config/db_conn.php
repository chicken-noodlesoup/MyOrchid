<?php

$hostName = "localhost";
$dbUser = "admin";
$dbPassword = "admin";
$dbName = "orchidapp";

$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

