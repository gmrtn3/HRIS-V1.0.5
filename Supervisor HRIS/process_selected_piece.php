<?php
error_reporting(0);
include 'config.php';

$unit_work = $_POST['unit_work'];
$unit_type = $_POST['unit_type'];

$sql = "SELECT * FROM piece_rate_tb WHERE id = $unit_type";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if ($unit_work == ''){
    $unit_work = 0;
}


$unit_quantity = intval($row['unit_quantity']);
$unit_rate = intval($row['unit_rate']);

$subtotal = 0;
$workpay = 0;

$subtotal += $unit_rate / $unit_quantity;
$workpay = $unit_work * $subtotal;


if($unit_work > $unit_quantity){
   echo "Employee exceed the unit quantity of the piece rate which is ", $unit_quantity, " the employee unit work is ", $unit_work;
}else{
    echo "The total Work Pay is ", $workpay;
}


?>
