/*
*  @modified Ionut Irofte - juniorionut @ elance
*  @version $Id: index.js ,v 1.0 10:38 PM 7/4/2014 juni $
*  -> [req 1.33  - 04.07.2014]
		-> Code Indentation
		-> New methods which combines code to resemble the "behaviour" of all existing panels
		-> Add password requirements pop'up additional validation rules
*/
var API = 'ajax/proxy.php?url=';

//juni -> 06.07.2014 -> block of code from admin panel. Comment this out and uncomment code in main.js to have the same behaviour
jQuery(document).ready(function(){
    jQuery('#login').submit(function(e){
       e.preventDefault();
       var u = jQuery('#username').val();
       var p = jQuery('#password').val();
        if(u == '' || p == '') {
            jQuery('.login-alert').fadeIn();
            return false;
        }else{
            var checkcon = CheckConnection();
            if (checkcon==""){
                signin();
            }
        }
    });
});
function CheckConnection() {	
        var url = "api/criticalmission.php?token="+generatetoken();
	var isError = false;	
	jQuery.ajax({
		url:url,
		async:false,
		dataType:'json',
		success:function(data) {
			if (data.ret == 1) {				
				isError = false;
			}else if (data.ret == 3) {
                                document.location.href=data.dest;
                            
                        } else {
                                //jAlert(data.mes,'Alert Dialog');
				isError = false;
				return true; 
			}
		}
	});
	return isError;
}
function signin(){
	jQuery.ajax({
		url: 'signin.php',
		data: jQuery('#login').serialize(),
		type: 'POST',
		dataType: 'json',
		success:function(data){
			if(data.success){
				if(data.password_change == "Y")	{
					if(data.alertt == "Y"){
						jAlert("Your account has been deactivated due to too many failed log in attempts. An email has been sent to you with infomation on how to reactivate your account.","Alert Dialog!");
					} else if (data.failue == "Y")	{
						jAlert("Your account has been deactivated due to too many failed log in attempts. An email has been sent to you with infomation on how to reactivate your account.","Alert Dialog!");
					} else {
						window.location.href = 'password_change.php';
					}
				} else {
					/*if (data.teamalert==null) {
						window.location.href = 'dashboard.php?tm=1';     
					} else*/ if (data.day == 1)  {
						window.location.href = 'dashboard.php?day=1';	
					} else {
						window.location.href = 'dashboard.php';
					}
				}
				//   jAlert("");
				//  window.location.href = 'help.php';
			} else {
				if(data.failue_st == "Y"){
					jAlert("Your account has been deactivated due to too many failed log in attempts. An email has been sent to you with infomation on how to reactivate your account.","Alert Dialog!");
					jQuery('#loginerror').text(error(data.error_code));
					jQuery('.login-alert').fadeIn();
				} else {
                                        if (data.error_code==6){
                                            jAlert("Your Team account has not yet been confirmed. We have sent you an email, so that you can confirm your account. Please check your emails.");
                                        }
					jQuery('#loginerror').text(error(data.error_code));
					jQuery('.login-alert').fadeIn();
				}
			}
		},
		error: function(a,b,c){
			jQuery('#loginerror').text('An error has occurred, Please try again.');
			jQuery('.login-alert').fadeIn();
			jQuery.unblockUI();
		}
	});
}
function error(e){
    
	var msg = '';
	switch(e){
		case 1:
			msg = 'User field is required!';
			break;
		case 2:
			msg = 'Password field is required!';
			break;
		case 3:
			msg = 'Incorrect user and password combination!';
			break;
		case 4:
			msg = 'User is not active!';
			break;
                case 5:
			msg = 'User is not active!';
			break;   
                case 6:
                        msg = 'User is not active!';
                        break;          
	}
	if(msg != ''){
	return msg;
	}
}

jQuery(document).ready(function(){
	jQuery("#signu_up_btn").click(function(event,obj){
		jQuery.colorbox({
			href: 'ajax/signup-form.php',
			title: '<h4 class="widgettitle title-primary">Sign Up</h4>',
			width: '370px',
			height: '650px',
			initialWidth:'200px',
			initialHeight:'100px',
			onComplete:function(){
				
				/*jQuery('#show_password').click(function(){
					if(jQuery(this).is(':checked')){
						jQuery('#signup_password_text').show();
						jQuery('#signup_password').hide();
					} else {
						jQuery('#signup_password').show();
						jQuery('#signup_password_text').hide();
					}
				});
				
				jQuery('#signup_password').keyup(function() {
					jQuery('#signup_password_text').val(jQuery('#signup_password').val());
				});
				jQuery('#signup_password_text').keyup(function() {
					jQuery('#signup_password').val(jQuery('#signup_password_text').val());
				});*/
				
				jQuery("#signup_form").validate({
					rules: {
						signup_first_name: "required",
						signup_last_name: "required",
						signup_email: {
							required: true,
							email: true,
							remote: {
								url: API + 'check_availability',
								data: {
									email: function () {
										return jQuery('#signup_email').val();
									}
								}
							}
						},
						signup_password: {
							required: true,
							pwcheck: true,
							minlength: 6
						  ,notEqualTo: "#signup_email"
						}
					},
					messages: {
						signup_first_name: "Please enter your first name",
						signup_last_name: "Please enter your last name",
						signup_email: { 
							required : "Please enter a email address",
							email: "Please enter a valid email address",
							remote: "Already exists, please choose different one"
						},
						signup_password: "Please enter a valid password"
					},
					highlight: function(label) {
						jQuery(label).closest('.control-group').addClass('error');
					},
					
					errorElement : 'span'
				});
				//juni -> 04.07.2014 -> new criteria(s)
				jQuery.validator.addMethod("pwcheck", function(value) {
					return /^[A-Za-z0-9\d=!\-@._*]*/.test(value) // consists of only these
					&& /[A-Z]/.test(value) // has a lowercase letter
					&& /\d/.test(value) // has a digit
				});
				jQuery.validator.addMethod("notEqualTo", function (value, element, param)	{
					var target = jQuery(param);
					if (value) return value != target.val();
					else return this.optional(element);
				}, "Does not match");
				
				jQuery('#signup_submit').click(function(event){
					event.preventDefault();
					if(jQuery("#signup_form").valid()){

						var first_name = jQuery('#signup_first_name').val();
						var last_name = jQuery('#signup_last_name').val();
						var email = jQuery('#signup_email').val();
						var password = jQuery('#signup_password').val();
						var client_id = jQuery('#client_id').val();
						var name_title = jQuery('#name_title').val();
						var phone = jQuery('#phone').val();
						var address = jQuery('#address').val();
						var country = jQuery('#country').val();
						var state = jQuery('#state').val();
						var city = jQuery('#city').val();
						var zip = jQuery('#zip').val();
						var gender = jQuery('#gender').val();
						var dob = jQuery('#dob').val();
						var profile_image = jQuery('#profile_image').val();
                                                if (profile_image!=""){
                                                   jQuery.ajax({
                                                        async: false,
							url: 'setup_upload_sm_profile_image.php',
							data:{profile_image:profile_image},
							type: 'POST',
							success: function(data){
								profile_image = data;
                                                        
							}
                                                    });
                                               } 
						var last_datetime = jQuery('#last_datetime').val();
						var provider = jQuery('#provider').val();
						var Created_datetime = jQuery('#Created_datetime').val();
						jQuery.blockUI({ message: null }); 
                                                var ajaxData = {
								name: first_name+' '+last_name,
								first_name: first_name,
								last_name: last_name,
								email: email,
								password: password,
								name_title:name_title,
								phone:phone,
								address:address,
								country:country,
								state:state,
								city:city,
								zip:zip,
								created_on: 'TeamPanel',
								created_by: 'Self',
								gender:gender,
								dob:dob,
								last_by:'Self',
								last_on:'TeamPanel',
								last_datetime:last_datetime,
								Created_datetime:Created_datetime,
								status : 'A'
                                                };
                                                if(provider == 'Google'){
                                                        ajaxData.google_id = client_id;
                                                        ajaxData.google_image = profile_image;
                                                        ajaxData.image = profile_image;
                                                        ajaxData.google_status = "Linked";
                                                }
                                                else{
                                                        ajaxData.google_id = 'N';
                                                        ajaxData.google_image = "";
                                                        ajaxData.google_status = "Inactive";
                                                }
                                                if(provider == 'LinkedIn'){
                                                        ajaxData.linkedin_id = client_id;
                                                        ajaxData.linkedin_image = profile_image;
                                                        ajaxData.image = profile_image;
                                                        ajaxData.linkedin_status = "Linked";
                                                        
                                                }
                                                else{
                                                        ajaxData.linkedin_id = 'N';
                                                        ajaxData.linkedin_image = "";
                                                        ajaxData.linkedin_status = "Inactive";
                                                }
                                                if(provider == 'Facebook'){
                                                        ajaxData.facebook_id = client_id;
                                                        ajaxData.profile_image = profile_image;
                                                        ajaxData.facebook = 'Y';
                                                        ajaxData.facebook_status = "Linked";
                                                }
                                                else{
                                                        ajaxData.facebook_id = 'N';
                                                        ajaxData.facebook = 'N';
                                                        ajaxData.profile_image = "";
                                                        ajaxData.facebook_status = "Inactive";
                                                }
                                                
						jQuery.ajax({
							url: API + 'signup_process',
							data:ajaxData,
							type: 'POST',
							dataType: 'JSON',
							success: function(data){
								if(data.code==0){
									//jQuery.alerts.dialogClass = 'alert-inverse';
									jAlert('Congratulations! You have registered successfully.\n A confirmation has been sent to your email address.\n Please confirm registration.', 'Sign-up', function(){
									//	jQuery.alerts.dialogClass = null; // reset to default
										jQuery.fn.colorbox.close();
									});
								}
								jQuery.unblockUI();
							}
						});
					}

				});

				jQuery('#signup_close').live('click', function(event){
					event.preventDefault();
					jQuery.fn.colorbox.close();
				});
/*
				jQuery('#facebook_signup').click(function(){
					var url = "https://www.facebook.com/dialog/oauth?client_id=" + facebook_app_id + "&redirect_uri=" + encodeURIComponent (redirectUrl) + "&scope=email&display=popup&state="+state;
					window.open(url,
			'openid_login','width=400,height=200');
				});

				jQuery("#linkedin_signup").click(function () {
				  IN.UI.Authorize().params({"scope":["r_fullprofile", "r_emailaddress"]}).place();
				  IN.Event.on(IN, "auth", onAuthComplateLinkedin);
				});

				jQuery('#google_signup').click(function(){
					var url = 'https://accounts.google.com/o/oauth2/auth?scope=https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile& state=/profile&redirect_uri='+base_url+'signup_google.php&response_type=token&client_id='+google_api_client_id;
					window.open(url,'openid_login','width=600,height=200');
				});
*/
			}
		});
	});

	jQuery("#forgot_password_btn").click(function(event,obj){
		jQuery.colorbox({
			href: 'forgot-password-form.php',
			title: '<h4 class="widgettitle title-primary">Forgot Password</h4>',
			width: '370px',
			height: '450px',
			initialWidth:'200px',
			initialHeight:'100px',
			onComplete:function(){
				
				jQuery("#forgot_password_form").validate({
					rules: {
						forgot_password_email: {
                                                    required: true,
                                                    email: true
						}
                                        },
					highlight: function(label) {
						jQuery(label).closest('.control-group').addClass('error');
					},
					
					errorElement : 'span'
				});
				
				jQuery('#forgot_password_submit').click(function(event){
					event.preventDefault();
					if(jQuery("#forgot_password_form").valid()){
						var email = jQuery('#forgot_password_email').val();
						jQuery.blockUI({ message: null }); 
						
						jQuery.ajax({
							url: API + 'forgot-password.php',
							data:{email:email},
							type: 'POST',
							dataType: 'JSON',
							success: function(data){
                                                           
								if(data==1){
									
									jAlert('Check your email for instructions on how to reset your password.', 'Forgot Password', function(){
										jQuery.fn.colorbox.close();
									});
								}else{
                                                                    jAlert('The email you entered is invalid!', 'Forgot Password', function(){
										jQuery.fn.colorbox.close();
									});
                                                                }
								jQuery.unblockUI();
							}
						});
					}

				});


				jQuery('#forgot_password_close').live('click', function(event){
					event.preventDefault();
					jQuery.fn.colorbox.close();
				});

			}
		});
	});


	//------------------------Facebook----------------------------
/*	
	var redirectUrl = base_url+'signup_facebook.php';
	var state='123456789';
	window.fbAsyncInit = function(){
		FB.init({
			appId : facebook_app_id,
			status : true,
			cookie : true,
			oauth : true
		});
		
	};

	(function(d)
	{
		var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
		js = d.createElement('script'); js.id = id; js.async = true;
		js.src = "//connect.facebook.net/en_US/all.js";
		d.getElementsByTagName('head')[0].appendChild(js);
	}(document));

	//------------------------Facebook End---------------------------------
*/	
});
function checkEmailAvailable(email){
	jQuery.ajax({
		url: API + 'check_availability',
		async : false,
		data:{
			email: email
		},
		type: 'POST',
		success: function(data){
			return data;
		}
	});
}
//---------------------------------------------
function onLoginComplete(){
	FB.getLoginStatus (onAuthComplateFacebook);
}

function onAuthComplateFacebook(response)
{
	if(response.status == "connected"){
		FB.api('/me', function (response) {

			jQuery.ajax({
				url: API + 'check_availability',
				data:{
					email: response.email
				},
				type: 'POST',
				success: function(can_use){
					if(can_use=='true'){
						createAccount({facebook_id:response.email, email:response.email, name:response.name, name_first:response.first_name, name_last:response.last_name, status:'A'});
					}
					else {
						jAlert('Someone already has that email, Try another.', 'Sign-up', function(){
							jQuery.alerts.dialogClass = null;
						})
					}
				}
			});
		});
	}	
}
//---------------------------------------
function onAuthComplateGoogle(queryString){
	jQuery.ajax({
		url: 'ajax/google-getclientinfo.php',
		data:{
			query_string: queryString
		},
		type: 'POST',
		dataType: 'JSON',
		success: function(data){
			jQuery.ajax({
				url: API + 'check_availability',
				data:{
					email: data.email
				},
				type: 'POST',
				success: function(can_use){
					if(can_use=='true'){
						createAccount({google_id:data.email, email:data.email, name:data.name, name_first:data.given_name, name_last:data.family_name, status:'A'});
					}
					else {
						jAlert('Someone already has that email, Try another.', 'Sign-up', function(){
							jQuery.alerts.dialogClass = null;
						})
					}
				}
			});
		}
	});
}
//-----------------
function onAuthComplateLinkedin() {
  IN.API.Profile("me")
	.fields([ "id","firstName", "lastName", "emailAddress"])
	.result( function(me) {
		
		var id = me.values[0].id;
		var fn = me.values[0].firstName;
		var ln = me.values[0].lastName;
		var ea = me.values[0].emailAddress;

		jQuery.ajax({
			url: API + 'check_availability',
			data:{
				email: ea
			},
			type: 'POST',
			success: function(data){
				if(data=='true'){
					createAccount({linkedin_id:ea, email:ea, name:fn+ln, name_first:fn, name_last:ln, status:'A'});
				}
				else {
					jAlert('Someone already has that email, Try another.', 'Sign-up', function(){
						jQuery.alerts.dialogClass = null;
					});
				}
			}
		});
		
		//jQuery("#signu_up_btn").trigger('click',[{vendor:'linkedin',id:id,first_name:fn,last_name:ln}]);
    });
}