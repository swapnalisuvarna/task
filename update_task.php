<?php
require_once 'config/db.php';


if (isset($_GET['id'])) {

   
    $id = (int) $_GET['id'];

    if ($id > 0) {

        
        $sql = "UPDATE tasks SET status = 'Completed' WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            
            header("Location: index.php?success=updated");
        } else {
          
            header("Location: index.php?error=db");
        }

    } else {
        header("Location: index.php?error=invalid");
    }

} else {
   
    header("Location: index.php");
}

mysqli_close($conn);
exit();
?>
