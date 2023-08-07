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
        header("Location: ../../Position.php?error=You cannot delete a position that has employee designated");
    }
    else{
        $query = "DELETE FROM positionn_tb WHERE id='$id'";
        $query_run = mysqli_query($conn, $query);
    
        if($query_run)
        {
            header("Location: ../../Position.php?msg=Delete Record Successfully");
        }
        else
        {
            echo "Failed: " . mysqli_error($conn);
        }
    }

   

}



?>