<?php 
include_once '../require/security.php';
include_once '../config/accessConfig.php';

//$sql = "SELECT lm.* , coalesce(usr.name,lm.sent_by) AS sender FROM location_messages lm LEFT JOIN users usr ON lm.sent_by = usr.id
  //                                  WHERE type_of_message='pmb' ORDER BY lm.date DESC,  lm.time DESC"; 
  $sqlUserFilter=$_REQUEST['sqlUserFilter'];
  $sqlUserEmployeeFilter=$_REQUEST['sqlUserEmployeeFilter'];
  $sqlStatusFilter=$_REQUEST['sqlStatusFilter'];
  $sqlsearch=$_REQUEST['sqlsearch'];
  $sqlsearch2=$_REQUEST['sqlsearch2'];
 // $location_id=$_SESSION['loc'];
  
  
   switch (mysql_real_escape_string($_POST["hdnCurrentTab"]))
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
                          WHERE to_type = 'Team' AND to_employee_master='{$_SESSION['client_id']}' AND help.status = 'unread' ";     
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
                          WHERE from_type = 'Team' AND from_employee_master='{$_SESSION['client_id']}' "; 
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
                 if ($sqlsearch!=""){
                      //    $sqlsearch = " AND (help.topic LIKE '%{$_POST["msgsearch"]}%' OR help.topic LIKE '%{$_POST["msgsearch"]}%' OR help.topic  LIKE '%{$_POST["msgsearch"]}%' OR employees.first_name  LIKE '%{$_POST["msgsearch"]}%' OR employees.last_name  LIKE '%{$_POST["msgsearch"]}%' OR users.user  LIKE '%{$_POST["msgsearch"]}%' OR locations.name  LIKE '%{$_POST["msgsearch"]}%' OR clients.name  LIKE '%{$_POST["msgsearch"]}%') ";
			$sql .= $sqlsearch;			 
                     } 
                     $sql .= " ORDER BY help.sent_datetime DESC";	
$result = mysql_query($sql);
$msgstyle = "";
$countmessages = mysql_num_rows($result);
while ($row=mysql_fetch_assoc($result)){
    if ($row["status"]=='unread'){
        $msgstyle = "selected"; 
        //$firstRecord = False;
    }else {   
        $msgstyle = "unread"; 
    }
    ?>
    <li class="<?php echo $msgstyle;?> getmessage" data-id="<?php echo $row["help_id"];?>" data-subject="<?php echo $row["topic"]; ?>" data-status="<?php echo $row["status"];?>" id="message<?php echo $row["id"];?>">
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
 <?php $msgstyle = ""; } ?>
<input type="hidden" id="countedmessages" value="<?php echo $countmessages;?>">