<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hris_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(isset($_POST['add_event'])){

    $createdBy = mysqli_real_escape_string($conn, $_POST['name_emp']);
    $titleEvent = mysqli_real_escape_string($conn, $_POST['event_title']);
    $eventDate = mysqli_real_escape_string($conn, $_POST['event_date']);
    $typeEvent = mysqli_real_escape_string($conn, $_POST['event_type']);


    $query = "INSERT INTO event_tb (`empid`, `event_title`, `date_event`, `event_type`)
    VALUES ('$createdBy', '$titleEvent', '$eventDate', '$typeEvent')";

    $query_run = mysqli_query($conn, $query);

    if($query_run)
    {
        header("Location: ../../Dashboard.php?msg=Successfully Added");
    }
    else
    {
        echo "Failed: " . mysqli_error($conn);
    }
}
?>