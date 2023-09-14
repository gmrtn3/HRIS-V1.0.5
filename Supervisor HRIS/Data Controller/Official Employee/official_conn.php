<?php
include '../../config.php';

    if(isset($_POST['savedata']))
    {
        $employee_id = $_POST ['name_emp'];
        $name_company = $_POST ['company_name'];
        $start_date = $_POST['str_date'];
        $end_date = $_POST['end_date'];
        $start_time = $_POST['str_time'];
        $end_time = $_POST['end_time'];
        $location = $_POST['locate'];
        $reason = $_POST['text_reason'];

        if(isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
            $contents = file_get_contents($_FILES['file_upload']['tmp_name']);
            $escaped_contents = mysqli_real_escape_string($conn, $contents);
        } else {
            $escaped_contents = "";
        }


        $query = "INSERT INTO emp_official_tb (`employee_id`, `company_name`,`str_date`,`end_date`,`start_time`,`end_time`,`location`,`file_upl`,`reason`,`status`)
        VALUES ('$employee_id', '$name_company', '$start_date','$end_date','$start_time','$end_time','$location','$escaped_contents','$reason','Pending')";
        $query_run = mysqli_query($conn, $query);

        if($query_run)
        {
            header("Location: ../../official_emp.php?msg=Successfully Added");
        }
        else
        {
            echo "Failed: " . mysqli_error($conn);
        }

    }
?>