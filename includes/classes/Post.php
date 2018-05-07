<?php
class Post{
	private $user_obj;
	private $con;

	public function __construct($con,$user){
		$this->con = $con;
		$this->user_obj =new User($con,$user);
	}

	public function submitPost($body,$user_to){
		$body = strip_tags($body);
		$body = mysqli_real_escape_string($this->con,$body);
		$check_empty = preg_replace('/\s+/','',$body);//deletes all spaces
		if($check_empty!=""){
              
              //current date and time
			$date_added = date("Y-m-d H:i:s");
            
            $added_by = $this->user_obj->getUserName();

            //If user is on own profile ,user_to is 'none'
            if($user_to == $added_by){
            	$user_to = "none";
            }

            //insert post
            $query = mysqli_query($this->con,"INSERT into posts values('','$body','$added_by','$user_to','$date_added','NO','NO','0')");
            if($query===false){
    	      printf("error: %s\n", mysqli_error($this->con));
            }
            $returned_id = mysqli_insert_id($this->con);

            //Insert notification


            //update post count of user
            $num_posts = $this->user_obj->getNumPosts();
            $num_posts++;
            $update_query = mysqli_query($this->con,"UPDATE users set num_of_posts='$num_posts' where username='$added_by'");
            //unset($_POST);
            //header("Location: index.php");
		}
  }

	
	public function loadPostsFriends($data,$limit){
        $page = $data['page'];
        //echo $page;
        $userLoggedIn = $this->user_obj->getUserName();

        if($page==1){
             $start = 0;
        }
        else{
          $start = ($page-1)*$limit;
        }
		    $str="";//string to return
        $query = mysqli_query($this->con,"SELECT * from posts where deleted='NO' order by id DESC ");

        if(mysqli_num_rows($query)>0){

          $num_iterations = 0;
          $count = 1;


        while($row=mysqli_fetch_array($query)){
        	$id = $row['id'];
        	$body=$row['body'];
        	$added_by = $row['added_by'];
        	$date_time = $row['date_added'];

        	//prepare user_to string if geven to another user or not

        	if($row['user_to']=="none"){
               $user_to="";
               //echo "here";
        	}
        	else{
                $user_to_obj = new User($this->con,$row['user_to']);
                $user_to_name = $user_to_obj->getFirstAndLastName();
                $user_to = "to <a href=".$row['user_to'].">".$user_to_name."</a>";

        	}

        	//check if user is closed or not

        	$added_by_obj = new User($this->con,$added_by);
        	if($added_by_obj->isClosed()){
        		continue;
        	}

          $user_logged_obj = new user($this->con,$userLoggedIn);
          if($user_logged_obj->isFriend($added_by)){
       //   $un = $added_by->getUserName();

          if($num_iterations++ <$start)
            continue;

          //onc 10 posts have loaded break
          if($count>$limit){
            break;
          }
          else{
            $count++;
          }
        	$user_details_query = mysqli_query($this->con,"SELECT fname,lname,profile_pic from users where username='$added_by'");
        	$user_row = mysqli_fetch_array($user_details_query);
        	$first_name = $user_row['fname'];
        	$last_name = $user_row['lname'];
          $profile_pic = $user_row['profile_pic'];
           ?>
            <script>
              function toggle<?php echo $id;?>(){
                
                var target = $(event.target);
                if(!target.is("a")){

                var element = document.getElementById("toggleComment<?php echo $id; ?>");
                if(element.style.display=='block')
                  element.style.display ='none';
                else 
                  element.style.display = 'block';
              }
            }
            </script>
 

           <?php
          
          $comments_check =mysqli_query($this->con,"SELECT * from comments where post_id='$id'");
          $comments_check_num=mysqli_num_rows($comments_check); 


        	//timeframe
        	$date_time_now = date("Y-m-d H:i:s");
        	$start_date = new DateTime($date_time);
        	$end_date = new DateTime($date_time_now);
        	$interval = $start_date->diff($end_date);
        	if($interval->y>=1){
        		if($inteval==1)
        			$time_message = $interval->y." year ago";
        		else $time_message = $interval->y." years ago";
        	}
        	else if($interval->m>=1) {
                   if($interval->d==0){
                   	  $days = " ago";
                   }
                   else if($interval->d==1){
                   	$days = $interval->d." day ago";
                   }
                   else{
                   	$days = $interval->d." days ago";
                   }

                   if($interval->m==1){
                   	$time_message = $interval->m." month ".$days;
                   }
                   else{
                   	  $time_message = $interval->m." months ".$days; 
                   }  
          	}
          	else if($interval->d>=1){
                 if($interval->d==1){
                   	$time_message = "Yesterday";
                   }
                   else{
                   	$time_message = $interval->d." days ago";
                   }      		
          	}
          	else if($interval->h>=1){
          		if($interval->h==1){
                   	$time_message = $interval->h." hour ago";
                   }
                   else{
                   	$time_message = $interval->h." hours ago";
                   }
          	}
          	else if($interval->i>=1){
          		if($interval->i==1){
                   	$time_message = $interval->i." minute ago";
                   }
                   else{
                   	$time_message = $interval->i." minutes ago";
                   }
          	}
          	else {
                  if($interval->s<30)
          		       $time_message = "Just Now";
                   
                   else{
                   	$time_message = $interval->s." seconds ago";
                   }
          	}
          

            $str.= "<div class='status_post' onclick='javascript:toggle$id()'>
                       <div class='post_profile_pic'>
                       <img src='$profile_pic' width='50'>
                       </div>
                       <div class ='posted_by' style='color:#ACACAC;'>
                         <a href='$added_by'>$first_name $last_name</a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;$time_message
                         </div>
                         <div id = 'post_body'>
                           $body
                         <br>
                         <br>

                         </div>
                         <div class='newsFeedPostOptions'>
                            Comments($comments_check_num)&nbsp;&nbsp;&nbsp;&nbsp;
                            <iframe src='like.php?post_id=
                            $id' scrolling='no'> </iframe>
                         </div>
                    </div> 
                    <div class='post_comment' id='toggleComment$id' style='display:none'>
                      <iframe src='comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'>
                      </iframe>

                    </div>    
                    <hr>
                   ";
          }

        }//end while loop
        if($count>$limit){
         //
          //echo $page;
          $page++;
           //echo $page;
          $str .="<input type='hidden' class='next_page' value='".($page)."'><input type='hidden' class='noMorePosts' value='false'>";
          echo $str;
        }
        else{
          //echo "printing";    
          $str .="<input type='hidden' class='noMorePosts' value='true'><p style='text-align:center'>No more posts to show </p>";
          echo $str;   
        }
        
    
    }  
    //echo $str;

   }
}
?>