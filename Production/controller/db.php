<?php

require_once  __DIR__ . '/db_connect.php';


class MySql extends Db_connect{

    private $serverData;

    protected $Db;

    function  __construct(){
    }

    

    // Function to insert record into DB 
    // Arguments:
    // 1. $userData - array holding user data
    // 2. $userType - type of use e.g KTP, Phd, Visitor an etc
    // 3. $supervisorAdd - optional argment to add or not to add supervisor
    // 4. $roomKeyAdd - optional argment to add or not to add key

    public function insertUserForm($userData, $userType, $supervisorAdd =false, $roomKeyAdd =false) {

        $this->Db = $this->OpenCon();

        $resultArray = [];

        // start transaction
        $this->Db->begin_transaction();
        try{

            // insert expression

            // insert expression
            $stmtPerson = $this->Db->prepare("INSERT INTO Person(essexAccount, userName, userSurname, userType) VALUES (?,?,?,?)");
            $stmtPerson->bind_param('ssss',  $userData['account'], $userData['name'], $userData['surname'], $userType);
            $stmtPerson->execute();
            // store insterted personID
            $personID = $this->Db->insert_id;

            $resultArray["recordID"] = $personID;


            $stmtRequesterCheck = "SELECT requesterID FROM Requester WHERE requestedby='" . $userData['requestBy'] . "';";
            $data = $this->Db->query($stmtRequesterCheck);
            $requesterID; 

            if($data->num_rows == 0) {
                $stmtRequester = $this->Db->prepare("INSERT INTO Requester(requestedby, requesterEmail) VALUES (?,?)");
                $stmtRequester->bind_param('ss',  $userData['requestBy'], $userData['requestEmail']);
                $stmtRequester->execute();
                // store insterted requesterID 
                $requesterID = $this->Db->insert_id;
            } else {
                $requesterID = $data->fetch_assoc()["requesterID"];
                print_r($requesterID);
            }
            
            // insert expressions
            $stmt = $this->Db->prepare("INSERT INTO PersonToRequester(personID, requesterID) VALUES (?,?)");
            $stmt->bind_param('ii',  $personID, $requesterID);
            $stmt->execute();

            $stmt = $this->Db->prepare("INSERT INTO PersonToLocation(personID, location) VALUES (?,?)");
            $stmt->bind_param('is',  $personID, $userData['location']);
            $stmt->execute();

            $stmt = $this->Db->prepare("INSERT INTO PersonToDisability(personID, disability) VALUES (?,?)");
            $stmt->bind_param('is',  $personID, $userData['disability']);
            $stmt->execute();

            $stmt = $this->Db->prepare("INSERT INTO PersonToEquipment(personID, equipment, INVNumbers) VALUES (?,?,?)");
            $stmt->bind_param('iss',  $personID, $userData['equipmentList'], $userData['INVNumbers']);
            $stmt->execute();

            $stmt = $this->Db->prepare("INSERT INTO PersonToTime(personID, startDate, endDate) VALUES (?,?,?)");
            $stmt->bind_param('iss',  $personID, $userData['startDate'], $userData['endDate']);
            $stmt->execute();

            $stmt = $this->Db->prepare("INSERT INTO PersontToStatus(personID, reqStatus, submitedDate, lastupdate) VALUES (?,?,?,?)");
            $currentDate = date('Y-m-d');
            $status = "pending";
            $stmt->bind_param('isss',  $personID, $status, $currentDate, $currentDate);
            $stmt->execute();

            // check if it is requred to insert supervisor 
            if($supervisorAdd){
                $stmt = $this->Db->prepare("INSERT INTO PersonToSupervisor(personID, supervisor) VALUES (?,?)");
                $stmt->bind_param('is',  $personID, $userData['supervisor']);
                $stmt->execute();
            }
            
            // check if it is requred to insert key 
            if($roomKeyAdd){
                $stmt = $this->Db->prepare("INSERT INTO PersonToKey(personID, roomKey) VALUES (?,?)");
                $stmt->bind_param('is',  $personID, $userData['roomKey']);
                $stmt->execute();
            }
            // end transaction
            $this->Db->commit();

        } catch(Exception $e){
            $this->jsonResponse($e);
            // end transaction
            $this->Db->rollback();
            $resultArray["status"] = false;
            return $resultArray;
        } finally{
            $this->Db->close();
            $resultArray["status"] = true;
            return $resultArray;
        }
    }

    // Function to update record data in DB
    // Arguments:
    // 1. $userData - array holding user data
    // 2. $id - personID to find record
    // 3. $supervisorAdd - optional argment to add or not to add supervisor
    // 4. $roomKeyAdd - optional argment to add or not to add key

    public function updateUserForm($userData, $id, $supervisorAdd =false, $roomKeyAdd =false) {

        $this->Db = $this->OpenCon();
        $this->Db->begin_transaction();

        $resultArray = [];

        try{
            $stmtPerson = $this->Db->prepare("UPDATE Person SET essexAccount=?, userName=?, userSurname=? WHERE personID=?");
            $stmtPerson->bind_param('sssi',  $userData['account'], $userData['name'], $userData['surname'], $id);
            $stmtPerson->execute();

            $resultArray["recordID"] = $id;

            $stmt = "SELECT requesterID FROM PersonToRequester WHERE personID=" . $id;
            $requesterID = $this->Db->query($stmt)->fetch_assoc()['requesterID']; 

            $stmt = $this->Db->prepare("UPDATE Requester SET requestedby=?, requesterEmail=? WHERE requesterID =?;");
            $stmt->bind_param('ssi', $userData['requestedby'], $userData['requesterEmail'], $requesterID);
            $stmt->execute();


            $stmt = $this->Db->prepare("UPDATE PersonToLocation SET location=? WHERE personID=?");
            $stmt->bind_param('si', $userData['location'], $id);
            $stmt->execute();

            $stmt = $this->Db->prepare("UPDATE PersonToDisability SET disability=? WHERE personID=?");
            $stmt->bind_param('si',$userData['disability'], $id);
            $stmt->execute();

            $stmt = $this->Db->prepare("UPDATE PersonToEquipment SET equipment=?, INVNumbers=? WHERE personID=?");
            $stmt->bind_param('ssi', $userData['equipmentList'], $userData['INVNumbers'], $id);
            $stmt->execute();

            $stmt = $this->Db->prepare("UPDATE PersonToTime SET startDate=?, endDate=? WHERE personID=?");
            $stmt->bind_param('ssi', $userData['startDate'], $userData['endDate'], $id);
            $stmt->execute();

            $stmt = $this->Db->prepare(" UPDATE PersontToStatus SET lastupdate=? WHERE personID =?");
            $currentDate = date('Y-m-d');
            $status = "pending";
            $stmt->bind_param('si', $currentDate, $id);
            $stmt->execute();

            if($supervisorAdd){
                $stmt = $this->Db->prepare("UPDATE PersonToSupervisor SET supervisor=? WHERE personID =?");
                $stmt->bind_param('si', $userData['supervisor'], $id);
                $stmt->execute();
            }

            if($roomKeyAdd){
                
                $stmt = "SELECT roomKey FROM PersonToKey WHERE personID=" . $id;
                $record = $this->Db->query($stmt)->fetch_assoc(); 
                if($record) {
                    // Updated
                    $stmt = $this->Db->prepare("UPDATE PersonToKey SET roomKey=? WHERE personID =?");
                    $stmt->bind_param('si', $userData['roomKey'], $id);
                    $stmt->execute();
                } else {
                    // Inserted
                    $stmt = $this->Db->prepare("INSERT INTO PersonToKey(personID, roomKey) VALUES (?,?)");
                    $stmt->bind_param('is',  $id, $userData['roomKey']);
                    $stmt->execute();
                }
            }

            $this->Db->commit();
        } catch(Exception $e){
            $this->jsonResponse($e);
            $this->Db->rollback();
            $this->Db->close();
            $resultArray["status"] = false;
            return $resultArray;
        } finally{
            $this->Db->close();
            $resultArray["status"] = true;
            return $resultArray;
        }
    }

    // Function to display all record list in admin panel 
    // Arguments:

    public function getAllPendingUserRequestInfo() {

        $this->Db = $this->OpenCon();
        try{
            $stmt = "SELECT Person.personID, Person.userName, Person.userSurname , Person.essexAccount, Person.userType, PersonToTime.startDate, PersontToStatus.submitedDate, PersontToStatus.lastupdate from Person 
            INNER JOIN PersonToTime ON Person.personID = PersonToTime.personID
            INNER JOIN PersontToStatus ON Person.personID = PersontToStatus.personID WHERE PersontToStatus.reqStatus='pending';";
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

    public function getAllApproveUserRequestInfo() {

        $this->Db = $this->OpenCon();
        try{
            $stmt = "SELECT Person.personID, Person.userName, Person.userSurname , Person.essexAccount, Person.userType, PersonToTime.startDate ,PersontToStatus.submitedDate, PersontToStatus.lastupdate from Person
             INNER JOIN PersonToTime ON Person.personID = PersonToTime.personID
             INNER JOIN PersontToStatus ON Person.personID = PersontToStatus.personID WHERE PersontToStatus.reqStatus='approve';";
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

    // Function to display all record list in directory page
    // Arguments:

    public function getUserDirectory() {

        $this->Db = $this->OpenCon();
        try{
            $stmt = "SELECT Person.personID, Person.userName, Person.userSurname, Person.essexAccount, Person.userType, PersonToTime.startDate, PersonToTime.endDate, PersonToLocation.location, PersontToStatus.reqStatus from Person 
            INNER JOIN PersonToTime ON Person.personID = PersonToTime.personID
            INNER JOIN PersonToLocation ON Person.personID = PersonToLocation.personID
            INNER JOIN PersontToStatus ON Person.personID = PersontToStatus.personID;";
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

    public function getUserDirectoryVisitor() {
        $this->Db = $this->OpenCon();
        try{
            $stmt = "SELECT Person.personID, Person.userName, Person.userSurname, Person.essexAccount, Person.userType, PersonToTime.startDate, PersonToTime.endDate, PersonToLocation.location, PersontToStatus.reqStatus from Person 
            INNER JOIN PersonToTime ON Person.personID = PersonToTime.personID
            INNER JOIN PersonToLocation ON Person.personID = PersonToLocation.personID
            INNER JOIN PersontToStatus ON Person.personID = PersontToStatus.personID
            WHERE Person.userType = 'Visitor';";
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

    public function getUserFilterByStartDate($numberOfDays_Start, $numberOfDays_End) {
        $this->Db = $this->OpenCon();
        try{
            $stmt = "SELECT Person.personID, Person.userName, Person.userSurname, Person.essexAccount, Person.userType, PersonToTime.startDate, 
            PersonToTime.endDate, PersonToLocation.location, PersontToStatus.reqStatus, 
            fLOOR(DATEDIFF(PersonToTime.startDate, CURRENT_DATE)) as d1
            FROM Person 
             INNER JOIN PersonToTime ON Person.personID = PersonToTime.personID
             INNER JOIN PersonToLocation ON Person.personID = PersonToLocation.personID
             INNER JOIN PersontToStatus ON Person.personID = PersontToStatus.personID
            HAVING d1 > " . $numberOfDays_Start ." AND d1 <= " . $numberOfDays_End . ";";
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

    public function getUserFilterByEndDate($numberOfDays_Start, $numberOfDays_End) {
        $this->Db = $this->OpenCon();
        try{
            $stmt = "SELECT Person.personID, Person.userName, Person.userSurname, Person.essexAccount, Person.userType, PersonToTime.startDate, 
            PersonToTime.endDate, PersonToLocation.location, PersontToStatus.reqStatus, 
            fLOOR(DATEDIFF(PersonToTime.endDate, CURRENT_DATE)) as d1
            FROM Person 
             INNER JOIN PersonToTime ON Person.personID = PersonToTime.personID
             INNER JOIN PersonToLocation ON Person.personID = PersonToLocation.personID
             INNER JOIN PersontToStatus ON Person.personID = PersontToStatus.personID
            HAVING d1 > " . $numberOfDays_Start ." AND d1 <= " . $numberOfDays_End . ";";
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


    public function getUserDirectoryPhd() {
        $this->Db = $this->OpenCon();
        try{
            $stmt = "SELECT Person.personID, Person.userName, Person.userSurname, Person.essexAccount, Person.userType, PersonToTime.startDate, PersonToTime.endDate, PersonToLocation.location, PersontToStatus.reqStatus from Person 
            INNER JOIN PersonToTime ON Person.personID = PersonToTime.personID
            INNER JOIN PersonToLocation ON Person.personID = PersonToLocation.personID
            INNER JOIN PersontToStatus ON Person.personID = PersontToStatus.personID
            WHERE Person.userType = 'Phd Student';";
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

    public function getUserDirectoryFix() {
        $this->Db = $this->OpenCon();
        try{
            $stmt = "SELECT Person.personID, Person.userName, Person.userSurname, Person.essexAccount, Person.userType, PersonToTime.startDate, PersonToTime.endDate, PersonToLocation.location, PersontToStatus.reqStatus from Person 
            INNER JOIN PersonToTime ON Person.personID = PersonToTime.personID
            INNER JOIN PersonToLocation ON Person.personID = PersonToLocation.personID
            INNER JOIN PersontToStatus ON Person.personID = PersontToStatus.personID
            WHERE Person.userType = 'Fix-term Researcher';";
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

    public function getUserDirectoryPro() {
        $this->Db = $this->OpenCon();
        try{
            $stmt = "SELECT Person.personID, Person.userName, Person.userSurname, Person.essexAccount, Person.userType, PersonToTime.startDate, PersonToTime.endDate, PersonToLocation.location, PersontToStatus.reqStatus from Person 
            INNER JOIN PersonToTime ON Person.personID = PersonToTime.personID
            INNER JOIN PersonToLocation ON Person.personID = PersonToLocation.personID
            INNER JOIN PersontToStatus ON Person.personID = PersontToStatus.personID
            WHERE Person.userType = 'Professional staff';";
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

    public function getUserDirectoryKTP() {
        $this->Db = $this->OpenCon();
        try{
            $stmt = "SELECT Person.personID, Person.userName, Person.userSurname, Person.essexAccount, Person.userType, PersonToTime.startDate, PersonToTime.endDate, PersonToLocation.location, PersontToStatus.reqStatus from Person 
            INNER JOIN PersonToTime ON Person.personID = PersonToTime.personID
            INNER JOIN PersonToLocation ON Person.personID = PersonToLocation.personID
            INNER JOIN PersontToStatus ON Person.personID = PersontToStatus.personID
            WHERE Person.userType = 'KTP Memeber';";
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

    public function getUserDirectoryByUserType($userType) {
        $this->Db = $this->OpenCon();
        try{
            $stmt = "SELECT Person.personID, Person.userName, Requester.requestedby, Person.userSurname, Person.essexAccount, Person.userType, PersonToTime.startDate, PersonToTime.endDate, PersonToLocation.location, PersontToStatus.reqStatus from Person 
            INNER JOIN PersonToTime ON Person.personID = PersonToTime.personID
            INNER JOIN PersonToLocation ON Person.personID = PersonToLocation.personID
            INNER JOIN PersontToStatus ON Person.personID = PersontToStatus.personID
            INNER JOIN PersonToRequester ON Person.personID = PersonToRequester.personID
            INNER JOIN Requester ON PersonToRequester.requesterID = Requester.requesterID
            WHERE PersontToStatus.reqStatus='pending' AND Requester.requestedby = '" . $userType . "';";
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

    // Function to display all informaation about specific record
    // Arguments:
    // 2. $id - personID to find record

    public function getUserDataID($id) {

        $this->Db = $this->OpenCon();
        try{
            $stmt = "SELECT Person.personID, Person.userName, Person.userSurname, Person.essexAccount, Person.userType, PersonToTime.startDate, PersonToTime.endDate, PersonToLocation.location, PersonToDisability.disability,PersonToEquipment.equipment, PersonToEquipment.INVNumbers, Requester.requestedby, Requester.requesterEmail, PersonToSupervisor.supervisor, PersonToKey.roomKey from Person 
            INNER JOIN PersonToTime ON Person.personID = PersonToTime.personID
            INNER JOIN PersonToLocation ON Person.personID = PersonToLocation.personID
            INNER JOIN PersonToDisability ON Person.personID = PersonToDisability.personID
            left JOIN PersonToEquipment ON Person.personID = PersonToEquipment.personID
            INNER JOIN PersonToRequester ON Person.personID = PersonToRequester.personID
            INNER JOIN Requester ON Requester.requesterID = PersonToRequester.requesterID
            LEFT JOIN PersonToSupervisor ON Person.personID = PersonToSupervisor.personID
            LEFT JOIN PersonToKey ON Person.personID = PersonToKey.personID
            WHERE Person.personID=". $id .";";
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

    // Function to delete record from db
    // Arguments:
    // 2. $id - personID to find record
    
    public function deletUser($id){
        $this->Db = $this->OpenCon();
        $this->Db->begin_transaction();
        try{  

            $stmt = $this->Db->prepare("DELETE FROM PersonToLocation WHERE personID=?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            $stmt = $this->Db->prepare("DELETE FROM PersonToDisability WHERE personID=?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            
            $stmt = $this->Db->prepare("DELETE FROM PersonToTime WHERE personID=?");
            $stmt->bind_param('i', $id);
            $stmt->execute();


            $stmt = $this->Db->prepare("DELETE FROM PersonToEquipment WHERE personID=?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            $stmtSelect = "SELECT PersonToRequester.personID, PersonToRequester.requesterID from PersonToRequester WHERE personID =". $id .";";
            $dataRequesterID = $this->Db->query($stmtSelect);
            $data = $dataRequesterID->fetch_assoc();
            
            $stmtSelect = "SELECT PersonToRequester.personID, PersonToRequester.requesterID from PersonToRequester WHERE requesterID =". $data["requesterID"] .";";
            $dataRequesterRecords = $this->Db->query($stmtSelect);


            if ($dataRequesterRecords->num_rows > 1) {
                $stmt = $this->Db->prepare("DELETE FROM PersonToRequester WHERE personID=?");
                $stmt->bind_param('i', $id);
                $stmt->execute();
            } else {
                $stmt = $this->Db->prepare("DELETE FROM PersonToRequester WHERE personID=?");
                $stmt->bind_param('i', $id);
                $stmt->execute();

                $stmt = $this->Db->prepare("DELETE FROM Requester WHERE requesterID=?");
                $stmt->bind_param('i', $data["requesterID"]);
                $stmt->execute();
            }


            $stmt = $this->Db->prepare("DELETE FROM PersonToRequester WHERE personID=?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            $stmt = $this->Db->prepare("DELETE FROM PersontToStatus WHERE personID=?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            $stmt = $this->Db->prepare("DELETE FROM Person WHERE personID=?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            $this->Db->commit();

        }
        catch(Exception $e){
            $this->jsonResponse($e);
            print_r($e);
            $this->Db->rollback();
            return false;
        }
        finally{
            $this->Db->close();
            return true;
        }
    }

    public function statusSwitch($id, $name, $surname, $requestBy) {
        $this->Db = $this->OpenCon();
        $this->Db->begin_transaction();

        try{

            $stmt = "SELECT reqStatus FROM PersontToStatus WHERE personID=" . $id;
            $reqStatus = $this->Db->query($stmt)->fetch_assoc()['reqStatus'];


            if ($reqStatus == "pending") {
                $stmtPerson = $this->Db->prepare("UPDATE PersontToStatus SET reqStatus=? WHERE personID=?");
                $status = "approve";
                $stmtPerson->bind_param('si', $status , $id);
                $stmtPerson->execute();

            } else if($reqStatus == "approve") {
                $stmtPerson = $this->Db->prepare("UPDATE PersontToStatus SET reqStatus=? WHERE personID=?");
                $status = "pending";
                $stmtPerson->bind_param('si', $status , $id);
                $stmtPerson->execute();
            }

            $this->Db->commit();

            if ($reqStatus == "pending") {
                require_once './../controller/email.php';
                $emailService = new emailService();
                $emailService->approveUserEmail($id, $name, $surname, $requestBy);
                header("Location: https://csee.essex.ac.uk/OSS/view/requests_table.php");
            }

            if ($reqStatus == "approve") {
                require_once './../controller/email.php';
                $emailService = new emailService();
                $emailService->refuseUserEmail($id, $name, $surname, $requestBy);
                header("Location: https://csee.essex.ac.uk/OSS/view/requests_table.php");
            }
            return true;
        } catch(Exception $e){
            $this->jsonResponse($e);
            $this->Db->rollback();
            return false;
        } finally{
            $this->Db->close();
        }


    }



    private function jsonResponse($data) {
        if (is_array($data)){
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            $data = ["text" => $data];
            $this->jsonResponse($data);
        }
    }



}


?>