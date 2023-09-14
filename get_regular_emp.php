<?php
include 'config.php';

if (isset($_POST['empid'])) {
    $empids = $_POST['empid'];

    // Convert the received empids into an array if it's not already
    if (!is_array($empids)) {
        $empids = array($empids);
    }

    // Initialize an array to store the results
    $results = array();

    foreach ($empids as $empid) {
        // Sanitize and use prepared statements to prevent SQL injection
        $empid = mysqli_real_escape_string($conn, $empid);

        $sql = "SELECT * FROM `employee_tb` WHERE `classification` = '1' AND `empid` = '$empid'";

        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $classification = $row['classification'];
            $results[] = $classification;
        }
    }

    echo $results;
}
?>
