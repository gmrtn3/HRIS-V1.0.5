<?php
$conn = mysqli_connect("localhost", "root", "", "hris_db");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["empid"])) {
  $empid = $_POST["empid"];
  
  // echo "<script> console.log($empid) </script>";

  $query = "SELECT piece_rate_tb.unit_type, piece_rate_tb.unit_quantity, piece_rate_tb.unit_rate
            FROM piece_rate_tb
            INNER JOIN employee_pakyawan_work_tb ON piece_rate_tb.id = employee_pakyawan_work_tb.piece_rate_id
            WHERE employee_pakyawan_work_tb.empid = '$empid' ";

  $result = $conn->query($query);

  if ($result->num_rows > 0) {
    $unitDetails = array();
    while ($row = $result->fetch_assoc()) {
      $unitDetails[] = $row;
    }
    echo json_encode($unitDetails);
  } else {
    echo "No unit details found for the given empid.";
  }
}
?>
