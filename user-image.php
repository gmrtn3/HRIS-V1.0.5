<?php $userId = $_SESSION['id'];

$result = mysqli_query($conn, "SELECT id, emp_img_url FROM employee_tb WHERE id = '$userId'");
$row = mysqli_fetch_assoc($result);

if ($row) {
    $image_url = $row['emp_img_url'];
} else {
    // Handle the case when the user ID is not found in the database
    $image_url = 'img/user.jpg'; // Set a default image or handle the situation accordingly
}

?>