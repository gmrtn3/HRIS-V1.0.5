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

    // Insert the data into the piece_rate_tb table
    $sql = "INSERT INTO piece_rate_tb (unit_type, unit_quantity, unit_rate) VALUES ('$unitType', '$unitQuantity' ,'$unitRate')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../Piece_rate");
    } else {
        echo "Error inserting data: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
