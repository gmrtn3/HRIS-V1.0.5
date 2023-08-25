
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/changepass.css">
    <title>Employee Change Password</title>
</head>
<body>

<style>
.show-password{
    position: relative;
    
    display: flex; 
}

.show-password #old-password-eye{
    font-size:  1.3em !important;
       position: absolute !important;
       bottom: 1.4em !important;
        right: 3% !important;
        cursor: pointer !important;
        transform: translateY(-50%);
}

.show-password2{
    position: relative;
   
    display: flex;
     
}

.show-password2 #new-password-eye{
    font-size:  1.3em !important;
       position: absolute !important;
       bottom: 1.4em !important;
        right: 3% !important;
        cursor: pointer !important;
        transform: translateY(-50%);
}

.show-password3{
    position: relative;
  
    display: flex;
     
}

.show-password3 #confirm-new-password-eye{
    font-size:  1.3em !important;
       position: absolute !important;
       bottom: 1.4em !important;
        right: 3% !important;
        cursor: pointer !important;
        transform: translateY(-50%);
    
}


.changepass-update{
    border: none !important;
    border-radius: 5px !important;
}
</style>



<script>
  function toggleOldPassword() {
    var oldPasswordInput = document.getElementById("oldPassword");
    var oldPasswordEyeIcon = document.getElementById("old-password-eye");

    if (oldPasswordInput.type === "password") {
      oldPasswordInput.type = "text";
      oldPasswordEyeIcon.classList.remove("fa-eye");
      oldPasswordEyeIcon.classList.add("fa-eye-slash");
    } else {
      oldPasswordInput.type = "password";
      oldPasswordEyeIcon.classList.remove("fa-eye-slash");
      oldPasswordEyeIcon.classList.add("fa-eye");
    }
  }

  function toggleNewPassword() {
    var newPasswordInput = document.getElementById("newPassword");
    var newPasswordEyeIcon = document.getElementById("new-password-eye");

    if (newPasswordInput.type === "password") {
      newPasswordInput.type = "text";
      newPasswordEyeIcon.classList.remove("fa-eye");
      newPasswordEyeIcon.classList.add("fa-eye-slash");
    } else {
      newPasswordInput.type = "password";
      newPasswordEyeIcon.classList.remove("fa-eye-slash");
      newPasswordEyeIcon.classList.add("fa-eye");
    }
  }

  function toggleConfirmNewPassword() {
    var confirmNewPasswordInput = document.getElementById("cpassword");
    var confirmNewPasswordEyeIcon = document.getElementById("confirm-new-password-eye");

    if (confirmNewPasswordInput.type === "password") {
      confirmNewPasswordInput.type = "text";
      confirmNewPasswordEyeIcon.classList.remove("fa-eye");
      confirmNewPasswordEyeIcon.classList.add("fa-eye-slash");
    } else {
      confirmNewPasswordInput.type = "password";
      confirmNewPasswordEyeIcon.classList.remove("fa-eye-slash");
      confirmNewPasswordEyeIcon.classList.add("fa-eye");
    }
  }
</script>
    
    <nav>
    <div class="emp-changepass-logo">
            <div class="changepass-logo" >
                <img src="img/Slash Tech Solutions.png" class="logo" alt="" srcset="" >   
            </div>
            <div class="changepass-login">
                <a href="login.php"  class="changepass-login-btn" style="text-decoration: none;">Login <i class="fa-solid fa-right-to-bracket" style="margin-bottom: -3px; margin-left: 5px;"></i></a>
            </div>
            
        </div>
       
    </nav>

    
        
        <div class="emp-changepass-container" style="background-color: #f4f4f4;">
            <div class="emp-changepass-content">
                <div class="changepass-title">
                    <p  class="p-title">Change the password to your preferred credential.</p>
                </div>
                <form action="Data Controller/Employee List/changepasswordController.php" method="POST" id="changepassword-form">
                    <div class="changepass-form form-group" style="margin-top: 40px;">
                        <div class="form-group">
                            <label for="username">Username:</label><br>
                            <input type="text" name="username" class="form-control hehe" placeholder="Enter Username" style="padding: 20px">
                        </div>
                        <div class="form-group show-password">
                            <div>
                                <label for="oldpw">Old Password:</label><br>
                                <input type="password" name="password" class="form-control" placeholder="Enter Old Password" style="padding: 20px" id="oldPassword">
                            </div>
                            
                            <i class="fas fa-eye show-pass" aria-hidden="true" id="old-password-eye" onclick="toggleOldPassword()"></i>
                        </div>

                        <div class="form-group show-password2">
                            <div>
                                <label for="password">New Password:</label><br>
                                <input type="password" name="newPassword" class="form-control" placeholder="New Password" style="padding: 20px" id="newPassword">
                            </div>
                            
                            <i class="fas fa-eye show-pass" aria-hidden="true" id="new-password-eye" onclick="toggleNewPassword()"></i>
                        </div>

                        <div class="form-group show-password3">
                            <div>
                                <label for="cpassword">Confirm New Password:</label><br>
                                <input type="password" name="cpassword" class="form-control" placeholder="Confirm New Password" style="padding: 20px" id="cpassword">
                            </div>
                            
                            <i class="fas fa-eye show-pass" aria-hidden="true" id="confirm-new-password-eye" onclick="toggleConfirmNewPassword()"></i>
                        </div>
                        <div class="form-group cupdate" style="">
                            <input  type="submit" value="Update" name="update" class="changepass-update">
                        </div>
                    </div>
                </form>
            </div>
        </div>


    

    



<script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>