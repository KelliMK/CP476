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
		// Include config
	require_once("config.php");

	// Initialize the session
	session_start();

	// Check if the user is logged in, if not then redirect them to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: login.php");
		exit;
	}

		// Variables
	$ProdName = $Description = $Price = $Quantity = $Status = $SupplierID = "";
	$name_err = $desc_err = $price_err = $quantity_err = $status_err = $supp_err = "";

	$fck = $conn->prepare("SELECT SuppID FROM supplier_table");
	$fck->execute();
	$suppliers = $fck->fetchAll(PDO::FETCH_COLUMN);


	if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// Validate name
		$nameIn = trim($_POST["ProdName"]);
		if ($nameIn == ""){
			$name_err = "Enter the product's name.";
		} elseif (!filter_var($nameIn, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))) {
			$name_err = "Product names can only have alphanumeric characters.";
		} elseif (strlen($nameIn) > 25){
			$name_err = "Product names cannot be longer than 25 characters.";
		} else {
			$ProdName = $nameIn;
		}

			// Validate Description
		$descIn = trim($_POST["Description"]);
		if (!filter_var($descIn, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z \s]+$/")))) {
			$desc_err = "Descriptions can only have alphanumeric characters and spaces.";
		} elseif (strlen($descIn) > 64){
			$desc_err = "Descriptions cannot be longer than 64 characters.";
		} else {
			$Description = $descIn;
		}

			// Validate Price
		$priceIn = trim($_POST["Price"]);
		if ($priceIn == ""){
			$price_err = "Enter a price. 0 is accepted.";
		} elseif (!filter_var($priceIn, FILTER_VALIDATE_FLOAT)) {
			$price_err = "Price must be a float value.";
		} elseif ($priceIn < 0) {
			$price_err = "Price cannot be negative.";
		} else {
			$Price = $priceIn;
		}

			// Validate Quantity
		$quantityIn = trim($_POST["Quantity"]);
		if ($quantityIn == "") {
			$quantity_err = "Enter a quantity. Integers only.";
		} elseif (!filter_var($quantityIn, FILTER_VALIDATE_INT)) {
			$quantity_err = "Quantity must be an integer.";
		} elseif ($quantityIn < 1) {
			$quantity_err = "Quantity must be greater than 0.";
		} else {
			$Quantity = $quantityIn;
		}

			// validate radio buttons for Status
		$StatusIn = trim($_POST["Status"]);
		if ($StatusIn == "A" || $StatusIn == "B" || $StatusIn == "C") {
			$Status = $StatusIn;
		} else {
			$status_err = "You must give the product a status.";
		}

			// Validate SupplierID
		$supplierIn = trim($_POST["SupplierID"]);

		if ($supplierIn == "") {
			$supp_err = "Enter valid Supplier ID.";
		} elseif (!filter_var($supplierIn, FILTER_VALIDATE_INT)) {
			$supp_err = "Supplier ID must be an integer.";
		} elseif ($supplierIn < 0) {
			$supp_err = "Supplier IDs cannot be negative.";
		} elseif (!array_search($supplierIn, $suppliers)) {
			$supp_err = "Supplier ID not found.";
		} else {
			$SupplierID = $supplierIn;
		}

			// Check input errors
		if(empty($name_err) && empty($desc_err) && empty($price_err) && empty($quantity_err) && empty($supp_err)) {
				// prepare insert statement
			$insert = "INSERT INTO product_table (ProdName, Description, Price, Quantity, Status, SupplierID) VALUES (:ProdName, :Description, :Price, :Quantity, :Status, :SupplierID)";
			if ($statement = $conn->prepare($insert)) {
					// set params
				$paramProdName = $ProdName;
				$paramDescription = $Description;
				$paramPrice = $Price;
				$paramQuantity = $Quantity;
				$paramStatus = $Status;
				$paramSupplierID = (int) $SupplierID;

					// bind params
				$statement->bindParam(":ProdName", $paramProdName);
				$statement->bindParam(":Description", $paramDescription);
				$statement->bindParam(":Price", $paramPrice);
				$statement->bindParam(":Quantity", $paramQuantity);
				$statement->bindParam(":Status", $paramStatus);
				$statement->bindParam(":SupplierID", $paramSupplierID);

					// execute
				if($statement->execute()){
                // Records created successfully. Redirect to landing page
					header("location: indexCRUD.php");
					exit();
				} else {
					echo "Form Error. Ouch dawg.";
				}
			}
		} else {
			echo $name_err . "\r\n";
			echo $desc_err . "\r\n";
			echo $price_err . "\r\n";
			echo $quantity_err . "\r\n";
			echo $status_err . "\r\n";
			echo $supp_err . "\r\n";
		}
		unset($statement);
	}
	$conn = null;
	?>
	<h2>Create Product Entry</h2>
	<p>Please fill this form out and submit it to add a product entry to the database.</p>
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
		<label>Product Name:</label>
		<input type="text" name="ProdName" value="<?php echo $ProdName; ?>"><br>
		<label>Description:</label>
		<input type="text" name="Description" value="<?php echo $Description; ?>"><br>
		<label>Price:</label>
		<input type="text" name="Price" value="<?php echo $Price; ?>"><br>
		<label>Quantity:</label>
		<input type="text" name="Quantity" value="<?php echo $Quantity; ?>"><br>
		Status:
		<input type="radio" name="Status" 
		<?php if (isset($Status) && $Status=="A") echo "checked";?> value='A'>A
		<input type="radio" name="Status" 
		<?php if (isset($Status) && $Status=="B") echo "checked";?> value='B'>B
		<input type="radio" name="Status" 
		<?php if (isset($Status) && $Status=="C") echo "checked";?> value='C'>C<br>
		<label>Supplier ID:</label>
		<input type="text" name="SupplierID" value="<?php echo $SupplierID; ?>"><br>
		<input type="submit" name="submit" value="Submit">
		<a href="indexCRUD.php" class="button">Cancel</a>
	</form>
</body>
</html>