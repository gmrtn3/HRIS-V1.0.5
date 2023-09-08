<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Set the API endpoint
    $apiEndpoint = "http://192.168.0.143:8090/setPassWord";

    // Retrieve old and new passwords from the form
    $oldPassword = $_POST["oldPassword"];
    $newPassword = $_POST["newPassword"];

    // Check if the required fields are not empty
    if (empty($oldPassword) || empty($newPassword)) {
        echo "Please fill in both old and new passwords.";
        exit;
    }

    // Prepare the data to be sent in the POST request
    $postData = array(
        "oldPass" => $oldPassword,
        "newPass" => $newPassword
    );

    // Initialize cURL session
    $curl = curl_init($apiEndpoint);

    // Set cURL options
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));

    // Execute cURL request and get the response
    $response = curl_exec($curl);

    // Check for cURL errors
         if (curl_errno($curl)) {
        echo "Error: " . curl_error($curl);
    } else {
        // Process the API response (you may want to handle success/error messages accordingly)
        $decodedResponse = json_decode($response, true);
        if ($decodedResponse["success"]) {
            echo "<script>alert('Password changed successfully!');  window.history.go(-1);</script>";
        } else {
            echo "<script>alert('" . $decodedResponse["msg"] . "'); window.history.go(-1);</script>";
        }
    }
        // Close cURL session
        curl_close($curl);
    }



?>
