<?php
include '../../config.php';

if(isset($_POST['add_undertime'])){

$employee_id = $_POST['name_emp'];
$date_undertime = $_POST['date_undertime'];
$start_undertime = $_POST['under_time_from'];
$end_undertime = $_POST['under_time_to'];
$total_undertime = $_POST['total_undertime'];
$reason_undertime = $_POST['undertime_reason'];


if(isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
    $contents = file_get_contents($_FILES['file_upload']['tmp_name']);
    $escaped_contents = mysqli_real_escape_string($conn, $contents);
} else {
    $escaped_contents = "";
}

$sql = "SELECT * FROM attendances WHERE `empid` = '$employee_id' AND `date` = '$date_undertime'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        $query = "INSERT INTO undertime_tb (`empid`,`date`,`start_time`,`end_time`,`total_undertime`,`file_attachment`,`reason`,`status`)
        VALUES ('$employee_id', '$date_undertime', '$start_undertime', '$end_undertime', '$total_undertime', '$escaped_contents', '$reason_undertime', 'Pending')";
        $query_run = mysqli_query($conn, $query);
            if($query_run)
        {
            header("Location: ../../undertime_req.php?msg=Successfully Added");
        }else{
            echo "Failed: " . mysqli_error($conn);
        }
        }else{
        header("Location: ../../undertime_req.php?error=You Don't Have Attendance For that Date") ;
        }

}

?>