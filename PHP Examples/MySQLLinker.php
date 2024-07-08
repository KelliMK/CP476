<?php
  $servername = "localhost";
  $username = "thephp";
  $password = "password";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=CP476", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
  // Close connection with below line
  // $conn = null;
?> 