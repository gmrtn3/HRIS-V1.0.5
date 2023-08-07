
<?php
    include '../../config.php';
     #--------------------------------START IF SA HALFDAY FIRST OR SECOND HALF--------------------------------
     if (isset($_POST['firstHalf'])) {

        
                                $empname = $_POST["name_emp"];
                                $leave_type =  $_POST['name_LeaveT'];
                                $leave_period = $_POST['firstHalf'];
                                $str_date = $_POST['name_STRdate'];
                                $end_date = $_POST['name_ENDdate'];
                                $reason_txt = $_POST['name_txtRSN'];
                                
                                 #file name with a random number so that similar dont get replaced
                                    //     $reason_file = rand(1000,10000)."-" . $_FILES["name_file"]["name"];

                                    //     #temporary file name to store file
                                    //     $tname = $_FILES["name_file"]["tmp_name"];
                                
                                    // #upload directory path
                                    // $uploads_dir = 'file_reason';
                                    // #TO move the uploaded file to specific location
                                    // move_uploaded_file($tname, $uploads_dir.'/'.$reason_file);
                                    $contents = file_get_contents($_FILES['name_file']['tmp_name']);
                                    $escaped_contents = mysqli_real_escape_string($conn, $contents);



                                    //Para sa pag select ng mga data galing sa EMPSCHEDULE to validate if may sched na siya ay pwede na mag request ng leave
                                        $result_empsched = mysqli_query($conn, "SELECT
                                            *  
                                        FROM
                                            empschedule_tb
                                        WHERE empid = $empname");
                                        if(mysqli_num_rows($result_empsched) > 0) {
                                            $row__schedEMP= mysqli_fetch_assoc($result_empsched);
                                                $sched_name = $row__schedEMP['schedule_name'];
                                                        // --------------break------------//
                                                    $result_sched = mysqli_query($conn, "SELECT
                                                        *  
                                                    FROM
                                                        schedule_tb
                                                    WHERE `schedule_name` = '$sched_name'");
                                                    if(mysqli_num_rows($result_sched) > 0) {
                                                        $row_sched= mysqli_fetch_assoc($result_sched);



                                                                    //Para sa pag select ng mga data galing sa LEAVE INFO TABLE
                                                                $result_leaveINFO = mysqli_query($conn, "SELECT
                                                                *  
                                                                FROM
                                                                leaveinfo_tb
                                                                WHERE col_empID = $empname");
                                                                if(mysqli_num_rows($result_leaveINFO) > 0) {
                                                                $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
                                                                //echo $row__leaveINFO['col_vctionCrdt'];
                                                                } else {
                                                                echo "No results found.";
                                                                }
                                                                //Para sa pag select ng mga data galing sa LEAVE INFO TABLE (END)


                                                                //------------------------------------------------START VALIDATION ONLY ONE REQUEST-----------------------------------------------------

                                                                //Para sa pag select ng mga data galing sa apply leave TABLE  (PARA MAG CHECK IF EXIST) DIto CHESTAH
                                                                $result_leaveINFO = mysqli_query($conn, " SELECT
                                                                *  
                                                                FROM
                                                                applyleave_tb
                                                                WHERE `col_req_emp` = $empname
                                                                AND `col_LeavePeriod` = '$leave_period'
                                                                AND (`col_status` = 'Pending' OR `col_status` = 'Approved')
                                                                AND ('$str_date' BETWEEN `col_strDate` AND `col_endDate` 
                                                                OR '$end_date' BETWEEN `col_strDate` AND `col_endDate`)");
                                                                if(mysqli_num_rows($result_leaveINFO) > 0) {
                                                                $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
                                                                //echo  "d maka insert";
                                                                header("Location: ../../leavereq.php?error= Cannot Apply due to the selected dates is already taken by your past requests.");
                                                                } else {       
                                                                if (isset($_POST['name_wthPay'])) {
                                                                    // CAN LEAVE WITH PAY
                                                                    $minusVacationCredits = $row__leaveINFO['col_vctionCrdt'] - 0.5;
                                                                    $minusSickCredits = $row__leaveINFO['col_sickCrdt'] - 0.5;
                                                                    $minusBvrvmntCredits = $row__leaveINFO['col_brvmntCrdt'] - 0.5;
                        

                                                                    if($leave_type == 'Vacation Leave'){
                                                                        if($minusVacationCredits < 0 ){
                                                                            header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Vacation Leave");
                                                                        }
                                                                        else{
                                                                            // header("Location: ../../leavereq.php?error=You request a leave on your rest day");
                                                                            #sql query to insert into database
                                                                                    $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`) 
                                                                                    VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending')";

                                                                                    if(mysqli_query($conn,$sql)){
                                                                                        header("Location: ../../leavereq.php?msg=Successfully Added");
                                                                                    }
                                                                                    else{
                                                                                        echo "Error";
                                                                                    }
                                                                            
                                                                            }
                                                                    } //end if statement in Vacation
                                                                    elseif($leave_type == 'Bereavement Leave'){
                                                                        if($minusBvrvmntCredits < 0 ){
                                                                            header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Bereavement Leave");
                                                                        }
                                                                        else{
                                                                                #sql query to insert into database
                                                                                $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`) 
                                                                                VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending')";

                                                                                if(mysqli_query($conn,$sql)){
                                                                                header("Location: ../../leavereq.php?msg=Successfully Added");
                                                                                }
                                                                                else{
                                                                                echo "Error";
                                                                                }
                                                                            }
                                                                    } //end if statement in Bereavement Leave
                                                                    elseif($leave_type == 'Sick Leave'){
                                                                        if($minusSickCredits < 0 ){
                                                                            header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Sick Leave");
                                                                        }
                                                                        else{
                                                                                #sql query to insert into database
                                                                                $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`) 
                                                                                VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending')";

                                                                                if(mysqli_query($conn,$sql)){
                                                                                header("Location: ../../leavereq.php?msg=Successfully Added");
                                                                                }
                                                                                else{
                                                                                echo "Error";
                                                                                }
                                                                            }
                                                                    } //end if statement in Sick Leave
                                                                }else{
                                                                    //else CAN LEAVE BUT NO PAY
                                                                    
                                                                    $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`) 
                                                                    VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','Without Pay', 'Pending')";

                                                                        if(mysqli_query($conn,$sql)){
                                                                            header("Location: ../../leavereq.php?msg=Successfully Added");
                                                                        }
                                                                        else{
                                                                            echo "Error";
                                                                        }
                                                                }//END ISSET WITHPAY                                             
                                                                                

                                                                }

                                                                //Para sa pag select ng mga data galing sa apply leave TABLE  (PARA MAG CHECK IF EXIST)
                                                       
                                                    
                                                    }// end schedule tb
                                                    
                                        } //end empschedule tb
                                        else {
                                            header("Location: ../../leavereq.php?error=You cannot Request a Leave without an assigned schedule.");
                                        } //Para sa pag select ng mga data galing sa EMPSCHEDULE to validate if may sched na siya ay pwede na mag request ng leave (END)
                                
                            //------------------------------------------------START VALIDATION ONLY ONE REQUEST END-----------------------------------------------------
                            

        }
    else if (isset($_POST['secondHalf'])) {



        

                                  
                                $empname = $_POST["name_emp"];
                                $leave_type =  $_POST['name_LeaveT'];
                                $leave_period = $_POST['secondHalf'];
                                $str_date = $_POST['name_STRdate'];
                                $end_date = $_POST['name_ENDdate'];
                                $reason_txt = $_POST['name_txtRSN'];
                                 #file name with a random number so that similar dont get replaced
                                    //     $reason_file = rand(1000,10000)."-" . $_FILES["name_file"]["name"];

                                    //     #temporary file name to store file
                                    //     $tname = $_FILES["name_file"]["tmp_name"];
                                
                                    // #upload directory path
                                    // $uploads_dir = 'file_reason';
                                    // #TO move the uploaded file to specific location
                                    // move_uploaded_file($tname, $uploads_dir.'/'.$reason_file);

                                    $contents = file_get_contents($_FILES['name_file']['tmp_name']);
                                    $escaped_contents = mysqli_real_escape_string($conn, $contents);



    //Para sa pag select ng mga data galing sa EMPSCHEDULE to validate if may sched na siya ay pwede na mag request ng leave
            $result_empsched = mysqli_query($conn, "SELECT
            *  
            FROM
            empschedule_tb
            WHERE empid = $empname");
            if(mysqli_num_rows($result_empsched) > 0) {
            $row__schedEMP= mysqli_fetch_assoc($result_empsched);

                    //Para sa pag select ng mga data galing sa LEAVE INFO TABLE
                    $result_leaveINFO = mysqli_query($conn, "SELECT
                    *  
                    FROM
                    leaveinfo_tb
                    WHERE col_empID = $empname");
                    if(mysqli_num_rows($result_leaveINFO) > 0) {
                    $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
                    //echo $row__leaveINFO['col_vctionCrdt'];
                    } else {
                    echo "No results found.";
                    }
                    //Para sa pag select ng mga data galing sa LEAVE INFO TABLE (END)


                    //------------------------------------------------START VALIDATION ONLY ONE REQUEST-----------------------------------------------------

                    //Para sa pag select ng mga data galing sa apply leave TABLE  (PARA MAG CHECK IF EXIST) DIto CHESTAH
                    $result_leaveINFO = mysqli_query($conn, " SELECT
                    *  
                    FROM
                    applyleave_tb
                    WHERE `col_req_emp` = $empname
                    AND `col_LeavePeriod` = '$leave_period'
                    AND (`col_status` = 'Pending' OR `col_status` = 'Approved')
                    AND ('$str_date' BETWEEN `col_strDate` AND `col_endDate` 
                    OR '$end_date' BETWEEN `col_strDate` AND `col_endDate`)");
                    if(mysqli_num_rows($result_leaveINFO) > 0) {
                    $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
                    //echo  "d maka insert";
                    header("Location: ../../leavereq.php?error= Cannot Apply due to the selected dates is already taken by your past requests.");
                    } else {       
                    if (isset($_POST['name_wthPay'])) {
                        // CAN LEAVE WITH PAY
                        $minusVacationCredits = $row__leaveINFO['col_vctionCrdt'] - 0.5;
                        $minusSickCredits = $row__leaveINFO['col_sickCrdt'] - 0.5;
                        $minusBvrvmntCredits = $row__leaveINFO['col_brvmntCrdt'] - 0.5; 

                        if($leave_type == 'Vacation Leave'){
                            if($minusVacationCredits < 0 ){
                                header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Vacation Leave");
                            }
                            else{
                                
                                    #sql query to insert into database
                                    $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`) 
                                    VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending')";

                                    if(mysqli_query($conn,$sql)){
                                    header("Location: ../../leavereq.php?msg=Successfully Added");
                                    }
                                    else{
                                    echo "Error";
                                    }
                                }
                        } //end if statement in Vacation
                        elseif($leave_type == 'Bereavement Leave'){
                            if($minusBvrvmntCredits < 0 ){
                                header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Bereavement Leave");
                            }
                            else{
                                    #sql query to insert into database
                                    $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`) 
                                    VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending')";

                                    if(mysqli_query($conn,$sql)){
                                    header("Location: ../../leavereq.php?msg=Successfully Added");
                                    }
                                    else{
                                    echo "Error";
                                    }
                                }
                        } //end if statement in Bereavement Leave
                        elseif($leave_type == 'Sick Leave'){
                            if($minusSickCredits < 0 ){
                                header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Sick Leave");
                            }
                            else{
                                    #sql query to insert into database
                                    $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`) 
                                    VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending')";

                                    if(mysqli_query($conn,$sql)){
                                    header("Location: ../../leavereq.php?msg=Successfully Added");
                                    }
                                    else{
                                    echo "Error";
                                    }
                                }
                        } //end if statement in Sick Leave
                    }else{
                        //else CAN LEAVE BUT NO PAY
                        #sql query to insert into database
                        $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`) 
                        VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','Without Pay', 'Pending')";

                            if(mysqli_query($conn,$sql)){
                            header("Location: ../../leavereq.php?msg=Successfully Added");
                            }
                            else{
                                echo "Error";
                            }
                    } //END ISSET WITHPAY                                        
                                

                    }
                    //Para sa pag select ng mga data galing sa apply leave TABLE (END)



    } else {
        header("Location: ../../leavereq.php?error=You cannot Request a Leave without an assigned schedule.");
    } //Para sa pag select ng mga data galing sa EMPSCHEDULE to validate if may sched na siya ay pwede na mag request ng leave (END)



                            




    //------------------------------------------------START VALIDATION ONLY ONE REQUEST END-----------------------------------------------------

    }#--------------------------------END IF SA HALFDAY FIRST OR SECOND HALF--------------------------------
    else { //------------------------------------pARA if full day
         
        $empname = $_POST["name_emp"];
        $leave_type =  $_POST['name_LeaveT'];
        $leave_period = $_POST['name_LeaveP'];
        $str_date = $_POST['name_STRdate'];
        $end_date = $_POST['name_ENDdate'];
        $reason_txt = $_POST['name_txtRSN'];
        #file name with a random number so that similar dont get replaced
    //     $reason_file = rand(1000,10000)."-" . $_FILES["name_file"]["name"];

    //     #temporary file name to store file
    //     $tname = $_FILES["name_file"]["tmp_name"];
   
    // #upload directory path
    // $uploads_dir = 'file_reason';
    // #TO move the uploaded file to specific location
    // move_uploaded_file($tname, $uploads_dir.'/'.$reason_file);

    $contents = file_get_contents($_FILES['name_file']['tmp_name']);
	$escaped_contents = mysqli_real_escape_string($conn, $contents);



    //Para sa pag select ng mga data galing sa EMPSCHEDULE to validate if may sched na siya ay pwede na mag request ng leave
        $result_empsched = mysqli_query($conn, "SELECT
        *  
        FROM
        empschedule_tb
        WHERE empid = $empname");
        if(mysqli_num_rows($result_empsched) > 0) {
        $row__schedEMP= mysqli_fetch_assoc($result_empsched);


                    //Para sa pag select ng mga data galing sa LEAVE INFO TABLE
                    $result_leaveINFO = mysqli_query($conn, "SELECT
                    *  
                FROM
                    leaveinfo_tb
                WHERE col_empID = $empname");
                if(mysqli_num_rows($result_leaveINFO) > 0) {
                    $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
                    //echo $row__leaveINFO['col_vctionCrdt'];
                } else {
                    echo "No results found.";
                }
                //Para sa pag select ng mga data galing sa LEAVE INFO TABLE (END)


                //------------------------------------------------START VALIDATION ONLY ONE REQUEST-----------------------------------------------------
                //Para sa pag select ng mga data galing sa apply leave TABLE 
                $result_leaveINFO = mysqli_query($conn, " SELECT
                        *  
                FROM
                                            applyleave_tb
                                        WHERE `col_req_emp` = $empname
                                        AND `col_LeavePeriod` = '$leave_period'
                                        AND (`col_status` = 'Pending' OR `col_status` = 'Approved')
                                        AND ('$str_date' BETWEEN `col_strDate` AND `col_endDate` 
                                        OR '$end_date' BETWEEN `col_strDate` AND `col_endDate`)");
                    if(mysqli_num_rows($result_leaveINFO) > 0) {
                                            $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
                                            
                                        header("Location: ../../leavereq.php?error= Cannot Apply due to the selected dates is already taken by your past requests.");
                } else {

                        if (isset($_POST['name_wthPay'])) {
                            //---------------BREAK START IF FULLDAY ANG REQUEST----------------
                            // CAN LEAVE WITH PAY
                            $date1 = new DateTime($str_date); // value ng start date 
                            $date2 = new DateTime($end_date); // value ng end date 
                            $interval = $date1->diff($date2);
                            echo $interval->days . "  break";
                            $minusVacationCredits = $row__leaveINFO['col_vctionCrdt'] - $interval->days;
                            $minusSickCredits = $row__leaveINFO['col_sickCrdt'] - $interval->days;
                            $minusBvrvmntCredits = $row__leaveINFO['col_brvmntCrdt'] - $interval->days;
                    
                            if($leave_type == 'Vacation Leave'){
                                if($minusVacationCredits < 0 ){
                                    header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Vacation Leave");
                                }
                                else{
                                        #sql query to insert into database
                                        $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`) 
                                        VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending')";
                    
                                        if(mysqli_query($conn,$sql)){
                                            header("Location: ../../leavereq.php?msg=Successfully Added");
                                        }
                                        else{
                                        echo "Error";
                                        }
                                    }
                            } //end if statement in Vacation
                            elseif($leave_type == 'Bereavement Leave'){
                                if($minusBvrvmntCredits < 0 ){
                                    header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Bereavement Leave");
                                }
                                else{
                                        #sql query to insert into database
                                    $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`) 
                                    VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending')";
                    
                                        if(mysqli_query($conn,$sql)){
                                        header("Location: ../../leavereq.php?msg=Successfully Added");
                                        }
                                        else{
                                            echo "Error";
                                        } 
                                    }
                            } //end if statement in Bereavement Leave
                            elseif($leave_type == 'Sick Leave'){
                                if($minusSickCredits < 0 ){
                                    header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Sick Leave");
                                }
                                else{
                                        #sql query to insert into database
                                    $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`) 
                                    VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents', 'With Pay', 'Pending')";
                    
                                        if(mysqli_query($conn,$sql)){
                                        header("Location: ../../leavereq.php?msg=Successfully Added");
                                        }
                                        else{
                                        echo "Error";
                                        }
                                    }
                            } //end if statement in Sick Leave
                                        //--------------------------BREAK END IF FULLDAY ANG REQUEST---------------------- 

                        }
                        else{ 
                            //else CAN LEAVE BUT NO PAY
                            #sql query to insert into database
                            $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`) 
                            VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','Without Pay', 'Pending')";

                                if(mysqli_query($conn,$sql)){
                                header("Location: ../../leavereq.php?msg=Successfully Added");
                                }
                                else{
                                    echo "Error";
                                }

                        } //END ISSET WITHPAY

                                    
                }
                //Para sa pag select ng mga data galing sa apply leave TABLE (END)



                //------------------------------------------------START VALIDATION ONLY ONE REQUEST END-----------------------------------------------------



        } else {
            header("Location: ../../leavereq.php?error=You cannot Request a Leave without an assigned schedule.");
    } //Para sa pag select ng mga data galing sa EMPSCHEDULE to validate if may sched na siya ay pwede na mag request ng leave (END)

        
    }


      
?>