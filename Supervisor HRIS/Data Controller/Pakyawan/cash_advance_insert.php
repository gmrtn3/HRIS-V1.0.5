<?php
include '../../config.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $empid = $_POST['empid'];
    $date = $_POST['date'];
    $cash_advance = $_POST['cash_advance'];
    $status = $_POST['status'];


    if($cash_advance <= 0 ){
        header("Location: ../../cash_advance?error");
        exit;
    }else{
        $currentDate = date('Y-m-d'); // Get the current date
        $dayOfWeek = date('N', strtotime($currentDate)); // Get the day of the week (1 = Monday, 7 = Sunday)


        // Calculate the start date and end date of the current week
        $startDate = date('Y-m-d', strtotime('-' . ($dayOfWeek - 1) . ' days', strtotime($currentDate)));
        $endDate = date('Y-m-d', strtotime('+' . (7 - $dayOfWeek) . ' days', strtotime($currentDate)));

    
        $validationSql = "SELECT * FROM pakyaw_cash_advance_tb WHERE `empid` = '$empid' AND `date` = '$date'";

        $validationResult = mysqli_query($conn, $validationSql);
        $validationRow = mysqli_fetch_assoc($validationResult);

        @$validEmpid = $validationRow['empid'];
        @$validDate = $validationRow['date'];

        $weekValidationSql = "SELECT * FROM pakyaw_cash_advance_tb WHERE `date` BETWEEN '$startDate' AND '$endDate' AND `empid` = '$empid' ";
        
        $weekValidationResult = mysqli_query($conn, $weekValidationSql);

        $weekValidationRow = mysqli_fetch_assoc($weekValidationResult);

        @$dates = $weekValidationRow['date'];
        @$empids = $weekValidationRow['empid'];

        // echo $dates;
        // echo "<br>";
        // echo $empids;


        if($dates >= $startDate && $dates <= $endDate){
            header("Location: ../../cash_advance?error");
            // echo "may mali";
            exit;
        } else{


        
        if($empid == $validEmpid && $date == $validDate){
            header("Location: ../../cash_advance?error");
            exit;
        }else{

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
        
            echo $cash_total;

            if($cash_advance > $cash_total){
                header("Location: ../../cash_advance?error");
                exit;
            }else{

                //validate
                $existingSql = "SELECT * FROM pakyaw_cash_advance_tb WHERE 
                        empid = '$empid' AND
                        date = '$date'";
                
                $existingSql = mysqli_query($conn, $existingSql);

                if(mysqli_num_rows($existingSql) > 0){
                    header("Location: ../../cash_advance?validationFailed=1");
                    exit;
                }

                //insert
                $sql = "INSERT INTO pakyaw_cash_advance_tb (empid, date, cash_advance, status) 
                        VALUES ('$empid', '$date', '$cash_advance', '$status')";
                
                if(mysqli_query($conn, $sql)){
                    header("Location: ../../cash_advance");
                }else{
                    echo "Error inserting data ". mysqli_error($conn);
                }

                }

            }
        }
    }
}
mysqli_close($conn);
?>

