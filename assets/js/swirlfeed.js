$(document).ready(function(){
   
   //Button for profile post
   $('#submit_profile_post').click(function(){
       //alert("here");
       $.ajax({
       	type:"Post",
       	url:"includes/handlers/ajax_submit_profile_post.php",
       	data: $('form.profile_post').serialize(),
       	success:function(msg){
       		//alert("success");
       		$('#post_form').modal('hide');
       		location.reload();
       	},
       	error: function(){
       	   alert("Failed to load posts");	
       	} 


       });
   });

});

function getUsers(value,user){
  $.post("includes/handlers/ajax_friend_search.php",{query:value,userLoggedIn:user},function(data){
       $(".results").html(data);
  });

}
