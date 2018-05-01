<?php
require 'config/config.php';

if(isset($_SESSION['username'])){
	$userLoggedIn = $_SESSION['username'];
    $user_details_query = mysqli_query($con,"SELECT * from users where username='$userLoggedIn'");
    $user = mysqli_fetch_array($user_details_query);

}
else{
	header("Location: register.php");
}

?>
<!DOCTYPE html>
<html>
<head>
	<!-- js -->
	<title>welcome to caavo drive</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="assets/js/bootstrap.js" ></script>
	<!-- cs -->

	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

	
</head>
<body>
    <div class = "top_bar">
    	<div class="logo">
    		<a href="index.php">Swirlfeed</a>
        </div>
        
        <nav>
        	<a href=""><?php
             echo $user['fname'];
        	?></a>
        	<a href="index.php"><i class="fa fa-home fa-lg" aria-hidden="true"></i></a>
        	<a href=""><i class="fa fa-commenting-o" aria-hidden="true"></i></a>
        	<a href=""><i class="fa fa-cog" aria-hidden="true"></i></a>
        	<a href=""><i class="fa fa-users" aria-hidden="true"></i></a>

        	<a href=""><i class="fa fa-bell-o" aria-hidden="true"></i></a>
        	<a href="includes/handlers/logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
          
        </nav>
    </div>

  <div class="wrapper">
