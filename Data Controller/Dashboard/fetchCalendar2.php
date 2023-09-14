<?php
// Start a session (if not already started)
session_start();

include '../../config.php';

// Set the batch size
$batch_size = 20;

// Check if a session variable exists to track the offset
if (!isset($_SESSION['offset'])) {
    $_SESSION['offset'] = 0;
}

// Get the current offset from the session
$offset = $_SESSION['offset'];

// Get the IDs of processed records from the 'processed_records' table
$processed_ids = array();
$sql_processed = "SELECT `record_id` FROM `processed_records`";
$result_processed = $conn->query($sql_processed);

if ($result_processed->num_rows > 0) {
    while ($row = $result_processed->fetch_assoc()) {
        $processed_ids[] = $row['record_id'];
    }
}

// SQL query to select a batch of data from 'holiday_tb' that haven't been processed yet
if (!empty($processed_ids)) {
    $sql_select = "SELECT `id`, `holiday_title`, `date_holiday`, `holiday_type` 
                   FROM `holiday_tb` 
                   WHERE `id` NOT IN (" . implode(',', $processed_ids) . ")
                   LIMIT $batch_size";
} else {
    // If there are no processed records, select the first $batch_size records
    $sql_select = "SELECT `id`, `holiday_title`, `date_holiday`, `holiday_type` 
                   FROM `holiday_tb` 
                   LIMIT $batch_size";
}

// Execute the SELECT query
$result = $conn->query($sql_select);

// Check if there are rows to transfer
if ($result->num_rows > 0) {
    // Prepare an INSERT statement with placeholders
    $sql_insert = "INSERT INTO `schedule_list` (`title`, `description`, `start_datetime`, `end_datetime`) 
                   VALUES (?, ?, ?, ?)";
    
    // Prepare the INSERT statement
    $stmt = $conn->prepare($sql_insert);

    if ($stmt === FALSE) {
        die("Error preparing the statement: " . $conn->error);
    }

    // Bind parameters to the INSERT statement
    $stmt->bind_param("ssss", $title, $description, $start_datetime, $end_datetime);

    // Loop through each row in the result set
    while ($row = $result->fetch_assoc()) {
        // Prepare data for insertion into 'schedule_list'
        $title = $row['holiday_title'];
        $description = $row['holiday_type'];
        $start_datetime = $row['date_holiday'];
        $end_datetime = $row['date_holiday'];

        // Execute the INSERT query
        if ($stmt->execute() === TRUE) {
            // Mark the record as processed by adding its ID to 'processed_records'
            $record_id = $row['id'];
            $sql_mark_processed = "INSERT INTO `processed_records` (`record_id`) VALUES ($record_id)";
            $conn->query($sql_mark_processed);
            
            // echo "Data inserted successfully!";
            // header("Location: Dashboard.php" );
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    // Close the statement
    $stmt->close();
    
    // Update the offset for the next batch
    $offset += $batch_size;
    $_SESSION['offset'] = $offset;
} else {
    // No more data to transfer
    // echo "No more data to transfer from holiday_tb.";
    // header("Location: Dashboard.php" );
}

// Close the database connection
$conn->close();
?>
