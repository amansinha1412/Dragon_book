<?php

$fname = "";
$lname = "";
$em = "";
$em2 = "";
$password = "";
$password2 = "";
$date = date("Y-m-d");
$profile_pic="C:/xampp/htdocs/swirlfeed/assets/images/backgrounds/goku.jpg";
$error_array=array();

if(isset($_POST['register_button'])){
	//registration form values
	$flag = true;
	
    

	$fname = strip_tags($_POST['reg_fname']);//remove html tags
    $fname = str_replace(' ','',$fname);//remove spaces

    $fname = ucfirst(strtolower($fname));
    $_SESSION['reg_fname'] = $fname;
    
    $lname = strip_tags($_POST['reg_lname']);//remove html tags
    $lname = str_replace(' ','',$lname);//remove spaces

    $lname = ucfirst(strtolower($lname));
    $_SESSION['reg_lname'] = $lname;

    $em = strip_tags($_POST['reg_email']);//remove html tags
    $em = str_replace(' ','',$em);//remove spaces

    $em = ucfirst(strtolower($em));

    $_SESSION['reg_email'] = $em;

    $em2 = strip_tags($_POST['reg_email2']);//remove html tags
    $em2 = str_replace(' ','',$em2);//remove spaces

    $em2 = ucfirst(strtolower($em2));
    $_SESSION['reg_email2'] = $em2;
//    echo $em;
//    echo $em2;

    //password
    $password = strip_tags($_POST['reg_password']);//remove html tags
    //$em = str_replace(' ','',$em);//remove spaces

    //$password = ucfirst(strtolower($password));

    $password2 = strip_tags($_POST['reg_password2']);
     //$date = date("Y-m-d");
     //echo $date;
    if($em==$em2){
      if(filter_var($em, FILTER_VALIDATE_EMAIL)){
      	  $em = filter_var($em, FILTER_VALIDATE_EMAIL);

      	  //check if mail already exists
  
      	  $check = mysqli_query($con,"SELECT email from users where email='$em'");
      	  //Count num of rows used
      	  $num_rows = mysqli_num_rows($check);
      	  if($num_rows>0){
                array_push($error_array,"Email already in use<br>"); 
      	        echo"error";
      		  	$flag = false;
      	  }

      }
    } 
    else{
        array_push($error_array,"Emails don't match<br>");
    	echo"error";
        $flag = false;
    }
    if(strlen($fname)>25 || strlen($fname)<2)
    {echo"error";
    array_push($error_array,"First name must be greater or less than 2 and 25 chracters respectively<br>");  
    $flag = false;
    }
    if(strlen($lname)>25 || strlen($lname)<2)
    {echo"error";
     array_push($error_array,"Last name must be greater or less than 2 and 25 chracters respectively<br>");
    $flag = false;
    }
    if($password != $password2){
    	echo"error";
        array_push($error_array,"Passwords dont match<br>");
    	$flag = false;
    }
    else{
    	if(preg_match('/[^A-Za-z0-9]/',$password)){
            array_push($error_array,"Passwords must contain alphabets or digits<br>");
    		echo"error";
    		$flag = false;
    	}
    }
    if(strlen($password)>30 || strlen($password)<5){
        array_push($error_array,"Passwords must contain 5 or more characters<br>");
    	echo"error";
    	$flag = false;
    }
    
    if($flag){
    	$password = md5($password);
    	echo "registering";
    	//generate username by concatenation of first and last name

    	$username = strtolower($fname."_".$lname);
    	$check_username_query = mysqli_query($con,"SELECT username from users where username='$username'");
    	$i=0;
    	//if username exists add no to username
    	while(mysqli_num_rows($check_username_query)!=0){
    		$i++;
    		$username = $username."_".$i;
    		$check_username_query =mysqli_query($con,"SELECT username from users where username='$username'"); 
    	}
        //echo $username;

    	//profile pic assinment

        $n = rand(0,5);
        $arr = array();
        $arr[0] = '1';
        $arr[1] = '2';
        $arr[2] = '3';
        $arr[3] = '4';
        $arr[4] = '5';
        $arr[5] = '6';

        //echo $arr[$n];

     	$profile_pic = "assets/images/profile_pics/default/".$arr[$n].".png";
    	$k = "NO";
        $query = mysqli_query($con,"INSERT into users values ('','$fname' , '$lname' , '$username' , '$em' , '$password' ,'$profile_pic','$date','0','0','NO',',') " );
       
       array_push($error_array, "<span style='color:#14C800'>You're all set go ahead and login</span>"); 
       //echo ""
        if($query === false){
        	printf("error: %s\n", mysqli_error($con));
        }
    }
    else{
       // header("location: register.php");
    }
    
    

}
?>
