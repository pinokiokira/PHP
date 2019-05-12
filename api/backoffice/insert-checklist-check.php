<?php

$token = md5(http_build_query($_POST) . 'backofficesecure12');

if($token == $_GET['token2']){
    require_once("../../includes/connectdb.php");
	
    $location_id = intval($_POST['location_id']);
    $name = mysql_real_escape_string($_POST['name']);
    $list = mysql_real_escape_string($_POST['list_id']);
    $detail_ids = $_POST['detail_id'];
    $answers = $_POST['answer'];
	 $status = $_POST['status'];
	if($_REQUEST['created_on']!=""){
	$created_on = mysql_real_escape_string($_REQUEST['created_on']);
	}else{
	$created_on ='BackOffice';
	}
    $datetime = date('Y-m-d H:i:s');


    for($i=0;$i<=count($detail_ids);$i++){
        $detail_id = mysql_real_escape_string($detail_ids[$i]);
        $answer = mysql_real_escape_string($answers[$i]);

        if($detail_id != ''){
           				$query = "INSERT INTO location_checklist_check
                      	  SET checklist_id='$list',
                          checklistdetails_id='$detail_id',
                          location_id='" . $location_id . "',
						   status='" . $status . "',
                          datetime='$datetime',
                          answer='$answer',
                          created_by='$name',
                          created_on='$created_on',
                          created_datetime='$datetime'";
						  
            $result = mysql_query($query) or die(json_encode(array(
					'status' => 'success',
			        'list' => $list,
					'post'=>$_POST
					,'$query'=>$query
					,'mysql_error'=>mysql_error()
				)));
        }
    }
	
    echo json_encode(array(
        'status' => 'success',
        'list' => $list,
        'date' => substr($datetime,0,10)
		,'$query'=>$query
		,'post'=>$_POST
    ));
}else{
    echo json_encode(array(
        'status' => 'fail',
        'list' => '',
        'date' => ''
    ));
}
?>