<?php
    // dbConnection.php - Include your database connection code here

    $con = mysqli_connect("localhost", "root", "Pokemon123!@", "signup");

    if(mysqli_connect_errno()) {
        echo "Failed to connect to database: " . mysqli_connect_error();
    }

    // sessionCheck.php - Include your session check code here

    session_start();

    if(!$_SESSION['username']){
        header("Location: login.php");
        exit();
    }

    // getUserData function - Include your getUserData function here

    function getUserData($username) {
        global $con; // Access the global connection variable

        // Prepare and execute the query
        $query = "SELECT * FROM 'login' WHERE username = ?";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);

            // Get the result
            $result = mysqli_stmt_get_result($stmt);

            // Fetch the user data
            $userData = mysqli_fetch_assoc($result);

            // Close the statement
            mysqli_stmt_close($stmt);

            return $userData;
        } else {
            die("Error in prepared statement: " . mysqli_error($con));
        }
    }
?>