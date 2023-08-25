<?php 
include '../../config.php';

if(isset($_POST['update_company']))
{
    $CompanyPhoto = $_FILES['photo']['tmp_name'] ? addslashes(file_get_contents($_FILES['photo']['tmp_name'])) : '';
    $CompanyName = $_POST['company_name'];
    $CompanyAddress = $_POST['company_address'];
    $CompanyZipcode = $_POST['company_zipcode'];
    $CompanyCode = $_POST['company_code'];

    $query = "SELECT * FROM settings_company_tb";
    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) > 0){
        $row = mysqli_fetch_assoc($query_run);
        $id = $row['id'];

        // Check if user uploaded a photo
        if (!empty($CompanyPhoto)) {
            $update_query = "UPDATE settings_company_tb SET `cmpny_logo` = '$CompanyPhoto', `cmpny_name` = '$CompanyName', `cmpny_address` = '$CompanyAddress', `cmpny_zipcode` = '$CompanyZipcode' , `cmpny_code` = '$CompanyCode' WHERE id = '$id'";
            $update_run = mysqli_query($conn, $update_query);

            if($update_run){
                echo '<script>alert("You have successfully updated the Company Settings"); window.location.href="../../settings";</script>';
                exit;
            } else {
                echo "Failed: " . mysqli_error($conn);
            }
        } else {
            // User did not upload a photo, update other columns only
            $update_query = "UPDATE settings_company_tb SET `cmpny_name` = '$CompanyName', `cmpny_address` = '$CompanyAddress', `cmpny_zipcode` = '$CompanyZipcode' , `cmpny_code` = '$CompanyCode' WHERE id = '$id'";
            $update_run = mysqli_query($conn, $update_query);

            if($update_run){
                echo '<script>alert("You have successfully updated the Company Settings"); window.location.href="../../settings";</script>';
                exit;
            } else {
                echo "Failed: " . mysqli_error($conn);
            }
        }
    } else {
        $query = "INSERT INTO settings_company_tb (`cmpny_logo`, `cmpny_name`, `cmpny_address`, `cmpny_zipcode`, `cmpny_code`) 
        VALUES ('$CompanyPhoto', '$CompanyName', '$CompanyAddress', '$CompanyZipcode', '$CompanyCode' )";
        $query_run = mysqli_query($conn, $query);    

        if($query_run){
            echo '<script>alert("You have successfully inserted the Company Settings"); window.location.href="../../settings";</script>';
            exit;
        } else {
            echo "Failed: " . mysqli_error($conn);
        }
    }
}

?>