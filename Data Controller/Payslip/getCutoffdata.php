<?php
//para sa pag kuha ng cutoff dates
    include '../../config.php';
    $value = $_GET['value'];



    $sql = "SELECT * FROM `cutoff_tb` WHERE `col_cutOffNum` = '$value'";
    $result = $conn->query($sql);
    
    // Check if any rows are fetched
    if ($result->num_rows > 0) {
      $row = mysqli_fetch_assoc($result);
      $strTOend = $row['col_startDate'] . " - " . $row['col_endDate'];
      echo $strTOend;
    } else {
      $strTOend = "No cutoff.";
      echo $strTOend;
    }
    

?>