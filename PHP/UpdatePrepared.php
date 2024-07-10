<?php
$servname = "localhost";
$username = "root";
$password = "355D1ck!!!!0";
$dbname = "CP476";

try {
  $conn = new PDO("mysql:host=$servname;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $FUpdate = $conn->prepare("UPDATE product_table
    SET ProdName = :prodName, Quantity = :quantity, Price = :newPrice, Status = :newStatus
    WHERE ProdID = :prodID;");
  $FUpdate->bindParam(':prodID', $prodID);
  $FUpdate->bindParam(':prodName', $prodName);
  $FUpdate->bindParam(':quantity', $quantity);
  $FUpdate->bindParam(':newPrice', $newPrice);
  $FUpdate->bindParam(':newStatus', $newStatus);
  
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;
?>