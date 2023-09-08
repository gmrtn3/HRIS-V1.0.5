<?php
    $emp_id = isset($_GET['empid']) ? $_GET['empid'] : '0';
    $conn = mysqli_connect("localhost", "root", "", "hris_db");
    $query = "SELECT * FROM employee_tb WHERE empid = $emp_id";
    $result = $conn->query($query);

        if($result->num_rows > 0){
            while ($row = $result->fetch_assoc()){

                echo "<div>";
                echo "<label for='username'> Firstname: ".$row['fname']."</label>";
                echo "<input type='hidden' id='username' name='username' value=".$row['fname'].">";
                echo "</div>";

                echo "<div>";
                echo "<label for='lastname'> Lastname: ".$row['lname']."</label>";
                echo "<input type='hidden' name='lastname' value=".$row['lname'].">";
                echo "</div>";

                echo "<div>";
                echo "<label for='id'>Employee ID: ".$row['empid']."</label>";
                echo "<input type='hidden' name='id' value=".$row['empid'].">";
                echo "</div>";

                echo "<div>";
                echo "<label for='contact'>Employee ID: ".$row['contact']."</label>";
                echo "<input type='hidden' name='contact' value=".$row['contact'].">";
                echo "</div>";

                echo "<div>";
                echo "<br><label for='password'>Enter a Password: </label>";
                echo "<input type='password' name='password' >";
                echo "</div>";
                }

                echo "<input type='submit' name='add' value='Add'>";
            }
            else{
                echo "<h3>No info available</h3>";
            }
?>
