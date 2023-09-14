<?php
include '../../config.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the file data based on the ID
    $query = "SELECT * FROM emp_file WHERE `id` = '$id'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $fileName = $row['name'];
        $fileContent = $row['content'];

        // Set the appropriate headers for file download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        // Output the file content
        echo $fileContent;
        exit;
    }
}
?>