# Operation Support System 

## Main Config 

There are 2 versions of system Production one Development.

I was usuing UWamp to simulate PHP and Apache Environment:

UWamp - https://www.uwamp.com/en/

Production adn Development version of PHP is 8.0.29

Production adn Development version of MySQL is 5.7.11

Local SMTP test tool - http://toolheap.com/test-mail-server-tool/

The main difference between production and development vesrion is file path and DB configuration.

Db connection is inside private/controller/db_connect.php

Production Settings: 

<code>
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
</code>

Development Settings:

<code>
   protected function OpenCon() {
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "zsazsa159";
        $db = "oso";
        $conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
    
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
        }
    
        return $conn;
    }
</code>

It is requried to change all URL path inside PHP files from "http://localhost/" to " https://csee.essex.ac.uk/OSS" to swap between development and production version. 


## Authentication

Authentication of a user is happpening by using LDAP technology.

**ldapLogin** and **ldapGetGroups** function is inside **ldapConnect.php**

User session is created in index.php file after validating email and password. 

There are 3 main veriables:

$_SESSION['email'], $_SESSION['username'], $_SESSION['Login']

$_SESSION['username'] is used for profiling of users records e.g user can see only records which he submited to the system.

$_SESSION['email'] is used to prefill data form.

$_SESSION['Login'] is check if session is valid.

Session is breaks when person redirects to index.php.

<code>

    if(session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
      $_SESSION['groupStatus'] = null;
      $_SESSION['Login'] = false;
    } else{
      session_destroy();
    }

</code>

Seesion validation for each URL is happening in public/view/include/header.php

<code>

session_start();
// block root if person is not logged in 
if ($_SESSION['Login'] != true) { 
  header("Location: https://csee.essex.ac.uk/OSS/");
}

</code>


## Database

You can create schematics of database by using private/db_create.sql.

I was using star model of normalization during proccess of creation of database. 

Person or record is central unit of data inside of DB. 

All CRUD operations on DB is inside private/db.php

DB connection settings is inside private/controller/db_connect.php

Use private/db_create.sql script to create DB schematics. Just run it inside of MySQL. 



### Pages

index.php - Login gape
main.php - main page in a system after login in, choice type of record to submite in a system.

Pages below have very simillar code and functionality, it is allow to submite different types of users:

1. submit_request_acad.php
2. submit_request_ktp.php
3. submit_request_phd.php
4. submit_request_prof.php
5. submit_request_visitor.php

The main differance is in tyoe of user and in supervisor field. It can be a company, person, academic. All different types of supervisors is stored under supervisor field name. 

user_directory.php - is a page where user can see all records, filter this records and see his own submitted records. 

requests_table.php - admin page, admin can delete, edit and approve a user 

edit.php -  is an admin version of editing of a record. You can add key number in this one.

edit_local.php - local version of user update. User can add extra information by using this edit page before record was approved. #

done.php - page which indicate that user submited data. 

user_page.php - detailed information aboute user/record 

include/header.php - header of each page except index.php

### Controller

Majority of logical code is inside private/controller foldier.

Db.php - PHP CRUD operations for adding, deleting, editing, viewing on a different pages.
db_connect.php - connection to DB.
email.php - email notifications for adding, deleting, editing, viewing on a different pages.
ldapConnect.php - authentication logic.

Public foldier contain css, js, img and public php/html files

submit_request.js - controls swaping between different submit_request_*.php pages 