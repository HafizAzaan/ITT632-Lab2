<?php
    require('dbConnection.php');

    if(isset($_POST['submit'])) 
    {
        session_start(); // Start the session
        $email = isset($_POST['email']) ? mysqli_real_escape_string($con, $_POST['email']) : '';
        $password = isset($_POST['password']) ? mysqli_real_escape_string($con, $_POST['password']) : '';

        // Use prepared statements and password hashing
        $sql = "SELECT * FROM `login` WHERE email = ? AND password = ?";
        $stmt = mysqli_prepare($con, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $email, $password);
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            $rows = mysqli_num_rows($result);

            if($rows == 1) {
                $_SESSION['username'] = $email;  
                header("Location: dashboard.php");
                exit();
            } else {
                echo "<div class='container'><div class='form_container'>
                <h3>Incorrect email or password</h3><br/>
                <p class='link'>Click here to <a href='signup.php'>Try Again</a></p></div></div>";
            }

            mysqli_stmt_close($stmt);
        } else {
            die("Error in prepared statement: " . mysqli_error($con));
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="loginstyles.css">
</head>
<body>
        <div class="container">
                <div class="form_container">
                <h2>LOGIN</h2>
                    <form method="POST">
                        <div class="inputDiv">
                            <label for="email">Email</label>
                            <input type="email" name="email"  placeholder="Enter your email">
                        </div>
                        <div class="inputDiv">
                            <label for="password">Password</label>
                            <div class="password-container">
                                <input type="password" name="password" id="password" placeholder="Enter your password">
                            </div>
                        </div>                
                        <button class="sbtn" name="submit">Login</button>
                        <p>Don't have an account?<a href="signup.php">Sign Up Now</a></p>
                    </form> 
                </div>        
        </div>

    </body>

</html>