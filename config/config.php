<?php
ob_start();//turns on output buffering
session_start();
//$timezone = date_default_timezone("Indian/Christmas");

$con = mysqli_connect("localhost","root","","caavo");//connection variable
if(mysqli_connect_errno())
{
	echo "Failed to connect".mysqli_connect_errno();
}

?>
