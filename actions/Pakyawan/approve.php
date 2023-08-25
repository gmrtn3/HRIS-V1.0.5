<?php 
  include '../../config.php';

  if(isset($_POST['approve'])){
    $id = $_POST['id'];
    $status = $_POST['status'];

    // echo $status;

    $sql = "UPDATE `pakyaw_cash_advance_tb` SET `status` = '$status' WHERE `id` = $id";

    $query_run = mysqli_query($conn, $sql);
    if($query_run){
      header("Location: ../../cash_advance");
    
  }
  else{
      echo '<script> alert("Data Not Updated"); </script>';
  }

  }


?>