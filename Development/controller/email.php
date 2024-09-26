<?php

        // template for email
        
        class emailService{

                private $serverData;
            
                function  __construct(){
                }

        
                // Arguments:

                // 2. $id - personID to find record
                // 2. $userData - array holding user data
                // 3. $destination - destination email. 
                // 4. $userType - type of user. ph, ktp

                public function addUserEmail($id, $userData, $userType, $destination = "as19028@essex.ac.uk") {
                        $from = "no-replyt@essex.ac.uk";
                        $to = $destination;
                        $subject = "New User - " . $userData['name'] . " " .  $userData['surname'];
                        $message = "New " . $userType . " was added to a system by ". $userData['requestBy'] .", please approve information! \r\n\r\n";
                        $message .= "Link to user (Please Log In and copy past link): " . "http://localhost/view/user_page.php?id=" . $id . "\r\n";

                        $message .= "\r\n";

                        $message .= "Kind Regards, \r\n";
                        $message .= "Operation Support System \r\n";
                
                        $header = "From: " . $from . " \r\n";
                        $header .= "MIME-Version: 1.0  \r\n";
                        $header .= "Content-type: text/plain \r\n";
                    
                        $rc = mail($to, $subject, $message, $header);
                }

                // Arguments:

                // 2. $id - personID to find record
                // 2. $userData - array holding user data
                // 3. $destination - destination email. 
                // 4. $userType - type of user. ph, ktp

                public function updateUserEmail($id, $userData, $userType, $destination = "as19028@essex.ac.uk") {
                        $from = "no-replyt@essex.ac.uk";
                        $to = $destination;
                        $subject = "Updated - " . $userData['name'] . " " .  $userData['surname'] . " by ". $userData['requestByLocal'];
                        $message .= "User Information for  " . $userData['name'] . " " .  $userData['surname'] . " was updated. \r\n";
                        $message .= "Link to user (Please Log In and copy past link): " . "http://localhost/view/user_page.php?id=" . $id . "\r\n";

                        $message .= "\r\n";

                        $message .= "Kind Regards \r\n";
                        $message .= "Operation Support System \r\n";
                
                        $header = "From: " . $from . " \r\n";
                        $header .= "MIME-Version: 1.0  \r\n";
                        $header .= "Content-type: text/plain \r\n";
                    
                        $rc = mail($to, $subject, $message, $header);
                }

                
                // Arguments:

                // 2. $id - personID to find record
                // 2. $userData - array holding user data
                // 3. $destination - destination email. 
                // 4. $userType - type of user. ph, ktp

                public function deleteUserEmail($id, $name, $surname, $requestBy, $destination = "as19028@essex.ac.uk") {
                        $from = "no-replyt@essex.ac.uk";
                        $to = $destination;
                        $subject = "Deleted - " . $name . " " .  $surname;
                        $message = "User Information for  " . $name . " " .  $surname . " was deleted by " . $requestBy;

                        $message .= "\r\n";
                        $message .= "\r\n";

                        $message .= "Kind Regards, \r\n";
                        $message .= "Operation Support System \r\n";
                
                        $header = "From: " . $from . " \r\n";
                        $header .= "MIME-Version: 1.0  \r\n";
                        $header .= "Content-type: text/plain \r\n";
                    
                        $rc = mail($to, $subject, $message, $header);
                }

                                // Arguments:

                // 2. $id - personID to find record
                // 2. $userData - array holding user data
                // 3. $destination - destination email. 
                // 4. $userType - type of user. ph, ktp

                public function approveUserEmail($id, $name, $surname, $requestBy, $destination = "as19028@essex.ac.uk") {
                        $id = str_replace(' ', '', $id);
                        $from = "no-replyt@essex.ac.uk";
                        $to = $destination;
                        $subject = "User - " . $name . " " .  $surname . " was approved by administrator";
                        
                        $message = "Approved. " . $requestBy . " \r\n";
                        $message .= "Link to user (Please Log In and copy past link): " . "http://localhost/view/user_page.php?id=" . $id . "\r\n";
                        $message .= "\r\n";

                        $message .= "Kind Regards, \r\n";
                        $message .= "Operation Support System \r\n";
                
                        $header = "From: " . $from . " \r\n";
                        $header .= "MIME-Version: 1.0  \r\n";
                        $header .= "Content-type: text/plain \r\n";
                    
                        $rc = mail($to, $subject, $message, $header);
                }

                                                // Arguments:

                // 2. $id - personID to find record
                // 2. $userData - array holding user data
                // 3. $destination - destination email. 
                // 4. $userType - type of user. ph, ktp

                public function refuseUserEmail($id, $name, $surname, $requestBy, $destination= "as19028@essex.ac.uk")  {
                        $id = str_replace(' ', '', $id);
                        $from = "no-replyt@essex.ac.uk";
                        $to = $destination;
                        $subject = "User - " . $name . " " .  $surname . " was refused by " . $requestBy . " administrator";
                        
                        $message = "Please add extra amount of informaion \r\n";
                        $message .= "Link to user (Please Log In and copy past link): " . "http://localhost/view/user_page.php?id=" . $id . "\r\n";
                        $message .= "\r\n";

                        $message .= "Kind Regards, \r\n";
                        $message .= "Operation Support System \r\n";
                
                        $header = "From: " . $from . " \r\n";
                        $header .= "MIME-Version: 1.0  \r\n";
                        $header .= "Content-type: text/plain \r\n";
                    
                        $rc = mail($to, $subject, $message, $header);
                }


                

                
                // Arguments:

                // 2. $id - personID to find record
                // 2. $userData - array holding user data
                // 3. $destination - destination email. 
                // 4. $userType - type of user. ph, ktp
                // 5. $duration - 3 months, 1 month, 2 weeks, 1 week notification

                public function notificationEmail($id, $userData, $userType, $duration, $destination = "as19028@essex.ac.uk") {
                        $from = "no-replyt@essex.ac.uk";
                        $to = $destination;
                        $subject = "User  - " . $userData['name'] . " " .  $userData['surname'] . " will finish in " . $duration;
                        $message = "User Information for  " . $userData['name'] . " " .  $userData['surname'] . " will expire on " .  $userData['endDate'];

                        $message .= "Please collect key from " . $userData['name'] . " " .  $userData['surname'] . " \r\n";

                        $message .= "Equipment List " . $userData['equipment'] . " \r\n";

                        $message .= "\r\n";

                        $message .= "Kind Regards, \r\n";
                        $message .= "Operation Support System \r\n";
                
                        $header = "From: " . $from . " \r\n";
                        $header .= "MIME-Version: 1.0  \r\n";
                        $header .= "Content-type: text/plain \r\n";
                    
                        $rc = mail($to, $subject, $message, $header);
                }
        }
?>