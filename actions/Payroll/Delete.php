<?php
if(isset($_POST['btn_delete_modal'])){
    include "../../config.php";
    echo $cutOffID = $_POST['name_CutoffID'];

    $sql = "DELETE FROM `cutoff_tb` WHERE `col_ID` = '$cutOffID'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $sql = "DELETE FROM `empcutoff_tb` WHERE `cutOff_ID` = '$cutOffID'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
    
            header ("Location: ../../cutoff.php?msg= Record deleted Successfully");
        }
        else {
            echo "Failed: " . mysqli_error($conn);
        }

    }
    else {
        echo "Failed: " . mysqli_error($conn);
    }

}
    
?>