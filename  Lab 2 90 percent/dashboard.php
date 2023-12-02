<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashbstyles.css">
    <!-- Add any additional stylesheets if needed -->
    <title>Dashboard</title>
</head>

<body>
    <?php
        include("headerfooter/header.php");
        // Additional page-specific content goes here
    ?>
    <!-- <div class="welcome-card">
            <h1 class="welcome-message">Welcome, <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : 'User'; ?></h1>
        </div> -->

    <div class="content">
                <main>
                <div class="intro">
                    <h1>PETROL FINDER </h1>
                    <p>Out of petrol? Find the nearest petrol station now!</p>
                    <a href="lol.php">
                        <button>Find a station</button>
                    </a>
                </div>
            <!-- <div class="achievements">
                <div class="work">
                    <i class="fas fa-atom"></i>
                    <p class="work-heading">Projects</p>
                    <p class="work-text">I have worked on many projects and I am very proud of them. I am a very good developer and I am always looking for new projects.</p>
                </div>
                <div class="work">
                    <i class="fas fa-skiing"></i>
                    <p class="work-heading">Skills</p>
                    <p class="work-text">I have a lot of skills and I am very good at them. I am very good at programming and I am always looking for new skills.</p>
                </div>
                <div class="work">
                    <i class="fas fa-ethernet"></i>
                    <p class="work-heading">Network</p>
                    <p class="work-text">I have a lot of network skills and I am very good at them. I am very good at networking and I am always looking for new network skills.</p>
                </div>
            </div> -->
        </main>
    </div>
    <?php
    include("headerfooter/footer.php");
    ?>
    
</body>
</html>
