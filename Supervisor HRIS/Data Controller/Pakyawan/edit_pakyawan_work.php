<?php
$conn = mysqli_connect("localhost", "root", "", "hris_db");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["empid"]) && isset($_POST["piece_rate_id"])) {
  $empid = $_POST["empid"];
  $selectedOptions = $_POST["piece_rate_id"];

  // Check if the employee work details already exist
  $existingDataQuery = "SELECT * FROM employee_pakyawan_work_tb WHERE empid = '$empid'";
  $existingDataResult = mysqli_query($conn, $existingDataQuery);

  if (mysqli_num_rows($existingDataResult) > 0) {
    // Employee work details already exist, perform update
    $existingData = mysqli_fetch_assoc($existingDataResult);
    $existingPieceRateIds = $existingData["piece_rate_id"];

    // Convert selectedOptions to an array if it's not already
    if (!is_array($selectedOptions)) {
      $selectedOptions = [$selectedOptions];
    }

    $updatedPieceRateIds = implode(",", $selectedOptions);

    if ($existingPieceRateIds === $updatedPieceRateIds) {
      // No changes in piece_rate_id values
      echo "No changes made to pakyawan work details.";
    } else {
      // Update piece_rate_id values if it's not empty
      if (!empty($updatedPieceRateIds)) {
        $updateQuery = "UPDATE employee_pakyawan_work_tb SET piece_rate_id = '$updatedPieceRateIds' WHERE empid = '$empid'";
        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
          // Update successful
          echo "update_success";
        } else {
          // Update failed
          echo "Error updating pakyawan work details: " . mysqli_error($conn);
        }
      }
    }
  } else {
    // Employee work details don't exist, perform insert
    // Convert selectedOptions to an array if it's not already
    if (!is_array($selectedOptions)) {
      $selectedOptions = [$selectedOptions];
    }

    $insertPieceRateIds = implode(",", $selectedOptions);
    $insertQuery = "INSERT INTO employee_pakyawan_work_tb (empid, piece_rate_id) VALUES ('$empid', '$insertPieceRateIds')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
      // Insert successful
      echo "insert_success";
    } else {
      // Insert failed
      echo "Error inserting pakyawan work details: " . mysqli_error($conn);
    }
  }
} else {
  // Invalid request or missing parameters
  echo "Invalid request or missing parameters.";
}
?>
