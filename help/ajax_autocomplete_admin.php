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
    if (isset($_REQUEST['identifier']) && $_REQUEST['identifier'] == "estado") {
		// <!-- ->juni [req 1.8] --> <!--- 01.02.2014 - You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'or ) (id like '3174%') order by name ASC limit 10' at line 1 -->
		$filerBy = "";
        
            $nameq = "(name like '%" . $q . "%')";//juni -> added % before 
			$nameq1 = "(email like '%" . $q . "%')";//juni -> added % before 
			$filerBy  = "($nameq or $nameq1)"; //juni
        
		//Correct sql query to be able to get by id
        //$sql = "SELECT id,concat(name,' ','( ',email,' ) ') name FROM clients where status='A' and ($nameq or $nameq1) $numq order by name ASC limit 10";
        $sql = "SELECT id,concat(name,' ','( ',email,' ) ') name FROM users where status='Active' and $filerBy order by name ASC limit 10";
		//echo $sql;
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
    
}

?>