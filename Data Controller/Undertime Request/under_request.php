<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hris_db";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if(isset($_POST['add_undertime']))
    {

    $employee = $_POST['name_emp'];
    $undertime_date = $POST['date_pick'];
    $starttime = $_POST['start_time'];
    $endtime = $_POST['end_time'];
    $total_undertime = $_POST['total_undertime'];
    $reason = $_POST['under_reason'];

    if(isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
        $contents = file_get_contents($_FILES['file_upload']['tmp_name']);
        $escaped_contents = mysqli_real_escape_string($conn, $contents);
    } else {
        $escaped_contents = "";
    }

    $query = "INSERT INTO undertime_tb (`empid`,`date`,`start_time`,`end_time`,`total_undertime`,`file_attachment`,`reason`,`status`)
              VALUES ('$employee','$undertime_date','$starttime','$endtime','$total_undertime','$reason','$escaped_contents','Pending')";
    $query_run = mysqli_query($conn, $query);
    
    if($query_run)
    {
        header("Location: ../../undertime_req.php?msg=Successfully Added");
    }
    else
    {
        echo "Failed: " . mysqli_error($conn);
    }

}
?>