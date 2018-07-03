<?php 
require 'config/config.php';
require 'includes/form_handlers/register_handler.php';
require 'includes/form_handlers/login_handler.php';

//declaration variables to prevent errors


?>
<html>
<head>
	<title>WElcome to caavo</title>
  <link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="assets/js/register.js"></script>
</head>	
<body>
  <?php

   if(isset($_POST['register_button'])){
    echo '
     <script>
         $(document).ready(function(){
              $("#first").hide();
              $("#second").show();
         });
     </script>
    ';
   }

  ?>
  <div class="wrapper">

    <div class = "login_box">
   
       <div class="login_header">
          <h1>Swirlfeed!</h1>
          Login or sign up below
        </div>
        <div id="first">
          <form action="register.php" method="POST">
          	<input type="email" name = "log_email" placeholder ="EMAIL" required>
          	<br>
          	<input type="password" name = "log_password" placeholder ="password" required>
          	<br>
            <?php
              if(in_array("Email or password is incorrect<br>",$error_array)){
                echo "Email or password is incorrect<br>";
              }

            ?>
          	<input type="submit" name = "login_button" value = "LOGIN">
            <br>
            <a href="#" id="signup" class="signup">Need an account? Register here </a>
            
          </form>
     	  </div>
        <div id="second">
            <form action="register.php" method="POST">
            	<input type = "text" name="reg_fname" placeholder="First Name"
            	<?php if(isset($_SESSION['reg_fname'])){
            		echo $_SESSION['reg_fname'];
            	}

            	?> required>

            	<br>
            	<?php
              if(in_array("First name must be greater or less than 2 and 25 chracters respectively<br>",$error_array)){
                echo "First name must be greater or less than 2 and 25 chracters respectively<br>";
              }
              ?>
            	<input type = "text" name="reg_lname" placeholder="Last Name" <?php if(isset($_SESSION['reg_fname'])){
            		echo $_SESSION['reg_lname'];
            	}

            	?> required>
            	<br>
            	<?php
              if(in_array("Last name must be greater or less than 2 and 25 chracters respectively<br>",$error_array)){
                echo "Last name must be greater or less than 2 and 25 chracters respectively<br>";
              }
              ?>
            	<input type = "text" name="reg_email" placeholder="Email" 
            	 <?php if(isset($_SESSION['reg_fname'])){
            		echo $_SESSION['reg_email'];
            	}

            	?> required>
            	<br>
            	<?php
              if(in_array("Email already in use<br>",$error_array)){
                echo "Email already in use<br>";
              }
              ?>
            	<input type = "text" name="reg_email2" placeholder="Confirm Email" <?php if(isset($_SESSION['reg_email2'])){
            		echo $_SESSION['reg_email2'];
            	}

            	?> required>
            	<br>
              <?php
              if(in_array("Emails don't match<br>",$error_array)){
                echo "Emails don't match<br>";
              }
              ?>
            	<input type = "password" name="reg_password" placeholder="Password"  required>
            	<br>
            	<?php
              if(in_array("Passwords must contain 5 or more characters<br>",$error_array))
                echo "Passwords must contain 5 or more characters<br>";
              
              if(in_array("Passwords must contain alphabets or digits<br>",$error_array)){
                 echo "Passwords must contain alphabets or digits<br>";
              }
              ?>	
            	<input type = "password" name="reg_password2" placeholder="Confirm Password"  required>
            	<br>
            	<?php
                if(in_array("Passwords dont match<br>",$error_array)){
                  echo "Passwords dont match<br>";
                }
              ?>
            	<input type = "submit" name="register_button" value="Register">
              <br>
              <?php
                if(in_array("<span style='color:#14C800'>You're all set go ahead and login</span>",$error_array)){
                  echo "<span style='color:#14C800'><a href='register.php'>You're all set go ahead and login</a></span><br>";
                }
              ?>
              <a href="register.php" id="signin" class="signin">Aleady have an account? Sign in here </a>
          </form>
        </div>      
      </div> 
  	<br>
  	
  </div>
</body>
</html>