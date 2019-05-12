<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

//require_once "connect.php";
require('phpmailer-schedulle/class.phpmailer.php');
require('phpmailer-schedulle/language/phpmailer.lang-en.php');
require_once 'require/security.php';
include_once 'config/accessConfig.php';
include("init.php");

$from_email = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


	$dato_1 = $_POST['availability_type'];
	$dato_2 = $_POST['request_type'];
	//$dato_3 = $_POST['location_id'];
	$dato_4 = $_POST['message'];
	$dato_5 = $_POST['start_time'];
	$dato_6 = $_POST['end_time'];
	$dato_7 = $_POST['start_date'];
	$dato_8 = $_POST['end_date'];






	// Query is manager returns Yes or No
	/*("select employees.manager from employees_master inner join employees on employees.email= employees_master.email and employees.location_id = ".$_POST['location_id']." and employees_master.email = 'pi@softpointcloud.com'");*/
	$sql = "select employees.manager,employees.first_name, employees.last_name, employees.emp_id from employees_master inner join employees on employees.email= employees_master.email and employees.location_id = ".$_POST['location_id']." and employees_master.email = '".$_POST['email']."'";

	//echo "<br><br> el sql". $sql;

	$resultado = mysql_query($sql);

	while ($fila = mysql_fetch_array($resultado, MYSQL_NUM)) {
	    if ($fila[0]!="Yes") {


	    	
	    		


	    	$sql2 = "select employees.email,employees.first_name,employees.emp_id from employees_master inner join employees on employees.email= employees_master.email and employees.manager = 'Yes' and employees.location_id = ".$_POST['location_id'];
	    	$resultado2 = mysql_query($sql2);
			while ($fila2 = mysql_fetch_array($resultado2, MYSQL_NUM)) {

				/* Mensjae al inbox */
/*
	    	$employeeid = $fila[3];
	    	$subject_inbox = 'New Employee request';
			$messagebody_inbox = '<p style="font-size: 14px;text-align:justify;">New Request from employee. </p><p><span style="font-size: 14px;"><h2>Information of the Employee</h2>Name: <strong>'.$fila[2].'</strong><br>Location: <strong>'.$fila[2].'</strong><br>Department: <strong>Front of the house</strong><br><h2>Information of the Request</h2>Availability Type: <strong>'.$dato_1.'</strong><br>Request Type: <strong>'.$dato_2.'</strong><br>Message: <strong>'.$dato_4.'</strong><br>Start date : <strong>'.$dato_5.":".$dato_6.'</strong><br>End date : <strong>'.$dato_7.":".$dato_8.'</strong><br></span></p>';
			$priority_inbox = 'High';
			$selectedOption = $fila2[2];
			$sqlthread = "select max(thread_id) as maxthread from employee_messages";
                $resultthread = mysql_query($sqlthread);
                $rowthread = mysql_fetch_assoc($resultthread);
                $maxthread = $rowthread["maxthread"];
                if ($maxthread==""){
                    $maxthread = 1;
                }else $maxthread = intval($maxthread)+1;

	    	 $managrsquery = "SELECT id FROM EMPLOYEES WHERE location_id = '".mysql_real_escape_string($_POST["location_id"])."' AND manager = 'Yes'";
	            $result_managrsquery = mysql_query($managrsquery);
	            while ($rowwww = mysql_fetch_array($result_managrsquery)) {
	                
	                $newsql = "INSERT INTO employee_messages (location_id,entered_by_emp_id,Subject,message,date,time,emp_id,readd,Message_type,priority,thread_id) 
	            VALUES ('".mysql_real_escape_string($_POST["location_id"])."','{$employeeid}','{$subject_inbox}','{$messagebody_inbox}',DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(NOW(),'%H:%i:%s'),'{$selectedOption}','no','Location','{$priority_inbox}',{$maxthread})";
	            $resulttt2 = mysql_query($newsql) or die(mysql_error());
	            }*/


	            /* Fin */
			    	

				$to = $fila2[0];

					$subject = 'New Employee request';
					        $subjectClient = 'New Employee request';
						$htmlContent = "You have received the following New Employee request from the SoftPoint website!";
					        $htmlContentClient = '<head>
					    <meta name="viewport" content="width=device-width">
					    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
					    <title>SoftPoint</title>
					    <style type="text/css" data-immutable="true">
					        @import url("https://fast.fonts.net/t/1.css?apiType=css&projectid=43d8124e-fdde-4bce-b6cb-c5496474db5a");
					        @font-face{
					            font-family:"MetroNova_n2";
					            src:url("http://chris-armstrong.com/fonts/c282ed6d-f289-4246-b9e9-ca23086465cb.eot?#iefix") format("eot")
					        }
					        @font-face{
					            font-family:"MetroNova";
					            src:url("http://chris-armstrong.com/fonts/c282ed6d-f289-4246-b9e9-ca23086465cb.eot?#iefix");
					            src:url("http://chris-armstrong.com/fonts/c282ed6d-f289-4246-b9e9-ca23086465cb.eot?#iefix") format("eot"),url("http://chris-armstrong.com/fonts/89af966e-d3e3-4c30-ac08-90d105427594.woff") format("woff"),url("http://chris-armstrong.com/fonts/5f070be2-fe7d-455e-a15b-6ce5ef5cdcae.ttf") format("truetype"),url("http://chris-armstrong.com/fonts/7c6fb5d5-d07d-4d88-8df5-61a0947d86c9.svg#7c6fb5d5-d07d-4d88-8df5-61a0947d86c9") format("svg");
					            font-weight: 200;
					            font-style: normal;
					        }
					        @font-face{
					            font-family:"MetroNova_n3";
					            src:url("http://chris-armstrong.com/fonts/c8c9aba5-f3a6-4e34-a524-70823be702f5.eot?#iefix") format("eot")
					        }
					        @font-face{
					            font-family:"MetroNova";
					            src:url("http://chris-armstrong.com/fonts/c8c9aba5-f3a6-4e34-a524-70823be702f5.eot?#iefix");
					            src:url("http://chris-armstrong.com/fonts/c8c9aba5-f3a6-4e34-a524-70823be702f5.eot?#iefix") format("eot"),url("http://chris-armstrong.com/fonts/969ff0b9-0f7c-4b65-a9c2-839849ffb133.woff") format("woff"),url("http://chris-armstrong.com/fonts/d7f3eee0-3187-4c2d-87e9-00a566a44133.ttf") format("truetype"),url("http://chris-armstrong.com/fonts/7eb485c4-0102-47f8-be76-8b02acb3f853.svg#7eb485c4-0102-47f8-be76-8b02acb3f853") format("svg");
					            font-weight: 300;
					            font-style: normal;
					        }
					    </style><style type="text/css">a,img{border:none}.btn-primary,a{text-decoration:none}.btn,.btn-primary{letter-spacing:.85px}.btn-primary,.reply,.ta-c{text-align:center}*{margin:0;padding:0;font-family:"Helvetica Neue",Helvetica,Helvetica,Arial,sans-serif;font-size:100%;line-height:25px}.btn-primary,h1,h2,h3{font-family:MetroNova,"Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;color:#212121}img{max-width:100%;display:block}body{-webkit-font-smoothing:antialiased;-webkit-text-size-adjust:none;width:100%!important;height:100%;color:#212121}a{color:#9BA826}.btn-primary{text-transform:uppercase;background-color:none;border:2px solid #cad568;padding:0 20px;line-height:42px;margin:auto;cursor:pointer;display:inline-block;border-radius:50px;white-space:nowrap;font-size:12px;font-weight:600}h1,h2,h3{margin:0;line-height:1.2;font-weight:200}h2{font-size:42px;line-height:50px;margin-top:0;padding-bottom:25px}h3{font-size:25px;line-height:37px}h4,ol,p,ul{font-size:16px}h4{line-height:25px;font-weight:700}ol,p,ul{margin:0 auto 20px;font-weight:400;color:#777;}.body h1,.btn{color:#212121}ol li,ul li{margin-left:5px;list-style-position:inside}.container{margin:0 auto;max-width:550px}.body,.footer,.header{padding:20px}.header{padding-bottom:0;}.header h1{margin:0}.body h1,.body p{margin:0 0 20px}..body h1{font-size:42px;line-height:50px}.btn{border:2px solid #CFCF6D;display:inline-block;padding:3px;font-weight:600;font-size:12px;line-height:15px;text-transform:uppercase}.footer img{margin-bottom:15px}.board-activity,.board-header,hr{margin-bottom:30px}.footer p{font-size:14px;color:#bbb}hr{border:none;border-bottom:1px solid #F2F2F2}p.small{font-size:14px;line-height:20px}p.x-small{font-size:12px;opacity:.6}p.x-small a{color:#777;text-decoration:underline}.board-activity td,.board-header td{padding:10px}.board-activity .avatar,.board-activity .board,.board-activity .line,.board-header .avatar,.board-header .board,.board-header .line{width:50px;padding:0}.board-activity .avatar img,.board-activity .board img,.board-activity .line img,.board-header .avatar img,.board-header .board img,.board-header .line img{margin-right:0;margin-bottom:0}.board-activity img,.board-header img{margin-right:20px;display:inline-block;vertical-align:top;max-width:113px;margin-bottom:20px}.board-activity .avatar,.board-header .avatar{border-radius:100%;width:50px;height:50px}.board-activity .line,.board-header .line{background:url(https://niice.co/assets/emails/bg-line.jpg) center 0 repeat-y}.board-activity{margin-bottom:50px}.board-activity tr:last-child .line{background:0 0}.reply{font-size:14px;opacity:.6}.board-invite{margin-bottom:30px}.board-invite hr{margin-bottom:0;border-bottom:2px dashed #777}.board-invite table{width:100%;table-layout:fixed}.board-invite table .board-invite__board{width:150px}.board-invite td{vertical-align:middle}.board-invite p{font-weight:700;margin-bottom:0;color:#212121}.board-invite img{display:inline}.board-invite .avatar{text-align:center;width:74px}.board-invite .avatar img{margin-top:20px}.board-invite .avatar p{text-overflow:ellipsis;overflow:hidden;width:72px;white-space:nowrap;font-size:14px;line-height:20px}</style></head>
					<body>
					    <div class="body">
					        <div class="container">
					            <h3>Hello ' .$fila2[1].'!</h3>

					            <p style="font-size: 14px;text-align:justify;">New Request from employee. </p>


					            <p>
					                <span style="font-size: 14px;">
						                <h2>Information of the Employee</h2>
						                 Name: <strong>'.$fila[1].'</strong><br>
						                  Location: <strong>'.$fila[2].'</strong><br>
						                   Department: <strong>Front of the house</strong><br>
						                   <h2>Information of the Request</h2>
						                    Availability Type: <strong>'.$dato_1.'</strong><br>
						                    Request Type: <strong>'.$dato_2.'</strong><br>
						                    Message: <strong>'.$dato_4.'</strong><br>
						                    Start date : <strong>'.$dato_5.":".$dato_6.'</strong><br>
						                    End date : <strong>'.$dato_7.":".$dato_8.'</strong><br></span>
					            </p>

					            <!--<p>
					                <span style="font-size: 14px;"><strong>Join from PC, Mac, Linux, iOS or Android : <a href="https://zoom.us/j/279909787"> Click Here to Join!!</a></strong> <br>
					                    <strong>iPhone one-tap:</strong>
					                    +1 669 900 6833,,279909787#, +1 646 558 8656,,279909787# (USA) <br>
					                    <strong> Telephone : </strong>Dial (for higher quality, dial a number based on your current location) :<br> 

					                    US : +1 669 900 6833, +1 646 558 8656</a> <br>
					                    <strong>Meeting ID : </strong>  
					                    279 909 787<br>
					                    <strong>International numbers available : <a href="https://zoom.us/u/rdusnZNQ"> Click Here to Join!!</a></strong> <br></span>
					            </p>-->
					            <p><span style="font-size: 15px;">(If you have any questions or feedback, just <a href="mailto:riv@softpointcloud.com" target="_blank">reply to this email</a>)</span></p> 
					            
					        </div>
					    </div>
					    <br> <br>

					    <div class="footer">
					        <div class="container">
					            <a href="https://softpoint.us/" target="_blank"><img src="https://www.softpointcloud.com/assets/base/img/layout/logos/logo-4.png" alt="SotPoint" style="width: 110px;"></a><br>

					           <!--<span style="color:#2E5C94; display: block;margin: -15px 0px;"><span style="font-size:22px;">Taylor Kuypers |</span>   Business Development & Innovation</span><br>
					            <span style="font-size:13px">
					                SoftPoint - Point Yourself In The Right Direction       
					                <br> P: (480) 745.3049 x107 | F: (480) 503.8023
					                <br>E: <a style="color:blue" href="mailto:kuypers@softpointcloud.com">kuypers@softpointcloud.com</a>
					                <br>W: <a style="color:#fc6805" href="www.softpointcloud.com ">www.softpointcloud.com </a><span>
					                    <div>
					                        <span  style="font-size:11px; text-align: justify; display: block; margin: 12px 0;"> THIS MESSAGE CONTAINS CONFIDENTIAL AND/OR PRIVILEGED INFORMATION. This information in it’s entirety is deemed confidential if the recipient is in any written or verbal agreement with SoftPoint. If you are not the addressee or authorized to receive this for the addressee, you are hereby notified that any dissemination, distribution or copying of this communication is strictly prohibited. If you have received this message in error, please advise the sender immediately by reply e-mail and delete this message. Thank you for your cooperation.</span>--> 
					                    </div>             
					        </div>
					    </div>
					</body>';







					//$to = 'jonnathangte68@hotmail.com';


					if($from_email==''){
							$from_email = 'noreply@softpointcloud.com';
						}
						if($from_name==''){
							$from_name = "Softpont";
						}
						$mail = new PHPmailer();
						$mail->IsSendmail();
						$mail->IsSMTP(); 
						$mail->SetLanguage("en", "phpmailer/language");
						$mail->From     = $from_email;
						$mail->FromName = $from_name;		
						$mail->SMTPAuth  =  "false";
						$mail->Subject  = $subject;
						$mail->Host = 'mail.softpointcloud.com';
						$mail->Port = 25;
						$mail->Username = 'noreply@softpointcloud.com';
						$mail->Password = 'NGVnazBhcmkzZngw';
						$mail->Body = $htmlContentClient;
						$mail->IsHTML(true);
						if($attachment!=''){
							$mail->AddAttachment($attachment);
						}
						//$mail->AddReplyTo($from_email, $from_name);
						$mail->AddAddress($to, 'Manager');		
						$sended = $mail->Send();
					return $sended;





			}


	    }

	}

	return "1";
}

?>