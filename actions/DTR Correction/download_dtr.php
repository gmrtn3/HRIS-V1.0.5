<?php
include '../../config.php';
if (isset($_POST['yes_dl'])) {
   $select_tableid = $_POST["table_id"];
   $select_tablename = $_POST["table_name"];


   // Step 2: Retrieve blob data from database
        $sql = "SELECT `file_attach` FROM `emp_dtr_tb` WHERE `id` = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $select_tableid); // $id is the ID of the blob data you want to retrieve
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $blobData);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Step 3: Display blob data in PHP
        header("Content-type: application/pdf"); // Set appropriate content type for PDF
        header("Content-Disposition: inline; filename=" . $select_tablename . '.pdf'); // Set the filename for the browser
        echo $blobData; // Output blob data to browser
}
?>