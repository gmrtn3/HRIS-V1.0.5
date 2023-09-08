<?php
require_once '../Databases/Database.php';
require_once '../Models/TimeIn.php';
require_once '../Models/TimeOut.php';

// require_once '../Models/NewAttendance.php';
require_once 'NewRecordsHandler.php';

// use Db\CustomQuery;
use BiometricsData\NewRecords;
// use Db\DatabaseShit;

function letsGo()
{
    date_default_timezone_set('Asia/Manila');

    $records = NewRecords::NewFindRecords('12345', ip: 'http://192.168.0.143:8090');
    $db = new DatabaseShit();

    $timeIn = new TimeIn();
    $timeOut = new TimeOut();

    $all = $records->All();
    // var_dump($all);

    $isSuccess = false;

    if (empty($all) || $all == null) {
        // echo "Biometrics is not connected/currently offline. Please connect.";
        $isSuccess = false;
        $data = array(
            'message' => "Biometrics is not connected or currently offline. Please connect.",
            'isSuccess' => $isSuccess,
        );
        return json_encode($data);
    }


    foreach ($all as $r) {
        $date = date('Y-m-d', intdiv($r['time'], 1000));
        $recs = NewRecords::NewFindRecords('12345', $r['personId'], null, null, '192.168.0.143:8090');
        $time_in = $recs->GetTimeInWithinThisDate($date);
        $time_out = $recs->GetTimeOutWithinThisDate($date);

        if ($r['attendance']['attendanceStatus'] == 'Time In') {

            //Insert time in
            if ($r['personId'] != 'STRANGERBABY') {
                $sql = "SELECT * FROM attendance_time_in
                WHERE time_in_personId = :personId
                AND date_time_in = :timeInDate";

                $q = $db->getConnection()->prepare($sql);
                $q->execute([
                    'personId' => $r['personId'],
                    'timeInDate' => $date,
                ]);

                $rows = $q->fetchAll(PDO::FETCH_ASSOC);
                $counts = $q->rowCount();

                if ($counts <= 0) {
                    $timeIn->create([
                        'time_in_personId' => $r['personId'],
                        'date_time_in' => $date,
                        'time_in' => $time_in
                    ]);
                }
            }
        }
        if ($r['attendance']['attendanceStatus'] == 'Time Out') {

            //Insert Time Out
            if ($r['personId'] != 'STRANGERBABY') {
                $sql = "SELECT * FROM attendance_time_out
                WHERE time_out_personId = :personId
                AND date_time_out = :timeOutDate";

                $q = $db->getConnection()->prepare($sql);
                $q->execute([
                    'personId' => $r['personId'],
                    'timeOutDate' => $date,
                ]);

                $rows = $q->fetchAll(PDO::FETCH_ASSOC);
                $counts = $q->rowCount();

                if ($counts <= 0) {
                    $timeOut->create([
                        'time_out_personId' => $r['personId'],
                        'date_time_out' => $date,
                        'time_out' => $time_out
                    ]);
                }
            }
        }
    }

    $isSuccess = true;
    $data = array(
        'message' => "Biometrics data fetched successfully",
        'isSuccess' => $isSuccess,
    );
    return json_encode($data);
}


echo "<pre>";
// echo json_decode(letsGo());
echo letsGo();
?>

<script>
    // Reload the page every 10 seconds
    setInterval(function(){
        location.reload();
        console.log("add to db has reloaded");

        // Send a message to sample.php using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../../sample.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Handle the response from sample.php if needed
                console.log("Message sent to sample.php");
            }
        };
        xhr.send("message=It%20is%20reloaded");
    }, 10000); // 10,000 milliseconds = 10 seconds
</script>

