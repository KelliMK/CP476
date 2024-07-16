<?php
// Include config file
require_once("config.php");
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect them to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}

// Define variables and initialize with empty values
$ProdName = $Description = $Price = $Quantity = $Status = "";
$name_err = $desc_err = $price_err = $quantity_err = $status_err = "";

// Processing form data when form is submitted
if (isset($_POST["ProdID"]) && !empty($_POST["ProdID"])) {
    // Get hidden input value
	$ProdID = $_POST["ProdID"];

    // Validate name
	$nameIn = trim($_POST["ProdName"]);
	if (empty($nameIn)){
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
	if (empty($priceIn)){
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
	if (empty($quantityIn)) {
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

    // Check input errors before inserting in database
	if(empty($name_err) && empty($desc_err) && empty($price_err) && empty($quantity_err) && empty($status_err)) {
        // Prepare an update statement
		$sql = "UPDATE product_table SET ProdName=:ProdName, Description=:Description, Price=:Price, Quantity=:Quantity, Status=:Status WHERE ProdID=:ProdID";

		if ($statement = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
			$statement->bindParam(":ProdName", $paramProdName);
			$statement->bindParam(":Description", $paramDescription);
			$statement->bindParam(":Price", $paramPrice);
			$statement->bindParam(":Quantity", $paramQuantity);
			$statement->bindParam(":Status", $paramStatus);
			$statement->bindParam(":ProdID", $paramProdID);

            // Set parameters
			$paramProdName = $ProdName;
			$paramDescription = $Description;
			$paramPrice = $Price;
			$paramQuantity = $Quantity;
			$paramStatus = $Status;
			$paramProdID = $ProdID;
            // Attempt to execute the prepared statement
			if($statement->execute()){
				header("location: indexCRUD.php");
				exit();
			} else {
				echo "Form Error. Ouch dawg.";
			}
		}

        // Close statement
		unset($statement);
	} 
    // Close connection
	unset($conn);
} else {
    // Check existence of ProdID parameter before processing further
	if(isset($_GET["ProdID"]) && !empty(trim($_GET["ProdID"]))){
        // Get URL parameter
		$ProdID =  trim($_GET["ProdID"]);

        // Prepare a select statement
		$sql = "SELECT * FROM product_table WHERE ProdID = :ProdID";
		if($statement = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
			$statement->bindParam(":ProdID", $paramProdID);

            // Set parameters
			$paramProdID = $ProdID;

            // Attempt to execute the prepared statement
			if($statement->execute()){
				if($statement->rowCount() == 1){
                    // Fetch result row as an associative array. No loops since it's a single entry 
					$row = $statement->fetch(PDO::FETCH_ASSOC);

                    // Retrieve individual field values
					$ProdID = $row["ProdID"];
					$ProdName = $row["ProdName"];
					$Description = $row["Description"];
					$Price = $row["Price"];
					$Quantity = $row["Quantity"];
					$Status = $row["Status"];
				} else {
                    // URL doesn't contain valid id. Redirect to error page
					header("location: error.php");
					exit();
				}

			} else {
				echo "Something went wrong! Yay!";
			}
		}

        // Close statement
		unset($statement);

        // Close connection
		unset($conn);
	} else {
        // URL doesn't contain id parameter. Redirect to error page
		header("location: error.php");
		exit();
	}
}
?>
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
	// Browser tab name
	<title>Update Product Entry</title>
</head>
<body>
	// Page headline
	<h1>Update Product Entry</h1><br>
	// sub headline
	<p>Edit below input values and submit to update product entry.</p><br>
	<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
		// Product Name field
		<label>Product Name:</label>
		<input type="text" name="ProdName" value="<?php echo $ProdName; ?>"><br>
		<span class="invalid-feedback"><?php echo $name_err;?></span>
		// Description name field
		<label>Description:</label>
		<input type="text" name="Description" value="<?php echo $Description; ?>"><br>
		<span class="invalid-feedback"><?php echo $desc_err;?></span>
		<label>Price:</label>
		<input type="text" name="Price" value="<?php echo $Price; ?>"><br>
		<span class="invalid-feedback"><?php echo $price_err;?></span>
		<label>Quantity:</label>
		<input type="text" name="Quantity" value="<?php echo $Quantity; ?>"><br>
		<span class="invalid-feedback"><?php echo $quantity_err;?></span>
		<label>Status:</label>
		<input type="radio" name="Status" 
		<?php if (isset($Status) && $Status=="A") echo "checked";?> value='A'>A
		<input type="radio" name="Status" 
		<?php if (isset($Status) && $Status=="B") echo "checked";?> value='B'>B
		<input type="radio" name="Status" 
		<?php if (isset($Status) && $Status=="C") echo "checked";?> value='C'>C<br>
		<span class="invalid-feedback"><?php echo $status_err;?></span>
		<input type="hidden" name="ProdID" value="<?php echo $ProdID; ?>"/>
		<input type="submit" name="submit" value="Submit">
		<a href="indexCRUD.php" class="button">Cancel</a>
	</form>
</body>
</html>