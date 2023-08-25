<?php
    // $servername = "localhost";
    // $username = "root";
    // $password = "";
    // $dbname = "hris_db";


    // $conn = mysqli_connect($servername, $username,  $password, $dbname);

    include '../../config.php';

if(isset($_POST['update_data']))
{
    $id = $_POST['update_id'];
    $Classification = $_POST['classification'];


//para sa holiday payroll computation kasi need na Regular ang employee para  may holiday pay kaya dapat HINDI ma EDIT ANG classification name
    $query = "SELECT * FROM classification_tb WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        $row_classification = mysqli_fetch_assoc($result);


        if($row_classification['classification'] === 'Pakyawan' || $row_classification['classification'] === 'Manny Pakyawan' || $row_classification['classification'] === 'Regular' || $row_classification['classification'] === 'Internship/OJT'){
            header("Location: ../../Classification?error=You cannot edit static classification");
        }else{
            $query = "UPDATE classification_tb SET classification = '$Classification' WHERE id='$id'";
            $query_run = mysqli_query($conn, $query);
        
            if($query_run)
            {
                header("Location: ../../Classification?msg=Update Record Successfully");
            }
            else
            {
                echo "Failed: " . mysqli_error($conn);
            }
        }
    
    } 

}

?>