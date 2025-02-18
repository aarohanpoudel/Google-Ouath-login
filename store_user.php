<?php
// Your Database connection info
$servername = "localhost";
$username = "your db suername";
$password = "your db password";
$dbname = "your db name";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection db
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the posted username and Email
if (isset($_POST['username']) && isset($_POST['email'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $email);

    // Execute the statement
    if ($stmt->execute()) {
        echo "User data stored successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
