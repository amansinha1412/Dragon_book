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
         $(document).ready(functio(){
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
            	
            	<input type = "text" name="reg_lname" placeholder="Last Name" <?php if(isset($_SESSION['reg_fname'])){
            		echo $_SESSION['reg_lname'];
            	}

            	?> required>
            	<br>
            	
            	<input type = "text" name="reg_email" placeholder="Email" 
            	 <?php if(isset($_SESSION['reg_fname'])){
            		echo $_SESSION['reg_email'];
            	}

            	?> required>
            	<br>
            	
            	<input type = "text" name="reg_email2" placeholder="Confirm Email" <?php if(isset($_SESSION['reg_email2'])){
            		echo $_SESSION['reg_email2'];
            	}

            	?> required>
            	<br>
            	<input type = "password" name="reg_password" placeholder="Password"  required>
            	<br>
            		
            	<input type = "password" name="reg_password2" placeholder="Confirm Password"  required>
            	<br>
            	
            	<input type = "submit" name="register_button" value="Register">
              <br>
              <a href="#" id="signin" class="signin">Aleady have an account? Sign in here </a>
          </form>
        </div>      
      </div> 
  	<br>
  	
  </div>
</body>
</html>