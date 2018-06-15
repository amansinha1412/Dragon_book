<?php
   include("includes/header.php");
   include("includes/form_handlers/settings_handler.php");

?>
<div class="main_column column">
    
    <h4>Account Settings</h4>
    <?php
       echo "<img src='".$user['profile_pic']."' id='small_profile_pic'>";
    ?>
    <br>
    <a href="upload.php">Upload new profile picture</a><br><br><br>

    Modify The values and click 'Update Details'
    
    <?php
       $user_data_query = mysqli_query($con,"SELECT fname,lname,email from users where username='$userLoggedIn'");
       $row = mysqli_fetch_array($user_data_query);
       $fname = $row['fname'];
	   $lname = $row['lname'];
	   $email = $row['email'];
       

    ?>
    <form action="settings.php" method="POST">
    	First Name: <input type="text" name="first_name" value='<?php echo $fname;?>' id="settings_input"><br>
    	Last Name: <input type="text" name="last_name" value='<?php echo $lname;?>' id="settings_input"><br>
    	Email: <input type="text" name="email" value='<?php echo $email;?>' id="settings_input"><br>
    	 <input type="submit" name="update_details" id="save_details" value="Update Details" class="info settings_submit" >
    	 <?php
            echo $message;
    	 ?>
    </form>

    <h4>Change Password</h4>
    <form action="settings.php" method="POST">
    	Old Password: <input type="password" name="old_password" ><br>
    	New Password: <input type="password" name="new_password_1" v><br>
    	New Password: <input type="password" name="new_password_2" ><br>

    	<?php echo $password_message; ?>
    	 <input type="submit" name="update_password" id="save_details" value="Update Password" class="info settings_submit">
    </form>	
    
    <h4>Close Account</h4>
    <form action="close_account.php" method="POST">
    	 <input type="submit" name="close_account" id="close_account" value="Close Account" class="danger settings_submit">
    </form>
</div>