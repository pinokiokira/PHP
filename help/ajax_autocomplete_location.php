<?php
/**
 * @author Wellington Ribeiro
 * @version 1.0
 * @since 2010-02-09
 */
header('Content-type: text/html; charset=UTF-8');
include_once '../includes/session.php';
include_once '../require/functions.php';
include_once '../config/accessConfig.php';
if (isset($_REQUEST['query']) && $_REQUEST['query'] != "") {
    $q = mysql_real_escape_string($_REQUEST['query']);
    $numq = '';
    $nameq = '';
    if ((isset($_REQUEST['identifier']) && $_REQUEST['identifier'] == "estado") or (isset($_REQUEST['identifier']) && $_REQUEST['identifier'] == "estado_location") ){
        if(is_numeric($q)){
            $numq = "(id like '" . $q . "%')";
        }else{
            $nameq = "(name like '" . $q . "%')";
        }
        $sql = "SELECT id,concat(name,' ','(ID: ',id,')') name FROM locations where $nameq $numq order by name ASC limit 10";
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
}

?>