<?php
if(isset($_POST['view_data'])){
    if($_POST['name_reqType'] === 'Leave Request'){
        header("Location: ../../leavereq.php");
    }
    else if($_POST['name_reqType'] === 'OverTime Request'){
        header("Location: ../../overtime_req.php");
    }
    else if($_POST['name_reqType'] === 'Undertime Request'){
        header("Location: ../../undertime_req.php");
    }
    else if($_POST['name_reqType'] === 'WFH Request'){
        header("Location: ../../Wfh_request.php");
    }
    else if($_POST['name_reqType'] === 'Official Business'){
        header("Location: ../../official_business.php");
    }
    else if($_POST['name_reqType'] === 'DTR Request'){
        header("Location: ../../dtr_admin.php");
    }
}
?>