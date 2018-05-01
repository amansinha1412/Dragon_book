<?php
   
   if(isset($_POST['login_button'])){
   	$email = filter_var($_POST['log_email'],FILTER_SANITIZE_EMAIL);

   	$_SESSION['log_email'] = $email;//store email in session var
   	$password = md5($_POST['log_password']);

    $chech_database_query = mysqli_query($con,"SELECT * from users where email = '$email' and password = '$password'");
    if($chech_database_query===false){
    	printf("error: %s\n", mysqli_error($con));
    }
    $n= mysqli_num_rows($chech_database_query);
    if($n==1){
    	$row =mysqli_fetch_array($chech_database_query);
     // $user = $row;
    	$username = $row['username'];
        
    	$_SESSION['username'] = $username;
    	$_SESSION['message'] = "";
      header("Location: index.php");
    	exit();

    }

   }

?>