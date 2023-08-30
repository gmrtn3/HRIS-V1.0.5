<?php
include '../../config.php';
if (isset($_POST['yes_download'])) {
    $select_tableid = $_POST["table_id"];
    $select_tablename = $_POST["table_name"];

    // Step 2: Retrieve blob data from database
    $sql = "SELECT `file_attachment` FROM `announcement_tb` WHERE `id` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $select_tableid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $blobData);
        if (mysqli_stmt_fetch($stmt)) {
            mysqli_stmt_close($stmt);

            // Step 3: Display blob data in PHP
            header("Content-type: application/pdf");
            header("Content-Disposition: inline; filename=" . $select_tablename . '.pdf');
            header("Content-Length: " . strlen($blobData)); // Set Content-Length header
            echo $blobData;
            exit; // Make sure to exit after sending the file
        } else {
            // Handle fetch failure
            echo "Error fetching blob data.";
        }
    } else {
        // Handle prepare failure
        echo "Error preparing statement.";
    }
}
?>
