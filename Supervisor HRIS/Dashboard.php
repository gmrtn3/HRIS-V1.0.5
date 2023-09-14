<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php"); 
    } else {
        // Check if the user's role is not "admin"
        if($_SESSION['role'] != 'Supervisor'){
            // If the user's role is not "admin", log them out and redirect to the logout page
            session_unset();
            session_destroy();
            header("Location: logout.php");
            echo "<script> alert('Something went wrong.'); </script>";
            exit();
        }
    }

include 'config.php';
    $approver_ID = $_SESSION['empid'];
    $sql = "SELECT COUNT(*) AS employee_count
        FROM employee_tb
        INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
        WHERE approver_tb.approver_empid = $approver_ID";
    
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    }
    
    $row = mysqli_fetch_assoc($result);
    $employee_count = $row["employee_count"];
    
    mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/ti-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
    <!-- Link to the MDI CSS file -->
    <!-- <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css"> -->
    <!-- Import the MDI font files using @font-face -->
    <!-- inject:css -->
    <!-- <link rel="stylesheet" href="bootstrap/vertical-layout-light/style.css"> -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="bootstrap/vertical-layout-light/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/styles.css">


    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>



<!-- skydash -->

<link rel="stylesheet" href="skydash/feather.css">
    <link rel="stylesheet" href="skydash/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="skydash/vendor.bundle.base.css">

    <link rel="stylesheet" href="skydash/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">

    <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"
/>

<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css">


    <style>
    @font-face {
        font-family: 'Material Design Icons';
        font-style: normal;
        font-weight: 400;
        src: url('https://cdn.materialdesignicons.com/5.4.55/fonts/materialdesignicons-webfont.woff2?v=5.4.55') format('woff2'),
            url('https://cdn.materialdesignicons.com/5.4.55/fonts/materialdesignicons-webfont.woff?v=5.4.55') format('woff');
    }
    </style>
    <title>HRIS | Dashboard</title>
</head>
<body >
    <header>
        <?php include("header.php")?>
    </header>

    <style>
    html{
        background-color: #f4f4f4 !important; 
        overflow: hidden;
    }
    body{
        overflow: hidden;
        background-color: #F4F4F4 !important;
    }

    .sidebars ul li{
        list-style: none;
        text-decoration:none;
        width: 287px;
        margin-left:-16px;
        line-height:30px;
       
    }

    .sidebars ul li .hoverable{
        height:55px;
    }

    .sidebars ul{
        height:100%;
    }

    .sidebars .first-ul{
        line-height:60px;
        height:100px;
    }

    .sidebars ul li ul li{
        width: 100%;
    }

    .card-body{
        width: 99.8%;
        box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);
    }

    .card-header{
        width: 99.8%;
        box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);
    }

    .swiper-button-next:after, .swiper-button-prev:after {
            font-size: 1em;
        }


        .user-icon{
            height: 100em !important;
        }
    
    @media(max-width: 1350px){
        html{
            background-color: #fff !important;
            overflow: scroll;
        }


     .dashboard-content{
        background-color: #fff !important;
     }

     .sidebar{
        background-color: #fff !important;
     }
     /* heder-user*/   
    .header-user{
       width: 400px;
       margin-right: -50px;
       transition: ease-in-out 1s;
    }

    .header-notif{
        margin-right: 30px;
        transition: ease-in-out 1s;
    }
    .header-head{
        margin-right: 25px;
        transition: ease-in-out 1s;
        
    }
    .header-head img{
        height: 50px;
        transition: ease-in-out 1s;
    }
    .header-type h1{
        font-size: 20px;
        transition: ease-in-out 1s;
    }
    .header-type p{
        font-size: 16px;
        transition: ease-in-out 1s;
    }
    .header-dropdown{
        margin-right: 30px;
        transition: ease-in-out 1s;
    }


    .dashboard-content{
        border: none;
        height: 1750px;
        transition: ease-in-out 1s;
    }
    
    .dashboard-contents{
        display:flex;
        flex-direction: column;
        transition: ease-in-out 1s;
    }


    /* first-dash-contents */
    .first-dash-contents{
        display:flex;
        flex-direction: column;
        align-items: center;
        margin-left: 35px;
        transition: ease-in-out 1s;
        
       
        
    }

    .emp-request-list-container{
        margin-top: 50px !important;
        margin-right: 20px !important;
        transition: ease-in-out 1s;
    }

    .employee-status-overview{
        margin-top: 20px !important;
        transition: ease-in-out 1s;
    }



    /*end of first-dash */


    /*second-dash-contents*/
    .second-dash-contents{
        margin: auto;
        display:flex;
        flex-direction: column;
        align-items: center;
        margin-left: 30px;
        transition: ease-in-out 1s;
        width: 93% !important;
    }

    .announcement-container{
        transition: ease-in-out 1s;
        width: 88% !important;
    }

    .event-container{
        margin-top: 30px !important;
        width: 88% !important;
        transition: ease-in-out 1s;
    }

    .event-content{
        width: 100% !important;
        transition: ease-in-out 1s;
    }

    /* end of second dash*/
    
  }
  

  @media(max-width: 500px){

html{
    overflow-x: hidden !important;
    background-color: white !important;
}
body{
    overflow-y: hidden !important;
    background-color: #fff !important;
    width: 500px !important;
}
#upper-nav{
    background-color: black !important; 
    width: 500px !important;
    height: 75px;
    position: fixed !important;
}

.navbar-menu-wrapper{
    background-color: black !important;
    width: 390px !important;
    height: 60px !important;
    position: fixed !important;
   
    margin-left: 70px !important;
    
    
}

.navbar-brand-wrapper{
    background-color: black !important;
    position: absolute !important;
    width: 100px !important;
    box-shadow: none;
    height: 60px !important;
    z-index: 100;
}

.navbar-brand-wrapper img{
    height: 40px !important;
    width: 50px !important;
    margin-left: -20px !important;
}

.sidebar{
    position: fixed !important;
    left: 0;
    width: 85px;
    margin-top: 0 !important;
    height: 130vh !important;
    display: none !important;
    transition: ease-in-out 1s !important;
    
}

#sidebar.active-sidebar {
display: block !important;
} 


.navbar-toggler{
    display: none !important;
}

.responsive-bars-btn{
    display: block !important;
    margin-right: 90px !important;
    border: none !important;
    
}

.responsive-bars-btn span{
    color: white !important;
    font-size: 18px !important;
}

.header-user{
   width: 290px;
   transition: ease-in-out 1s;
   background-color: black !important;
   
}
.header-notif span{
    font-size: 20px !important;
    margin-right: -10px;
    margin-left: 30px !important;
}
.header-head img{
    height: 40px !important;
}
.header-type h1{
    font-size: 19px !important;
}
.header-type p{
    font-size: 14px !important;
    margin-top: -25px !important;
}
.header-dropdown{
    margin-left: 30px !important;
}
.header-dropdown-menu{
    width: 130px !important;
    margin-left: -55px !important;
   
}

.nav-title,.menu-arrow{
    display: none !important;
}

.nav-item{
    margin-bottom: 15px !important;
}

.collapse{
    width: 250px !important;
    position: fixed !important;
    left: 85px !important;
}

.dashboard-content{
    width: 390px;
    
    margin-left: 0px !important;
    margin-top: -20px !important;
}

.dashboard-title{
    
}

.dashboard-title h1{
    margin-left: 20px;
}

.dashboard-contents{
    
}

.first-dash-contents{
    width: 500px !important;
    margin: auto !important;
}

.employee-status-overview{
    width: 100% !important;
}

.emp-status-title{
   margin-left: -10px !important;
}

.emp-status-container{
   
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
    align-items: center !important;
    height: 900px !important;
}

.emp-status-container div{
    width: 340px !important;
}

.emp-status-container div:nth-child(1){
    background-color: #000080 !important;
    
}

.emp-status-container div:nth-child(1) input{
color: white;
font-size: 35px !important;
margin-left: 10px !important;
margin-top: -5px !important;
}

.emp-status-container div:nth-child(1) label{
color: white;
margin-left: 10px !important;
font-size: 19px !important;
}   

.emp-status-container div:nth-child(1) p{
color: white;
margin-left: 10px !important;
margin-top: 5px !important; 
font-size: 19px !important;
} 

.emp-status-container div:nth-child(1) span{
    font-size: 19px !important;
    margin-left: 3px !important;
    color: black !important;
}


.emp-status-container div:nth-child(2){
    background-color: #F3797E !important; 
}

.emp-status-container div:nth-child(2) input{
color: white;
font-size: 35px !important;
margin-left: 10px !important;
margin-top: -5px !important;
}

.emp-status-container div:nth-child(2) label{
color: white;
margin-left: 10px !important;
font-size: 19px !important;
}   

.emp-status-container div:nth-child(2) p{
color: white;
margin-left: 10px !important;
margin-top: 5px !important; 
font-size: 19px !important;
} 

.emp-status-container div:nth-child(2) span{
    font-size: 19px !important;
    margin-left: 3px !important;
    color: black !important;
}

.emp-status-container div:nth-child(3){
    background-color: #4747A1 !important;
}

.emp-status-container div:nth-child(3) input{
color: white;
font-size: 35px !important;
margin-left: 10px !important;
margin-top: -5px !important;
}

.emp-status-container div:nth-child(3) label{
color: white;
margin-left: 10px !important;
font-size: 19px !important;
}   

.emp-status-container div:nth-child(3) p{
color: white;
margin-left: 10px !important;
margin-top: 5px !important; 
font-size: 19px !important;
} 

.emp-status-container div:nth-child(3) span{
    font-size: 19px !important;
    color: black !important;
    margin-left: 3px !important;
}

.emp-status-container div:nth-child(4){
    background-color: #7978E9 !important;
}

.emp-status-container div:nth-child(4) input{
color: white;
font-size: 35px !important;
margin-left: 10px !important;
margin-top: -5px !important;
}

.emp-status-container div:nth-child(4) label{
color: white;
margin-left: 10px !important;
font-size: 19px !important;
}   

.emp-status-container div:nth-child(4) p{
color: white;
margin-left: 10px !important;
margin-top: 5px !important; 
font-size: 19px !important;
} 

.emp-status-container div:nth-child(4) .wfh-color{
    font-size: 19px !important;
    margin-left: 3px !important;
    color: black !important;
}

.emp-status-container div:nth-child(5){
    background-color: #98BDFF !important;
}
.emp-status-container div:nth-child(5) input{
color: white;
font-size: 35px !important;
margin-left: 10px !important;
margin-top: -5px !important;
}

.emp-status-container div:nth-child(5) label{
color: white;
margin-left: 10px !important;
font-size: 19px !important;
}   

.emp-status-container div:nth-child(5) p{
color: white;
margin-left: 10px !important;
margin-top: 5px !important; 
font-size: 19px !important;
} 

.emp-status-container div:nth-child(5) span{
    font-size: 19px !important;
    margin-left: 3px !important;
    color: black !important;
}

.emp-request-list-container{
    width: 500px !important;
    
}

.emp-btn-container{
    width: 90% !important;
    margin:auto !important;

}

.emp-request-btn{
    position: relative !important;
    display: flex;
    justify-content: space-between;
   
    width: 95% !important;
    margin:auto !important;
}

/* .emp-request-btn div:nth-child(1){
    margin-left: 20px !important;
} */

.emp-request-btn div:nth-child(1) p{
    font-size: 10px !important;
   
    height: 25px !important;
    width: 25px !important;
}

.emp-request-btn div:nth-child(1) div{
    margin-left: 0px !important;
}

.emp-request-btn div:nth-child(2){
    margin-top: 5px !important;
    margin-left: -3px !important;
}

.emp-request-btn div:nth-child(3){
    margin-top: 5px !important;

}

.emp-request-btn div:nth-child(4){
    margin-top: 5px !important;
    margin-right: 5px !important;
}

.emp-request-btn div button{
    font-size: 13px !important;
}

.emp-request-table{
    width: 90% !important;
    margin-left: 11px !important;
    margin: auto !important;
}

.dash

.dash-responsive-btn{
    margin-top: 16px !important;
    display: block !important;
    margin-right: 40px !important;
}

.request-list-dropdown {
    display: none;
    margin-top: 10px;
    background-color: #f4f4f4 !important;
    position: absolute !important;
    right: -10px !important;
    bottom: -115px !important;
    width: 130px !important;
    border-radius: 7px !important;
    padding: 10px !important;
    box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17);
 }

 .request-list-dropdown button{
    font-size: 18px !important;
    font-weight: 500 !important;
 }
 
.emp-request-btn .fa-chevron-down {
    transition: transform 0.3s;
}

.emp-request-btn.active .fa-chevron-down {
    transform: rotate(180deg);
}

.request-table{
   
    margin-left: 10px !important;
    width: 95% !important; 
}

/* second content */
.second-dash-contents{
    display:flex;
    flex-direction: column;
    align-items: center;
    transition: ease-in-out 1s;
    margin:auto !important;
    margin-top: 20px !important;
    
    width: 500px !important;
    
}

.announcement-container{
    
    width: 485px !important;
    margin: auto !important;
    margin-left: 5px !important;
}

.announce-title{
    width: 93% !important;
    margin: auto !important;
    margin-left: 12px !important;
}

.announce-content{
    width: 100% !important;
    margin: auto !important;
    margin-top: 20px !important;
}

.announce-content button{
    font-size: 16px !important;
}

.announce-content h4{
    font-size: 26px !important;
}

.announce-content p{
    font-size: 14px !important;
}

.event-container{
    margin-bottom: 25px !important;
    width: 95% !important;
    margin-left: 0px !important;
}
.event-title{
    width: 95% !important;
    margin: auto !important;
}
.event-content{
    width: 96% !important;
    margin: auto !important;
}

.header-dropdown-menu a{
    margin-left: 30px !important;
    margin-bottom: 5px !important;
}


}   

  @media(max-width: 390px){

    html{
        overflow-x: hidden !important;
        background-color: white !important;
    }
    body{
        overflow-y: hidden !important;
        background-color: #fff !important;
        width: 390px !important;
    }
    #upper-nav{
        background-color: black !important; 
        width: 390px !important;
        height: 75px;
        position: fixed !important;
    }

    .navbar-menu-wrapper{
       
        width: 390px !important;
        height: 60px !important;
        position: fixed !important;
        
        
    }

    .navbar-brand-wrapper{
        background-color: black !important;
        position: absolute !important;
        width: 100px !important;
        box-shadow: none;
        height: 60px !important;
        z-index: 100;
    }

    .navbar-brand-wrapper img{
        height: 40px !important;
        width: 50px !important;
        margin-left: -20px !important;
    }
    
    .sidebar{
        position: fixed !important;
        left: 0;
        width: 85px;
        margin-top: 0 !important;
        height: 130vh !important;
        display: none !important;
        transition: ease-in-out 1s !important;
        
    }

    #sidebar.active-sidebars {
    display: block !important;
  } 

  

    
    .navbar-toggler{
        display: none !important;
    }

    .responsive-bars-btn{
        display: block !important;
        margin-right: 40px !important;
        border: none !important;
        
    }

    .responsive-bars-btn span{
        color: white !important;
        font-size: 18px !important;
    }

    .header-user{
       width: 200px;
        margin-right: 90px;
       transition: ease-in-out 1s;
       background-color: black !important;
       
    }
    .header-notif span{
        font-size: 20px !important;
        margin-right: -10px;
        margin-left: 30px !important;
    }
    .header-head img{
        height: 40px !important;
    }
    .header-type h1{
        font-size: 19px !important;
    }
    .header-type p{
        font-size: 14px !important;
        margin-top: -25px !important;
    }
    .header-dropdown{
        margin-left: 30px !important;
    }
    .header-dropdown-menu{
        width: 130px !important;
        margin-left: -55px !important;
       
    }

    .nav-title,.menu-arrow{
        display: none !important;
    }

    .nav-item{
        margin-bottom: 15px !important;
    }

    .collapse{
        width: 250px !important;
        position: fixed !important;
        left: 85px !important;
    }

    .dashboard-content{
        width: 390px;
        
        margin-left: 0px !important;
        margin-top: -20px !important;
    }
    
    .dashboard-title{
        
    }

    .dashboard-title h1{
        margin-left: 20px;
    }

    .dashboard-contents{
        
    }

    .first-dash-contents{
   
        width: 95% !important;
        margin: auto !important;
    }

    .employee-status-overview{
        width: 100% !important;
    }

    .emp-status-title{
       margin-left: -15px !important;
    }

    .emp-status-container{
       
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        align-items: center !important;
        height: 900px !important;
    }

    .emp-status-container div{
        width: 340px !important;
    }

    .emp-status-container div:nth-child(1){
        background-color: #000080 !important;
        
    }

    .emp-status-container div:nth-child(1) input{
    color: white;
    font-size: 35px !important;
    margin-left: 10px !important;
    margin-top: -5px !important;
    }

    .emp-status-container div:nth-child(1) label{
    color: white;
    margin-left: 10px !important;
    font-size: 19px !important;
    }   

    .emp-status-container div:nth-child(1) p{
    color: white;
    margin-left: 10px !important;
    margin-top: 5px !important; 
    font-size: 19px !important;
    } 

    .emp-status-container div:nth-child(1) span{
        font-size: 19px !important;
        margin-left: 3px !important;
        color: black !important;
    }


    .emp-status-container div:nth-child(2){
        background-color: #F3797E !important; 
    }

    .emp-status-container div:nth-child(2) input{
    color: white;
    font-size: 35px !important;
    margin-left: 10px !important;
    margin-top: -5px !important;
    }

    .emp-status-container div:nth-child(2) label{
    color: white;
    margin-left: 10px !important;
    font-size: 19px !important;
    }   
    
    .emp-status-container div:nth-child(2) p{
    color: white;
    margin-left: 10px !important;
    margin-top: 5px !important; 
    font-size: 19px !important;
    } 

    .emp-status-container div:nth-child(2) span{
        font-size: 19px !important;
        margin-left: 3px !important;
        color: black !important;
    }

    .emp-status-container div:nth-child(3){
        background-color: #4747A1 !important;
    }

    .emp-status-container div:nth-child(3) input{
    color: white;
    font-size: 35px !important;
    margin-left: 10px !important;
    margin-top: -5px !important;
    }

    .emp-status-container div:nth-child(3) label{
    color: white;
    margin-left: 10px !important;
    font-size: 19px !important;
    }   
    
    .emp-status-container div:nth-child(3) p{
    color: white;
    margin-left: 10px !important;
    margin-top: 5px !important; 
    font-size: 19px !important;
    } 

    .emp-status-container div:nth-child(3) span{
        font-size: 19px !important;
        color: black !important;
        margin-left: 3px !important;
    }

    .emp-status-container div:nth-child(4){
        background-color: #7978E9 !important;
    }

    .emp-status-container div:nth-child(4) input{
    color: white;
    font-size: 35px !important;
    margin-left: 10px !important;
    margin-top: -5px !important;
    }

    .emp-status-container div:nth-child(4) label{
    color: white;
    margin-left: 10px !important;
    font-size: 19px !important;
    }   
    
    .emp-status-container div:nth-child(4) p{
    color: white;
    margin-left: 10px !important;
    margin-top: 5px !important; 
    font-size: 19px !important;
    } 

    .emp-status-container div:nth-child(4) .wfh-color{
        font-size: 19px !important;
        margin-left: 3px !important;
        color: black !important;
    }

    .emp-status-container div:nth-child(5){
        background-color: #98BDFF !important;
    }
    .emp-status-container div:nth-child(5) input{
    color: white;
    font-size: 35px !important;
    margin-left: 10px !important;
    margin-top: -5px !important;
    }

    .emp-status-container div:nth-child(5) label{
    color: white;
    margin-left: 10px !important;
    font-size: 19px !important;
    }   
    
    .emp-status-container div:nth-child(5) p{
    color: white;
    margin-left: 10px !important;
    margin-top: 5px !important; 
    font-size: 19px !important;
    } 

    .emp-status-container div:nth-child(5) span{
        font-size: 19px !important;
        margin-left: 3px !important;
        color: black !important;
    }

    .emp-request-list-container{
        width: 95% !important;
        
    }

    .emp-btn-container{
        width: 100% !important;
        margin-left: 11px !important; 
    }

    .emp-request-btn{
        position: relative !important;
        display: flex;
        justify-content: space-between;
       
        width: 95% !important;
        margin:auto !important;
    }

    /* .emp-request-btn div:nth-child(1){
        margin-left: 20px !important;
    } */

    .emp-request-btn div:nth-child(1) p{
        font-size: 10px !important;
       
        height: 25px !important;
        width: 25px !important;
    }

    .emp-request-btn div:nth-child(1) div{
        margin-left: 0px !important;
    }

    .emp-request-btn div:nth-child(2){
        margin-top: 5px !important;
        margin-left: -3px !important;
    }

    .emp-request-btn div:nth-child(3){
        margin-top: 5px !important;
    
    }

    .emp-request-btn div:nth-child(4){
        margin-top: 5px !important;
        margin-right: 5px !important;
    }

    .emp-request-btn div button{
        font-size: 13px !important;
    }

    .emp-request-table{
        width: 100% !important;
        margin-left: 11px !important;
    }

    .dash

    .dash-responsive-btn{
        margin-top: 16px !important;
        display: block !important;
        margin-right: 40px !important;
    }

    .request-list-dropdown {
        display: none;
        margin-top: 10px;
        background-color: #f4f4f4 !important;
        position: absolute !important;
        right: -10px !important;
        bottom: -115px !important;
        width: 130px !important;
        border-radius: 7px !important;
        padding: 10px !important;
        box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17);
     }

     .request-list-dropdown button{
        font-size: 18px !important;
        font-weight: 500 !important;
     }
     
    .emp-request-btn .fa-chevron-down {
        transition: transform 0.3s;
    }

    .emp-request-btn.active .fa-chevron-down {
        transform: rotate(180deg);
    }

    .request-table{
       
        margin-left: 10px !important;
        width: 95% !important; 
    }

    /* second content */
    .second-dash-contents{
        display:flex;
        flex-direction: column;
        align-items: center;
        transition: ease-in-out 1s;
        margin-top: 20px !important;
        margin-left: 10px !important;
        width: 95% !important;
    }

    .announcement-container{
        
        width: 100% !important;
    }

    .announce-title{
        width: 95% !important;
        margin: auto !important;
    }

    .announce-content{
        width: 100% !important;
        margin: auto !important;
        margin-top: 20px !important;
    }

    .announce-content button{
        font-size: 16px !important;
    }

    .announce-content h4{
        font-size: 26px !important;
    }

    .announce-content p{
        font-size: 14px !important;
    }

    .event-container{
        margin-bottom: 25px !important;
        width: 100% !important;
        margin-left: 0px !important;
    }
    .event-title{
        width: 95% !important;
        margin: auto !important;
    }
    .event-content{
        width: 95% !important;
        margin: auto !important;
    }

    .header-dropdown-menu a{
        margin-left: 30px !important;
        margin-bottom: 5px !important;
    }
   
   
}   
  
</style> 

<!------------------------------------Message alert------------------------------------------------->
<?php
        // if (isset($_GET['msg'])) {
        //     $msg = $_GET['msg'];
        //     echo '<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
        //     '.$msg.'
        //     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        //   </div>';
        // }
?>
<!------------------------------------End Message alert------------------------------------------------->

<!------------------------------------Message alert------------------------------------------------->
<?php
        // if (isset($_GET['error'])) {
        //     $err = $_GET['error'];
        //     echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        //     '.$err.'
        //     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        //   </div>';
        // }
?>
<!------------------------------------End Message alert------------------------------------------------->
    <?php 
    include 'config.php';

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Query the attendances table to count the number of present employees with an empid
    $query = "SELECT COUNT(*) AS present_count FROM attendances WHERE Status = 'Present' AND empid IS NOT NULL";
    $results = mysqli_query($conn, $query);
    
    // Check for errors
    if (!$results) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    // Fetch the result and store it in a variable
    $rows = mysqli_fetch_assoc($results);
    $present_count = $rows["present_count"];
    
    // Close the connection
    mysqli_close($conn);

    ?>


<!-------------------------------------------Modal of Announce Start Here--------------------------------------------->
</div><div class="modal fade" id="announcement_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Announcement</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
       <form action="Data Controller/Announcement/insert_announce.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3" style="display:none;">
                        <label for="Select_emp" class="form-label">Name</label>
                            <?php
                                include 'config.php'; 
                                @$employeeid = $_SESSION['empid'];
                                ?>
                                <input type="text" class="form-control" name="name_emp" value="<?php 
                                    error_reporting(E_ERROR | E_PARSE);
                                    if($employeeid == NULL){
                                        
                                        echo '0909090909';
                                    }else{
                                        echo $employeeid;
                                    }?>" id="empid" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="company" class="form-label">Title</label>
                            <input type="text" name="announce_title" class="form-control" id="announce_title_id" required>
                        </div>

                        <div class="mb-3">
                            <label for="date_announcement" class="form-label">Date</label>
                            <input type="date" name="announce_date" class="form-control" id="announce_date_id" required>
                        </div>

                        <div class="mb-3">
                            <label for="text_description" class="form-label">Description</label>
                            <textarea class="form-control" name="announce_description" id="announce_description_id"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="text_description" class="form-label">File Attachment</label>
                            <input type="file" name="file_upload" class="form-control" id="inputfile" >
                        </div>

                    </div><!--Modal body Close tag--->
                    <div class="modal-footer">
                <button type="submit" name="add_announcement" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         </form>

      </div>
    </div>
  </div>
</div>
<!-------------------------------------------Modal of Announce End Here---------------------------------------------> 

<!-------------------------------- Modal of view all Holiday Start Here ----------------------->
<div class="modal fade" id="view_holiday" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Holidays</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <div class="table-responsive mt-2" style=" overflow-x: hidden; height: 300px;">
            <table id="order-listing" class="table" style="width: 100%; " >
                <thead style="background-color: #ececec">
              
                    <tr> 
                        <th style= 'display: none;'> ID  </th>  
                        <th> Holiday Title </th>
                        <th> Holiday Date </th>
                        <th> Holiday Type </th>                             
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'config.php';
                    
                    // Query the department table to retrieve department names
                    $dept_query = "SELECT * FROM holiday_tb Order By `date_holiday`";
                    $dept_result = mysqli_query($conn, $dept_query);
                    
                    // Generate the HTML table header
                    
                    // Loop over the departments and count the employees
                    while ($holiday_row = mysqli_fetch_assoc($dept_result)) {
                        $holiday_id = $holiday_row['id'];
                        $holiday_name = $holiday_row['holiday_title'];
                        $date_holiday = $holiday_row['date_holiday'];
                        $holiday_type = $holiday_row['holiday_type'];
                        
                        // Generate the HTML table row
                        echo "<tr>
                                <td style='display: none;'>$holiday_id</td>
                                <td>$holiday_name</td>
                                <td>$date_holiday</td>
                                <td>$holiday_type</td>
                                
                                
                        </tr>";
                    }
                    ?>
                </tbody>

            </table>        
        </div> <!--table my-3 end--> 
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </div>
    </div>
  </div>
</div>
<!--------------------------- Modal of view all Holiday Start Here ---------------------------------->

<!-------------------------------------------Modal of View Summary Start Here--------------------------------------------->
<div class="modal fade" id="view_summary" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Summary of Announcement</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                    <table id="order-listing" class="table" style="width: 100%; max-height: 450px;">
                        <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                            <tr>
                                <th>Date</th>
                                <th>Created By</th>
                                <th>Title</th>
                                <th>Details</th>
                                <th style="display: none;">View Button</th>
                                <th>Attachment</th>
                                <th class="d-none">ID</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            include 'config.php';

                            $query = "SELECT
                            announcement_tb.id,
                            announcement_tb.announce_title,
                            employee_tb.empid,
                            CONCAT(
                                employee_tb.`fname`,
                                ' ',
                                employee_tb.`lname`
                            ) AS `full_name`,
                            announcement_tb.announce_date,
                            announcement_tb.description,
                            announcement_tb.file_attachment,
                            announcement_tb.date_file
                            FROM announcement_tb INNER JOIN employee_tb ON employee_tb.empid = announcement_tb.empid;";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td><?php echo $row['announce_date']?></td>
                                <td><?php echo $row['full_name']?></td>
                                <td><?php echo $row['announce_title']?></td>
                                <td style="display: none;"><?php echo $row['description']?></td>
                                <td><a href="" class="btn btn-primary showmodal" data-bs-toggle="modal" data-bs-target="#view_desc_modal">View</a></td>
                                <?php if(!empty($row['file_attachment'])): ?>
                                <td>
                                <button type="button" class="btn btn-outline-success downloadbtn" data-bs-toggle="modal" data-bs-target="#download">Download</button>
                                </td>
                                <td class="d-none"><?php echo $row['id'];?></td>
                                <?php else: ?>
                                <td>None</td> <!-- Show an empty cell if there is no file attachment -->
                                <?php endif; ?>
                               
                            </tr>
                        </tbody>
                        <?php
                            }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div><!---Modal Body Close Tag-->

    </div>
  </div>
</div>
<!-------------------------------------------Modal of View Summary End Here--------------------------------------------->
<!---------------------------------------View Modal Start Here -------------------------------------->
<div class="modal fade" id="view_desc_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Description</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3">
            <label for="text_area" class="form-label"></label>
            <textarea class="form-control" name="text_description" id="view_description" readonly></textarea>
         </div>
      </div><!--Modal Body Close Tag-->

    </div>
  </div>
</div>
<!---------------------------------------View Modal End Here --------------------------------------->    

<!---------------------------------------Download Modal Start Here -------------------------------------->
<div class="modal fade" id="download_announcement" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/Announcement/download.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="table_id" id="id_table_announce">
        <input type="hidden" name="table_name" id="name_table_announce">
        <h3>Are you sure you want download the PDF File?</h3>
      </div>
      <div class="modal-footer">
        <button type="submit" name="yes_download" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!---------------------------------------Download Modal End Here --------------------------------------->

<!-------------------------------------------Modal of Event Start Here--------------------------------------------->
<div class="modal fade" id="add_event" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Event</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
       <form action="Data Controller/Event/insert_event.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3" style="display:none;">
                        <label for="Select_emp" class="form-label">Name</label>
                            <?php
                                include 'config.php'; 
                                @$employeeid = $_SESSION['empid'];
                                ?>
                                <input type="text" class="form-control" name="name_emp" value="<?php error_reporting(E_ERROR | E_PARSE);
                                    if($employeeid == NULL){
                                        echo 'Super Admin';
                                    }else{
                                        echo $employeeid;
                                    }?>" id="empid" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Event Title</label>
                            <input type="text" name="event_title" class="form-control" id="id_title_event" required>
                        </div>

                        <div class="mb-3">
                            <label for="event" class="form-label">Event Date</label>
                            <input type="date" name="event_date" class="form-control" id="id_event_date" required>
                        </div>

                        <div class="mb-3">
                            <label for="eventype" class="form-label">Type of Event</label>
                            <input type="text" class="form-control" name="event_type" id="id_event_type"></input>
                        </div>
                    </div><!--Modal body Close tag--->
                    <div class="modal-footer">
                <button type="submit" name="add_event" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         </form>

      </div>
    </div>
  </div>
</div>
<!-------------------------------------------Modal of Event End Here---------------------------------------------> 

<!-------------------------------------------Modal of Holiday Start Here--------------------------------------------->
<div class="modal fade" id="add_holiday" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Holiday</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
       <form action="Data Controller/Holiday/insert_holiday.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3" style="display:none;">
                        <label for="Select_emp" class="form-label">Name</label>
                            <?php
                                include 'config.php'; 
                                @$employeeid = $_SESSION['empid'];
                                ?>
                                <input type="text" class="form-control" name="name_emp" value="<?php error_reporting(E_ERROR | E_PARSE);
                                    if($employeeid == NULL){
                                        echo 'Super Admin';
                                    }else{
                                        echo $employeeid;
                                    }?>" id="empid" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label">Holiday Title</label>
                            <input type="text" name="title_holiday" class="form-control" id="id_title_holiday" required>
                        </div>

                        <div class="mb-3">
                            <label for="event" class="form-label">Holiday Date</label>
                            <input type="date" name="date_holiday" class="form-control" id="id_holiday_date" required>
                        </div>

                        <div class="mb-3">
                            <label for="eventype" class="form-label">Type of Holiday</label>
                            <input type="text" class="form-control" name="type_holiday" id="id_holiday_type"></input>
                        </div>
                    </div><!--Modal body Close tag--->
                    <div class="modal-footer">
                <button type="submit" name="add_holiday" class="btn btn-primary">Add</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         </form>

      </div>
    </div>
  </div>
</div>
<!-------------------------------------------Modal of Holiday End Here---------------------------------------------> 

    <div class="dashboard-container" id="dashboard-container">
        <div class="dashboard-content" style="margin-left: 320px;">
            <div class="dashboard-title" style="">
                <h1>DASHBOARD</h1>
            </div>
            <div class="dashboard-contents">
                <div class="first-dash-contents">
                    <div class="employee-status-overview">
                        <div class="emp-status-title">
                            <p>Employee Status Overview</p>
                            <p>Real time status</p>
                        </div>

                        <!--------------------------- MODALS FOR DAILY ATTENDANCE TRACKER START ---------------------------------->

                                        
                            <!-- Modal of view all Present Employee Start Here --------------------------------------->
                                    <div class="modal fade" id="IDmodal_ViewPresent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Present Employees</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <div class="table-responsive mt-2" style=" overflow-x: hidden; height: 300px;">
                                                <table id="order-listing" class="table" style="width: 100%; " >
                                                    <thead style="background-color: #ececec">
                                                
                                                        <tr> 
                                                            <th> Status  </th>  
                                                            <th> Employee ID </th>
                                                            <th> Fullname </th>
                                                            <th> Time In </th>
                                                            <th> Time Out </th>
                                                            <th> Late </th>                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        include 'config.php';
                                                        date_default_timezone_set('Asia/Manila');
                                                        $approver_ID = $_SESSION['empid'];
                                                        $currentDate = date('Y-m-d');

                                                        // Query the department table to retrieve department names
                                                        $present_query = "SELECT 
                                                                           *
                                                                            
                                                                        FROM attendances
                                                                        INNER JOIN approver_tb ON approver_tb.empid = attendances.empid
                                                                        WHERE approver_tb.approver_empid = $approver_ID  
                                                                        AND `status` = 'Present' AND DATE(`date`) = '$currentDate'";
                                                        $present_result = mysqli_query($conn, $present_query);
                                                        
                                                        // Generate the HTML table header
                                                        
                                                        // Loop over the departments and count the employees
                                                        while ($present_row = mysqli_fetch_assoc($present_result)) {
                                                            $status = $present_row['status'];
                                                            $empid = $present_row['empid'];

                                                            $query_emp_tb = "SELECT  CONCAT(
                                                                                    employee_tb.`fname`,
                                                                                    ' ',
                                                                                    employee_tb.`lname`
                                                                                ) AS `full_name`
                                                                            FROM employee_tb
                                                                            WHERE empid = $empid";
                                                                            $result_emp_tb = mysqli_query($conn, $query_emp_tb);

                                                                            $row_emp_tb = mysqli_fetch_assoc($result_emp_tb);


                                                            $fullname = $row_emp_tb['full_name'];
                                                            $time_in = $present_row['time_in'];
                                                            $time_out =  $present_row['time_out'];
                                                            $late = $present_row['late'];                                                            
                                                            
                                                            // Generate the HTML table row
                                                            echo "<tr>
                                                                    <td>$status</td>
                                                                    <td>$empid</td>
                                                                    <td>$fullname</td>
                                                                    <td>$time_in</td>
                                                                    <td>$time_out</td>
                                                                    <td>$late</td>
                                                                    
                                                                    
                                                            </tr>";
                                                        }
                                                        ?>
                                                    </tbody>

                                                </table>        
                                            </div> <!--table my-3 end--> 
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                            <!--------------------------- Modal of view all Present Employee End Here ---------------------------------->




                            <!-- Modal of view all Absent Employee Start Here --------------------------------------->
                            <div class="modal fade" id="IDmodal_ViewAbsent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Absent Employees</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <div class="table-responsive mt-2" style=" overflow-x: hidden; height: 300px;">
                                                <table id="order-listing" class="table" style="width: 100%; ">
                                                    <thead style="background-color: #ececec">
                                                
                                                        <tr> 
                                                            <th> Status  </th>  
                                                            <th> Employee ID </th>
                                                            <th> Fullname </th>
                                                            <th> Time In </th>
                                                            <th> Time Out </th>
                                                            <th> Late </th>                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        include 'config.php';
                                                        date_default_timezone_set('Asia/Manila');
                                                        $approver_ID = $_SESSION['empid'];
                                                        $currentDate = date('Y-m-d');

                                                        // Query the department table to retrieve department names
                                                        $present_query = "SELECT 
                                                                           *
                                                                            
                                                                        FROM attendances
                                                                        INNER JOIN approver_tb ON approver_tb.empid = attendances.empid
                                                                        WHERE approver_tb.approver_empid = $approver_ID  
                                                                        AND `status` = 'Absent' AND DATE(`date`) = '$currentDate'";
                                                        $present_result = mysqli_query($conn, $present_query);
                                                        
                                                        // Generate the HTML table header
                                                        
                                                        // Loop over the departments and count the employees
                                                        while ($present_row = mysqli_fetch_assoc($present_result)) {
                                                            $status = $present_row['status'];
                                                            $empid = $present_row['empid'];

                                                            $query_emp_tb = "SELECT  CONCAT(
                                                                                    employee_tb.`fname`,
                                                                                    ' ',
                                                                                    employee_tb.`lname`
                                                                                ) AS `full_name`
                                                                            FROM employee_tb
                                                                            WHERE empid = $empid";
                                                                            $result_emp_tb = mysqli_query($conn, $query_emp_tb);

                                                                            $row_emp_tb = mysqli_fetch_assoc($result_emp_tb);


                                                            $fullname = $row_emp_tb['full_name'];
                                                            $time_in = $present_row['time_in'];
                                                            $time_out =  $present_row['time_out'];
                                                            $late = $present_row['late'];                                                            
                                                            
                                                            // Generate the HTML table row
                                                            echo "<tr>
                                                                    <td>$status</td>
                                                                    <td>$empid</td>
                                                                    <td>$fullname</td>
                                                                    <td>$time_in</td>
                                                                    <td>$time_out</td>
                                                                    <td>$late</td>
                                                                    
                                                                    
                                                            </tr>";
                                                        }
                                                        ?>
                                                    </tbody>

                                                </table>        
                                            </div> <!--table my-3 end--> 
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                            <!--------------------------- Modal of view all Absent Employee End Here ---------------------------------->

                            <!-- Modal of view all On-Leave Employee Start Here --------------------------------------->
                            <div class="modal fade" id="IDmodal_ViewOnleave" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">On-Leave Employees</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <div class="table-responsive mt-2" style=" overflow-x: hidden; height: 300px;">
                                                <table id="order-listing" class="table" style="width: 100%; ">
                                                    <thead style="background-color: #ececec">
                                                
                                                        <tr> 
                                                            <th> Status  </th>  
                                                            <th> Employee ID </th>
                                                            <th> Fullname </th>
                                                            <th> Time In </th>
                                                            <th> Time Out </th>
                                                            <th> Late </th>                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        include 'config.php';
                                                        date_default_timezone_set('Asia/Manila');
                                                        $approver_ID = $_SESSION['empid'];
                                                        $currentDate = date('Y-m-d');

                                                        // Query the department table to retrieve department names
                                                        $present_query = "SELECT 
                                                                           *
                                                                            
                                                                        FROM attendances
                                                                        INNER JOIN approver_tb ON approver_tb.empid = attendances.empid
                                                                        WHERE approver_tb.approver_empid = $approver_ID  
                                                                        AND `status` = 'On-Leave' AND DATE(`date`) = '$currentDate'";
                                                        $present_result = mysqli_query($conn, $present_query);
                                                        
                                                        // Generate the HTML table header
                                                        
                                                        // Loop over the departments and count the employees
                                                        while ($present_row = mysqli_fetch_assoc($present_result)) {
                                                            $status = $present_row['status'];
                                                            $empid = $present_row['empid'];

                                                            $query_emp_tb = "SELECT  CONCAT(
                                                                                    employee_tb.`fname`,
                                                                                    ' ',
                                                                                    employee_tb.`lname`
                                                                                ) AS `full_name`
                                                                            FROM employee_tb
                                                                            WHERE empid = $empid";
                                                                            $result_emp_tb = mysqli_query($conn, $query_emp_tb);

                                                                            $row_emp_tb = mysqli_fetch_assoc($result_emp_tb);


                                                            $fullname = $row_emp_tb['full_name'];
                                                            $time_in = $present_row['time_in'];
                                                            $time_out =  $present_row['time_out'];
                                                            $late = $present_row['late'];                                                            
                                                            
                                                            // Generate the HTML table row
                                                            echo "<tr>
                                                                    <td>$status</td>
                                                                    <td>$empid</td>
                                                                    <td>$fullname</td>
                                                                    <td>$time_in</td>
                                                                    <td>$time_out</td>
                                                                    <td>$late</td>
                                                                    
                                                                    
                                                            </tr>";
                                                        }
                                                        ?>
                                                    </tbody>

                                                </table>        
                                            </div> <!--table my-3 end--> 
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                            <!--------------------------- Modal of view all On-Leave Employee End Here ---------------------------------->



                            
<!-- Modal of view all Working Home Employee Start Here --------------------------------------->
<div class="modal fade" id="IDmodal_ViewWFH" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Employees Working Home</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <div class="table-responsive mt-2" style=" overflow-x: hidden; height: 300px;">
                                                <table id="order-listing" class="table" style="width: 100%; ">
                                                    <thead style="background-color: #ececec">
                                                
                                                        <tr> 
                                                            <th> Status  </th>  
                                                            <th> Employee ID </th>
                                                            <th> Fullname </th>
                                                            <th> Time In </th>
                                                            <th> Time Out </th>
                                                            <th> Late </th>                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        include 'config.php';
                                                        date_default_timezone_set('Asia/Manila');
                                                        $approver_ID = $_SESSION['empid'];
                                                        $currentDate = date('Y-m-d');
                
                
                
                
                                                        // Run code 
                                                        $query_approver = "SELECT empid from approver_tb WHERE approver_empid = '$approver_ID'";
                                                        $result_approver = mysqli_query($conn, $query_approver);
                                                        
                                                        // Check if any rows are fetched
                                                        if (mysqli_num_rows($result_approver) > 0) {
                                                            $empid_Assigned = array(); // Array to store the employee assigned to the log in supervisor
                                                        
                                                            // Loop through each row
                                                            while($row = $result_approver->fetch_assoc()) 
                                                            {
                                                                $empid = $row['empid'];
                                                                                                    
                                                                $empid_Assigned[] = array('empid' => $empid);
                                                            }
                
                                                            $employeeWFH_bool = false;
                                                            
                                                            
                
                                                            
                                                            foreach ($empid_Assigned as $empid_Assign) {
                                                                $timestamp = strtotime($currentDate);
                                                                $today = date("l", $timestamp);
                                                                $emp_array_ID =  $empid_Assign['empid'];

                                                                $query_empSched = "SELECT * FROM empschedule_tb
                                                                                INNER JOIN schedule_tb ON empschedule_tb.schedule_name = schedule_tb.schedule_name
                                                                                WHERE empschedule_tb.empid = '$emp_array_ID' 
                                                                                AND empschedule_tb.sched_from <= '$currentDate' 
                                                                                AND empschedule_tb.sched_to >= '$currentDate'";
                                                                $result_empSched = mysqli_query($conn, $query_empSched);

                                                                if (mysqli_num_rows($result_empSched) > 0) {
                                                                    $row_empSched = mysqli_fetch_assoc($result_empSched);

                                                                    // Modify the condition to use logical AND (&&) and strict comparison (===)
                                                                    if ($today === 'Monday' && ($row_empSched['mon_wfh'] !== NULL && $row_empSched['mon_wfh'] !== '')) {
                                                                        $employeeWFH_bool = true;
                                                                    } else if ($today === 'Tuesday' && ($row_empSched['tues_wfh'] !== NULL && $row_empSched['tues_wfh'] !== '')) {
                                                                        $employeeWFH_bool = true;
                                                                    } else if ($today === 'Wednesday' && ($row_empSched['wed_wfh'] !== NULL && $row_empSched['wed_wfh'] !== '')) {
                                                                        $employeeWFH_bool = true;
                                                                    } else if ($today === 'Thursday' && ($row_empSched['thurs_wfh'] !== NULL && $row_empSched['thurs_wfh'] !== '')) {
                                                                        $employeeWFH_bool = true;
                                                                    } else if ($today === 'Friday' && ($row_empSched['fri_wfh'] !== NULL && $row_empSched['fri_wfh'] !== '')) {
                                                                        $employeeWFH_bool = true;
                                                                    } else if ($today === 'Saturday' && ($row_empSched['sat_wfh'] !== NULL && $row_empSched['sat_wfh'] !== '')) {
                                                                        $employeeWFH_bool = true;
                                                                    } else if ($today === 'Sunday' && ($row_empSched['sun_wfh'] !== NULL && $row_empSched['sun_wfh'] !== '')) {
                                                                        $employeeWFH_bool = true;
                                                                    } else{
                                                                        $employeeWFH_bool = false;
                                                                    }

                                                                    if ($employeeWFH_bool === true) {
                                                                        $empid = $row_empSched['empid'];
                                                                        
                                                                        $query_emp_tb = "SELECT 
                                                                                           attendances.status,
                                                                                           attendances.empid,
                                                                                           employee_tb.fname,
                                                                                           employee_tb.lname,
                                                                                           attendances.time_in,
                                                                                           attendances.time_out,
                                                                                           attendances.late                                                
                                                                                        FROM attendances
                                                                                        INNER JOIN employee_tb ON attendances.empid = employee_tb.empid
                                                                                        WHERE DATE(attendances.`date`) = '$currentDate' AND attendances.empid = '$empid'";
                                                                        $result_emp_tb = mysqli_query($conn, $query_emp_tb);

                                                                        $row_emp_tb = mysqli_fetch_assoc($result_emp_tb);
                                                                        $empidss =  $row_emp_tb['empid'];
                                                                        $status = $row_emp_tb['status'];
                                                                        $fullname = $row_emp_tb['fname'] . " " . $row_emp_tb['lname'];
                                                                        $time_in = $row_emp_tb['time_in'];
                                                                        $time_out = $row_emp_tb['time_out'];
                                                                        $late = $row_emp_tb['late'];
                                                                        echo "<tr>
                                                                                <td>$status</td>
                                                                                <td>$empidss</td>
                                                                                <td>$fullname</td>
                                                                                <td>$time_in</td>
                                                                                <td>$time_out</td>
                                                                                <td>$late</td>                                                                          
                                                                            </tr>";
                                                                    } 
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>

                                                </table>        
                                            </div> <!--table my-3 end--> 
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                            <!--------------------------- Modal of view all Working Home Employee End Here ---------------------------------->




<!-- Modal of view all LATE Employee Start Here --------------------------------------->
<div class="modal fade" id="IDmodal_ViewLate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Employees with Late</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <div class="table-responsive mt-2" style=" overflow-x: hidden; height: 300px;">
                                                <table id="order-listing" class="table" style="width: 100%; ">
                                                    <thead style="background-color: #ececec">
                                                
                                                        <tr> 
                                                            <th> Status  </th>  
                                                            <th> Employee ID </th>
                                                            <th> Fullname </th>
                                                            <th> Time In </th>
                                                            <th> Time Out </th>
                                                            <th> Late </th>                           
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        include 'config.php';
                                                        date_default_timezone_set('Asia/Manila');
                                                        $approver_ID = $_SESSION['empid'];
                                                        $currentDate = date('Y-m-d');

                                                        // Query the department table to retrieve department names
                                                        $present_query = "SELECT 
                                                                           *
                                                                            
                                                                        FROM attendances
                                                                        INNER JOIN approver_tb ON approver_tb.empid = attendances.empid
                                                                        WHERE approver_tb.approver_empid = $approver_ID  
                                                                        AND `late` != '00:00:00' AND `late` != '' AND DATE(`date`) = '$currentDate'";
                                                        $present_result = mysqli_query($conn, $present_query);
                                                        
                                                        // Generate the HTML table header
                                                        
                                                        // Loop over the departments and count the employees
                                                        while ($present_row = mysqli_fetch_assoc($present_result)) {
                                                            $status = $present_row['status'];
                                                            $empid = $present_row['empid'];

                                                            $query_emp_tb = "SELECT  CONCAT(
                                                                                    employee_tb.`fname`,
                                                                                    ' ',
                                                                                    employee_tb.`lname`
                                                                                ) AS `full_name`
                                                                            FROM employee_tb
                                                                            WHERE empid = $empid";
                                                                            $result_emp_tb = mysqli_query($conn, $query_emp_tb);

                                                                            $row_emp_tb = mysqli_fetch_assoc($result_emp_tb);


                                                            $fullname = $row_emp_tb['full_name'];
                                                            $time_in = $present_row['time_in'];
                                                            $time_out =  $present_row['time_out'];
                                                            $late = $present_row['late'];                                                            
                                                            
                                                            // Generate the HTML table row
                                                            echo "<tr>
                                                                    <td>$status</td>
                                                                    <td>$empid</td>
                                                                    <td>$fullname</td>
                                                                    <td>$time_in</td>
                                                                    <td>$time_out</td>
                                                                    <td>$late</td>
                                                                    
                                                                    
                                                            </tr>";
                                                        }
                                                        ?>
                                                    </tbody>

                                                </table>        
                                            </div> <!--table my-3 end--> 
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                            <!--------------------------- Modal of view all LAte Employee End Here ---------------------------------->




                                    

                        <!--------------------------- MODALS FOR DAILY ATTENDANCE TRACKER END ---------------------------------->


                        <!---Query sa pagcount ng presents--->
                        <div class="emp-status-container">
                            <div class="" data-bs-toggle="modal" data-bs-target="#IDmodal_ViewPresent" style="cursor: pointer;">
                                <?php
                                include 'config.php';
                                date_default_timezone_set('Asia/Manila');
                                $approver_ID = $_SESSION['empid'];
                                $currentDate = date('Y-m-d');

                                $query = "SELECT COUNT(*) AS employee_present FROM attendances
                                INNER JOIN approver_tb ON approver_tb.empid = attendances.empid WHERE approver_tb.approver_empid = $approver_ID  
                                AND `status` = 'Present' AND DATE(`date`) = '$currentDate'";
                                $result = mysqli_query($conn, $query);

                                if ($result) {
                                    $row = mysqli_fetch_assoc($result);
                                    $employeePresent = $row['employee_present'];
                                } else {
                                    $employeePresent = 0;
                                }
                                ?>
                                <input type="text" name="present" style="cursor: pointer;" value="<?php echo $employeePresent; ?>" readonly>
                                <p style="margin-top: -7px; ">of <span style="color: red;"><?php echo $employee_count?> </span></p>
                                <label for="present" style="margin-top: 3px;" ><i class="mdi mdi-alarm-check"> </i>Present</label>   
                            </div>

                            <!---Query sa pagcount ng Absent--->
                            <div class="" data-bs-toggle="modal" data-bs-target="#IDmodal_ViewAbsent" style="cursor: pointer;">
                                <?php
                                include 'config.php';
                                date_default_timezone_set('Asia/Manila');
                                $approver_ID = $_SESSION['empid'];
                                $currentDate = date('Y-m-d');

                                $query = "SELECT COUNT(*) AS employee_absent FROM attendances
                                INNER JOIN approver_tb ON approver_tb.empid = attendances.empid WHERE approver_tb.approver_empid = $approver_ID  
                                AND `status` = 'Absent' AND DATE(`date`) = '$currentDate'";
                                $result = mysqli_query($conn, $query);

                                if ($result) {
                                    $row = mysqli_fetch_assoc($result);
                                    $employeeAbsent = $row['employee_absent'];
                                } else {
                                    $employeeAbsent = 0;
                                }
                                ?>
                                <input type="text" name="absent" value="<?php echo $employeeAbsent; ?>" readonly>
                                <p style="margin-top: -7px; ">of<span style="color: red;"> <?php echo $employee_count?> </span></p>
                                <label for="absent" style="margin-top: 3px;" ><i class="mdi mdi-alarm-off"></i> Absent</label>
                            </div>

                            <!---Query sa pagcount ng On Leave--->
                            <div  class="" data-bs-toggle="modal" data-bs-target="#IDmodal_ViewOnleave" style="cursor: pointer;">
                                <?php
                                include 'config.php';
                                date_default_timezone_set('Asia/Manila');
                                $approver_ID = $_SESSION['empid'];
                                $currentDate = date('Y-m-d');

                                $query = "SELECT COUNT(*) AS employee_leave FROM attendances
                                INNER JOIN approver_tb ON approver_tb.empid = attendances.empid WHERE approver_tb.approver_empid = $approver_ID  
                                AND `status` = 'On-Leave' AND DATE(`date`) = '$currentDate'";
                                $result = mysqli_query($conn, $query);

                                if ($result) {
                                    $row = mysqli_fetch_assoc($result);
                                    $employeeOnLeave = $row['employee_leave'];
                                } else {
                                    $employeeOnLeave = 0;
                                }
                                ?>
                                <input type="text" name="on_leave" value="<?php echo $employeeOnLeave; ?>" readonly >
                                <p style="margin-top: -7px; ">of <span style="color: red;"><?php echo $employee_count?> </span></p>
                                <label for="on_leave" style="margin-top: 3px;" ><i class="mdi mdi-airplane-takeoff"></i>  On Leave</label>
                            </div>

                            <!---Query sa pagcount ng Wfh--->
                            <div  class="" data-bs-toggle="modal" data-bs-target="#IDmodal_ViewWFH" style="cursor: pointer;">
                                 <?php 
                                        include 'config.php';
                                        date_default_timezone_set('Asia/Manila');
                                        $approver_ID = $_SESSION['empid'];
                                        $currentDate = date('Y-m-d');




                                        // Run code 
                                        $query_approver = "SELECT empid from approver_tb WHERE approver_empid = $approver_ID";
                                        $result_approver = $conn->query($query_approver);
                                        
                                        // Check if any rows are fetched
                                        if ($result->num_rows > 0) 
                                        {
                                            $empid_Assigned = array(); // Array to store the employee assigned to the log in supervisor
                                        
                                            // Loop through each row
                                            while($row = $result_approver->fetch_assoc()) 
                                            {
                                                $empid = $row['empid'];
                                                                                    
                                                $empid_Assigned[] = array('empid' => $empid);
                                            }

                                            $employeeWFH = 0;
                                            $emp_array_ID = '';
                                            

                                            foreach ($empid_Assigned as $empid_Assign){


                                                $timestamp = strtotime($currentDate);
                                                $today = date("l", $timestamp);

                                                $emp_array_ID =  $empid_Assign['empid'];

                                                $query_empSched = " SELECT * from empschedule_tb
                                                                    INNER JOIN schedule_tb ON empschedule_tb.schedule_name = schedule_tb.schedule_name
                                                                    WHERE empschedule_tb.empid = '$emp_array_ID' 
                                                                    AND empschedule_tb.sched_from <= '$currentDate' 
                                                                    AND empschedule_tb.sched_to >= '$currentDate'";
                                                $result_empSched = mysqli_query($conn, $query_empSched);


                                                if(mysqli_num_rows($result_empSched) > 0){
                                                    $row_empSched = mysqli_fetch_assoc($result_empSched);

                                                    if($today === 'Monday'){
                                                        if($row_empSched['mon_wfh'] != NULL || $row_empSched['mon_wfh'] != '')
                                                        {
                                                            $employeeWFH += 1;
                                                        }
                                                    }else if($today === 'Tuesday'){
                                                    
                                                        if($row_empSched['tues_wfh'] != NULL || $row_empSched['tues_wfh'] != '')
                                                        {
                                                            $employeeWFH += 1;
                                                        }
                                                    }else if($today === 'Wednesday'){
                                                        if($row_empSched['wed_wfh'] != NULL || $row_empSched['wed_wfh'] != '')
                                                        {
                                                            $employeeWFH += 1;
                                                        }
                                                    }else if($today === 'Thursday'){
                                                        if($row_empSched['thurs_wfh'] != NULL || $row_empSched['thurs_wfh'] != '')
                                                        {
                                                            $employeeWFH += 1;
                                                        }
                                                    }else if($today === 'Friday'){
                                                        if($row_empSched['fri_wfh'] != NULL || $row_empSched['fri_wfh'] != '')
                                                        {
                                                            $employeeWFH += 1;
                                                        }
                                                    }else if($today === 'Saturday'){
                                                        if($row_empSched['sat_wfh'] != NULL || $row_empSched['sat_wfh'] != '')
                                                        {
                                                            $employeeWFH += 1;
                                                        }
                                                    }
                                                    else if($today === 'Sunday'){
                                                        if($row_empSched['sun_wfh'] != NULL || $row_empSched['sun_wfh'] != '')
                                                        {
                                                            $employeeWFH += 1;
                                                        }
                                                    }


                                                }

                                            
                                            }


                                            

                                            echo '<input type="text" name="wfh" value=" ' . $employeeWFH . '" readonly style="margin-top:12px;"> ';
                                            echo '<p style=" ">of <span class="wfh-color" style="color: red;">' . $employee_count . ' </span></p>';
                                            echo '<label for="wfh" style="margin-top: -6px; margin-bottom: 20px"><i class="mdi mdi-home"></i> <span style="font-size: 16px;"> Working Home</span></label>';
                                        }
                                        
                                        ?>
                            </div>


                            <div class="" data-bs-toggle="modal" data-bs-target="#IDmodal_ViewLate" style="cursor: pointer;">
                                <?php
                                    include 'config.php';
                                    date_default_timezone_set('Asia/Manila');
                                    $approver_ID = $_SESSION['empid'];
                                    $currentDate = date('Y-m-d');

                                    $query = "SELECT COUNT(*) AS employee_late FROM attendances
                                    INNER JOIN approver_tb ON approver_tb.empid = attendances.empid WHERE approver_tb.approver_empid = $approver_ID  
                                    AND `late` != '00:00:00' AND `late` != '' AND DATE(`date`) = '$currentDate'";
                                    $result = mysqli_query($conn, $query);

                                    if ($result) {
                                        $row = mysqli_fetch_assoc($result);
                                        $employeeLate = $row['employee_late'];
                                    } else {
                                        $employeeLate = 0;
                                    }
                                    ?>
                                <input type="text" name="late" value="<?php echo $employeeLate; ?>" readonly style="margin-bottom: 5px; margin-left: 3px;">
                                <p style="margin-top: -7px; margin-left: 3px; ">of <span style="color: red; "><?php echo $employee_count?> </span></p>
                                <label for="present" style="margin-top: 3px;" ><i class="mdi mdi-run"> </i>Late</label>
                            </div>
                        </div>
                    </div>

                    <div class="emp-request-list-container">
                        <div class="emp-btn-container">
                            <div class="emp-request-btn">
                            <div>
                                <button class="mb-2 active-tab" onclick="changeTab(0)">Employee Request List <p>
                                    <?php
                                        include 'config.php';
                                        $approver_ID = $_SESSION['empid'];
                                        $sql = "
                                            SELECT COUNT(*) AS request_count
                                            FROM (
                                                SELECT applyleave_tb.col_ID, applyleave_tb.col_req_emp
                                                FROM employee_tb
                                                INNER JOIN applyleave_tb ON employee_tb.empid = applyleave_tb.col_req_emp
                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                WHERE approver_tb.approver_empid = '$approver_ID' AND applyleave_tb.col_status = 'Pending'

                                                UNION

                                                SELECT overtime_tb.id, overtime_tb.empid
                                                FROM employee_tb
                                                INNER JOIN overtime_tb ON employee_tb.empid = overtime_tb.empid
                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                WHERE approver_tb.approver_empid = '$approver_ID' AND overtime_tb.status = 'Pending'

                                                UNION

                                                SELECT undertime_tb.id, undertime_tb.empid
                                                FROM employee_tb
                                                INNER JOIN undertime_tb ON employee_tb.empid = undertime_tb.empid
                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                WHERE approver_tb.approver_empid = '$approver_ID' AND undertime_tb.status = 'Pending'

                                                UNION

                                                SELECT wfh_tb.id, wfh_tb.empid
                                                FROM employee_tb
                                                INNER JOIN wfh_tb ON employee_tb.empid = wfh_tb.empid
                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                WHERE approver_tb.approver_empid = '$approver_ID' AND wfh_tb.status = 'Pending'

                                                UNION

                                                SELECT emp_official_tb.id, emp_official_tb.employee_id
                                                FROM employee_tb
                                                INNER JOIN emp_official_tb ON employee_tb.empid = emp_official_tb.employee_id
                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                WHERE approver_tb.approver_empid = '$approver_ID' AND emp_official_tb.status = 'Pending'

                                                UNION

                                                SELECT emp_dtr_tb.id, emp_dtr_tb.empid
                                                FROM employee_tb
                                                INNER JOIN emp_dtr_tb ON employee_tb.empid = emp_dtr_tb.empid
                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                WHERE approver_tb.approver_empid = '$approver_ID' AND emp_dtr_tb.status = 'Pending'
                                            ) AS requests";

                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                $row = $result->fetch_assoc();
                                                echo $row['request_count'];
                                            } else {
                                                echo 0;
                                            }
                                        ?>
                                    </p>
                                </button>

                                <div style="border: gold 1px solid;"></div>
                            </div>

                            <div>
                                <button onclick="changeTab(1)">Leave</button>
                            </div>

                            <div>
                                <button onclick="changeTab(2)">Loans</button>
                            </div>

                            <div>
                                <button onclick="changeTab(3)">Overtime</button>
                            </div>

                            </div>
                        </div>
                        <div class="emp-request-table" style="overflow-x: hidden;">
                           <form action="actions/Employee Request List/emp_request.php" method="POST">
                              <input type="hidden" name="Super_request" id="id_Super_request">
                                 <input type="hidden" name="Super_emp_type" id="id_request_type">
                                   <table id="table-0"  class="table request-table table-borderless ml-5 mt-3" >
                                       <?php
                                                include 'config.php';
                                                $approver_ID = $_SESSION['empid'];
                                                $sql = "
                                                SELECT
                                                    CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                                    positionn_tb.position AS Position,
                                                    dept_tb.col_deptname AS Department,
                                                    applyleave_tb.col_ID AS col_ID,
                                                    applyleave_tb.col_req_emp AS col_req_emp,
                                                    applyleave_tb._datetime AS datetime,
                                                    applyleave_tb.col_status AS col_status,
                                                    'Leave Request' AS request_type
                                                FROM
                                                    employee_tb
                                                INNER JOIN applyleave_tb ON employee_tb.empid = applyleave_tb.col_req_emp
                                                INNER JOIN positionn_tb ON employee_tb.empposition = positionn_tb.id
                                                INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                WHERE approver_tb.approver_empid = '$approver_ID' AND applyleave_tb.col_status = 'Pending'

                                                UNION

                                                SELECT
                                                    CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                                    positionn_tb.position AS Position,
                                                    dept_tb.col_deptname AS Department,
                                                    overtime_tb.id AS col_ID,
                                                    overtime_tb.empid AS col_req_emp,
                                                    overtime_tb.date_filed AS datetime,
                                                    overtime_tb.status AS col_status,
                                                    'OverTime Request' AS request_type
                                                FROM
                                                    employee_tb
                                                INNER JOIN overtime_tb ON employee_tb.empid = overtime_tb.empid
                                                INNER JOIN positionn_tb ON employee_tb.empposition = positionn_tb.id
                                                INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                WHERE approver_tb.approver_empid = '$approver_ID' AND overtime_tb.status = 'Pending'

                                                UNION

                                                SELECT
                                                    CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                                    positionn_tb.position AS Position,
                                                    dept_tb.col_deptname AS Department,
                                                    undertime_tb.id AS col_ID,
                                                    undertime_tb.empid AS col_req_emp,
                                                    undertime_tb.date_file AS datetime,
                                                    undertime_tb.status AS col_status,
                                                    'Undertime Request' AS request_type
                                                FROM
                                                    employee_tb
                                                INNER JOIN undertime_tb ON employee_tb.empid = undertime_tb.empid
                                                INNER JOIN positionn_tb ON employee_tb.empposition = positionn_tb.id
                                                INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                WHERE approver_tb.approver_empid = '$approver_ID' AND undertime_tb.status = 'Pending'

                                                UNION

                                                SELECT
                                                    CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                                    positionn_tb.position AS Position,
                                                    dept_tb.col_deptname AS Department,
                                                    wfh_tb.id AS col_ID,
                                                    wfh_tb.empid AS col_req_emp,
                                                    wfh_tb.date_file AS datetime,
                                                    wfh_tb.status AS col_status,
                                                    'WFH Request' AS request_type
                                                FROM
                                                    employee_tb
                                                INNER JOIN wfh_tb ON employee_tb.empid = wfh_tb.empid
                                                INNER JOIN positionn_tb ON employee_tb.empposition = positionn_tb.id
                                                INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                WHERE approver_tb.approver_empid = '$approver_ID' AND wfh_tb.status = 'Pending'

                                                UNION

                                                SELECT
                                                    CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                                    positionn_tb.position AS Position,
                                                    dept_tb.col_deptname AS Department,
                                                    emp_official_tb.id AS col_ID,
                                                    emp_official_tb.employee_id AS col_req_emp,
                                                    emp_official_tb._dateTime AS datetime,
                                                    emp_official_tb.status AS col_status,
                                                    'Official Business' AS request_type
                                                FROM
                                                    employee_tb
                                                INNER JOIN emp_official_tb ON employee_tb.empid = emp_official_tb.employee_id
                                                INNER JOIN positionn_tb ON employee_tb.empposition = positionn_tb.id
                                                INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                WHERE approver_tb.approver_empid = '$approver_ID' AND emp_official_tb.status = 'Pending'

                                                UNION

                                                SELECT
                                                    CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                                    positionn_tb.position AS Position,
                                                    dept_tb.col_deptname AS Department,
                                                    emp_dtr_tb.id AS col_ID,
                                                    emp_dtr_tb.empid AS col_req_emp,
                                                    emp_dtr_tb._dateTime AS datetime,
                                                    emp_dtr_tb.status AS col_status,
                                                    'DTR Request' AS request_type
                                                FROM
                                                    employee_tb
                                                INNER JOIN emp_dtr_tb ON employee_tb.empid = emp_dtr_tb.empid
                                                INNER JOIN positionn_tb ON employee_tb.empposition = positionn_tb.id
                                                INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                WHERE approver_tb.approver_empid = '$approver_ID' AND emp_dtr_tb.status = 'Pending'";

                                                $result = $conn->query($sql);

                                                ?>
                                            <thead>
                                                <th style="display: none;">ID</th>
                                                <th class="emp-table-adjust" style="color: blue; width: 10%;">Type of Request</th>
                                                <th style="color: blue; width: 10%;">Requestor</th>
                                                <th style="color: blue; width: 10%;">Action</th>
                                            </thead>
                                            <tbody>
                                            <?php
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>";
                                                            echo "<td style='font-weight: 500; font-size: 14px; display: none;'>" . $row['col_ID'] . "</td>";
                                                            echo "<td style='font-weight: 500; font-size: 14px;'>" . $row['request_type'] . "</td>";
                                                            echo "<td style='font-weight: 500; font-size: 14px;'>" . $row['full_name'] . "</td>";
                                                            echo "<td><button type='submit' name='viewall_request' class='btn btn-primary viewEmprequest'>View</button></td>";
                                                            echo "</tr>";
                                                        }
                                                    }
                                                    ?>
                                            </tbody>
                                    </table>   
                                    
                                    <div class="table-responsive" style="overflow-x: hidden">
                                    <table id="table-1" class="table request-table table-borderless ml-5 mt-3" style="display: none;">
                                    <thead>
                                            <th class="emp-table-adjust" style="color: blue">Type of Request</th>
                                            <th style="color: blue">Requestor</th>
                                    </thead>
                                            <tbody>
                                                 <?php 
                                                    include 'config.php';
                                                    
                                                    date_default_timezone_set('Asia/Manila');
                                                    
                                                    // Get the current date in Manila, Philippines
                                                    $currentDate = date('Y-m-d');
                                                    
                                                    $query = "SELECT applyleave_tb.col_ID,
                                                                employee_tb.empid,
                                                                CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name, 
                                                                COUNT(*) AS employee_leave,
                                                                applyleave_tb.col_LeaveType
                                                                FROM applyleave_tb 
                                                                INNER JOIN employee_tb ON employee_tb.empid = applyleave_tb.col_req_emp 
                                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                                WHERE approver_tb.approver_empid = $approver_ID
                                                                AND applyleave_tb.`col_status` = 'Pending'
                                                                GROUP BY applyleave_tb.col_ID, employee_tb.empid, full_name, col_LeaveType";
                                                    
                                                    $result = mysqli_query($conn, $query);
                                                    
                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            ?>
                                                            <tr>
                                                                <td style="font-weight: 500; font-size: 14px"><?php echo $row['col_LeaveType'] ?></td>
                                                                <td style="font-weight: 500; font-size: 14px"><?php echo $row['full_name'] ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="2" style="text-align: center; font-weight: bold;">No Request</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                ?>
                                            </tbody>
                                    </table>
                                    </div>


                                    <div class="table-responsive" style="overflow-x: hidden">
                                    <table id="table-2" class="table request-table table-borderless ml-5 mt-3" style="display: none;">
                                    <thead>
                                            <th class="emp-table-adjust" style="color: blue">Type of Request</th>
                                            <th style="color: blue">Requestor</th>
                                    </thead>
                                            <tbody>
                                            <?php 
                                                    include 'config.php';
                                                    date_default_timezone_set('Asia/Manila');
                                                    
                                                    // Get the current date in Manila, Philippines
                                                    $currentDate = date('Y-m-d');
                                                    
                                                    $query = "SELECT payroll_loan_tb.id,
                                                                employee_tb.empid,
                                                                CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name, 
                                                                COUNT(*) AS employee_loan,
                                                                payroll_loan_tb.loan_type
                                                                FROM payroll_loan_tb 
                                                                INNER JOIN employee_tb ON employee_tb.empid = payroll_loan_tb.empid
                                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                                WHERE approver_tb.approver_empid = $approver_ID
                                                                AND payroll_loan_tb.`loan_status` = 'Pending'
                                                                GROUP BY payroll_loan_tb.id, employee_tb.empid, full_name, loan_type";
                                                    
                                                    $result = mysqli_query($conn, $query);
                                                    
                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            ?>
                                                            <tr>
                                                                <td style="font-weight: 500; font-size: 14px"><?php echo $row['loan_type'] ?></td>
                                                                <td style="font-weight: 500; font-size: 14px"><?php echo $row['full_name'] ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="2" style="text-align: center; font-weight: bold;">No Request</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                ?>
                                            </tbody>
                                    </table>
                                    </div>

                                    <div class="table-responsive" style="overflow-x: hidden">
                                    <table id="table-3" class="table request-table table-borderless ml-5 mt-3 pd-2" style="display: none;">
                                    <thead>
                                            <th class="emp-table-adjust" style="color: blue">Type of Request</th>
                                            <th style="color: blue">Requestor</th>
                                    </thead>
                                        <tbody>
                                        <?php 
                                                    include 'config.php';
                                                    date_default_timezone_set('Asia/Manila');
                                                    
                                                    // Get the current date in Manila, Philippines
                                                    $currentDate = date('Y-m-d');
                                                    
                                                    $query = "SELECT overtime_tb.id,
                                                                employee_tb.empid,
                                                                CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name, 
                                                                COUNT(*) AS employee_overtime
                                                                FROM overtime_tb 
                                                                INNER JOIN employee_tb ON employee_tb.empid = overtime_tb.empid
                                                                INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                                WHERE approver_tb.approver_empid = $approver_ID
                                                                AND overtime_tb.`status` = 'Pending'
                                                                GROUP BY overtime_tb.id, employee_tb.empid, full_name";
                                                    
                                                    $result = mysqli_query($conn, $query);
                                                    
                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            ?>
                                                            <tr>
                                                                <td style="font-weight: 500; font-size: 14px">Overtime Request</td>
                                                                <td style="font-weight: 500; font-size: 14px"><?php echo $row['full_name'] ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="2" style="text-align: center; font-weight: bold;">No Request</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                ?>
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                    </div>
                </div>   

                <div class="second-dash-contents">
                    <div class="announcement-container">
                        <div class="announce-title">
                            <h3 class="mb-0 d-inline-block mt-2 ml-2">Announcement</h3>
                            <i class="mdi mdi-arrow-down-drop-circle float-right mt-2 mr-2" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: blue; cursor: pointer;"></i>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#announcement_modal" style="cursor: pointer;">Add Announcement</a>
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#view_summary" style="cursor: pointer;">View Summary</a>
                        </div>
    

                        <div class="swiper mt-3 " style="height: 20em; border-radius: 0.8em; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 5px 0 rgba(0, 0, 0, 0.17); background-color: white; padding-bottom: 2em">
                         <!-- Additional required wrapper -->
  <div class="swiper-wrapper" style="border-radius: 0.8em; ">
  <?php
  include 'config.php';

  $query = "SELECT announcement_tb.id,
            announcement_tb.announce_title,
            employee_tb.empid,
            CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
            announcement_tb.announce_date,
            announcement_tb.description,
            announcement_tb.file_attachment 
            FROM announcement_tb 
            INNER JOIN employee_tb ON announcement_tb.empid = employee_tb.empid
            ORDER BY announcement_tb.date_file DESC";
  $result = mysqli_query($conn, $query);
  $slideIndex = 0;
  $pageNumber = 1;
  $totalAnnouncements = mysqli_num_rows($result);
  while ($row = mysqli_fetch_assoc($result)) {
    if ($slideIndex % 1 === 0) {
      echo "<div class='swiper-slide bg-white p-3' style='border-radius: 0.8em; overflow-y:scroll; '>";
    }
    ?>
    <h4 class="mt-2 ml-2"><?php echo $row['announce_title'] ?></h4>
    <p class="ml-2">
      <span style="color: #7F7FDD; font-style: Italic;">
        <?php
        if ($row['empid'] === '0909090909') {
          echo 'SuperAdmin';
        } else {
          echo $row['full_name'];
        }
        ?>
      </span> - <?php echo $row['announce_date'] ?>
    </p>
    <p class="ml-2 pl-4 pr-4" ><?php echo $row['description']?></p>  
    <?php
    if (($slideIndex + 1) % 1 === 0) {
    //   echo "<span class='page-number'>Page $pageNumber of $totalAnnouncements</span>";
      echo "</div>";
    //   $pageNumber++;
    }
    $slideIndex++;
  }
  if ($slideIndex % 1 !== 0) {
    echo "</div>";
  }
//   echo "<span class='page-number'>Page $pageNumber of $totalAnnouncements</span>";
//   $pageNumber++;
  ?>
 
</div>
<div class="swiper-pagination"></div>

  <!-- If we need navigation buttons -->
  <div class="swiper-button-prev"></div>
  <div class="swiper-button-next"></div>
 
  <!-- If we need scrollbar -->
  <!-- <div class="swiper-scrollbar"></div> -->
</div>
                        </div>
                    </div>

                    <div class="event-container mt-2" id="event-box" style="width: 670px; margin-left: 5px;">
                        <div class="event-title">
                            <div>
                                <p><span class="mdi mdi-calendar-check" style="margin-right:10px;"></span> Events</p>
                            </div>
                            <div>
                            <i class="mdi mdi-arrow-down-drop-circle float-right mt-2 mr-2"  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: blue; cursor: pointer;"></i>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#add_event" style="cursor: pointer;">Add Event</a>
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#add_holiday" style="cursor: pointer;">Add Holiday</a>
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#view_holiday" style="cursor: pointer;">View holiday</a>
                            </div>
                        </div>
                            </div>
                        <div class="event-content" style="overflow-y: auto;">
                            <div class="first_content">
                                <?php
                                date_default_timezone_set('Asia/Manila');

                                // Get the current month's start and end dates
                                $startDate = date('Y-m-d');
                                $endDate = date('Y-m-t');
                                                                  
                                $query = "SELECT * FROM event_tb WHERE date_event BETWEEN '$startDate' AND '$endDate' ORDER BY `date_event` ASC";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $eventDate = date('Y-m-d', strtotime($row['date_event']));
                                    $eventDay = date('l', strtotime($row['date_event']));
                                ?>
                                <div class="son_first" style="background-color: #ECECEC;">
                                    <p><?php echo '<strong style="font-size: 20px; margin-left: 10px;">' . $row['event_title'] . '</strong> ' . '<span style="float: right; margin-right: 10px;">' . $eventDate . '</span>'; ?></p>
                                    <p><?php echo '<span style="margin-left: 10px;">' . $row['event_type'] . '</span> ' . '<span style="float: right; margin-right: 10px;">' . $eventDay . '</span>'; ?></p>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                            
                            <div class="holiday-content">
                            <div class="first_holiday_content">
                                <?php
                                 date_default_timezone_set('Asia/Manila');

                                 // Get the current month's start and end dates
                                 $startDate = date('Y-m-01');
                                 $endDate = date('Y-m-t');
                                 $query = "SELECT * FROM holiday_tb WHERE `date_holiday` BETWEEN '$startDate' AND '$endDate' ORDER BY `date_holiday` ASC";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $holidayDate = date('Y-m-d', strtotime($row['date_holiday']));
                                    $holidayDay = date('l', strtotime($row['date_holiday']));
                                ?>
                                <div class="son_holiday" style="background-color: #ECECEC;">
                                    <p><?php echo '<strong style="font-size: 20px; margin-left: 10px;">' . $row['holiday_title'] . '</strong> ' . '<span style="float: right; margin-right: 10px;">' . $holidayDate . '</span>'; ?></p>
                                    <p><?php echo '<span style="margin-left: 10px;">' . $row['holiday_type'] . '</span> ' . '<span style="float: right; margin-right: 10px;">' . $holidayDay . '</span>'; ?></p>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>    

            </div>
        </div>
    </div>
    
    <script>
const swiper = new Swiper('.swiper', {
  // Optional parameters
  direction: 'horizontal',
  loop: true,

  // If we need pagination
  pagination: {
    el: '.swiper-pagination',
  },

  // Navigation arrows
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },

  // And if we need scrollbar
  scrollbar: {
    el: '.swiper-scrollbar',
  },
});
</script>

<script> 
        $(document).ready(function(){
        $('.viewEmprequest').on('click', function(){
        $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function () {
            return $(this).text();
        }).get();

        console.log(data);
        $('#id_Super_request').val(data[0]);                  
        $('#id_request_type').val(data[1]);
    });
});
</script>

<!------------------------------------Script para sa pag pop-up ng view modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.showmodal').on('click', function(){
                 $('#view_desc_modal').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#view_description').val(data[3]);
               });
             });
</script>
<!---------------------------------End ng Script para sa pag pop-up ng view modal------------------------------------------>
    
    <script>
function changeTab(tabIndex) {
  // Get all the table elements
  var tables = document.querySelectorAll('.request-table');

  // Get all the tab buttons
  var buttons = document.querySelectorAll('.emp-request-btn button');

  // Hide all tables
  for (var i = 0; i < tables.length; i++) {
    tables[i].style.display = 'none';
  }

  // Remove active-tab class from all buttons
  for (var i = 0; i < buttons.length; i++) {
    buttons[i].classList.remove('active-tab');
  }

  // Show the selected table
  tables[tabIndex].style.display = 'block';

  // Add active-tab class to the selected button
  buttons[tabIndex].classList.add('active-tab');
}
</script>


<!------------------------------------Script para lumabas ang download modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.downloadbtn').on('click', function(){
                 $('#download_announcement').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#id_table_announce').val(data[6]);
                   $('#name_table_announce').val(data[2]);
               });
             });
</script>
<!---------------------------------End ng Script para lumabas ang download modal------------------------------------------>

<!---------------------------- Script para lumabas ang warning message na PDF File lang inaallow------------------------------------------>
<script>
  document.getElementById('inputfile').addEventListener('change', function(event) {
    var fileInput = event.target;
    var file = fileInput.files[0];
    if (file.type !== 'application/pdf') {
      alert('Please select a PDF file.');
      fileInput.value = ''; // Clear the file input field
    }
  });
</script>

<!--------------------End ng Script para lumabas ang Script para lumabas ang warning message na PDF File lang inaallow--------------------->
        
<!------------------------Script sa function ng Previous and Next Button--------------------------------------->
<script>
 var currentSlide = 0;
  var slides = document.getElementsByClassName("announcement-slide");

  function showSlide(n) {
    for (var i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }
    slides[n].style.display = "block";
    currentSlide = n;
  }

  function prevSlide() {
    if (currentSlide > 0) {
      showSlide(currentSlide - 1);
    }
  }

  function nextSlide() {
    if (currentSlide < slides.length - 1) {
      showSlide(currentSlide + 1);
    }
  }

  showSlide(0); // Show the first slide initially


  var announceContent = document.querySelector('.announce-content');
  var prevButton = document.querySelector('.prev');
  var nextButton = document.querySelector('.next');

  announceContent.onscroll = function() {
    var scrollPosition = announceContent.scrollTop;

    // Adjust the position of prev and next buttons based on the scroll position
    prevButton.style.top = scrollPosition + announceContent.offsetHeight - prevButton.offsetHeight + 'px';
    nextButton.style.top = scrollPosition + announceContent.offsetHeight - nextButton.offsetHeight + 'px';
  };
</script>
<!------------------------End Script sa function ng Previous and Next Button--------------------------------------->



<!--     
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script src="vendors/js/vendor.bundle.base.js"></script> -->

<!-- endinject -->
<!-- Plugin js for this page-->
<!-- <script src="vendors/datatables.net/jquery.dataTables.js"></script>
<script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="bootstrap js/template.js"></script> -->
<!-- Custom js for this page-->
<!-- <script src="bootstrap js/data-table.js"></script> -->
<!-- End custom js for this page-->
    <!-- <script src="main.js"></script> -->

<script> 
     $('.header-dropdown-btn').click(function(){
        $('.header-dropdown .header-dropdown-menu').toggleClass("show-header-dd");
    });

//     $(document).ready(function() {
//     $('.navbar-toggler').click(function() {
//     $('.nav-title').toggleClass('hide-title');
//     $('.dashboard-container').toggleClass('move-content');
  
//   });
// });
 $(document).ready(function() {
    var isHamburgerClicked = false;

    $('.navbar-toggler').click(function() {
    $('.nav-title').toggleClass('hide-title');
    // $('.dashboard-container').toggleClass('move-content');
    isHamburgerClicked = !isHamburgerClicked;

    if (isHamburgerClicked) {
      $('#dashboard-container').addClass('move-content');
    } else {
      $('#dashboard-container').removeClass('move-content');

      // Add class for transition
      $('#dashboard-container').addClass('move-content-transition');
      // Wait for transition to complete before removing the class
      setTimeout(function() {
        $('#dashboard-container').removeClass('move-content-transition');
      }, 800); // Adjust the timeout to match the transition duration
    }
  });
});
 

//     $(document).ready(function() {
//   $('.navbar-toggler').click(function() {
//     $('.nav-title').toggleClass('hide-title');
//   });
// });


    </script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
//   $('.nav-link').on('click', function(e) {
//     if ($(window).width() <= 390) {
//       e.preventDefault();
//       $(this).siblings('.sub-menu').slideToggle();
//     }
//   });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 390) {
      $('#sidebar').toggleClass('active-sidebars');
    }
  });
});


$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
//   $('.nav-link').on('click', function(e) {
//     if ($(window).width() <= 500) {
//       e.preventDefault();
//       $(this).siblings('.sub-menu').slideToggle();
//     }
//   });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 500) {
      $('#sidebar').toggleClass('active-sidebar');
    }
  });
});


</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>





<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
    
    <!--skydash-->
    <script src="skydash/vendor.bundle.base.js"></script>
    <script src="skydash/off-canvas.js"></script>
    <script src="skydash/hoverable-collapse.js"></script>
    <script src="skydash/template.js"></script>
    <script src="skydash/settings.js"></script>
    <script src="skydash/todolist.js"></script>
    <script src="main.js"></script>
    <script src="bootstrap js/data-table.js"></script>
    

    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
</body>
</html>