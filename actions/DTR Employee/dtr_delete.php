<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hris_db";


    $conn = mysqli_connect($servername, $username,  $password, $dbname);

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