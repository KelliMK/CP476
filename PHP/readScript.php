<!DOCTYPE html>
<html>
<style>
	a.button {
		padding: 2px 6px;
		border: 1px outset buttonborder;
		border-radius: 3px;
		color: buttontext;
		background-color: buttonface;
		text-decoration: none;
	}
</style>
<head>
	<title>New Inventory Entry</title>
</head>
<body>
	<?php
		// if this check fails then something is seriously wrong
	if(isset($_GET['ProdID']) && !empty(trim($_GET['ProdID'])) && isset($_GET['SuppName']) && !empty(trim($_GET['SuppName']))) {
		// include config
		require_once("config.php");

		// Initialize the session
		session_start();

		// Check if the user is logged in, if not then redirect them to login page
		if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
			header("location: login.php");
			exit;
		}

			// Prepare select statements
		$prodquery = "SELECT * FROM product_table WHERE ProdID = :ProdID";
		$suppquery = "SELECT * FROM supplier_table WHERE Suppname = :SupplierName";

		if ($statement1 = $conn->prepare($prodquery)) {
			$paramPID = trim($_GET["ProdID"]);

			$statement1->bindParam(":ProdID", $paramPID);

			if ($statement1->execute()) {
				if ($statement1->rowCount() == 1) {
					$row1 = $statement1->fetch(PDO::FETCH_ASSOC);
					$ProdID = $row1["ProdID"];
					$ProdName = $row1["ProdName"];
					$Description = $row1["Description"];
					$Price = $row1["Price"];
					$Quantity = $row1["Quantity"];
					$Status = $row1["Status"];
				} else {
					header("location: error.php");
					exit();
				}
			}

			unset($statement1);

		} else {
			echo "Something went wrong! Yay!";
		}

		if ($statement2 = $conn->prepare($suppquery)) {
			$paramSN = trim($_GET["SuppName"]);

			$statement2->bindParam(":SupplierName", $paramSN);

			if ($statement2->execute()) {
				if ($statement2->rowCount() == 1) {
					$row2 = $statement2->fetch(PDO::FETCH_ASSOC);
					$SuppID = $row2["SuppID"];
					$SuppName = $row2["SuppName"];
					$Address = $row2["Address"];
					$Phone = $row2["Phone"];
					$Email = $row2["Email"];
				} else {
					header("location: error.php");
					exit();
				}
			}

                // Close shit
			unset($statement2);

		} else {
			echo "Something went wrong! Yay!";
		}
	} else {
			// URL doesn't contain ProdID and/or SuppName parameter. Error page.
		header("location: error.php");
		exit();
	}
	?>
	<h1>Product Details</h1><br>
	<label>Product name: <?php echo $row1["ProdName"]; ?></label><br>
	<label>Product ID: <?php echo $row1["ProdID"]; ?></label><br>
	<label>Product Description: <?php echo $row1["Description"]; ?></label><br>
	<label>Product Price: <?php echo $row1["Price"]; ?></label><br>
	<label>Product in Inventory: <?php echo $row1["Quantity"]; ?></label><br>
	<label>Product Status: <?php echo $row1["Status"]; ?></label><br>
	<br><br>
	<h1>Supplier/Vendor Details</h1><br>
	<label>Supplier Name: <?php echo $row2["SuppName"]; ?></label><br>
	<label>Supplier ID: <?php echo $row2["SuppID"]; ?></label><br>
	<label>Supplier Address: <?php echo $row2["Address"]; ?></label><br>
	<label>Supplier Phone: <?php echo $row2["Phone"]; ?></label><br>
	<label>Supplier Email: <?php echo $row2["Email"]; ?></label><br><br>

	<a href="indexCRUD.php" class="button">Back</a>
</body>
</html>
