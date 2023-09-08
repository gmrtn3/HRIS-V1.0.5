<?php
    if(isset($_POST['delete'])) {
        // API endpoint URL
        $endpoint = 'http://192.168.0.143:8090/newDeleteRecords';

        // Parameters for the request
        $pass = '12345';
        $personId = '-1';
        $startTime = '2021-07-18 01:00:00';
        $endTime = '2024-07-18 01:00:00';
        $model = -1;

        // Create the request body
        $data = [
            'pass' => $pass,
            'personId' => $personId,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'model'  => $model
        ];

        // Encode the request body as URL-encoded format
        $json = http_build_query($data);

        // Set the headers for the request
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($json)
        ];

        // Send POST request
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);






    // Check for cURL errors
        if (curl_errno($ch)) {
        echo "Error: " . curl_error($ch);
    } else {
        // Process the API response (you may want to handle success/error messages accordingly)
        $decodedResponse = json_decode($response, true);
        if ($decodedResponse["success"]) {
            echo "<script>alert('Delete All Records Successfully!');  window.history.go(-1);</script>";
        } else {
            echo "<script>alert('" . $decodedResponse["msg"] . "'); window.history.go(-1);</script>";
        }
    }
        // Close cURL session
        curl_close($ch);
    }
    ?>
