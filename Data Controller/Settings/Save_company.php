<?php 

include '../../config.php';
    if(isset($_POST['name_btn_submit']))
    {
        $holiday_type = '';

        if(isset($_POST['name_before'])){

            $holiday_type = 'Holiday Before';

        }else if(isset($_POST['name_after'])){

            $holiday_type = 'Holiday After';

        }else if(isset($_POST['name_beforeAfter'])){

            $holiday_type = 'Holiday Before and After';

        }else{
            $holiday_type = 'Default';
        }
 

        $query_check = "SELECT * FROM settings_tb";
        $result = mysqli_query($conn, $query_check);

        if(mysqli_num_rows($result) > 0){
            $query = "UPDATE settings_tb
            SET holiday_pay = '$holiday_type'
            ";
            $query_run = mysqli_query($conn, $query);    
    
            if($query_run){
                header("Location: ../../settings?error=You successfull save data for Company Settings");
            }
        }else{
            $query = "INSERT INTO settings_tb (`holiday_pay`) VALUES ('$holiday_type')";
            $query_run = mysqli_query($conn, $query);    
    
            if($query_run){
                header("Location: ../../settings?error=You successfull save data for Company Settings");
            }
        }

      

    }
?>