<?php
// Include config file
require_once("config.php");

// Define variables 
$Username = $UserPass = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if (empty(trim($_POST["Username"]))){
        $username_err = "Please enter a username.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["Username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        // Prepare a select statement
        $sql = "SELECT UserID FROM users WHERE Username = :Username";
        
        if ($statement = $conn->prepare($sql)){
            // Set parameters
            $paramUsername = trim($_POST["Username"]);

            // Bind variables to the prepared statement as parameters
            $statement->bindParam(":Username", $paramUsername, PDO::PARAM_STR);
            
            // Attempt to execute the prepared statement
            if ($statement->execute()){
                if ($statement->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["Username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($statement);
        }
    }
    
    // Validate password
    if (empty(trim($_POST["UserPass"]))){
        $password_err = "Please enter a password.";     
    } elseif (strlen(trim($_POST["UserPass"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $UserPass = trim($_POST["UserPass"]);
    }
    
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($UserPass != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (Username, UserPass) VALUES (:Username, :UserPass)";

        if ($statement = $conn->prepare($sql)){
            // Set parameters
            $paramUsername = $Username;
            $paramUserPass = password_hash($UserPass, PASSWORD_DEFAULT); // Creates a password hash

            // Bind variables to the prepared statement as parameters
            $statement->bindParam(":Username", $paramUsername, PDO::PARAM_STR);
            $statement->bindParam(":UserPass", $paramUserPass, PDO::PARAM_STR);
            
            // Attempt to execute the prepared statement
            if ($statement->execute()){
                // Redirect to login page
                header("location: login.php");
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
    <title>Sign Up</title>
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
    <h2>Sign Up</h2>
    <p>Please fill this form to create an account.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label>Username</label>
        <input type="text" name="Username" value="<?php echo $Username; ?>">
        <span class="invalid-feedback"><?php echo $username_err; ?></span><br>
        <label>Password</label>
        <input type="password" name="UserPass" value="<?php echo $UserPass; ?>">
        <span class="invalid-feedback"><?php echo $password_err; ?></span><br>
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span><br>
        <input type="submit" name="submit" value="Submit">
        <input type="reset" value="Reset">
        <p>Already have an account? <a href="login.php" class="button">Login here</a></p>
    </form>   
</body>
</html>