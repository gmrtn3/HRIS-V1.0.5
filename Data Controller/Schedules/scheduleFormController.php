<?php

    $schedule_name = $_POST['schedule_name'];

    $monday = filter_input(INPUT_POST, "monday", FILTER_SANITIZE_STRING);
    $mon_timein = filter_input(INPUT_POST, "mon_timein", FILTER_SANITIZE_STRING);
    $mon_timeout = filter_input(INPUT_POST, "mon_timeout", FILTER_SANITIZE_STRING);
    $mon_wfh = filter_input(INPUT_POST, "mon_wfh", FILTER_SANITIZE_STRING);

    $tuesday = filter_input(INPUT_POST, "tuesday", FILTER_SANITIZE_STRING);
    $tues_timein = filter_input(INPUT_POST, "tues_timein", FILTER_SANITIZE_STRING);
    $tues_timeout = filter_input(INPUT_POST, "tues_timeout", FILTER_SANITIZE_STRING);
    $tues_wfh = filter_input(INPUT_POST, "tues_wfh", FILTER_SANITIZE_STRING);

    $wednesday = filter_input(INPUT_POST, "wednesday", FILTER_SANITIZE_STRING);
    $wed_timein = filter_input(INPUT_POST, "wed_timein", FILTER_SANITIZE_STRING);
    $wed_timeout = filter_input(INPUT_POST, "wed_timeout", FILTER_SANITIZE_STRING);
    $wed_wfh = filter_input(INPUT_POST, "wed_wfh", FILTER_SANITIZE_STRING);

    $thursday = filter_input(INPUT_POST, "thursday", FILTER_SANITIZE_STRING);
    $thurs_timein = filter_input(INPUT_POST, "thurs_timein", FILTER_SANITIZE_STRING);
    $thurs_timeout = filter_input(INPUT_POST, "thurs_timeout", FILTER_SANITIZE_STRING);
    $thurs_wfh = filter_input(INPUT_POST, "thurs_wfh", FILTER_SANITIZE_STRING);

    $friday = filter_input(INPUT_POST, "friday", FILTER_SANITIZE_STRING);
    $fri_timein = filter_input(INPUT_POST, "fri_timein", FILTER_SANITIZE_STRING);
    $fri_timeout = filter_input(INPUT_POST, "fri_timeout",FILTER_SANITIZE_STRING);
    $fri_wfh = filter_input(INPUT_POST, "fri_wfh", FILTER_SANITIZE_STRING);

    $saturday = filter_input(INPUT_POST, "saturday", FILTER_SANITIZE_STRING);
    $sat_timein = filter_input(INPUT_POST, "sat_timein", FILTER_SANITIZE_STRING);
    $sat_timeout = filter_input(INPUT_POST, "sat_timeout", FILTER_SANITIZE_STRING);
    $sat_wfh = filter_input(INPUT_POST, "sat_wfh", FILTER_SANITIZE_STRING);

    $sunday = filter_input(INPUT_POST, "sunday", FILTER_SANITIZE_STRING);
    $sun_timein = filter_input(INPUT_POST, "sun_timein", FILTER_SANITIZE_STRING);
    $sun_timeout = filter_input(INPUT_POST, "sun_timeout", FILTER_SANITIZE_STRING);
    $sun_wfh = filter_input(INPUT_POST, "sun_wfh", FILTER_SANITIZE_STRING);

    $flexible = filter_input(INPUT_POST, "flexible", FILTER_SANITIZE_STRING);
    $grace_period =filter_input(INPUT_POST, "grace_period", FILTER_SANITIZE_STRING);
    $sched_ot = filter_input(INPUT_POST, "sched_ot", FILTER_SANITIZE_STRING);
    $sched_holiday = filter_input(INPUT_POST, "sched_holiday", FILTER_SANITIZE_STRING);
    $restday = filter_input(INPUT_POST, "restday", FILTER_SANITIZE_STRING);
    $enable_grace_period = filter_input(INPUT_POST, "enable_grace_period", FILTER_SANITIZE_STRING);
    $enable_sched_ot = filter_input(INPUT_POST, "enable_sched_ot", FILTER_SANITIZE_STRING);


    $days = [
        'monday' => filter_input(INPUT_POST, "monday", FILTER_SANITIZE_STRING),
        'tuesday' => filter_input(INPUT_POST, "tuesday", FILTER_SANITIZE_STRING),
        'wednesday' => filter_input(INPUT_POST, "wednesday", FILTER_SANITIZE_STRING),
        'thursday' => filter_input(INPUT_POST, "thursday", FILTER_SANITIZE_STRING),
        'friday' => filter_input(INPUT_POST, "friday", FILTER_SANITIZE_STRING),
        'saturday' => filter_input(INPUT_POST, "saturday", FILTER_SANITIZE_STRING),
        'sunday' => filter_input(INPUT_POST, "sunday", FILTER_SANITIZE_STRING)
    ];
    
    // Remove empty values from the array
    $days = array_filter($days);
    
    // Check if the resulting array is empty
    if (empty($days)) {
        $errorMsg = "Please fill schedule setup!";
        header("Location: ../../scheduleForm.php?errorMsg=" . urlencode($errorMsg) . "&showError=true");
        exit;
    }
    

    $server = "localhost";
    $user = "root";
    $pass = "";
    $database = "hris_db";

    $conn = mysqli_connect(hostname: $server,
                            username: $user,
                            password: $pass,
                            database: $database);

    if(mysqli_connect_errno()){
        die("Connection error: " .mysqli_connect_error());
    }

    

    $sql = "INSERT INTO schedule_tb (schedule_name, monday, mon_timein, mon_timeout, mon_wfh, tuesday, tues_timein, tues_timeout, tues_wfh, wednesday, wed_timein, wed_timeout, wed_wfh, thursday, thurs_timein, thurs_timeout, thurs_wfh, friday, fri_timein, fri_timeout, fri_wfh, saturday, sat_timein, sat_timeout, sat_wfh, sunday, sun_timein, sun_timeout, sun_wfh, flexible, grace_period, sched_ot, sched_holiday,restday, enable_grace_period, enable_sched_ot)
             VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, ?,?)";

    $stmt = mysqli_stmt_init($conn);

    if(! mysqli_stmt_prepare($stmt, $sql)){
        die(mysqli_error($conn));
    }
    
    mysqli_stmt_bind_param($stmt, "ssssssssssssssssssssssssssssssssssss",
    $schedule_name, $monday, $mon_timein, $mon_timeout, $mon_wfh,$tuesday, $tues_timein, $tues_timeout, $tues_wfh,$wednesday, $wed_timein, $wed_timeout, $wed_wfh,$thursday, $thurs_timein, $thurs_timeout, $thurs_wfh,$friday, $fri_timein, $fri_timeout, $fri_wfh,$saturday, $sat_timein, $sat_timeout, $sat_wfh,$sunday, $sun_timein, $sun_timeout, $sun_wfh,$flexible, $grace_period, $sched_ot, $sched_holiday,$restday, $enable_grace_period, $enable_sched_ot);

    mysqli_stmt_execute($stmt);

    header("Location: ../../scheduleForm.php");

    // echo $saturday, "<br>";
    // echo $enable_grace_period , "<br>";
    // echo $enable_sched_ot;
    


  ?>