<?php
    include '../../config.php';
    $value = $_GET['value'];

    // $mysqli = new mysqli('localhost', 'root', '', 'hris_db');
    // if ($mysqli->connect_errno) {
    //   echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    //   exit();
    // }

    // // Prepare the SQL statement
    // $stmt = $mysqli->prepare("SELECT * FROM cutoff_tb WHERE col_year = ?");
    // $stmt->bind_param("s", $value);
    // $stmt->execute();

    // // Fetch the result
    // $stmt->bind_result($result);
    // $stmt->fetch();

    // // Close the statement and the database connection
    // $stmt->close();
    // $mysqli->close();

    // // Return the result back to the client
    // if ($result > 0) {
    //     echo $result;
    // } else {
    //     echo "not_exist";
    // }
    // $sql = mysqli_query($conn, " SELECT
    //     *  
    // FROM
    //     `cutoff_tb`
    // WHERE `col_year` = '$value'");
    // $result = $conn->query($sql);

    // // Check if any rows are fetched
    // if ($result->num_rows > 0) 
    // {
    //     while($row = $result->fetch_assoc()) 
    //     {
    //         echo $row['col_cutOffNum'];
    //         echo $row['_dateTime'];
    //     }
    //     // $row = mysqli_fetch_assoc($sql);
    //     // echo $row['col_cutOffNum'];
    //     // echo $row['_dateTime'];
    // } else {
    //     echo "No results found schedule.";
    // } 


    $query = " SELECT
         *  
    FROM
        `cutoff_tb`
    WHERE `col_year` = '$value'";
    $result = $conn->query($query);

    $cutffnum_Array = array(); // Array to store the dates

    // Check if any rows are fetched
    if ($result->num_rows > 0) 
    {
        // Loop through each row
        while($row = $result->fetch_assoc()) 
        {
            $cutffnum_Array[] = array('col_cutOffNum' => $row['col_cutOffNum']);
        }
        foreach ($cutffnum_Array as $cutffnum_Array) 
        {
            echo $cutffnum_Array['col_cutOffNum'];
        }
    }
?>