<?php
//sign in php
// Start the session
session_start();

// Get form data
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Create connection
    $conn = mysqli_connect('sql306.infinityfree.com', 'if0_38391513', 'tpftcimd', 'if0_38391513_demo');
    
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
        if ($password === $stored_password) { // Direct comparison of plain text passwords
            // Set session variables if needed
            $_SESSION['email'] = $email;
            $_SESSION['logged_in'] = true;
            
            // Redirect to main page (fixed the filename)
            header("Location: Main-Page.html");
            exit();
        } else {
            echo "<script>alert('Invalid email or password.');</script>";
        }
    } else {
        echo "<script>alert('Invalid email or password.');</script>";
    }
    
    // Close connection
    $stmt->close();
    $conn->close();
}
?>
