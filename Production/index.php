<?php 
    if(session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
      $_SESSION['groupStatus'] = null;
      $_SESSION['Login'] = false;
    } else{
      session_destroy();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="./css/bundle.css">
  <title>Sign In</title>
</head>
<body>

<?php include './controller/ldapConnect.php'; ?>


<header>
        <div class="logo">
            <img src="./img/UE-white.png" alt="University of Essex">
        </div>

        <h1 class="title_program">Operations Support System</h1>
        <div class="nav">
        <?php
        // it is external header from a system 
        if ($_SESSION['Login'] == true) {
            echo '<a href="https://csee.essex.ac.uk/OSS/view/submit_request_prof.php" class="link">
                    <div class="text">Request Form</div>
                  </a>';
        }

        if ($_SESSION['Login'] == true) {
          if ($_SESSION['groupStatus'] == "ces-tech-g") {
            echo '<a href="https://csee.essex.ac.uk/OSS/view/user_directory.php" class="link">
                    <div class="text">Employee List</div>
                  </a>';
          }
        }

        if ($_SESSION['Login'] == true) {
          if ($_SESSION['groupStatus'] == "ces-tech-g") {
            echo '<a href="https://csee.essex.ac.uk/OSS/view/requests_table.php" class="link">
                    <div class="text">Request Logging</div>
                  </a>';
          }
        }

        if ($_SESSION['Login'] == true) {
            echo '<a href="https://csee.essex.ac.uk/OSS/index.php" class="link">
                    <div class="text">Log Out</div>
                  </a>';
        }

        ?>
        </div>
    </header>

  <div class="login_page">
    
    <section class="wrapper">

      <form method="post">
        <section class="login_menu">
          <h2>Sign In </h2>
          <div class="input_field">
            <label for="username">Email</label>
            <input type="text" class="username" id="username" name="username" style="margin-left:47px">
          </div>
          <div class="input_field">
            <label for="username">Password</label>
            <input type="password" class="password" id="password" name="password">
          </div>

          <button type="submit" name="ok">Login</button>

          <?php
          // THIS PART OF CODE WILL BE ACTIVATTED WHEN FORM IS SUBMITED - POST Request  ///////////////////


          if(isset($_POST['ok'])){
            if ($_POST['username'] == '' or $_POST['password'] == '') {
              $_SESSION['Login'] = false;
              echo "<h4 style='color:red;'>Icnorrect username or password </h4>";
              
            } else {
              // ldapLogin requre email from you to connect
              $ldapconn = ldapLogin($_POST['username'], $_POST['password'], "https://csee.essex.ac.uk/OSS/view/requests_table.php");
              $_SESSION['email'] = $_POST['username'];
              // extract username (first part of email)
              $username = explode("@", $_POST['username'])[0];
              $_SESSION['username'] = $username;
              // User will be able to login to system 
              $_SESSION['Login'] = true;

              if(isset($ldapconn) and $ldapconn != null) {
                $group = ldapGetGroups($ldapconn, $username);
                // store grou name into session, it will be needed to restrict access to certain type of internal users 
                $_SESSION['groupStatus'] = $group;
                header("Location: https://csee.essex.ac.uk/OSS/view/main.php");
              } else {
                // if password or email is not in a system, person wouldnt be able to login 
                $_SESSION['Login'] = false;
                echo "<h4 style='color:red;'>Icnorrect username or password </h4>";
              }
            }
          }

        // THIS PART OF CODE WILL BE ACTIVATTED WHEN FORM IS SUBMITED - POST Request END  ///////////////////
        ?>
        </section>
      </form>

    </section>


  </div>

</body>
</html>