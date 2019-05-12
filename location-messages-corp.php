<?php                       require_once 'require/security.php';
							include 'config/accessConfig.php';
							$uid=$_GET["uid"];
							$corporate_id=$_GET["location_id"];
							$status=$_GET["status"];
							$name=$_GET["name"];
							
							$tab=$_GET["tab"];
							
							$remiders = $_GET["reminder"];
							
							$checkReminderCount = 1;
							if($tab == 1)
								$checkReminderCount = 0;
							
							$by_user_id = "";
							if($remiders=='my' || $remiders==''){
								$by_user_id = " AND us.id=".$uid;
							} else {
								$by_user_id="";
							}
							
							 $sql = "SELECT lo.corp_msg_id,lo.corp_msg_id as lmid,lo.type_of_message,lo.email_type,lo.status,lo.message,lo.subject,
							 DATE(lo.created_datetime) as created_datetime,lo.created_by,lo.reminder_date,us.name,us.user,us.email,
							 DATE_FORMAT(lo.created_datetime,'%Y-%m-%d %H:%i') as created_date,
							 CONCAT(UCASE(LEFT(lo.type_of_message, 1)), SUBSTRING(lo.type_of_message, 2)) as type,
							 DATE_FORMAT(lo.read_datetime,'%Y-%m-%d %H:%i') as read_datetime,
							 us1.name as read_by_admin ,us2.name as read_by_employee ,lo.file_loc as file_loc,c.name as bName
							 from corporate_messages lo 
							 left join users us on lo.created_by=us.id 
							 left join users us1 on lo.read_by_employee_id=us1.id
							 left join users us2 on lo.read_by_admin_id=us2.id
							 LEFT JOIN corporate as c ON c.id = lo.corporate_id
							 where lo.corporate_id='$corporate_id' $by_user_id order by lo.created_datetime desc";
							 
							 echo (isset($_REQUEST['debug'])) ? $sql : '';
							 
                            $result2 = mysql_query($sql);
								
							$numRows = mysql_num_rows($result2);
							
							/*if($numRows > 0 && $checkReminderCount == 0)
							{
								while($rowChk = mysql_fetch_array($result2))
								{
									if($rowChk['reminder_date'] == '0000-00-00')
										continue;
									else
									{
										$checkReminderCount++;
										break;
									}
										
								}
								
							}*/
							
							if($numRows > 0 /*&& $checkReminderCount >0*/){	
							
							  $result = mysql_query($sql);	
								
								?>
								<table id="dyntableright" class="table table-bordered responsive">
								<colgroup>
									<col class="con0" style="width:5%;"/>
									<col class="con1" style="width:15%;"/>
									<col class="con0" style="width:17%;"/> 
									<col class="con1" style="width:40%;" />
									<col class="con0" style="width:8%;" />
									<col class="con1" style="width:2%"/>
								</colgroup>
								<thead>
								<tr>
									<th style="text-align:left !important;">S</th>
									<th style="text-align:center !important;">Date / <br> Reminder</th>
									<th style="text-align:left !important;">Type / <br> Subject</th>									
									<!-- <th style="text-align:left !important;">Subject</th>  -->
									<th style="text-align:left !important;">Message</th>
									<th style="text-align:left !important;">Sent By</th>
									<th style="text-align:left;">A</th>
								</tr>
								</thead>
								<tbody>
							<?php
							$i = 1; $j=1; $k=1; $l=1;
							while($row = mysql_fetch_array($result)){
							//array_push($arr,$row);
							$dateDisplay = date("m-d-Y / H:i" , strtotime($row['date']." ".$row['time']));
					
							
							if($row['email_type'] == "first_contact_email") 
							{
								$emailtype = "First Contact Email";
							}
							else if($row['email_type'] == "first_contact_email_sultry")
							{
								$emailtype = "First Contact Email";
							}
							else if($row['email_type'] == "BPhelp")
							{
								$emailtype = "BP Sign Up Follow Up Help";
							}
							else if ($row['email_type'] == "softfork_surveyed")
							{
								$emailtype = "SoftFork -  1st Survey";
							}
							
							else if ($row['email_type'] == "visit")
							{
								$emailtype = "Visit";
							}
							
							else if ($row['email_type'] == "message")
							{
								$emailtype = "Internal Message";
							}
							
							else if ($row['email_type'] == "call")
							{
								$emailtype = "Call";
							}
							
							else if ($row['email_type'] == "email")
							{
								$emailtype = "Email";
							}
							
							else if ($row['email_type'] == "surveyed")
							{
								$emailtype = "Surveyed";
							}
							
							
							
							else if ($row['email_type'] == "pmb")
							{
								$emailtype = "PMB";
							}
							else if($row['email_type'] == "manual")
							{
								$emailtype = "Manual Email";
							}
							else if($row['email_type'] == "BarPoint")
							{
								$emailtype = "SoftPoint - Introducing - BarPoint";
							}
							else if($row['email_type'] == "CorporatePoint")
							{
								$emailtype = "SoftPoint - Introducing - CorporatePoint";
							}
							else if($row['email_type'] == "ExpenseTAB")
							{
								$emailtype = "SoftPoint - Introducing - ExpenseTAB";
							}
							else if($row['email_type'] == "HotelPoint")
							{
								$emailtype = "SoftPoint - Introducing - HotelPoint";
							}
							else if($row['email_type'] == "LearnTube")
							{
								$emailtype = "SoftPoint - Introducing - LearnTube";
							}
							else if($row['email_type'] == "POSPoint")
							{
								$emailtype = "SoftPoint - Introducing - POSPoint";
							}
							else if($row['email_type'] == "RegisterPoint")
							{
								$emailtype = "SoftPoint - Introducing - RegisterPoint";
							}
							else if($row['email_type'] == "ResvPoint")
							{
								$emailtype = "SoftPoint - Introducing - StaffPoint";
							}
							else
							{
								$emailtype = "";
							}
							
							
							if($row['type'] == "Visit") 
							{
								$type = "visit";
								$type_show = "Visit";
							}
							elseif($row['type'] == "Message") 
							{
								$type = "message";
								$type_show = "Internal Message";
							}
							elseif($row['type'] == "Call") 
							{
								$type = "call";
								$type_show = "Call";
							}
							elseif($row['type'] == "Pmb" || $row['type'] == "pmb" || $row['type'] == "PMB") 
							{
								$type = "pmb";
								$type_show = "PMB";
							}
							elseif($row['type'] == "Email") 
							{
								$type = "email";
								$type_show = "Email";
							}
							elseif($row['type'] == "Surveyed") 
							{
								$type = "surveyed";
								$type_show = "Surveyed";
							}
							elseif($row['type'] == "Manual") 
							{
								$type = "manual";
								$type_show = "Manual Email";
							}
							elseif($row['type'] == "Contract") 
							{
								$type = "contract";
								$type_show = "Contract";
							}
							elseif($row['type'] == "Proposal") 
							{
								$type = "proposal";
								$type_show = "Proposal";
							}
							elseif($row['type'] == "Webinar") 
							{
								$type = "webinar";
								$type_show = "Webinar";
							}
							else
							{
								$type = '';
								$type_show = '';
							}
							
							//$message = strip_tags($row['message']);
							
							$org_message = $row['message'];
							$wordCount = substr_count($row['message'],'/>');
							$wordCount2 = substr_count($row['message'],'</');
							$org_message = str_replace("<p>&nbsp;</p>","",$org_message);
							$message = strip_tags($org_message);
							
							if($wordCount > 3 || $wordCount2 > 3)
							{
								$showMessage = $row['message'];
								$class = 'class="trimwords"';
								$dots = "<div style='font: bold 12px/30px Georgia, serif'>...</div>";
							}
							else
							{
							   $showMessage = $org_message;
							   $class = '';
							   $dots = "";
							}
						
							?>
							<tr onclick="jQuery(this).css('background','gray').siblings().css('background','white');" rel="<?php echo $i++;?>" class="codedatamessage gridr<?php echo $j++;?>" data-lmid="<? echo $row['lmid'];?>" 
								data-file_loc="<? echo $row['file_loc'];?>" 
								data-id="<? echo $row['corp_msg_id'];?>" 
								data-bid="<? echo $corporate_id;?>" 
								data-bName="<? echo $row['bName'];?>" 
								data-rem_date="<? echo $row['reminder_date'];?>" 
								data-type="<? echo ($row['type'] == "Pmb") ? "PMB" : strtolower($type);?>" 
								data-message_type="Corporate" 
								data-message="<? echo htmlentities($row['message']);?>" 
								data-m_status="<? echo $row['status'];?>" 
								data-read_by="<? echo ($row['read_by_admin']!='') ? $row['read_by_admin'] : $row['read_by_employee'] ;?>" 
								data-read_date="<? echo $row['read_datetime'];?>" 
								data-Created_by="<? echo $row['name'];?>" 
								data-Created_date="<? echo $row['created_date'];?>" 
								data-subject1="<? echo $row['subject'];?>" 
								data-type_of_message="<? echo strtolower($row['type_of_message']);?>"
								data-sent_by = "<?=$row['created_by'];?>" 
								data-sent_by = "<?=$row['sent_by'];?>"
								data-email = "<?=$row['email'];?>"								
								href="#login-box21" 
								data-toggle="modal" class="codedatamessage">
							
							<td class="center" style="text-align:center !important;vertical-align: middle !important;">
								<?php
                                        switch ($row['status']) {
											   case "":
											   case "read":
                                                    echo "<img title='Read' src='images/Active-Corrected-Delivered-16.png' />";
                                                    break;
                                               case "unread":
                                                     echo "<img title='Unread' src='images/Inroute_16.png' />";
                                                     break;
                                        }
                                        
                                  ?>
								</td>
								<td style="word-wrap: break-word;text-align:center !important;vertical-align: middle !important;">
								<?php 	if(trim($row['reminder_date'])=='0000-00-00'){$row['reminder_date']='';} 
										if(trim($row['created_datetime'])=='0000-00-00'){$row['created_datetime']='';}
								?>
								<?php echo '<span datetime="'.$row['created_date'].'">'.$row['created_datetime'].'</span>'; echo ($row['reminder_date'] !='0000-00-00') ? "<br>"."<span style='color:red;'>".$row['reminder_date']."</span>" : "";?></td>
								<td style="text-align:left !important;"><?php echo ($row['type'] == "Pmb") ? "PMB <br>".$row['subject'] : $type_show."<br>".$row['subject']; ?></td>
								
							<!--	<td style="text-align:left !important;" class="word_break"><?php echo $row['subject'];?></td> -->
                                <?php //echo $showMessage; ?>
								<td style="text-align:left !important;" <?php echo $class;?> class="word_break"><?php echo  $showMessage.$dots;?></td>
								<td style="text-align:center !important;vertical-align: middle !important;"> <?php echo $row['user'];?></td>
								<td style="text-align:center !important;min-width:18px;vertical-align: middle !important;" class="center">
								<a style="text-decoration:none;" 
								><img title="Edit this" src="images/Edit.png"></a></td>
							</tr> 
							<?php }
							echo "</tbody></table>";
							} else if(isset($_POST['statusEmpV'])) {
								echo "<div style='font-size:15pt;text-align:center;padding:10px;'>No Results</div>";
							} else{
								echo "<div style='font-size:15pt;text-align:center;padding:10px;'>No Active Queue's</div>";
							} ?>
                            
<style>
.dataTables_info {
    /*padding: 18px;*/
}
#dyntableright_paginate{
	bottom:12px;
}
/*#dyntableright
{
 overflow-x: auto !important;
    max-width:100%;
	width:100%;
	display: block;
}*/
.trimwords {
    display: block;
    width: 90%;    
    height: 6.5em !important;
    overflow: hidden;
    position: relative;
}
tr.selected{
    background: gray;
}
                           

</style>

<script type="text/javascript">


	function Editr(elem, rowid , type) {

       var locationID = jQuery(elem).data("id");
	   var tabselected = '';
	   

	   <?php if (isset($_GET["tabselected"])) {?>
	   	tabselected = 'location';
	   <?php } ?>
	
		/*if(jQuery(elem).parent('tr').data('is_corp') == true && typeof jQuery(elem).parent('tr').data('is_corp') != undefined) {
			url = "location-messages-corp.php";
		} else {
			url = "location-messages.php";
		}*/
	
        //alert(rowid);
		
		if(tabselected !=='')
        	jQuery('.secondtab').css('background', 'none');
		
		if(type == 1){
        	jQuery('#dyntableright tr').css('background', 'none');
            jQuery(".gridr" + rowid).css('background', 'gray');
        }else{
			jQuery('#dyntable2 tr').css('background', 'none');
            jQuery(".gridc" + rowid).css('background', 'gray');
        }
		
		 jQuery('.ediBtnn' + rowid).click();
		 jQuery("#gray_tr").val(locationID);
    }


     jQuery("#dyntableright").on('click','tr',function () {
            //alert(jQuery(this).css('background-color')=='rgb(128, 128, 128)');
            var elem = jQuery(this).find(".codedatar");
            var rowid = jQuery(this).attr("rel");
			
			//Editr(elem, rowid , 1);

			jQuery(this).addClass("selected").siblings().removeClass("selected");
        
			
        });



/*
	jQuery('tr').on('click', function(){
	    jQuery(this).addClass("selected");
	});
	jQuery('tr').removeClass("selected")*/
</script>