<?php 
include_once '../require/security.php';
include_once '../config/accessConfig.php';
if ($_POST["msgid"]!=""){

			$sql = "SELECT * FROM help WHERE help_id=".$_POST["msgid"];
            $result = mysql_query($sql);
            $rowmsg = mysql_fetch_array($result);
			
			$from_location = $rowmsg["from_location"];
            $to_location = $rowmsg["to_location"];
            $to_type = $rowmsg["to_type"];
            $from_type = $rowmsg["from_type"];
            $from_corp = $rowmsg["from_corp"];
            $to_corp = $rowmsg["to_corp"];
            $from_client = $rowmsg["from_client"];
            $to_client = $rowmsg["to_client"];
            $from_employee_master = $rowmsg["from_employee_master"];
            $to_employee_master = $rowmsg["to_employee_master"];
			$ticket = $rowmsg['Ticket'];
 
            $sql = "SELECT help.*, COALESCE (users.name, corporate.name, locations.name, concat(employees.first_name, ' ', employees.last_name),clients.name ,concat(employees_master.first_name, ' ', employees_master.last_name)) as sender , concat(employees.first_name, ' ', employees.last_name) as employeename ,
            COALESCE ( locations.image,employees.image,corporate.image,  clients.image, employees_master.image, empmm.image) as senderimage, 
            COALESCE (u.name, emp.emp_id,cor.name, loc.name, cli.name,concat(emp2.first_name, ' ', emp2.last_name)) as receiver 
            FROM help LEFT JOIN users on help.from_admin = users.id LEFT JOIN employees_master empmm ON users.email = empmm.email
            LEFT JOIN employees on employees.id = help.from_employee 
            LEFT JOIN corporate on corporate.id = help.from_corp 
            Left JOIN clients on clients.id = help.from_client  
            LEFT JOIN locations on locations.id = help.from_location
            LEFT JOIN employees_master on employees_master.empmaster_id = help.from_employee_master
                 LEFT JOIN users u on help.to_admin = u.id 
            LEFT JOIN employees emp on emp.id = help.to_employee 
            LEFT JOIN corporate cor on cor.id = help.to_corp 
            Left JOIN clients cli on cli.id = help.to_client  
            LEFT JOIN locations loc on loc.id = help.to_location
            LEFT JOIN employees_master emp2 on emp2.empmaster_id = help.to_employee_master
            WHERE (help.help_id='".mysql_real_escape_string($_POST["msgid"])."' OR (topic = '".mysql_real_escape_string($_POST["subject"])."' AND Ticket = '".mysql_real_escape_string($rowmsg['Ticket'])."')) "; 
			
			if ($to_type=="Location" || $from_type=="Location"){
                if ($from_location!="" && $from_location!=0){
                    $sql .= " AND ( to_location=".$from_location." OR from_location=".$from_location.") ";
                }

                if ($to_location!="" && $to_location!=0){
                        $sql .= " AND ( to_location=".$to_location." OR from_location=".$to_location.") ";
                }
            }
            
            if ($to_type=="Client" || $from_type=="Client"){
                if ($from_client!="" && $from_client!=0){
                    $sql .= " AND ( to_client=".$from_client." OR from_client=".$from_client.") ";
                }

                if ($to_client!="" && $to_client!=0){
                        $sql .= " AND ( to_client=".$to_client." OR from_client=".$to_client.") ";
                }
          
            }
            
            if ($to_type=="Corp" || $from_type=="Corp"){
                if ($from_corp!="" && $from_corp!=0){
                    $sql .= " AND ( to_corp=".$from_corp." OR from_corp=".$from_corp.") ";
                }

                if ($to_corp!="" && $to_corp!=0){
                        $sql .= " AND ( to_corp=".$to_corp." OR from_corp=".$to_corp.") ";
                }
            }
            
            if ($to_type=="Team" || $from_type=="Team"){
                if ($from_employee_master!="" && $from_employee_master!=0){
                    $sql .= " AND ( to_employee_master=".$from_employee_master." OR from_employee_master=".$from_employee_master.") ";
                }

                if ($to_employee_master!="" && $to_employee_master!=0){
                        $sql .= " AND ( to_employee_master=".$to_employee_master." OR from_employee_master=".$to_employee_master.") ";
                }
            } 
			
			$sql .= " ORDER BY help.sent_datetime DESC";   //AND (to_employee_master='{$_SESSION['client_id']}' OR from_employee_master='{$_SESSION['client_id']}')
                  //  echo $sql;
                      $result = mysql_query($sql);
                    if (mysql_num_rows($result)>0){
                                    ?>
                               
                                
                                <div class="btn-group pull-right">
                                    <button data-toggle="dropdown" class="btn dropdown-toggle" style="color:black !important;">Actions <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
										<!--
                                        <li><a href="#" id="forward" data-id="<?php echo mysql_real_escape_string($_POST["msgid"]);?>">Forward</a></li>
                                        <li><a href="#" id="delete" data-id="<?php echo mysql_real_escape_string($_POST["msgid"]);?>">Delete Message</a></li>
										-->
                                        <li><a href="#" id="print" data-id="<?php echo mysql_real_escape_string($_POST["msgid"]);?>">Print Message</a></li>
                                        <li><a href="#" id="mark" data-id="<?php echo mysql_real_escape_string($_POST["msgid"]);?>">Mark as Unread</a></li>
                                    </ul>
                                </div>
                                <div class="msgwrapper">
								
                          <?php 
						  $index=0;
						  while ($row = mysql_fetch_assoc($result)){
                          if($row["from_type"]!='Location'){
                          $sender = explode(' ',$row["sender"]);                       
						  $sender1 = $sender[0];
						  $sender2 =  ucfirst(substr($sender[1],0,1));
						  $row["sender"] = $sender1.' '.$sender2;
						  }                              
						  if ($index==0)
						  {
						  ?>      
						  <h1 class="subject"><?php echo $row["topic"]." (Ticket: ".$row["Ticket"]. ')';?></h1>
						  <?php 
						  } 
						  $index++;
						  ?>
                                <div class="msgauthor">
                                    <?php if ($row["senderimage"]!=""){?>
                                    <div class="thumb"><img src="<?php echo API. "images/". $row["senderimage"];?>" style="width:35px;max-height:35px;" alt="" onerror="this.src='images/Default - User - thumb.png';"/></div>
                                    <?php }else{?>
                                    <div class="thumb"><img src="images/Default - User - thumb.png" alt="" /></div>
                                    <?php }?>
                                    <div class="authorinfo">
                                        <span class="date pull-right"><?php echo date("Y-m-d H:i",strtotime($row["sent_datetime"]));?></span>
                                        <h5><strong>From : <?php echo $row["from_type"] . " - " . $row["sender"]; if ($row["employeename"]!="") echo " - " . $row["employeename"];?></strong><?php if ($row["attachment"]!="") {?><span style="padding-left:30px;"><a href="<?php echo API."images/".$row["attachment"];?>" target="_blank"><img src="images/attach2.png" width='19px'><a/></span><?php } ?></h5>
                                        <span class="to">To: <?php echo $row["to_type"] . " - " . $row["receiver"];?>
                                        <?php
											if($row['to_admin'] != ''){
												echo ' (ID:'. $row['to_admin'] .')';
											} elseif($row['to_location'] != ''){
												echo ' (ID:'. $row['to_location'] .')';
											} elseif($row['to_employee'] != ''){
												echo ' (ID:'. $row['to_employee'] .')';
											} elseif($row['to_client'] != ''){
												echo ' (ID:'. $row['to_client'] .')';
											} elseif($row['to_corp'] != ''){
												echo ' (ID:'. $row['to_corp'] .')';
											} elseif($row['to_employee_master'] != ''){
												echo ' (ID:'. $row['to_employee_master'] .')';
											}
                                            ?>
                                        
                                        </span>
                                    </div><!--authorinfo-->
                                </div><!--msgauthor-->
                                <div class="msgbody">
                                	<?php 
										if($index >= mysql_num_rows($result)){
											echo '<span style="color: black;">Ticket Type: </span>'. $row["ticket_type"] .'<br />';
											echo '<span style="color: black;">Ticket Priority: </span>'. $row["ticket_priority"] .'<br />';
											echo '<span style="color: black;">Product: </span>'. $row["product"] .'<br />';
											echo '<span style="color: black;">Caller: </span>'. $row["caller"] .'<br />';
											echo '<span style="color: black;">Telephone: </span>'. $row["phone"] .'<br /><br />';											
										}
									?>
                                    
                                    <?php echo $row["message"];?>
                                    <?php 
										if($index >= mysql_num_rows($result)){
											echo '<br /><br />';
											echo '<span style="color: black;">Solution: </span>'. $row["solution"] .'<br /><br />';
											echo '<span style="color: black;">Duration: </span>'. $row["duration"] .'<br />';
										}
									?>
                                </div><!--msgbody-->
                           <?php }?>   
                                </div>
                                
 <?php }
 }?>
<script>
jQuery(document).ready(function(){
    var msgid = '<?php echo mysql_real_escape_string($_POST["msgid"]);?>';
    jQuery('#forward').click(function(){
           //jQuery(this).data('id');
        })
        jQuery('#delete').click(function(){
            jQuery.ajax({
                        type: "POST",
                        url: "help/ajax_help_read_message.php",
                        data: {msgid:msgid, action:"delete"},
                        success: function(data){
                            console.log ("Message deleted");
                            jQuery("#message"+msgid).remove();
                            jQuery('.messageview').html('<span style="margin-left: 10px;padding-left: 10px;position: absolute;margin-top: 10px;"><h4>Message has been deleted.</h4></span>');
                            jQuery('.msgreply').css("display","none");
                            jQuery("#currentmessageid").val('');
                            jQuery("#currentmessagesubject").val('');
                        }
                     })
        })
        jQuery('#mark').click(function(){
            jQuery.ajax({
                        type: "POST",
                        url: "help/ajax_help_read_message.php",
                        data: {msgid:msgid, action:"unread"},
                        success: function(data){
                            
                            console.log ("Message unread");
                            jQuery('#message'+msgid).removeClass('unread').fadeOut().addClass('selected');
                            
                        }
                     })
        })
        jQuery('#print').click(function(){
            w=window.open(null, 'Print_Page', 'scrollbars=yes');        
            w.document.write(jQuery('.msgwrapper').html());
            w.document.close();
            w.print();
        })
})        
</script>
