<?php
require '../../config/config.php';
  if(isset($_GET['post_id'])){
  	echo "here";
  	$post_id = $_GET['post_id'];
  }

  if(isset($_POST['result'])){
  	if($_POST['result'] == 'true'){
     //echo "here2";
      $delete_query = mysqli_query($con,"UPDATE posts set deleted='YES' where id='$post_id'");
  	}
  }
?>