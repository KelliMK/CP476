<?php
$servname = "localhost";
$username = "root";
$password = "355D1ck!!!!0";
$dbname = "CP476";

try {
  $conn = new PDO("mysql:host=$servname;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $FDelete = $conn->prepare("DELETE FROM product_table WHERE ProdID=:prodID;");
  $FDelete->bindParam(':prodID', $prodID);

} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}
$conn = null;
?>