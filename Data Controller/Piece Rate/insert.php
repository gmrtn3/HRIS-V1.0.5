<?php
$server = "localhost";
$user = "root";
$pass = "";
$database = "hris_db";

$conn = mysqli_connect($server, $user, $pass, $database);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $unitType = $_POST["unit_type"];
    $unitQuantity = $_POST["unit_quantity"];
    $unitRate = $_POST["unit_rate"];

    // Check if the data for this unit type already exists in the piece_rate_tb table
    $checkSql = "SELECT * FROM piece_rate_tb WHERE unit_type = '$unitType'";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        // If the unit type already exists, update the existing record
        $updateSql = "UPDATE piece_rate_tb SET unit_quantity = '$unitQuantity', unit_rate = '$unitRate' WHERE unit_type = '$unitType'";
        
        if (mysqli_query($conn, $updateSql)) {
            header("Location: ../../Piece_rate");
        } else {
            echo "Error updating data: " . mysqli_error($conn);
        }
    } else {
        // If the unit type doesn't exist, insert a new record
        $insertSql = "INSERT INTO piece_rate_tb (unit_type, unit_quantity, unit_rate) VALUES ('$unitType', '$unitQuantity', '$unitRate')";
        
        if (mysqli_query($conn, $insertSql)) {
            header("Location: ../../Piece_rate");
        } else {
            echo "Error inserting data: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>
