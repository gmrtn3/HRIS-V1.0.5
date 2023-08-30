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
include 'config.php';

if (isset($_POST["import"])) {
  $fileName = $_FILES["file"]["tmp_name"];

  if ($_FILES["file"]["size"] > 0) {
      $file = fopen($fileName, "r");
      $firstRow = true; // Flag to skip the first row

      while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
          if ($firstRow) {
              $firstRow = false;
              continue; // Skip the first row
          }

          $unitType = $column[0];
          $unitQuantity = $column[1];
          $unitRate = $column[2];

          // Check if the unit type already exists in the piece_rate_tb table
          $checkSql = "SELECT * FROM piece_rate_tb WHERE unit_type = '$unitType'";
          $checkResult = mysqli_query($conn, $checkSql);

          if (mysqli_num_rows($checkResult) > 0) {
              // If the unit type already exists, update the existing record
              $updateSql = "UPDATE piece_rate_tb SET unit_quantity = '$unitQuantity', unit_rate = '$unitRate' WHERE unit_type = '$unitType'";
              $updateResult = mysqli_query($conn, $updateSql);

              if (!$updateResult) {
                  echo "Error updating data: " . mysqli_error($conn);
              }
          } else {
              // If the unit type doesn't exist, insert a new record
              $insertSql = "INSERT INTO piece_rate_tb (unit_type, unit_quantity, unit_rate) VALUES ('$unitType', '$unitQuantity', '$unitRate')";
              $insertResult = mysqli_query($conn, $insertSql);

              if (!$insertResult) {
                  echo "Error inserting data: " . mysqli_error($conn);
              }
          }
      }
      
      fclose($file);
  }
}



?>
<?php
        include 'config.php';
        $result = mysqli_query($conn, "SELECT * FROM piece_rate_tb");
        $row = mysqli_fetch_assoc($result);
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>


        <!-- skydash -->

    <link rel="stylesheet" href="skydash/feather.css">
    <link rel="stylesheet" href="skydash/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="skydash/vendor.bundle.base.css">

    <link rel="stylesheet" href="skydash/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
   

    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/piece_rate.css">
    <title>HRIS | Schedule</title>
</head>
<body>

<header>
        <?php include("header.php")?>
    </header>
    

    <!-- insert modal -->

    <form action="Data Controller/Piece Rate/insert.php" method="POST">
      <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="staticBackdropLabel" >Unit Piece</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="form-group">
                          <label for="">Unit Type:</label><br>
                          <input type="text" name="unit_type" class="form-control" required><br>
                          <label for="">Unit Quantity:</label><br>
                          <input type="text" name="unit_quantity" class="form-control"  oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);" required>
                          <label for="" class="mt-3">Unit Rate:</label><br>
                        <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="height: 50%">&#8369;</span>
                        </div>
                        <input type="text" name="unit_rate" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);" required>
                        </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary" >Submit</button>
                  </div>
              </div>
          </div>
      </div>
    </form>

    


    <!-- edit modal -->
    <form action="Data Controller/Piece Rate/getData.php" method="POST">
      <div class="modal fade" id="updateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updateLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                  <input type="text" id="id" name="id" style= "display: none;">
                      <h5 class="modal-title" id="updateLabel">Unit Piece</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="form-group">
                          <label for="">Unit Type:</label><br>
                          <input type="text" name="unit_type" class="form-control" id="unit_type">
                          <label for="" class="mt-3">Unit Quantity:</label><br>
                          <input type="text" name="unit_quantity" class="form-control" id="unit_quantity" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);">
                          <label for="" class="mt-3">Unit Rate:</label><br>
                          <div class="d-flex flex-row">
                          <span class="input-group-text" style="height: 100%; padding: 0.8em; padding-top: 0.7em" >&#8369;</span>
                            <input type="text" name="unit_rate" class="form-control" id="unit_rate" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);">
                          </div>
                          
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" name="updatedata" class="btn btn-primary" >Submit</button>
                  </div>
              </div>
          </div>
      </div>
    </form>

    <!-- delete modal -->

    <div class="modal fade" id="deletemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/Piece Rate/delete.php" method="POST">
      <div class="modal-body">

        <input type="hidden" name="id" id="delete_id">
       

        <h4>Do you want to delete?</h4>

      </div> <!--Modal body div close tag-->
      <div class="modal-footer">
       
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button type="submit" name="delete_data" class="btn btn-primary">Yes</button>
      </div>
      </form>


    </div>
  </div>
</div>


    <div class="pr-container">
        <div class="header-title">
            <h1 style="font-size: 32px">Piece Rate</h1>
            <div class="d-flex flex-row justify-content-between align-items-center" style="height: 4em; width: 50%;">
              <form action="" method="post" enctype="multipart/form-data" name="uploadCsv" class="form-horizoontal">
                <div class="d-flex flex-row justify-content-between w-100 align-items-center">
                  <input type="file" name="file" id="" class="form-control mr-3" accept=".csv">
                  <button type="submit" name="import" class="btn btn-primary">Import</button>
                  
                </div>
              </form>  
                <button  class="btn btn-primary btn_add mr-5 " data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="height: 3.3em">Add Unit Piece</button>
                
            </div>
        </div>
        <div class=" mt-5">
            <div class="table-responsive " id="table-responsive" >
                <table id="order-listing" class="table table-responsive" >
                    <thead>
                      <th style= 'display: none;'> ID  </th>  
                        <th>Unit Type</th>
                        <th>Unit Quantity</th>
                        <th>Quantity Rate</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        <?php
                        $server = "localhost";
                        $user = "root";
                        $pass = "";
                        $database = "hris_db";

                        $conn = mysqli_connect($server, $user, $pass, $database);

                        // Check if the connection was successful
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        $sql = "SELECT * FROM piece_rate_tb";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td style='display: none'>".$row['id']." </td>";
                                echo "<td style='font-weight: 400'>" . $row['unit_type'] . "</td>";
                                echo "<td style='font-weight: 400'>" . $row['unit_quantity'] . "</td>";
                                echo "<td style='font-weight: 400'>₱ " . $row['unit_rate'] . "</td>";
                                echo "<td style='font-weight: 400'> 
                                          <button class='editbtn' style='margin-right: 0.6em; border: none; background-color: inherit' > <i class='fa-solid fa-pen-to-square' style='font-size: 1.4em' title = 'Edit' data-bs-toggle='modal' data-bs-target='#updateModal'></i> </button>
                                          

                                          <button class='deletebtn'  title = 'Delete' data-bs-toggle='modal' data-bs-target='#deletemodal' style='border: none; background-color: inherit'> <i class='fa-sharp fa-solid fa-trash' style='font-size: 1.4em'></i> </button>
                                      </td>";
                                echo "</tr>";
                            }
                        } 

                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
            </div>
            <div class=" mt-3" style="margin-left: 2.4%">
              <p style="font-size: 1.1em">Export options: <button id="export-csv-btn" class="" style="color: green; border: none; background-color: inherit">CSV</button> | <button class="pdf " style="background-color:inherit; border: none; color: red" onclick="makePDF()">PDF</button></p>
            </div>
            
        </div>
    </div>

    <script>

window.html2canvas = html2canvas;
window.jsPDF = window.jspdf.jsPDF;

function makePDF() {
    html2canvas(document.querySelector("#order-listing"), {
        allowTaint: true,
        useCORS: true,
        scale: 1
    }).then(canvas => {
        var img = canvas.toDataURL("Payroll Attendance Report");
        
        // Set the PDF to landscape mode
        var doc = new jsPDF({
            orientation: 'landscape'
        });

        doc.setFont('Arial');
        doc.getFontSize(11);
        doc.addImage(img, 'PNG', 10, 10, 0,0);
        doc.save("Attendance Report.pdf");
    });
}
</script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function() {
    // Export button click event
    $('#export-csv-btn').click(function() {
        // Create a CSV content
        var csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Unit Quantity,Unit Type,Quantity Rate\n";

        // Loop through table rows and append data
        $('#order-listing tbody tr').each(function() {
            var unitQuantity = $(this).find('td:nth-child(2)').text();
            var unitType = $(this).find('td:nth-child(3)').text();
            var quantityRate = $(this).find('td:nth-child(4)').text().replace("₱ ", ""); // Remove currency sign
            csvContent += unitQuantity + "," + unitType + "," + quantityRate + "\n";
        });

        // Create a CSV blob and trigger a download
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "piece_rate_data.csv");
        document.body.appendChild(link);
        link.click();
    });
});
</script>


    <script>
            $(document).ready(function (){
                $('.deletebtn').on('click' , function(){
                    $('#deletemodal').modal('show');


                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function(){
                        return $(this).text();
                    }).get();

                    console.log(data);

                    $('#delete_id').val(data[0]);
                    
                    

                });
            });
        </script>

<script>
    $(document).ready(function(){
        $('.editbtn').on('click', function(){
            $('#updateModal').modal('show');
            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function () {
                return $(this).text();
            }).get();

            console.log(data);
            //id_colId
            $('#id').val(data[0]);
            $('#unit_type').val(data[1]);
            $('#unit_quantity').val(data[2]);

            // Remove the "₱" symbol and then set the value
            var unitRate = data[3].replace('₱', '');
            $('#unit_rate').val(unitRate);
        });
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