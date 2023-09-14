<?php
include '../../config.php';

if(isset($_POST['add_event'])){

    $createdBy = mysqli_real_escape_string($conn, $_POST['name_emp']);
    $titleEvent = mysqli_real_escape_string($conn, $_POST['event_title']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $typeEvent = mysqli_real_escape_string($conn, $_POST['event_type']);


    $query = "INSERT INTO event_tb (`empid`, `event_title`, `start_date`, `end_date`, `event_type`)
    VALUES ('$createdBy', '$titleEvent', '$start_date', '$end_date', '$typeEvent')";


    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        $sql = "INSERT INTO schedule_list (`title`, `description`, `start_datetime`, `end_datetime`)
                VALUES ('$titleEvent', '$typeEvent', '$start_date', '$end_date') ";

                $sql_run = mysqli_query($conn, $sql);
        header("Location: ../../Calendar?msg=Successfully Added");
    }
    else
    {
        echo "Failed: " . mysqli_error($conn);
    }
}
?>