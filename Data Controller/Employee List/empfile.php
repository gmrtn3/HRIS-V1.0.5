<?php
include '../../config.php';
if (isset($_POST['btn_save'])) {
    $employeeId = $_POST['empoyeeId'];
    foreach ($_FILES['multipleFile']['name'] as $key => $name) {
        $tmpName = $_FILES['multipleFile']['tmp_name'][$key];
        $fileType = $_FILES['multipleFile']['type'][$key];
  
        // Read the file content
        $content = file_get_contents($tmpName);
  
        // Store the file content in the database
        $stmt = $conn->prepare("INSERT INTO emp_File (empid, name, type, content) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $employeeId, $name, $fileType, $content);
        $stmt->execute();
        $stmt->close();
    }
  
    // Close the database connection
    $conn->close();
    header("Location: ../../EmployeeList.php?Success Add");
    exit();
  }


?>