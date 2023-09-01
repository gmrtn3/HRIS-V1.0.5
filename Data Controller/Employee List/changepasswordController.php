<?php
// changepassword.php

include '../../config.php';

// Step 2: Retrieve form data
$username = $_POST['username'];
$oldPassword = $_POST['password']; // Use $_POST['oldPassword'] for the old password
$newPassword = $_POST['newPassword']; // Use $_POST['newPassword'] for the new password
$confirmPassword = $_POST['cpassword'];

$hashOldPassword = mysqli_real_escape_string($conn, md5($oldPassword));

// $hashNewPassword = mysqli_real_escape_string($conn, md5($newPassword));


// Step 3: Validate form data
if (empty($username) || empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
    echo '<script type="text/javascript">';
    echo 'alert("Please fill in all fields.");';
    echo '</script>';
    header("Location: ../../empChangePassword.php");
    exit;
}




// Step 4: Connect to the database and query for employee data
// Replace DB_HOST, DB_USERNAME, DB_PASSWORD, and DB_NAME with your database credentials
$server = "localhost";
$user = "root";
$pass = "";
$database = "hris_db";

$connection = mysqli_connect($server, $user, $pass, $database);

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Step 4: Query for employee data with username
$query = "SELECT * FROM employee_tb WHERE username = '$username'";
$result = mysqli_query($connection, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo '<script type="text/javascript">';
    echo 'alert("Invalid Username.");';
    echo 'window.location.href = "../../empChangePassword.php";';
    echo '</script>';
    mysqli_close($connection);
    exit;
}

$employee = mysqli_fetch_assoc($result);
// $storedPassword = $employee['password'];

// Step 5: Compare old password
// $oldPasswordMatch = password_verify($oldPassword, $storedPassword);
include '../../config.php';
$sql = "SELECT * FROM employee_tb WHERE `username` = '$username' AND `password` = '$hashOldPassword'";
$result = mysqli_query($conn, $sql);


// $oldPasswordMatch = $row['password'];

if(mysqli_num_rows($result) >0){
    $row = mysqli_fetch_assoc($result);
    
    $oldiePassword = $row['password'];


    if ($oldiePassword !== $hashOldPassword ) {
        echo '<script type="text/javascript">';
        echo 'alert("Incorrect old password.");';
        // echo 'window.location.href = "../../empChangePassword.php";';
        echo '</script>';
        mysqli_close($connection);
        // exit;
    }
    
}


// Step 6: Check if new passwords match
if ($newPassword !== $confirmPassword) {
    echo '<script type="text/javascript">';
    echo 'alert("New password does not match.");';
    echo 'window.location.href = "../../empChangePassword.php";';
    echo '</script>';
    mysqli_close($connection);
    exit;
}

// Step 7: Update password in the database
// $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
$newPasswordHash = mysqli_real_escape_string($conn, md5($newPassword));
$updateQuery = "UPDATE employee_tb SET password = '$newPasswordHash' WHERE username = '$username'";

if (mysqli_query($connection, $updateQuery)) {
    mysqli_close($connection);
    // header("Location: ../../login.php");
    echo "success";
    exit;
} else {
    echo "Error updating password: " . mysqli_error($connection);
    mysqli_close($connection);
}
mysqli_close($connection);
?>