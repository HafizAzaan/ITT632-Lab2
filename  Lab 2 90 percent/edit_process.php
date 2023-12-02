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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $editFeedback = mysqli_real_escape_string($conn, $_POST['editFeedback']);
    $editExperience = mysqli_real_escape_string($conn, $_POST['editExperience']);

    // Check if the "Delete Existing Image" checkbox is selected
    $deleteImage = isset($_POST['deleteImage']);

    // If deleteImage is true, remove the existing image from the database
    if ($deleteImage) {
        $deleteImageQuery = "UPDATE feedback SET image = NULL WHERE id = $id";
        $conn->query($deleteImageQuery);
    }

    // Process the uploaded image if a new image is provided
    if (isset($_FILES['editImageUpload']) && $_FILES['editImageUpload']['error'] == 0) {
        $targetDir = __DIR__ . "/uploads/"; // Change this to your desired upload directory
        $targetFile = $targetDir . basename($_FILES['editImageUpload']['name']);
        move_uploaded_file($_FILES['editImageUpload']['tmp_name'], $targetFile);

        // Update the image path in the database
        $updateImageQuery = "UPDATE feedback SET image = '$targetFile' WHERE id = $id";
        $conn->query($updateImageQuery);
    }

    // Update feedback and experience in the database
    $updateQuery = "UPDATE feedback SET feedback = '$editFeedback', experience = '$editExperience' WHERE id = $id";

    if ($conn->query($updateQuery) === TRUE) {
        // Delay for 2 seconds
        sleep(2);

        // Redirect to lol.html
        header("Location: lol.php");
        exit();
    } else {
        echo "Error updating feedback: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
