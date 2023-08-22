<?php

    include '../../config.php';

    if(isset($_POST['updatedata'])){

        $id = $_POST['id'];
        $empid = $_POST['empid'];
        $cash_advance = $_POST['cash_advance'];
        $date = $_POST['date'];

        $currentDate = date('Y-m-d'); // Get the current date
        $dayOfWeek = date('N', strtotime($currentDate)); // Get the day of the week (1 = Monday, 7 = Sunday)
    
        // Calculate the start date and end date of the current week
        $startDate = date('Y-m-d', strtotime('-' . ($dayOfWeek - 1) . ' days', strtotime($currentDate)));
        $endDate = date('Y-m-d', strtotime('+' . (7 - $dayOfWeek) . ' days', strtotime($currentDate)));

        $sql = "SELECT SUM(pakyawan_based_work_tb.work_pay) AS cash_total, employee_tb.fname, employee_tb.empid, employee_tb.lname
        FROM pakyawan_based_work_tb
        INNER JOIN employee_tb ON pakyawan_based_work_tb.employee = employee_tb.empid
        WHERE pakyawan_based_work_tb.employee = $empid 
        AND `start_date` >= '$startDate' 
        AND `end_date` <= '$endDate'";

        $result = mysqli_query($conn, $sql);
            
        $row = mysqli_fetch_assoc($result);

        $cash_total = $row['cash_total'];

        // echo $cash_total;

        if($cash_advance > $cash_total){
            //input a validation
            header("Location: ../../cash_advance?error");
            exit;
        }else{
            $sql ="UPDATE `pakyaw_cash_advance_tb` SET `empid`='$empid', `cash_advance` = '$cash_advance', `date` = '$date' WHERE `id` = $id";
            $query_run = mysqli_query($conn, $sql);
    
            if($query_run){
                header("Location: ../../cash_advance");
              
            }
            else{
                echo '<script> alert("Data Not Updated"); </script>';
            }   
        }
      
    }
?>


