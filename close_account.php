<?php
  include("includes/header.php");
  if(isset($_POST['cancel'])){
  	header("location: settings.php");
    unset($_POST);
  }
  if(isset($_POST['close_account1'])){
  	$close_query = mysqli_query($con,"UPDATE users set account_closed='yes' where username='$userLoggedIn'");
  	unset($_POST);
  	session_destroy();
  	header("location: register.php");
  }
?>
<div class="main_column column">
  <h4>Close Account</h4>

  Are You sure you want to close your account!<br><br>
  Closing You account will hide your profile and all your activity from other users.<br><br>
  You can re-open your account at any time by simply logging in.<br><br>

  <form action="close_account.php" method="POST">
  	<input type="submit" name="close_account1" id="close_account" value="Yes Close it!" class="danger settings_submit">
  	<input type="submit" name="cancel" id="update_details" value="No way!" class="info settings_submit">
  </form>
</div>