<?php
// ============================================
// delete_task.php - Delete a Task from Database
// ============================================

// Include database connection
require_once 'config/db.php';

// Get the task ID from the URL (e.g., delete_task.php?id=3)
if (isset($_GET['id'])) {

    // Cast to integer for security (no SQL injection possible with integers)
    $id = (int) $_GET['id'];

    // Validate the ID
    if ($id > 0) {

        // SQL query to delete the task
        $sql = "DELETE FROM tasks WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            // Check if any row was actually deleted
            if (mysqli_affected_rows($conn) > 0) {
                header("Location: index.php?success=deleted");
            } else {
                // Task ID didn't exist
                header("Location: index.php?error=notfound");
            }
        } else {
            // Database error
            header("Location: index.php?error=db");
        }

    } else {
        header("Location: index.php?error=invalid");
    }

} else {
    // No ID in URL
    header("Location: index.php");
}

// Close connection
mysqli_close($conn);
exit();
?>
