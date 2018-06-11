<?php
include("includes/header.php");

$message_obj = new Message($con,$userLoggedIn);

if(isset($_GET['u'])){
	$user_to = $_GET['u'];
}
else{
	$user_to = $message_obj->getMostRecentUser();
	if($user_to == false){
		$user_to = 'new';
	}

}

if($user_to!='new'){
	$user_to_obj = new user($con,$user_to);
}

if(isset($_POST['post_message'])){
	if(isset($_POST['message_body'])){
		$body = mysqli_real_escape_string($con,$_POST['message_body']);
		$date = date("Y-m-d H:i:s");
		$message_obj->sendMessage($user_to,$body,$date);
		unset($_POST['post_message']);
	}
}
?>
<div class="user_details column">
	  	<a href="<?php echo $userLoggedIn; ?>"><img src="<?php echo $user['profile_pic']; ?>"></a>
	  	 <a href="<?php echo $userLoggedIn; ?>">
	  	 <div class="user_details_left_right">
	  	 	
	  	 	
	  	 <?php
	       echo $user['fname']." ".$user['lname'];
	  	 ?>
	  	</a>
	  	<br>
	  	<?php
	      echo "Posts: ".$user['num_of_posts']."<br>";
	      echo "Likes:".$user['num_of_likes'];
	  	?>
	    </div>
    </div>

    <div class="main_column column" id = "main_column">
    	<?php
         if($user_to!="new"){
         	echo "<h4>You and <a href=".$user_to.">".$user_to_obj->getFirstAndLastName()."</a></h4><br><hr>";
         	echo "<div class ='loaded_messages'   id='scroll_messages'>";
         	//echo $user_to;
         	echo $message_obj->getMessages($user_to);
         	echo '</div>';
         }
         else {
         	echo "<h4>New Messsage</h4>";
         }
    	?>

    	<div class="message_post">
    		<form action="" method="POST">
    			<?php
    			if($user_to == 'new'){
    				echo "select the friend you would like to message <br><br>";
                    ?>
    				TO:<input type = 'text' onkeyup='getUsers(this.value,"<?php echo $userLoggedIn?>")' name='q' placeholder='Name' autocomplete='off' id='search_text_input'>

                    <?php
    				echo "<div class='results'></div>";

    			}
    			else{
    				echo "<textarea name='message_body' id ='message_textarea' placeholder='Write your message...'></textarea>";
                    echo "<input type='submit' name ='post_message' class='info' id='message_submit' value='Send'>";
    				
    			}
    			

    			?>
    			
    		</form>
    	</div>
      
    </div>
  <script>
      	window.onload = function(){
		var div = document.getElementById("scroll_messages");

		//alert(div.scrollTop);
		div.scrollTop = div.scrollHeight;
	     }

       </script>
 <div class="user_details column" id = "conversations">
 	<h4>Conversations</h4>
 	<div class="loaded_conversations">
 		<?php echo $message_obj->getConvos(); ?>
 	</div>
 	<br>
 	<a href="messages.php?u=new">New Messages</a>
 	

 </div>

  