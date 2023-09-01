    <?php
    include 'config.php';

    if (isset($_POST['cutoffID']) && isset($_POST['startDate']) && isset($_POST['endDate'])) {
        $cutoffID = $_POST['cutoffID'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];

        $empCO = mysqli_query($conn, "SELECT * FROM empcutoff_tb WHERE `cutOff_ID` = '$cutoffID'");
        while ($emprow = $empCO->fetch_assoc()) {
            $EmployeeID = $emprow['emp_ID'];

            $attendances = "SELECT attendances.id,
                attendances.status,
                attendances.empid,
                employee_tb.empid,
                CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name,
                COUNT(attendances.`status`) AS totalPresent
                FROM attendances INNER JOIN employee_tb ON employee_tb.empid = attendances.empid
                WHERE (attendances.status = 'Present' OR attendances.status = 'On-Leave') AND attendances.`empid` = '$EmployeeID' AND attendances.`date` BETWEEN '$startDate' AND '$endDate'";
            $attrun = $conn->query($attendances);

            $TotalPresent = 0;
            while ($attrow = $attrun->fetch_assoc()) {
                $TotalPresent = $attrow['totalPresent'];
            
            }

            $attquery = "SELECT attendances.id,
            attendances.status,
            attendances.empid,
            employee_tb.empid,
            CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name,
            COUNT(attendances.`status`) AS totalAbsent
            FROM attendances INNER JOIN employee_tb ON employee_tb.empid = attendances.empid
            WHERE (attendances.status = 'Absent' OR attendances.status = 'LWOP') AND attendances.`empid` = '$EmployeeID' AND attendances.`date` BETWEEN '$startDate' AND '$endDate'";
            $query_run = $conn->query($attquery);    

            $TotalAbsent = 0;
            while($absrow = $query_run->fetch_assoc()){
                $TotalAbsent = $absrow['totalAbsent'];
            }

            //for late
            $latequery = "SELECT attendances.id,
            attendances.status,
            attendances.empid,
            employee_tb.empid,
            CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name,
            CONCAT(
                    FLOOR( 
                        SUM(TIME_TO_SEC(attendances.late)) / 3600
                    ),
                    'H:',
                    FLOOR(
                        (
                            SUM(TIME_TO_SEC(attendances.late)) % 3600
                        ) / 60
                    ),
                    'M'
                ) AS total_hours_minutesLATE
            FROM attendances INNER JOIN employee_tb ON employee_tb.empid = attendances.empid
            WHERE (attendances.status = 'Present' OR attendances.status = 'On-Leave') AND attendances.`empid` = '$EmployeeID' AND attendances.`date` BETWEEN '$startDate' AND '$endDate'";
            $late_run = $conn->query($latequery);
            
            $TotalLate = "0H:0M";
            while ($laterow = $late_run->fetch_assoc()) {
                $TotalLate = $laterow['total_hours_minutesLATE'];
            }

            //Undertime
            $Ut_query = " 
            SELECT
                IFNULL(
                    CONCAT(
                        FLOOR(SUM(TIME_TO_SEC(total_undertime)) / 3600), 'H:',
                        FLOOR((SUM(TIME_TO_SEC(total_undertime)) % 3600) / 60), 'M'
                    ),
                    '0H:0M'
                ) AS total_hours_minutesUndertime
            FROM 
                `undertime_tb` 
            WHERE 
                `empid` = '$EmployeeID' 
                AND `date` BETWEEN '$startDate' AND '$endDate' 
                AND `status` = 'Approved'";
            $ut_run = mysqli_query($conn, $Ut_query);

            $row_table_UT = mysqli_fetch_assoc($ut_run);
            $UT_time = $row_table_UT['total_hours_minutesUndertime'];

            //for OT 
            $OT_query = " 
            SELECT
                IFNULL(
                    CONCAT(
                        FLOOR(SUM(TIME_TO_SEC(total_ot)) / 3600), 'H:',
                        FLOOR((SUM(TIME_TO_SEC(total_ot)) % 3600) / 60), 'M'
                    ),
                    '0H:0M'
                ) AS total_hours_minutesOvertime
            FROM 
                `overtime_tb` 
            WHERE 
                `empid` = '$EmployeeID' 
                AND `work_schedule` BETWEEN '$startDate' AND '$endDate' 
                AND `status` = 'Approved'";
            $ot_run = mysqli_query($conn, $OT_query);

            $row_table_OT = mysqli_fetch_assoc($ot_run);
            $OT_time = $row_table_OT['total_hours_minutesOvertime'];
            
            
            //for total work
            $totalquery = "SELECT attendances.id,
            attendances.status,
            attendances.empid,
            employee_tb.empid,
            CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name,
            CONCAT(
                    FLOOR(
                        SUM(TIME_TO_SEC(attendances.total_work)) / 3600
                    ),
                    'H:',
                    FLOOR(
                        (
                            SUM(TIME_TO_SEC(attendances.total_work)) % 3600
                        ) / 60
                    ),
                    'M'
                ) AS total_hours_minutestotalHours
            FROM attendances INNER JOIN employee_tb ON employee_tb.empid = attendances.empid
            WHERE (attendances.status = 'Present' OR attendances.status = 'On-Leave') AND attendances.`empid` = '$EmployeeID' AND attendances.`date` BETWEEN '$startDate' AND '$endDate'";
            $total_run = $conn->query($totalquery);
            
            $TotalworkingHours = "0H:0M";
            while ($totalworkrow = $total_run->fetch_assoc()) {
                $TotalworkingHours = $totalworkrow['total_hours_minutestotalHours'];
            

            echo '<div class="table-responsive" id="table-responsiveness">
            <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Total Present</th>
                            <th>Total Absent</th>
                            <th>Late</th>
                            <th>Undertime</th>
                            <th>Overtime</th>
                            <th>Total Working Hours</th>
                        </tr>
                    </thead>
                <tbody>';
            echo '<tr>';
            echo '<td style="font-weight: 400;">' . $emprow['emp_ID'] . '</td>';
            echo '<td style="font-weight: 400;">' . $totalworkrow['full_name'] . '</td>';
            echo '<td style="font-weight: 400;">' . $TotalPresent . '</td>';
            echo '<td style="font-weight: 400;">' . $TotalAbsent . '</td>';
            echo '<td style="font-weight: 400;">' . $TotalLate . '</td>';
            echo '<td style="font-weight: 400;">' . $UT_time . '</td>';
            echo '<td style="font-weight: 400;">' . $OT_time . '</td>';
            echo '<td style="font-weight: 400;">' . $TotalworkingHours . '</td>';
            echo '</tr>';
            }
            echo '</div>';
        }
    }
    ?>
