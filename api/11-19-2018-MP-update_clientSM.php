<?
	include("init.php");
        include_once '../../../internalaccess/url.php';
	$sqlimage = "";
        $empmaster_id=mysql_real_escape_string($_POST['client_id']);
        $SM_id=mysql_real_escape_string($_POST['client_idsm']);
        $empmaster_image=mysql_real_escape_string($_POST['empmaster_image']);
	$smprofile_image=mysql_real_escape_string($_POST['smprofile_image']);
        $provider=mysql_real_escape_string($_POST['provider']);
        $unlinkprovider=mysql_real_escape_string($_POST['unlinkprovider']);
        $action=mysql_real_escape_string($_POST['action']);
        $last_by=mysql_real_escape_string($_POST['last_by']);
	$last_on=mysql_real_escape_string($_POST['last_on']);
        
        /*if ($empmaster_image==""){
            $sqlimage = ", image='{$smprofile_image}'"; 
        }*/
        
       
	
	if ($action=="link"){
            switch ($provider){
                case "Facebook":
                    $sql = "UPDATE employees_master SET facebook_id='{$SM_id}', profile_image='{$smprofile_image}', facebook_status='Linked' , last_on='TeamPanel', last_by ='{$last_by}' $sqlimage WHERE empmaster_id = {$empmaster_id}";
                    break;
                case "Google":
                   $sql = "UPDATE employees_master SET google_id='{$SM_id}', google_image='{$smprofile_image}', google_status='Linked', last_on='TeamPanel', last_by ='{$last_by}' $sqlimage WHERE empmaster_id = {$empmaster_id}";
                    break;
                case "LinkedIn":
                    $sql = "UPDATE employees_master SET linkedin_id='{$SM_id}', linkedin_image='{$smprofile_image}', linkedin_status='Linked', last_on='TeamPanel', last_by ='{$last_by}' $sqlimage WHERE empmaster_id = {$empmaster_id}";
                    break;
                case "Twitter":
                    $sql = "UPDATE employees_master SET twitter_id='{$SM_id}', twitter_image='{$smprofile_image}', twitter_status='Linked', last_on='TeamPanel', last_by ='{$last_by}' $sqlimage WHERE empmaster_id = {$empmaster_id}";
                    break;
            }
        }else{
            switch ($unlinkprovider){
                case "Facebook":
                    $sql = "UPDATE employees_master SET  last_on='TeamPanel', facebook_status='Unlinked', last_datetime=now(), last_by ='{$last_by}'  WHERE empmaster_id = {$empmaster_id}";
                    break;
                case "Google":
                    $sql = "UPDATE employees_master SET  last_on='TeamPanel', google_status='Unlinked', last_datetime=now(), last_by ='{$last_by}'  WHERE empmaster_id = {$empmaster_id}";
                    break;
                case "LinkedIn":
                    $sql = "UPDATE employees_master SET  last_on='TeamPanel', linkedin_status='Unlinked', last_datetime=now(), last_by ='{$last_by}'  WHERE empmaster_id = {$empmaster_id}";
                    break;
                case "Twitter":
                    $sql = "UPDATE employees_master SET  last_on='TeamPanel', twitter_status='Unlinked', last_datetime=now(), last_by ='{$last_by}'  WHERE empmaster_id = {$empmaster_id}";
                    break;
            }
        }
	
	$result = mysql_query($sql);
        if ($result){
                $response['success'] = true;
                $response['code'] = 0;
				$response[1] = $sql;

        }
        else
        {
                $response['code'] = 1;
                $response[1] = $sql;
                $response['success'] = false;

        }

        echo json_encode($response);
		
	
?>