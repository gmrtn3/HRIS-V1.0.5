
<?php
    session_start();

    
    if(!isset($_SESSION['username'])){
        header("Location: login.php"); 
    }
 
    $server = "localhost";
    $user = "root";
    $pass ="";
    $database = "hris_db";

    $db = mysqli_connect($server, $user, $pass, $database);


    if(!empty($_GET['status'])){
        switch($_GET['status']){
            case 'succ':
                $statusType = 'alert-success';
                $statusMsg = 'Members data has been imported successfully.';
                break;
            case 'err':
                $statusType = 'alert-danger';
                $statusMsg = 'Some problem occurred, please try again.';
                break;
            case 'invalid_file':
                $statusType = 'alert-danger';
                $statusMsg = 'Please upload a valid CSV file.';
                break;
            default:
                $statusType = '';
                $statusMsg = '';
        }
    }
?>

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
    <link rel="stylesheet" href="css/styles.css"> 
    <title>HRIS | Employee List</title>
</head>
<script>
      // Function to display the current date in the specified format
      function displayCurrentDate() {
        // Get the current date
        const today = new Date();

        // Define the date format as "MM/DD/YYYY"
        const dateFormat = `${today.getMonth() + 1}/${today.getDate()}/${today.getFullYear()}`;

        // Update the content of the h1 element with the current date
        document.getElementById("current-date").innerHTML = `Today's date is <strong style=" color: rgb(154, 67, 224); ">${dateFormat}</strong>`;
      }
    </script>


<body onload="displayCurrentDate()">

    <header>
        <?php include("header.php")?>
    </header>

    <div class="attendace-container">
        <div class="attendance-title">
            <h1>Attendance</h1>
        </div>

        <div class="attendance-input">
            <div>
                <div class="att-emp">
                <?php
                        $server = "localhost";
                        $user = "root";
                        $pass ="";
                        $database = "hris_db";

                        $conn = mysqli_connect($server, $user, $pass, $database);
                        $sql = "SELECT empid, fname, lname FROM employee_tb";
                        $result = mysqli_query($conn, $sql);

                        $options = "";
                        while ($row = mysqli_fetch_assoc($result)) {
                            $options .= "<option value=' ". $row['empid'] . "'>". $row['empid'] . " ". " - ". " " .$row['fname']. " ".$row['lname']. "</option>";
                        }
                        ?>

                        <label for="emp">Select Employee
                        <select name="empname" id="" class="stat">
                            <option value disabled selected>Select Employee</option>
                            <?php echo $options; ?>
                        </select>
                        </label>
                </div>
              
                <div class="att-stat" >
                    <label for="Employee" >Status
                    <select name="" id="" class="custom-select" >
                        <option value="">All Status</option>
                    </select>   
                    </label>
                </div>
                

            </div>

            <div>
                <div class="att-range">                   
                        <label for="Employee">Date Range
                        <input type="date" name="" id="" placeholder="Start Date" style="padding:10px; ">
                        </label>
                </div>

                
                <input class="att-end" type="date" name="" id="" placeholder="End Date" style="padding:10px; ">
            </div>

            
                <div class="att-excel-input">
                    <form action="Data Controller/Attendance/attImportController.php"  enctype="multipart/form-data" method="POST">
                            <input type="file" name="file" />
                            <input type="submit" value="Submit" name="importSubmit" class="btn btn-primary">
                    </form>
                </div>
          

        </div>

        <div class="att-date">
            <h1 id="current-date"></h1>
        </div>
        
    
        <div class="att-search">
        <input class="employeeList-search" type="text" placeholder="&#xF002; Search" style="font-family:Arial, FontAwesome;" id="search" style="outline:none;"/>
        </div>
       
        <table class="table table-hover" id="att-table">
            <thead>
                <th>Status</th>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Time in</th>
                <th>Time out</th>
                <th>Late</th>
                <th>Early Out</th>
                <th>Overtime</th>
                <th>Total Work</th>
                <th>Total Rest</th>
            </thead>
            <tbody id="myTable">
                <?php
                $result = $db->query("SELECT * FROM attendances 
                                     AS att
                                     INNER JOIN employee_tb
                                     AS emp
                                     ON(att.empid = emp.empid)
                                     ORDER BY date ASC");

                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        ?>
                        <tr>
                            <td style="font-weight: 400;"><?php echo $row['status'];?></td>
                            <td style="font-weight: 400;"><?php echo $row['empid']; ?></td>
                            <td style="font-weight: 400;"><?php echo $row['fname']; ?> <?php echo $row['lname']?> </td>
                            <td style="font-weight: 400;"><?php echo $row['date']; ?></td>
                            <td style="font-weight: 400;"><?php echo $row['time_in']; ?></td>
                            <td style="font-weight: 400;"><?php echo $row['time_out']; ?></td>
                            <td style="font-weight: 400;"><?php echo $row['late']; ?></td>
                            <td style="font-weight: 400;"><?php echo $row['early_out']; ?></td>
                            <td style="font-weight: 400;"><?php echo $row['overtime']; ?></td>
                            <td style="font-weight: 400;"><?php echo $row['total_work']; ?></td>
                            <td style="font-weight: 400;"><?php echo $row['total_rest']; ?></td>
                        </tr> 
                        <?php        
                    }
                } else{
                    ?>
                    <tr>
                        <td colspan="11">No attendance found...</td>
                    </tr>

                <?php
                }
                ?>
                
            </tbody>

        </table>
    
        <div class="att-export-btn">
         <p>Export options: <a href="excel-att.php" class=""></i>Excel</a><span> |</span> <a href="#">PDF</a></p>
         
        </div>
    </div>
    
    
    

    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
    <script src="main.js"></script>

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

</body>
</html>