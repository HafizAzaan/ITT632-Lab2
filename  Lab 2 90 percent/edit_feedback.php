<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff8dc; /* Pastel Yellow */
            margin: 0;
            padding: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #e6be00; /* Darker Gold */
            color: #333;
            border: none;
            padding: 12px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #ffd700; /* Lighter Gold on Hover */
        }

        a {
            display: inline-block;
            padding: 10px 16px;
            background-color: #4CAF50; /* Green */
            color: white;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px; /* Add some margin for spacing */
        }

        a:hover {
            background-color: #45a049;
        }

        h1 {text-align: center;}
    </style>
</head>
<body>
    <a href="javascript:history.back()">Go Back</a>
    <h1>Edit Feedback</h1>

    <?php
    // Include your database connection code here or include the file if it's in a separate file
    $servername = "localhost";
    $username = "root";
    $password = "Pokemon123!@";
    $dbname = "feedbackdb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch previous feedback, experience, and image
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $selectQuery = "SELECT feedback, experience, image FROM feedback WHERE id=$id";
    $result = $conn->query($selectQuery);

    if ($result === FALSE) {
        echo "Error selecting data: " . $conn->error;
    }

    // Check if there are rows in the result
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $previousFeedback = $row["feedback"];
        $previousExperience = $row["experience"];
        $previousImage = $row["image"];
    }

    // Close the database connection
    $conn->close();
    ?>

    <form id="editFeedbackForm" method="post" action="edit_process.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>">

        <label for="editFeedback">Feedback:</label>
        <textarea id="editFeedback" name="editFeedback" rows="4" required><?php echo htmlspecialchars($previousFeedback); ?></textarea>

        <!-- Add radio buttons for indicating good or bad experience -->
        <label>How was your experience?</label>
        <label for="editGoodExperience">
            <input type="radio" id="editGoodExperience" name="editExperience" value="good" <?php echo ($previousExperience == 'good') ? 'checked' : ''; ?>> Good
        </label>
        <label for="editBadExperience">
            <input type="radio" id="editBadExperience" name="editExperience" value="bad" <?php echo ($previousExperience == 'bad') ? 'checked' : ''; ?>> Bad
        </label>

        <!-- Checkbox to delete existing image -->
        <label for="deleteImage">
            <input type="checkbox" id="deleteImage" name="deleteImage"> Delete Existing Image
        </label>

        <!-- Input for uploading a new image -->
        <label for="editImageUpload">Upload New Image (Optional):</label>
        <input type="file" id="editImageUpload" name="editImageUpload">

        <input type="submit" value="Update Feedback">
    </form>

    <?php
    // Display the existing image
    if (!empty($previousImage)) {
        echo "<p>Existing Image:</p>";
        echo "<img src='" . $previousImage . "' alt='Existing Feedback Image' style='max-width: 100px; max-height: 100px;'>";
    }
    ?>
</body>
</html>
