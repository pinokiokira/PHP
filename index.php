<?php require_once('require/openid-config.php');
include_once("config/accessConfig.php");
session_name("VENDOR");
session_start();
if($_GET['lang']=="" ){
$_SESSION['lang'] =  'en';
}

$dbname = "";

$sql = "SELECT * FROM preferences";
$result = mysql_query($sql);
while ($row = mysql_fetch_assoc($result)){
    switch ($row["key"]){
        case "DB":
            $dbname = $row["value"];
            break;
        case "COPYRIGHT":
            $_SESSION["COPYRIGHT"] = $row["value"];
            break;
        
        case "PANEL LOGO":
            $_SESSION["PANELLOGO"] =$row["value"];
            break;
        
        case "WEBSITE_NAME":
            $_SESSION["SITENAME"] = $row["value"];
            break;
        
        case "WEBSITE_URL":
            $_SESSION["SITE_URL"] = $row["value"];
            break;
        
        case "DESIGNEDBY_NAME":
            $_SESSION["DESIGNEDBY_NAME"] = $row["value"];
            break;
        
        case "DESIGNEDBY_URL":
            $_SESSION["DESIGNEDBY_URL"] = $row["value"];
            break;
        
        case "VENDOR_TITLE":
            $_SESSION["SITE_TITLE"] = $row["value"];
            break;
    }
}


$username='';

if (isset($_COOKIE['usernameTP'])){
    $username = $_COOKIE['usernameTP'];
}
if($_REQUEST['id'] != "")
{
	mysql_query("update employees_master set status = 'A' where empmaster_id =".$_REQUEST['id']);
}
include('language_info.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/index.css" type="text/css" />
<!--<link rel="stylesheet" href="css/style.shinyblue.css" type="text/css" />-->
<!--pep480ur7wpw
<script type="text/javascript" src="http://platform.linkedin.com/in.js">
  api_key: x2ctksmfukgs
  scope: r_basicprofile r_emailaddress r_contactinfo
</script>-->
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script>
var APIdomain = '<?php echo API;?>';
</script>
<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.blockUI.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/token.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    
window.onload=function(){
if(navigator.geolocation)
{
navigator.geolocation.getCurrentPosition(showPosition);
}
}
function showPosition(pos){
    jQuery("#latitude").val(pos.coords.latitude);
    jQuery("#longitude").val(pos.coords.longitude);
}    
    
var google_api_client_id = '<?php echo GOOGLE_API_CLIENT_ID;?>';
var facebook_app_id = '<?php echo FACEBOOK_APP_ID;?>';
var base_url = '<?php echo BASE_URL; ?>';
</script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        
         <?php if (MAINTENANCE_MODE==true){ ?>
               //jAlert('<?php echo MAINTENANCE_MESSAGE;?>');
               <?php if (LOGIN_ACCESSIBLE==false){?>
                jQuery("button[name=submit], #medialist").hide(); 
                jQuery('#login').bind("keyup keypress", function(e) {
                    var code = e.keyCode || e.which; 
                    if (code  == 13) {               
                      e.preventDefault();
                      //jAlert('<?php echo MAINTENANCE_MESSAGE;?>');
                      return false;
                    }
                  });
        <?php }
        
        } ?>  
        
        <?php if ($username==""){?>
        jQuery("#username").val(""); 
        <?php } ?>
       jQuery("#password").val("");       
    });
    
</script>
<script type="text/javascript" src="js/index.js"></script>
<style>
/**juni -> 16.07.2014 -> chrome bug, need it here */	
#pswd_info 
{
	display:none;
}
</style>
</head>
<body class="loginpage" style="overflow:hidden;">

<div class="loginpanel">
    <div class="loginpanelinner" >
			<div class="inputwrapper animate_infinite bounceIn" ></div><!--juni -> 2014-10-17 - bug in chrome -> fields remain hidden after first transition - keep an animation with count infinite -->
        <div class="logo animate0 bounceIn" style="margin-left: 0px;text-align: center;padding: 20px 0;"><img src="<?php echo $_SESSION["PANELLOGO"];?>" alt=""><br>
            <span id="clientSpan" style="color: rgba(255, 255, 255, 0.5);font-family: arial,sans-serif !important;font-size: 13px;float: right;line-height: 20px;margin-right: 20px;"><?//=//strtoupper($_SESSION["Team"])?> VENDOR </span></div>
        
		<form id="login">
            <div class="inputwrapper login-alert">
                <div class="alert alert-error">Invalid email or password</div>
            </div>
            <div class="inputwrapper capslock-alert" style="display: none;">
                <div class="alert alert-error" id="capslockerror">Caps Lock is on.</div>
            </div>
            <div class="inputwrapper animate1 bounceIn">
              <?php /*?>  <input type="text" name="username" id="username" placeholder="<?=$_SESSION["Enter Email"].' '.$_SESSION["or"].' '.$_SESSION["ID"];?>" value="<?php echo $username;?>"/><?php */?>
			    <input type="text" name="username" id="username" placeholder="<?=$_SESSION["Enter Email"];?>" value="<?php echo $username;?>"/>
            </div>
            <div class="inputwrapper animate2 bounceIn">
                <input type="password" name="password" id="password" placeholder="<?=$_SESSION["Enter Password"]?>" style="margin-bottom: 10px;width: 215px;margin-right: -3px;box-shadow: none;" />
				<button type="button" id="show_password" class="btn" style="display: inline-block; background-color: white; width: auto;margin-bottom: 10px;box-shadow: none;border-color: white;padding: 9.2px; height:40px;"><span class="icon icon-eye-open"></span></button>
            </div>
			
				<div>
				<input type="hidden" name="language" id="lang_info" value="<?php if(isset($_GET['lang'])){echo $_GET['lang'];}else{ echo 'en';}?>">
				<select style="height:35px; padding-top: 9px;" class="input-xlarge" id="select_language">
					<option value="en"  <?php if( $_GET['lang']=='en'){echo "selected";} ?>>English</option>
					<option value="ar" <?php if( $_GET['lang']=='ar'){echo "selected";} ?> >Arabic</option>
					<option value="zh-CN" <?php if( $_GET['lang']=='zh-CN'){echo "selected";} ?> >Chinese</option>
					<option value="nl" <?php if( $_GET['lang']=='nl'){echo "selected";} ?> >Dutch</option>	
					<option value="fi" <?php if( $_GET['lang']=='fi'){echo "selected";} ?> >Finnish</option>
					<option value="fr" <?php if( $_GET['lang']=='fr'){echo "selected";} ?>>French</option>					
					<option value="de" <?php if( $_GET['lang']=='de'){echo "selected";} ?> >German</option>
					<option value="el" <?php if( $_GET['lang']=='el'){echo "selected";} ?> >Greek</option>
					<option value="hi" <?php if( $_GET['lang']=='hi'){echo "selected";} ?> >Hindi</option>
					<option value="it" <?php if( $_GET['lang']=='it'){echo "selected";} ?> >Italian</option>
					<option value="ja" <?php if( $_GET['lang']=='ja'){echo "selected";} ?> >Japanese</option>
					<option value="ko" <?php if( $_GET['lang']=='ko'){echo "selected";} ?> >Korean</option>
					<option value="no" <?php if( $_GET['lang']=='no'){echo "selected";} ?> >Norwegian</option>					
					<option value="pt" <?php if( $_GET['lang']=='pt'){echo "selected";} ?> >Portuguese</option>					
					<option value="ru" <?php if( $_GET['lang']=='ru'){echo "selected";} ?> >Russian</option>
					<option value="es" <?php if( $_GET['lang']=='es'){echo "selected";}?> >Spanish</option>
				
					<option value="sv" <?php if( $_GET['lang']=='sv'){echo "selected";} ?> >Swedish</option>
					
				</select>
				
				
			</div>
			
            <div class="inputwrapper animate3 bounceIn">
                <button name="submit"><?=$_SESSION["Log"]?> <?=$_SESSION["In"]?></button>
            </div>
            <div class="inputwrapper animate4 bounceIn">
                <label><input type="checkbox" class="remember" id="rememberme" name="rememberme" value="rememberme" <?php if (isset($_COOKIE['usernameTP']) && $_COOKIE['usernameTP']!="") echo "checked";?>/><?=$_SESSION["Keep Me Logged In"]?></label>
            </div>
                    <input type="hidden" name="latitude" id="latitude" />
                    <input type="hidden" name="longitude" id="longitude" />
        </form>
		<div class="inputwrapper animate5 bounceIn" id="medialist" style="margin-top:15px;">
			<button href="ajax/signup-form.php" id="signu_up_btn"><?=$_SESSION["Sign"]?> <?=$_SESSION["Up"]?></button>
		</div>
                <div class="inputwrapper animate5 bounceIn" id="medialist" style="margin-top:15px;">
			<button href="forgot-password-form.php" id="forgot_password_btn"><?=$_SESSION['Forgot Password'];?></button>
		</div>
    </div><!--loginpanelinner-->
</div><!--loginpanel-->
<div id="pswd_info">   <!-- juni - 06.07.2014 -> Add password requirements pop'up - 16.07.2014 -> chrome bug, need it here-->     
	<ul>
		<li id="length" class="invalid">At least <strong>6 character</strong></li>
		<li id="u_name" class="invalid"><strong>Password can not contain email</strong></li>
		<li id="number" class="invalid">At least <strong>one alpha numeric chratcter</strong></li>
		<li id="capital" class="invalid">At least <strong>One Special character or  1 Upper case character</strong></li>
	</ul>
</div>	
<!--
<div class="loginpanel">
	<div class="logo animate0 bounceIn loginpanelinner" style="width:520px;"><img src="images/logo.png" alt="" /></div>

    <div class="loginpanelinner">
        <div style="float:left;">
			<form id="login">
				<div class="inputwrapper login-alert">
					<div class="alert alert-error">Invalid username or password</div>
				</div>
				<div class="inputwrapper animate1 bounceIn">
					<input type="text" name="username" id="username" placeholder="Enter Email" />
				</div>
				<div class="inputwrapper animate2 bounceIn">
					<input type="password" name="password" id="password" placeholder="Enter Password" />
				</div>
				<div class="inputwrapper animate3 bounceIn">
					<button name="submit">Log In</button>
				</div>
				<div class="inputwrapper animate4 bounceIn">
					<label><input type="checkbox" class="remember" name="signin" />Keep Me Logged In</label>
				</div>
			</form>
		</div>

		<div style="float:left; margin-left: 50px;">
			<div style="position:absolute; margin-top:30%;">
				<div style="position:relative; margin-top:-90%;" id="signupbtnbox">
					<div class="inputwrapper animate3 bounceIn" id="medialist">
						<button href="ajax/signup-form.php" id="signu_up_btn" style="width:200px;">Sign Up</button>
					</div>
					<p style="text-align:center;margin-top:10px;">
						<a class='openid_btn' id="linkedin_signup">Signup with Linkedin</a>
					</p>
					<p style="text-align:center;margin-top:10px;">
						<a class='openid_btn facebook_button' id="facebook_signup">Signup with Facebook</a>
					</p>
					<p style="text-align:center;margin-top:10px;">
						<a class='openid_btn google_button' id="google_signup">Signup with Google</a>
					</p>
				</div>
			</div>
		</div>
    </div>
</div>--><!--loginpanel-->

<div class="loginfooter">
    <p style="display: block;text-align: center;"><?php echo str_replace($_SESSION["SITENAME"],'<a href="'.$_SESSION["SITE_URL"].'" target="_blank" style="color: white;">'.$_SESSION["SITENAME"].'</a>',$_SESSION["COPYRIGHT"]);?></p>
    <p style="padding-right: 15px"><?php echo $dbname;?></p>
</div>
<div id="content"></div>


</body>
</html>

<?php 

if (isset($_REQUEST["token"]) && $_REQUEST["token"]!=""){
    $token = $_REQUEST["token"];
$sql = "SELECT empmaster_id FROM employees_master WHERE  md5(empmaster_id)='$token' ";
   
$result = mysql_query($sql);
//    $row = mysql_fetch_assoc($result);
    if (mysql_num_rows($result) != 0) {
    $sql = "update employees_master set status='A' where md5(empmaster_id)='$token'";
        mysql_query($sql);
        $flag = 1;
        
    } else {
        $flag = 0;
    }
    
    if ($flag == 1){
       // mysql_query("UPDATE employee_master SET status='A' where md5(empmaster_id)='$token'");
        echo "<script>jAlert('Your Account has been activated successfully, please login to your account!');</script>";
    }
    else{
        echo "<script>jAlert('Please use the right url sent to your email!');</script>"; 
    }
}   
?>
<script type="text/javascript">

    jQuery(document).ready(function() {
			jQuery("#select_language").change(function(){			
			var lang = jQuery(this).val();
			document.location.href="index.php?lang="+lang;
	})
			
			
			// START CODE TO CHECK CAPSLOCK IS ON OR OFF
			var isShiftPressed = false;
			var isCapsOn = null;
			jQuery("#password").bind("keydown", function (e) {
				var keyCode = e.keyCode ? e.keyCode : e.which;
				if (keyCode == 16) {
					isShiftPressed = true;
				}
			});
			jQuery("#password").bind("keyup", function (e) {
				var keyCode = e.keyCode ? e.keyCode : e.which;
				if (keyCode == 16) {
					isShiftPressed = false;
				}
				if (keyCode == 20) {
					if (isCapsOn == true) {
						isCapsOn = false;
						jQuery(".capslock-alert").hide();
					} else if (isCapsOn == false) {
						isCapsOn = true;
						jQuery(".capslock-alert").show();
					}
				}
			});
			jQuery("#password").bind("keypress", function (e) {
				var keyCode = e.keyCode ? e.keyCode : e.which;
				if (keyCode >= 65 && keyCode <= 90 && !isShiftPressed) {
					isCapsOn = true;
					jQuery(".capslock-alert").show();
				} else {
					jQuery(".capslock-alert").hide();
				}
			});
			// END CODE TO CHECK CAPSLOCK IS ON OR OFF
			setTimeout(function(){
				jQuery("#username").focus();
			},1000);
			
			jQuery("#show_password").click(function(){
				if (jQuery('#password').attr('type') == 'password') {
					jQuery('#password').attr('type', 'text');
					jQuery('#show_password span.icon').removeClass('icon-eye-open');
					jQuery('#show_password span.icon').addClass('icon-eye-close');
				} else {
					jQuery('#password').attr('type', 'password');
					jQuery('#show_password span.icon').removeClass('icon-eye-close');
					jQuery('#show_password span.icon').addClass('icon-eye-open');
				}
			});
	});
    
	
	
	setTimeout(
    function() {
		jQuery("#username").val("");
		jQuery("#password").val("");
	 },
    1000  //1,000 milliseconds = 1 second
);
</script>