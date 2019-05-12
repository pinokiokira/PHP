<?php 
require_once '../require/security.php';
include_once '../config/accessConfig.php';

                            $sqlUserFilter=$_REQUEST['sqlUserFilter'];
                            $sqlUserEmployeeFilter=$_REQUEST['sqlUserEmployeeFilter'];
                            $sqlStatusFilter=$_REQUEST['sqlStatusFilter'];
                            $sqlsearch=$_REQUEST['sqlsearch'];
                            $sqlsearch2=$_REQUEST['sqlsearch2'];
					 switch (mysql_real_escape_string($_POST["hdnCurrentTab"]))
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
                     if ($sqlsearch!=""){
                      //    $sqlsearch = " AND (help.topic LIKE '%(mysql_real_escape_string{$_POST["msgsearch"]})%' OR help.topic LIKE '%(mysql_real_escape_string{$_POST["msgsearch"]})%' OR help.topic  LIKE '%(mysql_real_escape_string{$_POST["msgsearch"]})%' OR employees.first_name  LIKE '%(mysql_real_escape_string{$_POST["msgsearch"]})%' OR employees.last_name  LIKE '%(mysql_real_escape_string{$_POST["msgsearch"]})%' OR users.user  LIKE '%(mysql_real_escape_string{$_POST["msgsearch"]})%' OR locations.name  LIKE '%(mysql_real_escape_string{$_POST["msgsearch"]})%' OR clients.name  LIKE '%(mysql_real_escape_string{$_POST["msgsearch"]})%') ";
			$sql .= $sqlsearch;			 
                     } 
				         
			$sql .= " ORDER BY empmsg.date DESC,  empmsg.time DESC";
                       // echo $sql; 
                    
                    //die($sql);
                    $result = mysql_query($sql);
                    if (mysql_num_rows($result)>0){
                            ?>
                            <ul class="msglist" style="height: 579px;">
                               <?php 
                               $firstRecord = True;
                               $msgstyle = "";
                               while ($row=mysql_fetch_assoc($result)){
                                   if ($row["readd"]=='no' && (mysql_real_escape_string($_POST["hdnCurrentTab"])=="INBOX" || mysql_real_escape_string($_POST["hdnCurrentTab"])=="")){
                                       $msgstyle = "selected"; 
                                       //$firstRecord = False;
                                   }else {   
                                       $msgstyle = "unread"; 
                                   }
                                   if (mysql_real_escape_string($_POST["hdnCurrentTab"])=="SENT"){
                                   ?>
                                <li class="<?php echo $msgstyle;?> getmessage" data-id="<?php echo $row["id"];?>" data-subject="<?php echo $row["Subject"];?>" data-status="<?php echo $row["readd"];?>" id="message<?php echo $row["id"];?>" data-thread="<?php echo $row["thread_id"];?>">
                                    <?php if ($row["receiverimage"]!="" && getimagesize(API."images/" .$row['receiverimage']) != false){?>
                                    <div class="thumb"><img src="<?php echo API. "images/". $row["receiverimage"];?>" style="width:40px;height:40px;" alt="" /></div>
                                    <?php }else{?>
                                    <div class="thumb"><img src="images/Default - User - thumb.png" alt="" /></div>
                                    <?php }?>
                                    <div class="summary" style="max-height: 55px;">
                                        <span class="date pull-right"><small><?php echo $row["date"];?> <?php echo date("H:i",strtotime($row["time"]));?></small><br><?php if ($row["priority"]=="High"){?><span class="iconfa-flag" style="color:red;float: right;margin-top: 5px;"></span><?php }else if ($row["priority"]=="Low"){ ?><span class="iconfa-flag" style="color:yellow;float: right;margin-top: 5px;"></span><?php } ?></span>
                                        <p style="margin-top:0px;"><strong><?php echo $row["receiver"];?></strong></p>
                                        <p style="margin-top:0px; " ><strong><?php if ($row["Subject"] == "") {echo "No Subject";} else echo $row["Subject"] ;?></strong></p>
                                        <p  style="margin-top:0px;"><span style="line-height:15px;"><?php echo substr($row["message"],0,18);?>..</span></p>
                                    </div>
                                </li>
                                <?php } else {?>
                                <li class="<?php echo $msgstyle;?> getmessage" data-id="<?php echo $row["id"];?>" data-subject="<?php echo $row["Subject"];?>" data-status="<?php echo $row["readd"];?>" id="message<?php echo $row["id"];?>" data-thread="<?php echo $row["thread_id"];?>">
                                    <?php if ($row["senderimage"]!="" && getimagesize(API."images/" .$row['senderimage']) != false){?>
                                    <div class="thumb"><img src="<?php echo API. "images/". $row["senderimage"];?>" style="width:40px;height:40px;" alt="" /></div>
                                    <?php }else{?>
                                    <div class="thumb"><img src="images/Default - User - thumb.png" alt="" /></div>
                                    <?php }?>
                                    <div class="summary" style="max-height: 55px;">
                                        <span class="date pull-right"><small><?php echo $row["date"];?> <?php echo date("H:i",strtotime($row["time"]));?></small><br><?php if ($row["priority"]=="High"){?><span class="iconfa-flag" style="color:red;float: right;margin-top: 5px;"></span><?php }else if ($row["priority"]=="Low"){ ?><span class="iconfa-flag" style="color:yellow;float: right;margin-top: 5px;"></span><?php } ?></span>
                                        <p style="margin-top:0px;"><strong><?php echo $row["sender"];?></strong></p>
                                        <p style="margin-top:0px; " ><strong><?php if ($row["Subject"] == "") {echo "No Subject";} else echo $row["Subject"] ;?></strong></p>
                                        <p  style="margin-top:0px;"><span style="line-height:15px;"><?php echo substr($row["message"],0,18);?>..</span></p>
                                    </div>
                                </li>
                                <?php }$msgstyle = ""; }?>
                            </ul>
                            <?php } else {echo "<div style='padding-left: 102px;padding-top: 30px;font-size: 15px;'>No Messages Found.</div>";}?>
       