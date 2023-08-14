<?php

require_once 'Databases/Database.php';

$db = new DatabaseShit();


$query = "SELECT attendances.id,
                employee_tb.empid,
                CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                attendances.date, attendances.time_in,
                attendances.time_out,
                SUM(CASE WHEN attendances.status = 'Absent' THEN 1 ELSE 0 END) AS absent_count,
                SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.overtime)))AS total_overtime,
                SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.late))) AS total_late,
                SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.early_out))) AS total_early_out,
                SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.total_work))) AS total_work
                FROM attendances
                INNER JOIN employee_tb ON employee_tb.empid = attendances.empid
                INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                WHERE MONTH(attendances.date) = 08
                AND YEAR(attendances.date) = 2023
                AND dept_tb.col_deptname = 'Software'
                GROUP BY employee_tb.empid;";

$q = $db->getConnection()->prepare($query);
$q->execute();

echo "<pre>";
var_dump($q->fetchAll(PDO::FETCH_ASSOC));
