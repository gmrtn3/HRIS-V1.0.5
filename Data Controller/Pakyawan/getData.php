<?php

    include '../../config.php';

    if(isset($_POST['updatedata'])){

        $id = $_POST['id'];
        $employee = $_POST['employee'];
        $work_frequency = $_POST['work_frequency'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $unit_type = $_POST['unit_type'];
        $unit_work = $_POST['unit_work'];
       

        $sql ="UPDATE `pakyawan_based_work_tb` SET `employee`='$employee', `work_frequency` = '$work_frequency', `start_date` = '$start_date', `end_date` = '$end_date' , `unit_type` = '$unit_type' , `unit_work` = '$unit_work' WHERE `id` = $id";
        $query_run = mysqli_query($conn, $sql);

        if($query_run){
            header("Location: ../../pakyawan_work");
          
        }
        else{
            echo '<script> alert("Data Not Updated"); </script>';
        }

    
    
      
    }
?>