<?php 
    include "config.php";
    $id = $_GET['col_ID'];

if(isset($_POST['submit'])) {
    $dept_name = $_POST['dept_name'];



    $sql = "UPDATE `dept_tb` SET `col_deptname`='$dept_name' WHERE col_ID = $id";

    $result = mysqli_query($conn, $sql);

    if($result) {
        header("Location: dept.php?msg=Data updated successfully");
    }
    else {
        echo "Failed: " . mysqli_error($conn);
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="branch.css"/>
    <title>Edit Department</title>
</head>
<body>

    <nav class="navbar navbar-light justify-content-center fs-3 mb-5">
            DEPARTMENT
    </nav>

    <div class="container">
        <div class="text-center mb-4" >
            <h3>Edit Information</h3>
            <p class="text-muted">Click update after changing any information</p>
        </div>

        <?php

            $sql = "SELECT * FROM `dept_tb` WHERE col_ID = $id LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

        ?>


        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width:50vw; min-width:300px;">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Department Name:</label>
                        <input type="text" class="form-control" name="dept_name" value="<?php echo $row['col_deptname'] ?>" >
                    </div>

                
                    <div class="button_save">
                        <button type="submit" class="btn btn-success" name="submit">Update</button>
                        <a href="dept.php" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    


    <!--Bootstrap Js-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
     integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>