<?php
include("includes/header.php");

if(isset($_GET['q'])){
	$query = $_GET['q'];

}
else{
	$query="";
}
if(isset($_GET['type'])){
	$type = $_GET['type'];

}
else{
	$type="name";
}

?>
<div class="main_column column" id="main_column">
 <?php
   if($query==""){
   	echo "You must enter something in search box.";
   }
   else{

 //If query contains underscore,assume users are searching for usernames.
 if($type=="username")
 	$usersreturnedQuery = mysqli_query($con,"SELECT * from users where username like '$query%' and account_closed='no' LIMIT 8");
 else{
 	 $names = explode(" ",$query);
 	 if(count($names)==3){
    	$usersreturnedQuery = mysqli_query($con,"SELECT * from users where (fname like '$names[0]%' and lname like '$names[2]%') and account_closed='no'");
}

//If query has one word only search fname and lnames0
//If there are two words ,assume they are fnames and last names
	else if(count($names)==2){
		$usersreturnedQuery = mysqli_query($con,"SELECT * from users where (fname like '$names[0]%' and lname like '$names[1]%') and account_closed='no'");
		//printf("error: %s\n", mysqli_error($con));
	}
	else{
		$usersreturnedQuery = mysqli_query($con,"SELECT * from users where (fname like '$names[0]%' or lname like '$names[0]%') and account_closed='no'");
	}
}
    
	//check if results were found
	if(mysqli_num_rows($usersreturnedQuery)==0)
		echo "No matching searches for ".$type." like ".$query;
	else
		echo mysqli_num_rows($usersreturnedQuery)." results found.<br><br>";
	echo "<p id='grey'>Try Searching for :</p>";
	echo "<a href='search.php?q=".$query."&type=name'>Names</a> , <a href='search.php?q=".$query."&type=username'>Usernames</a><br><br><hr id='search_hr'>";
    
    while($row=mysqli_fetch_array($usersreturnedQuery)){

    	$user_obj = new User($con,$user['username']);

    	$button="";
    	$mutual_friends = "";
    	if($user['username']!=$row['username']){

    	   	//Generate button and friendship status

    	   	if($user_obj->isFriend($row['username'])){
    	   		   $button = "<input type='submit' name ='".$row['username']."' class='danger' value='Remove Friend'>";
    	   	}
    	   	else if($user_obj->didRecieveRequest($row['username'])){
    	   		 $button="<input type='submit' name ='".$row['username']."' class='warning' value='Respond to  Request'>";
    	   	}
    	   	else if($user_obj->didSendRequest($row['username'])){
    	   		$button="<input type='submit' name ='".$row['username']."' class='default' value='Request Sent'>";
    	   	}
    	   	else{
    	   		$button="<input type='submit' name ='".$row['username']."' class='success' value='Add Friend'>";
    	   	}

    	   	$mutual_friends = $user_obj->getMutualFriends($row['username'])." Mutual Friends";
             
            //Button Form
            if(isset($_POST[$row['username']])){
            	if($user_obj->isFriend($row['username'])){
            		$user_obj->removeFriend($row['username']);
            		header("location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            	}
            	else if($user_obj->didRecieveRequest($row['username'])){
            		header("location: request.php");
            	}
            	else if($user_obj->didSendRequest($row['username'])){
            		//
            	}
            	else{
            		$user_obj->sendRequest($row['username']);
            		header("location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            	}
            } 

    	}
    	echo "<div class='search_result'>
                <div class='searchPageFriendButton'>
                  <form action='' method='Post'>
                    ".$button."
                    <br>
                  </form>
                </div>
                <div class='result_profile_pic'>
                   <a href='".$row['username']."'>
                     <img src='".$row['profile_pic']."' style='Height:100px;'>
                   </a>
                   </div>
                   <a href='".$row['username']."'>".$row['fname']." ".$row['lname']."
                   <p id='grey'>".$row['username']."</p>
                   </a>
                   <br>
                   ".$mutual_friends."<br>
                
              </div>
              <hr id='search_hr'>
    	";

    }//End While
 }


  
   

   

 ?>	
</div>