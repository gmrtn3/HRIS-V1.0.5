<?php
   include '../../config.php';

if(isset($_POST['update_data']))
{
    $id = $_POST['update_id'];
    $position = $_POST['position_text'];


    $query = "UPDATE positionn_tb SET position='$position' WHERE id='$id'";
    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        header("Location: ../../Position.php?msg=Update Record Successfully");
    }
    else
    {
        echo "Failed: " . mysqli_error($conn);
    }

}



?>