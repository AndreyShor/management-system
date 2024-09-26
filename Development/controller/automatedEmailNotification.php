<?php
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "zsazsa159";
    $db = "oso";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
    }


    // Notification and check for 31 days = 1 month
    $DATA = $mysql->getUserFilterByEndDate(28);

    foreach ($DATA as $d) {
        if ($d["reqStatus"] = "approve") {
            notificationEmail($d["personID"], $d, "1 month");
        }
    }

    // Notification and check for 90 days = 3 month

    $DATA = $mysql->getUserFilterByEndDate(90);

    foreach ($DATA as $d) {
        if ($d["reqStatus"] = "approve") {
            notificationEmail($d["personID"], $d, "3 month");
        }
    }

    // Notification and check for 180 days = 6 month

    $DATA = $mysql->getUserFilterByEndDate(180);

    foreach ($DATA as $d) {
        if ($d["reqStatus"] = "approve") {
            notificationEmail($d["personID"], $d, "3 month");
        }
    }

    



    function getUserFilterByEndDate($numberOfDays_End) {
        $this->Db = $this->OpenCon();
        try{
            $stmt = "SELECT Person.personID, Person.userName, Person.userSurname, Person.essexAccount, Person.userType, PersonToTime.startDate, 
            PersonToTime.endDate, PersonToLocation.location, PersontToStatus.reqStatus, 
            fLOOR(DATEDIFF(PersonToTime.startDate, CURRENT_DATE)) as d1
            FROM Person 
             INNER JOIN PersonToTime ON Person.personID = PersonToTime.personID
             INNER JOIN PersonToLocation ON Person.personID = PersonToLocation.personID
             INNER JOIN PersontToStatus ON Person.personID = PersontToStatus.personID
            HAVING d1 = " . $numberOfDays_End. ";";
            $data = $this->Db->query($stmt);
            if ($data) {
                $allData = [];
                while($row = $data->fetch_assoc()){
                    array_push($allData, $row);
                }
                return $allData;
            } else {
                throw new Exception("Error in Fetching of data" . $this->Db->error);
            }

        } catch(Exception $e){
            $this->jsonResponse($e);
        } finally{
            $this->Db->close();
        }
    }

    function notificationEmail($id, $userData, $userType, $duration, $destination = "as19028@essex.ac.uk") {
        $from = "no-replyt@essex.ac.uk";
        $to = $destination;
        $subject = "User  - " . $userData['name'] . " " .  $userData['surname'] . " will finish in " . $duration;
        $message = "User Information for  " . $userData['name'] . " " .  $userData['surname'] . " will expire on " .  $userData['endDate'];

        $message .= "Please collect key from " . $userData['name'] . " " .  $userData['surname'] . " \r\n";


        $message .= "\r\n";

        $message .= "Kind Regards, \r\n";
        $message .= "Operation Support System \r\n";

        $header = "From: " . $from . " \r\n";
        $header .= "MIME-Version: 1.0  \r\n";
        $header .= "Content-type: text/plain \r\n";
    
        $rc = mail($to, $subject, $message, $header);
}
?>