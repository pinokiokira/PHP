<?php 
/*
 *  @author Ionut Irofte -> used as a base:ajax_autocomplete_location.php
 *  @version $Id: ajax_autocomplete_employee.php,v 1.0 01:00 PM 1/20/2014 juni $
 *  -> [req 1.3  - 20.01.2014]
      -> Add typeahead for employee master
      -> Searches numeric (for id) and/or firstname and lastname 
 */
header('Content-type: text/html; charset=UTF-8');
include_once '../includes/session.php';
include_once '../require/functions.php';
include_once '../config/accessConfig.php';
//if (isset($_REQUEST['query']) && $_REQUEST['query'] != "") {
if (isset($_REQUEST['query']) && $_REQUEST['query'] != ""&& strlen($_REQUEST['query'])>2) {//juni 20.01.2013 -> change minimum size to 3 chars
	$q = mysql_real_escape_string($_REQUEST['query']);
	$numq = '';
	$nameq = '';
	//if ( (isset($_REQUEST['identifier']) && $_REQUEST['identifier'] == "empmaster") || (isset($_REQUEST['identifier']) && $_REQUEST['identifier'] == "empmaster_location")) {
	if (isset($_REQUEST['identifier']) && $_REQUEST['identifier'] == "empmaster") {
		if(is_numeric($q)){
		  $numq = "(empmaster_id like '" . $q . "%')";
		}else{
		  $nameq = "(first_name  like '" . $q . "%' OR last_name like '" . $q . "%')";
		}
		//Don't use e-mail as it will mess up css
		//$sql = "SELECT empmaster_id as id,concat(first_name,' ',last_name,' ','(Email: ',email,')') name FROM employees_master where $nameq $numq order by name ASC limit 10";
		$sql = "SELECT empmaster_id as id,concat(first_name,' ',last_name,' ') name FROM employees_master where $nameq $numq order by name ASC limit 10";
		$r = mysql_query($sql) or die(mysql_error());
		if ($r) {
			$result = array();
			while ($l = mysql_fetch_assoc($r)) {
				$p = $l['name'];
				$id = $l['id'];
				$result[] = array('id'=>$id, 'label'=>$p);
			}
			echo json_encode($result); die();
		}		
	}	
	/*
	if (isset($_REQUEST['identifier']) && $_REQUEST['identifier'] == "cidade") {
		$sql = isset($_REQUEST['extraParam']) ? " and estado = " . mysql_real_escape_string($_REQUEST['extraParam']) . " " : "";
		$sql = "SELECT * FROM tb_cidades where locate('$q',nome) > 0 $sql order by locate('$q',nome) limit 10";
		$r = mysql_query($sql);
		if (count($r) > 0) {
			$result = array();
			while ($l = mysql_fetch_array($r)) {
				$p = $l['name'];
				$id = $l['id'];
				$result[] = array('id'=>$id, 'label'=>$p);
			}
			echo json_encode($result); die();
		}
	}*/
} else {//juni 20.01.2013 -> change minimum size to 3 chars
	$result[] = array('id'=>"null", 'label'=>"null");
	echo json_encode($result); die();
  }
?>