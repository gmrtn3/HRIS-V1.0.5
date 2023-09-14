<?php
   session_start();
//    if (!isset($_SESSION['username'])) {
//        header("Location: login.php");
//    } else {
//        // Check if the user's role is not "admin"
//        if ($_SESSION['role'] != 'admin') {
//            // If the user's role is not "admin", log them out and redirect to the logout page
//            session_unset();
//            session_destroy();
//            header("Location: logout.php");
//            exit();
//        } else {
//            include 'config.php';
//            include 'user-image.php';
//        }
//    }

include 'config.php';
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
    <title>HRIS | Cash Advance</title>
    <script>
        function closeErrorMessage() {
            document.getElementById('error-message').style.display = 'none';
            window.history.replaceState({}, document.title, 'cash_advance');
        }
    </script>
    
</head>
<body>

    <header>
        <?php include("header.php")?>
    </header>

    <style>
   

    .pagination{
        margin-right: 73px !important;
        
    }
    .sorting_asc{
        color: black !important;
    }

    .pagination li a{
        color: #c37700;
    }

        .page-item.active .page-link, .jsgrid .jsgrid-pager .active.jsgrid-pager-nav-button .page-link, .jsgrid .jsgrid-pager .active.jsgrid-pager-page .page-link, .page-item.active .jsgrid .jsgrid-pager .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button .page-item.active a, .jsgrid .jsgrid-pager .active.jsgrid-pager-nav-button a, .page-item.active .jsgrid .jsgrid-pager .jsgrid-pager-page a, .jsgrid .jsgrid-pager .jsgrid-pager-page .page-item.active a, .jsgrid .jsgrid-pager .active.jsgrid-pager-page a {
        z-index: 3;
        color: #fff;
        background-color: #000;
        border-color: #000;
    }

    
    
    #order-listing_next{
        margin-right: 28px !important;
        margin-bottom: -16px !important;

    }
    table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
                max-height: 450px;
                height: 450px;
                
                
            }
            thead{
                display: table;
                width: 100%;
            }
            tbody {
                display: table;
                width: 100%;
            }

            #table-responsiveness{
    overflow-y: hidden !important;
   }

   thead th:nth-child(1){
    width: 20% !important;
   }

   tr td:nth-child(1){
    width: 20% !important;
   }

   thead th:nth-child(2){
    width: 15% !important;
   }

   tr td:nth-child(2){
    width: 15% !important;
   }

   
   thead th:nth-child(3){
    width: 15% !important;
   }

   tr td:nth-child(3){
    width: 15% !important;
   }

   thead th:nth-child(4){
    width: 15% !important;
   }

   tr td:nth-child(4){
    width: 15% !important;
   }

   thead th:nth-child(5){
    width: 15% !important;
   }

   tr td:nth-child(5){
    width: 15% !important;
   }

   

     .pending {
        color: red;
    }

    .approved {
        color: green;
    }
           
           .pagination .page-item.active .page-link, .jsgrid .jsgrid-pager .page-item.active .page-link, .jsgrid .jsgrid-pager .active.jsgrid-pager-nav-button .page-link, .jsgrid .jsgrid-pager .active.jsgrid-pager-page .page-link, .pagination .page-item.active .jsgrid .jsgrid-pager .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .page-item.active .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button .page-item.active a, .jsgrid .jsgrid-pager .active.jsgrid-pager-nav-button a, .pagination .page-item.active .jsgrid .jsgrid-pager .jsgrid-pager-page a, .jsgrid .jsgrid-pager .page-item.active .jsgrid-pager-page a, .jsgrid .jsgrid-pager .jsgrid-pager-page .page-item.active a, .jsgrid .jsgrid-pager .active.jsgrid-pager-page a, .pagination .page-item:hover .page-link, .jsgrid .jsgrid-pager .page-item:hover .page-link, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button:hover .page-link, .jsgrid .jsgrid-pager .jsgrid-pager-page:hover .page-link, .pagination .page-item:hover .jsgrid .jsgrid-pager .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .page-item:hover .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button .page-item:hover a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button:hover a, .pagination .page-item:hover .jsgrid .jsgrid-pager .jsgrid-pager-page a, .jsgrid .jsgrid-pager .page-item:hover .jsgrid-pager-page a, .jsgrid .jsgrid-pager .jsgrid-pager-page .page-item:hover a, .jsgrid .jsgrid-pager .jsgrid-pager-page:hover a, .pagination .page-item:focus .page-link, .jsgrid .jsgrid-pager .page-item:focus .page-link, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button:focus .page-link, .jsgrid .jsgrid-pager .jsgrid-pager-page:focus .page-link, .pagination .page-item:focus .jsgrid .jsgrid-pager .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .page-item:focus .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button .page-item:focus a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button:focus a, .pagination .page-item:focus .jsgrid .jsgrid-pager .jsgrid-pager-page a, .jsgrid .jsgrid-pager .page-item:focus .jsgrid-pager-page a, .jsgrid .jsgrid-pager .jsgrid-pager-page .page-item:focus a, .jsgrid .jsgrid-pager .jsgrid-pager-page:focus a, .pagination .page-item:active .page-link, .jsgrid .jsgrid-pager .page-item:active .page-link, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button:active .page-link, .jsgrid .jsgrid-pager .jsgrid-pager-page:active .page-link, .pagination .page-item:active .jsgrid .jsgrid-pager .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .page-item:active .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button .page-item:active a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button:active a, .pagination .page-item:active .jsgrid .jsgrid-pager .jsgrid-pager-page a, .jsgrid .jsgrid-pager .page-item:active .jsgrid-pager-page a, .jsgrid .jsgrid-pager .jsgrid-pager-page .page-item:active a, .jsgrid .jsgrid-pager .jsgrid-pager-page:active a {
   background-color: #000 !important;
   color: white !important;
}
</style>

    <!-- Modal -->
    
        <!-- <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"> -->
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Pakyawan Cash Advance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <?php 
                            include 'config.php';

                            $approverEmpid = $_SESSION['empid'];

                            $sql = "SELECT * FROM employee_tb
                                    INNER JOIN approver_tb ON employee_tb.empid = approver_tb.empid 
                                    WHERE classification = 3 AND approver_tb.approver_empid = $approverEmpid";

                            $result = mysqli_query($conn, $sql);
                           
                            $options = "";
                            while ($row = mysqli_fetch_assoc($result)) {
                                $options .= "<option value='".$row['empid']."'>".$row['fname']."  ".$row['lname']."</option>";
                            }


                        ?>
                         <label for="">Select Pakyawan Employee</label><br>
                        <select name="empid" id="" class="form-select" required>
                        <option value="" disabled selected>Select Employee</option> 
                            <?php echo $options ?>
                        </select><br>

                        <label for="">Date</label><br>
                        <input type="date" name="date" id="" class="form-control" required><br>

                        <label for="">Cash</label><br>
                        <input type="text" name="cash_advance" id="" class="form-control" required oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);"><br>

                        <button class="btn btn-info w-100 text-white" id="loanForecastButton">Loan Forecast</button>

                        <input type="hidden" name="status" value="Pending">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btn_save" name="btn_save" class="btn btn-primary">Submit</button>
                </div>
                </div>
            </div>
        </div>
  
        <form action="Data Controller/Pakyawan/cash_advance_insert.php" method="POST">
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loanForecastModalLabel">Loan Forecast</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="empid">Select Pakyawan Employee</label><br>
                        <select name="empid" id="empid" class="form-select">
                            <option value="" disabled selected>Select Employee</option>
                            <?php echo $options ?>
                        </select><br>

                        <label for="">Date</label>
                        <input type="date" name="date" id="" class="form-control"><br>
                        
                        <label for="">Cash</label><br>
                        <input type="text" name="cash_advance" id="" class="form-control" required oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);">
                        <input type="hidden" name="status" value="Pending">
                
                        <div id="forecastContainer"></div>
                        <input type="hidden" name="selected_empid" id="selectedEmpidInput" value="" readonly>
                    </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const empidSelect = document.getElementById("empid");
                const forecastContainer = document.getElementById("forecastContainer");
                const selectedEmpidInput = document.getElementById("selectedEmpidInput");

                empidSelect.addEventListener("change", function () {
                    const selectedEmpid = empidSelect.value;

                    if (selectedEmpid) {
                        selectedEmpidInput.value = selectedEmpid; // Set the value in the input field
                        fetchForecast(selectedEmpid);
                    } else {
                        forecastContainer.innerHTML = "";
                        selectedEmpidInput.value = ""; // Clear the input field if no employee selected
                    }
                });

                function fetchForecast(empid) {
                    const url = `forecast_endpoint.php?empid=${empid}`;

                    fetch(url)
                        .then(response => response.text())
                        .then(data => {
                            forecastContainer.innerHTML = data;
                        })
                        .catch(error => {
                            console.error("Error fetching forecast:", error);
                        });
                }
            });
        </script>
    </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id="btn_save" name="btn_save" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const loanForecastButton = document.getElementById("loanForecastButton");
        const originalModal = new bootstrap.Modal(document.getElementById("staticBackdrop"));
        const loanForecastModal = new bootstrap.Modal(document.getElementById("loanForecastModal"));

        loanForecastButton.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent form submission
            originalModal.hide();
            loanForecastModal.show();
        });

        document.getElementById("loanForecastModal").addEventListener("hidden.bs.modal", function () {
            originalModal.show();
        });
    });
</script>


<!-- edit modal -->
<form action="Data Controller/Pakyawan/cash_advance_edit.php" method="POST">
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="empid" id="pakyaw">
            <input type="hidden" name="id" id="id">
            <!-- <input type="text" name="" id="cash"> -->
            <!-- <input type="text" name="" id="caDate"> -->
            <div class="form-group">
                <label for="">Name</label><br>
                <input type="text" name="" id="name" class="form-control" readonly><br>

                <label for="">Cash Advance</label><br>
                <input type="text" name="cash_advance"  id="cash" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);"><br>

                <label for="">CA Date</label><br>
                <input type="date" name="date" id="caDate" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="updatedata" class="btn btn-primary">Update</button>
        </div>
        </div>
    </div>
    </div>
</form>




    <div class="gen-payslip">
        <div class="loanreq-container">
            <div class="loanreq-title">
                <h1 style="font-size: 1.8em">Pakyawan Cash Advance</h1>
                
                <div></div>
            </div>
           
                <?php
                if(isset($_GET['error'])){
                    echo ' <div class="error-handler w-100 mb-3" style="height: 3em" id="error-message">';
                        echo '<div class="w-100 bg-danger d-flex flex-row align-items-center justify-content-between pl-4 pr-4" style="height: 100%">';
                            echo '<p class="error-message" style="font-size: 0.9em; color: #fff;">There was an error in the input. </p>';
                            echo '<button style="background-color: inherit; border: none; color: #fff; font-size: 1.1em"onclick="closeErrorMessage()"> <i class="fa-solid fa-x"></i> </button>';
                        echo '</div>';
                    echo '</div>';
                }
                ?>
           
            <div class="loanreq-inputs d-flex" style="width: 95%; margin:auto;">
                <button class="btn btn-primary p-3" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Create New</button>
                <!-- <input class="employeeList-search" type="text" placeholder="&#xF002; Search" style="font-family:Arial, FontAwesome" id="search"/> -->
            </div>

            <div class="table-responsive" id="table-responsiveness" style="width: 95%; height: 100%; margin:auto; margin-top: 30px; overflow-y: hidden;">
            <table id="order-listing" class="table table-responsive" style="width: 100%;">
                <thead style="background-color: #f4f4f4;">
                    <th>Name</th>
                    <th style="" >Cash Advance</th>
                    <th>CA Date</th>
                    <th>Date Filed</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th class="d-none">id</th>
                    <th style="display:none">Empid</th>
                </thead>
                <tbody>
                    <?php 

                        $empid = $_SESSION['empid'];
                        // echo $empid;
                       include 'config.php';
                       $result = $conn->query("SELECT pakyaw.empid , pakyaw.status, pakyaw.cash_advance, pakyaw.date, pakyaw.id, pakyaw.timestamp, emp.empid, emp.fname, emp.lname FROM pakyaw_cash_advance_tb AS pakyaw
                                                INNER JOIN employee_tb AS emp ON pakyaw.empid = emp.empid");
                                
                        
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){ 
                               
                    ?>
                    <tr>  
                        <td style="font-weight: 400"><?php echo $row['fname']?> <?php echo $row['lname'] ?></td> 
                        <td style="font-weight: 400">₱ <?php echo $row['cash_advance']?></td>
                        <td style="font-weight: 400"><?php echo $row['date']?></td>
                        
                        <td style="font-weight: 400"><?php echo date('Y-m-d', strtotime($row['timestamp'])); ?></td>
                        <td style="font-weight: 400; <?php
                            if ($row['status'] === 'Rejected') {
                                echo 'color: red;';
                            } elseif ($row['status'] === 'Approved') {
                                echo 'color: green;';
                            } elseif ($row['status'] === 'Pending') {
                                echo 'color: orange;';
                            }
                        ?>">
                            <?php echo $row['status'] ?>
                        </td>
                        <td style="font-weight: 400; outline:none;"><button type='button' data-bs-toggle='modal' data-bs-target='#editModal' id='editModal' class='editModal' style='border:none; background-color:inherit; color:cornflowerblue; outline:none;'>Edit</button></td>
                        <td class="d-none"><?php echo $row['id']?></td>
                        <td style="font-weight: 400; display:none"><?php echo $row['empid']; ?></td> 
                    </tr>
                    <?php 
                            }
                        } else{
                           
                        }     
                        ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function(){
            $('.editModal').on('click', function(){
                $('#editModal').modal('show');
                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function (){
                    return $(this).text();
                }).get();

                console.log(data);
                $('#name').val(data[0]);
                var cashValue = data[1].replace('₱', '').trim();
        
                // Set the value in the input field
                $('#cash').val(cashValue);
                $('#caDate').val(data[2]);
                $('#id').val(data[6]);
                $('#pakyaw').val(data[7]);
            });
        });
    </script>

    
<script type="text/javascript">
        $(document).ready(function(){
            $('#search').keyup(function(){
                search_table($(this).val());
            });

            function search_table(value){
                $('#myTable tr').each(function(){
                    var found = 'false';
                    $(this).each(function(){
                        if($(this).text().toLowerCase().indexOf(value.toLowerCase())>= 0){
                            found = 'true';
                        }
                    });
                    if(found == 'true'){
                        $(this).show();
                    }else{
                        $(this).hide();
                    }
                });
            }
        });

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
