
<?php

  include 'config.php';

  $sql = "SELECT * FROM employee_tb";


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">


</head>
<body>

<style>
  #piece_hide_show {
            display: block;
        }
   #sidebar::-webkit-scrollbar {
      width: 5px; /* Change the width as needed */
    }

    /* Customize scrollbar track */
    #sidebar::-webkit-scrollbar-track {
      background-color: #f1f1f1;
    }

    /* Customize scrollbar handle */
    #sidebar::-webkit-scrollbar-thumb {
      background-color: #888;
    }
    .dropdown{
            background-color: inherit !important;
            border: none !important;
        }
        #notificationDropdown:hover{
            background-color: inherit !important;
        }


</style>
    <!-- UPPER NAV -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row custom-navbar" id="upper-nav"> <!-- UPPER NAV MOTHER -->
                <?php
                      include 'config.php';
                      $query = "SELECT * FROM settings_company_tb";
                      $query_run = mysqli_query($conn, $query);

                      if (mysqli_num_rows($query_run) > 0){
                        $company_row = mysqli_fetch_assoc($query_run);
                        $inserted_photo = $company_row['cmpny_logo'];
                        $image_data = base64_encode($inserted_photo); // Convert blob to base64
                        
                        $image_type = 'image/jpeg'; // Default image type
                        // Determine the image type based on the blob data
                        if (substr($image_data, 0, 4) === "\x89PNG") {
                          $image_type = 'image/png';
                        } elseif (substr($image_data, 0, 2) === "\xFF\xD8") {
                          $image_type = 'image/jpeg';
                        } elseif (substr($image_data, 0, 4) === "RIFF" && substr($image_data, 8, 4) === "WEBP") {
                          $image_type = 'image/webp';
                        }
                      }
                    ?>
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start" id="logo-upper-nav" >
      <a class="navbar-brand brand-logo me-5" href="dashboard.php" ><img src="data:<?php echo $image_type; ?>;base64,<?php echo $image_data; ?>" class="me-2" alt="logo" style="margin-left: 25px;"/></a>
        <!-- <a class="navbar-brand brand-logo-mini" href="dashboard.php" style="width: 100px;"><img src="img/header-logo-small.jpg" alt="logo" style="width: 100px; " /></a> -->
      </div>
      
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end" id="upper-nav-container" >
        <button class="navbar-toggler navbar-toggler align-self-center" id="navbar-toggler" type="button" data-toggle="minimize">
            <span class="fa-solid fa-bars" style="color:white;"></span>
          </button> 
          <button id="sidebarToggle" class="responsive-bars-btn">
            <span class="fa-solid fa-bars" style="color:white;"></span>
          </button>
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          
        <div class="header-user">
                <div class="header-notif">
                  <li class="nav-item dropdown">
                      <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                        <i class="fa-regular fa-bell" style="color: white; font-size: 30px;"></i>
                        <span class="count"></span>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                        <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>

                        <!----------Overtime Notif----------->
                        <?php
                          include 'config.php';
                          date_default_timezone_set('Asia/Manila');

                          $startDate = date('Y-m-d', strtotime('last Monday'));
                          $endDate = date('Y-m-d', strtotime('next Sunday'));

                          $query = "SELECT COUNT(*) AS employee_ot, MAX(`date_filed`) AS last_pending FROM overtime_tb WHERE `status` = 'Pending' AND DATE(`date_filed`) BETWEEN '$startDate' AND '$endDate'";
                          $query_run = mysqli_query($conn, $query);

                          if (mysqli_num_rows($query_run) > 0) {
                            $OT_row = mysqli_fetch_assoc($query_run);
                            $employeeOT = $OT_row['employee_ot'];
                            $lastPending = $OT_row['last_pending'];
                            
                            $now = time(); // Current timestamp
                            $pendingTime = strtotime($lastPending); // Convert last_pending to timestamp
                            $timeDiff = $now - $pendingTime; // Difference in seconds

                            if ($timeDiff < 60) {
                              $formattedTime = 'Just now';
                            } elseif ($timeDiff < 3600) {
                              $minutes = floor($timeDiff / 60);
                              $formattedTime = $minutes . ' minute(s) ago';
                            } else {
                              $hours = floor($timeDiff / 3600);
                              if ($hours < 24) {
                                $formattedTime = $hours . ' hour(s) ago';
                              } else {
                                $days = floor($hours / 24);
                                $formattedTime = $days . ' day(s) ago';
                              }
                            }
                          }

                          $hideDropdown = true;

                          $queryHide = "SELECT `status` FROM overtime_tb WHERE `status` = 'Pending' AND DATE(`date_filed`) BETWEEN '$startDate' AND '$endDate'";
                          $query_run_hide = mysqli_query($conn, $queryHide);
                          $rowsOT = mysqli_fetch_assoc($query_run_hide);

                          if ($rowsOT > 0) {
                            $hideDropdown = false;
                          }
                          ?>

                          <?php if (!$hideDropdown): ?>
                          <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                              <div class="preview-icon bg-danger" style="color: white; font-weight: bold;">
                                <i class="mx-0"><?php echo $employeeOT; ?></i>
                              </div>
                            </div>
                            <div class="preview-item-content">
                              <h6 class="preview-subject font-weight-normal">Overtime Request</h6>
                              <p class="font-weight-light small-text mb-0 text-muted">
                                <?php echo $formattedTime; ?>
                              </p>
                            </div>
                          </a>
                          <?php endif; ?>

                      <!----------Undertime Notif----------->
                      <?php
                            include 'config.php';
                            date_default_timezone_set('Asia/Manila');

                            $startDate = date('Y-m-d', strtotime('last Monday'));
                            $endDate = date('Y-m-d', strtotime('next Sunday'));

                            $query = "SELECT COUNT(*) AS employee_ut, MAX(`date_file`) AS last_pending FROM undertime_tb WHERE `status` = 'Pending' AND DATE(`date_file`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                              $UT_row = mysqli_fetch_assoc($query_run);
                              $employeeUT = $UT_row['employee_ut'];
                              $lastPending = $UT_row['last_pending'];
                              
                              $now = time(); // Current timestamp
                              $pendingTime = strtotime($lastPending); // Convert last_pending to timestamp
                              $timeDiff = $now - $pendingTime; // Difference in seconds

                              if ($timeDiff < 60) {
                                $formattedTime = 'Just now';
                              } elseif ($timeDiff < 3600) {
                                $minutes = floor($timeDiff / 60);
                                $formattedTime = $minutes . ' minute(s) ago';
                              } else {
                                $hours = floor($timeDiff / 3600);
                                if ($hours < 24) {
                                  $formattedTime = $hours . ' hour(s) ago';
                                } else {
                                  $days = floor($hours / 24);
                                  $formattedTime = $days . ' day(s) ago';
                                }
                              }
                            }

                            $hideDropdown = true;

                            $queryHide = "SELECT `status` FROM undertime_tb WHERE `status` = 'Pending' AND DATE(`date_file`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run_hide = mysqli_query($conn, $queryHide);
                            $rowsUT = mysqli_fetch_assoc($query_run_hide);

                            if ($rowsUT > 0) {
                              $hideDropdown = false;
                            }
                            ?>

                            <?php if (!$hideDropdown): ?>
                            <a class="dropdown-item preview-item">
                              <div class="preview-thumbnail">
                                <div class="preview-icon bg-danger" style="color: white; font-weight: bold;">
                                  <i class="mx-0"><?php echo $employeeUT; ?></i>
                                </div>
                              </div>
                              <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal">Undertime Request</h6>
                                <p class="font-weight-light small-text mb-0 text-muted">
                                  <?php echo $formattedTime; ?>
                                </p>
                              </div>
                            </a>
                            <?php endif; ?>

                        <!----------WFH Notif----------->
                        <?php
                            include 'config.php';
                            date_default_timezone_set('Asia/Manila');

                            $startDate = date('Y-m-d', strtotime('last Monday'));
                            $endDate = date('Y-m-d', strtotime('next Sunday'));

                            $query = "SELECT COUNT(*) AS employee_wfh, MAX(`date_file`) AS last_pending FROM wfh_tb WHERE `status` = 'Pending' AND DATE(`date_file`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                              $WFH_row = mysqli_fetch_assoc($query_run);
                              $employeeWFH = $WFH_row['employee_wfh'];
                              $lastPending = $WFH_row['last_pending'];
                              
                              $now = time(); // Current timestamp
                              $pendingTime = strtotime($lastPending); // Convert last_pending to timestamp
                              $timeDiff = $now - $pendingTime; // Difference in seconds

                              if ($timeDiff < 60) {
                                $formattedTime = 'Just now';
                              } elseif ($timeDiff < 3600) {
                                $minutes = floor($timeDiff / 60);
                                $formattedTime = $minutes . ' minute(s) ago';
                              } else {
                                $hours = floor($timeDiff / 3600);
                                if ($hours < 24) {
                                  $formattedTime = $hours . ' hour(s) ago';
                                } else {
                                  $days = floor($hours / 24);
                                  $formattedTime = $days . ' day(s) ago';
                                }
                              }
                            }

                            $hideDropdown = true;

                            $queryHide = "SELECT `status` FROM wfh_tb WHERE `status` = 'Pending' AND DATE(`date_file`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run_hide = mysqli_query($conn, $queryHide);
                            $rowsWFH = mysqli_fetch_assoc($query_run_hide);

                            if ($rowsWFH > 0) {
                              $hideDropdown = false;
                            }
                            ?>

                            <?php if (!$hideDropdown): ?>
                            <a class="dropdown-item preview-item">
                              <div class="preview-thumbnail">
                                <div class="preview-icon bg-danger" style="color: white; font-weight: bold;">
                                  <i class="mx-0"><?php echo $employeeWFH; ?></i>
                                </div>
                              </div>
                              <div class="preview-item-content">
                                <h6 class="preview-subject font-weight-normal">WFH Request</h6>
                                <p class="font-weight-light small-text mb-0 text-muted">
                                  <?php echo $formattedTime; ?>
                                </p>
                              </div>
                            </a>
                            <?php endif; ?>

                      <!----------Official Business Notif----------->
                      <?php
                          include 'config.php';
                          date_default_timezone_set('Asia/Manila');

                          $startDate = date('Y-m-d', strtotime('last Monday'));
                          $endDate = date('Y-m-d', strtotime('next Sunday'));

                          $query = "SELECT COUNT(*) AS employee_OB, MAX(`_dateTime`) AS last_pending FROM emp_official_tb WHERE `status` = 'Pending' AND DATE(`_dateTime`) BETWEEN '$startDate' AND '$endDate'";
                          $query_run = mysqli_query($conn, $query);

                          if (mysqli_num_rows($query_run) > 0) {
                            $OB_row = mysqli_fetch_assoc($query_run);
                            $employeeOB = $OB_row['employee_OB'];
                            $lastPending = $OB_row['last_pending'];
                            
                            $now = time(); // Current timestamp
                            $pendingTime = strtotime($lastPending); // Convert last_pending to timestamp
                            $timeDiff = $now - $pendingTime; // Difference in seconds

                            if ($timeDiff < 60) {
                              $formattedTime = 'Just now';
                            } elseif ($timeDiff < 3600) {
                              $minutes = floor($timeDiff / 60);
                              $formattedTime = $minutes . ' minute(s) ago';
                            } else {
                              $hours = floor($timeDiff / 3600);
                              if ($hours < 24) {
                                $formattedTime = $hours . ' hour(s) ago';
                              } else {
                                $days = floor($hours / 24);
                                $formattedTime = $days . ' day(s) ago';
                              }
                            }
                          }

                          $hideDropdown = true;

                          $queryHide = "SELECT `status` FROM emp_official_tb WHERE `status` = 'Pending' AND DATE(`_dateTime`) BETWEEN '$startDate' AND '$endDate'";
                          $query_run_hide = mysqli_query($conn, $queryHide);
                          $rowsOB = mysqli_fetch_assoc($query_run_hide);

                          if ($rowsOB > 0) {
                            $hideDropdown = false;
                          }
                          ?>

                          <?php if (!$hideDropdown): ?>
                          <a class="dropdown-item preview-item">
                            <div class="preview-thumbnail">
                              <div class="preview-icon bg-danger" style="color: white; font-weight: bold;">
                                <i class="mx-0"><?php echo $employeeOB; ?></i>
                              </div>
                            </div>
                            <div class="preview-item-content">
                              <h6 class="preview-subject font-weight-normal">Official Business</h6>
                              <p class="font-weight-light small-text mb-0 text-muted">
                                <?php echo $formattedTime; ?>
                              </p>
                            </div>
                          </a>
                          <?php endif; ?>

                      <!----------DTR Correction Notif----------->
                      <?php
                            include 'config.php';
                            date_default_timezone_set('Asia/Manila');
                            $startDate = date('Y-m-d', strtotime('last Monday'));
                            $endDate = date('Y-m-d', strtotime('next Sunday'));

                            $query = "SELECT COUNT(*) AS employee_DTR, MAX(`_dateTime`) AS last_pending FROM emp_dtr_tb WHERE `status` = 'Pending' AND DATE(`_dateTime`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0){
                              $DTR_row = mysqli_fetch_assoc($query_run);
                              $employeeDTR = $DTR_row['employee_DTR'];
                              $lastPending = $DTR_row['last_pending'];

                              $now = time(); // Current timestamp
                              $pendingTime = strtotime($lastPending); // Convert last_pending to timestamp
                              $timeDiff = $now - $pendingTime; // Difference in seconds
  
                              if ($timeDiff < 60) {
                                $formattedTime = 'Just now';
                              } elseif ($timeDiff < 3600) {
                                $minutes = floor($timeDiff / 60);
                                $formattedTime = $minutes . ' minute(s) ago';
                              } else {
                                $hours = floor($timeDiff / 3600);
                                if ($hours < 24) {
                                  $formattedTime = $hours . ' hour(s) ago';
                                } else {
                                  $days = floor($hours / 24);
                                  $formattedTime = $days . ' day(s) ago';
                                }
                              }
                              
                            }

                            $hideDropdown = true;

                            $queryHide = "SELECT `status` FROM emp_dtr_tb WHERE `status` = 'Pending' AND DATE(`_dateTime`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run_hide = mysqli_query($conn, $queryHide);
                            $rowsDTR = mysqli_fetch_assoc($query_run_hide);

                            if ($rowsDTR > 0) {
                              $hideDropdown = false;
                          }
                          ?>
                          <?php if (!$hideDropdown): ?>
                          <a class="dropdown-item preview-item">
                          <div class="preview-thumbnail">
                              <div class="preview-icon bg-danger" style="color: white; font-weight: bold;">
                                  <i class="mx-0"><?php echo $employeeDTR; ?></i>
                              </div>
                          </div>
                          <div class="preview-item-content">
                              <h6 class="preview-subject font-weight-normal">DTR Correction</h6>
                              <p class="font-weight-light small-text mb-0 text-muted">
                                  <?php echo $formattedTime; ?>
                              </p>
                          </div>
                      </a>
                      <?php endif; ?>

                      <!----------Leave Notif----------->
                      <?php
                            include 'config.php';
                            date_default_timezone_set('Asia/Manila');
                            $startDate = date('Y-m-d', strtotime('last Monday'));
                            $endDate = date('Y-m-d', strtotime('next Sunday'));

                            $query = "SELECT COUNT(*) AS employee_leave, MAX(`_datetime`) AS last_pending FROM applyleave_tb WHERE `col_status` = 'Pending' AND DATE(`_datetime`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0){
                              $LEAVE_row = mysqli_fetch_assoc($query_run);
                              $employeeLEAVE = $LEAVE_row['employee_leave'];
                              $lastPending = $LEAVE_row['last_pending'];

                              $now = time(); // Current timestamp
                              $pendingTime = strtotime($lastPending); // Convert last_pending to timestamp
                              $timeDiff = $now - $pendingTime; // Difference in seconds
  
                              if ($timeDiff < 60) {
                                $formattedTime = 'Just now';
                              } elseif ($timeDiff < 3600) {
                                $minutes = floor($timeDiff / 60);
                                $formattedTime = $minutes . ' minute(s) ago';
                              } else {
                                $hours = floor($timeDiff / 3600);
                                if ($hours < 24) {
                                  $formattedTime = $hours . ' hour(s) ago';
                                } else {
                                  $days = floor($hours / 24);
                                  $formattedTime = $days . ' day(s) ago';
                                }
                              }
                              
                            }
                            $hideDropdown = true;

                            $queryHide = "SELECT `col_status` FROM applyleave_tb WHERE `col_status` = 'Pending' AND DATE(`_datetime`) BETWEEN '$startDate' AND '$endDate'";
                            $query_run_hide = mysqli_query($conn, $queryHide);
                            $rowsLEAVE = mysqli_fetch_assoc($query_run_hide);

                            if ($rowsLEAVE > 0) {
                              $hideDropdown = false;
                          }
                          ?>
                          <?php if (!$hideDropdown): ?>
                          <a class="dropdown-item preview-item">
                          <div class="preview-thumbnail">
                              <div class="preview-icon bg-danger" style="color: white; font-weight: bold;">
                                  <i class="mx-0"><?php echo $employeeLEAVE; ?></i>
                              </div>
                          </div>
                          <div class="preview-item-content">
                              <h6 class="preview-subject font-weight-normal">Leave Request</h6>
                              <p class="font-weight-light small-text mb-0 text-muted">
                              <?php echo $formattedTime; ?>
                              </p>
                          </div>
                      </a>
                      <?php endif; ?>



                        <!-- <a class="dropdown-item preview-item">
                          <div class="preview-thumbnail">
                            <div class="preview-icon bg-info">
                              <i class="ti-user mx-0"></i>
                            </div>
                          </div>
                          <div class="preview-item-content">
                            <h6 class="preview-subject font-weight-normal">New user registration</h6>
                            <p class="font-weight-light small-text mb-0 text-muted">
                              2 days ago
                            </p>
                          </div>
                        </a> -->
                        
                      </div>
                    </li>
                </div><!---header-notif-->
                
                <div class="header-head">
                <?php 
                        include 'config.php';
                        $employeeID = $_SESSION['empid'];

                        $Supervisor_Profile = "SELECT * FROM employee_tb WHERE `empid` = '$employeeID'";
                        $profileRun = mysqli_query($conn, $Supervisor_Profile);

                        $SuperProfile = mysqli_fetch_assoc($profileRun);
                        $visor_Profile = $SuperProfile['user_profile'];

                        $image_data = "";
                                        
                        if (!empty($visor_Profile)) {
                            $image_data = base64_encode($visor_Profile); // Convert blob to base64
                        } else {
                            // Set default image path when user_profile is empty
                            $image_data = base64_encode(file_get_contents("img/user.jpg"));
                        }
                        
                        $image_type = 'image/jpeg'; // Default image type
                        
                        // Determine the image type based on the blob data
                        if (substr($image_data, 0, 4) === "\x89PNG") {
                            $image_type = 'image/png';
                        } elseif (substr($image_data, 0, 2) === "\xFF\xD8") {
                            $image_type = 'image/jpeg';
                        } elseif (substr($image_data, 0, 4) === "RIFF" && substr($image_data, 8, 4) === "WEBP") {
                            $image_type = 'image/webp';
                        }
                     ?>
                    <img src="data:<?php echo $image_type; ?>;base64,<?php echo $image_data; ?>" alt="" srcset="" style="width: 5em; height:  5em;">
                </div>
                <div class="header-type">
                    <h1 style="color: white;margin-top: 15px; margin-bottom: 20px; text-transform: uppercase"><?php if(empty($_SESSION['username'])){
                                echo "no user!";
                            }else{
                                echo $_SESSION['username'];
                            }
                            ?></h1> 
                    <p class="user-name" style="color: white; margin-top: 10px;"><?php if(empty($_SESSION['role'])){
                                echo "";
                            }else{
                                echo $_SESSION['role'];
                            }
                            ?></p>
                </div>
                <div class="header-dropdown" >
                    <button class="header-dropdown-btn" style="color: white"><span class="fa-solid fa-chevron-down"></span></button>
                    <div class="header-dropdown-menu" style="background-color: #000">
                        <a href="logout.php" class="header-dd-btn" style="text-decoration: none;color: white">Logout</a>
                        <a href="#" style="text-decoration:none; color: white">User Profile</a>
                    </div>
                </div>
            </div>
          <!-- <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
              <i class="icon-bell mx-0"></i>
              <span class="count"></span>
            </a>
            
          </li>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="../../../../images/faces/face28.jpg" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="ti-settings text-primary"></i>
                Settings
              </a>
              <a class="dropdown-item">
                <i class="ti-power-off text-primary"></i>
                Logout
              </a>
            </div>
          </li>
          <li class="nav-item nav-settings d-none d-lg-flex">
            <a class="nav-link" href="#">
              <i class="icon-ellipsis"></i>
            </a>
          </li> -->
        </ul>
        <!-- <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button> -->
      </div>
    </nav> <!-- END UPPER NAV -->
    
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        
      </div>
      <div id="right-sidebar" class="settings-panel">
        
        <div class="tab-content" id="setting-content">
          <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
            <div class="add-items d-flex px-3 mb-0">
              
                <div class="form-group d-flex">
                  
                </div>
             
            </div>
            <div class="list-wrapper px-3">
              
            </div>
           
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                
              </div>
             
            </div>
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                
              </div>
          
            </div>
          </div>
       
        </div>
      </div>

<!-- sidebar -->      
<nav class="sidebar sidebar-offcanvas custom-nav" id="sidebar" style="margin-top: 20px; position:fixed; overflow-y: auto; height: calc(100vh - 40px);">
  <ul class="nav" style="margin-top: 50px; color:red;">
          <li class="nav-item" style="color: black">
            <a class="nav-link" href="dashboard" style="color: white;">
              <i class="icon-grid fa-solid fa-tv" style=""></i>
              <span class="nav-title" style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 500">DASHBOARD</span>
            </a>
          </li>

          <li class="nav-item">
          <a class="nav-link nav-links" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic" style="margin-top: 10px; color:white;">
            <i class="fa-regular fa-clock" id="side-icon"></i>
            <span class="nav-title" style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400; height: 35px">TIMEKEEPING</span>
            <i class=" menu-arrow" style="color: white;"></i>
          </a>
          <div class="collapse" id="ui-basic">
            <ul class="nav flex-column sub-menu" id="sub-menu" style="width: 100%;">
              <li class="nav-item"> <a class="nav-link" href="attendance.php">ATTENDANCE</a></li>
              <li class="nav-item"> <a class="nav-link" href="#">CALENDAR</a></li>
              <li class="nav-item"> <a class="nav-link" href="dtRecords">DAILY TIME RECORDS</a></li>
              <li class="nav-item"> <a class="nav-link" href="dtr_admin">DTR CORRECTION</a></li>
              <li class="nav-item"> <a class="nav-link" href="leaveInfo">LEAVE CREDIT</a></li>
              <li class="nav-item"> <a class="nav-link" href="leaveReq">LEAVE REQUEST</a></li>
              <li class="nav-item"> <a class="nav-link" href="official_business">OFFICIAL BUSINESS</a></li>
              <li class="nav-item"> <a class="nav-link" href="Schedules">SCHEDULES</a></li>
            </ul>
          </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-payroll" aria-expanded="false" aria-controls="ui-payroll" style="margin-top: 5px; color:white">
              <i class="fa-regular fa-credit-card" ></i>
              <span class="nav-title"  style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400; height: 35px" >PAYROLL</span>
              <i class="menu-arrow" style="color: white"></i>
            </a>
            <div class="collapse" id="ui-payroll">
              <ul class="nav flex-column sub-menu" style=" width: 100%;">
                <li class="nav-item"> <a class="nav-link" href="loanRequest">LOAN REQUEST</a></li>
                <li class="nav-item"> <a class="nav-link" href="Dailycompute.php">DAILY COMPUTATION</a></li>
                <li class="nav-item"> <a class="nav-link" href="cutoff">GENERATE PAYROLL</a></li>
                <li class="nav-item"> <a class="nav-link" href="generatePayslip">GENERATE PAYSLIP</a></li>
              </ul>
            </div>
          </li>

          <!-- <li class="nav-item">
            <a class="nav-link" data-bs-toggle="" href="#ui-advanced" aria-expanded="false" aria-controls="ui-advanced" style="margin-top: 5px; color:white">
              <i class=" fa-regular fa-credit-card"></i>
              <span class="nav-title"  style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400; height: 35px" >PAYROLL</span>
              <i class="menu-arrow" style="color: white;"></i>
            </a>
            <div class="collapse" id="ui-advanced">
              <ul class="nav flex-column sub-menu" style=" width: 100%;">
                <li class="nav-item"> <a class="nav-link" href="loanRequest.php">LOAN REQUEST</a></li>
                <li class="nav-item"> <a class="nav-link" href="gnrate_payroll.php">GENERATE PAYROLL</a></li>
                <li class="nav-item"> <a class="nav-link" href="generatePayslip.php">GENERATE PAYSLIP</a></li>
              </ul>
            </div>
          </li> -->

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-emp" aria-expanded="false" aria-controls="ui-emp" style="margin-top: 5px; color:white">
              <i class=" fa-solid fa-users" ></i>
              <span class="nav-title"  style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400; height: 35px" >EMPLOYEES</span>
              <i class="menu-arrow" style="color: white"></i>
            </a>
            <div class="collapse" id="ui-emp">
              <ul class="nav flex-column sub-menu" style=" width: 100%;">
                <li class="nav-item"> <a class="nav-link" href="EmployeeList">EMPLOYEE LIST</a></li>
                <li class="nav-item"> <a class="nav-link" href="employeeRequest">EMPLOYEE REQUEST</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-reports" aria-expanded="false" aria-controls="ui-reports" style="margin-top: 5px; color:white">
              <i class="fa-regular fa-clipboard"></i>
              <span class="nav-title"  style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400; height: 35px" >REPORTS</span>
              <i class="menu-arrow" style="color: white"></i>
            </a>
            <div class="collapse" id="ui-reports">
              <ul class="nav flex-column sub-menu" style=" width: 100%;">
                <li class="nav-item"> <a class="nav-link" href="attendance_report">ATTENDANCE</a></li>
                <li class="nav-item"> <a class="nav-link" href="payroll_report">PAYROLL</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-develop" aria-expanded="false" aria-controls="ui-develop" style="margin-top: 5px; color:white"  >
              <i class="fa-regular fa-lightbulb"></i>
              <span class="nav-title"  style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400; height: 35px" >DEVELOPMENT</span>
              <i class="menu-arrow" style="color: white"></i>
            </a>
            <div class="collapse" id="ui-develop">
              <ul class="nav flex-column sub-menu" style=" width: 100%;">
                <li class="nav-item" style="color: white"> <a class="nav-link" href="#">TRAINING PROGRAM</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-perf" aria-expanded="false" aria-controls="ui-perf" style="margin-top: 5px; color:white">
              <i class="fa-solid fa-person-running"></i>
              <span class="nav-title"  style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400; height: 35px" >PERFORMANCE</span>
              <i class="menu-arrow" style="color: white"></i>
            </a>
            <div class="collapse" id="ui-perf">
              <ul class="nav flex-column sub-menu" style=" width: 100%;">
                <li class="nav-item"> <a class="nav-link" href="#">EVALUATION</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">PERFORMANCE RATE</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-acquisition" aria-expanded="false" aria-controls="ui-acquisition" style="margin-top: 5px; color:white">
              <i class="fa-solid fa-chart-line"></i>
              <span class="nav-title"  style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400; height: 35px" >ACQUISITION</span>
              <i class="menu-arrow" style="color: white"></i>
            </a>
            <div class="collapse" id="ui-acquisition">
              <ul class="nav flex-column sub-menu" style=" width: 100%;">
                <li class="nav-item"> <a class="nav-link" href="#">VACANCIES</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">APPLICATION</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">ASSESSMENT</a></li>
                <li class="nav-item"> <a class="nav-link" href="#">MANPOWER</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-org" aria-expanded="false" aria-controls="ui-org" style="margin-top: 5px; color:white">
              <i class="fa-regular fa-building"></i>
              <span class="nav-title"  style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400; height: 35px" >ORGANIZATION</span>
              <i class="menu-arrow" style="color: white"></i>
            </a>
            <div class="collapse" id="ui-org">
              <ul class="nav flex-column sub-menu" style=" width: 100%;">
                <li class="nav-item"> <a class="nav-link" href="Branch">BRANCH</a></li>
                <li class="nav-item"> <a class="nav-link" href="Department">DEPARTMENT</a></li>
                <li class="nav-item"> <a class="nav-link" href="Position">POSITION</a></li>
                <li class="nav-item"> <a class="nav-link" href="Classification">CLASSIFICATION</a></li>
                <li class="nav-item"> <a class="nav-link" href="companyCode">COMPANY CODE</a></li>
               
              </ul>
            </div>
          </li>

          <li class="nav-item" id="piece_hide_show" >
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-pakyawan" aria-expanded="false" aria-controls="ui-pakyawan" style="margin-top: 5px; color:white">
              <i class="fa-solid fa-gear"></i>
              <span class="nav-title"  style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400" >PAKYAWAN</span>
              <i class="menu-arrow" style="color: white"></i>
            </a>
            <div class="collapse" id="ui-pakyawan">
              <ul class="nav flex-column sub-menu" style=" width: 100%;">
              <!-- <li class="nav-item"> <a class="nav-link" href="pakyawanEmpList">EMPLOYEE LIST</a></li> -->
                <li class="nav-item"> <a class="nav-link" href="Piece_rate">PIECE RATE</a></li>
                <li class="nav-item"> <a class="nav-link" href="pakyawan_work">SET WORK LOAD</a></li>
                <li class="nav-item"> <a class="nav-link" href="pakyawan_payroll">PAYROLL</a></li>
                <li class="nav-item"> <a class="nav-link" href="cash_advance">CASH ADVANCE</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-settings" aria-expanded="false" aria-controls="ui-settings" style="margin-top: 5px; color:white">
              <i class="fa-solid fa-gear"></i>
              <span class="nav-title"  style="font-size: 21px; margin-left: 15px; font-family: Arial, sans-serif; font-weight: 400" >SETTINGS</span>
              <i class="menu-arrow" style="color: white"></i>
            </a>
            <div class="collapse" id="ui-settings">
              <ul class="nav flex-column sub-menu" style=" width: 100%;">
                <li class="nav-item"> <a class="nav-link" href="settings">GENERAL SETTINGS</a></li>
                <li class="nav-item"> <a class="nav-link" href="user_profile">USER PROFILE</a></li>
                
              </ul>
            </div>
          </li>


        </ul>
      </nav>

      <script>
        // Fetch the piece_rate_toggle value from the PHP code
        var pieceRateToggle = "<?php echo $pieceRateToggle; ?>";

        // Get the div element with the id "piece_hide_show"
        var pieceHideShowDiv = document.getElementById("piece_hide_show");

        // Check the value of pieceRateToggle and set the display style accordingly
        if (pieceRateToggle === "Hidden") {
            pieceHideShowDiv.style.display = "none";
        } else {
            pieceHideShowDiv.style.display = "block";
        }
    </script>

     

 
</body>
</html>