<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <iframe src="../../uploads/646b231f91891.pdf" width="100%" height="800px"></iframe>
</body>
</html> -->
<?php
include '../../config.php';
$payslip_ID = $_GET['id'];
$result_payslip = mysqli_query($conn, "SELECT * FROM 
                                            `payslip_tb`
                                            WHERE `col_ID`=  '$payslip_ID'");
 $row_payslip= mysqli_fetch_assoc($result_payslip);
 echo $row_payslip['col_Payslip_pdf'];
// Path to the PDF file
$filepath = '../../' . $row_payslip['col_Payslip_pdf'];

// Check if the file exists
if (file_exists($filepath)) {
    // Set the appropriate headers
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($filepath) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');

    // Read the file and output it to the browser
    readfile($filepath);
} else {
    echo 'File not found.';
}

// $sql = "SELECT `col_Payslip_pdf` FROM `payslip_tb` WHERE `col_ID` = ?";
// $stmt = mysqli_prepare($conn, $sql);
// mysqli_stmt_bind_param($stmt, "i", $payslip_ID);
// mysqli_stmt_execute($stmt);
// mysqli_stmt_bind_result($stmt, $blobData);
// mysqli_stmt_fetch($stmt);
// mysqli_stmt_close($stmt);

// // Check if blob data was retrieved successfully
// if ($blobData) {
//     // Step 3: Display blob data in PHP
//     header("Content-type: application/pdf");
//     header("Content-Disposition: inline; filename=" . $payslip_ID . '.pdf');
//     echo $blobData;
// } else {
//     echo "Error: Unable to retrieve blob data from the database.";
// }
 // Retrieve the PDF file from the database based on its filename or unique identifier
 
 
//  if (isset($_GET['id'])) {
//      $id = $_GET['id'];
 
//      // Query to fetch the PDF file from the database
//      $query = "SELECT col_Payslip_pdf FROM payslip_tb WHERE col_ID = '$id'";
//      $result = mysqli_query($conn, $query);
 
//      if ($result && mysqli_num_rows($result) > 0) {
//          $row = mysqli_fetch_assoc($result);
//          $fileContent = $row['col_Payslip_pdf'];
 
//          // Set the appropriate headers to indicate that the response should be treated as a PDF file
//          header('Content-type: application/pdf');
//          header('Content-Disposition: inline; filename="payslip.pdf"');
//          header('Content-Length: ' . strlen($fileContent));
 
//          // Output the content of the PDF file to the browser
//          echo $fileContent;
//          exit;
//      } else {
//          echo "Error retrieving the PDF file from the database.";
//      }
//  }
 


//         include '../../config.php';
// $payslip_ID = $_GET['id'];

// // Step 2: Retrieve blob data from the database
// $sql = "SELECT `col_Payslip_pdf` FROM `payslip_tb` WHERE `col_ID` = ?";
// $stmt = mysqli_prepare($conn, $sql);
// mysqli_stmt_bind_param($stmt, "i", $payslip_ID); // $id is the ID of the blob data you want to retrieve
// mysqli_stmt_execute($stmt);
// $result = mysqli_stmt_get_result($stmt);
// $row = mysqli_fetch_assoc($result);
// $blobData = $row['col_Payslip_pdf'];
// mysqli_stmt_close($stmt);

// // Step 3: Display blob data in PHP
// header("Content-type: application/pdf"); // Set appropriate content type for PDF
// header("Content-Disposition: inline; filename=" . $payslip_ID . '.pdf'); // Set the filename for the browser
// echo $blobData; // Output blob data to the browser
?>


