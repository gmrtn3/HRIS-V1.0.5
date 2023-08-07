<?php

    include '../../config.php';

    if(isset($_POST['updatedata'])){

        $id = $_POST['id'];
        $unit_type = $_POST['unit_type'];
        $unit_quantity = $_POST['unit_quantity'];
        $unit_rate = $_POST['unit_rate'];

        $sql ="UPDATE `piece_rate_tb` SET `unit_type`='$unit_type', `unit_quantity` = '$unit_quantity', `unit_rate` = '$unit_rate' WHERE `id` = $id";
        $query_run = mysqli_query($conn, $sql);

        if($query_run){
            header("Location: ../../Piece_rate");
          
        }
        else{
            echo '<script> alert("Data Not Updated"); </script>';
        }

        

    
      
    }
?>