<?php

use BiometricsData\NewRecords;

// use BiometricsData\Records;

require 'NewRecordsHandler.php';
// require 'Models\Attendance.php';

// // $recs = new Records('12345', '-1', endpoint:'http://192.168.0.121:8090');

// // $recs->Test();
$records = NewRecords::NewFindRecords('12345', ip: 'http://192.168.0.102:8090');
// // $records->Test();
// $records->getTime();
// echo "<pre>";
// echo "Time Out: ".$records->GetTimeOutWithinThisDate('2023-08-01')."<br>";
// echo "Time In: ".$records->GetTimeInWithinThisDate('2023-08-01');
// // var_dump($records->GetTimeOutWithinThisDate('2023-08-01'));

spl_autoload_register(function ($className) {
    $filename = './' . $className . '.php';
    if (file_exists($filename)) {
        require_once $filename;
    }
});

$dir = '/hris-wews/HRISv1/SpecialFolders/';
require_once '../Models/Attendance.php';
require_once '../Models/Classification.php';
require_once '../Models/Employees.php';
require_once '../Databases/Model.php';
$attendance = new Attendance();
$Classification = new Classification();
$employees = new Employees();

// $data = Model::viewAll($attendance);
// $data = $attendance->find('10');

$data = $employees->JoinWith($Classification, 'classification');


echo "<pre>";
// foreach ($data['result'] as $d){
//     var_dump($d);
// }
// var_dump($data['result']);
if (is_array($data['result'])) {
    foreach ($data['result'] as $d) {
        echo '<pre>';
        echo $d['company_code'];
        echo $d['fname'];
    }
} else {
    echo "not an array";
}

// var_dump($data);
// echo $data['result'];
// echo $attendance->findWith('empid', '002');
// var_dump($attendance->find(1));
// $find = $attendance->findWith('empid', '002');
// var_dump($find['result']);
// echo $find['rowsAffected'];
// if ($find['rowsAffected'] == 1){
//     echo 'data exists';
// }
// else{
//     echo 'data does not exist';
// }

// echo $attendance->updateWhere('empid', '002', [
//     'name' => 'dummy'
// ]);

$allrecs = $records->All();

// foreach ($allrecs as $r){
//     $date = date('Y-m-d', $r['time']/1000);

//     $d = $attendance->findWith('empid', $r['personId']);

//     $recs = NewRecords::NewFindRecords('12345', $r['personId'], ip:'192.168.0.102:8090');

//     if($d['rowsAffected'] == 0)
//     {
//         $attendance->create([
//             'empid' => $r['personId'],
//             'name' => $r['name'],
//             'date' => $date,
//             'time_in' => $recs->GetTimeInWithinThisDate($date) != null ? $recs->GetTimeInWithinThisDate($date) : "00:00",
//             'time_out' => $recs->GetTimeOutWithinThisDate($date) != null ? $recs->GetTimeOutWithinThisDate($date) : "00:00",
//         ]);
//     }
// }

// echo $attendance->create([
//     'status' => ' ',
//     'empid' => '002',
//     'date' => date('Y-m-d', time()),
//     'time_in' => '09:00',
//     'time_out' => '18:00'
// ]);
// $date = date('Y-m-d', time());
// $emp_id = '013';
// $recs = NewRecords::NewFindRecords('12345', $emp_id, ip:'192.168.0.102:8090');
// $attendance->create([
//                 'empid' => $emp_id,
//                 'name' => 'sample',
//                 'date' => $date,
//                 'time_in' => $recs->GetTimeInWithinThisDate($date),
//                 'time_out' => $recs->GetTimeOutWithinThisDate($date),
//             ]);



// $date = date('Y-m-d', time());
// echo $recs->GetTimeInWithinThisDate($date);

// require_once './Databases/Database.php';
// $db = new Database();
// $conn = $db->getConnection();
// $id = '1';

// // $sql = "SELECT * FROM attendance_tb WHERE `id`= :id";
// $sql = "SELECT * FROM attendance_tb";
// // $sql = "SELECT * FROM employee_tb INNER JOIN classification_tb ON employee_tb.classification = classification_tb.id";
// $stmt = $conn->prepare($sql);
// // $stmt->bindValue(':id', $id);
// $stmt->execute();

// $result = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($result);
