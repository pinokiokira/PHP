<?php
/**
 * @author Wellington Ribeiro
 * @version 1.0
 * @since 2010-02-09
 *  @mdified Ionut Irofte ->
 *  @version $Id: ajax_autocomplete,v 1.0 7:53 AM 1/21/2014 juni $
 *  -> [req 1.3  - 21.01.2014]
      -> Separate query (for id and name)
		-> Be able to filter on id
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
    if (isset($_REQUEST['identifier']) && $_REQUEST['identifier'] == "estado") {
        
        $nameq = "name like '" . $q . "%' OR id like '" . $q . "%'";
	//	if(is_numeric($q)){ //juni -> separate query
        //    $numq = "(id like '" . $q . "%')";
        //}else{
        //    $nameq = "(name like '" . $q . "%')";
        //}  
       // $sql = "SELECT id,name FROM locations where $numq $nameq order by name ASC limit 10"; //juni -> filter on id also
	    // $sql = "SELECT id,concat(name,' ','(ID: ',id,')') name FROM locations where $nameq $numq order by name ASC limit 10";
	     $sql = "SELECT id, name name FROM locations where $nameq  order by name ASC limit 10";
        
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
    }
} else {//juni 20.01.2013 -> change minimum size to 3 chars
	$result[] = array('id'=>"null", 'label'=>"null");
	echo json_encode($result); die();
  }

?>