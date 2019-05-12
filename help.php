<?php 
if(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){
	ob_start("ob_gzhandler"); 
}else{ 
	ob_start();
}
require_once 'require/security.php';
include_once("config/accessConfig.php");


if (isset($_POST["AddMessage"])){

	$msg = "";
        $image_up = "";
         //   $employees = $_POST['employees'];
        if (isset($_POST["attachedfile"]) && $_POST["attachedfile"] != '') {
      
          	$filename = $_POST["attachedfile"];

            $upload_to_temp = "temp_img/" . $filename;

           // if (move_uploaded_file($_FILES['attach']['tmp_name'], $upload_to_temp)) {
                $ftp_path = "help/" . $filename;
                $ftphost = FTPDOMAIN;
                $ftpusr = FTPUSER;
                $ftppwd = FTPPASSWORD;
                if (file_exists($upload_to_temp)) {
                     $conn_id = ftp_connect($ftphost,FTPPORT) or die("Couldn't connect to $ftphost");
                    $login_result = ftp_login($conn_id, $ftpusr, $ftppwd);
                    ftp_pasv ($conn_id, FTPPASIVE);
                    if (ftp_put($conn_id, $ftp_path, $upload_to_temp, FTP_BINARY)) {
                        unlink($upload_to_temp);
                        $image_up = $ftp_path;

                    }else{
                        $msg = "Could not upload, please check permissions!";
                      //  echo "Error transferring file via ftp!<br/>";
                      //  echo $filename . "<br/>";
                      //  echo $upload_to_temp . "<br/>";
                      //  echo $ftp_path . "<br/>";
                    }
                }else{
                   // echo "File does not exist in img/temp!";
                    $msg = "An error has occured while uploading!";
                }
            //}else{
              //  echo "Error moving file to temp location!<br>";

            //}
        }
		$txtSubject = mysql_real_escape_string($_POST["hdnTxtSubject"]);
		$msgText = mysql_real_escape_string($_POST["hdnMsgText"]);
		$ticket_number = mysql_real_escape_string($_POST["ticket_number"]);		 
                $type = $_POST["type"];
		$location = isset($_POST['location_id'])&& $_POST['location_id']!=""? $_POST['location_id']: "";
                $admin = isset($_POST['admin_id'])&& $_POST['admin_id']!=""? $_POST['admin_id']: "";
                $corporate = isset($_POST['corporate_id'])&& $_POST['corporate_id']!=""? $_POST['corporate_id']: "";
                $master = isset($_POST['master_id'])&& $_POST['master_id']!=""? $_POST['master_id']: "";
                $client = isset($_POST['client_id'])&& $_POST['client_id']!=""? $_POST['client_id']: "";
		
		 $newsql = "INSERT INTO help (from_type,sent_datetime,status,from_employee_master,topic,to_type,message,attachment,Ticket) 
         VALUES ('Team','".date("Y-m-d H:i:s")."','unread','{$_SESSION['client_id']}','{$txtSubject}','Admin','{$msgText}','{$image_up}','{$ticket_number}')";
     		mysql_query($newsql);
                
                header("location:help.php");
                $_SESSION["help_message_sent"]="OK";
		exit;
		
	}


?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<!-- <title><?php// echo $_SESSION["SITE_TITLE"];?></title> -->
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-timepicker.min.css" type="text/css" />
<link rel="stylesheet" href="css/responsive-tables.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap-datetimepicker.min.css"/>
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
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script> 
<script type="text/javascript" src="js/fullcalendar.min.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript">
idleTime = 0;
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
    if (idleTime > 59) { 
        refreshMessages();
        idleTime=0
    }
}
</script> 
<script type="text/javascript">
	new_time="<?php echo date('h:i A'); ?>";			
</script>
<script type="text/javascript">
	var client_id = <?php echo $_SESSION['client_id'];?>;
        jQuery(document).ready(function(){
            jQuery('.modal').on('hide',function(e){
                jQuery(".getdata").hide();
                jQuery("#txtSubject").val("");
                jQuery("#txtMessage").val("");
                jQuery('#attachment').html("");
            });
        });
</script>

<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
<style>
    .graymsg{
        background-color: rgb(128,128,128) !important;
        color:black;
    }
.chzn-container
{
	width: 324px;
}
.ui-autocomplete
{
	height:200px !important;
	overflow-y: scroll;
}
#loading {
        width: 100%;
        height: 100%;
        top: 0px;
        left: 0px;
        position: fixed;
        display: block;
        opacity: 0.7;
        background-color: #fff;
        z-index: 99;
        text-align: center;
      }

      #loading-image {
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: 100;
      }
</style>
<script type="text/javascript">
    function refreshMessages(){
        sqlUserFilter=jQuery("#sqlUserFilter").val();
        sqlStatusFilter=jQuery("#sqlStatusFilter").val();
        sqlsearch=jQuery("#sqlsearch").val();
	     jQuery("#loading").show();					
        jQuery.ajax({
        type: "POST",
        url: "help/ajax_refresh_messages.php",
        data: {sqlUserFilter:sqlUserFilter,sqlStatusFilter:sqlStatusFilter,sqlsearch:sqlsearch,hdnCurrentTab:'<?php echo $_POST["hdnCurrentTab"]?>'},
        success: function(data){
            
          jQuery(".msglist").html(data);
          jQuery('.messageview').html('');
          jQuery('.msgreply').css("display","none");
          jQuery("#newreply").val("");
          jQuery("#countmsg").text(jQuery("#countedmessages").val());
          jQuery("#loading").hide();

        }
     });
                       
    }

</script>
</head>

<body>
    <div id="loading" style="display:none;">
    <img id="loading-image" src="images/loaders/loader7.gif" alt="Loading..." />
</div> 
<div class="mainwrapper">
   
    <?php require_once('require/top.php');?>
	
	<?php require_once('require/left_nav.php');?>
    <?php if(isset($_SESSION["help_message_sent"]) && $_SESSION["help_message_sent"] == "OK") { ?>
    	<script type="text/javascript">
		jAlert("Message has been sent!",'Alert Dialog');
		</script>
	<?php $_SESSION["help_message_sent"] = ""; } ?>  
    <div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="help.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Help</li>
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
        </ul>   
                 
       <div class="pageheader">
           <div class="messagehead" style="float:right">
                    <button class="btn btn-success btn-large" onClick="refreshMessages();">Refresh</button>&nbsp;&nbsp;
                    <button class="btn btn-success btn-large addcode"  href="#add-popup" data-toggle="modal">Compose Message</button>
             </div>
            <div class="pageicon"><span class="iconfa-envelope"></span></div>
            <div class="pagetitle">
                <h5>Internal help message board</h5>
                <h1>Help</h1>
            </div>
        </div><!--pageheader-->
             
        <div class="maincontent"> 
            <div class="maincontentinner" style="padding-top: 5px;">
                <div class="messagepanel">
                    <div class="messagemenu">
                        <ul>
                            <li class="back"><a><span class="iconfa-chevron-left"></span> Back</a></li>
                            <li class="inbox <?php if ((isset($_POST["hdnCurrentTab"]) && $_POST["hdnCurrentTab"]=="INBOX") || !isset($_POST["hdnCurrentTab"])) echo "active"; else echo""; ?>"><a href="#"><span class="iconfa-inbox"></span> Inbox</a></li>
                            <li class="sent <?php if (isset($_POST["hdnCurrentTab"]) && $_POST["hdnCurrentTab"]=="SENT") echo "active"; else echo""; ?>"><a href="#"><span class="iconfa-envelope"></span> Sent</a></li>
                            <li class="trash <?php if (isset($_POST["hdnCurrentTab"]) && $_POST["hdnCurrentTab"]=="READ") echo "active"; else echo""; ?>"><a href="#"><span class="iconfa-share-alt"></span> Read</a></li>
                        </ul>
                    </div>
                    <div class="messagecontent">
                        <div class="messageleft" style="height:640px">
                            <form class="messagesearch" action="help.php" name="frmsearch" id="frmsearch" method="post">
								<input type="hidden" name="hdnCurrentTab" id="hdnCurrentTab" value="<?php echo $_POST["hdnCurrentTab"]; ?>">
                                <input type="text" id="msgsearch" name="msgsearch" class="input-block-level" value="<?php if (isset($_POST["msgsearch"]) && $_POST["msgsearch"]!="") echo $_POST["msgsearch"];?>" placeholder="Search message and hit enter..." />
                            </form>
							
                             <?php 
							
                     $sqlsearch ="";
					 $sqlStatusFilter = "";
					 $sqlUserFilter = "";
					 $sqlUserEmployeeFilter="";
					 switch ($_POST["hdnCurrentTab"])
					 {
						case "INBOX": 
							$sql = "SELECT help.*, COALESCE (users.name, concat(employees.first_name, ' ', employees.last_name),concat(employees_master.first_name, ' ', employees_master.last_name),corporate.name, locations.name, clients.name, 'Admin') as sender ,
                                                            COALESCE ( employees.image,corporate.image, locations.image, clients.image, employees_master.image, empmm.image) as senderimage 
                                                       FROM help LEFT JOIN users on help.from_admin = users.id LEFT JOIN employees_master empmm ON users.email = empmm.email
                                                       LEFT JOIN employees on employees.id = help.from_employee
                                                        LEFT JOIN corporate on corporate.id = help.from_corp 
                                                       Left JOIN clients on clients.id = help.from_client  
                                                       LEFT JOIN locations on locations.id = help.from_location
                                                       LEFT JOIN employees_master on employees_master.empmaster_id = help.from_employee_master
                                                       WHERE to_type = 'Team'  AND to_employee_master='{$_SESSION['client_id']}' AND help.status = 'unread' ";     
							break;
						case "SENT": 
							$sql = "SELECT help.*, COALESCE (users.name, concat(employees.first_name, ' ', employees.last_name),concat(employees_master.first_name, ' ', employees_master.last_name),corporate.name, locations.name, clients.name) as sender ,
                                                            COALESCE ( employees.image,corporate.image, locations.image, clients.image, employees_master.image, empmm.image) as senderimage 
                                                       FROM help LEFT JOIN users on help.from_admin = users.id LEFT JOIN employees_master empmm ON users.email = empmm.email
                                                       LEFT JOIN employees on employees.id = help.from_employee 
                                                        LEFT JOIN corporate on corporate.id = help.from_corp 
                                                       Left JOIN clients on clients.id = help.from_client  
                                                       LEFT JOIN locations on locations.id = help.from_location
                                                       LEFT JOIN employees_master on employees_master.empmaster_id = help.from_employee_master
                                                       WHERE from_type = 'Team'  AND from_employee_master='{$_SESSION['client_id']}'"; 
							break;
						case "READ": 
							$sql = "SELECT help.*, COALESCE (users.name, concat(employees.first_name, ' ', employees.last_name),concat(employees_master.first_name, ' ', employees_master.last_name),corporate.name, locations.name, clients.name) as sender ,
                                                            COALESCE ( employees.image,corporate.image, locations.image, clients.image, employees_master.image, empmm.image) as senderimage 
                                                       FROM help LEFT JOIN users on help.from_admin = users.id LEFT JOIN employees_master empmm ON users.email = empmm.email
                                                       LEFT JOIN employees on employees.id = help.from_employee 
                                                        LEFT JOIN corporate on corporate.id = help.from_corp 
                                                       Left JOIN clients on clients.id = help.from_client  
                                                       LEFT JOIN locations on locations.id = help.from_location
                                                       LEFT JOIN employees_master on employees_master.empmaster_id = help.from_employee_master
                                                       WHERE to_type = 'Team' AND to_employee_master='{$_SESSION['client_id']}' AND help.status = 'read'  "; 
							break;
						default: 
							$sql = "SELECT help.*, COALESCE (users.name, concat(employees.first_name, ' ', employees.last_name),concat(employees_master.first_name, ' ', employees_master.last_name),corporate.name, locations.name, clients.name, 'Admin') as sender ,
                                                            COALESCE ( employees.image,corporate.image, locations.image, clients.image, employees_master.image, empmm.image) as senderimage 
                                                       FROM help LEFT JOIN users on help.from_admin = users.id LEFT JOIN employees_master empmm ON users.email = empmm.email
                                                       LEFT JOIN employees on employees.id = help.from_employee 
                                                        LEFT JOIN corporate on corporate.id = help.from_corp 
                                                       Left JOIN clients on clients.id = help.from_client  
                                                       LEFT JOIN locations on locations.id = help.from_location
                                                       LEFT JOIN employees_master on employees_master.empmaster_id = help.from_employee_master
                                                       WHERE to_type = 'Team' AND to_employee_master='{$_SESSION['client_id']}' AND help.status = 'unread' "; 
							break;
					 }
                     if ($_POST["msgsearch"]!=""){
                          $sqlsearch = " AND (help.topic LIKE '%{$_POST["msgsearch"]}%' OR help.message LIKE '%{$_POST["msgsearch"]}%'  OR employees.first_name  LIKE '%{$_POST["msgsearch"]}%' OR employees.last_name  LIKE '%{$_POST["msgsearch"]}%' OR users.user  LIKE '%{$_POST["msgsearch"]}%' OR locations.name  LIKE '%{$_POST["msgsearch"]}%' OR clients.name  LIKE '%{$_POST["msgsearch"]}%' OR corporate.name  LIKE '%{$_POST["msgsearch"]})%' ";
			$sql .= $sqlsearch;			 
                     }
				         
			$sql .= " ORDER BY help.sent_datetime DESC";		
                    $result = mysql_query($sql);
                    if (mysql_num_rows($result)>0){
                            ?>
                            <ul class="msglist">
                               <?php 
                               $firstRecord = True;
                               $msgstyle = "";
                               while ($row=mysql_fetch_assoc($result)){
                                   if ($row["status"]=='unread' && ($_POST["hdnCurrentTab"]=="INBOX" || $_POST["hdnCurrentTab"]=="")){
                                       $msgstyle = "selected"; 
                                       //$firstRecord = False;
                                   }else {   
                                       $msgstyle = "unread"; 
                                   }
                                 ?>
                                <li class="<?php echo $msgstyle;?> getmessage" data-id="<?php echo $row["help_id"];?>" data-subject="<?php echo $row["topic"]; ?>" data-status="<?php echo $row["status"];?>" id="message<?php echo $row["help_id"];?>">
                                    <?php if ($row["senderimage"]!=""){?>
                                    <div class="thumb"><img src="<?php echo API. "images/". $row["senderimage"];?>" style="width:40px;height:40px;" alt="" /></div>
                                    <?php }else{?>
                                    <!--<div class="thumb"><img src="images/Default - User - thumb.png" alt="" /></div>-->
									<div class="thumb"><img src="images/Default - User - thumb.png" alt="" /></div>
                                    <?php }?>
                                    <div class="summary" style="max-height: 55px;">
									<span class="date pull-right"><small><?php echo date("Y-m-d H:i",strtotime($row["sent_datetime"]));?> <?php //echo Date("H:i",strtotime($row["time"]));?></small></span>
										<p style="margin-top:0px;"><strong style="line-height:15px;"><?php echo $row["sender"];//echo substr($row["location_name"],0,26);?></strong></p>
										
                                        
                                        <p  style="margin-top:0px; " ><strong style="line-height:15px;"><?php if ($row["topic"] == "") {echo "No Subject";} else echo substr($row["topic"],0,50) ;?></strong> </p>
                                        <p  style="margin-top:0px;"><strong style="line-height:15px;"><?php echo substr($row["message"],0,18);?>..</strong></p>
										
                                    </div>
                                </li>
                                <?php $msgstyle = ""; }?>
                            </ul>
                         
							<input type="hidden" name="sqlUserFilter" id="sqlUserFilter" value="<?php echo $sqlUserFilter;?>" />
							<input type="hidden" name="sqlUserEmployeeFilter" id="sqlUserEmployeeFilter" value="<?php echo $sqlUserEmployeeFilter;?>" />
							<input type="hidden" name="sqlStatusFilter" id="sqlStatusFilter" value="<?php echo $sqlStatusFilter;?>" />
							<input type="hidden" name="sqlsearch" id="sqlsearch" value="<?php echo $sqlsearch;?>" />
                        </div><!--messageleft-->
						
						<?php } else { $test=1;} ?>
						
						<?php if($test==1){ echo "<ul class='msglist'><li><div style='text-align:center;padding-top: 20px;font-size: 15px;'>No Messages Found.</div></li></ul></div>";}?>
                        <div class="messageright" style="height:640px">
                            <div class="messageview">
                                  
                             
                            </div><!--messageview-->
                            
                            <div class="msgreply" style="display:none;">
                                <input type="hidden" id="currentmessageid">
                                <input type="hidden" id="currentmessagesubject">
                              
                                <div class="reply" style="margin-left:2px;">
                                    <textarea placeholder="Type something here to reply" name="newreply" id="newreply" style="margin-left: 8px;width:99%" ></textarea>
                                
                                </div><!--reply-->
				<div class="thumb" style="padding-left: 10px;width: 40px;height: 40px;float: left;"><button class="btn btn-primary alertinfo" onClick="sendReply();" style="background: #0041a0;border-color: #4a96d1;padding: 5px 12px 5px;">Submit</button></div>				 
                           </div><!--messagereply-->
                            
                        </div><!--messageright-->
						 
                    </div><!--messagecontent-->
                    
                </div>
                <?php require_once('require/footer.php');?>
    <!--footer-->
                
            </div><!--maincontentinner-->
        </div><!--maincontent-->
        
    </div><!--rightpanel-->
    
</div><!--mainwrapper-->
<div id="img-popup" class="modal in fade"  style="z-index:4000;width: 725px;display:none;">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> × </button>
            <h3 id="myModalLabel">Attachment</h3>
        </div>
        <div class="modal-body" id="attachbody">
           
        </div>
        <div class="modal-footer" style="text-align: center;">
            <p class="stdformbutton">
                <button id="btnClose" data-dismiss="modal" class="btn btn-primary">Cancel</button>
                <button type="submit" id="saveattachment" name="saveattachment"  class="btn btn-primary">Submit</button>
            </p>
	</div>
    </div>
<div id="add-popup" class="modal hide fade"  >
    <form id="frmAddMessage" name="frmAddMessage" action="" method="post" class="" ENCTYPE="multipart/form-data">
    <input type="hidden" name="loc_id" id="loc_id" value="">
	<input type="hidden" name="hdnTxtSubject" id="hdnTxtSubject" value="">
	<input type="hidden" name="hdnMsgText" id="hdnMsgText" value="">	
	
	
	<div class="modal-header" >
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3>Add Message</h3>
	</div>
	<div class="modal-body">
        <table width="100%" height="100%">
           <tr>
                <td width="30%" style="vertical-align: top;">
                    <div class="rows_pop control-group" style="padding-top: 5px"><b>Ticket Number:</b> <font color="Red">*</font></div>
                </td>
                <td width="70%">
                    <div class="rows_pop control-group">
                    <?php 
						$query_count = mysql_fetch_array(mysql_query("SELECT COALESCE(count(help_id),0)+1 as counted from help where DATE_FORMAT(sent_datetime,'%Y-%m-%d') ='".date('Y-m-d')."'"));
						$ticket = $_SESSION['empmaster_id'].'-'.date('Y-m-d').'-'.$query_count['counted'];
					?>
                        <input type="text" name="ticket_number" value="<?php echo $ticket; ?>" id="ticket_number" readonly placeholder="Ticket Number" title="Ticket Number" style="width:310px;" >
                    </div>
                </td>
            </tr>
            <tr>
                <td width="30%" style="vertical-align: top;">
                    <div class="rows_pop control-group" style="padding-top: 5px"><b>Subject:</b> <font color="Red">*</font></div>
                </td>
                <td width="70%">
                    <div class="rows_pop control-group">
                        <input type="text" name="txtSubject" value="" id="txtSubject" placeholder="Subject" title="Subject" style="width:310px;" >
                    </div>
                </td>
            </tr>
            <tr>
                <td width="30%" style="vertical-align: top;">
                    <div class="rows_pop control-group"><b>Message:</b> <font color="Red">*</font></div>
                </td>
                <td width="70%" style="vertical-align: top;" required="required">
                     <textarea style="width: 310px;height: 227px;resize: none" id="txtMessage" name="txtMessage"></textarea>
                </td>				
            </tr>
           
            <tr>
                <td width="30%" style="vertical-align: top;">
                    <div class="rows_pop control-group"><b>Attachment:</b></div>
                </td>
                <td width="70%" style="vertical-align: top;">
                    <div id="attachment" style="padding-bottom: 5px;"></div>                     
                     <input type="button" name="upload_image" id="upload_image" value="Upload File" class="submit-green btn btn-primary">
					 <span style="display:none">	<input type="file" name="attach" id="attach"/></span>
                     <input type="hidden" name="attachedfile" id="attachedfile" />
                </td>				
            </tr>
        </table>
	</div>
	<div class="modal-footer" style="text-align: center;">
		<p class="stdformbutton">
          <button id="btnCancel" data-dismiss="modal" class="btn" style="color:#333333 !important;">Cancel</button>
          <button type="submit" id="AddMessage" name="AddMessage"  class="btn btn-primary">Submit</button>
        </p>
	</div>
        </form>    
</div> 
    
</div><!--mainwrapper-->



<script type="text/javascript">
  
     
jQuery(document).ready(function(){
        jQuery("#upload_image").click(function(){
           // jQuery("#attach").trigger("click");
           var url = "upload_help_attachment.php";
             jQuery.get(url, function (data) {
                 jQuery("#attachbody ").html(data);
                 jQuery("#img-popup").modal('show');
                 jQuery("#img-popup").show();
		jQuery("#opaque").show();
             });
           
        })
        jQuery("#btnClose").click(function(){
		jQuery("#img-popup").hide();
		jQuery("#opaque").hide();
	});
        jQuery('#img-popup').on('hide', function () {
           
                jQuery("#opaque").hide();
        })
        jQuery('#attach').live('change', function(){ 
            var filename = jQuery('#attach').val();
			alert(filename);
            if (filename!=""){
                filename = filename.split("\\");
                jQuery('#attachment').html("<img src='images/attach.jpeg' width='21px'>"+filename[filename.length-1]);
            }
            else {
                jQuery('#attachment').html("");
            }
        });
  
 }); 
jQuery(document).ready(function(){
     jQuery("#type").on("change", function(){
         var type = jQuery("#type").val();
         switch (type){
             case "Admin":
                 jQuery(".getdata").hide();
                 jQuery(".admin").show();
                 break;
            case "Location":
                jQuery(".getdata").hide();
                 jQuery(".location").show();
                
                 break;
                 
             case "Team":
                 jQuery(".getdata").hide();
                 jQuery(".master").show();
                 
                 break;
             case "Client":
                 jQuery(".getdata").hide();
                 jQuery(".client").show();
                 
                 break;
                 
              case "Corp":
                 jQuery(".getdata").hide();
                 jQuery(".corporate").show();
                 
                 break;   
             default:    
         }
     });
	
	 jQuery('#add-popup').on('hide', function () {
		jQuery("#locations").val("");
		jQuery("#loc_id").val("");
		jQuery("#txtSubject").val("");
		jQuery("#txtSubject").val("");
	 });
	 
    jQuery('.msglist li').live("click",function(){
        jQuery('.msglist li').each(function(){ jQuery(this).removeClass('graymsg')});
        jQuery(this).addClass('graymsg');
        var msgid = jQuery(this).data('id') ;
        var msgsubject = jQuery(this).data('subject');
        var msgstatus = jQuery(this).data('status');
        jQuery(this).removeClass('selected').addClass('unread');
        jQuery("#currentmessageid").val(msgid);
        jQuery.ajax({
            type: "POST",
            url: "help/ajax_help_message.php",
            data: {msgid:msgid,subject:msgsubject},
			beforeSend:function(){
				jQuery(".messageview").html('<div style="margin-top:50px; "><div class="progress progress-striped active"><div class="bar" style="margin-left:1%; margin-right:1%; width: 98%;"></div></div></div>');
			},
            success: function( data ){
                    jQuery('.messageview').html(data);
					
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
                    if (msgstatus=='unread' && jQuery("#hdnCurrentTab").val()!="SENT" && jQuery("#hdnCurrentTab").val()!="READ"){
                        jQuery.ajax({
                        type: "POST",
                        url: "help/ajax_help_read_message.php",
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
	
	//Submit button on AddMessage popup
	/*jQuery('#frmAddMessage').submit(function(){
        jQuery("#hdnTxtSubject").val(jQuery("#txtSubject").val());
		jQuery("#hdnMsgText").val(jQuery("#txtMessage").val());
	});*/
	jQuery('#AddMessage').click(function(){
            
		if(jQuery('#txtSubject').val() == "")
		{
			jAlert('Please enter subject','Alert Dialog');
			return false;
		}else if(jQuery('#txtMessage').val() == "")
		{
			jAlert('Please enter message','Alert Dialog');
			return false;
		}
		else
		{
			jQuery("#hdnTxtSubject").val(jQuery("#txtSubject").val());
			jQuery("#hdnMsgText").val(jQuery("#txtMessage").val());
                        jQuery('#frmAddMessage').submit();
                        
		}
	});
	
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
	
	jQuery("#popup_location").change(function(){
		jQuery.ajax({
            type: "POST",
            url: "ajax/employees_location.php",
            data: {location_id:jQuery(this).val()},
            success: function( data ){
                    jQuery(".formwrapper").html(data);
					jQuery(".chzn-select").chosen();
					jQuery(".chzn-select").trigger("chosen:updated");
            }
        }); 
	});
	jQuery("#msgsearch").keyup(function(){
		msgsearch=jQuery("#msgsearch").val();
		if (msgsearch.length>3 || msgsearch.length==0)
			SearchMessages(msgsearch);
	});
	
        
});


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
            url: "help/ajax_help_message.php",
            data: {msgid:msgid,subject:msgsubject},
            success: function( data ){
                    jQuery('.messageview').html(data);
                    jQuery('.msgreply').css("display","");
                    if (msgstatus=='unread' && jQuery("#hdnCurrentTab").val()!="SENT" && jQuery("#hdnCurrentTab").val()!="READ"){
                        jQuery.ajax({
                        type: "POST",
                        url: "help/ajax_help_read_message.php",
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
function sendReply(){

var msgid = jQuery('#currentmessageid').val();

if (jQuery('#newreply').val()!=""){
    jQuery.ajax({
                type: "POST",
                url: "help/ajax_reply_message.php",
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
                            url: "help/ajax_refresh_messages.php",
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

function SearchMessages(msgsearch)
{
	sqlUserFilter=jQuery("#sqlUserFilter").val();
		sqlStatusFilter=jQuery("#sqlStatusFilter").val();
		sqlUserEmployeeFilter=jQuery("#sqlUserEmployeeFilter").val();
		sqlsearch = " AND (help.topic LIKE '%"+msgsearch+"%' OR help.message LIKE '%"+msgsearch+"%' OR employees.first_name  LIKE '%"+msgsearch+"%' OR employees.last_name  LIKE '%"+msgsearch+"%' OR users.name  LIKE '%"+msgsearch+"%' OR locations.name  LIKE '%"+msgsearch+"%' OR clients.name  LIKE '%"+msgsearch+"%'  OR corporate.name  LIKE '%"+msgsearch+"%' OR employees_master.first_name  LIKE '%"+msgsearch+"%' OR employees_master.last_name  LIKE '%"+msgsearch+"%')";
		//sqlsearch2 = " AND (lm.subject LIKE '%"+msgsearch+"%' OR lm.message LIKE '%"+msgsearch+"%' OR lo.name  LIKE '%"+msgsearch+"%' OR concat(emp.first_name,' ',emp.last_name)  LIKE '%"+msgsearch+"%' ) ";
						
        jQuery.ajax({
                type: "POST",
                url: "help/ajax_refresh_messages.php",
                data: {sqlUserFilter:sqlUserFilter,sqlStatusFilter:sqlStatusFilter,sqlUserEmployeeFilter:sqlUserEmployeeFilter,sqlsearch:sqlsearch,hdnCurrentTab:'<?php echo $_POST["hdnCurrentTab"]?>'},
							success: function(data){
                                jQuery(".msglist").html(data);
								RegisterMessageClick();
                            }
                         });
}


</script>

</body>
</html>
