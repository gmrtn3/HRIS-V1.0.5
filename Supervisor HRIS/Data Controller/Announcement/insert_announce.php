<?php
include '../../config.php';

if(isset($_POST['add_announcement'])) {

    $title_announce = mysqli_real_escape_string($conn, $_POST['announce_title']);
    $announce_by = mysqli_real_escape_string($conn, $_POST['name_emp']);
    $date_announce = mysqli_real_escape_string($conn, $_POST['announce_date']);
    $desc_announce = mysqli_real_escape_string($conn, $_POST['announce_description']);

    if(isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
        $contents = file_get_contents($_FILES['file_upload']['tmp_name']);
        $escaped_contents = mysqli_real_escape_string($conn, $contents);
    } else {
        $escaped_contents = "";
    }

    $query = "INSERT INTO announcement_tb (`announce_title`, `empid`, `announce_date`, `description`, `file_attachment`)
              VALUES ('$title_announce', '$announce_by', '$date_announce', '$desc_announce', '$escaped_contents')";
    $query_run = mysqli_query($conn, $query);

    if($query_run) {
        header("Location: ../../Dashboard.php?msg=Successfully Added");
    } else {
        echo "Failed: " . mysqli_error($conn);
    }
}
?>
