<?php
// calendar php 0.1
// Get form data
if (isset($_POST['task']) && isset($_POST['date'])) {
    $task = $_POST['task'];
    $date = $_POST['date'];

    // Create connection
    $conn = mysqli_connect('sql306.infinityfree.com', 'if0_38391513', 'tpftcimd', 'if0_38391513_demo');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create table if it does not exist
    $CREATE_TABLE = "CREATE TABLE IF NOT EXISTS tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        task VARCHAR(255) NOT NULL,
        date VARCHAR(10) NOT NULL
    )";
    if ($conn->query($CREATE_TABLE) === FALSE) {
        die("Error creating table: " . $conn->error);
    }

    // Prepare SQL statement
    $INSERT = "INSERT INTO tasks (task, date) VALUES (?, ?)";

    // Insert new record
    $stmt = $conn->prepare($INSERT);
    $stmt->bind_param("ss", $task, $date);
    $stmt->execute();
    echo "New task added successfully";

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
