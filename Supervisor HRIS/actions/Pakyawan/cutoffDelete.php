<?php
if(isset($_POST['btn_delete_modal'])){
    include "../../config.php";
    echo $cutOffID = $_POST['id'];

    $sql = "DELETE FROM `pakyawan_cutoff_tb` WHERE `id` = '$cutOffID'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $sql = "DELETE FROM `pakyawan_payroll_tb` WHERE `cutoff_id` = '$cutOffID'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
    
            header ("Location: ../../pakyawan_payroll?msg= Record deleted Successfully");
            // echo "<script> alert($cutOffID) </script>";
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