<?php
/*
*  @modified Ionut Irofte - juniorionut @ elance
*  @version $Id: quick_activity.php,v 1.0 9:12 AM 1/19/2014 juni $
*  -> [req 1.12  - 02.16.2014]
	-> Code Indentation
	-> Make changes as requested
	-> Had a layout as a base, but had to change in some place
	-> NOT ALL Changes are not explained in code
*/
include_once("includes/session.php");
include_once("config/accessConfig.php");
 $market = $_POST['market'];
 ///// changes done by Atul johri - 22-08-2016 ///
  $iType = $_POST['iType'];
 $groupID = "";
if($market!=''){
	$sqlmarket =  " where ig.Market='".$market."'";
	$limit = '';
	}else
	{
	 $sqlmarket =' where 1';
	 $limit = 'LIMIT 0';
	}
	///// changes done by Atul johri - 22-08-2016 ///
 if($market == "Restaurant" && $iType == "prep" ){
 	 $groupID = '52';
 }
 $sql = "SELECT distinct(ig.id) as id,ig.description 
			FROM inventory_groups ig
				 $sqlmarket  
		ORDER BY ig.description ASC $limit" ;
		//echo $sql;
		$output = mysql_query($sql) or die(mysql_error());								
		$rows = mysql_num_rows($output);
		if ($rows > 0 && $rows != '') {	
			$data .= '<option value=""> - - -   Select Item Group - - - </option>';
			while ($result = mysql_fetch_assoc($output)) {
				//print_r($result);exit;
				$id = $result['id'];
				$description = $result['description'];
				if ($id == $groupID) {
					$sel = ' selected="selected"';
				} else {
					$sel = '';
				}
				$data .= '<option value="' . $id . '"' . $sel . '>' . utf8_decode($description."  (Group ID: ".$id.") ").'</option>';
			}
		} else {
		  $data .= '<option value=""> - - -   No Item Group Found  - - - </option>';
		}
		
		echo $data;exit;
  
?>
