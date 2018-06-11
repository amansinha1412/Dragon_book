<?php
//include ("User.php");
class Message{
	private $user_obj;
	private $con;

	public function __construct($con,$user){
		$this->con = $con;
		$this->user_obj =new User($con,$user);
	}
  
  public function getMostRecentUser(){
    $userLoggedIn = $this->user_obj->getUsername();

    $query = mysqli_query($this->con,"SELECT user_to ,user_from from messages where user_to='$userLoggedIn' or user_from = '$userLoggedIn' order by id DESC limit 1" );
    if($query == false)
     printf("error: %s\n", mysqli_error($this->con));
    if(mysqli_num_rows($query)==0){
      return false;
    }

    $row = mysqli_fetch_array($query);
    $user_to = $row['user_to'];
    $user_from = $row['user_from'];

    if($user_to!= $userLoggedIn){
      return $user_to;
    }
    else return $user_from;
  }
  
  public function sendMessage($user_to,$body,$date){
    if($body!=""){
      $userLoggedIn = $this->user_obj->getUsername();
      $query = mysqli_query($this->con,"INSERT into messages values('','$user_to','$userLoggedIn' ,'$body','$date','no','no','no')");
    }

  }

  public function getMessages($other_user){
      $userLoggedIn = $this->user_obj->getUsername();
      $data = "";
      $query = mysqli_query($this->con,"UPDATE messages set opened='yes' where user_to='$userLoggedIn' and user_from='$other_user'");
      $get_messages_query = mysqli_query($this->con,"SELECT * from messages where (user_to='$userLoggedIn' and user_from='$other_user') or (user_from='$userLoggedIn' and user_to = '$other_user') ");

        while($row = mysqli_fetch_array($get_messages_query)){
          $user_to = $row['user_to'];
          $user_from = $row['user_from'];
          $body = $row['body'];

          $div_top  = ($user_to == $userLoggedIn)?"<div class='message' id='green'>":"<div class='message' id='blue'>";
          $data = $data.$div_top.$body."</div><br><br>";
        }
        return $data; 
  }
  
  public function getLatestMessage($userLoggedIn,$user2){

    $details_array = array();

    $query = mysqli_query($this->con,"SELECT body,user_to,date from messages where (user_to = '$userLoggedIn' and user_from='$user2') or (user_to='$user2' and user_from='$userLoggedIn') order by id DESC LIMIT 1" ) ;

    $row = mysqli_fetch_array($query);
    $sent_by = ($row['user_to']==$userLoggedIn)?"They said :":"You said :";
    $date_time_now = date("Y-m-d H:i:s");
          $start_date = new DateTime($row['date']);
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

            array_push($details_array, $sent_by);
            array_push($details_array,$row['body']);
            array_push($details_array,$time_message);

            return $details_array;

  }

  public function getConvos(){
    $userLoggedIn = $this->user_obj->getUsername();
    $return_string="";
    $convos = array();

    $query = mysqli_query($this->con,"SELECT user_to,user_from from messages where user_to='$userLoggedIn' or user_from = '$userLoggedIn' order by id desc");
    
    while($row = mysqli_fetch_array($query)){
         $user_to_push =($row['user_to']!=$userLoggedIn)?$row['user_to']:$row['user_from'];

         if(!in_array($user_to_push,$convos)){
          array_push($convos,$user_to_push);
         }
    }
    foreach ($convos as $username) {
      $user_found_obj = new user($this->con,$username);
      $latest_message_details = $this->getLatestMessage($userLoggedIn,$username);
      $dots = (strlen($latest_message_details[1])>= 12)
      ?"...":"";
      $split = str_split($latest_message_details[1],12);
      $split = $split[0]."...";
      
      $return_string .="<a href='messages.php?u=".$username."'><div class = 'user_found_messages' id='user_found_messages'>
      <img src='".$user_found_obj->getProfilePic()."' style='border-radius:5px ;margin-right:5px;'>".$user_found_obj->getFirstAndLastName()."<br>
      <span class='timestamp_smaller' id='grey'>".$latest_message_details[2]."</span>
      <p id='grey' style='margin:0;'>".$latest_message_details[0].$split."</p>
      </div>
      </a>
      ";




    }
    return $return_string;
  }

}

?>