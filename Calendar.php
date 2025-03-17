<?php
// calendar 0.2
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

    // Create tables if they do not exist
    $CREATE_TASKS_TABLE = "CREATE TABLE IF NOT EXISTS tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        task VARCHAR(255) NOT NULL,
        date VARCHAR(10) NOT NULL
    )";
    if ($conn->query($CREATE_TASKS_TABLE) === FALSE) {
        die("Error creating tasks table: " . $conn->error);
    }

    $CREATE_ARCHIVED_TASKS_TABLE = "CREATE TABLE IF NOT EXISTS archived_tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        task VARCHAR(255) NOT NULL,
        date VARCHAR(10) NOT NULL
    )";
    if ($conn->query($CREATE_ARCHIVED_TASKS_TABLE) === FALSE) {
        die("Error creating archived tasks table: " . $conn->error);
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

// Function to toggle task completion
if (isset($_POST['toggle_task_id'])) {
    $task_id = $_POST['toggle_task_id'];
    $is_archived = $_POST['is_archived'];

    // Create connection
    $conn = mysqli_connect('sql306.infinityfree.com', 'if0_38391513', 'tpftcimd', 'if0_38391513_demo');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($is_archived == 'false') {
        // Move task from tasks to archived_tasks
        $MOVE_TASK = "INSERT INTO archived_tasks (task, date) SELECT task, date FROM tasks WHERE id = ?";
        $DELETE_TASK = "DELETE FROM tasks WHERE id = ?";
    } else {
        // Move task from archived_tasks to tasks
        $MOVE_TASK = "INSERT INTO tasks (task, date) SELECT task, date FROM archived_tasks WHERE id = ?";
        $DELETE_TASK = "DELETE FROM archived_tasks WHERE id = ?";
    }

    // Move task
    $stmt = $conn->prepare($MOVE_TASK);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();

    // Delete task from original table
    $stmt = $conn->prepare($DELETE_TASK);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();

    echo "Task toggled successfully";

    // Close connection
    $stmt->close();
    $conn->close();
}

// Function to delete task
if (isset($_POST['delete_task_id'])) {
    $task_id = $_POST['delete_task_id'];
    $is_archived = $_POST['is_archived'];

    // Create connection
    $conn = mysqli_connect('sql306.infinityfree.com', 'if0_38391513', 'tpftcimd', 'if0_38391513_demo');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($is_archived == 'false') {
        // Delete task from tasks
        $DELETE_TASK = "DELETE FROM tasks WHERE id = ?";
    } else {
        // Delete task from archived_tasks
        $DELETE_TASK = "DELETE FROM archived_tasks WHERE id = ?";
    }

    // Delete task
    $stmt = $conn->prepare($DELETE_TASK);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();

    echo "Task deleted successfully";

    // Close connection
    $stmt->close();
    $conn->close();
}

// Function to fetch tasks
if (isset($_GET['fetch_tasks'])) {
    // Create connection
    $conn = mysqli_connect('sql306.infinityfree.com', 'if0_38391513', 'tpftcimd', 'if0_38391513_demo');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $tasks = [];
    $archivedTasks = [];

    // Fetch tasks
    $result = $conn->query("SELECT * FROM tasks");
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }

    // Fetch archived tasks
    $result = $conn->query("SELECT * FROM archived_tasks");
    while ($row = $result->fetch_assoc()) {
        $archivedTasks[] = $row;
    }

    echo json_encode(['tasks' => $tasks, 'archivedTasks' => $archivedTasks]);

    // Close connection
    $conn->close();
}
?>
