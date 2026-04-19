<?php

require_once 'config/db.php';


if (isset($_GET['id'])) {

   
    $id = (int) $_GET['id'];


    if ($id > 0) {

        
        $sql = "DELETE FROM tasks WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            
            if (mysqli_affected_rows($conn) > 0) {
                header("Location: index.php?success=deleted");
            } else {
              
                header("Location: index.php?error=notfound");
            }
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
