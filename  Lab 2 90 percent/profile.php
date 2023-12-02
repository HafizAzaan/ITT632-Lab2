<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profilestyles.css"> <!-- You can create a separate stylesheet for the profile page if needed -->
    <title>User Profile</title>
</head>
<body>
    <header class="header">
        <a href="#" class="logo">Developer</a>
        <nav class="nav-items">
            <a href="dashboard.php">Dashboard</a> <!-- Link back to the dashboard page -->
            <div class="logout">
                <a href="logout.php">Logout</a>
            </div>
        </nav>
    </header>

    <div class="content">
        <h1>User Profile</h1>

        <!-- Display user information fetched from the database -->
        <p>Username: <?php echo $userData['name']; ?></p>
        <p>Email: <?php echo $userData['email']; ?></p>
        <!-- Add more fields as needed -->
    </div>
</body>
</html>