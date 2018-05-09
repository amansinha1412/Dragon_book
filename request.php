<?php 
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");

?>
<div class="main_column column" id="main_column">
	<p>Friend Requests</p>
	<?php
     $query = mysqli_query($con,"SELECT * from friend_requests where user_to='$userLoggedIn'");
     if(mysqli_num_rows($query)==0){
     	echo "No friend Requests";
     }
     else{
     	//echo "here";
     	while($row = mysqli_fetch_array($query)){
     		$user_from = $row['user_from'];
     		$user_from_obj = new User($con,$user_from);

     		echo $user_from_obj->getFirstAndLastName()." sent you a Friend Request";
     		$user_from_friend_array = $user_from_obj->getFriendArray();

     		if(isset($_POST['accept_request'.$user_from])){
     			$add_friend_query = mysqli_query($con,"UPDATE users set friend_array=CONCAT(friend_array,'$user_from,') where username='$userLoggedIn'");
     			$add_friend_query = mysqli_query($con,"UPDATE users set friend_array=CONCAT(friend_array,'$userLoggedIn,') where username='$user_from'");
                $delete_query = mysqli_query($con,"DELETE from friend_requests where user_to='$userLoggedIn' and user_from='$user_from'"); 
                echo "You are now friends";
                //echo "relocating";
                header("Location: request.php");

     		}
     		if(isset($_POST['ignore_request'.$user_from])){
     			$delete_query = mysqli_query($con,"DELETE from friend_requests where user_to='$userLoggedIn' and user_from='$user_from'");
     			echo "Request Ignored!";
     			header("Location: request.php");
     			
     		}
     		?>
     		<form action="request.php" method="POST">
		<input type="submit" name="accept_request<?php echo $user_from?>" value="Accept" id="accept_button" >
		<input type="submit" name="ignore_request<?php echo $user_from?>" value="Ignore" id="ignore_button" >

	</form>
	<?php
     	}
     }
	?>

	</div>