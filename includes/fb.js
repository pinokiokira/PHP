  window.fbAsyncInit = function() {
    FB.init({
      appId      : '370341579752518', // App ID
      channelUrl : 'http://softpoint.us/Panels/TeamPanel/includes/channel.html', // Channel File
      status     : true, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true  // parse XFBML
    });
    
    
	FB.Event.subscribe('auth.authResponseChange', function(response) 
	{
 	 if (response.status === 'connected') 
  	{
  		document.getElementById("message").innerHTML +=  "<br>Connected to Facebook";
  		//SUCCESS
  		
  	}	 
	else if (response.status === 'not_authorized') 
    {
    	document.getElementById("message").innerHTML +=  "<br>Failed to Connect";

		//FAILED
    } else 
    {
    	document.getElementById("message").innerHTML +=  "<br>Logged Out";

    	//UNKNOWN ERROR
    }
	});	
	
    };
    
   	function Login()
	{
		FB.login(function(response) {
		   if (response.authResponse) 
		   {
				getUserInfo()
				setTimeout(function(){jQuery('#signup_form').submit()},2000);
				setTimeout(function(){FB.logout()},3000);
  			} else 
  			{
  	    	 console.log('User cancelled login or did not fully authorize.');
   			}
		 },{scope: 'email, user_address, user_mobile_phone, user_photos,user_videos, user_about_me, user_birthday, user_hometown, user_website, read_stream, offline_access, publish_stream, read_friendlists'});
	
	
	}

  function getUserInfo() {
	    FB.api('/me?fields=id,name,location,hometown,gender,birthday,email,first_name,last_name,address', function(response) {
	  
		  document.getElementById('signup_first_name').value = response.first_name;
		  document.getElementById('signup_last_name').value = response.last_name;
		  document.getElementById('signup_email').value = response.email;
		  document.getElementById('client_id').value = response.id;
		  document.getElementById('name_title').value = response.name;
		  document.getElementById('phone').value = '';
		  document.getElementById('address').value = '';
		  document.getElementById('country').value = '';
		  document.getElementById('state').value = '';
		  document.getElementById('city').value = response.hometown.name;
		  document.getElementById('zip').value = '';
		  
			if(response.gender == 'Female' || response.gender == 'female' || response.gender == 'f' || response.gender == 'F') {		
				$gender = 'F';
			}
			else{
				$gender = 'M';
			}			
		  document.getElementById('gender').value = $gender;
		  document.getElementById('dob').value = response.birthday;
		  document.getElementById('profile_image').value = "https://graph.facebook.com/"+response.id+"/picture?width=150&height=150";
		  document.getElementById('provider').value = 'Facebook';
    	});
		return true;
    }
	function getPhoto()
	{
	  FB.api('/me/picture?type=normal', function(response) {

		  var str="<br/><b>Pic</b> : <img src='"+response.data.url+"'/>";
	  	  document.getElementById("status").innerHTML+=str;
	  	  	    
    });
	
	}
	function Logout()
	{
		FB.logout(function(){document.location.reload();});
	}

  // Load the SDK asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));
