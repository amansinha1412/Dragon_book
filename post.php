<?php
include("includes/header.php");

if(isset($_GET['id'])){
	$id = $_GET['id'];

}
else $id = 0;

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
    <div class="main_column column" id ="column">
    	<div class="posts_area">
    		<?php
               $post = new Post($con,$userLoggedIn);
               echo $post->getSinglePost($id);

    		?>
    	</div>
    </div>

