<!-- <head>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.css"> 
</head> -->

<head>
    <link rel="stylesheet" href="css/header.css">
</head>
<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php"); 
    } else {
        // Check if the user's role is not "admin"
        if($_SESSION['role'] != 'admin'){
            // If the user's role is not "admin", log them out and redirect to the logout page
            session_unset();
            session_destroy();
            header("Location: logout.php");
            exit();
        }
    }
?>
<body>


        <div class="header-container">
            <div class="header-logo">
                <img id="logo" src="img/Slash Tech Solutions.png" class="logo" alt="" srcset="" >  
            </div>

            <div class="header-user">
                <div class="header-notif">
                    <span class="fa-regular fa-bell" style="color: white;"></span>
                </div>
                <div class="header-head">
                    <img src="img/user.jpg" alt="" srcset="">
                </div>
                <div class="header-type">
                    <h1 style="color: white;margin-top: 15px;"><?php if(empty($_SESSION['userType'])){
                            echo "No user ";
                        } else {
                            echo $_SESSION['userType'];
                        }
                        
                        ?></h1>
                    <p class="user-name" style="color: white; margin-top: 1px;"><?php if(empty($_SESSION['username'])){
                                echo "";
                            }else{
                                echo $_SESSION['username'];
                            }
                            ?></p>
                </div>
                <div class="header-dropdown" >
                    <button class="header-dropdown-btn" style="color: white"><span class="fa-solid fa-chevron-down"></span></button>
                    <div class="header-dropdown-menu" style="background-color: #555">
                        <a href="logout.php" class="header-dd-btn" style="text-decoration: none;color: white">Logout</a>
                        <a href="#" style="text-decoration:none; color: white">User Profile</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebars">
            <ul class="first-ul">
                <li><a href="Dashboard.php" class="hoverable"><div><span class="fa-solid fa-tv"></span>DASHBOARD</div></a></li>

                <li><a href="#" class="timekeep-dd hoverable"><div><span class="fa-regular fa-clock"></span>TIMEKEEPING</div><span class="fa-solid fa-chevron-right"></span></a>
                    <ul class="timekeep-dd-show">
                        <li> <a href="attendance.php"> ATTENDANCE</a></li>
                        <li> <a href="#"> CALENDAR</a></li>
                        <li> <a href="dtRecords.php"> DAILY TIME RECORDS</a></li>
                        <li> <a href="dtr_admin.php"> DTR CORRECTION</a></li>
                        <li> <a href="leaveInfo.php"> LEAVES INFORMATION</a></li>
                        <li> <a href="leaveReq.php"> LEAVE REQUEST</a></li>
                        <li> <a href="official_business.php"> OFFICIAL BUSINESS</a></li>
                        <li> <a href="Schedules.php"> SCHEDULES</a></li>
                    </ul>
                </li>
                  
                <li><a href="#" class="hoverable payroll-dd"><div><span class="fa-regular fa-credit-card"></span>PAYROLL</div><span class="fa-solid fa-chevron-right"></span></a>
                    <ul class="payroll-dd-show">
                        <li><a href="loanRequest.php">LOAN REQUEST</a></li>
                        <li><a href="cutoff.php">GENERATE PAYROLL</a></li>
                        <li><a href="generatePayslip.php">GENERATE PAYSLIP</a></li>
                    </ul> 
                </li>

                <li><a href="#" class="hoverable employees-dd"><div><span class="fa-solid fa-users"></span>EMPLOYEES</div><span class="fa-solid fa-chevron-right"></span></a>
                    <ul class="employees-dd-show">
                        <li><a href="EmployeeList.php">EMPLOYEE LIST</a></li>
                        <li><a href="#">EMPLOYEE REQUEST</a></li>
                    </ul>
                </li>

                <li><a href="#" class="hoverable report-dd"><div><span class="fa-regular fa-clipboard"></span>REPORTS</div><span class="fa-solid fa-chevron-right"></span></a>
                    <ul class="report-dd-show">
                        <li><a href="#">ATTENDANCE</a></li>
                        <li><a href="#">PAYROLL</a></li>
                    </ul>
                </li>

                <li><a href="#" class="hoverable development-dd"><div><span class="fa-regular fa-lightbulb"></span>DEVELOPMENT</div><span class="fa-solid fa-chevron-right"></span></a>
                    <ul class="development-dd-show">
                        <li><a href="#">TRAINING PROGRAM</a></li>
                    </ul>
                </li>

                <li><a href="#" class="hoverable performance-dd"><div><span class="fa-solid fa-person-running"></span>PERFORMANCE</div><span class="fa-solid fa-chevron-right"></span></a>
                    <ul class="performance-dd-show">
                        <li><a href="#">EVALUATION</a></li>
                        <li><a href="#">PERFORMANCE RATE</a></li>
                    </ul>
                </li>

                <li><a href="#" class="hoverable acquisition-dd"><div><span class="fa-solid fa-chart-line"></span>ACQUISITION</div><span class="fa-solid fa-chevron-right"></span></a>
                    <ul class="acquisition-dd-show">
                        <li><a href="#">VACANCIES</a></li>
                        <li><a href="#">APPLICATION</a></li>
                        <li><a href="#">ASSESSMENT</a></li>
                        <li><a href="#">MANPOWER</a></li>
                    </ul>
                </li>

                <li><a href="#" class="hoverable org-dd"><div><span class="fa-regular fa-building"></span>ORGANIZATION</div><span class="fa-solid fa-chevron-right"></span></a>
                    <ul class="org-dd-show">
                        <li><a href="Branch.php">BRANCH</a></li>
                        <li><a href="Department.php">DEPARTMENT</a></li>
                        <li><a href="Position.php">POSITION</a></li>
                        
                    </ul>
                </li>

                <li><a href="#" class="hoverable sett-dd"><div><span class="fa-solid fa-gear"></span>SETTINGS</div><span class="fa-solid fa-chevron-right"></span></a>
                <ul class="sett-dd-show">
                        
                    </ul>
                </li>
                
            </ul>
        </div>
    


        <script>
        $('.timekeep-dd').click(function(){
        $('.sidebars ul .timekeep-dd-show').toggleClass("show");
        $('.sidebars ul .payroll-dd-show').removeClass("show2");
        $('.sidebars ul .employees-dd-show').removeClass("show3");
        $('.sidebars ul .report-dd-show').removeClass("show4");
        $('.sidebars ul .development-dd-show').removeClass("show5");
        $('.sidebars ul .performance-dd-show').removeClass("show6");
        $('.sidebars ul .acquisition-dd-show').removeClass("show7");
        $('.sidebars ul .org-dd-show').removeClass("show7");
        $('.sidebars ul .sett-dd-show').removeClass("show8");
    });

    $('.payroll-dd').click(function(){
        $('.sidebars ul .payroll-dd-show').toggleClass("show2");
        $('.sidebars ul .timekeep-dd-show').removeClass("show");
        $('.sidebars ul .employees-dd-show').removeClass("show3");
        $('.sidebars ul .report-dd-show').removeClass("show4");
        $('.sidebars ul .development-dd-show').removeClass("show5");
        $('.sidebars ul .performance-dd-show').removeClass("show6");
        $('.sidebars ul .acquisition-dd-show').removeClass("show7");
        $('.sidebars ul .org-dd-show').removeClass("show7");
        $('.sidebars ul .sett-dd-show').removeClass("show8");
    });

    $('.employees-dd').click(function(){
        $('.sidebars ul .employees-dd-show').toggleClass("show3");
        $('.sidebars ul .timekeep-dd-show').removeClass("show");
        $('.sidebars ul .payroll-dd-show').removeClass("show2");
        $('.sidebars ul .report-dd-show').removeClass("show4");
        $('.sidebars ul .development-dd-show').removeClass("show5");
        $('.sidebars ul .performance-dd-show').removeClass("show6");
        $('.sidebars ul .acquisition-dd-show').removeClass("show7");
        $('.sidebars ul .org-dd-show').removeClass("show7");
        $('.sidebars ul .sett-dd-show').removeClass("show8");
    });

    $('.report-dd').click(function(){
        $('.sidebars ul .report-dd-show').toggleClass("show4");
        $('.sidebars ul .timekeep-dd-show').removeClass("show");
        $('.sidebars ul .payroll-dd-show').removeClass("show2");
        $('.sidebars ul .employees-dd-show').removeClass("show3");
        $('.sidebars ul .development-dd-show').removeClass("show5");
        $('.sidebars ul .performance-dd-show').removeClass("show6");
        $('.sidebars ul .acquisition-dd-show').removeClass("show7");
        $('.sidebars ul .org-dd-show').removeClass("show7");
        $('.sidebars ul .sett-dd-show').removeClass("show8");
    });

    $('.development-dd').click(function(){
        $('.sidebars ul .development-dd-show').toggleClass("show5");
        $('.sidebars ul .timekeep-dd-show').removeClass("show");
        $('.sidebars ul .payroll-dd-show').removeClass("show2");
        $('.sidebars ul .employees-dd-show').removeClass("show3");
        $('.sidebars ul .report-dd-show').removeClass("show4");
        $('.sidebars ul .performance-dd-show').removeClass("show6");
        $('.sidebars ul .acquisition-dd-show').removeClass("show7");
        $('.sidebars ul .org-dd-show').removeClass("show7");
        $('.sidebars ul .sett-dd-show').removeClass("show8");
    });

    $('.performance-dd').click(function(){
        $('.sidebars ul .performance-dd-show').toggleClass("show6");
        $('.sidebars ul .timekeep-dd-show').removeClass("show");
        $('.sidebars ul .payroll-dd-show').removeClass("show2");
        $('.sidebars ul .employees-dd-show').removeClass("show3");
        $('.sidebars ul .report-dd-show').removeClass("show4");
        $('.sidebars ul .development-dd-show').removeClass("show5");
        $('.sidebars ul .acquisition-dd-show').removeClass("show7");
        $('.sidebars ul .org-dd-show').removeClass("show7");
        $('.sidebars ul .sett-dd-show').removeClass("show8");
    });

    $('.acquisition-dd').click(function(){
        $('.sidebars ul .acquisition-dd-show').toggleClass("show7");
        $('.sidebars ul .timekeep-dd-show').removeClass("show");
        $('.sidebars ul .payroll-dd-show').removeClass("show2");
        $('.sidebars ul .employees-dd-show').removeClass("show3");
        $('.sidebars ul .report-dd-show').removeClass("show4");
        $('.sidebars ul .development-dd-show').removeClass("show5");
        $('.sidebars ul .performance-dd-show').removeClass("show6");
        $('.sidebars ul .org-dd-show').removeClass("show7");
        $('.sidebars ul .sett-dd-show').removeClass("show8");
    });

    $('.org-dd').click(function(){
        $('.sidebars ul .org-dd-show').toggleClass("show7");
        $('.sidebars ul .timekeep-dd-show').removeClass("show");
        $('.sidebars ul .payroll-dd-show').removeClass("show2");
        $('.sidebars ul .employees-dd-show').removeClass("show3");
        $('.sidebars ul .report-dd-show').removeClass("show4");
        $('.sidebars ul .development-dd-show').removeClass("show5");
        $('.sidebars ul .performance-dd-show').removeClass("show6");
        $('.sidebars ul .acquisition-dd-show').removeClass("show7");
        $('.sidebars ul .sett-dd-show').removeClass("show8");
    });

    $('.sett-dd').click(function(){
        $('.sidebars ul .sett-dd-show').toggleClass("show8");
        $('.sidebars ul .timekeep-dd-show').removeClass("show");
        $('.sidebars ul .payroll-dd-show').removeClass("show2");
        $('.sidebars ul .employees-dd-show').removeClass("show3");
        $('.sidebars ul .report-dd-show').removeClass("show4");
        $('.sidebars ul .development-dd-show').removeClass("show5");
        $('.sidebars ul .performance-dd-show').removeClass("show6");
        $('.sidebars ul .acquisition-dd-show').removeClass("show7");
        $('.sidebars ul .org-dd-show').removeClass("show7");
    });

    $('.header-dropdown-btn').click(function(){
        $('.header-dropdown .header-dropdown-menu').toggleClass("show-header-dd");
    });
    </script>


    <!-- <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
    <script src="main.js"></script> -->
</body>