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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware Settings</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="bootstrap/vertical-layout-light/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/styles.css">


    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
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
    <link rel="stylesheet" href="css/try.css">


    <style>
    .container {
        position: absolute;
        top: 50px;
        left: 15%;
        border-radius: 8px;
        margin-top: 100px;
        margin-left: 100px;
        width: 1000px;
        height: 1000px;
    }

    p {
        font-size: 25px;
    }


    /* Additional styles for the form elements */
    .card-body form label {
        margin-bottom: 8px;
        width: 120px;

    }

    .old {
        flex-direction: column;
    }

    .card-body form input[type="password"] {
        padding: 8px;
        margin-bottom: 16px;
        border-radius: 20px;
    }

    .card-body form .submit {
        width: 100px;
        padding: 10px;
        background-color: #46f846;
        color: black;
        font-weight: bold;
        border: none;
        border-radius: 20px;
        cursor: pointer;
    }

    .card-body form .form-row {
        display: flex;
        align-items: center;
    }



    .clear {
        width: 180px;
        height: 40px;
        color: white;
        background-color: #FD4B4B;
        color: #FFF;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        border: 0px solid;
        font-size: 20px;
    }

    .save {
        width: 180px;
        height: 40px;
        color: white;
        background-color: #46f846;
        color: black;
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        border: 0px solid;
        font-size: 20px;
    }
    </style>

</head>

<body>

    <header>
        <?php
    include 'header.php';
    include 'SpecialFolders/BiometricsData/deleteRecords.php';
    include 'SpecialFolders/BiometricsData/deleteEmployee.php';
    include 'SpecialFolders/BiometricsData/changeDevicePassword.php';
   ?>
    </header>
    <div class="container">
        <div class="card">
            <div class="card-body" style="border-radius: 20px;">
                <p for="title" style="font-weight: bolder;">Change Device Password</p>
                <br>
                <form method="post" action="SpecialFolders/BiometricsData/changeDevicePassword.php">
                    <div class="forms">
                        <div class="old">
                            <label for="oldPassword">Old Password:</label>
                            <input type="password" name="oldPassword" id="oldPassword" required>
                        </div>
                        <div class="new">
                            <label for="newPassword">New Password:</label>
                            <input type="password" name="newPassword" id="newPassword" required>
                        </div>
                        <input type="submit" class="submit" name="submit" value="Submit">
                    </div>
                </form>
            </div>
            <div class="card-body">
                <p for="title" style="font-weight: bolder;">Hardware Database Management</p>
                <br>
                <div class="delRecords" style="display: flex; flex-direction: row;">
                    <form method="POST" action="SpecialFolders/BiometricsData/deleteRecords.php">
                        <button type="submit" class="clear" name="delete">Delete All Records</button>
                    </form>
                    <form method="POST" action="SpecialFolders/BiometricsData/deleteRecords.php"
                        style="margin-left: 10px;">
                        <input type="text" name="records" id="records"
                            style="width: auto; height: 40px; text-align:center;" placeholder="Input Employee ID"
                            required><button name="delRec"
                            style="width: 60px; height: 40px; color: white; background-color:#FD4B4B; margin-left:5px;">Delete</button></input>
                    </form>&nbsp;
                </div>
                <div class="delEmp" style="display: flex; flex-direction: row; margin-top: 20px;">
                    <form method="POST" action="SpecialFolders/BiometricsData/deleteAllEmployee.php">
                        <button class="clear" type="save" name="delete">Delete All Employee</button>
                    </form>
                    <form method="POST" action="SpecialFolders/BiometricsData/deleteEmployee.php"
                        style="margin-left: 10px;">
                        <input type="text" name="employee" id="employee"
                            style="width: auto; height: 40px; text-align:center;" placeholder="Input Employee ID"
                            required><button name="delEmp"
                            style="width: 60px; height: 40px; color: white; background-color:#FD4B4B;  margin-left:5px;">Delete</button></input>
                    </form>
                </div>
            </div>
            <!-- <div class="card-body">
                <p for="title" style="font-weight: bolder;">HRIS Database Management</p>
                <br>
                <form>
                    <button type="save" class="save">Save All Records</button>
                </form>&nbsp;
                <form>
                    <button type="save" class="save">Save All Employee</button>
                </form>
            </div> -->
        </div>
    </div>





    <script>
    $(document).ready(function() {
        $('#zipcode_id').on('input', function() {
            if ($(this).val().length > 4) {
                $(this).val($(this).val().slice(0, 4));
            }
        });
    });
    </script>

    <script>
    const name_before = document.querySelector('input[name="name_before"]');
    const name_after = document.querySelector('input[name="name_after"]');
    const name_beforeAfter = document.querySelector('input[name="name_beforeAfter"]');
    const description = document.getElementById('id_desc');

    name_before.addEventListener('click', function() {
        if (this.checked) {
            name_after.checked = false;
            name_beforeAfter.checked = false;
            description.value =
                'Before Holiday is checked, it means in HOLIDAY PAY, employee must PRESENT day BEFORE the Holiday';
        } else if (!name_after.checked && !name_beforeAfter.checked) {
            description.value =
                'There is no checked in the checkboxes, it means the HOLIDAY PAY is set to Default. The Holiday pay will be applied in all types.';
        }
    });

    name_after.addEventListener('click', function() {
        if (this.checked) {
            name_before.checked = false;
            name_beforeAfter.checked = false;
            description.value =
                'After Holiday is checked, it means in HOLIDAY PAY, employee must PRESENT day AFTER the Holiday';
        } else if (!name_before.checked && !name_beforeAfter.checked) {
            description.value =
                'There is no checked in the checkboxes, it means the HOLIDAY PAY is set to Default. The Holiday pay will be applied in all types.';
        }
    });

    name_beforeAfter.addEventListener('click', function() {
        if (this.checked) {
            name_before.checked = false;
            name_after.checked = false;
            description.value =
                'Before After Holiday is checked, it means in HOLIDAY PAY, employee must PRESENT day BEFORE & AFTER the Holiday';
        } else if (!name_before.checked && !name_after.checked) {
            description.value =
                'There is no checked in the checkboxes, it means the HOLIDAY PAY is set to Default. The Holiday pay will be applied in all types.';
        }
    });
    </script>



    <script>
    $('.header-dropdown-btn').click(function() {
        $('.header-dropdown .header-dropdown-menu').toggleClass("show-header-dd");
    });


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
    <script>
    // JavaScript to handle the button click and show/hide the container
    document.getElementById("popupButton").addEventListener("click", function() {
        var container = document.getElementById("popupContainer");
        if (container.style.display === "none") {
            container.style.display = "block";
        } else {
            container.style.display = "none";
        }
    });
    document.getElementById("closeButton").addEventListener("click", function() {
        var container = document.getElementById("popupContainer");
        container.style.display = "none";
    });
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
        integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
    </script>





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