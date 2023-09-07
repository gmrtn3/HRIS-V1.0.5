<?php
 include '../../config.php';

if(isset($_POST['delete_data']))
{
    $id = $_POST['delete_id'];


    $query = "DELETE FROM emp_dtr_tb WHERE id='$id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        header("Location: ../../dtr_emp.php?msg=Delete Record Successfully");
    }
    else
    {
        echo "Failed: " . mysqli_error($conn);
    }

}



?>