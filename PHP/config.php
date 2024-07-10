<?php
// Database info, pretty self-explanatory
$servname = "localhost";
$username = "root";
$password = "355D1ck!!!!0";
$dbname = "CP476";

// Attempt to connect to Database with above info. 
// If you're using this on your own computer you'll likely need to change some credentials
try {
	$conn = new PDO("mysql:host=$servname;dbname=$dbname", $username, $password);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	echo "Error: " . $e->getMessage();
}
?>