<!-- <head>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.css"> 
</head> -->

<?php

session_start();

include 'config.php';
                            
$id = $_SESSION['id'];
$sql = "SELECT * FROM employee_tb WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
    if($result->num_rows > 0){
        $row = mysqli_fetch_assoc($result);
    }

?>
<body>


        <div class="header-container">
            <div class="header-logo">
                <h1><a href="Dashboard.php"></a> HRIS</h1>
            </div>

            <div class="header-user">
                <div class="header-notif">
                    <span class="fa-regular fa-bell"></span>
                </div>
                <div class="header-head">   
                    <img src="img/user.jpg" alt="" srcset="">
                </div>
                <div class="header-type">
                    <h1>
                        <?php 
                        if(empty($row['role'])){
                            echo "No role";
                                } else {
                                    echo $row['role'];
                                }
                        ?></h1>
                    <p class="user-name">
                        
                        
                    <?php if(empty($row['fname'])){
                            echo "No user type";
                        } else {
                            echo $row['empid'] . ' - ' .$row['fname'] . ' ' . $row['lname'];
                        }
                        
                        ?>
                    </p>
                    
                    
                </div>
                <div class="header-dropdown">
                    <button class="header-dropdown-btn"><span class="fa-solid fa-chevron-down"></span></button>
                    <div class="header-dropdown-menu">
                        <a href="logout.php">Logout</a>
                        <a href="#">User Profile</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebars" style="margin-top: -30px;">
            <ul class="first-ul">
                <li><a href="Dashboard.php" class="hoverable"><div><span class="fa-solid fa-tv"></span>DASHBOARD</div></a></li>

                <li><a href="#" class="timekeep-dd hoverable"><div><span class="fa-regular fa-clock"></span>SCHEDULE</div><span class="fa-solid fa-chevron-right"></span></a>
                    <ul class="timekeep-dd-show">
                        <li> <a href="my_schedule.php">MY SCHEDULE</a></li>
                        <li> <a href="overtime_req.php"> OVERTIME REQUEST</a></li>
                        <li> <a href="undertime_req.php">UNDERTIME REQUEST</a></li>
                        <li> <a href="Wfh_request.php">WFH REQUEST</a></li>
                        <li> <a href="official_emp.php">OFFICIAL BUSINESS</a></li>
                        <li> <a href="dtr_emp.php">DTR CORRECTION</a></li>
                    </ul>
                </li>
                  
                <li><a href="attendance.php" class="hoverable"><div><span class="fa-regular fa-calendar"></span>ATTENDANCE</div></a></li>
        
                </li>

                <li><a href="#" class="hoverable employees-dd"><div><span class="fa-regular fa-envelope"></span>LEAVES</div><span class="fa-solid fa-chevron-right"></span></a>
                    <ul class="employees-dd-show">
                        <li><a href="leaveReq.php">LEAVE REQUEST</a></li>
                        <li><a href="#">EMPLOYEE REQUEST</a></li>
                    </ul>
                </li>

                <li><a href="Dashboard.php" class="hoverable" style="font-size: 17px;"><div><span class="far fa-credit-card"></span>COMPENSATION/BENEFITS</div></a></li>

                <li><a href="Dashboard.php" class="hoverable"><div><span class="fa-regular fa-file"></span>DOCUMENT REQUEST</div></a></li>

                <li><a href="#" class="hoverable performance-dd"><div><span class="fa-solid fa-person-running"></span>PERFORMANCE</div><span class="fa-solid fa-chevron-right"></span></a>
                    <ul class="performance-dd-show">
                        <li><a href="#">EVALUATION</a></li>
                        <li><a href="#">PERFORMANCE RATE</a></li>
                    </ul>
                </li>
                
            </ul>
        </div>
    


        <script>
       $('.timekeep-dd').click(function(){
            $('.sidebars ul .timekeep-dd-show').toggleClass("show");
            
        });

        $('.payroll-dd').click(function(){
            $('.sidebars ul .payroll-dd-show').toggleClass("show2");
            
        });
        
        $('.employees-dd').click(function(){
            $('.sidebars ul .employees-dd-show').toggleClass("show3");
            
        });

        $('.report-dd').click(function(){
            $('.sidebars ul .report-dd-show').toggleClass("show4");
            
        });

        $('.development-dd').click(function(){
            $('.sidebars ul .development-dd-show').toggleClass("show5");
            
        });

        $('.performance-dd').click(function(){
            $('.sidebars ul .performance-dd-show').toggleClass("show6");
            
        });

        $('.acquisition-dd').click(function(){
            $('.sidebars ul .acquisition-dd-show').toggleClass("show7");
            
        });
        $('.org-dd').click(function(){
            $('.sidebars ul .org-dd-show').toggleClass("show7");
            
        });

        $('.sett-dd').click(function(){
            $('.sidebars ul .sett-dd-show').toggleClass("show8");
            
        });
        
        $('.header-dropdown-btn').click(function(){
            $('.header-dropdown .header-dropdown-menu').toggleClass("show-header-dd");
            
        });
    </script>


    <!-- <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
    <script src="main.js"></script> -->
</body>