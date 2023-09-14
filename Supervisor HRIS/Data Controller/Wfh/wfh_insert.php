<?php
include '../../config.php';

    if(isset($_POST['add_wfh']))
    {
        $employee_id = $_POST ['name_emp'];
        $wfh_date = $_POST ['date_wfh'];
        $schedule_type = $_POST['sched_type'];
        $start_time = $_POST['time_from'];
        $end_time = $_POST['time_to'];
        $request_desc = $_POST['text_description'];

        if(isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
            $contents = file_get_contents($_FILES['file_upload']['tmp_name']);
            $escaped_contents = mysqli_real_escape_string($conn, $contents);
        } else {
            $escaped_contents = "";
        }


        $query = "INSERT INTO wfh_tb (`empid`, `wfh_date`, `start_time`, `end_time`, `schedule_type`,`upload_file`, `reason`,`status`)
        VALUES ('$employee_id', '$wfh_date', '$start_time','$end_time', '$schedule_type', '$escaped_contents', '$request_desc', 'Pending')";
        $query_run = mysqli_query($conn, $query);

        if($query_run)
        {
            header("Location: ../../Wfh_request.php?msg=Successfully Added");
        }
        else
        {
            echo "Failed: " . mysqli_error($conn);
        }

    }
?>