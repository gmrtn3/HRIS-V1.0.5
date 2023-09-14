<?php 

    $server = "localhost";
    $user = "root";
    $pass ="";
    $database = "hris_db";

    // $server = "172.16.2.45";
    // $user = "root";
    // $pass = "RAKabo64657";
    // $database = "hris_db";

    $conn = mysqli_connect($server, $user, $pass, $database);

    if(!$conn){
        echo '<script type="text/javascript">';
        echo 'alert("Connection Failed.");';
        echo '</script>';
        die;
}