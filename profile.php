<?php
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");

if(isset($_GET['profile_username'])){
	$username = $_GET['profile_username'];
	$user_details_query = mysqli_query($con,"SELECT * from users where username='$username'");
	$user_array = mysqli_fetch_array($user_details_query);
	$num_friends = (substr_count($user_array['friend_array'],","))-1;
    if($num_friends<0){
    	$num_friends = 0;
    }
}

if(isset($_POST['remove_friend'])){
	$user = new user($con,$userLoggedIn);
	$user->removeFriend($username);
}
if(isset($_POST['add_friend'])){
	$user = new user($con,$userLoggedIn);
	$user->sendRequest($username);
}
if(isset($_POST['respond_request'])){
	header("Location:requests.php");
}
?>
<style type="text/css">
  .wrapper{
  	margin-left:0px;
	padding-left:0px;
  }
</style>
<div class="profile_left">
	<img src="<?php echo $user_array['profile_pic']?>" >
	<div class="profile_info">
		<P><?php echo "Posts:".$user_array['num_of_posts'];?></P>
		<P><?php echo "Likes:".$user_array['num_of_likes'];?></P>
		<P><?php echo "Friends:".$num_friends ;?></P>
	</div>
	
	<form action="<?php echo $username?>" method="Post">
		<?php
            $profile_user_obj = new user($con,$username);
            if($profile_user_obj->isClosed()){
            	header("Location:user_closed.php");
            }

            $logged_in_user_obj = new user($con,$userLoggedIn);
            if($userLoggedIn!=$username){
            	if($logged_in_user_obj->isFriend($username)){
            		//echo "here";
            		echo '<input type="submit" name="remove_friend" class="danger" value="Remove Friend"><br>';
            	}
            	else if($logged_in_user_obj->didRecieveRequest($username)){
            		echo '<input type="submit" name ="respond_request" class="warning" value="Respond">';
            	}
            	else if($logged_in_user_obj->didSendRequest($username)){
            		echo "sending";
            		echo '<input type="submit" name ="" class="default" value="Request Sent"><br>';
            	}
            	else{
            		echo '<input type="submit" name ="add_friend" class="success" value="Add Friend"><br>';

            	}
            }      
		?>
		
	</form>
</div>
  
 <div class="main_column column">
  This is a profile_page; 	
 </div>

  </div>
</body>
</html>
