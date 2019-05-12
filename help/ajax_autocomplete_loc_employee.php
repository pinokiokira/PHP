<?php 
header('Content-type: text/html; charset=UTF-8');
include_once '../config/accessConfig.php';
//echo "<option value='All'>All Employees</option>";

           $location =  $_REQUEST['loc'];
	if ($location!="" && $location!="All"){	
		$sql = "SELECT  id,concat(first_name,' ',last_name,' ') name FROM employees where location_id={$location} order by name ASC ";
		$r = mysql_query($sql) or die(mysql_error());
		if ($r) {
			
			while ($l = mysql_fetch_assoc($r)) {
                            echo "<option value='".$l['id']."'>".$l['name']."</option>";
                       }
				
                }	
        }
?>