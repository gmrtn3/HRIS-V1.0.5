<?php

//-------------------------------FOR FETCHING HOLIDAY---------------//


        include 'config.php';

        error_reporting(0);
        date_default_timezone_set('Asia/Manila'); 
        $currentYear = date('Y');
        
        
        $apiKey = 'f8OoEox6tyATeqbA5k0hMX7xvsW7LnDV';
        $country = 'PH';
        

        $checker_holiday = "SELECT * FROM holiday_tb";
        $result_holiday = mysqli_query($conn, $checker_holiday);

        if(mysqli_num_rows($result_holiday) <= 0 ){      
            
            // API endpoint URL
            $url = "https://calendarific.com/api/v2/holidays?api_key={$apiKey}&country={$country}&year={$currentYear}";
            
            // Make a request to the API
            $response = file_get_contents($url);
            
            // Parse the JSON response
            $data = json_decode($response, true);
            
            // Check if the API request was successful
            if ($data['meta']['code'] == 200) {
                // Iterate through each holiday
                // Prepare the SQL statement
                $sql = "INSERT INTO holiday_tb (`holiday_title`, `date_holiday`, `holiday_type`) 
                        VALUES (?, ?, ?)";
            
                // Prepare the statement
                $stmt = mysqli_prepare($conn, $sql);
            
                foreach ($data['response']['holidays'] as $holiday) {
                    // $title = $holiday['name'];
                    // $date = $holiday['date']['iso'];
                    // $type = $holiday['type'][0];
            
                    // echo "Title: {$title}\n";
                    // echo "Date: {$date}\n";
                    // echo "Type: {$type}\n";
                    // echo "----------\n";
            
            
                    $date = $holiday['date']['iso'];
                    $dateTime = new DateTime($date);
                    $holidayDate = $dateTime->format('Y-m-d');
            
                    $title = $holiday['name']; // holiday title
                    $type = $holiday['type'][0];
                    //$emp_login_ID = $_SESSION['empid']; // login Emp ID
            
                    // Determine if it's a working day or non-working day holiday
                    $isWorkingDayHoliday = ($type == 'observance' || $type == 'season') ? 'Regular Working Day' : 'Regular Working Day';
            
            
                    // Check if the holiday already exists in the database
                    $query = "SELECT * FROM `holiday_tb` WHERE `holiday_title` =  ? AND `date_holiday` = ?";
                    $stmt_check = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt_check, 'ss', $title, $holidayDate);
                    mysqli_stmt_execute($stmt_check);
                    mysqli_stmt_store_result($stmt_check);
            
                    // If the holiday does not exist, insert it
                    if (mysqli_stmt_num_rows($stmt_check) == 0) {
                        // Bind the parameters to the prepared statement
                        mysqli_stmt_bind_param($stmt, 'sss', $title, $holidayDate, $isWorkingDayHoliday);
            
                        // Execute the statement
                        if (mysqli_stmt_execute($stmt)) {
                            // Insert successful
                        }
                    } else {
                        // Wont insert
                    
                    }
            
                    // Close the statement
                    mysqli_stmt_close($stmt_check);
                    
                }
                // Close the statement
                mysqli_stmt_close($stmt);
            } else {
                // echo "Error: Unable to fetch holidays.";
            
            }
        }
        else{
            //if may laman na ang holiday_TB

            $checker_year = "SELECT YEAR(date_holiday) as year FROM holiday_tb ORDER BY date_holiday DESC";
            $result_year = mysqli_query($conn, $checker_year);

            $row_checker_year = mysqli_fetch_assoc($result_year);

            $year_last_holiday = $row_checker_year['year'];

            if($year_last_holiday != $currentYear){
                    
                    // API endpoint URL
                    $url = "https://calendarific.com/api/v2/holidays?api_key={$apiKey}&country={$country}&year={$currentYear}";
                    
                    // Make a request to the API
                    $response = file_get_contents($url);
                    
                    // Parse the JSON response
                    $data = json_decode($response, true);
                    
                    // Check if the API request was successful
                    if ($data['meta']['code'] == 200) {
                        // Iterate through each holiday
                        // Prepare the SQL statement
                        $sql = "INSERT INTO holiday_tb (`empid`, `holiday_title`, `date_holiday`, `holiday_type`) 
                                VALUES (?, ?, ?, ?)";
                    
                        // Prepare the statement
                        $stmt = mysqli_prepare($conn, $sql);
                    
                        foreach ($data['response']['holidays'] as $holiday) {
                            // $title = $holiday['name'];
                            // $date = $holiday['date']['iso'];
                            // $type = $holiday['type'][0];
                    
                            // echo "Title: {$title}\n";
                            // echo "Date: {$date}\n";
                            // echo "Type: {$type}\n";
                            // echo "----------\n";
                    
                    
                            $date = $holiday['date']['iso'];
                            $dateTime = new DateTime($date);
                            $holidayDate = $dateTime->format('Y-m-d');
                    
                            $title = $holiday['name']; // holiday title
                            $type = $holiday['type'][0];
                            $emp_login_ID = $_SESSION['empid']; // login Emp ID
                    
                            // Determine if it's a working day or non-working day holiday
                            $isWorkingDayHoliday = ($type == 'observance' || $type == 'season') ? 'Regular Working Day' : 'Regular Working Day';
                    
                    
                            // Check if the holiday already exists in the database
                            $query = "SELECT * FROM `holiday_tb` WHERE `holiday_title` =  ? AND `date_holiday` = ?";
                            $stmt_check = mysqli_prepare($conn, $query);
                            mysqli_stmt_bind_param($stmt_check, 'ss', $title, $holidayDate);
                            mysqli_stmt_execute($stmt_check);
                            mysqli_stmt_store_result($stmt_check);
                    
                            // If the holiday does not exist, insert it
                            if (mysqli_stmt_num_rows($stmt_check) == 0) {
                                // Bind the parameters to the prepared statement
                                mysqli_stmt_bind_param($stmt, 'ssss', $emp_login_ID, $title, $holidayDate, $isWorkingDayHoliday);
                    
                                // Execute the statement
                                if (mysqli_stmt_execute($stmt)) {
                                    // Insert successful
                                }
                            } else {
                                // Wont insert
                            
                            }
                    
                            // Close the statement
                            mysqli_stmt_close($stmt_check);
                            
                        }
                        // Close the statement
                        mysqli_stmt_close($stmt);
                    } else {
                        // echo "Error: Unable to fetch holidays.";
                    
                    }
            }

        }
//----------------------------- FOR FETCHING HOLIDAY END ----------------------------//
?>