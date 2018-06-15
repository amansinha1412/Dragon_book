<?php
require 'config/config.php';
include("classes/Message.php");
include("classes/User.php");
include("classes/Post.php");
include("classes/Notification.php");

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
	<title>Welcome to dragon ball super book</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="assets/js/bootstrap.js" ></script>
    <script src="assets/js/swirlfeed.js" ></script>
    <script src="assets/js/bootbox.min.js" ></script>
    <script src="assets/js/jquery.Jcrop.js" ></script>
    <script src="assets/js/jcrop_bits.js" ></script>
	<!-- cs -->

	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.Jcrop.css">
	
</head>
<body>
    <div class = "top_bar">
    	<div class="logo">
    		<a href="index.php">Swirlfeed</a>
        </div>
       
       <div class="search">
             <form  action="search.php" method="GET" name="search_form">
                <input type="text" onkeyup="getLiveSearchUsers(this.value,'<?php echo $userLoggedIn?>')" name="q" placeholder="Search.." autocomplete="off" id="search_text_input">

                <div class="button_holder" >
                   <img src="assets/images/icons/magnifying_glass.png"> 
                </div>  
              </form>
              <div class="search_results">
                </div>
                <div class="search_results_footer_empty" onclick = "search_submit()">
                </div>

        </div>

        <nav>
            <?php
               //Unread Messages 
               $messages = new Message($con,$userLoggedIn);
               $num_messages = $messages->getUnreadNumber();

               //unread notifications

               $notifications= new Notification($con,$userLoggedIn);
               $num_notifications = $notifications->getUnreadNumber();

               $user_obj= new User($con,$userLoggedIn);
               $num_requests = $user_obj->getNumberOfFriendRequests();



            ?>
        	<a href=<?php echo $userLoggedIn; ?> ><?php
             echo $user['fname'];
        	?></a>
        	<a href="index.php" class="home_icon_link"><i class="fa fa-home fa-lg" aria-hidden="true"></i></a>
        	<a href=""><i class="fa fa-commenting-o" aria-hidden="true"></i></a>
        	<a href="settings.php"><i class="fa fa-cog" aria-hidden="true"></i></a>
            <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>','message')"> <i class="fa fa-envelope-square" aria-hidden="true"></i>
                <?php
                if($num_messages>0)

                echo '<span class="notification_badge" id ="unread_message">'.$num_messages.' </span>'; 
                
                ?>

            </a>
        	<a href="request.php"><i class="fa fa-users" aria-hidden="true"></i>
                <?php
                if($num_requests>0)

                echo '<span class="notification_badge" id ="unread_request">'.$num_requests.' </span>'; 
                
                ?>

            </a>

        	<a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>','notification')"><i class="fa fa-bell-o" aria-hidden="true"></i>
             <?php
                if($num_notifications>0)
                echo '<span class="notification_badge" id ="unread_notification">'.$num_notifications.' </span>'; 
                
                ?>
   

            </a>
        	<a href="includes/handlers/logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
          
        </nav>
        <div class="dropdown_data_window" ></div>
            <input type="hidden" id="dropdown_data_type" value="">
         
         <script>
        var userLoggedIn = '<?php echo $userLoggedIn?>';
        //echo userLoggedIn;
        //alert(userLoggedIn);
       //sessionStorage.current_page = 1;
      // alert(sessionStorage.current_page);
        $(document).ready(function(){
          
    
           //original req for loading req


           

           $('.dropdown_data_window').scroll(function(){
                  
                 var inner_height = $('.dropdown_data_window').innerHeight();//div containing posts
                 //alert(height); 
                 var scroll_top = $('.dropdown_data_window').scrollTop();
                 var page = $('.dropdown_data_window').find('.nextPageDropDownData').val();
                 var noMoreData = $('.dropdown_data_window').find('.noMoreDropDownData').val();
                // alert (noMorePosts);
                //console.log(page);
                 //console.log(document.body.scrollHeight);
                // console.log(document.body.scrollTop);
                // console.log($(window).innerHeight());
                 //(document.body.scrollHeight == document.body.scrollTop + window.innerHeight)   
                    var scrollHeight, totalHeight;
                    scrollHeight = document.body.scrollHeight;
                    totalHeight = window.scrollY + window.innerHeight;
                    //var current_page = '<%= Session["current_page"] %>';

                 //(document.body.scrollHeight == document.body.scrollTop + window.innerHeight)
                 var k = $('.dropdown_data_window')[0].scrollHeight
                  console.log("hi3");
                 if((scroll_top + inner_height >= k ) && noMoreData == "false" ){

                     var pageName;
                     var type = $('#dropdown_data_type').val();

                     if(type=="notification"){
                        pageName = "ajax_load_notifications.php";
                     }
                     else if(type=="message"){
                        pageName = "ajax_load_messages.php";
                     }
                     var ajaxReq = $.ajax({
                        url:"includes/handlers/"+pageName,
                        type:"POST",
                        async:false,//adding it made a single call for each ajax call
                        data:"page="+page+"&userLoggedIn="+userLoggedIn, 
                        cache:false,
                        success:function(response){
                            $('.dropdown_data_window').find('.nextPageDropDownData').remove(); //removes current next page

                            $('.dropdown_data_window').find('.noMoreDropDownData').remove(); //removes current next page
 

                            //$('#loading').hide();
                            $('.dropdown_data_window').append(response);
                        }
                        //sessionStorage.current_page = page;

                     });

 

                 }// end if 
                  

                return false;


           });//end (window).scroll(function());
        }) ;    
    </script>   


    </div>

  <div class="wrapper">
