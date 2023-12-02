<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>See Feedbacks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #66CDAA;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        #lol-button {
            margin-top: 20px;
            padding: 10px;
            background-color: #fff8dc;
            color: black;
            border: none;
            cursor: pointer;
        }

        .lol-button:hover {
            background-color: #e6be00;
            /* Darker Gold on Hover */
        }
    </style>
</head>

<body>
    <h1>Feedbacks</h1>

    <?php
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

    // Assuming you have a 'feedbacks' table with columns 'id', 'gasStationName', 'gasStationAddress', 'feedback', 'experience'
    $sql = "SELECT id, gasStationName, gasStationAddress, feedback, experience FROM feedback";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data in a table
        echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Gas Station Name</th>
                        <th>Gas Station Address</th>
                        <th>Feedback</th>
                        <th>Experience</th>
                    </tr>";

        // Output data of each row
// Output data of each row
while ($row = $result->fetch_assoc()) {
    // Construct the URL for lol.php with gas station details
    $lolUrl = "lol.php?name=" . urlencode($row["gasStationName"]) . "&address=" . urlencode($row["gasStationAddress"]);

    echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["gasStationName"] . "</td>
                <td><a href='$lolUrl'>" . $row["gasStationAddress"] . "</a></td>
                <td>" . $row["feedback"] . "</td>
                <td>" . $row["experience"] . "</td>
            </tr>";
}


        echo "</table>";
    } else {
        echo "No feedbacks found.";
    }

    $conn->close();
    ?>

    <!-- Logout button -->
    <button id="lol-button" onclick="location.href='lol.php';">Go Back</button>
</body>

</html>
