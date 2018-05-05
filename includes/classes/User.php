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
}
?>