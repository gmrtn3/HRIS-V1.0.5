<?php
include '../../config.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $empid = $_POST['empid'];
    $date = $_POST['date'];
    $cash_advance = $_POST['cash_advance'];
    $status = $_POST['status'];


    //validate
    $existingSql = "SELECT * FROM pakyaw_cash_advance_tb WHERE 
            empid = '$empid' AND
            date = '$date'";
    
    $existingSql = mysqli_query($conn, $existingSql);

    if(mysqli_num_rows($existingSql) > 0){
        header("Location: ../../cash_advance?validationFailed=1");
        exit;
    }

    //insert
    $sql = "INSERT INTO pakyaw_cash_advance_tb (empid, date, cash_advance, status) 
            VALUES ('$empid', '$date', '$cash_advance', '$status')";
    
    if(mysqli_query($conn, $sql)){
        header("Location: ../../cash_advance");
    }else{
        echo "Error inserting data ". mysqli_error($conn);
    }

}

mysqli_close($conn);
?>

