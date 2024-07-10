<!DOCTYPE html>
<html>
<head>
	<title>Inventory</title>
	<style>
		table {
			width: 100%;
			border-collapse: collapse;
		}
		table, th, td {
			border: 1px solid black;
		}
		th, td {
			padding: 15px;
			text-align: left;
		}
	</style>
</head>
<body>

	<?php

	// Database connection details
	$servname = "localhost";
	$username = "root";
	$password = "355D1ck!!!!0";
	$dbname = "CP476";
	$prodID = 0000;

	try {
		$conn = new PDO("mysql:host=$servname;dbname=$dbname", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Prepared Statement for Deleting entry
		$FDelete = $conn->prepare("DELETE FROM product_table WHERE ProdID=:prodID;");
		$FDelete->bindParam(':prodID', $prodID);

		// Prepared Statement for updating entry
		$FUpdate = $conn->prepare("UPDATE product_table
			SET ProdName = :prodName, Quantity = :quantity, Price = :newPrice, Status = :newStatus
			WHERE ProdID = :prodID;");
		$FUpdate->bindParam(':prodID', $prodID);
		$FUpdate->bindParam(':prodName', $prodName);
		$FUpdate->bindParam(':quantity', $newQuantity);
		$FUpdate->bindParam(':newPrice', $newPrice);
		$FUpdate->bindParam(':newStatus', $newStatus);

		// Initial Table display
		$inisql = "SELECT product_table.ProdID, product_table.ProdName, product_table.Quantity, product_table.Price, product_table.Status, supplier_table.SuppName
		FROM product_table
		INNER JOIN supplier_table ON product_table.SupplierID = supplier_table.SuppID";
		$result = $conn->query($inisql);
		$numRows = $conn->query("SELECT FOUND_ROWS()")->fetchColumn();
		if ($numRows > 0) {
			echo "<table>";
			echo "<thead>
			<tr>
			<th>Product ID</th>
			<th>Product Name</th>
			<th>Quantity</th>
			<th>Price</th>
			<th>Status</th>
			<th>Supplier Name</th>
			</tr>
			</thead>
			<tbody>";
			foreach ($result as $row) {
				echo "<tr>";
				echo var_dump($row);
				for ($x = 0; $x < (count($row) - 6); $x++) {
					echo "<td>" . $row[$x] . "</td>";
				}
				echo "</tr>";
			}
			echo "</tbody></table>";
		} else {
			echo "No results";
		}
	} catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
	}

	//$conn = null;
	?>
	<script type="text/javascript">
		function updateProdID(data) {
			ProdID = data;
		}

		function updateProduct() {

		}
	</script>

	
</body>
</html>