<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hris_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(isset($_POST['add_holiday'])){

    $createBy = mysqli_real_escape_string($conn, $_POST['name_emp']);
    $holiday_title = mysqli_real_escape_string($conn, $_POST['title_holiday']);
    $holidayDate = mysqli_real_escape_string($conn, $_POST['date_holiday']);
    $typeofHoliday = mysqli_real_escape_string($conn, $_POST['type_holiday']);


    $query = "INSERT INTO holiday_tb (`empid`, `holiday_title`, `date_holiday`, `holiday_type`)
    VALUES ('$createBy', '$holiday_title', '$holidayDate', '$typeofHoliday')";

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