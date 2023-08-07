<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the empid from the form
    $empid = $_POST['empid'];

    // Get the piece_rate_id values from the form
    $pieceRateIds = $_POST['piece_rate_id'];

    $status = $_POST['status'];

    // Connect to the database
    include '../../config.php';

    // Delete the existing records for the given empid to handle the update case
    $deleteQuery = "DELETE FROM employee_pakyawan_work_tb WHERE empid = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("s", $empid);
    $stmt->execute();
    $stmt->close();

    // Insert the new records with the given empid and piece_rate_id values
    $insertQuery = "INSERT INTO employee_pakyawan_work_tb (empid, piece_rate_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);

    foreach ($pieceRateIds as $pieceRateId) {
        $stmt->bind_param("ss", $empid, $pieceRateId);
        $stmt->execute();
    }

    $stmt->close();
    
    $updateQuery = "UPDATE employee_tb SET status = ? WHERE empid = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ss", $status, $empid);
    $stmt->execute();
    $stmt->close();

    echo "<script> alert('Data Inserted Successfully')</script>";
    echo "<script>window.location.href = '../../pakyawanEmpList';</script>";
    exit;
}
?>
