<?php
include '../../config.php';

$conn = mysqli_connect($server, $user, $pass, $database);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $work_frequency = $_POST['work_frequency'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $employee = $_POST["employee"];
    $unit_type = $_POST["unit_type"];
    $unit_work = $_POST["unit_work"];

    

    // echo $employee;  
    
    // $calcSql = "SELECT * FROM employee_pakyawan_work_tb 
    //             INNER JOIN  piece_rate_tb ON employee_pakyawan_work_tb.piece_rate_id = piece_rate_tb.id
    //             WHERE employee_pakyawan_work_tb.empid = $employee";


    
    $calcSql = "SELECT piece.id, piece.unit_quantity, piece.unit_rate, piece.unit_type, emp_pakyaw.empid, emp_pakyaw.piece_rate_id FROM employee_pakyawan_work_tb AS emp_pakyaw
                INNER JOIN  piece_rate_tb AS piece ON emp_pakyaw.piece_rate_id = piece.id
                WHERE emp_pakyaw.empid = $employee AND piece.id = $unit_type";

    $calcResult = mysqli_query($conn, $calcSql);
    $calcRow = mysqli_fetch_assoc($calcResult);

    $subtotal = 0;
    $workpay = 0;

    $int_unit_rate = intval($calcRow['unit_rate']);
    $int_unit_quantity = intval($calcRow['unit_quantity']);

    if($unit_work > $int_unit_quantity){
        header("Location: ../../pakyawan_work?error");
        exit;
    }


    $subtotal += $int_unit_rate / $int_unit_quantity;

    // echo $subtotal;

    $workpay = $unit_work * $subtotal;

    echo $workpay;
    echo $calcRow['unit_type'];

    // echo $int_unit_rate / $int_unit_quantity;





    // Validate if there are conflicting records with the same work_frequency, employee, unit_type, and unit_work
    $existingSql = "SELECT * FROM pakyawan_based_work_tb WHERE 
                    work_frequency = '$work_frequency' AND 
                    employee = '$employee' AND 
                    unit_type = '$unit_type' AND 
                    unit_work = '$unit_work' AND 
                    
                    ((start_date >= '$start_date' AND start_date <= '$end_date') OR 
                    (end_date >= '$start_date' AND end_date <= '$end_date') OR 
                    (start_date <= '$start_date' AND end_date >= '$end_date'))";
    $existingQuery = mysqli_query($conn, $existingSql);

    if (mysqli_num_rows($existingQuery) > 0) {
        header("Location: ../../pakyawan_work?validationFailed=1");
        exit;
    }

    // Validate if the start_date is within the range of any existing records
    $startInRangeSql = "SELECT * FROM pakyawan_based_work_tb WHERE 
                        employee = '$employee' AND 
                        unit_type = '$unit_type' AND 
                        unit_work = '$unit_work' AND 
                        work_pay = '$workpay' AND
                        start_date <= '$start_date' AND 
                        work_pay = '$workpay' AND
                        end_date >= '$start_date'";
    $startInRangeQuery = mysqli_query($conn, $startInRangeSql);

    if (mysqli_num_rows($startInRangeQuery) > 0) {
        header("Location: ../../pakyawan_work?validationFailed=1");
        exit;
    }

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



    // Insert the data into the pakyawan_based_work_tb table
    $sql = "INSERT INTO pakyawan_based_work_tb (work_frequency, start_date, end_date, employee, unit_type, unit_work, work_pay ) VALUES ('$work_frequency','$start_date','$end_date','$employee', '$unit_type' ,'$unit_work', '$workpays')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../pakyawan_work");
    } else {
        echo "Error inserting data: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
