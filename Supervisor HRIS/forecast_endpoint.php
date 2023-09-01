<?php
    // Handle fetching forecast data based on empid
    $empid = $_GET['empid'];
    
    // Perform your forecast calculations here
    // $forecastData = "Forecast for Employee $empid: ...";

    // echo $forecastData;
    // echo $empid;

    include 'config.php';

    

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

    @$work_pay = $row['cash_total'];

    $fname = $row['fname'];
    
    ?>  
    
    <div class="mt-3" >
    <?php if(!empty($work_pay)){
        ?>
          <p style="font-size: 1em; color:green">You can cash advance <?php echo $fname ?> up to <?php echo $work_pay ?></p>
    <?php      
    } else{
        ?> <p style="font-size:  1em; color:red">No workload this week.</p> <?php
    }
    
    ?>

    </div>
    <!-- <p>You can cash advance <?php echo $fname ?> up to <?php echo $work_pay ?></p> -->

<!-- 
if(!empty($fname)){
    echo $fname;
}else{

} -->
