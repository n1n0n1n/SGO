<?php
// Get form data

if(isset($_POST['email']) && isset($_POST['password'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $data = [];
    
    // Create connection
    $conn = mysqli_connect('localhost', "root", "", "demo");
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }else{
        $stmt = $conn->prepare("insert into users(email,password) values(?,?)");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        echo "Account created successfully";
        $stmt->close();
        $conn->close();
    }

    $data = [
        'email'=> $email,
        'password'=> $password
    ];

    return json_encode($data);
    
}


?>

/*
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

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new record
    $stmt = $conn->prepare($INSERT);
    $stmt->bind_param("ss", $email, $hashed_password);
    if ($stmt->execute()) {
        echo "Account created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Email already exists";
}

// Close connection
$stmt->close();
$conn->close();
?>

*/