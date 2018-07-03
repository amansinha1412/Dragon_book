<?php
include("includes/header.php");
//include("includes/classes/User.php");
//include("includes/classes/Post.php");

//echo "hello".$_SESSION['username'];

//session_start();
//$_SESSION['message'] = "";


if(isset($_POST['post'])){
   //$_SESSION['post_text'] = $_POST['post_text'];	
   $uploadOk = 1 ;
   $imageName = $_FILES['fileToUpload']['name'];

   $errorMessage = "";

   if($imageName !=""){
    $targetDir = "assets/images/posts/";
    $imageName = $targetDir.uniqid().basename($imageName);
    $imageFileType = pathinfo($imageName,PATHINFO_EXTENSION);

    if($_FILES['fileToUpload']['size']>100000000){
         $errorMessage = "Sorry the filesize is too large";
         $uploadOk=0; 
    }

    if(strtolower($imageFileType)!="jpeg" && strtolower($imageFileType)!="png" && strtolower($imageFileType)!="jpg"){
      $errorMessage = "Sorry only jpg, jpeg and png file are allowed";
      $uploadOk=0;
    }

    if($uploadOk){
      if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imageName)){
        //image uploaded ok;
      }
      else{
        $uploadOk = 0;
        
      }

    }

   }
   
   if($uploadOk == 1){

     if($_POST['post_text']!=$_SESSION['message']){
   //echo $_POST['post_text']." ".$_SESSION['message']; 
   $post = new Post($con,$userLoggedIn);
   //echo "adding";
   $post->submitPost($_POST['post_text'],'none',$imageName);   
   $_SESSION['message'] = $_POST['post_text'];
   //echo $_SESSION['message'];
   unset($_POST);
   //header("Location: index.php");
    }

   }
   else{
      echo "<div style='text-align:center'class='alert alert-danger'>
          $errorMessage;
            </div>
      ";
   }

   
   
  //else{
  	//unset($_POST);
 // }
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
    	<form class="post_form" action="index.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" id="fileToUpload">
    		<textarea name="post_text" id="post_text" placeholder="Whats on your mind!" ></textarea>
    		<input type="submit" name="post" id="post_button" value="Post">
    	</form>
    	
    	

    	<div class = "posts_area" id="posts_area"></div>
    	<img id = "loading" src="assets/images/icons/loading.gif">
    </div>

    <div class="user_details column">
      <h4>Popular</h4>
      <div class="trends">
        <?php
          $query = mysqli_query($con,"SELECT * from trends order by hits DESC limit 9");

          foreach($query as $row){
            $word = $row['title'];
            $word_dot =strlen($word)>=14?"...":"";

            $trimmed_word = str_split($word,14);
            $trimmed_word = $trimmed_word[0];

            echo "<div style='padding:1px;'>";
            echo $trimmed_word.$word_dot;
            echo "<br><br></div>";

          }
        ?>
      </div> 
      
    </div>
    <script>
    	var userLoggedIn = '<?php echo $userLoggedIn?>';
        //echo userLoggedIn;
        //alert(userLoggedIn);
       //sessionStorage.current_page = 1;
      // alert(sessionStorage.current_page);
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
                 var page = $('.posts_area').find('.next_page').val();
                 var noMorePosts = $('.posts_area').find('.noMorePosts').val();
                // alert (noMorePosts);
                console.log(page);
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
                     $('#loading').show();
                    // var page = $('.posts_area').find('.next_page').val();          
                     //console.log("here");
                     //alert(page);
                     var ajaxReq = $.ajax({
		                url:"includes/handlers/ajax_load_posts.php",
		                type:"POST",
		                async:false,//adding it made a single call for each ajax call
		                data:"page="+page+"&userLoggedIn="+userLoggedIn, 
		                cache:false,
		                success:function(response){
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
<?php 
//                         DIFFICULTIES 

//  1>to stop resubmission on reloading
//  2> to detect the end of scroll in index.php

?>