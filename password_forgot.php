<?php
/*
*  @modified Ionut Irofte - juniorionut @ elance
*  @version $Id: password_change.p ,v 1.0 5:37 PM 7/16/2014 juni $
*  -> [req 1.38  - 16.07.2014]
		-> BP - Sign Security
*/ 
//include_once 'includes/session.php';        
include_once 'config/accessConfig.php';

session_name("VENDOR");
session_start();
//print_r($_SESSION);
$dbname                         = "";
$_SESSION["COPYRIGHT"]          = "";
$_SESSION["PANELLOGO"]          = "";
$_SESSION["SITENAME"]           = "";
$_SESSION["SITE_URL"]           = "";
$_SESSION["DESIGNEDBY_NAME"]    = "";
$_SESSION["DESIGNEDBY_URL"]     = "";
$_SESSION["SITE_TITLE"]         = "";

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
	//print_r($_SESSION);
	if($_POST['password'] != "")
	{

		$update = mysql_query("update employees_master set password = '".$_POST['password']."'  where empmaster_id = ".$_SESSION['empmaster_id']."");
                
                $created_on = date("Y-m-d H:i:s");
                
		$sql = 
                        "insert into employee_master_ping set 
                                empmaster_id =".$_SESSION['empmaster_id']." ,
                                ip = '".$_SERVER['REMOTE_HOST']."' ,
                                Ping_type = 'Signin' ,
                                datetime = '".date("Y-m-d H:i:s")."',
                                longitude = '',
                                latitude = '',
                                created_on ='TeamPanel' ,
                                created_by =".$_SESSION['empmaster_id'].",
                                created_datetime = '".date("Y-m-d H:i:s")."'
					
			";
			$res = mysql_query($sql) or die(mysql_error());		
                
                
                $_POST["username"]= $_SESSION['client_email'];
                $_POST["password"]= $_POST['password'];
                
		 $ch = curl_init();
             
                $url = API.'panels/vendorpanel/signin.php';
    
                
                curl_setopt($ch,CURLOPT_URL, $url);
                curl_setopt($ch,CURLOPT_POST, count($_POST));
                curl_setopt($ch,CURLOPT_POSTFIELDS, $_POST);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                $result = json_decode(curl_exec($ch),true);
                curl_close($ch);
                //var_dump($result);
                $_SESSION += $result["allsession"];
                //var_dump($_SESSION);
                if ($result["success"]){
                   header("location: dashboard.php");
                    exit;
                }
                  
                
        }

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $_SESSION["SITE_TITLE"];?></title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/index.css" type="text/css" />

<!--<link rel="stylesheet" href="css/style.shinyblue.css" type="text/css" />-->
<!--pep480ur7wpw-->
<!--<script type="text/javascript" src="http://platform.linkedin.com/in.js">
  //api_key: x2ctksmfukgs
  //scope: r_basicprofile r_emailaddress r_contactinfo
</script>-->
<style>
	#pswd_info {
	position:absolute;
	bottom:20px;
	bottom: -115px\9; /* IE Specific */
	width:250px;
	padding:9px;
	background:#fefefe;
	border:1px solid #ddd;
	left:103%;
	width:250px;
}


#pswd_info h4 {
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
	position:absolute;
	top:-1px;
	left:-4%;
	display:block;
}
.invalid {
	background: url(./images/closed_cancelled_terminated_16.png) no-repeat 1% 22%;
	padding-left:22px;
	line-height:24px;
	color:#ec3f41;
	font-size:12px;
	list-style:none;
}
.valid {
	background: url(./images/active_16.png) no-repeat 1% 22%;
	padding-left:22px;
	line-height:24px;
	color:#3a7d34;
	font-size:12px;
	list-style:none;
}
#pswd_info {
	display:none;
}
</style>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>

<script type="text/javascript">
jQuery(document).ready(function() {
	 jQuery("#password").blur(function () {
 		var pass = jQuery("#password").val();
		jQuery.ajax({
			url: 'check_password.php',
			data: {value: password.value},
			type: 'POST',
			dataType: 'json',
			success:function(data){	
			
				if(data.success == '1'){				
					jQuery("#last_five").val("1");
				}
				if(data.success == '0')
				{
					jQuery("#last_five").val("");
				}
				
			}
			
		});
				
 	});

	jQuery('#password').focus(function () {
		jQuery('#pswd_info').show();
	});
	jQuery('#password').blur(function () {
		jQuery('#pswd_info').hide();
	});
	
	jQuery('#password').keyup(function ()
	{
		
		setTimeout(function () {		
		var pswd = jQuery('#password').val();
		var u_nm = jQuery("#user_name").val()
		if(pswd.match(u_nm)){
			jQuery('#u_name').removeClass('valid').addClass('invalid');
		}
		else
		{
			jQuery('#u_name').removeClass('invalid').addClass('valid');
		}
		
		if(pswd == "")
		{
			jQuery('#u_name').removeClass('valid').addClass('invalid');
		}
		
		if ( pswd.length < 6 ) {
			jQuery('#length').removeClass('valid').addClass('invalid');
		} 
		else
		{
			jQuery('#length').removeClass('invalid').addClass('valid');
		}
						
		//validate letter
		if ( pswd.match(/[A-z]/) ) {
			jQuery('#letter').removeClass('invalid').addClass('valid');
		} else {
			jQuery('#letter').removeClass('valid').addClass('invalid');
		}
		
		//validate capital letter
		if ( pswd.match(/[A-Z]/) || pswd.match(/[\@#\$\%\^\&*()_+!]/) ) {
			jQuery('#capital').removeClass('invalid').addClass('valid');
		} else {
			jQuery('#capital').removeClass('valid').addClass('invalid');
		}
		
		//validate number
		if ( pswd.match(/\d/) ) {
			jQuery('#number').removeClass('invalid').addClass('valid');
		} else {
			jQuery('#number').removeClass('valid').addClass('invalid');
		}	
		
		},500);
		
	});
});
function check_pwd ()
{
	var ln = jQuery('.valid').length;
	if(ln < 4)
	{
		jAlert("Please enter new Password that meets all requirements!",'Alert Dialog');
		return false;
	}
	else if(jQuery('#password').val() != jQuery('#re_password').val())
	{
		jAlert("Passwords do not match",'Alert Dialog');
		return false;
	}
	else if (jQuery("#last_five").val() == "1")
	{
		jAlert("You can not use your old password!","Alert Dialog");	
		return false;
	}
	else
	{
		jQuery("#password_change").submit();
	}
}

</script>
<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.blockUI.js"></script>

<script type="text/javascript" src="js/index.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

</head>
<body class="loginpage">

<div class="loginpanel">
    <div class="loginpanelinner">
        <div class="logo animate0 bounceIn" style="margin-left: 0px;text-align: center;padding: 20px 0;"><img src="<?php echo $_SESSION["PANELLOGO"];?>" alt="" style="vertical-align: baseline;"><br>
            <span id="clientSpan" style="color: rgba(255, 255, 255, 0.5);font-family: arial,sans-serif !important;font-size: 13px;float: right;line-height: 10px;margin-right: 20px;">VENDOR</span></div>
 <?php
 
 if (isset($_REQUEST["token"]) && $_REQUEST["token"]!=""){
    $token = $_REQUEST["token"];
    
$sql = "SELECT empmaster_id, email FROM employees_master WHERE  md5(empmaster_id)='$token' ";
   
$result = mysql_query($sql);
//    $row = mysql_fetch_assoc($result);
    if (mysql_num_rows($result) != 0) {
        $row = mysql_fetch_assoc($result);
        $_SESSION['empmaster_id']=$row["empmaster_id"];
        $_SESSION['client_email']=$row["email"];
 
        $flag = 1;
    } else {
        $flag = 0;
    }
    
    if ($flag == 0){
        echo "<script>jAlert('Please use the right url sent to your email!');</script>"; 
    }else{
        
    
 
 ?>       
	<form id="password_change" name="password_change" method="post" onSubmit="return check_pwd();" >
             <div class="alert alert-error" id="loginerror">Please Reset Your Password</div>
             <input type="hidden" name="last_five" id="last_five" value="">
              <input type="hidden" name="user_false" id="user_false" value="">
              <input type="hidden" name="user_name" id="user_name" value="<?=($_SESSION['client_email'] == "")? "nothing" : $_SESSION['client_email']?>">
			<div class="inputwrapper animate1 bounceIn" style="animation-fill-mode:none !important;">
                <input type="password" name="password" id="password"  class=""  placeholder="Enter Password" style="margin-bottom: 10px;" value="" />
            </div>
			<div class="inputwrapper animate1 bounceIn">
                <input type="password" name="re_password" id="re_password" placeholder="Confirm Password" style="margin-bottom: 10px;" value=""/>
            </div>
            <div class="inputwrapper animate3 bounceIn">
                <button name="submit">Submit</button>
            </div>
            
        </form>
        <div id="pswd_info">
            
            <ul>
                <li id="length" class="invalid">At least <strong>6 character</strong></li>
                <li id="u_name" class="invalid"><strong>Password can not contain username</strong></li>
                <li id="number" class="invalid">At least <strong>one alpha numeric character</strong></li>
                <li id="capital" class="invalid">At least <strong>One Special character or  1 Upper case character</strong></li>
            </ul>
		</div>
 <?php 
 }
 
 }?>       
    </div><!--loginpanelinner-->
</div>
<!--loginpanel-->
<!--loginpanel-->

<div class="loginfooter">
    <p style="text-align: center;"><?php echo str_replace($_SESSION["SITENAME"],'<a href="'.$_SESSION["SITE_URL"].'" target="_blank" style="color: white;">'.$_SESSION["SITENAME"].'</a>',$_SESSION["COPYRIGHT"]);?></p>
    <p style="float:right;display:inline-block;padding-right: 15px"><?php echo $dbname;?></p>
</div>
<div id="content"></div>

</body>
</html>