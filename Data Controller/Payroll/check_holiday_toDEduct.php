<?php
 //Para mag check ilan ang date ng may holiday para ma minus sa salary at d magdoble ang salary
$sql_att_all_table = "SELECT
                *
            FROM 
                `attendances` 
            WHERE 
                `status` = 'Present' 
            
            AND 
                `empid` = '$EmployeeID'
            AND 
                `date` 
            BETWEEN  
                '$str_date' 
            AND  
                '$end_date'";
            
            $result_table = $conn->query($sql_att_all_table);
                                                                 
                if ($result_table->num_rows > 0) 
                {

                    $att_table_array = array(); // Array to store the attendance

                    while ($row_att_all_table = $result_table->fetch_assoc())
                    {
                        $date_att_table = $row_att_all_table['date'];
                       
                        $att_table_array[] = array('date_att_table' => $date_att_table);
                    }

                    $value_of_drate_holiday = 0;
                    $num_days_holiday = 0;
                    
                    foreach($att_table_array as $check_for_holiday){
                       $check_holiday_array =  $check_for_holiday['date_att_table'];

                        $result_check_holiday = mysqli_query($conn, " SELECT
                            COUNT(date_holiday) as number_of_date_holiday
                        FROM 
                            `holiday_tb` 
                        WHERE date_holiday =  '$check_holiday_array' AND (`holiday_type` = 'Regular Holiday' OR `holiday_type` = 'Special Non-Working Holiday' OR `holiday_type` = 'Special Working Holiday')");

                        if(mysqli_num_rows($result_check_holiday) > 0) {
                            $row_check_holiday = mysqli_fetch_assoc($result_check_holiday);
                            $num_days_holiday += $row_check_holiday['number_of_date_holiday'];
                            $value_of_drate_holiday = $row_emp['drate'] * $num_days_holiday;
                        }
                    }
                }

                $query_checker_Payroll_settings = "SELECT * FROM settings_company_tb";
                $result_checker = mysqli_query($conn, $query_checker_Payroll_settings);
                $row_checker = mysqli_fetch_assoc($result_checker);

                $value_of_drate_holiday;
                // if($row_checker['col_salary_settings'] === 'Fixed Salary'){
                //     $value_of_drate_holiday = 0;
                // }else{
                //     $value_of_drate_holiday;
                // }



                //check para sa overtime para mabawasan 
// $holiday_OT = "SELECT
//                     *
//                 FROM 
//                     `holiday_tb` 
//                 WHERE `date_holiday` BETWEEN '$str_date' AND  '$end_date'";
            
//                 $result_holiday_OT = $conn->query($holiday_OT);
                                                                 
//                 if ($result_holiday_OT->num_rows > 0) 
//                 {
//                     $array_holdiday__OT = array(); // Array to store the attendance

//                     while ($row_holiday_OT = $result_holiday_OT->fetch_assoc())
//                     {
//                         $date_holiday = $row_holiday_OT['date_holiday'];
                       
//                         $array_holdiday__OT[] = array('date_holiday' => $date_holiday);
//                     }

//                     $value_of_drate_holiday_OT = 0;
//                     // $num_days_holiday_OT = 0;


//                     foreach($array_holdiday__OT as $array_holdiday_OT){

//                         $check_for_holiday_OT =  $array_holdiday_OT['date_holiday'];

//                         $result_check_holiday_OT = mysqli_query($conn, " SELECT
//                             *
//                         FROM 
//                             `overtime_tb` 
//                         WHERE `empid` = '$EmployeeID' AND `work_schedule` BETWEEN  '$str_date' AND  '$end_date' AND work_schedule = '$check_for_holiday_OT' AND `status` = 'Approved'");

//                         if(mysqli_num_rows($result_check_holiday_OT) > 0) {
//                             $row_check_holiday_OT = mysqli_fetch_assoc($result_check_holiday_OT);
//                             $OTHOUR = $row_check_holiday_OT['total_ot']; //value 02:00:00
//                             $OTHOUR = DateTime::createFromFormat('H:i:s', $OTHOUR);
//                             $OTHOUR = $OTHOUR ->format('H');// Extract Hour from DateTime object
//                             $OTHOUR += intval($OTHOUR);

//                             $value_of_drate_holiday_OT = $row_emp['otrate'] *  $OTHOUR;
                            
//                         }
//                     }
//                 }

$holiday_OT = " SELECT
                    *
                FROM 
                    `overtime_tb` 
                WHERE `empid` = '$EmployeeID' AND `work_schedule` BETWEEN  '$str_date' AND  '$end_date' AND `status` = 'Approved'";
                                
                $result_holiday_OT = $conn->query($holiday_OT);
                                                                 
                if ($result_holiday_OT->num_rows > 0) 
                {
                    $array_holdiday__OT = array(); // Array to store the attendance

                    while ($row_holiday_OT = $result_holiday_OT->fetch_assoc())
                    {
                        $date_holiday_OT = $row_holiday_OT['total_ot'];
                        $date_holiday = $row_holiday_OT['work_schedule'];
                       
                        $array_holdiday__OT[] = array('date_holiday' => $date_holiday);
                    }

                    $value_of_drate_holiday_OT = 0;
                    // $num_days_holiday_OT = 0;


                    foreach($array_holdiday__OT as $array_holdiday_OT){

                        $check_for_holiday_date =  $array_holdiday_OT['date_holiday'];

                        $holiday_OTT = " SELECT
                                            *
                                        FROM 
                                            `holiday_tb` 
                                        WHERE `date_holiday` BETWEEN '$str_date' AND  '$end_date' and `date_holiday` = '$check_for_holiday_date'";
                                    
                        $result_holiday_OTT = $conn->query($holiday_OTT);
                                                                                         
                        if ($result_holiday_OTT->num_rows > 0) 
                        {
                            $row_check_holiday_OTT = mysqli_fetch_assoc($result_holiday_OTT);
                            $check_for_holiday_OT = $row_check_holiday_OTT['date_holiday'];



                            $result_check_holiday_OT = mysqli_query($conn, " SELECT
                                                        *
                                                    FROM 
                                                        `overtime_tb` 
                                                    WHERE `empid` = '$EmployeeID' AND `work_schedule` BETWEEN  '$str_date' AND  '$end_date' AND work_schedule = '$check_for_holiday_OT' AND `status` = 'Approved'");
                            
                                                     if(mysqli_num_rows($result_check_holiday_OT) > 0) {
                                                        $row_check_holiday_OT = mysqli_fetch_assoc($result_check_holiday_OT);
                                                        @$OTHOUR += $row_check_holiday_OT['total_ot'];
                                                        // $OTHOUR1 = DateTime::createFromFormat('H:i:s', $OTHOUR);
                                                        // $OTHOUR2 = $OTHOUR1 ->format('H');// Extract Hour from DateTime object
                                                        // $OTHOUR3 += intval($OTHOUR2);
                            
                                                        $value_of_drate_holiday_OT = $row_emp['otrate'] *  $OTHOUR;
                                                        
                                                    }
                            
                        }
                        
                    }
                }

 ?>