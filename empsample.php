<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $empid = mysqli_real_escape_string($conn, $_POST['empid']);

    // Insert the new employee ID into the sampole table
    $insertQuery = "INSERT INTO sampole (empid) VALUES ('$empid')";
    
    if (mysqli_query($conn, $insertQuery)) {
        echo "Employee ID inserted successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

$sql = "SELECT empid FROM sampole ORDER BY empid DESC LIMIT 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$empid = '';

if ($row) {
    $lastEmpID = $row['empid'];

    // Calculate the next employee ID
    $nextEmpID = (int)$lastEmpID + 1;

    if ($nextEmpID < 10) {
        $nextEmpIDFormatted = sprintf("%02d", $nextEmpID); // Format for 01-09
    } else if ($nextEmpID < 100) {
        $nextEmpIDFormatted = (string)$nextEmpID; // No leading zeros for 10-99
    } else if ($nextEmpID < 10000) {
        $nextEmpIDFormatted = (string)$nextEmpID; // No leading zeros for 100-9999
    } else {
        $nextEmpIDFormatted = $nextEmpID; // No leading zeros for IDs in the 5 digits range and beyond
    }
} else {
    // No existing employee IDs, start from '00'
    $nextEmpIDFormatted = '00';
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="POST">
        <input type="text" name="empid" id="form-empid" class="p-1 form-control" placeholder="Employee ID" required maxlength="6" style="width: 73%" value="<?php echo $nextEmpIDFormatted; ?>" readonly>
        <span class="modalSave"> <input class="submit" type="submit" name="update" value
