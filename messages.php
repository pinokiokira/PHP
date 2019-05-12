<?php 
if(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){
	ob_start("ob_gzhandler"); 
}else{ 
	ob_start();
}
require_once 'require/security.php';
include_once 'config/accessConfig.php'; 
@session_start();

$messages = "active";
$client_id = $_SESSION['client_id'];

if ($_POST&&isset($_POST["recipients"])){
	$msgSent = false;
	$messagebody = mysql_real_escape_string($_POST["txtMessage"]);
	$subject = mysql_real_escape_string($_POST["txtSubject"]);
	$priority = mysql_real_escape_string($_POST["txtPriority"]);
        $sql = "SELECT id FROM employees INNER JOIN employees_master ON employees.email = employees_master.email WHERE employees_master.email = '{$_SESSION['email']}' AND location_id='".mysql_real_escape_string($_POST["location_id"])."'";
        $result = mysql_query($sql);
        $getemp = mysql_fetch_assoc($result);
        $employeeid = $getemp["id"];
	    // $recipients[] = $_POST["recipients"];
        //if ($_POST["location_id"]!="All"){
            foreach ($_POST["recipients"] as $selectedOption){
                $sqlthread = "select max(thread_id) as maxthread from employee_messages";
                $resultthread = mysql_query($sqlthread);
                $rowthread = mysql_fetch_assoc($resultthread);
                $maxthread = $rowthread["maxthread"];
                if ($maxthread==""){
                    $maxthread = 1;
                }else $maxthread = intval($maxthread)+1;
                
                if ($_POST["recipients"]!="All"){
                    $newsql = "INSERT INTO employee_messages (location_id,entered_by_emp_id,Subject,message,date,time,emp_id,readd,Message_type,priority,thread_id) 
                    VALUES ('".mysql_real_escape_string($_POST["location_id"])."','{$employeeid}','{$subject}','{$messagebody}',DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(NOW(),'%H:%i:%s'),'{$selectedOption}','no','Location','{$priority}',{$maxthread})";
                    $result2 = mysql_query($newsql) or die(mysql_error());
                    /*if ($result2)
                            $msgSent = true; */

                    /*$managrsquery = "SELECT id FROM EMPLOYEES WHERE location_id = '".mysql_real_escape_string($_POST["location_id"])."' AND manager = 'Yes'";
                    $result_managrsquery = mysql_query($managrsquery);
                    while ($rowwww = mysql_fetch_array($result_managrsquery)) {*/



                        /*print_r($rowwww['id']);
                        echo $rowwww['id'];*/
                        /*$selectedOption = $rowwww['id'];
                        $newsql = "INSERT INTO employee_messages (location_id,entered_by_emp_id,Subject,message,date,time,emp_id,readd,Message_type,priority,thread_id) 
                    VALUES ('".mysql_real_escape_string($_POST["location_id"])."','{$employeeid}','{$subject}','{$messagebody}',DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(NOW(),'%H:%i:%s'),'{$selectedOption}','no','Location','{$priority}',{$maxthread})";
                    $result2 = mysql_query($newsql) or die(mysql_error());
                    }*/

                    $msgSent = true; 
                    
                }else{
                   $sql = "SELECT id FROM employees WHERE location_id=".$_POST["location_id"];
                    $resultemp = mysql_query($sql);
                    while($rowemp = mysql_fetch_assoc($result)){
                        mysql_query("INSERT INTO employee_messages (location_id,entered_by_emp_id,Subject,message,date,time,emp_id,readd,Message_type,priority,thread_id) 
                    VALUES ('".mysql_real_escape_string($_POST["location_id"])."','{$employeeid}','{$subject}','{$messagebody}',DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(NOW(),'%H:%i:%s'),'{$rowemp["id"]}','no','Location','{$priority}',{$maxthread})");
                    }

                    $msgSent = true;  
                }
                     
            }  
        
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<?php 
/*if(!isset($_REQUEST["lang"]) || $_REQUEST["lang"]=="")
{
	header("Location: messages.php?lang=".$_SESSION['lang']."#googtrans(en|".$_SESSION['lang'].")");
	exit;
}*/

include('language_info2.php');
?>

<script>
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en',
    includedLanguages: '<?=$_SESSION['lang']?>'
  }, 'google_translate_element');
}
</script>
<script src=
"http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/jquery.blockUI.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<style>
body {
top:0px!important;
}
.goog-te-banner-frame{  margin-top: -50px!important; }
    #recipient_chzn{
        border-bottom: 1px solid #ddd;
    }
    .graymsg {
        background-color: #ccc !important;
    }
    .msgauthor .authorinfo {
        margin-left: 45px;
        }
    .msgauthor .thumb {
        width: 40px;
        height: 40px;
        float: left;
        margin-top: 0;
        }    
</style>
<script type="text/javascript">
idleTime = 0;
restarted = 0;
jQuery(document).ready(function () {
    //Increment the idle time counter every minute.
    var idleInterval = setInterval(timerIncrement, 1000); 

    //Zero the idle timer on mouse movement.
    jQuery(this).mousemove(function (e) {
        idleTime = 0;
    });
    jQuery(this).keypress(function (e) {
        idleTime = 0;
    });
});

function timerIncrement() {
    idleTime++;
    if (idleTime > 59) { // 1 minute
        if (restarted==0) {
            window.location = window.location.href;
            restarted=1;
        }
    }
}
</script>  
<script type="text/javascript">
	var client_id = <?php echo $client_id;?>;
</script>
<script type="text/javascript">
jQuery(document).ready(function(){
    <?php  if ($msgSent){ ?>
        jQuery(document).ready(function(){
        jAlert("Your message has been sent!");
        }) 
 <?php   } ?>
    jQuery(".chzn-select").chosen();
    //click on "INBOX" tab
	jQuery('.inbox').click(function(){
		jQuery(".active").removeClass("active");
		jQuery(this).addClass("active");
		jQuery("#hdnCurrentTab").val("INBOX");
		jQuery("#frmsearch").submit();
	});
	//click on "SENT" tab
	jQuery('.sent').click(function(){
		jQuery(".active").removeClass("active");
		jQuery(this).addClass("active");
		jQuery("#hdnCurrentTab").val("SENT");
		jQuery("#frmsearch").submit();
	});
	//click on "TRASH" ("DONE") tab
	jQuery('.trash').click(function(){
		jQuery(".active").removeClass("active");
		jQuery(this).addClass("active");
		jQuery("#hdnCurrentTab").val("READ");
		jQuery("#frmsearch").submit();
	});
        
        jQuery('.msglist li').live("click",function(){
        jQuery('.msglist li').each(function(){ jQuery(this).removeClass('graymsg')});
        jQuery(this).addClass('graymsg');
           
        
        
        if (jQuery("#temp_msg").val() != "" && (jQuery("#hdnCurrentTab").val()=="INBOX" || jQuery("#hdnCurrentTab").val()=="")){
            jQuery("#message"+jQuery("#temp_msg").val()).fadeOut(1500);
        }
        jQuery("#temp_msg").val(jQuery(this).data('id')) ;
        var msgid = jQuery(this).data('id') ;
        var msgsubject = jQuery(this).data('subject');
        var msgstatus = jQuery(this).data('status');
        var thread = jQuery(this).data('thread');
        jQuery(this).removeClass('selected').addClass('unread');
        jQuery("#currentmessageid").val(msgid);
        jQuery("#currentmessagesubject").val(msgsubject);
        jQuery.ajax({
            type: "POST",
            url: "messages/ajax_message.php",
            data: {msgid:msgid,subject:msgsubject,hdnCurrentTab:jQuery("#hdnCurrentTab").val(),thread:thread},
            beforeSend:function(){
				jQuery(".messageview").html('<div style="margin-top:50px; "><div class="progress progress-striped active"><div class="bar" style="margin-left:1%; margin-right:1%; width: 98%;"></div></div></div>');
			},
            success: function( data ){
                
                    jQuery('.messageview').html(data);
                    //jQuery('.msgreply').css("display","");
                    if(jQuery("#hdnCurrentTab").val()=="" || jQuery("#hdnCurrentTab").val()=="INBOX" || jQuery("#hdnCurrentTab").val()=="READ")
					{
						jQuery('.messageview').css("height","450px !important");
						jQuery('.msgreply').css("display","");
					}
					else
					{
						jQuery('.messageview').css("height","600px");
					}
					
					if(jQuery("#hdnCurrentTab").val()=="SENT")
					{
						jQuery('#mark').css("display","none");
					}
                    jQuery("#newreply").val("");
                    if (msgstatus=='no' && jQuery("#hdnCurrentTab").val()!="SENT" && jQuery("#hdnCurrentTab").val()!="READ"){
                        jQuery.ajax({
                        type: "POST",
                        url: "messages/ajax_read_message.php",
                        data: {msgid:msgid, action:"read"},
                        success: function(data){
                            console.log ("Message read");
                        }
                     })
                    } 
            }
        }); 
        
        // for mobile
        jQuery('.msglist').click(function(){
            if(jQuery(window).width() < 480) {
                jQuery('.messageright, .messagemenu .back').show();
                jQuery('.messageleft').hide();
            }
        });
        
        jQuery('.messagemenu .back').click(function(){
            if(jQuery(window).width() < 480) {
                jQuery('.messageright, .messagemenu .back').hide();
                jQuery('.messageleft').show();
            }
        });
    });
    
    jQuery("#msgsearch").keyup(function(){
		msgsearch=jQuery("#msgsearch").val();
                
		if (msgsearch.length>3 || msgsearch.length==0)
                    
			SearchMessages(msgsearch);
                    
	});
    if(window.location.hash == "#googtrans(en|<?php echo $_SESSION['lang'];?>)"){
		jQuery("input[type='text']").css("height","30px");
		jQuery("input[type='select']").css("width","310px");
	}
});
function SearchMessages(msgsearch)
{
	sqlUserFilter=jQuery("#sqlUserFilter").val();
		sqlStatusFilter=jQuery("#sqlStatusFilter").val();
		sqlUserEmployeeFilter=jQuery("#sqlUserEmployeeFilter").val();
               
		if (msgsearch!=""){
                sqlsearch = " AND (Subject LIKE '%"+msgsearch+"%' OR message LIKE '%"+msgsearch+"%' OR emp.last_name  LIKE '%"+msgsearch+"%' OR emp.first_name  LIKE '%"+msgsearch+"%' ";
                if (jQuery("#hdnCurrentTab").val()=="SENT"){
                    sqlsearch = sqlsearch + "OR emprcv.first_name LIKE '%"+msgsearch+"%' OR emprcv.last_name LIKE '%"+msgsearch+"%'";
                }
                sqlsearch = sqlsearch + " )";
		//sqlsearch2 = " AND (lm.subject LIKE '%"+msgsearch+"%' OR lm.message LIKE '%"+msgsearch+"%' OR lo.name  LIKE '%"+msgsearch+"%' OR concat(emp.first_name,' ',emp.last_name)  LIKE '%"+msgsearch+"%' ) ";
                } else sqlsearch ="";				
        jQuery.ajax({
                type: "POST",
                url: "messages/ajax_refresh_messages.php",
                data: {sqlUserFilter:sqlUserFilter,sqlStatusFilter:sqlStatusFilter,sqlUserEmployeeFilter:sqlUserEmployeeFilter,sqlsearch:sqlsearch,hdnCurrentTab:'<?php echo $_POST["hdnCurrentTab"]?>'},
							success: function(data){
                                jQuery(".msglist").html(data);
								RegisterMessageClick();
                            }
                         });
}
function sendReply(){

var msgid = jQuery('#currentmessageid').val();

if (jQuery('#newreply').val()!=""){
    jQuery.ajax({
                type: "POST",
                url: "messages/ajax_reply_message.php",
                data: {msgid:msgid, message:jQuery('#newreply').val()},
                success: function( data ){
					//new code begin 
							sqlUserFilter=jQuery("#sqlUserFilter").val();
							sqlStatusFilter=jQuery("#sqlStatusFilter").val();
							sqlsearch=jQuery("#sqlsearch").val();
						//end
                        if (data=='1'){
                            jQuery.ajax({
                            type: "POST",
                            url: "messages/ajax_refresh_messages.php",
                            data: {msgid:msgid,sqlUserFilter:sqlUserFilter,sqlStatusFilter:sqlStatusFilter,sqlsearch:sqlsearch,hdnCurrentTab:'<?php echo $_POST["hdnCurrentTab"]?>'},
                            success: function(data){
                                jQuery(".msglist").html(data);
                              //  jQuery('.messageview').html('<span style="margin-left: 10px !important;padding-left: 10px;margin-top: 10px;"><h4 class="subject">Your message has been sent.</h4></span>');
                              jQuery('.messageview').html('');
                              jQuery('.msgreply').css("display","none");
                              jQuery("#newreply").val("");
                              jAlert("Your message has been sent!");
								RegisterMessageClick();
                            }
                         })
                        } 
                }
            }); 
  }
  else{
    jAlert('Cannot send an empty message!', 'Alert', function(){
     });
  }
}
function RegisterMessageClick()
{
  jQuery('.msglist li').click(function(){
        jQuery('.msglist li').each(function(){ jQuery(this).removeClass('graymsg')});
        jQuery(this).addClass('graymsg');
        var msgid = jQuery(this).data('id') ;
        var msgsubject = jQuery(this).data('subject');
        var msgstatus = jQuery(this).data('status');
        jQuery(this).removeClass('selected').addClass('unread');
        jQuery("#currentmessageid").val(msgid);
        jQuery.ajax({
            type: "POST",
            url: "messages/ajax_message.php",
            data: {msgid:msgid,subject:msgsubject},
            success: function( data ){
                    jQuery('.messageview').html(data);
                    jQuery('.msgreply').css("display","");
                    if (msgstatus=='unread' && jQuery("#hdnCurrentTab").val()!="SENT" && jQuery("#hdnCurrentTab").val()!="READ"){
                        jQuery.ajax({
                        type: "POST",
                        url: "messages/ajax_read_message.php",
                        data: {msgid:msgid, action:"read"},
                        success: function(data){
                            console.log ("Message read");
                        }
                     })
                    } 
            }
        }); 
        
        // for mobile
        jQuery('.msglist').click(function(){
            if(jQuery(window).width() < 480) {
                jQuery('.messageright, .messagemenu .back').show();
                jQuery('.messageleft').hide();
            }
        });
        
        jQuery('.messagemenu .back').click(function(){
            if(jQuery(window).width() < 480) {
                jQuery('.messageright, .messagemenu .back').hide();
                jQuery('.messageleft').show();
            }
        });
    });
}
</script>
</head>

<body>

<div class="mainwrapper">
    <?php require_once('require/top.php');?>
	
	<?php require_once('require/left_nav.php');?>
    
    <div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="help.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Messages</li>
            
            <li class="right">
                <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
                <ul class="dropdown-menu pull-right skin-color">
                    <li><a href="default">Default</a></li>
                    <li><a href="navyblue">Navy Blue</a></li>
                    <li><a href="palegreen">Pale Green</a></li>
                    <li><a href="red">Red</a></li>
                    <li><a href="green">Green</a></li>
                    <li><a href="brown">Brown</a></li>
                </ul>
            </li>
			<li class="right">
			 <a href="" data-toggle="dropdown" class="dropdown-toggle capital_word notranslate"><?=$_SESSION['mainlang']?></a>
			 
			  <ul class="dropdown-menu pull-right notranslate">
                  <li  <?php if( $_SESSION['lang']=='en'){ echo "class='active'";} ?>>
				  <a href="messages.php?lang=en#googtrans(en|en)" >English</a>				  
				  </li>
                  <li <?php if( $_SESSION['lang']=='ar'){ echo "class='active'"; } ?> >
				     <a  href="messages.php?lang=ar#googtrans(en|ar)" >Arabic</a>
				  
				  </li>
                  <li <?php if( $_SESSION['lang']=='zh-CN'){ echo "class='active'"; } ?>>
				  <a href="messages.php?lang=zh-CN#googtrans(en|zh-CN)">Chinese</a>
				  
				  </li>
                  <li <?php if( $_SESSION['lang']=='nl'){ echo "class='active'"; } ?>  ><a href="messages.php?lang=nl#googtrans(en|nl)">Dutch</a></li>
                  <li <?php if( $_SESSION['lang']=='fi'){ echo "class='active'"; } ?> >
				  <a href="messages.php?lang=fi#googtrans(en|fi)">Finnish</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='fr'){ echo "class='active'"; } ?> >
				  <a href="messages.php?lang=fr#googtrans(en|fr)">French</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='de'){ echo "class='active'";} ?>>
				  <a href="messages.php?lang=de#googtrans(en|de)">German</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='el'){ echo "class='active'";} ?>  >
				  <a href="messages.php?lang=el#googtrans(en|el)">Greek</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='hi'){ echo "class='active'";} ?> >
				  <a href="messages.php?lang=hi#googtrans(en|hi)">Hindi</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='it'){ echo "class='active'";} ?> >
				  <a href="messages.php?lang=it#googtrans(en|it)">Italian</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='ja'){ echo "class='active'";} ?> >
				  <a href="messages.php?lang=ja#googtrans(en|ja)">Japanese</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='ko'){ echo "class='active'";} ?> >
				  <a href="messages.php?lang=ko#googtrans(en|ko)">Korean</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='no'){ echo "class='active'";  } ?> >
				  <a href="messages.php?lang=no#googtrans(en|no)">Norwegian</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='pt'){ echo "class='active'";} ?>>
				  <a href="messages.php?lang=pt#googtrans(en|pt)">Portuguese</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='ru'){ echo "class='active'"; } ?> >
				  <a href="messages.php?lang=ru#googtrans(en|ru)">Russian</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='es'){ echo "class='active'"; }?>>
				  <a href="messages.php?lang=es#googtrans(en|es)">Spanish</a>
				  </li>
                  <li  <?php if( $_SESSION['lang']=='sv'){ echo "class='active'";} ?> >
				  <a href="messages.php?lang=sv#googtrans(en|sv)">Swedish</a>
				  </li>  
               </ul>
			</li>
        </ul>
        
        <div class="pageheader">
         <div class="messagehead" style="float: right;margin-top: 10px;">
		<button class="btn btn-success btn-large" data-toggle="modal" id="composemsg">Compose Message</button>
	 </div>     
            <div class="pageicon"><span class="iconfa-envelope"></span></div>
            <div class="pagetitle" style="margin-left:85px !important">
                <h5>Communicate with ease</h5>
                <h1>Messages</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
               <div class="messagepanel">
				<div class="messagemenu " style="margin-top: 0px;">
					<ul>
						<li class="back"><a><span class="iconfa-chevron-left"></span> Back</a></li>
						<li class="inbox <?php if ((isset($_POST["hdnCurrentTab"]) && $_POST["hdnCurrentTab"]=="INBOX") || !isset($_POST["hdnCurrentTab"])) echo "active"; else echo""; ?>"><a href="messages.php?lang=<?php echo $_SESSION['lang'];?>#googtrans(en|<?php echo $_SESSION['lang'];?>)"><span class="iconfa-inbox"></span> Inbox</a></li>
						<li class="sent <?php if (isset($_POST["hdnCurrentTab"]) && $_POST["hdnCurrentTab"]=="SENT") echo "active"; else echo""; ?>"><a href="messages.php?lang=<?php echo $_SESSION['lang'];?>#googtrans(en|<?php echo $_SESSION['lang'];?>)"><span class="iconfa-envelope"></span> Sent</a></li>
						<li class="trash <?php if (isset($_POST["hdnCurrentTab"]) && $_POST["hdnCurrentTab"]=="READ") echo "active"; else echo""; ?>"><a href="messages.php?lang=<?php echo $_SESSION['lang'];?>#googtrans(en|<?php echo $_SESSION['lang'];?>)"><span class="iconfa-share-alt"></span> Read</a></li>
					</ul>
				</div>
				<div class="messagecontent">
					<div class="messageleft" style="height: 639px;">
						<form class="messagesearch" action="messages.php?lang=<?php echo $_SESSION['lang'];?>#googtrans(en|<?php echo $_SESSION['lang'];?>)" name="frmsearch" id="frmsearch" method="post">
							<input type="hidden" name="hdnCurrentTab" id="hdnCurrentTab" value="<?php echo $_POST["hdnCurrentTab"]; ?>">
							<input type="text" name="msgsearch" id="msgsearch" class="input-block-level" value="<?php if (isset($_POST["msgsearch"]) && $_POST["msgsearch"]!="") echo $_POST["msgsearch"];?>" placeholder="Search message and hit enter..." />
							<input type="hidden" id="temp_msg">
						</form>
						<?php 

						$sqlsearch ="";
					 $sqlStatusFilter = "";
					 $sqlUserFilter = "";
					 $sqlUserEmployeeFilter="";
					 switch ($_POST["hdnCurrentTab"])
					 {
						case "INBOX": 
							$sql = "Select empmsg.*, COALESCE(corporate.name, CONCAT(emp.first_name ,' ' ,emp.last_name )) as sender, COALESCE(corporate.image, emp.image )as senderimage, toemp.first_name as frptemp, toemp.last_name as lrptemp 
                                                        from employee_messages empmsg 
                                                        left join employees emp on empmsg.entered_by_emp_id = emp.id left join employees toemp on toemp.id = empmsg.emp_id 
                                                        LEFT JOIN corporate ON empmsg.entered_by_corp_id = corporate.id
                                                        where (empmsg.emp_id in (SELECT id FROM employees WHERE email = '{$_SESSION["email"]}')) and empmsg.readd='no' " . $sqlsearch ; 
                     
							break;
						case "SENT": 
							$sql = "Select empmsg.*, COALESCE(CONCAT(emp.first_name,' ', emp.last_name), corporate.name) as sender,
                                                        COALESCE(emp.image, corporate.image) as senderimage, 
                                                        COALESCE(emp.email,corporate.email) as senderemail, 
                                                        emp.emp_id, COALESCE(CONCAT(emprcv.first_name,' ', emprcv.last_name),corprcv.name) as receiver,
                                                        COALESCE(emprcv.email,corprcv.email) as receiveremail,  COALESCE(emprcv.image,corprcv.image) as receiverimage
                                                        from employee_messages empmsg 
                                                        left join employees emp on empmsg.entered_by_emp_id = emp.id 
                                                        left join corporate on empmsg.entered_by_corp_id = corporate.id 
                                                        left join employees emprcv on empmsg.emp_id = emprcv.id 
                                                        left join corporate corprcv on empmsg.sent_to_corp = corprcv.id 
                                                        where (empmsg.entered_by_emp_id in (SELECT id FROM employees WHERE email = '{$_SESSION["email"]}')) ". $sqlsearch; 
                     
							break;
						case "READ": 
							$sql = "Select empmsg.*, COALESCE(corporate.name, CONCAT(emp.first_name ,' ' ,emp.last_name )) as sender, COALESCE(corporate.image, emp.image )as senderimage, toemp.first_name as frptemp, toemp.last_name as lrptemp 
                                                        from employee_messages empmsg 
                                                        left join employees emp on empmsg.entered_by_emp_id = emp.id left join employees toemp on toemp.id = empmsg.emp_id 
                                                        LEFT JOIN corporate ON empmsg.entered_by_corp_id = corporate.id
                                                        where (empmsg.emp_id in (SELECT id FROM employees WHERE email = '{$_SESSION["email"]}')) and empmsg.readd='yes' " . $sqlsearch ; 
                     
							break;
						default: 
							$sql = "Select empmsg.*, COALESCE(corporate.name, CONCAT(emp.first_name ,' ' ,emp.last_name )) as sender, COALESCE(corporate.image, emp.image )as senderimage, toemp.first_name as frptemp, toemp.last_name as lrptemp 
                                                        from employee_messages empmsg 
                                                        left join employees emp on empmsg.entered_by_emp_id = emp.id left join employees toemp on toemp.id = empmsg.emp_id 
                                                        LEFT JOIN corporate ON empmsg.entered_by_corp_id = corporate.id
                                                        where (empmsg.emp_id in (SELECT id FROM employees WHERE email = '{$_SESSION["email"]}')) and empmsg.readd='no' "  . $sqlsearch ; 
                     
							break;
					 }
                                            if ($_POST["msgsearch"]!=""){
                                                 $sqlsearch = " AND (Subject like '%".mysql_real_escape_string($_POST["msgsearch"])."%' OR message like '%".mysql_real_escape_string($_POST["msgsearch"])."%' OR emp.last_name  like '%".mysql_real_escape_string($_POST["msgsearch"])."%' OR emp.first_name  like '%".mysql_real_escape_string($_POST["msgsearch"])."%' ) ";
                                               $sql .= $sqlsearch;			 
                                            }

                                               $sql .= " ORDER BY empmsg.date DESC,  empmsg.time DESC";

						$result = mysql_query($sql);
						if (mysql_num_rows($result)>0){
							?>
							<ul class="msglist" style="height: 579px;">
                               <?php 
                               $firstRecord = True;
                               $msgstyle = "";
                               while ($row=mysql_fetch_assoc($result)){
                                   if ($row["readd"]=='no' && ($_POST["hdnCurrentTab"]=="INBOX" || $_POST["hdnCurrentTab"]=="")){
                                       $msgstyle = "selected"; 
                                       //$firstRecord = False;
                                   }else {   
                                       $msgstyle = "unread"; 
                                   }
                                   if ($_POST["hdnCurrentTab"]=="SENT"){
                                   ?>
                                <li class="<?php echo $msgstyle;?> getmessage" data-id="<?php echo $row["id"];?>" data-subject="<?php echo $row["Subject"];?>" data-status="<?php echo $row["readd"];?>" id="message<?php echo $row["id"];?>" data-thread="<?php echo $row["thread_id"];?>">
                                     <?php if ($row["receiverimage"]!="" && file_exists("images/". $row["receiverimage"]) ){?>
                                    <div class="thumb"><img src="<?php echo API. "images/". $row["receiverimage"];?>" style="width:40px;height:40px;" alt="" /></div>
                                    <?php }else{?>
                                    <div class="thumb"><?php if ($row["sent_to_corp"]!="" && $row["sent_to_corp"]!=0) {?><img src="images/Default - Corporate Icon.png" /><?php } else {?><img src="images/Default - User - thumb.png" alt="" /><?php } ?></div>
                                    <?php }?>
                                    <div class="summary" style="max-height: 55px;">
                                        <span class="date pull-right"><small><?php echo $row["date"];?> <?php echo date("H:i",strtotime($row["time"]));?></small><br><?php if ($row["priority"]=="High"){?><span class="iconfa-flag" style="color:red;float: right;margin-top: 5px;"></span><?php }else if ($row["priority"]=="Low"){ ?><span class="iconfa-flag" style="color:yellow;float: right;margin-top: 5px;"></span><?php } ?></span>
                                        <p style="margin-top:0px;"><strong style="line-height: 14px;"><?php echo $row["receiver"];?></strong></p>
                                        <p style="margin-top:0px; " ><strong style="line-height: 14px;"><?php if ($row["Subject"] == "") {echo "No Subject";} else echo $row["Subject"] ;?></strong></p>
                                        <p  style="margin-top:0px;"><span style="line-height:14px;"><?php echo substr($row["message"],0,18);?>..</span></p>
                                    </div>
                                </li>
                                <?php } else {?>
                                <li class="<?php echo $msgstyle;?> getmessage" data-id="<?php echo $row["id"];?>" data-subject="<?php echo $row["Subject"];?>" data-status="<?php echo $row["readd"];?>" id="message<?php echo $row["id"];?>" data-thread="<?php echo $row["thread_id"];?>">
                                    <?php if ($row["senderimage"]!="" && file_exists("images/". $row["senderimage"])){?>
                                    <div class="thumb"><img src="<?php echo API. "images/". $row["senderimage"];?>" style="width:40px;height:40px;" alt="" /></div>
                                    <?php }else{?>
                                    <div class="thumb"><?php if ($row["entered_by_corp_id"]!=""  && $row["entered_by_corp_id"]!=0) {?><img src="images/Default - Corporate Icon.png" /><?php } else {?><img src="images/Default - User - thumb.png" alt="" /><?php } ?></div>
                                    <?php }?>
                                    <div class="summary" style="max-height: 55px;">
                                        <span class="date pull-right"><small><?php echo $row["date"];?> <?php echo date("H:i",strtotime($row["time"]));?></small><br><?php if ($row["priority"]=="High"){?><span class="iconfa-flag" style="color:red;float: right;margin-top: 5px;"></span><?php }else if ($row["priority"]=="Low"){ ?><span class="iconfa-flag" style="color:yellow;float: right;margin-top: 5px;"></span><?php } ?></span>
                                        <p style="margin-top:0px;"><strong style="line-height: 14px;"><?php echo $row["sender"];?></strong></p>
                                        <p style="margin-top:0px; " ><strong style="line-height: 14px;"><?php if ($row["Subject"] == "") {echo "No Subject";} else echo $row["Subject"] ;?></strong></p>
                                        <p  style="margin-top:0px;"><span style="line-height:14px;"><?php echo substr($row["message"],0,18);?>..</span></p>
                                    </div>
                                </li>
                                <?php }$msgstyle = ""; }?>
                            </ul>
                            <?php } else {echo "<div style='padding-left: 102px;padding-top: 30px;font-size: 15px;'>No Messages Found.</div>";}?>
					</div><!--messageleft-->
					<div class="messageright" style="height: 639px;">
                            <div class="messageview">
                               <?php //if ($msgSent) echo '<span style="margin-left: 10px;padding-left: 10px;position: absolute;margin-top: 10px;"><h4>Your message was sent.</h4></span>';?> 
                            </div>  
                            <div class="msgreply" style="display:none;">
                                <input type="hidden" id="currentmessageid">
                                <input type="hidden" id="currentmessagesubject">
                                
                                <div class="reply" style="margin-left: 0">
                                    <textarea placeholder="Type something here to reply" name="newreply" id="newreply" style="margin-left: 8px;width:99%"></textarea>
                                </div><!--reply-->
                                <div class="thumb" style="padding-left: 10px;"><button class="btn btn-info alertinfo" onClick="sendReply();" style="padding: 5px 12px 5px;">Send</button></div>
                            </div>
                        </div><!--messageright-->
				</div><!--messagecontent-->
			</div><!--messagepanel-->
                
                <?php include_once 'require/footer.php';?><!--footer-->
                
                
            </div><!--maincontentinner-->
        </div><!--maincontent-->
        
    </div><!--rightpanel-->
    
</div><!--mainwrapper-->

</body>
</html>
<div id="composeModal" class="modal hide fade" style="max-height:500px !important;">
	<!-- juni [req 1.28] - 16.03.2014 - show errors -->
	<!-- 	<form id="frmCompose" name="frmCompose" action="messages.php" method="post" class=""> -->
		<form id="frmCompose" name="frmCompose" action="" method="post" class="">
			<div class="modal-header" style="max-height:50px !important;">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Compose Message</h3>
			</div>
			<div class="modal-body" style="max-height:350px !important;">
				<table width="100%" height="100%">
					<tr>
						<td width="30%" style="vertical-align: middle;">
							<div class="rows_pop control-group"><b>Location: </b><span style="color:red;">*</span></div>
						</td>
						<td width="70%"> 
							<span class="rows_pop control-group">
								<!-- <input type="text" name="location" value="" id="location" placeholder="Location" title="location" style="width:310px;" >
                                                                <input type="hidden" name="location_id" value="" id="location_id">-->
                                                            <select name="location_id" id="location_id">
                                                                <option value=""> - - - Select Location - - - </option>
                                                                <?php $sql = "SELECT id,concat(name,' ','(ID#: ',id,')') name FROM locations where id in (SELECT location_id from employees WHERE email='{$_SESSION["email"]}') order by name ASC limit 10";
                                                                        $res = mysql_query($sql) or die(mysql_error());
                                                                        $loc_count = mysql_num_rows($res);
                                                                        $setselected="";
                                                                        if ($loc_count==1) $setselected = "selected";
                                                                        if ($res) {
                                                                                while ($row = mysql_fetch_assoc($res)) {	
                                                                                   echo "<option value='".$row['id']."' " . $setselected. ">".$row['name']."</option>";
                                                                                }
                                                                                
                                                                        }?>
                                                            </select>
							</span>
						</td>
					</tr>     
					<tr>
						<td width="30%" style="vertical-align: middle;">
							<div class="rows_pop control-group"><b>To: </b><span style="color:red;">*</span></div>
						</td>
						<td width="70%"> 
							<span class="rows_pop control-group">
								<select id="recipient" name="recipients[]" data-placeholder=" - - - Select Recipient - - - " class="chzn-select" multiple="multiple" style="width:324px;" tabindex="4">
									<option value=""></option> 
									 
								</select>
							</span>
						</td>
					</tr> 
					<tr>
						<td width="30%">
							<div class="rows_pop control-group"><b>Priority: </b><span style="color:red;">*</span></div>
						</td>
						<td width="70%" style="padding-top: 10px">
							<div class="rows_pop control-group">
								<select class="input-large" name="txtPriority" id="txtPriority" style="width:323px;">
                                                                        <option value=""> - - - Select Priority - - - </option>
									<option value="Low">Low</option>
									<option value="Normal" selected>Normal</option>
									<option value="High">High</option>
								</select>    
							</div>
						</td>
					</tr>
					<tr>
						<td width="30%">
							<div class="rows_pop control-group"><b>Subject: </b><span style="color:red;">*</span></div>
						</td>
						<td width="70%">
							<div class="rows_pop control-group">
							<input type="text" class="input-large" name="txtSubject" value="" id="txtSubject" placeholder="Subject" title="Subject" style="width:310px;">
							</div>
						</td>
					</tr>
					<tr>
						<td width="30%">
							<div class="rows_pop"><b>Message: </b><span style="color:red;">*</span></div>
						</td>
						<td width="70%" style="vertical-align: top;">
							<div class="rows_pop control-group">
								<textarea name="txtMessage" id="txtMessage" style="width: 310px;height: 127px;"></textarea>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</form>  
			<div class="modal-footer" style="text-align: center;max-height:50px !important;">
				<p class="stdformbutton">
					<button id="btnCancel" data-dismiss="modal" class="btn btn-primary" style="padding: 5px 12px 5px;">Cancel</button>
					<button type="submit" id="btnCompose" name="btnCompose"  class="btn btn-primary" style="padding: 5px 12px 5px;">Submit</button>
				</p>
			</div>		
	</div>    


<script type="text/javascript">

jQuery(document).ready(function(){
    if (jQuery("#location_id").val()!=""){
        loadlocemp(jQuery("#location_id").val());
    }
    jQuery('.modal').on('hide',function(e){
                jQuery("txtPriority").val("");
                jQuery("#txtSubject").val("");
                jQuery("#txtMessage").val("");
                jQuery("#recipient").val("");
          //      jQuery("#location, #location_id").val("");
                
            });
            
     
         jQuery("#composemsg").click(function(e){
             <?php if ($loc_count==0){?>
             e.preventDefault();
             jAlert("You are not associated with any Location. Please ask the location to use your email in your employee folder.");
              <?php }else { ?>
             jQuery('#composeModal').modal('show');
              <?php } ?>
         })
        
  /*  jQuery('#location').typeahead({
    source: function (query, process) {
        return jQuery.ajax({
	    //url: 'ajax_autocomplete.php', //juni -> switch file as someone keeps modifying it
	     url: 'messages/ajax_get_location_with_id.php',
            type: 'post',
            data: { query: query,  autoCompleteClassName:'autocomplete',
            employeeid:'<?php echo $_SESSION['client_id']?>',
            selectedClassName:'sel',
            attrCallBack:'rel',
            identifier:'estado'},
            dataType: 'json',
            success: function (result) {

                var resultList = result.map(function (item) {
                    var aItem = { id: item.id, name: item.label };
                    return JSON.stringify(aItem);
                });

                return process(resultList);

            }
        });
    },

matcher: function (obj) {
        var item = JSON.parse(obj);
        return ~item.name.toLowerCase().indexOf(this.query.toLowerCase())
    },

    sorter: function (items) {          
       var beginswith = [], caseSensitive = [], caseInsensitive = [], item;
        while (aItem = items.shift()) {
            var item = JSON.parse(aItem);
            if (!item.name.toLowerCase().indexOf(this.query.toLowerCase())) beginswith.push(JSON.stringify(item));
            else if (~item.name.indexOf(this.query)) caseSensitive.push(JSON.stringify(item));
            else caseInsensitive.push(JSON.stringify(item));
        }

        return beginswith.concat(caseSensitive, caseInsensitive)

    },


    highlighter: function (obj) {
        var item = JSON.parse(obj);
        var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
        return item.name.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
            return '<strong>' + match + '</strong>'
        })
    },

    updater: function (obj) {
        var item = JSON.parse(obj);
        jQuery('#location_id').attr('value', item.id);
        loadlocemp(item.id);
        return item.name;
    }
});
*/	
        jQuery("#location_id").change(function(){
            loadlocemp(jQuery("#location_id").val());
        })
        
	jQuery('#btnCompose').click(function(e){
		e.preventDefault();
		if (jQuery("#location_id").val()==""){
                        jAlert("Please select location!")
			return false;		
                } 
               
		if (jQuery("#recipient").val()=="" || jQuery("#recipient").val()==null){
                        jAlert("Please select recipient!")
			return false;		
                }  
                
		if (jQuery("#txtPriority").val()==""){
                        jAlert("Please enter priority!")
			return false;		
                }  				
		if (jQuery("#txtSubject").val()==""){
                        jAlert("Please enter subject!")
			return false;		
                }  
		if (jQuery("#txtMessage").val()==""){
                        jAlert("Please enter message!")
			return false;		
                }  
                jQuery(this).attr("disabled",true);
		jQuery('#frmCompose').submit();   
	});
	jQuery('#btnCancel').click(function(e){//19.03.2014 -> remove data on close
		jQuery('.chzn-search').hide();
		jQuery('#location').val("");   
		jQuery('#recipient').val(""); 
		jQuery(".chzn-select").val('').trigger("liszt:updated");		
		jQuery('#txtPriority').val("");   
		jQuery('#txtSubject').val("");   
		jQuery('#txtMessage').val("");   
	});
	//<!-- juni [req 1.28] - 16.03.2014 - submit search on change -->
	//Submit button on AddMessage popup
	jQuery('#frmAddMessage').submit(function(){
        jQuery("#hdnTxtSubject").val(jQuery("#txtSubject").val());
		jQuery("#hdnMsgText").val(jQuery("#txtMessage").val());
	});
	
});
function loadlocemp(id){
    jQuery.ajax({
	    url: 'messages/ajax_autocomplete_loc_employee.php?loc='+ id,
            type: 'post',
            success: function (result) {
                jQuery("#recipient").html("");
                jQuery('#recipient').trigger("liszt:updated");
                jQuery("#recipient").append(result);
                jQuery('#recipient').trigger("liszt:updated");
            }
    })     
}
</script>
