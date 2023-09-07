<?php
   include '../../config.php';

if(isset($_POST['delete_data']))
{
    $id = $_POST['delete_id'];
    $designate = $_POST['designation'];

    if ($designate > 0) {
        header("Location: ../../Branch.php?error=You cannot delete a branch that has employee designated");
    }
    else
    {
        $query = "DELETE FROM branch_tb WHERE id='$id'";
        $query_run = mysqli_query($conn, $query);
    
        if($query_run)
        {
            header("Location: ../../Branch.php?msg=Delete Record Successfully");
        }
        else
        {
            echo "Failed: " . mysqli_error($conn);
        }
    }

}