<?php
/**
 *  @author Ionut Irofte ->  juniorionut @ elance ( modifed original from @author Wellington Ribeiro)
 *  @version $Id: ajax_get_location_with_id,v 1.0 7:53 AM 1/23/2014 juni $
 *  -> [req 1.3  - 21.01.2014]
      -> Separate query (for id and name)
		-> Be able to filter on id
	-> req 1.15 - 01.03.2014 
		-> Customise error messages so that i can know what's wrong
		-> Rows that have special chars return value witb null -> Horizons® - Grove Park Inn (ID#: 17067)
 */
header('Content-type: text/html; charset=UTF-8');

require_once '../require/security.php';
include_once '../config/accessConfig.php';
if (isset($_REQUEST['query']) && $_REQUEST['query'] != ""&& strlen($_REQUEST['query'])>2) {//juni 20.01.2013 -> change minimum size to 3 chars
	$q = mysql_real_escape_string($_REQUEST['query']);
	$numq = '';
	$nameq = '';
	$result = array();
	if (isset($_REQUEST['identifier']) && $_REQUEST['identifier'] == "estado") {
		//$nameq = "name like '" . $q . "%' OR id like '" . $q . "%'";
		if(is_numeric($q)){ //juni -> separate query
			$numq = " AND (id like '%" . $q . "%')";
		}else{
			$nameq = " AND (name like '%" . $q . "%')";
		}  
		// $sql = "SELECT id,name FROM locations where $numq $nameq order by name ASC limit 10"; //juni -> filter on id also
		mysql_set_charset('utf8',$con);
		$sql = "SELECT id,concat(name,' ','(ID#: ',id,')') name FROM locations where id in (SELECT location_id FROM employees WHERE email = '{$_SESSION['email']}') $nameq $numq order by name ASC limit 10";
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
	}
	if ($result==null)//i need to know that nothing is found
		$result[] = array('id'=>'null', 'label'=>'null','error'=>'null result set');
	echo json_encode($result); die();
} else {//juni 20.01.2013 -> change minimum size to 3 chars
	$result[] = array('id'=>'null', 'label'=>'null','error'=>'query < 3');
	echo json_encode($result); die();
}

?>