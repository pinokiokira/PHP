<?php
$allowtoken=1;
include_once("../includes/connectdb.php");
$location_id = $_REQUEST['intLocationID'];
$servertime = $_REQUEST['server_time'];
$state_id = $_REQUEST['state_id'];
if($location_id==''){
	die();	
}
$servertimezone = date_default_timezone_get();

	if($state_id>0){
		$query = "SELECT l.GMT,st.name,st.id,st.timezone, l.Latitude, l.longitude from locations as l JOIN states as st ON st.id = '".$state_id."' where l.id = '".$location_id."'";	
	}else{
		$query = "SELECT l.GMT,st.name,st.id,st.timezone, l.Latitude, l.longitude from locations as l JOIN states as st ON st.id = l.state where l.id = '".$location_id."'";
	}
	$res = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($res);
	$state_id = $row['id'];
	$state_timezone = $row['timezone'];
	$state_name = $row['name'];
	$strlat = $row['Latitude'];
	$strlong = $row['longitude']; 
	
	if($_REQUEST['debug']=="1"){
		echo $query;
		echo "==>T:".time()."<br>";
		$ts = 1436950018;
		$date = new DateTime("@$ts");
		echo $date->format('U = Y-m-d H:i:s') . "\n";
	}
	if ($state_name !='' && $state_timezone != ''){
		$dateTimeZone = new DateTimeZone($state_timezone);		
		$dateTime = new DateTime("now", $dateTimeZone);		
		if($_REQUEST['debug']=="2"){
			
			echo "TEST:  ".timezoneDoesDST($state_timezone)."==>".$state_timezone;
			echo "<br>S :".$isDST = date("I", $DateTime = date("Y-m-d H:i:s", time()))."==>".$DateTime = date("Y-m-d H:i:s", time());
		}
		
		
		$Offset = $dateTimeZone->getOffset($dateTime);
		if($_REQUEST['debug']=="2"){
			echo "<br>Offset: ".$Offset;
			echo "<Br>D:";
		}
		
		$APIOffset = $Offset;
		$Offset1=$Offset+25200;
		//if(timezoneDoesDST($state_timezone)==true){
		//	$Offset=$Offset+28800;//+3600;
		//}else{
			$Offset=$Offset+25200;
		//}
		
	}else{
		$Offset=$row['GMT'];
		$Offset1=($Offset)*60*60;
		$Offset=($Offset)*60*60;
		
	}
	
	$DateTime = date("Y-m-d H:i:s", time() + $Offset);
	$DateTime1 = date("Y-m-d H:i:s", time() + $Offset1);
	$dtsgg=$DateTime;
	$arr[] = array('state'=>$state_name,'date_time'=>$DateTime);
	
	$DateTime = strtotime($DateTime);
	$DateTime1 = strtotime($DateTime1);
	$arr['loc_time']=date("h:i:s A",$DateTime);
	$loc_timedst=date("h:i:s A",$DateTime1);
	$arr['time'] = date("h:i",$DateTime);
	$arr['time24'] = date("H:i A",$DateTime);
	$arr['loc_datetime']=date("m/d/Y H:i",$DateTime);
	$arr['datetime'] = date("m/d/Y - D",$DateTime) ;
	
	$timediff_b_sl =  getdifferencetime(date('Y-m-d H:i:s',$DateTime1),$servertime);
	
	
	
	if($_REQUEST['debug']=='dif'){
		echo '<br> Diff = > '. $timediff_b_sl.'=>'. $DateTime1;
	}
	if($_REQUEST['debug']=="2"){
		echo "<br>TEST OFF:".$offset_diff = get_timezone_offset($state_timezone,$servertimezone);
	}
	$loctoserver = ConvertTimezoneToAnotherTimezone($loc_timedst,$state_timezone,$servertimezone);
	
	$loctoserver1 = explode(" ",$loctoserver);
	
	$arr['locationtoserver_time'] = trim($loctoserver1[1]);
	$arr['locationtoserver_datetime'] = trim($loctoserver);
	$arr['locationtoserver_time'] =date("H:i:s", time());
	$arr['locationtoserver_datetime'] = date("Y-m-d H:i:s", time());

	
	
	if($servertime==""){
		$servertime = $arr['locationtoserver_datetime']; //$loctoserver1[1];
	}else{
		if($strlat!="" && $strlong!=""){
			$tempservertime = explode(" ",$servertime);
			$timestamp = strtotime($tempservertime[1]);
			
			$url  = "https://maps.googleapis.com/maps/api/timezone/json?location=".trim($strlat).",".trim($strlong)."&key=AIzaSyBBLXCNd6InvapSA37_ygD_lVotEFWfi6w&timestamp=".$timestamp;
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL,$url);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
			$data = curl_exec ($curl);
			curl_close ($curl);
			$obj = json_decode($data);
			$newoffset = $obj->{'dstOffset'};
			
			$timestamp = strtotime($servertime)+$newoffset;
			$servertime = date("Y-m-d H:i:s", $timestamp);
		}
	}
	
	$loctoserver_by = ConvertTimezoneToAnotherTimezone($servertime,$state_timezone,$servertimezone);
	
	$loctoserver1_by = explode(" ",$loctoserver_by);
	
	$servertoloc_check = ConvertTimezoneToAnotherTimezone($loctoserver1[1],$servertimezone,$state_timezone);	
	$servertoloc_check1 = explode(" ",$servertoloc_check);
	$diff = strtotime($arr['loc_time']) - strtotime($servertoloc_check1[1]);
	
	if($_REQUEST['debug']=="1"){
	print_r($loctoserver1);
		echo "<br>AAA: ".$servertime.'==>'.$servertimezone.'==>'.$state_timezone;
	}
	$servertoloc = ConvertTimezoneToAnotherTimezone($servertime,$servertimezone,$state_timezone);
	//$add_time = date('Y-m-d H:i:s',strtotime($servertoloc)+$diff);
	if($_REQUEST['server_time']!=''){
		$server_date = date('Y-m-d H:i:s',$DateTime1);	
		$sql="select timediff('$dtsgg',now()) shours";
		if($_REQUEST['debug1']!=''){
			echo 'diff => '.$sql;
		}
		$qr = mysql_query($sql);
		$l = mysql_fetch_assoc($qr);
		$GMT_td = $l['shours']; 
		$GMT_td = explode(":",$GMT_td);
		$GMT_td=intval($GMT_td[0])-7;
		
		$str_servertime  = strtotime($_REQUEST['server_time']);
		$servertoloc = date('Y-m-d H:i:s', ($GMT_td+7)*3600+$str_servertime);
		$servertoloc1 = explode(" ",$servertoloc);
		
	}else{
		$servertoloc1 = explode(" ",$servertoloc);
	}
	
	$arr['servertolocation_time'] = trim($servertoloc1[1]);
	$arr['servertolocation_datetime'] = trim($servertoloc);
	$arr['Offset'] = $APIOffset;
	
	$arr['locationtoserver_bytime'] = trim($loctoserver1_by[1]);
	$arr['locationtoserver_bydatetime'] = trim($loctoserver_by);
	
	echo json_encode($arr);


function get_timezone_offset($remote_tz, $origin_tz = null) {
    if($origin_tz === null) {
        if(!is_string($origin_tz = date_default_timezone_get())) {
            return false; // A UTC timestamp was returned -- bail out!
        }
    }
    $origin_dtz = new DateTimeZone($origin_tz);
    $remote_dtz = new DateTimeZone($remote_tz);
    $origin_dt = new DateTime("now", $origin_dtz);
    $remote_dt = new DateTime("now", $remote_dtz);
    $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
    return $offset;
}	
	
function timezoneDoesDST($tzId) {
    $tz = new DateTimeZone($tzId);
	 return count($tz->getTransitions(time())) > 0;
    //$trans = $tz->getTransitions();
    //return ((count($trans) && $trans[count($trans) - 1]['ts'] > time()));
}	
	
function ConvertTimezoneToAnotherTimezone($time, $currentTimezone, $timezoneRequired) {
    $dayLightFlag = false;
    $dayLgtSecCurrent = $dayLgtSecReq = 0;
    $system_timezone = date_default_timezone_get();
    $local_timezone = $currentTimezone;
    date_default_timezone_set($local_timezone);
    $local = date("Y-m-d H:i:s");
	
	//if($_REQUEST['debug']=="2"){
		$daylight_flag = timezoneDoesDST($local_timezone);
	//}
	
    /* Uncomment if daylight is required */
            //$daylight_flag = date("I", strtotime($time));
			if($_REQUEST['debug']=="2"){
				echo "<br>1st : ".$daylight_flag."==>".$time;
				echo "<Br>D1st:".date('I');
				

			}
            if ($daylight_flag == 1 && $_REQUEST['server_time']!="") {
                //$dayLightFlag = true;
                //$dayLgtSecCurrent = -3600;
            }
    date_default_timezone_set("GMT");
    $gmt = date("Y-m-d H:i:s");

    $require_timezone = $timezoneRequired;
	$daylight_flag = timezoneDoesDST($require_timezone);
    date_default_timezone_set($require_timezone);
    $required = date("Y-m-d H:i:s ");
    /* Uncomment if daylight is required */
            //$daylight_flag = date("I", strtotime($time));
			if($_REQUEST['debug']=="2"){
				echo "<br>2nd : ".$daylight_flag."==>".$time;
				
			}
            if ($daylight_flag == 1 && $_REQUEST['server_time']!="") {
                //$dayLightFlag = true;
                //$dayLgtSecReq = +3600;
            }

	
    date_default_timezone_set($timezoneRequired);

    $diff1 = (strtotime($gmt) - strtotime($local));
    $diff2 = (strtotime($required) - strtotime($gmt));

    $date = new DateTime($time);
	
	if($_REQUEST['debug']=="1"){
		echo "<br> Time : ".$time."==>".$currentTimezone."==>".$timezoneRequired."==>R ".$required."==>L : ".$local;
		print_r($date);
	}
	
    $date->modify("+$diff1 seconds");
    $date->modify("+$diff2 seconds");

    if ($dayLightFlag) {
        $final_diff = $dayLgtSecCurrent + $dayLgtSecReq;
        $date->modify("$final_diff seconds");
    }

    $timestamp = $date->format("Y-m-d H:i:s");

    return $timestamp;
}	

function GetServerTime($intLocationID, $time){
	$slquery="SELECT GMT FROM locations WHERE id=".$intLocationID;
	$slresult=mysql_query($slquery);
	$slrow=mysql_fetch_object($slresult);
	$offset=$slrow->GMT + (14);

	$offset=($offset)*60*60;
	$dateFormat="H:i:s";
	$dateFormat1="Y-m-d H:i:s";
	$timeNdate=gmdate($dateFormat, strtotime($time)-$offset);
	$timeNdatetime=gmdate($dateFormat1, strtotime($time)-$offset);
	
	$arr['server_time'] = $timeNdate;
	$arr['server_datetime'] = $timeNdatetime;
	
	return $arr;
}


function GetLocationTime_Server($intLocationID, $servertime){
	//$slquery="SELECT GMT, DATE_FORMAT(now(),'%h:%i:%s %p') as servertime FROM locations WHERE id=".$intLocationID;
	$slquery = "SELECT st.name,st.id,st.timezone, GMT, DATE_FORMAT(now(),'%h:%i:%s %p') as servertime from locations as l JOIN states as st ON st.id = l.state where l.id = '".$intLocationID."'";
	$slresult=mysql_query($slquery);
	$slrow=mysql_fetch_object($slresult);
	
	$state_id = $slrow->id;
	$state_timezone = $slrow->timezone;
	$state_name = $slrow->name; 

	if ($state_name !=''){
		$dateTimeZone = new DateTimeZone($state_timezone);
		$dateTime = new DateTime("now", $dateTimeZone);
		$offset = $dateTimeZone->getOffset($dateTime);
		$offset=$offset;//+25200;
	}else{
		$offset=$slrow->GMT;
		$offset=($offset)*60*60;
	}
	//$servertime = $slrow->servertime;

	
	$dateFormat="h:i:s A";
	$dateFormat2="h:i A";
	$dateFormat1="m/d/Y H:i";
	$dateFormat3="Y-m-d h:i:s";
	$dateFormat4="m/d/Y H:i A";
	/*$timeNdate=gmdate($dateFormat, time()+$offset);
	$timeNdatetime=gmdate($dateFormat1, time()+$offset);
	
	$timeNdate_servertime=gmdate($dateFormat, strtotime($servertime)+$offset);
	$timeNdate_serverdatetime=gmdate($dateFormat1, strtotime($servertime)+$offset);
	$timeNdate_serverdatetime2=gmdate($dateFormat2, strtotime($servertime)+$offset);*/
	
	
	
	$timeNdate=gmdate($dateFormat, time()+$offset);
	$timeNdatetime=gmdate($dateFormat1, time()+$offset);
	$timeNdatetime_AM=gmdate($dateFormat4, time()+$offset);
	
	$timeNdate_servertime=gmdate($dateFormat, strtotime($servertime)+$offset);
	$timeNdate_servertime_sec=gmdate($dateFormat2, strtotime($servertime)+$offset);
	$timeNdate_serverdatetime=gmdate($dateFormat1, strtotime($servertime)+$offset);
	$timeNdate_serverdatetime2=gmdate($dateFormat3, strtotime($servertime)+$offset);
	
	/*$arr['loc_time'] = $timeNdate;
	$arr['loc_datetime'] = $timeNdatetime;
	
	$arr['loc_dbtime'] = $timeNdate_servertime;
	$arr['loc_dbdatetime'] = $timeNdate_serverdatetime;
	
	$arr['loc_dbdateformat'] = $timeNdate_serverdatetime2;*/
	
	$arr['loc_time'] = $timeNdate;
	$arr['loc_datetime'] = $timeNdatetime;
	$arr['loc_datetime'] = $dtsgg;
	
	$arr['loc_datetimeAM'] = $timeNdatetime_AM;
	
	$arr['loc_dbtime_sec'] = $timeNdate_servertime_sec;
	$arr['loc_curtime_sec'] = $timeNdate_serverdatetime2;
	
	$arr['loc_dbtime'] = $timeNdate_servertime;
	$arr['loc_dbdatetime'] = $timeNdate_serverdatetime;
	
	$arr['loc_offset'] = $slrow->GMT;
	
	return $arr;
}

function getdifferencetime($loctime,$passedtime){
		$query = "SELECT TIME_TO_SEC(TIMEDIFF('".$loctime."',now())) as timediff,now() as ccurrent_time";
		$res = mysql_query($query) or die(mysql_error());
		$row  = mysql_fetch_array($res);
		$diff =  $row['timediff'];
		$servertime = $row['ccurrent_time'];
		if($passedtime!=''){
			$servertime = $passedtime;
		}
		$add_q = "SELECT DATE_ADD('".$servertime."', INTERVAL ".$diff." SECOND) as stol";
		$res_q = mysql_query($add_q);
		$row_q = mysql_fetch_array($res_q);
		$datetime = date('Y-m-d H:i:s',strtotime($row_q['stol']));
		return $datetime;
		
}

?>


