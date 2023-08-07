<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hris_db";


$conn = mysqli_connect($servername, $username, $password, $dbname);

if(isset($_POST['add_overtime']))
{
    $employee_id = $_POST ['name_emp'];
    $work_schedule = $_POST ['schedule'];
    $time_entry = $_POST ['time_start'];
    $time_out = $_POST ['time_end'];
    $ot_set = $_POST ['time_to'];
    $total_time = $_POST ['total_overtime'];
    $reason = $_POST ['file_reason'];


    if(isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
        $contents = file_get_contents($_FILES['file_upload']['tmp_name']);
        $escaped_contents = mysqli_real_escape_string($conn, $contents);
    } else {
        $escaped_contents = "";
    }

    $sql = "SELECT * FROM attendances WHERE `empid` = '$employee_id' AND `date` = '$work_schedule'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        $query = "INSERT INTO overtime_tb (`empid`,`work_schedule`,`time_in`,`time_out`,`ot_hours`,`total_ot`,`reason`,`file_attachment`,`status`)
        VALUES ('$employee_id', '$work_schedule', '$time_entry', '$time_out', '$ot_set', '$total_time', '$reason', '$escaped_contents', 'Pending')";
        $query_run = mysqli_query($conn, $query);
            if($query_run)
        {
            header("Location: ../../overtime_req.php?msg=Successfully Added");
        }else
        {
            echo "Failed: " . mysqli_error($conn);
        }
        }else
        {
        header("Location: ../../overtime_req.php?error=You Don't Have Attendance For that Date") ;
        }


  

}

?>