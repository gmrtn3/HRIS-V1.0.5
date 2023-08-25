<?php

    include '../../config.php';

    if(isset($_POST['updatedata'])){
        $id = $_POST['name_id'];
        //set credits
        // $set_Vcrdt = $_POST['name_set_Vcrdt'];
        // $set_Scrdt = $_POST['name_set_Scrdt'];
        // $set_Bcrdt = $_POST['name_set_Bcrdt'];

        //for update inputs
        $Vcrdt = $_POST['name_updt_Vcrdt'];
        $Vcrdt1 = $_POST['name_updt_Vcrdt1'];

        $Vcrdt_final = $Vcrdt . $Vcrdt1;

        $Scrdt = $_POST['name_updt_Scrdt'];
        $Scrdt1 = $_POST['name_updt_Scrdt1'];

        $Scrdt_final = $Scrdt . $Scrdt1;

        $Bcrdt = $_POST['name_updt_Bcrdt'];
        $Bcrdt1 = $_POST['name_updt_Bcrdt1'];

        $Bcrdt_final = $Bcrdt . $Bcrdt1;


        // $diff_Vcrdt = abs((float)$set_Vcrdt - (float)$Vcrdt );
        // $diff_Scrdt = abs((float)$set_Scrdt - (float)$Scrdt );
        // $diff_Bcrdt = abs((float)$set_Bcrdt - (float)$Bcrdt );

        $sql ="UPDATE leaveinfo_tb SET col_vctionCrdt= $Vcrdt_final, col_sickCrdt=$Scrdt_final, col_brvmntCrdt= $Bcrdt_final WHERE col_ID = '$id' ";
        $query_run = mysqli_query($conn, $sql);

        if($query_run){
            header("Location: ../../LeaveInfo.php?msg=Updated Successfully");
            //echo $diff_Vcrdt;
        }
        else{
            echo '<script> alert("Data Not Updated"); </script>';
        }
    }
?>