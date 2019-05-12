<?php

require_once '../require/security.php';
include '../config/accessConfig.php';
require_once('../require/openid-config.php'); 

//Author : Munirkhan
//Description : Get location by location name and location id
//Date : 01-17-2019
if (isset($_REQUEST['query']) && $_REQUEST['query'] != "" && strlen($_REQUEST['query'])>2) {
    $q = mysql_real_escape_string($_REQUEST['query']);
    $numq = '';
    $nameq = '';
    
	if(is_numeric($q)){
        $numq = "(id like '" . $q . "%')";
    }else{
		$nameq = "(name like '" . $q . "%')";
    }  
    $sql = "SELECT id,concat(name,' ','(ID: ',id,')') name FROM locations where $nameq $numq AND id <>1 AND status = 'active' order by name ASC limit 10";
	
    $r = mysql_query($sql) or die(mysql_error());
	if ($r) {
		 $result = array();
		while ($l = mysql_fetch_array($r)) {
			$p = $l['name'];
			$id = $l['id'];
		  $result[] = array('id'=>$id, 'label'=>$p);
		}
		echo json_encode($result); die();
	}   
}else {
	$result[] = array('id'=>"null", 'name'=>"null");
	echo json_encode($result); die();
}

?>