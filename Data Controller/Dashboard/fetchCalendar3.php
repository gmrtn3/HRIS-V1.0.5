<?php
include '../../config.php';

$sql = "SELECT * FROM holiday_tb";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $holiday_array = array();
    while ($row = $result->fetch_assoc()) {
        $holiday_title = $row['holiday_title'];
        $date = $row['date_holiday'];
        $type = $row['holiday_type'];

        $holiday_array[] = array('title' => $holiday_title, 'date' => $date, 'type' => $type);
    }

    // Prepare the INSERT statement
    $insertSql = "INSERT INTO `schedule_list` (`title`, `description`, `start_datetime`, `end_datetime`) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insertSql);

    if ($stmt) {
        foreach ($holiday_array as $hol_array) {
            $title = $hol_array['title'];
            $date = $hol_array['date'];
            $type = $hol_array['type'];

            // Bind parameters and execute the statement
            mysqli_stmt_bind_param($stmt, "ssss", $title, $type, $date, $date);
            $query_run = mysqli_stmt_execute($stmt);

            if (!$query_run) {
                // Handle the error if the INSERT fails
                echo "Error inserting data: " . mysqli_error($conn);
            }
        }
        mysqli_stmt_close($stmt);
    } else {
        // Handle the error if the prepared statement fails
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}
?>
