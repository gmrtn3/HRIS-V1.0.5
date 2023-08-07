<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hris_db";


    $conn = mysqli_connect($servername, $username,  $password, $dbname);

    if(isset($_POST['add_data']))
    {
        $position = $_POST['position_text'];
    
        // Check if the position already exists in the database
        $query = "SELECT * FROM positionn_tb WHERE position = '$position'";
        $result = mysqli_query($conn, $query);
    
        if(mysqli_num_rows($result) > 0) {
            // Position already exists, display error message or redirect to the same page with an error message
            header("Location: ../../Position.php?error=Position already exists");
            exit;
        } else {
            // Position does not exist, insert the new record
            $query = "INSERT INTO positionn_tb (`position`) VALUES ('$position')";
            $query_run = mysqli_query($conn, $query);
    
            if($query_run)
            {
                header("Location: ../../Position.php?msg=New record created successfully");
            }
            else
            {
                echo "Failed: " . mysqli_error($conn);
            }
        }
    }

?>