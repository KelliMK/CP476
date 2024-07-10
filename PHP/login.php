<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to the database view
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: indexCRUD.php");
    exit();
}

// Include config file
require_once("config.php");

// Define variables and initialize with empty values
$Username = $UserPass = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST"){
   
    // Check if username is empty
    if (empty(trim($_POST["Username"]))){
        $username_err = "Please enter username.";
    } else {
        $Username = trim($_POST["Username"]);
    }
    
    // Check if password is empty
    if (empty(trim($_POST["UserPass"]))){
        $password_err = "Please enter your password.";
    } else {
        $UserPass = trim($_POST["UserPass"]);
    }
    
    // Validate credentials
    if (empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT UserID, Username, UserPass FROM users WHERE Username = :Username";
        
        if ($statement = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $statement->bindParam(":Username", $param_username, PDO::PARAM_STR);
            
            // Set parameters
            $param_username = trim($_POST["Username"]);
            
            // Attempt to execute the prepared statement
            if ($statement->execute()){
                // Check if username exists, if yes then verify password
                if ($statement->rowCount() == 1){
                    if ($row = $statement->fetch()){
                        $UserID = $row["UserID"];
                        $Username = $row["Username"];
                        $hashed_password = $row["UserPass"];
                        if (UserPass_verify($UserPass, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["UserID"] = $UserID;
                            $_SESSION["Username"] = $Username;                            
                            
                            // Redirect user to welcome page
                            header("location: indexCRUD.php");
                        } else {
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else {
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($statement);
        }
    }
    
    // Close connection
    unset($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body{ font: 14px sans-serif; }
        a.button {
            padding: 2px 6px;
            border: 1px outset buttonborder;
            border-radius: 3px;
            color: buttontext;
            background-color: buttonface;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h2>Login</h2>
    <p>Please fill in your credentials to login.</p>

    <?php 
    if (!empty($login_err)){
        echo $login_err;
    }        
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label>Username</label>
        <input type="text" name="Username" value="<?php echo $Username; ?>">
        <span class="invalid-feedback"><?php echo $username_err; ?></span><br>
        <label>Password</label>
        <input type="password" name="UserPass">
        <span class="invalid-feedback"><?php echo $password_err; ?></span><br>
        <input type="submit" class="btn btn-primary" value="Login"><br>
        <p>Don't have an account? <a href="registration.php" class="button">Sign up</a> now.</p>
    </form>
</body>
</html>