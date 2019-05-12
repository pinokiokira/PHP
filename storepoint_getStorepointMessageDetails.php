<?php 
        require_once 'require/security.php';
	include_once 'config/accessConfig.php';
	
	$option=$_REQUEST['option'];
	//echo $option;
	
	function isImage($url)
{
 $params = array('http' => array(
			  'method' => 'HEAD'
		   ));
 $ctx = stream_context_create($params);
  $url = str_replace(" ","%20",$url);
 $fp = @fopen($url, 'rb', false, $ctx);
 if (!$fp) 
	return false;  // Problem with url

$meta = stream_get_meta_data($fp);
if ($meta === false)
{
	fclose($fp);
	return false;  // Problem reading data from url
}

$wrapper_data = $meta["wrapper_data"];
if(is_array($wrapper_data)){
  foreach(array_keys($wrapper_data) as $hh){
	  if (substr($wrapper_data[$hh], 0, 19) == "Content-Type: image") // strlen("Content-Type: image") == 19 
	  {
		fclose($fp);
		return true;
	  }
  }
}

fclose($fp);
return false;
}
	
	function getPrimaryTypeImage($primary_type)
  {
 	 $img="";
			
		if($primary_type=='1')	{$img= "Default Primary Type - Restaurants.png";}
		
		if($primary_type=='2')	{$img= "Default Primary Type - Bar.png";}
		
		if($primary_type=='3')	{$img= "Default Primary Type - Lounge.png";}
		
		if($primary_type=='4')	{$img= "Default Primary Type - Private.png";}
		
		if($primary_type=='7')	{$img= "Default Primary Type - Club.png";}
		
		if($primary_type=='6')	{$img= "Default Primary Type - Health.png";}
		
		if($primary_type=='9')	{$img= "Default Primary Type - Home.png";}
		
		if($primary_type=='67')	{$img= "Default Primary Type - Other.png";}
		
		if($primary_type=='71')	{$img= "Default Primary Type - Quick Service.png";}
		
		if($primary_type=='5')	{$img= "Default Primary Type - Retail.png";}
		
		if($primary_type=='10')	{$img= "Default Primary Type - Travel.png";}
		
		if($primary_type=='8')	{$img= "Default Primary Type - Recreation.png";}
		
		if($primary_type=='78')	{$img= "Default Primary Type - Hotel.png";}
			return $img;
  }
	
	
if(!isset($_SESSION['client_id']) || intval($_SESSION['client_id']) == ''){
 echo "0";
 exit();
}
	  $str='';
	  $sql_check = "select emp_master_id,subject,location_id,`read` from employee_master_location_storepoint where id  = ".$_REQUEST["id"];
	  $res_check = mysql_query($sql_check);
	  $row_check = mysql_fetch_array($res_check);
	  
	  /*$sql_job = "select * from location_jobs where id  = ".$row_check["location_job_id"];
	  
	  $res_job = mysql_query($sql_job);
	  $row_job = mysql_fetch_array($res_job);*/
	  
?>
 <div class="messageview">
                  <div class="btn-group pull-right">
                    <button data-toggle="dropdown" class="btn dropdown-toggle" style="color:#000000 !important;">Actions <span class="caret"></span></button>
                    <ul class="dropdown-menu">
                      <li><a id="print" data-id="<?php echo $_REQUEST['id']; ?>" href="#">Print Message</a></li>
					  <?
					if($option!='sent')
					{
				?>
                      <li><a id="mark" data-id="<?php echo $_REQUEST['id'];?>" href="#">Mark as Unread</a></li>
					  <?
					  }
					  ?>
                    </ul>
                  </div>
				  <div class="msgwrapper">
                  <h1 class="" style="font-size: 16px;line-height: 28px;padding-right: 150px; background: transparent;border: 0;margin: 0;padding: 0;line-height: 21px;outline: none;padding: 14px 20px 13px 20px;"><?=mysql_real_escape_string($row_check['subject'])?></h1>
				  
                 <?
	

	 if($row_check['read']=='No' && $option!='sent')
	 {
		 $sql2 = "update employee_master_location_storepoint set read_date=CURDATE(),read_time=CURTIME(),`read` =  'Yes' where id  = ".$_REQUEST["id"];
		 mysql_query($sql2);
	 }
	  
	$sql = "select a.subject, a.message, a.sent_by_type , a.sent_datetime, a.id as sId, a.emp_master_id, a.location_id,a.location_employee_id,
												 locations.name, locations.city 
												 , DATE_FORMAT(a.sent_datetime,'%Y-%m-%d') as date, DATE_FORMAT(a.sent_datetime,'%H:%i') as time  
										 from employee_master_location_storepoint a, locations 										 
										 where a.location_id = locations.id and a.subject = '".mysql_real_escape_string($row_check["subject"])."' AND a.location_id = '".$row_check["location_id"]."' AND (a.id = " . intval($_REQUEST["id"]) . " or a.emp_master_id in (select emp_master_id from employee_master_location_storepoint where id = " . intval($_REQUEST["id"]) . " )) order by sId desc";	
	$result = mysql_query($sql);
	
	while($row = mysql_fetch_array($result))
	{	
		$image = "";
		if($row['sent_by_type'] != null && $row['sent_by_type'] == "Employee Master"){
			$sqlMaster = "select * from employees_master where empmaster_id = '".$row['emp_master_id']."'";
			$resultMaster = mysql_query($sqlMaster);
			$rowMaster = mysql_fetch_array($resultMaster);
			$email = $rowMaster['email'];
			$default_img= "images/Default - User.png";
			$myven2 	= 	"SELECT * FROM vendors WHERE id='".$rowMaster["StorePoint_vendor_Id"]."'";
			$quemyven2  = 	mysql_query($myven2) or die(mysql_error());
			$rowmyven2	=	mysql_fetch_array($quemyven2);
			$spvid = $rowmyven2['id'];
			$sqlven = "SELECT StorePoint_image as image,name from vendors where id = '$spvid'";
			$resven=mysql_query($sqlven);
			$rowven=mysql_fetch_array($resven);
			$img = $rowven["image"];
			$image = 	APIPHP . "images/" .$img;

			//$image1 = API."images/".$rowMaster['image'];
			
			$name=$rowven["name"].': '.$rowMaster['first_name'] .' '. $rowMaster['last_name'];
			
			// $name=$rowven['name'];
			// echo 'jhgdsf......';
		}else if(($row['sent_by_type'] != null && $row['sent_by_type'] == "Location") ){
			$sqlMaster = "select image,representative,name,primary_type,email from Locations where id = '".$row['location_id']."'";
			$resultMaster = mysql_query($sqlMaster);
			$rowMaster = mysql_fetch_array($resultMaster);
			$email = $rowMaster['email'];
			$img = $rowMaster["image"];
			$image = APIPHP."images/".$img; 
			$default_img= "images/Default - location.png";
			$empq = mysql_fetch_array(mysql_query("SELECT CONCAT(first_name,' ',last_name) as name from employees where id = '".$row['location_employee_id']."'"));
			// $name=$rowMaster["name"].': '.$rowMaster['representative'];
			$name=$rowMaster["name"].': '.$empq['name'];
			if(!isImage($image))
			{
				$image = APIPHP."panels/teampanel/images/primary-type/".getPrimaryTypeImage($rowMaster['primary_type']); 
			}
			//print_r($rowMaster);
			//echo 'elsejhgdsf......';
		}
			if($img == ''){
				$myven = "SELECT StorePoint_vendor_Id,image FROM employees_master WHERE StorePoint='Yes' AND empmaster_id='".$row["emp_master_id"]."'";
				$quemyven =	mysql_query($myven) or die(mysql_error());
				$rowmyven =	mysql_fetch_array($quemyven);	
				$image = APIIMAGE. "images/". $rowmyven['image'];
			}
		
			echo '<div class="" style="padding: 10px 20px;border: 1px solid #ddd;border-left: 0;border-right: 0;overflow: hidden;clear: both;">';
			echo '<div style="width: 30px;height: 30px;float: left;margin-top: 5px;"><img onerror="this.src=\''.$default_img.'\'" src="'.$image.'" alt="" style="width:30px;height:30px; background: transparent;border: 0;margin: 0;padding: 0;line-height: 21px;outline: none;"/></div>';
			echo '<div class="" style="margin-left:40px">';
			echo '<span class="date" style="float: right;">'.$row["date"].' '.$row["time"].'</span>';
			echo '<h5 style="background: transparent;border: 0;margin: 0;padding: 0;line-height: 21px;outline: none;"><strong>'.$name.'</strong> </h5>';
			echo '<h5 style="font-weight:normal;line-height:normal; background: transparent;border: 0;margin: 0;padding: 0;line-height: 21px;outline: none;">'.$email.' </h5>';
			echo '</div><!--authorinfo-->';
			echo '</div><!--msgauthor-->';
			echo '<div class="msgbody">';
			echo '<p style="width:100%; background: transparent;border: 0;margin: 0;padding: 0;line-height: 21px;outline: none;">'.$row["message"].'</p>';
			echo '</div><!--msgbody-->';
	}
			
?>
            </div>   
				</div>
				<?
					if($option!='sent')
					{
				?>
                <!--messageview-->
                <form method="post" id="form_comment" action="storepoint.php?tab=<?php echo $option; ?>">
                  <input type="hidden" name="sId" id="sId" value="<?=$_REQUEST['id']?>" />
                  <div class="msgreply" style="height:220px; " >
				  <?
				  	$sqluser = "select * from employees_master where empmaster_id = '".$_SESSION['client_id']."'";
					$resultuser = mysql_query($sqluser);
					$rowuser = mysql_fetch_array($resultuser);
					//$image = APIPHP."images/".$rowuser['image'];
					$myven21 	= 	"SELECT * FROM vendors WHERE id='".$rowuser["StorePoint_vendor_Id"]."'";
					$quemyven21  = 	mysql_query($myven21) or die(mysql_error());
					$rowmyven21	=	mysql_fetch_array($quemyven21);
					$spvid1 = $rowmyven21['id'];
					$sqlven1 = "SELECT StorePoint_image as image,name from vendors where id = '$spvid1'";
					$resven1=mysql_query($sqlven1);
					$rowven1=mysql_fetch_array($resven1);
				    $image = 	APIPHP . "images/" . $rowven1['image'];
				  ?>
				  
                    
					 <?
				  	if($image!='')
										{
				  ?>
				    <div class="thumb"><img onerror="this.src='images/Default - User - thumb.png'" src="<?php echo $image; ?>" alt="" /></div>
					<?
					}
					else
					{
					?>
					<div class="thumb"><img src="images/Default - User - thumb.png" alt="" /></div>
					<?
					}
					?>
                    <div class="reply">
                      <textarea style="height:80px; resize: none;" id="message" name="message" placeholder="Type something here to reply"></textarea>
                      <input type="submit" value="Submit" class="btn btn-primary"/>
                    </div>
                    <!--reply--> 
                    
                  </div>
                  <!--messagereply-->
                </form>

		<?
			}
		?>


 <script>
jQuery(document).ready(function(){
    var msgid = '<?php echo $_REQUEST['id'];?>';
	 var opt = '<?php echo $_REQUEST['option'];?>';
    jQuery('#forward').click(function(){
           //jQuery(this).data('id');
        })
        jQuery('#delete').click(function(){
            jQuery.ajax({
                        type: "POST",
                        url: "ajax_storepoint_read_message.php",
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
        	console.log("Mark pressed!");

            jQuery.ajax({
                        type: "POST",
                        url: "ajax_storepoint_read_message.php",
                        data: {msgid:msgid, action:"unread"},
                        success: function(data){
                            console.log ("Message unread");
							if(opt!='inbox'){
                            jQuery('.selected').fadeOut();
							}
							
                            
                        }
                     })
        })
        jQuery('#print').click(function(){
					w=window.open(null, 'Print_Page', 'scrollbars=yes');        
					w.document.write(jQuery('.msgwrapper').html());
					w.document.close();
					w.print();
				});
				
				function printDiv(divName){
			var orderid = jQuery('.print_data').attr("pre_rel");
			var client_id = jQuery('.print_data').attr("pre_location");
			console.log('orderid: '+orderid);
			console.log('client_id: '+client_id);
			
			jQuery.ajax({
				url:"storepoint_message_print.php?orderid="+orderid+"&client_id="+client_id,
				success:function(result){
					jQuery("#loading-header").hide();	
					console.log(result);
					var WindowObject = window.open('', 'PrintWindow', 'width=750,height=650,top=0,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes');
					var html = "<!DOCTYPE html>";
					html = html + '<style media="all" type="text/css">body {background:none}</style>';
					html = html + '</head><body id="body"><div class="maincontent"><div class="maincontentinner">';
					html = html + '<br/><br/>';	
					html = html + result;	
					html = html + '</div></div></body></html>';		
					WindowObject.document.write(html);
					WindowObject.focus();
					if(jQuery.browser.chrome) {
						setTimeout(function(){					
							WindowObject.print();
							WindowObject.document.close();
							WindowObject.close();	
						},400);
					}else{
						
						WindowObject.print();
						WindowObject.document.close();
						WindowObject.close();
							
					}
				}
			});	
		}
})        
</script>