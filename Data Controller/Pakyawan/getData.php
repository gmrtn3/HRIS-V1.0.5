<?php

    include '../../config.php';

    if(isset($_POST['updatedata'])){

        $id = $_POST['id'];
        $employee = $_POST['employee'];
        $work_frequency = $_POST['work_frequency'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $unit_type = $_POST['unit_type'];
        $unit_work = $_POST['unit_work'];
       

        $calcSqls = "SELECT piece.id, piece.unit_quantity, piece.unit_rate, piece.unit_type, emp_pakyaw.empid, emp_pakyaw.piece_rate_id FROM employee_pakyawan_work_tb AS emp_pakyaw
        INNER JOIN  piece_rate_tb AS piece ON emp_pakyaw.piece_rate_id = piece.id
        WHERE emp_pakyaw.empid = $employee AND piece.id = $unit_type";
    
        $calcResults = mysqli_query($conn, $calcSqls);
        $calcRows = mysqli_fetch_assoc($calcResults);
    
        $subtotals = 0;
        $workpays = 0;
    
        $int_unit_rates = intval($calcRows['unit_rate']);
        $int_unit_quantitys = intval($calcRows['unit_quantity']);
    
        $subtotals += $int_unit_rates / $int_unit_quantitys;
    
        // echo $subtotal;
    
        $workpays = $unit_work * $subtotals;
    
        echo $workpays;


        $sql ="UPDATE `pakyawan_based_work_tb` SET `employee`='$employee', `work_frequency` = '$work_frequency', `start_date` = '$start_date', `end_date` = '$end_date' , `unit_type` = '$unit_type' , `unit_work` = '$unit_work', `work_pay` = $workpays  WHERE `id` = $id";
        $query_run = mysqli_query($conn, $sql);

        if($query_run){
            header("Location: ../../pakyawan_work");
          
        }
        else{
            echo '<script> alert("Data Not Updated"); </script>';
        }

    
    
      
    }
?>