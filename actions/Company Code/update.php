<?php

    include '../../config.php';

    if(isset($_POST['updatedata'])){

        $id = $_POST['id'];
        $new_deptname = $_POST['company_code'];

        $sql ="UPDATE `company_code_tb` SET `company_code`='$new_deptname' WHERE `id` = $id";
        $query_run = mysqli_query($conn, $sql);

        $sqls = "UPDATE `employee_tb` SET `company_code` = '$new_deptname' WHERE `company_code` = `$id`";
        

        if($query_run){
            header("Location: ../../companyCode?msg=Updated Successfully");
            //echo $diff_Vcrdt;

            // $query = "SELECT * FROM employee_tb WHERE department_name = $id ";
            // $result = $conn->query($query);

            // // Check if any rows are fetched 
            // if ($result->num_rows > 0) 
            // {
            // $emp_array = array(); // Array to store
            //     while($row = $result->fetch_assoc()) 
            //     {
            //         echo $emp_ID = $row["id"];    

            //         $emp_array[] = array('col_ID' => $emp_ID); 
                    
            //     } //end while 

            //     foreach ($emp_array as $alias_empARRAY) 
            //     {
            //         $col_ID = $alias_empARRAY['col_ID'];

            //         $sql ="UPDATE `employee_tb` SET `department_name` = '$new_deptname' WHERE `col_ID` = $col_ID";
            //         $query_run = mysqli_query($conn, $sql);
            
            //         if($query_run){
            //             //header("Location: ../../Department.php?msg=Updated Successfully");
            //             //echo $diff_Vcrdt;
            //         }
                
            //     }
                
            //}
        }
        else{
            echo '<script> alert("Data Not Updated"); </script>';
        }

        

    
      
    }
?>