<?php

include 'config.php';

$holidate = $_POST['holidate'];

$sql = "SELECT * FROM holiday_tb WHERE `date_holiday` = '$holidate' ";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0){
    echo "You cannot enter a holiday date that is exist.";
}


?>