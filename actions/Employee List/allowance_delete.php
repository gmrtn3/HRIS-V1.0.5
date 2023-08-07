<?php
$conn = mysqli_connect("localhost", "root", "", "hris_db");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $empid = $_GET['empid']; // add this line to get the empid from the URL

    $results = mysqli_query($conn, "SELECT * FROM employee_tb WHERE empid = '$empid'");
    $rows = mysqli_fetch_assoc($results);

    $sql = "DELETE FROM `allowancededuct_tb` WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // If the delete was successful, redirect back to the edit form
        if ($results) {
            header("Location: ../../editempListForm.php?empid=$empid");
            exit;
        }
    } else {
        // If the delete failed, display an error message
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    // If no id was provided in the URL, display an error message
    echo "No id specified";
}
mysqli_close($conn);
?>