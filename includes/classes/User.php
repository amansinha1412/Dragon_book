<?php
class User{
	private $user;
	private $con;

	public function __construct($con,$user){
		$this->con = $con;
		$user_details_query = mysqli_query($this->con,"SELECT * FROM users WHERE username='$user'");
		if($user_details_query==false){
			printf("error: %s\n", mysqli_error($this->con));
		}
		$this->user = mysqli_fetch_array($user_details_query);

		//echo $this->user['username'];
	}
	public function getUserName(){
		return $this->user['username'];
	}
    public function getNumPosts(){
        $username = $this->user['username'];
    	$query = mysqli_query($this->con,"SELECT num_of_posts from users where username='$username'");
    	$row = mysqli_fetch_array($query);
    	return $row['num_of_posts'];

    }
	public function getFirstAndLastName(){
		$username = $this->user['username'];
		$query = mysqli_query($this->con,"SELECT fname,lname from users where username='$username'");
		$row = mysqli_fetch_array($query);
		return $row['fname']." ".$row['lname'];
	}
	public function isClosed(){
		$isClosed = $this->user['account_closed'];
		if($isClosed=='NO')
			return false;
		else return true;
	}
	public function isFriend($username_to_check){
      
      $usernameComma = ",".$username_to_check.",";
      if(strstr($this->user['friend_array'],$usernameComma)|| $username_to_check==$this->user['username']){
      	return true;

      }
      else return false;
	}
	public function getProfilePic(){
		$username = $this->user['username'];
		$query = mysqli_query($this->con,"SELECT profile_pic from users where username='$username'");
		$row = mysqli_fetch_array($query);
		return $row['profile_pic'];
	}
	public function didRecieveRequest($user_from){
		$user_to = $this->user['username'];
		$check_request_query = mysqli_query($this->con,"SELECT * FROM friend_requests where user_to='$user_to' and user_from='$user_from'");
		if(mysqli_num_rows($check_request_query)>0){
             return true;
		}
		else return false;

	}
	public function didSendRequest($user_to){
		$user_from = $this->user['username'];
		$check_request_query = mysqli_query($this->con,"SELECT * FROM friend_requests where user_to='$user_to' and user_from='$user_from'");
		if(mysqli_num_rows($check_request_query)>0){
             return true;
		}
		else return false;

	}
	public function removeFriend($user_to_remove){
          $logged_in_user = $this->user['username'];

          $query = mysqli_query($this->con,"SELECT friend_array from users where username='$user_to_remove'");
          $row = mysqli_fetch_array($query);
          $friend_array_username = $row['friend_array'];

          $new_friend_array = str_replace($user_to_remove.",","",$this->user['friend_array']);
          $remove_friend = mysqli_query($this->con,"UPDATE users set friend_array='$new_friend_array' where username='$logged_in_user'");
          $new_friend_array = str_replace($this->user['username'].",","",$friend_array_username);
          $remove_friend = mysqli_query($this->con,"UPDATE users set friend_array='$new_friend_array' where username='$user_to_remove'");
	}
	public function sendRequest($user_to){
		$user_from=$this->user['username'];
		$query = mysqli_query($this->con,"INSERT into friend_requests values('','$user_to','$user_from')");
		
	}
	public function getFriendArray(){
		$username = $this->user['username'];
		$query = mysqli_query($this->con,"SELECT friend_array from users where username='$username'");
		$row = mysqli_fetch_array($query);
		return $row['friend_array'];
	}

	public function getMutualFriends($user_to_check){
		$mutual_friends = 0;
		$user_array = $this->user['friend_array'];
		$user_array_explode = explode(",",$user_array);

		$query = mysqli_query($this->con,"SELECT friend_array from users where username='$user_to_check'");
		$row=mysqli_fetch_array($query);
		$user_to_check_array = $row['friend_array'];
		$user_to_check_array_explode = explode(",",$user_to_check_array);

		foreach($user_array_explode as $i){
			foreach ($user_to_check_array_explode as $j) {
				# code...
				if($i == $j && $i!=""){
					$mutual_friends++;

				}
			}
		}
		return $mutual_friends; 
	} 
}
?>