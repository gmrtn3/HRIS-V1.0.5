<?php
include 'config.php';
if (isset($_POST['btn_yes_modal'])) {
   $selected_TableID = $_POST["name_ID_tb"];
   $selected_empName = $_POST["name_empID_tb"];


   // Step 2: Retrieve blob data from database
        $sql = "SELECT `col_file` FROM `applyleave_tb` WHERE `col_ID` = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $selected_TableID); // $id is the ID of the blob data you want to retrieve
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $blobData);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Step 3: Display blob data in PHP
        header("Content-type: application/pdf"); // Set appropriate content type for PDF
        header("Content-Disposition: inline; filename=" . $selected_empName . '.pdf'); // Set the filename for the browser
        echo $blobData; // Output blob data to browser
}
?>