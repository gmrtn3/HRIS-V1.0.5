<?php

namespace BiometricsData;

class NewRecords
{

    private static $response;

    /**
     *
     * @param string $pass
     * @param string $id
     * @param string $startTime
     * @param string $endTime
     * @param string $ip
     **/
    public static function NewFindRecords($pass, $ip, $id = null, $startTime = null, $endtime = null)
    {
        $startTime = $startTime == null ? '0' : $startTime;
        $endtime = $endtime == null ? '0' : $endtime;
        $id = $id == null ? '-1' : $id;
        $endpoint = $ip . "/newFindRecords?pass=" . $pass . "&personID=" . $id . "&startTime=" . $startTime . "&endTime=" . $endtime . "";
        // echo $endpoint;

        $curl = curl_init($endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);
        $res = json_decode($data, true);

        if (isset($res['data'])) {
            self::$response = $res['data']['records'];
            // var_dump($res['data']);
        }
        return new self();
    }

    public function Test()
    {
        //    return self::$response;
        if (self::$response != null) {
            echo "<pre>";
            var_dump(self::$response);
        }
    }

    public function All()
    {
        return self::$response;
    }

    public function getTime()
    {
        if (self::$response != null) {
            foreach (self::$response as $r) {
                date_default_timezone_set('Asia/Manila');
                echo "<pre>";
                echo $r['name'] . " - ";
                echo $r['personId'] . " - ";
                echo $r['attendance']['attendanceStatus'] . " - ";
                echo $r['time'] . " - ";
                // echo date('Y-m-d H:i:s', $r['time']/1000);
                echo date('Y-m-d', intdiv($r['time'], 1000));
            }
        }
    }

    /**
     * @param string $date Y-m-d format (2023-08-01)
     */
    public function GetTimeOutWithinThisDate($date)
    {
        if (self::$response == null) return [];
        foreach (self::$response as $r) {
            $dateFormat = date('Y-m-d', intdiv($r['time'], 1000));
            if ($dateFormat == $date && $r['attendance']['attendanceStatus'] == 'Time Out') {
                return date('H:i', intdiv($r['time'], 1000));
            }
        }
    }

    public function GetTimeInWithinThisDate($date)
    {
        if (self::$response == null) return [];
        foreach (self::$response as $r) {
            $dateFormat = date('Y-m-d', intdiv($r['time'], 1000));
            if ($dateFormat == $date && $r['attendance']['attendanceStatus'] == 'Time In') {
                return date('H:i', intdiv($r['time'], 1000));
            }
        }
    }
}
