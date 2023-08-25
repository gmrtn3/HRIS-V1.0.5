
<?php
  session_start();
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
    } else {
        include 'config.php';
        include 'user-image.php';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">

        <!-- skydash -->

    <link rel="stylesheet" href="skydash/feather.css">
    <link rel="stylesheet" href="skydash/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="skydash/vendor.bundle.base.css">

    <link rel="stylesheet" href="skydash/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
   

    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/gnratepayrollVIEW.css">
    <link rel="stylesheet" href="css/schedule.css">
    <title>HRIS | Schedule</title>
</head>
<body>
    <header>
        <?php include("header.php")?>
    </header>

    

    <div class="payslip-container" style="height: 82vh; width: 60%; background-color: #fff;position:absolute; left: 18%; top: 13%;  box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17); border-radius: 0.8em">
        <div class="payslip-header border-bottom border-black p-4">
            <h3>PAYSLIP</h3>
        </div>
        <div class="header_view" style="width: 97%; margin:auto; margin-top: 2em">
          <img src="icons/logo_hris.png" width="70px" alt="">
          <p class="lbl_cnfdntial">CONFIDENTIAL SLIP</p>
        </div>

        <div class="container-fluid mt-3 ml-3" style="height: 5em">

            <div class="d-flex flex-row justify-content-between" style="width: 65%">
                <!-- backend to -->
                <p>Slash Tech Solutions</p>

                <!-- backend din -->
                <p>Pay Period: <span style="color: #7F7EC4">2023-08-18</span> to <span style="color: #7F7EC4">2023-08-18</span> </p>

                <!-- backend din -->
                <p>Payout: <span style="color: #7F7EC4">2023/08/18</span></p>
            </div>

            <div class="d-flex flex-row justify-content-between  mt-4" style="width: 50%">
                <!-- backend to -->
                <p>Employee ID: <span style="color: #7F7EC4">STS - 035</span></p>

                <!-- backend din -->
                <p style="margin-right: 11em">Employee Name <span style="color: #7F7EC4">JOSEPH </span> </p>

               
            </div>
            
        </div>

        <div class="container-fliud mt-3 border border-black" style="height: 60%; width: 95%; margin:auto; border-radius: 0.4em">
            <div class="w-100" style="height: 2.1em; background-color: #ececec; border-radius: 0.4em 0.4em 0 0;">
            </div>

            <div class="payslip-container-header container-fliud d-flex flex-row" style="height: 3.5em; width: 100%; border-bottom: #ced4da 1px solid">
                <div class="d-flex flex-row " style="width: 33.3%; height: 100%; border-right: #CED4DA 1px solid">
                    <p class="d-flex flex-row align-items-center justify-content-center" style="width: 33.3%; height: 100%">Earnings</p>
                    <p class="d-flex flex-row align-items-center justify-content-center" style="width: 33.3%; height: 100%">Hours</p>
                    <p class="d-flex flex-row align-items-center justify-content-center" style="width: 33.3%; height: 100%">Amount</p>
                </div>
                <div class="d-flex flex-row " style="width: 33.3%; height: 100%; ">
                    <p class="d-flex flex-row align-items-center justify-content-center" style="width: 50%; height: 100%">Earnings</p>
                    <p class="d-flex flex-row align-items-center justify-content-center" style="width: 50%; height: 100%">Hours</p>
                </div>
                <div class="d-flex flex-row " style="width: 33.3%; height: 100%; border-left: #CED4DA 1px solid">
                    <p class="d-flex flex-row align-items-center justify-content-center" style="width: 33.3%; height: 100%">Earnings</p>
                    <p class="d-flex flex-row align-items-center justify-content-center" style="width: 33.3%; height: 100%">Hours</p>
                    <p class="d-flex flex-row align-items-center justify-content-center" style="width: 33.3%; height: 100%">Amount</p>
                </div>
            </div>
            <div class="payslip-container d-flex flex-row" style="height: 10em; width: 100%">
                <div class="d-flex flex-row " style="width: 33.3%; height: 100%; border-right: #CED4DA 1px solid">
                    
                </div>
                <div class="d-flex flex-row " style="width: 33.3%; height: 100%; ">
                   
                </div>
                <div class="d-flex flex-row " style="width: 33.3%; height: 100%; border-left: #CED4DA 1px solid">
                  
                </div>
            </div>
        </div>
        

    </div>
    

    <!----------------------Script sa dropdown chain--------------------------->        
<script>
// Kapag nagbago ang pagpili sa select department dropdown
document.getElementById("select_department").addEventListener("change", function() {
    var departmentID = this.value; // Kunin ang value ng selected department

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var employees = JSON.parse(this.responseText);
            var employeeDropdown = document.getElementById("select_employee");
            employeeDropdown.innerHTML = ""; // I-clear ang current options

            // I-update ang employee dropdown base sa mga nakuha na empleyado
            if (departmentID == "All Department") {
                // Kapag "All Department" ang napili, ipakita ang "All Employee" kasama ang detalye ng bawat empleyado
                var allEmployeeOption = document.createElement("option");
                allEmployeeOption.value = "All Employee";
                allEmployeeOption.text = "All Employee";
                employeeDropdown.appendChild(allEmployeeOption);

                employees.forEach(function(employee) {
                    var option = document.createElement("option");
                    option.value = employee.empid;
                    option.text = employee.empid + " - " + employee.fname + " " + employee.lname;
                    employeeDropdown.appendChild(option);
                });
            } else {
                // Kapag ibang department ang napili, ipakita ang mga empleyado base sa department
                employees.forEach(function(employee) {
                    var option = document.createElement("option");
                    option.value = employee.empid;
                    option.text = employee.empid + " - " + employee.fname + " " + employee.lname;
                    employeeDropdown.appendChild(option);
                });
            }

            // I-enable ang employee dropdown
            employeeDropdown.disabled = false;
        }
    };
    xhttp.open("GET", "get_employees.php?departmentID=" + departmentID, true);
    xhttp.send();
});

function filterSched() {
        var department = document.getElementById('select_department').value;
        var employee = document.getElementById('select_employee').value;

        var url = 'Schedules.php?department_name=' + department + '&empid=' + employee;
        window.location.href = url;
    }
</script>
<!----------------------Script sa dropdown chain--------------------------->      

    <!------------------------------------Script para sa pag pop-up ng view modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.viewbtn').on('click', function(){
                 $('#view_rest_modal').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#view_reason1').val(data[3]);
               });
             });
</script>
<!---------------------------------End ng Script para sa pag pop-up ng view modal------------------------------------------>
    
    <script>
function populateDateFields(row) {
    var startDate = row.getElementsByTagName('td')[5].innerHTML;
    var endDate = row.getElementsByTagName('td')[6].innerHTML;

    document.getElementById('sched_from').value = startDate;
    document.getElementById('sched_to').value = endDate;
}

var updateButtons = document.getElementsByClassName('sched-update');
for (var i = 0; i < updateButtons.length; i++) {
    updateButtons[i].addEventListener('click', function() {
        var row = this.closest('tr');
        populateDateFields(row);
    });
}

function datevalidate() {
    var startDateInput = document.getElementById('sched_from');
    var endDateInput = document.getElementById('sched_to');
    var startDate = new Date(startDateInput.value);
    var endDate = new Date(endDateInput.value);
    var today = new Date();
    today.setHours(0, 0, 0, 0); // Reset time to midnight for comparison

    var startError = document.getElementById('sched_from_error');
    var endError = document.getElementById('sched_to_error');
    var submitBtn = document.getElementById('submit-btn');

    if (startDate < today) {
        startError.innerHTML = "Start Date must be today or a future date.";
    } else {
        startError.innerHTML = "";
    }

    if (endDate < startDate) {
        endError.innerHTML = "End Date must be equal to or greater than Start Date.";
    } else {
        endError.innerHTML = "";
    }

    if (startError.innerHTML !== "" || endError.innerHTML !== "") {
        submitBtn.disabled = true;
    } else {
        submitBtn.disabled = false;
    }
}
</script>

  

<script>
// sched form modal

let Modal = document.getElementById('schedules-modal-update');

//get open modal
let modalBtn = document.getElementById('sched-update');

//get close button modal
let closeModal = document.getElementsByClassName('sched-update-close')[0];

//event listener
modalBtn.addEventListener('click', openModal);
closeModal.addEventListener('click', exitModal);
window.addEventListener('click', clickOutside);

//functions
function openModal(){
    Modal.style.display ='block';
}

function exitModal(){
    Modal.style.display ='none';
}

function clickOutside(e){
    if(e.target == Modal){
        Modal.style.display ='none';    
    }
}
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
      $('#schedule-list-container').addClass('move-content');
    } else {
      $('#schedule-list-container').removeClass('move-content');

      // Add class for transition
      $('#schedule-list-container').addClass('move-content-transition');
      // Wait for transition to complete before removing the class
      setTimeout(function() {
        $('#schedule-list-container').removeClass('move-content-transition');
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

<script>
 //HEADER RESPONSIVENESS SCRIPT
 
 
$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
  $('.nav-link').on('click', function(e) {
    if ($(window).width() <= 390) {
      e.preventDefault();
      $(this).siblings('.sub-menu').slideToggle();
    }
  });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 390) {
      $('#sidebar').toggleClass('active-sidebars');
    }
  });
});


$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
  $('.nav-links').on('click', function(e) {
    if ($(window).width() <= 500) {
      e.preventDefault();
      $(this).siblings('.sub-menu').slideToggle();
    }
  });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 500) {
      $('#sidebar').toggleClass('active-sidebar');
    }
  });
});


</script>

<script> 
        $(document).ready(function(){
                $('.sched-update').on('click', function(){
                                    $('#schedUpdate').modal('show');
                                    $tr = $(this).closest('tr');

                                    var data = $tr.children("td").map(function () {
                                        return $(this).text();
                                    }).get();

                                    console.log(data);
                                    //id_colId
                                    $('#empid').val(data[9]);
                                    $('#sched_from').val(data[6]);
                                    $('#sched_to').val(data[7]);
                                    $('#empName').val(data[0]);
                                });
                            });
            
    </script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>

    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>

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