<?php
    require('dbConnection.php');
    session_start(); // Start the session

    if(isset($_POST['submit'])) {
        $name = isset($_POST['username']) ? mysqli_real_escape_string($con, $_POST['username']) : '';
        $email = isset($_POST['email']) ? mysqli_real_escape_string($con, $_POST['email']) : '';
        $password = isset($_POST['password']) ? mysqli_real_escape_string($con, $_POST['password']) : '';

        $sql = "INSERT INTO `login` (name, email, password) VALUES ('$name', '$email', '$password')";

        $result = mysqli_query($con, $sql);

        if ($result) {
            // Store the name in the session after successful registration
            $_SESSION['username'] = $email;
            $_SESSION['name'] = $name;

            echo "<div class='container'><div class='form_container'>
            <h3>Registration Successful</h3><br/>
            Click here to <a href='login.php'>Login</a>
            </div></div>";
        } else {
            echo "<div class='container'><div class='form_container'>
            <h3>Registration Failed</h3><br/>
            <p class='link'>Click here to <a href='signup.php'>Try Again</a></p></div></div>";
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signupstyles.css">
</head>
<body>
        <div class="container">
                <div class="form_container">
                    <h2>SIGN UP</h2>
                    <form method="POST">
                        <div class="inputDiv">
                            <label for="username">Username</label>
                            <input type="text" name="username"  placeholder="Enter your username">
                        </div>
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
                        <button class="sbtn" name="submit">Sign Up</button>
                        <p>Already have an account?<a href="login.php">Login</a></p>
                    </form> 
                </div>        
        </div>

    </body>

</html>