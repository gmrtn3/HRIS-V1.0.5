<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hris_db";


    $conn = mysqli_connect($servername, $username,  $password, $dbname);

if(isset($_POST['delete_data']))
{
    $id = $_POST['id'];
    $designate = $_POST['company_code'];

    if ($designate > 0) {
        header("Location: ../../companyCode?error=You cannot delete a company code that has employee designated");
    }
    else
    {
        $query = "DELETE FROM company_code_tb WHERE id='$id'";
        $query_run = mysqli_query($conn, $query);
    
        if($query_run)
        {
            header("Location: ../../companyCode?msg=Delete Record Successfully");
        }
        else
        {
            echo "Failed: " . mysqli_error($conn);
        }
    }

}