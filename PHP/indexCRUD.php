<!DOCTYPE html>
<html>
<style>
	table {
		width: 100%;
		border-collapse: collapse;
	}
	table, th, td {
		border: 2px solid black;
	}
	th, td {
		padding: 10px;
		text-align: left;
	}
	a.button {
		padding: 2px 6px;
		border: 1px outset buttonborder;
		border-radius: 3px;
		color: buttontext;
		background-color: buttonface;
		text-decoration: none;
	}
	.header {
		overflow: hidden;
		background-color: #f1f1f1;
		padding: 20px 10px;
	}

	.header a {
		float: left;
		color: black;
		text-align: center;
		padding: 12px;
		text-decoration: none;
		font-size: 18px;
		line-height: 25px;
		border-radius: 4px;
	}

	.header a.logo {
		font-size: 25px;
		font-weight: bold;
	}

	.header a:hover {
		background-color: #ddd;
		color: black;
	}

	.header-right {
		float: right;
	}
}
</style>
<head>
	<title>CompanyName Inventory</title>
	<div class="header">
		<a href="#default" class="logo">CompanyName</a>
		<div class="header-right">
			<a href="createScript.php">+ Create New Entry</a>
			<a href="logout.php">Logout</a>
		</div>
	</div> 
</head>
<body>
	<?php
	require_once("config.php");	// Config file for easy host access
	// Initialize the session
	session_start();

	// Check if the user is logged in, if not then redirect them to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: login.php");
		exit;
	}
	$inisql = "SELECT product_table.ProdID, product_table.ProdName, product_table.Quantity, product_table.Price, product_table.Status, supplier_table.SuppName
	FROM product_table
	INNER JOIN supplier_table ON product_table.SupplierID = supplier_table.SuppID";
	if ($output = $conn->query($inisql)) {
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
			<th>Actions</th>
			</tr>
			</thead>
			<tbody>";
			foreach ($output as $row) {
				echo "<tr>";
				echo "<td>" . $row['ProdID'] . "</td>";
				echo "<td>" . $row['ProdName'] . "</td>";
				echo "<td>" . $row['Quantity'] . "</td>";
				echo "<td>" . $row['Price'] . "</td>";
				echo "<td>" . $row['Status'] . "</td>";
				echo "<td>" . $row['SuppName'] . "</td>";
				echo "<td>";
				echo '<a href="readScript.php?ProdID=' . $row['ProdID'] . '&SuppName=' . $row['SuppName'] . '" class="button">Query</a> ';
				echo '<a href="updateScript.php?ProdID=' . $row['ProdID'] . '" class="button">Update</a> ';
				echo '<a href="deleteScript.php?ProdID=' . $row['ProdID'] . '" class="button">Delete</a>';
				echo "</td>";
				echo "</tr>";
			}
			echo "</tbody>";
			echo "</table>";
			unset($output); // Free Output if needed
		} else {
			echo "No Records Found.";
		}
	} else {
		echo "Error in SQL query. You are likely missing a table, or have an empty table";
	}

	// Close Connection
	$conn = null;
	?>
</body>
</html>