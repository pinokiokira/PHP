    <?php 
require_once '../require/security.php';
include_once '../config/accessConfig.php';

if ($_POST["msgid"]!=""){
    
                                $sqlmsg = "Select empmsg.*, COALESCE(CONCAT(emp.first_name,' ', emp.last_name), corporate.name) as sender, loc.name as location,
                                    COALESCE(emp.image, corporate.image) as senderimage, 
                                    COALESCE(emp.email,corporate.email) as senderemail, 
                                    emp.emp_id, COALESCE(CONCAT(emprcv.first_name,' ', emprcv.last_name),corprcv.name) as receiver, emprcv.emp_id as receiverid,
                                    COALESCE(emprcv.email,corprcv.email) as receiveremail, COALESCE(emprcv.image,corprcv.image) as receiverimage
                                    from employee_messages empmsg 
                                    left join employees emp on empmsg.entered_by_emp_id = emp.id 
                                    left join corporate on empmsg.entered_by_corp_id = corporate.id 
                                    left join employees emprcv on empmsg.emp_id = emprcv.id 
                                    LEFT JOIN locations loc ON empmsg.location_id = loc.id
                                    left join corporate corprcv on empmsg.sent_to_corp = corprcv.id 
                                    WHERE empmsg.id='".mysql_real_escape_string($_POST["msgid"])."' OR (empmsg.thread_id = '".mysql_real_escape_string($_POST["thread"])."') ORDER BY empmsg.date DESC, empmsg.time DESC ";
                                   // die($sqlmsg);
                                   //  AND empmsg.emp_id='{$_SESSION["employee_id"]}') OR (Subject = '(mysql_real_escape_string{$_POST["subject"]})' AND empmsg.entered_by_emp_id='{$_SESSION["employee_id"]}') ORDER BY date desc, time desc
             $firstmessage=true;
            
             $resultmsg = mysql_query($sqlmsg);
             //  echo $sqlmsg;     
             if (mysql_num_rows($resultmsg)>0){
                       
                                    ?>
                                <div class="btn-group pull-right">
                                    <button data-toggle="dropdown" class="btn dropdown-toggle" style="color:black !important">Actions <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                       <!-- <li><a href="#" id="forward" data-id="<?php echo $_POST["msgid"];?>">Forward</a></li>
                                        <li><a href="#" id="delete" data-id="<?php echo $_POST["msgid"];?>">Delete Message</a></li>-->
                                        <li><a href="#" id="print" data-id="<?php echo $_POST["msgid"];?>">Print Message</a></li>
                                       <?php if ($_POST["hdnCurrentTab"]!="SENT"){ ?>
                                        <li><a href="#" id="mark" data-id="<?php echo $_POST["msgid"];?>">Mark as Unread</a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="msgwrapper">
                          <?php while ($row = mysql_fetch_assoc($resultmsg)){if ($firstmessage){
                              ?> 
                                    
                                <h1 class="subject"><?php if ($row["Subject"]<>"") {echo $row["Subject"];} else echo "No Subject";?></h1>
                              <?php 
                              
                              $firstmessage = false;
                              }
                           /*   if (mysql_real_escape_string($_POST["hdnCurrentTab"]=="SENT"){
                              ?>  
                                 <div class="msgauthor">
                                    <?php if ($row["receiverimage"]!=""){?>
                                    <div class="thumb"><img src="<?php echo API. "images/". $row["receiverimage"];?>" style="width:40px;height:40px;" alt="" /></div>
                                    <?php }else{?>
                                    <div class="thumb"><?php if ($row["sent_to_corp"]!=""  && $row["sent_to_corp"]!=0) {?><img src="images/Default - Corporate Icon.png" /><?php } else {?><img src="images/Default - User - thumb.png" alt="" /><?php } ?></div>
                                    <?php }?>
                                    <div class="authorinfo">
                                        <span class="date pull-right"><?php echo $row["date"] . " " . $row["time"];?></span>
                                        <h5><strong> <?php  if ($row["sent_to_corp"]!=""){
                                             echo "Corporate: " . $row["receiver"]."</strong>";
                                        }else{
                                            echo "Location: ". $row["location"]." - ".$row["receiver"] ."</strong> - <span>" .$row["receiverid"] ."</span>";
                                        }?>
                                           </h5>
                                        <span class="to"><?php echo $row["receiveremail"];?></span>
                                    </div><!--authorinfo-->
                                </div><!--msgauthor-->
                                <div class="msgbody">
                                    <?php echo $row["message"];?>
                                </div><!--msgbody-->
                           <?php }
                           else 
                           {*/
                            ?>  
                               
                                <div class="msgauthor">
                                    <?php if ($row["senderimage"]!="" && file_exists(API."images/" .$row['senderimage']) != false){?>
                                    <div class="thumb"><img src="<?php echo API. "images/". $row["senderimage"];?>" style="width:40px;height:40px;" alt="" /></div>
                                    <?php }else{?>
                                    <div class="thumb"><?php if ($row["entered_by_corp_id"]!=""  && $row["entered_by_corp_id"]!=0) {?><img src="images/Default - Corporate Icon.png" /><?php } else {?><img src="images/Default - User - thumb.png" alt="" /><?php } ?></div>
                                    <?php }?>
                                    <div class="authorinfo">
                                        <span class="date pull-right"><?php echo $row["date"] . " " . date("H:i",strtotime($row["time"]));?><br><?php if ($row["priority"]=="High"){?><span class="iconfa-flag" style="color:red;float: right;margin-top: 5px;"></span><?php }else if ($row["priority"]=="Low"){ ?><span class="iconfa-flag" style="color:yellow;float: right;margin-top: 5px;"></span><?php } ?></span>
                                        <h5><strong> 
                                                <?php  if ($row["entered_by_corp_id"]!=""){
                                             echo "Corporate: " . $row["sender"]."</strong>";
                                        }else{
                                            echo "Location: ". $row["location"]." - ".$row["sender"] ."</strong> - <span>" .$row["emp_id"] ."</span>";
                                        }?>
                                           
                                        </h5>
                                        <span class="to"><?php echo $row["senderemail"];?></span>
                                    </div><!--authorinfo-->
                                </div><!--msgauthor-->
                                <div class="msgbody">
                                    <?php echo $row["message"];?>
                                </div><!--msgbody-->
                           <?php   
                          // }
                           }?>   
                                </div>
                                
 <?php }
 }?>
<script>
jQuery(document).ready(function(){
    var msgid = '<?php echo $_POST["msgid"];?>';
    jQuery('#forward').click(function(){
           //jQuery(this).data('id');
        })
        jQuery('#delete').click(function(){
            jQuery.ajax({
                        type: "POST",
                        url: "ajax/ajax_read_message.php",
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
                        url: "ajax/ajax_read_message.php",
                        data: {msgid:msgid, action:"unread"},
                        success: function(data){
                            console.log ("Message unread");
                            jQuery('#message'+msgid).fadeOut();
                            
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
