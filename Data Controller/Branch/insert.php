<?php 
    include "../../config.php";

    if(isset($_POST['add_data'])) {
        $branch_name = $_POST['branch_name'];
        $branch_address = $_POST['branch_address'];
        $zip_code = $_POST['zip_code'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];

        $sql = "SELECT * FROM `branch_tb` WHERE `branch_name`='$branch_name'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0) {
            // Duplicate branch name found
            header("Location: ../../Branch.php?error=Branch name already exists");
        }
        else {
            // No duplicate branch name found, insert the data
            $sql = "INSERT INTO `branch_tb`(`id`, `branch_name`, `branch_address`, `zip_code`, `email`, `telephone`) 
            VALUES (NULL,'$branch_name','$branch_address','$zip_code','$email','$telephone')";
            $result = mysqli_query($conn, $sql);

            if($result) {
                header("Location: ../../Branch.php?msg=New record created successfully");
            }
            else {
                echo "Failed: " . mysqli_error($conn);
            }
        }
    }

?>