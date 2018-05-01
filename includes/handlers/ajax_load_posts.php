<?php
include ("../../config/config.php");
include("../classes/user.php");
include("../classes/Post.php");


$limit = 10;
echo $_REQUEST['page'];

$posts = new Post($con,$_REQUEST['userLoggedIn']);
$posts->loadPostsFriends($_REQUEST,$limit);

?>