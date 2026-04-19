<?php
// ============================================
// update_task.php - Mark Task as Completed
// ============================================

// Include database connection
require_once 'config/db.php';

// Get the task ID from the URL (e.g., update_task.php?id=3)
if (isset($_GET['id'])) {

    // Convert to integer for safety (prevents SQL injection)
    $id = (int) $_GET['id'];

    // Make sure ID is a positive number
    if ($id > 0) {

        // SQL to update status to 'Completed'
        $sql = "UPDATE tasks SET status = 'Completed' WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            // Success
            header("Location: index.php?success=updated");
        } else {
            // DB error
            header("Location: index.php?error=db");
        }

    } else {
        header("Location: index.php?error=invalid");
    }

} else {
    // No ID provided
    header("Location: index.php");
}

// Close connection
mysqli_close($conn);
exit();
?>
