<?php
include("../../config/config.php");
include("../classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ",$query);

if(strpos($query,"_") !== false){
	$usersReturned = mysqli_query($con,"SELECT * from users where username like '$query%' and account_closed='no' LIMIT 8");
    //printf("error: %s\n", mysqli_error($con));
}
else if(count($names)==2){
	$usersReturned = mysqli_query($con,"SELECT * from users where (fname like '$names[0]%') and (lname ='$names[1]%') and account_closed='no' limit 8");
	//printf("error: %s\n", mysqli_error($con));
}
else{
	$usersReturned = mysqli_query($con,"SELECT * from users where((fname like '$names[0]%') or (lname ='$names[0]%') )and account_closed='no' limit 8");
	//printf("error: %s\n", mysqli_error($con));
}

if($query !=""){
	while($row = mysqli_fetch_array($usersReturned)){
		$user = new User($con , $userLoggedIn);

		if($row['username'] != $userLoggedIn){
			$mutual_friends = $user->getMutualFriends($row['username'])." Mutual Friends";
		}
		else{
			$mutual_friends="";
		}

		if($user->isFriend($row['username'])){
			echo "<div class='resultDisplay'>
			<a href = 'messages.php?u=".$row['username']."' style='color:#000;'>
			<div class ='liveSearchProfilePic'>
			    <img src='".$row['profile_pic']."'>

			    <div class='liveSearchText'>
			    ".$row['fname']." ".$row['lname']."
			    <p  style='margin:0;'>".$row['username']."</p>
			    <p id = 'grey'>".$mutual_friends."
			    </p>
			    </div>
			    </a>
			    </div>
			    </div>
			";
		}
	}
}

?>