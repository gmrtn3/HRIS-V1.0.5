<?php

// use Db\CustomQuery;
require_once './Databases/CustomQuery.php';
// require_once './SpecialFolders/Databases/Database.php';

function findMe(){
    $data = CustomQuery::SelectAll('attendance_time_in')
        ->InnerJoin('attendance_time_out', 'time_in_personId', 'time_out_personId')
        ->JoinAnd('date_time_in', 'date_time_out')
        ->InnerJoin('employee_tb', 'time_in_personId', 'empid');
    return $data;
}

$data = FindMe()->Execute();

echo "<pre>";
var_dump($data);
