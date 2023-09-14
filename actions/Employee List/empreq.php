<?php
include '../../config.php';

if (isset($_POST['view_data'])) {
    $table_id = $_POST['emp_req_id'];
    $request_type = $_POST['name_reqType'];

    if ($request_type === 'Leave Request') {
        header("Location: ../../leavereq.php?id=" . $table_id);
    }
    else if ($request_type === 'OverTime Request') {
        header("Location: ../../overtime_req.php?id=" . $table_id);
    } 
    else if ($request_type === 'Undertime Request') {
        header("Location: ../../undertime_req.php?id=" . $table_id);
    } 
    else if ($request_type === 'WFH Request') {
        header("Location: ../../Wfh_request.php?id=" . $table_id);
    } 
    else if ($request_type === 'Official Business') {
        header("Location: ../../official_business.php?id=" . $table_id);
    } 
    else if ($request_type === 'DTR Request') {
        header("Location: ../../dtr_admin.php?id=" . $table_id);
    }
}
?>