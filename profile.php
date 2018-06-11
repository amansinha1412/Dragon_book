<?php
include("includes/header.php");
//include("includes/classes/User.php");
//include("includes/classes/Post.php");
$message_obj = new message($con,$userLoggedIn);

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
    unset($_POST['add_friend']);
}
if(isset($_POST['respond_request'])){
	header("Location:request.php");
}
if(isset($_POST['post_message'])){
    if(isset($_POST['message_body'])){
      $body = mysqli_escape_string($con,$_POST['message_body']);
      $date = date("Y-m-d H:i:s");
      $message_obj->sendMessage($username,$body,$date);

    }
    $link = '#profileTabs a[href="#messages_div"';
 echo "<script>
     $(function(){
       $('".$link."]').tab('show');
     });
     </script>";  
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
            		//echo "sending";
            		echo '<input type="submit" name ="" class="default" value="Request Sent"><br>';
            	}
            	else{
            		echo '<input type="submit" name ="add_friend" class="success" value="Add Friend"><br>';

            	}

            }

            ?>
            
            <?php

		?>
		
	</form>
    <input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="post something" >
    <?php
     if($userLoggedIn!=$username){
        echo '<div class="profile_info_bottom">';
        echo $logged_in_user_obj->getMutualFriends($username)." Mutual Friends";
        echo '</div>';
     }   
    ?>
</div>
  
 <div class="profile_main_column column">
   <ul class="nav nav-tabs" role="tablist" id="profileTabs">
      <li role="presentation" class="active"><a href="#newsfeed_div" aria_controls="newsfeed_div" role="tab" data-toggle="tab">Home</a></li>
      <li role="presentation"><a href="#about_div" aria_controls="about_div" role="tab" data-toggle="tab">Profile</a></li>
      <li role="presentation"><a href="#messages_div" aria_controls="messages_div" role="tab" data-toggle="tab" >Messages</a></li>
    </ul>
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane fade in active" id="newsfeed_div">
        <div class="posts_area"></div>
          <img id="loading" src="assets/images/icons/loading.gif">
      </div>
      <div role="tabpanel" class="tab-pane fade " id="about_div">
        
     </div>
      <div role="tabpanel" class="tab-pane fade " id="messages_div">
      <?php
         
         
          echo "<h4>You and <a href=".$username.">".$profile_user_obj->getFirstAndLastName()."</a></h4><br><hr>";
          echo "<div class ='loaded_messages'   id='scroll_messages'  onload = 'alert('"."hello"."')'>";
          //echo $user_to;
          echo $message_obj->getMessages($username);
          echo '</div>';
         
      ?>

      <div class="message_post">
        <form action="" method="POST">
          
            <textarea name='message_body' id ='message_textarea' placeholder='Write your message...'></textarea>
                    <input type='submit' name ='post_message' class='info' id='message_submit' value='Send'> 
        </form>
      </div>
      

  <script>
        function load(){
          //alert("hello")s;
    var div = document.getElementById("scroll_messages");
    // alert(div.value); 
    //alert(div.scrollTop);
    div.scrollTop = div.scrollHeight;
       }

       </script>
  
                     </div>
        
      </div>
    </div>
  	

 


<!--modal button 
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>
-->

<!-- Modal -->
<div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Post Something</h5>
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>This will appear on user's profile page also their newsfeed for your friends to see</p>

        <form class="profile_post" action="profile.php" method="POST">
            <div class="form-group">
                <textarea class="form-control" name="post_body" ></textarea>
                <input type="hidden" name="user_from" value="<?php echo $userLoggedIn?>" >
                 <input type="hidden" name="user_to" value="<?php echo $username?>" >


                
                
            </div>
            
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
      </div>
    </div>
  </div>
</div>

<script>
      var profile_username = '<?php echo $username;  ?>';
      var userLoggedIn = '<?php echo $userLoggedIn ; ?>';

        //echo userLoggedIn;
        //alert(userLoggedIn);
       //sessionStorage.current_page = 1;
      // alert(sessionStorage.current_page);
        $(document).ready(function(){
           $('#loading').show();
           //alert (profile_username);
           //original req for loading req


           $.ajax({
                url:"includes/handlers/ajax_load_profile_post.php",
                type:"POST",
                data:"page=1&userLoggedIn="+userLoggedIn+"&profileUsername="+profile_username,
                cache:false,
                success:function(data){
                  //alert("success");
                  $('#loading').hide();
                  $('.posts_area').html(data);
                }

           });

           $(window).scroll(function(){
                  
                 var height = $('.posts_area').height();//div containing posts
                 //alert(height); 
                 var scroll_top = $(this).scrollTop();
                 var page = $('.posts_area').find('.next_page').val();
                 var noMorePosts = $('.posts_area').find('.noMorePosts').val();
                // alert (noMorePosts);
                //console.log(page);
                 //console.log(document.body.scrollHeight);
                // console.log(document.body.scrollTop);
                // console.log($(window).innerHeight());
                 //(document.body.scrollHeight == document.body.scrollTop + window.innerHeight)   
                    var scrollHeight, totalHeight;
            scrollHeight = document.body.scrollHeight;
            totalHeight = window.scrollY + window.innerHeight;
            //var current_page = '<%= Session["current_page"] %>';

                 //(document.body.scrollHeight == document.body.scrollTop + window.innerHeight)
                 if(totalHeight >= scrollHeight && noMorePosts === 'false' ){
                   //alert("coming_down");
                     $('#loading').show();
                    // var page = $('.posts_area').find('.next_page').val();          
                     //console.log("here");
                     //alert(page);
                     var ajaxReq = $.ajax({
                    url:"includes/handlers/ajax_load_profile_post.php",
                    type:"POST",
                    async:false,//adding it made a single call for each ajax call
                    data:"page="+page+"&userLoggedIn="+userLoggedIn+"&profileUsername="+profile_username, 
                    cache:false,
                    success:function(response){
                      //alert("succ");
                      $('.posts_area').find('.next_page').remove(); //removes current next page

                      $('.posts_area').find('.noMorePosts').remove(); //removes current next page
 

                      $('#loading').hide();
                      $('.posts_area').append(response);
                    }
                    //sessionStorage.current_page = page;

                     });

 

                 }// end if 
                  

                return false;


           });//end (window).scroll(function());
        }) ;  
    </script>   
    
  </div>
</body>
</html>
