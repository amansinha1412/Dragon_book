<?php
//include ("User.php");
class Notification{
  private $user_obj;
  private $con;

  public function __construct($con,$user){
    $this->con = $con;
    $this->user_obj =new User($con,$user);
  }

  public function getUnreadNumber(){
    $userLoggedIn = $this->user_obj->getUsername();
    $query = mysqli_query($this->con,"SELECT * from notifications where viewed='no' and user_to='$userLoggedIn'");
    return mysqli_num_rows($query);
  }

  public function getNotifications($data,$limit){
   $page = $data['page'];
   $userLoggedIn = $this->user_obj->getUsername();
    $return_string="";
   // $convos = array();
    //echo $page;
    if($page ==1){
      $start=0;
    }
    else {
      $start = ($page-1)*$limit;
    }

    $set_viewed_query = mysqli_query($this->con,"UPDATE notifications set viewed ='yes' where user_to = '$userLoggedIn'");

    $query = mysqli_query($this->con,"SELECT * from notifications where user_to='$userLoggedIn' order by id desc");
    
    if(mysqli_num_rows($query)==0){
      echo  "You have no notifications";
      return ;
    }
    
    //echo mysqli_num_rows($query);
    $num_iterations = 0;//number of messages checked
    $count = 1;// number of messages posted

   
    $i=0;

    while ($row = mysqli_fetch_array($query)) {
      //echo $i++;
      if($num_iterations++ < $start){
        continue;
      }

      if($count > $limit){
       // echo "here";
        break;
      }
      else $count++;

       $user_from = $row['user_from'];
       //echo $user_from;
       $query2 = mysqli_query($this->con,"SELECT * from users where username='$user_from'");
        
          $user_data = mysqli_fetch_array($query2);

       
          $date_time_now = date("Y-m-d H:i:s");
          $start_date = new DateTime($row['datetime']);
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

            

      $opened = $row['opened'];  
      $style = ($opened == 'no')? "":"background-color:#fff;";
    //
      /*
      $user_found_obj = new user($this->con,$username);
      $latest_message_details = $this->getLatestMessage($userLoggedIn,$username);
      $dots = (strlen($latest_message_details[1])>= 12)
      ?"...":"";
      $split = str_split($latest_message_details[1],12);
      $split = $split[0]."...";
      */
      $return_string .="<a href='".$row['link']."'>
        <div class='resultDisplay resultDisplayNotification' style='".$style."'> 
        <div class = 'notificationsProfilePic'>
          <img src='".$user_data['profile_pic']."'>
          </div>
          <p class='timestamp_smaller' id='grey'>".$time_message."</p> ".$row['message']."</div></a>";

    }

    //if posts were loaded
    if($count>$limit){
      $return_string .="<input type='hidden' class='nextPageDropDownData' value='".($page+1)."'><input type='hidden' class='noMoreDropDownData' value='false' >";
    }
    else{
     $return_string .="<input type='hidden' class='noMoreDropDownData' value='true'><p style='text-align:center;'> No more notifications to show</p>"; 
    }
    return $return_string;   
   } 

  public function insertNotification($post_id,$user_to,$type){
    $userLoggedIn = $this->user_obj->getUsername();
    $userLoggedInName = $this->user_obj->getFirstAndLastName();

    $date_time = date("Y:m:d H:i:s");
    
    switch($type){
      case "comment":
      $message = $userLoggedInName . " commented on your post";
      break;
      case "like":
      $message = $userLoggedInName . " liked your post";
      break;
      case "profile_post":
      $message = $userLoggedInName . " posted on your profile";
      break;
      case "comment_non_owner":
      $message = $userLoggedInName . " commented on a post you commented on";
      break;
      case "profile_comment":
      $message = $userLoggedInName . " commented on your profile's post";
      break;
    }

    $link = "post.php?id=".$post_id;
    
    $insert_query = mysqli_query($this->con,"INSERT into notifications values ('','$user_to','$userLoggedIn','$message' ,'$link','$date_time','no','no' )"); 
  }

}
?>