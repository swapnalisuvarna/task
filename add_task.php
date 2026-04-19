<?php
// ============================================
// add_task.php - Insert New Task into Database
// ============================================

// Include database connection
require_once 'config/db.php';

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get task name from form, trim whitespace
    $task_name = trim($_POST['task_name']);

    // Get due date (optional field)
    $due_date  = !empty($_POST['due_date']) ? $_POST['due_date'] : NULL;

    // Validate: task name must not be empty
    if (empty($task_name)) {
        // Redirect back with error
        header("Location: index.php?error=empty");
        exit();
    }

    // Sanitize input to prevent SQL injection
    $task_name = mysqli_real_escape_string($conn, $task_name);

    // Build the SQL query
    if ($due_date) {
        $due_date_escaped = mysqli_real_escape_string($conn, $due_date);
        $sql = "INSERT INTO tasks (task_name, due_date, status)
                VALUES ('$task_name', '$due_date_escaped', 'Pending')";
    } else {
        $sql = "INSERT INTO tasks (task_name, status)
                VALUES ('$task_name', 'Pending')";
    }

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Success: redirect back to main page
        header("Location: index.php?success=added");
    } else {
        // Failure: redirect with error
        header("Location: index.php?error=db");
    }

} else {
    // If someone visits this file directly without POST, redirect
    header("Location: index.php");
}

// Close the database connection
mysqli_close($conn);
exit();
?>
