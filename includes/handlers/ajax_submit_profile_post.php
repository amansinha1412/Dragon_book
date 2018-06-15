<?php
//include("includes/header.php");
require '../../config/config.php';
include("../classes/User.php");
include("../classes/Post.php");
include("../classes/Notification.php");

if(isset($_POST['post_body'])){
    echo $_POST['user_from'];
	$post = new post($con,$_POST['user_from']);
	$post->submitPost($_POST['post_body'],$_POST['user_to']);
}

?>
