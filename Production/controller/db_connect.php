<?php

// Change connection information for DB here

class Db_connect{

   protected function OpenCon() {
        $dbhost = "localhost";
        $dbuser = "ossadmin";
        $dbpass = "0wKF6dLvuLjqrsMLMEOIId3vl";
        $db = "oso";
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
    
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
        }
    
        return $conn;
    }

}



?>