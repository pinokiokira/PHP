<?php
/*
*  @modified Ionut Irofte - juniorionut @ elance
*  @version $Id: signup-form.php ,v 1.0 10:38 PM 7/4/2014 juni $
*  -> [req 1.34  - 06.07.2014]
		-> Code Indentation
		-> Make changes as requested
		-> Add password requirements pop'up
*/
?>
<script type="text/javascript" src="includes/fb.js" /></script>
<style>
  .error{
        margin-left: 10px;
        color: red;
    }
	#pswd_info 
	{
		position:fixed;
		bottom: -115px\9; /* IE Specific */
		padding:9px;
		background:#fefefe;
		border:1px solid #ddd;
		left:60.5%;
		top:56%;
		width:298px;
		z-index:10000;
	}
	
	#pswd_info h4 
	{
		margin:0 0 10px 0;
		padding:0;
		font-weight:normal;
	}
	#pswd_info::before {
		width: 0; 
		height: 0; 
		border-top: 10px solid transparent;
		border-bottom: 10px solid transparent; 	
		border-right:10px solid #FFFFFF;
		content: "";
		/* position:absolute; */
		top:-1px;
		left:0%;
		display:block;
	}
	
	#pswd_info .invalid 
	{
		background: url(./images/closed_cancelled_terminated_16.png) no-repeat 1% 22%;
		padding-left:22px;
		line-height:24px;
		color:#ec3f41;
		font-size:12px;
		list-style:none;
	}
	
	#pswd_info .valid 
	{
		background: url(./images/active_16.png) no-repeat 1% 22%;
		padding-left:22px;
		line-height:24px;
		color:#3a7d34;
		font-size:12px;
		list-style:none;
	}
	
	#pswd_info 
	{
		display:none;
	}
</style>
<!--<script type="text/javascript">
	FB.logout();
</script>-->
<script type="text/javascript">
	function open_fb()
	{ 
		window.open ("includes/login.php?provider=Facebook", "Facebook","location=1,status=1,scrollbars=1,width=500,height=500");
	}
	function open_gp()
	{		
		window.open ("includes/login.php?provider=Google", "Google","location=1,status=1,scrollbars=1,width=500,height=500");
	}
	function open_linked(){		
		window.open("includes/login.php?provider=LinkedIn", "Linked In","location=1,status=1,scrollbars=1,width=650,height=250");
	}
	function updateValue(id,valueS){	
		document.getElementById(id).value = valueS;
	}
	function validateForm(){
		setTimeout(function(){jQuery('#signup_form').submit()},2000);
	}
</script>
<script>
jQuery(document).ready(function(){
    jQuery("#signup_email").val("");
    jQuery("#signup_password").val("");
})
</script>
<form id="signup_form" name="signup_form">
	<div class="mediaWrapper row-fluid" style="width:318px; height:350px; min-height:100px;">
		<div >
			<p id="signupbtnbox" style="margin-top:10px;">
				<a onClick="open_linked()" class='openid_btn linkedin_button' id="linkedin_signup">Linkedin</a>
				<!--<a class='openid_btn facebook_button' id="facebook_signup">Facebook</a>-->
				<a class='openid_btn facebook_button' onClick="Login();" >Facebook</a>
				<!--<a class='openid_btn facebook_button' href="fb.php" >Facebook</a>-->
				<a class='openid_btn google_button' onClick="open_gp();" style="margin-right:0px;">Google</a>
			</p>

			<div class="orSeparator"></div>
			<p>
				<label>First Name:</label>
				<input type="text" name="signup_first_name" id="signup_first_name" class="input-block-level" value="" style="margin-bottom:0px;"/>
			</p>
			<p>
				<label>Last Name:</label>
				<input type="text" name="signup_last_name" id="signup_last_name" class="input-block-level" value="" style="margin-bottom:0px;"/>
			</p>
			<p>
				<label>Email:</label>
				<input type="text" name="signup_email" id="signup_email" class="input-block-level" value="" style="margin-bottom:0px;"/>
			</p>
			<p style="margin-bottom:0px;">
				<label>Password:</label>
				<input type="password" name="signup_password" id="signup_password" class="input-block-level" style="margin-bottom:0px;"/>
				<!--<input type="text" name="password_text" id="signup_password_text" class="input-block-level" style="margin-bottom:0px;display:none;">
				<br>
				<div style="float:right;">
					<input type="checkbox" id="show_password"/> <span style="font-size:12px; font-weight: bold; color:#666666;">Show Password</span>
				</div>-->		
			</p>
			<p>
				<div class="inputwrapper animate5 bounceIn" id="medialist" style="margin-top:15px;">
					<button href="ajax/signup-form.php" id="signup_submit" style="padding:5px;">Sign Up</button>
				</div>
				<!--<button class="btn btn-primary" id="signup_submit"></span> Submit</button>
				<button class="btn" id="signup_close">Close</button>-->
			</p>
			<p style="text-align:center;">
				<span style="font-size:11px;"><i>By clicking Sign Up you agree to our T&C's.</i></span>
			</p>
			<hr style="border:1px solid #cccccc; margin:0px;">
			<p style="text-align:center;">
				Already have an account? <a href="javascript:void(0);" id="signup_close">Log In</a>
			</p>
		</div><!--span3-->
	</div><!--imageWrapper-->
         <input type="hidden" name="client_id" id="client_id" />
	<input type="hidden" name="name_title" id="name_title" />
	<input type="hidden" name="phone" id="phone"/>
	<input type="hidden" name="address" id="address"/>
	<input type="hidden" name="country" id="country"/>
	<input type="hidden" name="state" id="state"/>
	<input type="hidden" name="city" id="city"/>
	<input type="hidden" name="zip" id="zip"/>	
	<input type="hidden" name="gender" id="gender"/>
	<input type="hidden" name="dob" id="dob"/>
	<input type="hidden" name="profile_image" id="profile_image"/>
	<input type="hidden" name="last_datetime" id="last_datetime" value="<?=date('Y-m-d H:i:s')?>"/>
	<input type="hidden" name="Created_datetime" id="Created_datetime" value="<?=date('Y-m-d H:i:s')?>"/>
	<input type="hidden" name="provider" id="provider"/>	
	<input type="hidden" name="access_token" id="access_token"/>
</form>
<script>  
// <!-- juni - 06.07.2014 -> Add password requirements pop'up-->     
jQuery('#signup_password').keyup(function () {
	var pswd = jQuery('#signup_password').val();
	if(pswd.match(jQuery("#signup_email").val())){
		jQuery('#u_name').removeClass('valid').addClass('invalid');
	} else	{
		jQuery('#u_name').removeClass('valid').addClass('valid');
	} if(pswd == "" ){
		jQuery('#u_name').removeClass('valid').addClass('invalid');
	}
	if ( pswd.length < 6 ) {
		jQuery('#length').removeClass('valid').addClass('invalid');
	} else {
		jQuery('#length').removeClass('invalid').addClass('valid');
	}
	//validate letter
	if ( pswd.match(/[A-z]/)) {	
		jQuery('#letter').removeClass('invalid').addClass('valid');
	} else {
		jQuery('#letter').removeClass('valid').addClass('invalid');
	}
	if (pswd.match(/[0-9]/)) {
		jQuery('#number').removeClass('invalid').addClass('valid');
	} else {
		jQuery('#number').removeClass('valid').addClass('invalid');
	}
	//validate capital letter	
	if (pswd.match(/[A-Z]/) || pswd.match(/[\@#\$\%\^\&*()_+!]/) ) {
		jQuery('#capital').removeClass('invalid').addClass('valid');
	} else {
		jQuery('#capital').removeClass('valid').addClass('invalid');
	}
	if ( pswd.match(/\d/) ) {
		jQuery('#number').removeClass('invalid').addClass('valid');
	} else {
		jQuery('#number').removeClass('valid').addClass('invalid');
	}
	//validate number	
});
jQuery('#signup_password').focus(function () {
		//jQuery('#colorbox').css('width','470px');
		jQuery('#pswd_info').show();
		 var offset = jQuery('#signup_password').offset();
		 var width = jQuery('#signup_password').width();
		 var new_left = offset.left+width;
		 var new_left = new_left +40;
		// var new_hieght = offset.top+5;
		 var new_hieght = offset.top;
		jQuery('#pswd_info').offset({ top:new_hieght, left: new_left})
		/* jQuery.colorbox.resize({width: '670px',	height: '650px',}) ; */
		//jQuery.colorbox.resize({height: '770px',width: '370px'}) ;//no need to resize as i have fixed element now
	});
jQuery('#signup_password').blur(function () {
	jQuery('#pswd_info').hide();
	//jQuery.colorbox.resize({width: '370px',	height: '650px',}) ;//no need to resize as i have fixed element now
});
</script>