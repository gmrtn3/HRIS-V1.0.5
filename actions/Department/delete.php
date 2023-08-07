<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hris_db";


    $conn = mysqli_connect($servername, $username,  $password, $dbname);

if(isset($_POST['delete_data']))
{
    $id = $_POST['delete_id'];
    $designate = $_POST['designation'];

    if ($designate > 0) {
        header("Location: ../../Department.php?error=You cannot delete a department that has employee designated");
    }
    else
    {
        $query = "DELETE FROM dept_tb WHERE col_ID='$id'";
        $query_run = mysqli_query($conn, $query);
    
        if($query_run)
        {
            header("Location: ../../Department.php?msg=Delete Record Successfully");
        }
        else
        {
            echo "Failed: " . mysqli_error($conn);
        }
    }

}