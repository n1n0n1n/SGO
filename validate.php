<?php
// Get form data
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Create connection
    $conn = mysqli_connect('localhost', 'root', '', 'demo');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare SQL statements
    $SELECT = "SELECT email, password FROM users WHERE email = ? LIMIT 1";

    // Check if email exists and verify password
    $stmt = $conn->prepare($SELECT);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($stored_email, $stored_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        // Verify password
        if ($password === $stored_password) { // Directly compare plain text passwords
            // Redirect to main page
            header("Location: main page.html");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Invalid email or password.";
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
