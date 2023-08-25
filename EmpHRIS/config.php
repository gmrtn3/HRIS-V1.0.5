<?php 

    $server = "localhost";
    $user = "root";
    $pass ="";
    $database = "hris_db";

    $conn = mysqli_connect($server, $user, $pass, $database);

    if(!$conn){
        echo '<script type="text/javascript">';
        echo 'alert("Connection Failed.");';
        echo '</script>';
        die;
}