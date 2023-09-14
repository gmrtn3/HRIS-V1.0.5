<?php
include '../../config.php';


// if (isset($_POST['empId'])) {
//     $empId = $_POST['empId'];

//     // Create a function to determine file type based on content
//     function getFileType($content)
//     {
//         $finfo = finfo_open(FILEINFO_MIME_TYPE);
//         $mime = finfo_buffer($finfo, $content);
//         finfo_close($finfo);

//         return $mime;
//     }

//     echo '<div class="table-responsive" id="table-responsiveness">
//             <table class="table">
//                 <thead style="background-color: #D8D8F5;">
//                     <tr>
//                         <th style="display: none;">Employee ID</th>
//                         <th>File Name</th>
//                         <th style="display: none;">Type</th>
//                         <th>Download</th>
//                     </tr>
//                 </thead>';

//     $query = "SELECT * FROM emp_file WHERE `empid` = '$empId'";
//     $result = mysqli_query($conn, $query);

//     while ($row = mysqli_fetch_assoc($result)) {
//         echo '<tr>';
//         echo '<td style="font-weight: 400; background-color: white; display:none">' . $row['empid'] . '</td>';
//         echo '<td style="font-weight: 400; background-color: white;">' . $row['name'] . '</td>';

//         // Determine the file type
//         $fileType = getFileType($row['content']);
//         echo '<td style="font-weight: 400; background-color: white; display: none;">' . $fileType . '</td>';

//         // Create a download link
//         echo '<td style="font-weight: 400; background-color: white;">
//                 <a href="actions/Employee List/download_file.php?id=' . $row['id'] . '">Download</a>
//               </td>';
//         echo '</tr>';
//     }
//     echo '</table>';
//     echo '</div>';
// }

if (isset($_POST['empId'])) {
    $empId = $_POST['empId'];

    // Create a function to determine file type based on content
    function getFileType($content)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_buffer($finfo, $content);
        finfo_close($finfo);

        return $mime;
    }

    echo '<div class="table-responsive" id="table-responsiveness">
            <table class="table">
                <thead style="background-color: #D8D8F5;">
                    <tr>
                        <th>File Name</th>
                        <th>Download</th>
                    </tr>
                </thead>';

    // Use prepared statements to prevent SQL injection
    $query = "SELECT * FROM emp_file WHERE empid = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $empId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td style="font-weight: 400; background-color: white;">' . $row['name'] . '</td>';

        // Determine the file type
        $fileType = getFileType($row['content']);
        $downloadText = '';

        // Set the download text based on file type
        switch ($fileType) {
            case 'application/pdf':
                $downloadText = 'Download PDF';
                break;
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                $downloadText = 'Download XLSX';
                break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                $downloadText = 'Download DOCX';
                break;
            case 'image/png':
                $downloadText = 'Download PNG';
                break;
            case 'image/jpeg':
                $downloadText = 'Download JPG';
                break;
            default:
                $downloadText = 'Download';
                break;
        }

        // Create a download link
        echo '<td style="font-weight: 400; background-color: white;">
                <a href="actions/Employee List/download_file.php?id=' . $row['id'] . '">' . $downloadText . '</a>
              </td>';
        echo '</tr>';
    }
    echo '</table>';
    echo '</div>';
}
?>