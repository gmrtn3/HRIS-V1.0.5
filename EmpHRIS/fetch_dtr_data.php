
<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $empid = $_POST['empid'];
  $date = $_POST['date'];

  $dtrQuery = "SELECT * FROM emp_dtr_tb WHERE `empid` = '$empid' AND `date` = '$date'";
  $DtrRun = mysqli_query($conn, $dtrQuery);

  if (mysqli_num_rows($DtrRun) > 0) {
    $dtrRow = mysqli_fetch_assoc($DtrRun);

    echo json_encode($dtrRow);
  }
}
?>