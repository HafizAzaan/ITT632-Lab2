<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #8FCC7D; /* Pastel Green */
            padding: 12px;
            text-align: left;
            font-weight: normal;
        }

        th {
            background-color: #B5EAD7; /* Light Pastel Green */
        }

        .goback-container {
            margin-top: 20px;
        }

        .goback-link {
            display: inline-block;
            padding: 10px 16px;
            background-color: #4CAF50; /* Green */
            color: white;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .goback-link:hover {
            background-color: #45a049;
        }

        input[type="radio"] {
            margin-right: 2px;
        }

        /* Style for green text */
        .good-text {
            color: green;
        }

        /* Style for red text */
        .bad-text {
            color: red;
        }

        /* Hide the ID column */
        .hidden-id {
            display: none;
        }

        /* Style for the edit and delete buttons */
        .edit-btn,
        .delete-btn {
            display: inline-block;
            padding: 8px 12px;
            margin-right: 8px;
            background-color: #3498db; /* Blue */
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .edit-btn:hover,
        .delete-btn:hover {
            background-color: #2980b9; /* Darker Blue on Hover */
        }

        h1 {text-align: center;}

        #imageUpload {
            margin-top: 16px;
        }
    </style>
</head>
<body>
    
    <div class="goback-container">
        <a class="goback-link" href="lol.php">Go Back</a>
    </div>
    <h1> Feedback Form </h1>
    <form id="feedbackForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <?php if (isset($feedbackMessage)) : ?>
            <p><?php echo $feedbackMessage; ?></p>
        <?php endif; ?>

        <label for="gasStationName">Gas Station Name:</label>
        <input type="text" id="gasStationName" name="gasStationName" readonly>

        <label for="gasStationAddress">Gas Station Address:</label>
        <input type="text" id="gasStationAddress" name="gasStationAddress" readonly>

        <label for="feedback">Feedback:</label>
        <textarea id="feedback" name="feedback" rows="4" required></textarea>

        <!-- Add radio buttons for indicating good or bad experience -->
        <label>How was your experience?</label> 
        <label for="goodExperience"><input type="radio" id="goodExperience" name="experience" value="good"> Good</label>
        <label for="badExperience"><input type="radio" id="badExperience" name="experience" value="bad"> Bad</label>

        <!-- Image upload button -->
        <label for="imageUpload">Upload Image (Optional):</label>
        <input type="file" id="imageUpload" name="imageUpload">

        <input type="submit" value="Submit Feedback">
    </form>

    <script>
        // Get gas station details from the URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const gasStationName = urlParams.get('name');
        const gasStationAddress = urlParams.get('address');

        // Set gas station details in the form
        document.getElementById('gasStationName').value = gasStationName || '';
        document.getElementById('gasStationAddress').value = gasStationAddress || '';
    </script>

    <?php
    // Your PHP code for handling form submission and displaying feedback in a table
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

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate and sanitize input
        $gasStationName = mysqli_real_escape_string($conn, $_POST['gasStationName']);
        $gasStationAddress = mysqli_real_escape_string($conn, $_POST['gasStationAddress']);
        $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
        $experience = mysqli_real_escape_string($conn, $_POST['experience']);

        // Check if an image is uploaded
        $imageUploaded = isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] == 0;
        
        // Process the uploaded image
        $imagePath = '';
        if ($imageUploaded) {
            $targetDir = __DIR__ . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR;
            $targetFile = $targetDir . basename($_FILES['imageUpload']['name']);
            move_uploaded_file($_FILES['imageUpload']['tmp_name'], $targetFile);
            $imagePath = $targetFile;
        }

        // SQL query to insert feedback into the database
        $sql = "INSERT INTO feedback (id, gasStationName, gasStationAddress, feedback, experience, image) VALUES (NULL, '$gasStationName', '$gasStationAddress', '$feedback', '$experience', '$imagePath')";

        if ($conn->query($sql) === TRUE) {
            $feedbackMessage = "Feedback submitted successfully";
        } else {
            $feedbackMessage = "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Check if delete button is clicked
    if (isset($_GET['delete'])) {
        $deleteId = $_GET['delete'];

        // SQL query to delete feedback from the database
        $deleteQuery = "DELETE FROM feedback WHERE id = $deleteId";

        if ($conn->query($deleteQuery) === TRUE) {
            $feedbackMessage = "Feedback deleted successfully";
        } else {
            $feedbackMessage = "Error deleting feedback: " . $conn->error;
        }
    }

     // SQL query to select all data from the feedback table
     $selectQuery = "SELECT id, gasStationName, gasStationAddress, feedback, experience, image FROM feedback";
     $result = $conn->query($selectQuery);
 
     if ($result === FALSE) {
         echo "Error selecting data: " . $conn->error;
     }
 
     // Check if there are rows in the result
     if ($result->num_rows > 0) {
         echo "<table>";
         echo "<tr><th>Gas Station Name</th><th>Gas Station Address</th><th>Feedback</th><th>Experience</th><th>Image</th><th>Action</th></tr>";
// Output data of each row
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td class='hidden-id'>" . $row["id"] . "</td>";
    echo "<td>" . $row["gasStationName"] . "</td>";
    echo "<td>" . $row["gasStationAddress"] . "</td>";
    echo "<td>" . $row["feedback"] . "</td>";
    echo "<td class='" . ($row["experience"] == 'good' ? 'good-text' : 'bad-text') . "'>" . $row["experience"] . "</td>";
    echo "<td>";

    // Check if there is an image path
    if (!empty($row["image"])) {
        $imagePath = str_replace('\\', '/', $row["image"]);
        $imagePath = str_replace(' ', '%20', $imagePath); // Handle spaces in the file path

        if (file_exists($imagePath)) {
            echo "<img src='/" . urlencode($imagePath) . "' alt='Feedback Image' style='max-width: 100px; max-height: 100px;'>";

        } else {
            echo "Image not found: " . $imagePath;
        }
    } else {
        echo "No image";
    }

    echo "</td>";
    echo "<td>
        <a class='edit-btn' href='edit_feedback.php?id=" . $row["id"] . "'>Edit</a>
        <a class='delete-btn' href='?delete=" . $row["id"] . "'>Delete</a>
    </td>";

    echo "</tr>";

}
         echo "</table>";
     } else {
         echo "0 results";
     }
 
     $conn->close();
     ?>
</body>
</html>