<?php

header('Content-type: text/html; charset=UTF-8');
require_once '../require/security.php';
include_once '../config/accessConfig.php';


		mysql_set_charset('utf8',$con);
		$sql = "SELECT id,concat(name,' ','(ID#: ',id,')') name FROM locations where id in (SELECT location_id from employees WHERE email='{$_SESSION["email"]}') $nameq $numq order by name ASC limit 10";
		$res = mysql_query($sql) or die(mysql_error());
		if ($res) {
			while ($row = mysql_fetch_assoc($res)) {	
				$label = "";
				if (is_null($row['name'])||$row['name']=='')
					 $label = "Empty Name!";
				 else
					 $label =  htmlentities(strip_tags($row['name']), ENT_QUOTES); 
				$label = str_replace(array('®','™','&reg;','&trade;', ":", "'"), '',$label);				 
				$result .= "<option value='".$row['id']."'>".$row['name']."</option>";
			}
		}
	
echo $result;
?>