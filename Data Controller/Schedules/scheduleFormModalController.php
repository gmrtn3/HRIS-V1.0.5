<?php
    include_once '../../config.php';

    class DataController {

        //  ------------ [ Language Listing ] ------------------------
            public function getLanguages() {
                $data            =           array();
                $db              =           new DBController();
                $conn            =           $db->connect();

                $sql             =           "SELECT * FROM language";
                $result          =           $conn->query($sql);
                if($result->num_rows > 0) {
                    $data        =           mysqli_fetch_all($result, MYSQLI_ASSOC);
                }
              
               $db->close($conn);
               return $data;
            }
    }
?>