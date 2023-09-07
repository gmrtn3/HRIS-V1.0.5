<?php
include '../../config.php';

if(isset($_POST['delete_data']))
{
    $id = $_POST['id'];

        $query = "DELETE FROM pakyawan_based_work_tb WHERE id='$id'";
        $query_run = mysqli_query($conn, $query);
    
        if($query_run)
        {
            header("Location: ../../pakyawan_work");
        }
        else
        {
            echo "Failed: " . mysqli_error($conn);
        }
    }