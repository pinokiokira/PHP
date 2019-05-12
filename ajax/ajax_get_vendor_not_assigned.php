<?php
ob_start("ob_gzhandler");
header('Content-type: application/json');
include_once("../../internalaccess/connectdb.php");

if (isset($_REQUEST['query']) && $_REQUEST['query'] != ""&& strlen($_REQUEST['query'])>1) {
	$q = mysql_real_escape_string($_REQUEST['query']);
	$numq = '';
	$nameq = '';
	if(is_numeric($q)){ //juni -> separate query
		$numq = " AND (id like '%" . $q . "%')";
	}else{
		$nameq = "AND (name like '%" . $q . "%')";
	} 
	$sql = "SELECT id,concat(name,' ','(ID#: ',id,')') name FROM vendors 
			where id NOT IN(SELECT storepoint_vendor_id from employees_master WHERE storepoint_vendor_id<>'' AND storepoint_vendor_id IS NOT NULL) $nameq $numq order by name ASC limit 10";
	$res = mysql_query($sql) or die(mysql_error());
	if ($res) {
		while ($row = mysql_fetch_assoc($res)) {	
			$label = "";
			if (is_null($row['name'])||$row['name']=='')
				 $label = "Empty Name!";
			 else
				 $label =  htmlentities(strip_tags($row['name']), ENT_QUOTES); 
			$label = str_replace(array('®','™','&reg;','&trade;', ":", "'"), '',$label);				 
			$result[] = array('id'=>$row['id'], 'label'=>$label);
		}
	}
}else{
	$result[] = array('id'=>'null', 'label'=>'null','error'=>'query < 3');
}
if ($result==NULL){
	$result[] = array('id'=>'null', 'label'=>'null','error'=>'null result set');
}
echo json_encode($result); 
?>