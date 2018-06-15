<?php
 include("../../config/config.php");
 include("../../includes/classes/User.php");

 $query = $_POST['query'];
 $userLoggedIn = $_POST['userLoggedIn'];

 $names = explode(" ",$query);

 //If query contains underscore,assume users are searching for usernames.
 if(strpos($query,'_')!== false)
 	$usersreturnedQuery = mysqli_query($con,"SELECT * from users where username like '$query%' and account_closed='no' LIMIT 8");

//If there are two words ,assume they are fnames and last names
else if(count($names)==2){
    	$usersreturnedQuery = mysqli_query($con,"SELECT * from users where (fname like '$names[0]%' and lname like '$names[1]%') and account_closed='no' LIMIT 8");
}

//If query has one word only search fname and lnames
else{
	$usersreturnedQuery = mysqli_query($con,"SELECT * from users where (fname like '$names[0]%' OR lname like '$names[0]%') and account_closed='no' LIMIT 8");
	//printf("error: %s\n", mysqli_error($con));
}


if($query!=""){
	while($row = mysqli_fetch_array($usersreturnedQuery)){
		$user = new User($con,$userLoggedIn);

		if($row['username'] !=$userLoggedIn){
			 $mutual_friends = $user->getMutualFriends($row['username'])." friends in common";
		}
		else{
			$mutual_friends ="";
		}

        
		echo "<div class='resultDisplay'>
              <a href='".$row['username']."' style='color:#1485BD;'>
               <div class='liveSearchProfilePic'>
                 <img src='".$row['profile_pic']."'>
               </div>
               <div class='LiveSearchText'>
                    ".$row['fname']." ".$row['lname']."
                  <p >".$row['username']."</p>
                  <p id='grey'>".$mutual_friends."</p>
               </div>
               
              </a>
              </div> 
		 ";
		 
		 //echo $row['username'];
	}
}
?>