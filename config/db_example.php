<?php

  $db_name = "moviecircle";
  $db_host = "localhost";
  $db_user = "root";
  $db_pass = "";

  // connection
  $conn = new PDO(
    "mysql:dbname=$db_name;host=$db_host;charset=utf8mb4",
    $db_user,
    $db_pass);

  // enable PDO errors
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
