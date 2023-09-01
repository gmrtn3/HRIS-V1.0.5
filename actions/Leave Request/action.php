<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <title>Action for Leave Request</title>
</head>

<style>
    .card{
      box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 20px 0 rgba(0, 0, 0, 0.17);
      width: 100%;
      height: 780px;
      padding: 20px;
      top: 75px;
      margin-left: 3%;
      
    }
</style>

<body>


<?php
session_start();
                if(isset($_POST['view_data'])){

                    $tbreqLeave_ID = $_POST['name_ID_tb'];
                    $emp_dept = $_POST['name_empID_tb'];

                    $_SESSION["ID_empId"] =   $emp_dept; //employee ID
                    $_SESSION["ID_applyleave"] =  $tbreqLeave_ID;


                    include '../../config.php';
                    //select for employee and apply leave tb db

                    $result = mysqli_query($conn, "SELECT
                                                        applyleave_tb.col_ID,
                                                        applyleave_tb.`col_req_emp`,
                                                        CONCAT(
                                                            employee_tb.`fname`,
                                                            ' ',
                                                            employee_tb.`lname`
                                                        ) AS `full_name`,
                                                        employee_tb.email,
                                                        employee_tb.`gender`,
                                                        employee_tb.`contact`,
                                                        applyleave_tb.`col_LeaveType`,
                                                        applyleave_tb.`col_strDate`,
                                                        applyleave_tb.`_datetime`,
                                                        applyleave_tb.`col_status`,
                                                        applyleave_tb.`col_reason`,
                                                        applyleave_tb.`col_PAID_LEAVE`,
                                                        applyleave_tb.col_approver,
                                                        applyleave_tb.`col_LeavePeriod`
                                                        
                                                    FROM
                                                        applyleave_tb
                                                    INNER JOIN employee_tb ON applyleave_tb.col_req_emp = employee_tb.empid
                                                    WHERE applyleave_tb.col_ID=  $tbreqLeave_ID;");
                    $row = mysqli_fetch_assoc($result);
                    $_SESSION["col_status"] =  $row['col_status'];
            //------------------------------------------------break-----------------------------------------------------

    //para sa pag select sa data  ng nasa action taken tb

            $result_actionTAken = mysqli_query($conn, "SELECT
                                                *               
                                            FROM
                                            actiontaken_tb
                                            WHERE col_applyID =  $tbreqLeave_ID;");
                                    $row_action = mysqli_fetch_assoc($result_actionTAken);

    //para sa pag select sa data  ng nasa action taken tb END
                                   
                }  

            ?>

    <div class="container mt-3 mb-3">
            <!--<form action="reject.php" method="post">-->
            
                <div class="card border-light">
                    <div class="">
                        <div class="row">
                            <div class="col-6">
                                <h2 style="font-size: 23px; font-weight: bold;">
                                    Leave Details
                                </h2>
                            </div> <!--end col-6-->
                            <div class="col-6 text-end">
                                <a href='../../leavereq.php' class='btn btn-outline-danger'>Go back</a>
                                
                            </div> <!--end col-6-->
                        </div> <!--end row-->
                    </div> <!--end card header-->
                    <div class="card-body">

                        <div class="row">
                            <div class="col-4">
                                <div class="mb-3">
                                    
                                <input type="text" readonly style=' text-transform: capitalize;' class="form-control bg-light -subtle" value=" <?php echo $row['full_name']?>">
                                    
                                    <label for="Select_dept" class="form-label">Employee Fullname :</label>
                                </div>  <!-- First mb-3 end-->
                            </div> <!-- col-4 end-->
                            <!----------------------------------Break------------------------------------->

                            <div class="col-4">
                                <div class="mb-3">
                                    <input type="text" readonly class="form-control bg-light -subtle" value=" <?php echo $row['email']?> ">
                                    <label for="Select_dept" class="form-label">Email Address :</label>
                                </div>  <!-- First mb-3 end-->
                            </div> <!-- col-4 end-->
                            <!----------------------------------Break------------------------------------->

                            <div class="col-4">
                                <div class="mb-3">
                                    <input type="text" readonly class="form-control bg-light -subtle"  value=" <?php echo $row['gender']?>">
                                    <label for="Select_dept" class="form-label">Gender :</label>
                                </div>  <!-- First mb-3 end-->
                            </div> <!-- col-4 end-->
                            <!----------------------------------Break------------------------------------->


                        </div> <!-- row end-->
                        
        <!-------------------------------------------------ROW1 Break------------------------------------------------------->

                        <div class="row">
                            <div class="col-3">
                                <div class="mb-3">
                                    <input type="text"  readonly class="form-control bg-light -subtle" value=" <?php echo $row['contact']?>"> 
                                    <label for="Select_dept" class="form-label ">Employee Phone Number :</label>
                                </div>  <!-- First mb-3 end-->
                            </div> <!-- col-4 end-->
                            <!----------------------------------Break------------------------------------->
                            
                            <div class="col-3">
                                <div class="mb-3">
                                    <input type="text"  readonly class="form-control bg-light -subtle"  value=" <?php echo $row['col_LeaveType']?>">
                                    <label for="Select_dept" class="form-label">Leave Type:</label> 
                                </div>  <!-- First mb-3 end-->
                            </div> <!-- col-4 end-->
                            <!----------------------------------Break------------------------------------->

                            <div class="col-3">
                                <div class="mb-3">
                                    <input type="text" readonly class="form-control bg-light -subtle" value=" <?php echo $row['_datetime']?>">
                                    <label for="Select_dept" class="form-label">Applied Date :</label>
                                </div>  <!-- First mb-3 end-->
                            </div> <!-- col-4 end-->
                            <div class="col-3">
                                <div class="mb-3"> 
                                    <input type="text" class="form-control bg-light -subtle" value="<?php echo $row['col_LeavePeriod']?>" readonly>
                                    <label for="Select_dept" class="form-label">Leave Period :</label>
                                </div>  <!-- First mb-3 end-->
                            </div> <!-- col-3 end-->
                            <!----------------------------------Break------------------------------------->
                        </div> <!-- row end-->

            <!-------------------------------------------------ROW2 Break------------------------------------------------------->

                        <div class="row">
                            <div class="col-4">
                                <label for="Select_dept" class="form-label mt-5 ml-5">Employee Leave Reason :</label> 
                            </div> <!-- col-4 end-->
                            <!----------------------------------Break------------------------------------->
                            
                            <div class="col-8">
                                <div class="form-floating ">
                                    <textarea class="form-control bg-light -subtle" readonly   name="name_txtRSN" placeholder="Leave a comment here" id="id_reason"   style=" max-width: 100% ; min-width: 100% ; max-height: 150px ; min-height: 150px ;"><?php echo $row['col_reason']?></textarea>
                                </div>  <!-- First mb-3 end-->
                            </div> <!-- col-4 end-->
                            <!----------------------------------Break------------------------------------->
                        </div> <!-- row end-->

            <!-------------------------------------------------ROW3 Break------------------------------------------------------->


            
                        <div class="row mt-4">
                            <div class="col-3">
                                <div class="mb-3">
                                    <input type="text" class="form-control bg-light -subtle" readonly value= "<?php 
                                    error_reporting(E_ERROR | E_PARSE);
                                    if($row_action['_datetime'] == NULL){
                                        
                                        echo 'No Data';
                                    }else{
                                        echo $row_action['_datetime'];
                                    }?>">
                                    <label for="Select_dept" class="form-label">Action Taken :</label>
                                </div>  <!-- First mb-3 end-->
                            </div> <!-- col-3 end-->
                            <!----------------------------------Break------------------------------------->
                            
                            <div class="col-3">
                                <div class="mb-3">
                                    <input type="text"  id="id_leaveStats" class="form-control bg-light -subtle"  readonly value=" <?php echo $row['col_status']?>" 
                                    style="<?php 
                                                if($row['col_status'] === 'Approved'){
                                                    echo 'style="color: blue;  text-align: center;"';
                                                }
                                            ?>">
                                    <label for="Select_dept" class="form-label">Leave Status:</label>
                                </div>  <!-- First mb-3 end-->
                            </div> <!-- col-3 end-->
                            <!----------------------------------Break------------------------------------->

                            <div class="col-3">
                                <div class="mb-3">
                                <?php 
                                            $approver = $row['col_approver'];
                                            if ($approver === ''){
                                                $approver_fullname = 'none';
                                            }
                                            else{
                                                $result_approver = mysqli_query($conn, " SELECT
                                                *  
                                            FROM
                                                employee_tb
                                            WHERE empid = $approver");
                                            if(mysqli_num_rows($result_approver) > 0) {
                                                $row_approver = mysqli_fetch_assoc($result_approver);
                                                //echo $row__leaveINFO['col_vctionCrdt'];
                                                $approver_fullname = $row_approver['fname'] . " " . $row_approver['lname'];
                                            } else {
                                                $approver_fullname = 'Something Went Wrong';
                                            } 
                                            }
                                        ?>
                                    <input type="text" class="form-control bg-light -subtle" value="<?php echo $approver_fullname; ?>" readonly>
                                    <label for="Select_dept" class="form-label">Approver :</label>
                                </div>  <!-- First mb-3 end-->
                            </div> <!-- col-3 end-->
                            <!----------------------------------Break------------------------------------->

                            <div class="col-3">
                                <div class="mb-3"> 
                                    <input type="text" class="form-control bg-light -subtle" value="<?php echo $row['col_PAID_LEAVE']?>" readonly>
                                    <label for="Select_dept" class="form-label">Leave Request Type :</label>
                                </div>  <!-- First mb-3 end-->
                            </div> <!-- col-3 end-->
                        </div> <!-- row end-->
                     


                        

                        <!-------------------------------------------------ROW4 Break------------------------------------------------------->

                        <div class="row">
                            <div class="col-4">
                                <label for="Select_dept" class="form-label mt-5 ml-5">Remarks:</label> 
                            </div> <!-- col-4 end-->
                            <!----------------------------------Break------------------------------------->
                            
                            <div class="col-8">
                                <div class="form-floating ">
                                    <textarea class="form-control bg-light -subtle" readonly   name="name_txtremarks"  id="id_reason"   style=" max-width: 100% ; min-width: 100% ; max-height: 150px ; min-height: 150px;"><?php
                                    error_reporting(E_ERROR | E_PARSE);
                                    if($row_action['col_remarks'] == NULL){
                                        
                                        echo 'No Data';
                                    }else{
                                        echo $row_action['col_remarks'];
                                    }
                                    ?>
                                    </textarea>
                                </div>  <!-- First mb-3 end--> 
                            </div> <!-- col-4 end-->
                            <!----------------------------------Break------------------------------------->
                        </div> <!-- row end-->

            <!-------------------------------------------------ROW5 Break------------------------------------------------------->

                </div> <!-- card body end-->
                    <div class="card-footer text-end">
                        <?php
                            if($row['col_status'] === 'Approved' || $row['col_status'] === 'Rejected' || $row['col_status'] === 'Cancelled'){
                                echo ' <button id="btn_reject" data-bs-toggle="modal" data-bs-target="#Mdl_reasonDecline" type="button" class="btn btn-outline-danger" onclick="click_btnReject()" style= " margin-right: 20px; display:none;">Reject</button>';
                                echo '<button id="btn_Approved"  data-bs-toggle="modal" data-bs-target="#Mdl_reasonApproved" type="button" class="btn btn-outline-success" onclick="click_btnApproved()" style= " margin-right: 20px; display:none;">Approve</button>';
                            }
                            else{
                                echo ' <button id="btn_reject" data-bs-toggle="modal" data-bs-target="#Mdl_reasonDecline" type="button" class="btn btn-outline-danger" onclick="click_btnReject()" style= " margin-right: 20px;">Reject</button>';
                                echo '<button id="btn_Approved"  data-bs-toggle="modal" data-bs-target="#Mdl_reasonApproved" type="button" class="btn btn-outline-success" onclick="click_btnApproved()" style= " margin-right: 20px;">Approve</button>';
                            }
                        ?>
                       

                    </div>

                 <!--------------------------------------- Modal Rejection REason start -------------------------------------->
                                <div class="modal fade" id="Mdl_reasonDecline" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="reject.php" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Rejection Reason</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-floating">
                                                        <textarea class="form-control" name="name_rjectResn" placeholder="Type your rejection reason..." id="floatingTextarea" required></textarea>
                                                        <label for="floatingTextarea">Reasons:</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit"  name="name_rejected" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            <!---------------------------------------------- Modal Reject------------------------------------------->

                                                        <!---------- Modal Approved REason start ------->
                                <div class="modal fade" id="Mdl_reasonApproved" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="approval.php" method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Approval Remarks</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-floating">
                                                        <textarea class="form-control" name="name_approvedtResn" placeholder="Type your rejection reason..." id="floatingTextarea" required></textarea>
                                                        <label for="floatingTextarea">Remarks:</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit"  name="name_approved" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                          <!------------------------------ Approved REason Modal end -------------------------------------->

                </div> <!--end card-->

    </div> <!--end container-->
   

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

</body>
    <script src="js/action.js"></script>
</html>