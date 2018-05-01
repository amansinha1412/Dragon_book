<?php
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");

//echo "hello".$_SESSION['username'];

//session_start();
//$_SESSION['message'] = "";


if(isset($_POST['post'])){
   //$_SESSION['post_text'] = $_POST['post_text'];	
   if($_POST['post_text']!=$_SESSION['message']){
   //echo $_POST['post_text']." ".$_SESSION['message'];	
   $post = new Post($con,$userLoggedIn);
   //echo "adding";
   $post->submitPost($_POST['post_text'],'none');   
   $_SESSION['message'] = $_POST['post_text'];
   echo $_SESSION['message'];
   unset($_POST);
   header("Location: index.php");
    }
   
  else{
  	unset($_POST);
  }
   //$_POST = array();
   //unset($_POST['post']);
   //unset($_SESSION['post']);
  // echo "hello";
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
    

    <div class="main_column column">
    	<form class="post_form" action="index.php" method="POST" >
    		<textarea name="post_text" id="post_text" placeholder="Whats on your mind!" ></textarea>
    		<input type="submit" name="post" id="post_button" value="Post">
    	</form>
    	
    	

    	<div class = "posts_area" id="posts_area"></div>
    	<img id = "loading" src="assets/images/icons/loading.gif">

    </div>
    <script>
    	var userLoggedIn = '<?php echo $userLoggedIn?>';
        //echo userLoggedIn;
        //alert(userLoggedIn);
        $(document).ready(function(){
           $('#loading').show();
    
           //original req for loading req

           $.ajax({
                url:"includes/handlers/ajax_load_posts.php",
                type:"POST",
                data:"page=1&userLoggedIn="+userLoggedIn,
                cache:false,
                success:function(data){
                	$('#loading').hide();
                	$('.posts_area').html(data);
                }

           });

           $(window).scroll(function(){
                  
                 var height = $('.posts_area').height();//div containing posts
                 //alert(height); 
                 var scroll_top = $(this).scrollTop();
                 var page = $('.posts_area').find('.nextPage').val();
                 var noMorePosts = $('.posts_area').find('.noMorePosts').val();
                // alert (noMorePosts);
                 //console.log(document.body.scrollHeight);
                 console.log(document.body.scrollTop);
                 console.log($(window).innerHeight());
                 //(document.body.scrollHeight == document.body.scrollTop + window.innerHeight)   
                    var scrollHeight, totalHeight;
				    scrollHeight = document.body.scrollHeight;
				    totalHeight = window.scrollY + window.innerHeight;
                 //(document.body.scrollHeight == document.body.scrollTop + window.innerHeight)
                 if(totalHeight >= scrollHeight && noMorePosts == 'false'){
                     $('#loading').show();
                               
                     console.log("here");
                     var ajaxReq = $.ajax({
		                url:"includes/handlers/ajax_load_posts.php",
		                type:"POST",
		                data:"page="+page+"&userLoggedIn="+userLoggedIn, 
		                cache:false,
		                success:function(response){
		                	$('.posts_area').find('.nextPage').remove(); //removes current next page

		                	$('.posts_area').find('.noMorePosts').remove(); //removes current next page
 

		                	$('#loading').hide();
		                	$('.posts_area').append(response);
		                }

                     });

 

                 }// end if 
                  

                return false;


           });//end (window).scroll(function());
        }) ;	
    </script>
    <script >
   // window.onscroll = function() {
   // var scrollHeight, totalHeight;
   // scrollHeight = document.body.scrollHeight;
   // totalHeight = window.scrollY + window.innerHeight;

    //if(totalHeight >= scrollHeight)
    //{
     //   console.log("at the bottom");
    //}
//    }
    </script>
    
  </div>
</body>
</html>
