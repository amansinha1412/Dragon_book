<?php
  if(isset($_POST['update_details'])){

    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];

    $email_check = mysqli_query($con,"SELECT * from users where email='$email'");

    $row = mysqli_fetch_array($email_check);
    $matched_user = $row['username'];

    if($matched_user == "" || $matched_user == $userLoggedIn){
        $message = "<br>Details Updated!<br><br>";

        $query = mysqli_query($con,"UPDATE users set fname ='$fname' , lname='$lname',email='$email' where username='$userLoggedIn' ");

    }
    else{
    	$message = "That email already in use.<br><br>";
    }
    unset($_POST);
  }
  else
  	$message = "";
  //****************************

  if(isset($_POST['update_password'])){

  	$old_password  = strip_tags($_POST['old_password']);
  	$new_password_1 = strip_tags($_POST['new_password_1']);
  	$new_password_2 = strip_tags($_POST['new_password_2']);
    
    $password_query = mysqli_query($con,"SELECT password from users where username='$userLoggedIn'");
    $row = mysqli_fetch_array($password_query);
    
    $db_password = $row['password'];
    
    if(md5($old_password) == $db_password){

    	if($new_password_1 == $new_password_2){
              
              if(strlen($new_password_2)<=4){
                $password_message = "Sorry your password must be greater than 4 characters<br><br>";
              }
              else{
              	$new_password_md5 = md5($new_password_1);
              	$password_query = mysqli_query($con,"UPDATE users set password='$new_password_md5' where username='$userLoggedIn' ");
              	$password_message = "Password has been changed<br><br><br>";
              }
    	}
    	else{
             $password_message = "Your 2 new passwords need to match<br><br>";
    	}
    }
    else{
    	 $password_message = "The old password is incorrect.<br><br>";
    }
   unset($_POST);

  }
  else{
    	$password_message ="";
    }

   if(isset($_POST['close_account'])){
   	  header("location: close_account.php");
      unset($_POST); 
   } 

?>