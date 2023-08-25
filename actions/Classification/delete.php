<?php
    // $servername = "localhost";
    // $username = "root";
    // $password = "";
    // $dbname = "hris_db";


    // $conn = mysqli_connect($servername, $username,  $password, $dbname);

    include '../../config.php';

if(isset($_POST['delete_data']))
{
    $id = $_POST['delete_id'];
    $designate = $_POST['classification'];

    if ($designate > 0) {
        header("Location: ../../Classification?error=You cannot delete a classification that has employee designated");
    }
    else{
//para sa holiday payroll computation kasi need na Regular ang employee para  may holiday pay kaya dapat HINDI ma DELETE ANG classification name
        $query = "SELECT * FROM classification_tb WHERE id = '$id'";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0) {
            $row_classification = mysqli_fetch_assoc($result);


            if($row_classification['classification'] === 'Pakyawan' || $row_classification['classification'] === 'Manny Pakyawan' || $row_classification['classification'] === 'Regular' || $row_classification['classification'] === 'Internship/OJT'){
                header("Location: ../../Classification?error=You cannot delete static classification");
            }else{
                $query = "DELETE FROM classification_tb WHERE id='$id'";
                $query_run = mysqli_query($conn, $query);
            
                if($query_run)
                {
                    header("Location: ../../Classification?msg=Delete Record Successfully");
                }
                else
                {
                    echo "Failed: " . mysqli_error($conn);
                }
            }
        }
        
    }

   

}



?>