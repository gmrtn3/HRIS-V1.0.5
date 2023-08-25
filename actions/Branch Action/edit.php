<?php 
    include "../../config.php";
    

if(isset($_POST['update_data'])) {
    $id = $_POST['update_id'];
    $branch_name = $_POST['branch_name'];
    $branch_address = $_POST['branch_address'];
    $zip_code = $_POST['zip_code'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];


    $sql = "UPDATE `branch_tb` SET `branch_name`='$branch_name',`branch_address`='$branch_address',
    `zip_code`='$zip_code',`email`='$email',`telephone`='$telephone' WHERE id = $id";

    $result = mysqli_query($conn, $sql);

    if($result) {
        header("Location: ../../Branch.php?msg=Data updated successfully");
    }
    else {
        echo "Failed: " . mysqli_error($conn);
    }
}

?>