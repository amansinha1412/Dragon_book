<?php

include("../../config/config.php");
include("../classes/User.php");
include("../classes/Message.php");

$limit =7; //messages to load

$message = new Message($con,$_REQUEST['userLoggedIn']);
echo $message->getConvosDropDown($_REQUEST,$limit);

?>