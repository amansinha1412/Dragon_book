$(document).ready(function(){
   

  $('#search_text_input').focus(function(){
     if(window.matchMedia("(min-width:800px)").matches){
          
          $(this).animate({width:'350px'} ,500);
     }
  }); 
  
  $('#search_text_input').on("keydown",function(e){
      if(e.keyCode == 13){
         document.search_form.submit();   
      }
  });

  
 
  $('.button_holder').on('click' ,function(){
       document.search_form.submit();
  });
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

$(document).click(function(e){
   if(e.target.class != "search_results" && e.target.id!='search_text_input'){
      $('.search_results').html("");
      $('.search_results_footer').html("");
        $('.search_results_footer').toggleClass("search_results_footer_empty");
        $('.search_results_footer').toggleClass("search_results_footer");
   }

   if(e.target.class != 'dropdown_data_window'){
      $('.dropdown_data_window').html("");
      $('.dropdown_data_window').css("padding:0px","height:0px");
      
   }
});

function getUsers(value,user){
  $.post("includes/handlers/ajax_friend_search.php",{query:value,userLoggedIn:user},function(data){
       $(".results").html(data);
  });

}

function getDropdownData(user,type){
  if($(".dropdown_data_window").css("Height") == "0px"){

   var pageName;
   if(type=="notification"){
     pageName = "ajax_load_notifications.php";
      $('span').remove('#unread_notification'); 
    
   }
   else if(type=="message"){
    pageName = "ajax_load_messages.php";
    $('span').remove('#unread_message'); 
    
      //console.log("hi2");
   }

   var ajaxreq = $.ajax({
          url :"includes/handlers/"+pageName,
          type:"POST",
          data:"page=1&userLoggedIn="+user,
          cache :false,
          success:function(response){
            console.log(type);
            $(".dropdown_data_window").html(response);
            $(".dropdown_data_window").css("padding : 0px", "height : 280px");
            $("#dropdown_data_type").val(type);
          } 
   });

  
}
  else{
    $(".dropdown_data_window").html("");
    $(".dropdown_data_window").css("padding:0px", "height:0px");

  }
}

function getLiveSearchUsers(value,user){
  
   $.post("includes/handlers/ajax_search.php",{query:value ,userLoggedIn:user},function(data){
      if($('.search_results_footer_empty')[0]){
        $('.search_results_footer_empty').toggleClass("search_results_footer");
        $('.search_results_footer_empty').toggleClass("search_results_footer_empty"); 

      } 
      $('.search_results').html(data);
      $('.search_results_footer').html("<a href='search.php?q="+value+"'>See All results</a>");

      if(data==""){
        $('.search_results_footer').html("");
        $('.search_results_footer').toggleClass("search_results_footer_empty");
        $('.search_results_footer').toggleClass("search_results_footer");
      }  
   });

}
function search_submit(){
     //console.log("hi");
     document.search_form.submit();
    // $('#search_text_input').val ="";
  } 