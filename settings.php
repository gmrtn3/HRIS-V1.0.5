  <?php
session_start();
//    $empid = $_SESSION['empid'];
   if (!isset($_SESSION['username'])) {
    header("Location: login.php");
} else {
    // Check if the user's role is not "admin"
    if ($_SESSION['role'] != 'admin') {
        // If the user's role is not "admin", log them out and redirect to the logout page
        session_unset();
        session_destroy();
        header("Location: logout.php");
        exit();
    } else{
        include 'config.php';
        $userId = $_SESSION['empid'];
       
        $iconResult = mysqli_query($conn, "SELECT id, emp_img_url, empid FROM employee_tb WHERE empid = '$userId'");
        $iconRow = mysqli_fetch_assoc($iconResult);

        if ($iconRow) {
            $image_url = $iconRow['emp_img_url'];
        } else {
            // Handle the case when the user ID is not found in the database
            $image_url = '../img/user.jpg'; // Set a default image or handle the situation accordingly
        }
    
    }
}
  ?>
            <?php

  include 'config.php';

  $query = "SELECT * FROM settings_company_tb";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  // Access the piece_rate_toggle value from the $row variable
  $pieceRateToggle = $row['piece_rate_toggle'];


  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Company Settings</title>

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

  <!-- Make sure to include this before your custom JavaScript code -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  <!-- skydash -->

  <link rel="stylesheet" href="skydash/feather.css">
      <link rel="stylesheet" href="skydash/themify-icons.css">
      <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
      <link rel="stylesheet" href="skydash/vendor.bundle.base.css">

      <link rel="stylesheet" href="skydash/style.css">

      <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
      <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">

      <link rel="stylesheet" href="css/try.css">
      <link rel="stylesheet" href="css/styles.css">
      <link rel="stylesheet" href="css/settings.css">
  </head>
  <body>

  <header>
    <?php
      include 'header.php';

    ?>
  </header>

  <style>
    
  .first-flex{
      display: flex;
      flex-direction: row;
    
      width: 60%;
  }
  </style>


  <div class="container">
      <div class="card">
          <div class="card-body" style="border-radius: 6px;">


          <form action="Data Controller/Settings/company_logo.php" method="POST" enctype="multipart/form-data">
          <div class="for-header">
            <h2 class="setting_text">Settings</h2>

            <div class="logo_text">
                    <h3>Company Profile</h3>
              </div>

              <?php
              include 'config.php';
              $result = mysqli_query($conn, "SELECT * FROM settings_company_tb");
              $row = mysqli_fetch_assoc($result); 
            ?>

              <div class="setting_content">
                <div class="first_cont">
                    <div class="mb-3">
                      <label for="" class="form-label comp_text">Company Name</label>
                      <input name="company_name" type="text" class="form-control input_company"  id="company_id" value="<?php echo $row['cmpny_name']?>" >
                    </div>

                    <div class="mb-3">
                      <label for="" class="form-label comp_text">Company Address</label>
                      <input name="company_address" type="text" class="form-control input_address" id="address_id" value="<?php echo $row['cmpny_address']?>">
                    </div>
                    
                    <div class="first-flex">
                      <div class="">
                        <label for="" class="form-label comp_text">Zipcode</label>
                        <input name="company_zipcode" type="text" class="form-control input_zipcode" id="zipcode_id" value="<?php echo $row['cmpny_zipcode']?>" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 4) this.value = this.value.slice(0, 4);">
                      </div>

                      <div class="dmcontainer">
                          <label for="" class="form-label">Domain email</label>
                          <input type="text" class="form-control inputemail" name="company_domain_mail" value="<?php echo $row['email_domain']?>">
                      </div>

                      
                      <div class="form-check form-switch">
                        <label for="" class="" style="margin-top: 5%">Hide Pakyawan</label>
                        <?php
                          // Assuming you have already fetched the data and stored it in the $pieceRateToggle variable
                          $isChecked = $pieceRateToggle === "Hidden" ? "checked" : "";
                          ?>
                          <input class="form-check-input" type="checkbox" style="margin-left: -70%; margin-top: 40%" name="piece_rate_toggle" id="piece_rate_toggle" value="Hidden" <?php echo $isChecked; ?>>
                      </div>
            
                    </div>


                    <label for="" class="form-label lbl_slary mt-3">Salary Settings</label>
                    <div class="mb-3">
                    <?php
                    include 'config.php';
                        $query_settings = "SELECT * FROM `settings_company_tb`";
                        $result_Settings = mysqli_query($conn, $query_settings);

                        $row_settings = mysqli_fetch_assoc($result_Settings);
                    
                    ?>
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="col_salary_settings" id="btnradio1" value="Fixed Salary" autocomplete="off" <?php if($row_settings['col_salary_settings'] === 'Fixed Salary'){echo 'checked';} ?> >
                        <label class="btn btn-outline-warning radio_btn" for="btnradio1">Fixed Salary</label>

                        <input type="radio" class="btn-check" name="col_salary_settings" id="btnradio3" value="Days Worked" autocomplete="off" <?php if($row_settings['col_salary_settings'] === 'Days Worked'){echo 'checked';} ?>>
                        <label class="btn btn-outline-warning radio_btn" for="btnradio3">Days Worked</label>
                    </div>
                    </div>
            
                </div><!--first_cont-->
                
                <div class="emp_logo_second_cont"> 
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
                        <div class="logo_emp_head">
                          <img src="data:<?php echo $image_type; ?>;base64,<?php echo $image_data; ?>">
                          <div class="photo_upload">
                            <input type="file" name="photo" accept="image/jpeg, image/png, image/webp" value="">
                            <p class="file_guidance">Please upload a JPG, PNG, or WebP file.</p>
                          </div>
                        </div>
                    </div>


                  
            </div><!--setting content--->
          
            
            <div class="">
              <button type="submit" name="update_company" class="btn btn-primary update_btn mb-3" id="update-button">Update</button>
            </div>
          
          </div> <!--for-head-->
    </form>
            <form action="Data Controller/Settings/Save_company.php" method="POST" enctype="multipart/form-data">
                  <div class="row bootsrow">
                    <h3 class="holiday_pay_role">Holiday Pay Rule</h3>
                      <div class="col-4 text_col">

                      <?php
                        include "config.php";
                        $query = "SELECT holiday_pay FROM settings_tb LIMIT 1";
                        $result = mysqli_query($conn, $query);
                        $row = mysqli_fetch_assoc($result);
                        $databaseValue = $row['holiday_pay'];
                      ?>

  <div class="check_before_holiday">
      <div class="input-group-text check1">
          <input class="form-check-input mt-0" type="checkbox" name="name_before" value="Holiday Before" aria-label="Checkbox for following text input" <?php if ($databaseValue === 'Holiday Before') echo 'checked'; ?>>
          <input type="text" class="form-control" aria-label="Text input with checkbox" readonly value="Holiday Before">         
      </div>
  </div>

  <div class="check_after_holiday">
      <div class="input-group-text check2">
          <input class="form-check-input mt-0" type="checkbox" name="name_after" value="Holiday After" aria-label="Checkbox for following text input" <?php if ($databaseValue === 'Holiday After') echo 'checked'; ?>>
          <input type="text" class="form-control" aria-label="Text input with checkbox" readonly value="Holiday After">
      </div>
  </div>

  <div class="check_both_holiday">
      <div class="input-group-text check3">
          <input class="form-check-input mt-0" type="checkbox" name="name_beforeAfter" value="Holiday Before and After" aria-label="Checkbox for following text input" <?php if ($databaseValue === 'Holiday Before and After') echo 'checked'; ?>>
          <input type="text" class="form-control" aria-label="Text input with checkbox" readonly value="Holiday Before and After">
      </div>
  </div>


                                                  <!------------------ after and before holiday END ---------------->
                                                <button type="submit" name="name_btn_submit" class="btn btn-primary custom_btn">Save</button>
                      </div> <!--  end Col-4 -->

                      <div class="col-8">
                        
                      </div> 
                    
                  </div> <!--  end row -->    
            </form>
            
            
          </div> <!--  end Card-body -->
      </div> <!--  end Card -->
  </div> <!--  end Container -->


  <script>
    const name_before = document.querySelector('input[name="name_before"]');
    const name_after = document.querySelector('input[name="name_after"]');
    const name_beforeAfter = document.querySelector('input[name="name_beforeAfter"]');
    const description = document.getElementById('id_desc');
    
    name_before.addEventListener('click', function() {
      if (this.checked) {
        name_after.checked = false;
        name_beforeAfter.checked = false;
        description.value = 'Before Holiday is checked, it means in HOLIDAY PAY, employee must PRESENT day BEFORE the Holiday';
      } else if (!name_after.checked && !name_beforeAfter.checked) {
        description.value = 'There is no checked in the checkboxes, it means the HOLIDAY PAY is set to Default. The Holiday pay will be applied in all types.';
      }
    });
    
    name_after.addEventListener('click', function() {
      if (this.checked) {
        name_before.checked = false;
        name_beforeAfter.checked = false;
        description.value = 'After Holiday is checked, it means in HOLIDAY PAY, employee must PRESENT day AFTER the Holiday';
      } else if (!name_before.checked && !name_beforeAfter.checked) {
        description.value = 'There is no checked in the checkboxes, it means the HOLIDAY PAY is set to Default. The Holiday pay will be applied in all types.';
      }
    });

    name_beforeAfter.addEventListener('click', function() {
      if (this.checked) {
        name_before.checked = false;
        name_after.checked = false;
        description.value = 'Before After Holiday is checked, it means in HOLIDAY PAY, employee must PRESENT day BEFORE & AFTER the Holiday';
      } else if (!name_before.checked && !name_after.checked) {
        description.value = 'There is no checked in the checkboxes, it means the HOLIDAY PAY is set to Default. The Holiday pay will be applied in all types.';
      }
    });
  </script>



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