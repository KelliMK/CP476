<?php
// Process delete operation 
if(isset($_POST["ProdID"]) && !empty($_POST["ProdID"])){
    // Include config file
    require_once("config.php");
    // Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect them to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
    
    // Prepare a delete statement
    $sql = "DELETE FROM product_table WHERE ProdID = :ProdID";
    
    if($statement = $conn->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $statement->bindParam(":ProdID", $paramProdID);
        
        // Set parameters
        $paramProdID = trim($_POST["ProdID"]);
        
        // Attempt to execute the prepared statement
        if($statement->execute()){
            // Records deleted successfully. Redirect to landing page
            header("location: indexCRUD.php");
            exit();
        } else{
            echo "Lol something went wrong.";
        }
    }

    // Close statement
    unset($statement);
    
    // Close connection
    unset($conn);
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["ProdID"]))){
        // URL doesn't contain ProdID parameter
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<head>
    <title>Delete Product Entry</title>
    <style>
        a.button {
            padding: 2px 6px;
            border: 1px outset buttonborder;
            border-radius: 3px;
            color: buttontext;
            background-color: buttonface;
            text-decoration: none;
        }
        input.warning {
            background-color: #FF4135;
        }
    </style>
</head>
<body>
    <h2>Delete Product Entry</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="ProdID" value="<?php echo trim($_GET["ProdID"]); ?>"/>
        <p>Are you sure you want to delete this product entry?</p>
        <p>
            <input type="submit" value="Yes" class="warning">
            <a href="indexCRUD.php" class="button">No</a>
        </p>
    </form>
</body>
</html>