<?php
    if(isset($_POST['delete'])) {
        // API endpoint URL
        $endpoint = 'http://192.168.0.143:8090/person/delete';

        // Parameters for the request
        $pass = '12345';
        $id = '-1'; // Retrieve the ID from the input field

        // Create the request body
        $data = [
            'pass' => $pass,
            'id' => $id
        ];

        // Encode the request body as JSON
        $json = json_encode($data);

        // Set the headers for the request
        $headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json)
        ];

        // Send POST request
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);

            if (curl_errno($ch)) {
        echo "Error: " . curl_error($ch);
    } else {
        // Process the API response (you may want to handle success/error messages accordingly)
        $decodedResponse = json_decode($response, true);
        if ($decodedResponse["success"]) {
            echo "<script>alert('Delete All Employee Successfully!');  window.history.go(-1);</script>";
        } else {
            echo "<script>alert('" . $decodedResponse["msg"] . "'); window.history.go(-1);</script>";
        }
    }
        // Close cURL session
        curl_close($ch);
    }
    ?>
