<?php 

    include '../../config.php';

    if(isset($_POST['updatedata'])){
        $empid = $_POST['empid'];
        $status = $_POST['status'];

        // var_dump($status);
       
        if($status == "Active"){
            echo "You are active <br>";

            $status = "Inactive";

            $sql = "UPDATE employee_tb SET `status` = '$status' WHERE empid = '$empid'";
            $query_run = mysqli_query($conn, $sql);

            if($query_run){
                header("Location: ../../EmployeeList");
              
            }
            else{
                echo '<script> alert("Data Not Updated"); </script>';
            }
        }else{
            $status = "Active";

            $sql = "UPDATE employee_tb SET `status` = '$status' WHERE empid = '$empid'";
            $query_run = mysqli_query($conn, $sql);

            if($query_run){
                header("Location: ../../EmployeeList");
              
            }
            else{
                echo '<script> alert("Data Not Updated"); </script>';
            }
        }

       
    }


?>