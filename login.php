<?php

include 'config.php';

// error_reporting(0);
session_start();

if(isset($_POST['signIn'])){
    $username = $_POST['username'];
    $passwordnot = $_POST['password'];
    $password = mysqli_real_escape_string($conn, md5($_POST["password"]));
    
    $Superadmin = "SELECT * FROM user_tb WHERE BINARY `username` = '$username' AND BINARY `password` = '$passwordnot'";
    $superAdminResult = mysqli_query($conn, $Superadmin);
    
    if (mysqli_num_rows($superAdminResult) > 0){
        $row_Superadmin = mysqli_fetch_assoc($superAdminResult);
        $_SESSION['username'] = $row_Superadmin['username'];
        $_SESSION['password'] = $row_Superadmin['password'];
        $_SESSION['userType'] = $row_Superadmin['userType'];
        $_SESSION['role'] = $row_Superadmin['role'];
        $_SESSION['empid'] = $row['empid'];
        
        header("Location: Dashboard"); // Redirect to admin dashboard
        exit();
    } else {
        $select_users = mysqli_query($conn, "SELECT * FROM employee_tb WHERE BINARY `username`='$username' AND `password`='$password'");
  
        if(mysqli_num_rows($select_users) > 0){
            $row = mysqli_fetch_assoc($select_users);
            if($row['status'] == 'Inactive'){
                echo '<script type="text/javascript">';
                echo 'alert("Your Account is already inactive!");';
                echo '</script>';
            } else {
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['password'] = $row['password'];
                $_SESSION['empid'] = $row['empid'];
                $_SESSION['role'] = $row['role'];
                
                if($row['role'] == 'admin'){
                    header("Location: Dashboard"); // Redirect to admin dashboard
                    exit();
                } else if($row['role'] == 'Employee'){
                    header("Location: EmpHRIS/Dashboard"); // Redirect to employee dashboard
                    exit();
                } else if ($row['role'] == 'Supervisor'){
                    header("Location: Supervisor HRIS/Dashboard"); // Redirect to supervisor dashboard
                    exit();
                }
            }
        } else {
            $errorMessage = "Invalid username or password";
            echo '<script type="text/javascript">';
            echo 'alert("Wrong Email or Password!");';
            echo '</script>';
        }
    }
}


// if(isset($_POST['signIn'])){
//     $username = $_POST['username'];
//     $passwordnot = $_POST['password'];
//     $password = mysqli_real_escape_string($conn, md5($_POST["password"]));
   

//                             $Superadmin = "SELECT * FROM user_tb WHERE BINARY `username` = '$username' AND BINARY `password` = '$passwordnot'";
//                             $superAdminResult = mysqli_query($conn, $Superadmin);
//                             // Check if employee login is successful

//                             if (mysqli_num_rows($superAdminResult) > 0){
//                                 $row_Superadmin = mysqli_fetch_assoc($superAdminResult);
//                                 $_SESSION['username'] = $row_Superadmin ['username'];
//                                 $_SESSION['password'] = $row_Superadmin['password'];
//                                 $_SESSION['userType'] = $row_Superadmin['userType'];
//                                 $_SESSION['role'] = $row_Superadmin['role'];
//                                 $_SESSION['empid'] = $row['empid'];
                            
//                                 header("Location: Dashboard"); // Redirect to admin dashboard
//                                 exit();
//                             } else {
                              
//                                 $select_users = mysqli_query($conn, "SELECT * FROM employee_tb WHERE `username`='$username' AND `password`='$password' AND `status` = 'Active'");
  
  
//                                 if(mysqli_num_rows($select_users) > 0){
                                  
//                                   $row = mysqli_fetch_assoc($select_users);
                              
//                                   if($row['role'] == 'admin'){
                              
//                                     $_SESSION['id'] = $row['id'];
//                                     $_SESSION['username'] = $row['username'];
//                                     $_SESSION['password'] = $row['password'];
//                                     $_SESSION['empid'] = $row['empid'];
//                                     $_SESSION['role'] = $row['role'];
                                             
//                                     header("Location: Dashboard"); // Redirect to employee dashboard
//                                     exit();
                              
//                                   }else if($row['role'] == 'Employee'){
                              
//                                     $_SESSION['id'] = $row['id'];
//                                     $_SESSION['username'] = $row['username'];
//                                     $_SESSION['password'] = $row['password'];
//                                     $_SESSION['empid'] = $row['empid'];
//                                     $_SESSION['role'] = $row['role'];
                                             
//                                     header("Location: EmpHRIS/Dashboard"); // Redirect to employee dashboard
//                                     exit();
                              
//                                   }else if ($row['role'] == 'Supervisor'){
//                                     $_SESSION['id'] = $row['id'];
//                                     $_SESSION['username'] = $row['username'];
//                                     $_SESSION['password'] = $row['password'];
//                                     $_SESSION['empid'] = $row['empid'];
//                                     $_SESSION['role'] = $row['role'];
                                             
//                                     header("Location: Supervisor HRIS/Dashboard"); // Redirect to employee dashboard
//                                     exit();
//                                 }//else{
//                                 //       $message[] = 'no user found!';
//                                 //   }
                              
//                                 }else{
//                                     $errorMessage = "Invalid username or password";
//                                 echo '<script type="text/javascript">';
//                                     echo 'alert("Wrong Email or Password!");';
//                                 echo '</script>';
//                                 }
//                             }


// }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  
    <link rel="stylesheet" href="backup/style.css">
    <link rel="stylesheet" href="css/login.css">
    <title>HRIS | LOG IN</title>
</head>
<body class="login-container" style="overflow:hidden; background-color: #000">

<style>
    .login-pass-container{
    position: relative;
    

}
    .login-pass-container #eye{
       font-size: 23px !important;
       position: absolute !important;
       bottom: 0 !important;
        right: 7% !important;
    }
</style>

    <div class="container" >
        <div class="logo-img" >
            <img src="img/login-img5.jpg" alt="" srcset="">
            <svg class="waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#000" fill-opacity="1" d="M0,64L48,74.7C96,85,192,107,288,101.3C384,96,480,64,576,74.7C672,85,768,139,864,133.3C960,128,1056,64,1152,64C1248,64,1344,128,1392,160L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
              </svg>
        </div>

        <div class="signin-container">
            <div class="signin-card">

                <div class="signin-logo-img">
                    <img src="img/Slash Tech Solutions.png" class="logo" alt="" srcset="" >
                </div>   
                
                <div class="form-container">
                    <form action="" method="POST">
                        <input class="input-text" type="text" name="username" id="username" placeholder="Username" value="<?php echo @$username; ?>" required>
                        
                        <div class="login-pass-container" style="">   
                            <input class="input-text" id="login-pass" type="password" name="password" placeholder="Password" required>
                            <i class="fas fa-eye show-pass" aria-hidden="true" id="eye" onclick="toggle()"></i>
                        </div>


                        <div class="remember-forgot">

                            <div class="chkbox-container">
                                <input class="checkbox" type="hidden" name="" id="">
                                <!-- <p>Remember me</p> -->
                            </div>
        
                            <a href="#">Forgot Password?</a>
                        </div>
                        
                        <button type="submit" name="signIn" class="signin-btn" id="signin-btn">Sign in </button> 
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
  function toggle() {
    var passwordInput = document.getElementById("login-pass");
    var eyeIcon = document.getElementById("eye");

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      eyeIcon.classList.remove("fa-eye");
      eyeIcon.classList.add("fa-eye-slash");
    } else {
      passwordInput.type = "password";
      eyeIcon.classList.remove("fa-eye-slash");
      eyeIcon.classList.add("fa-eye");
    }
  }
</script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="main.js"></script>
</body>
</html>