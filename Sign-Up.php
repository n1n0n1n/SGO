<?php
// sign up php
// Get form data
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Create connection
    $conn = mysqli_connect('sql306.infinityfree.com', 'if0_38391513', 'tpftcimd', 'if0_38391513_demo');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statements
    $SELECT = "SELECT email FROM users WHERE email = ? LIMIT 1";
    $INSERT = "INSERT INTO users (email, password) VALUES (?, ?)";

    // Check if email already exists
    $stmt = $conn->prepare($SELECT);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $rnum = $stmt->num_rows;

    if ($rnum == 0) {
        $stmt->close();

        // Insert new record
        $stmt = $conn->prepare($INSERT);
        $stmt->bind_param("ss", $email, $password);
        if ($stmt->execute()) {
            echo "<script>
                    alert('Account created successfully');
                    window.location.href = 'Sign-In-Page.html';
                  </script>";
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Email already exists";
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
