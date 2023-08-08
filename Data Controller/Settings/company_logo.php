<?php 
include '../../config.php';
session_start();

// ... (your existing PHP code for processing the form data) ...

// Handle the checkbox value and store it in a session variable

  if (isset($_POST['update_company'])) {
    // $salary_settings = '';
    // if(isset($_POST['radio_fixed'])){

    //     $salary_settings = 'Fixed Salary';

    // }else{
    //     $salary_settings = 'Days Worked';
    // }
    $salary_settings = $conn->real_escape_string($_POST["btnradio"]);
    $CompanyPhoto = $_FILES['photo']['tmp_name'] ? addslashes(file_get_contents($_FILES['photo']['tmp_name'])) : '';
    $CompanyName = $_POST['company_name'];
    $CompanyAddress = $_POST['company_address'];
    $CompanyZipcode = $_POST['company_zipcode'];
   
    @$piece_rate_toggle = $_POST['piece_rate_toggle'];

    $query = "SELECT * FROM settings_company_tb";
    $query_run = mysqli_query($conn, $query);

    if (mysqli_num_rows($query_run) > 0){
        $row = mysqli_fetch_assoc($query_run);
        $id = $row['id'];

        // Check if user uploaded a photo
        if (!empty($CompanyPhoto)) {
            $update_query = "UPDATE settings_company_tb SET `cmpny_logo` = '$CompanyPhoto', `cmpny_address` = '$CompanyAddress', `cmpny_zipcode` = '$CompanyZipcode' , `cmpny_code` = '$CompanyCode', `col_salary_settings` = '$salary_settings', `piece_rate_toggle` = '$piece_rate_toggle' WHERE id = '$id'";
            $update_run = mysqli_query($conn, $update_query);

            if($update_run){
                echo '<script>alert("You have successfully updated the Company Settings"); window.location.href="../../settings";</script>';
                exit;
            } else {
                echo "Failed: " . mysqli_error($conn);
            }
        } else {
            // User did not upload a photo, update other columns only
            $update_query = "UPDATE settings_company_tb SET `cmpny_name` = '$CompanyName', `cmpny_address` = '$CompanyAddress', `cmpny_zipcode` = '$CompanyZipcode' , `piece_rate_toggle` = '$piece_rate_toggle' WHERE id = '$id'";
            $update_run = mysqli_query($conn, $update_query);

            if($update_run){
                echo '<script>alert("You have successfully updated the Company Settings"); window.location.href="../../settings";</script>';
                exit;
            } else {
                echo "Failed: " . mysqli_error($conn);
            }
        }
    } else {
        $query = "INSERT INTO settings_company_tb (`cmpny_logo`, `cmpny_name`, `cmpny_address`, `cmpny_zipcode`,  `col_salary_settings`, `piece_rate_toggle`) 
        VALUES ('$CompanyPhoto', '$CompanyName', '$CompanyAddress', '$CompanyZipcode',  '$salary_settings', 'piece_rate_toggle')";
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