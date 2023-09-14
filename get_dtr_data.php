<?php 
include 'config.php';

if (isset($_POST['employeeId']) && isset($_POST['minDate']) && isset($_POST['maxDate'])) {
    $employeeId = $_POST['employeeId'];
    $minDate = $_POST['minDate'];
    $maxDate = $_POST['maxDate'];

    echo '<div class="table-responsive" id="table-responsiveness">
             <table id="order-listing" class="table">
               <thead>
                   <tr>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Late</th>
                    <th>Undertime</th>
                    <th>Overtime</th>
                    <th>Total Working Hours</th>
                    </tr>
                <thead>';

        $query = "SELECT * FROM attendances WHERE `empid` = '$employeeId' AND `date` BETWEEN '$minDate' AND '$maxDate'";

        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td style="font-weight: 400; background-color: white;">' . $row['status'] . '</td>';
            echo '<td style="font-weight: 400; background-color: white;">' . $row['date'] . '</td>';
            echo '<td style="font-weight: 400; background-color: white;">' . $row['time_in'] . '</td>';
            echo '<td style="font-weight: 400; background-color: white;">' . $row['time_out'] . '</td>';
                // Check kung ang 'late' ay hindi 00:00:00
            if ($row['late'] != '00:00:00') {
                echo '<td style="font-weight: 400;  background-color: white; color: red;">' . $row['late'] . '</td>';
            } else {
                echo '<td style="font-weight: 400; background-color: white;">' . $row['late'] . '</td>';
            }
            echo '<td style="font-weight: 400; background-color: white;">' . $row['early_out'] . '</td>';
            echo '<td style="font-weight: 400; background-color: white;">' . $row['overtime'] . '</td>';
            echo '<td style="font-weight: 400; background-color: white;">' . $row['total_work'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
}

?>