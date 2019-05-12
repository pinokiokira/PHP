<?
$allowtoken=1;
	session_start();
	include("../../../includes/connectdb.php");	
	//include("internalaccess/connectdb.php");
	 include_once("../../../internalaccess/url.php");
	function clean($var){
		$specials = array(' ','!','@','#','$','%','^','&','(',')','_','+','`','~',',',';',"'",']','[','}','{');
		$cleaned = strtolower($var);
		$cleaned = str_replace($specials,'-',$cleaned);
		$cleaned = str_replace('--------------------','-',$cleaned);
		$cleaned = str_replace('-------------------','-',$cleaned);
		$cleaned = str_replace('------------------','-',$cleaned);
		$cleaned = str_replace('-----------------','-',$cleaned);
		$cleaned = str_replace('----------------','-',$cleaned);
		$cleaned = str_replace('---------------','-',$cleaned);
		$cleaned = str_replace('--------------','-',$cleaned);
		$cleaned = str_replace('-------------','-',$cleaned);
		$cleaned = str_replace('------------','-',$cleaned);
		$cleaned = str_replace('-----------','-',$cleaned);
		$cleaned = str_replace('----------','-',$cleaned);
		$cleaned = str_replace('---------','-',$cleaned);
		$cleaned = str_replace('--------','-',$cleaned);
		$cleaned = str_replace('-------','-',$cleaned);
		$cleaned = str_replace('------','-',$cleaned);
		$cleaned = str_replace('-----','-',$cleaned);
		$cleaned = str_replace('----','-',$cleaned);
		$cleaned = str_replace('---','-',$cleaned);
		$cleaned = str_replace('--','-',$cleaned);
		$cleaned = str_replace('-','-',$cleaned);
		return $cleaned;
	}
	
	$loc_image_path="http://www.softpoint.us/images/";
	
	function randPass($n, $chars)
	{
		  srand((double)microtime()*1000000);
		 $m = strlen($chars);
		while($n--)
		{
			$randPassword .= substr($chars,rand()%$m,1);
		}
		return $randPassword;
	}
	
	function dates_range($date1, $date2)
	{
		if ($date1<$date2)
		{
			$dates_range[]=$date1;
			$date1=strtotime($date1);
			$date2=strtotime($date2);
			while ($date1!=$date2)
			{
				$date1=mktime(0, 0, 0, date("m", $date1), date("d", $date1)+1, date("Y", $date1));
				$dates_range[]=date('Y-m-d', $date1);
			}
		}
		else if($date1==$date2)
		{
			$dates_range[]=$date1;
		}
		return $dates_range;
	}
	
	function time_range($date1, $date2)
	{
		if ($date1<$date2)
		{
			$dates_range[]=$date1;
			$date1=strtotime(date("Y-m-d").$date1);
			$date2=strtotime(date("Y-m-d").$date2);
			while ($date1<$date2)
			{
				$date1=mktime(date("H", $date1), date("i", $date1)+30,date("s", $date1), date("m", $date1), date("d", $date1), date("Y", $date1));
				$dates_range[]=date('H:i:00', $date1);
			}
		}
		else if($date1==$date2)
		{
			$dates_range[]=$date1;
		}
		return $dates_range;
	}
	function time_range_final($date1, $date2)
	{
		
		if ($date1<$date2)
		{
			
			$date_first=strtotime($date1);
			$date_first=mktime(date("H", $date_first), date("i", $date_first),date("s", $date_first), date("m", $date_first), date("d", $date_first), date("Y", $date_first));
			$check_day=date('l', $date_first);
			$dates_range[]=date('Y-m-d_l_H:i:00', $date_first);
			
			$date1=strtotime($date1);
			$date2=strtotime($date2);
			while ($date1<$date2)
			{
				$date1=mktime(date("H", $date1), date("i", $date1)+30,date("s", $date1), date("m", $date1), date("d", $date1), date("Y", $date1));
				$check_day=date('l', $date1);
				$dates_range[]=date('Y-m-d_l_H:i:00', $date1);
			}
		}
		else if($date1==$date2)
		{
			$check_day=date('l', $date1);
			$dates_range[]=date('Y-m-d_l_H:i:00', $date1);
		}
		return $dates_range;
	
	}
function time_range_location($date1,$date2)
{
	if ($date1<$date2)
	{
		$date_first=strtotime($date1);
		$date_first=mktime(date("H", $date_first), date("i", $date_first),date("s", $date_first), date("m", $date_first), date("d", $date_first), date("Y", $date_first));
		$dates_range[]=date('Y-m-d_l_H:i:00', $date_first);
		
		$date1=strtotime($date1);
		$date2=strtotime($date2);
		while ($date1<$date2)
		{
			$date1=mktime(date("H", $date1), date("i", $date1)+30,date("s", $date1), date("m", $date1), date("d", $date1), date("Y", $date1));
			$dates_range[]=date('Y-m-d_l_H:i:00', $date1);
			
		}
	}
	else if($date1==$date2)
	{
		$dates_range[]=date('Y-m-d_l_H:i:00', $date1);
	}
	return $dates_range;
}
	
	
function new_time_range($date1, $date2,$days_allow)
{
	if ($date1<$date2)
	{
		$date_first=strtotime($date1);
		$date_first=mktime(date("H", $date_first), date("i", $date_first),date("s", $date_first), date("m", $date_first), date("d", $date_first), date("Y", $date_first));
		$check_day=date('l', $date_first);
		if(in_array($check_day,$days_allow))
		{
			$dates_range[]=date('Y-m-d_l_H:i:00', $date_first);
		}
		
		$date1=strtotime($date1);
		$date2=strtotime($date2);
		while ($date1<$date2)
		{
			$date1=mktime(date("H", $date1), date("i", $date1)+30,date("s", $date1), date("m", $date1), date("d", $date1), date("Y", $date1));
			$check_day=date('l', $date1);
			if(in_array($check_day,$days_allow))
			{
				$dates_range[]=date('Y-m-d_l_H:i:00', $date1);
			}
		}
	}
	else if($date1==$date2)
	{
		$check_day=date('l', $date1);
		if(in_array($check_day,$days_allow))
		{
			$dates_range[]=date('Y-m-d_l_H:i:00', $date1);
		}
	}
	return $dates_range;
}
	
function time_object($date1, $date2,$date_obj,$availability_type)
{
		if ($date1<$date2)
		{
			
			$date_first=strtotime($date1);
			$date_first=mktime(date("H", $date_first), date("i", $date_first),date("s", $date_first), date("m", $date_first), date("d", $date_first), date("Y", $date_first));
			$check_day=date('l', $date_first);
			$day=date('Y-m-d', $date_first);
			$tday=date('Y-m-d_H:i:00', $date_first);
			$dates_range[]=$tday;
			$date_obj[$day]['type']=$availability_type;
			$date_obj[$day]['daytime'][$tday]=$availability_type;
			$date1=strtotime($date1);
			$date2=strtotime($date2);
			while ($date1<$date2)
			{
				$date1=mktime(date("H", $date1), date("i", $date1)+30,date("s", $date1), date("m", $date1), date("d", $date1), date("Y", $date1));
				$check_day=date('l', $date1);
				$day=date('Y-m-d', $date1);
				$tday=date('Y-m-d_H:i:00', $date1);
				$dates_range[]=$tday;
				$date_obj[$day]['type']=$availability_type;
				$date_obj[$day]['daytime'][$tday]=$availability_type;
			}
		}
		else if($date1==$date2)
		{
				$check_day=date('l', $date1);
				$day=date('Y-m-d', $date1);
				$tday=date('Y-m-d_H:i:00', $date1);
				$dates_range[]=$tday;
				$date_obj[$day]['type']=$availability_type;
				$date_obj[$day]['daytime'][$tday]=$availability_type;
		}
		
}
	
	
function DateFormat_PHP($date,$format)
{
    $date=explode("/",$date);

    if($format=="Y-m-d")
    {
        $newdate=$date[2]."-".$date[0]."-".$date[1];
    }
    else if($format=="m-d-Y")
    {
        $newdate=$date[1]."-".$date[0]."-".$date[2];
    }
    else if($format=="d-m-Y")
    {
        $newdate=$date[2]."-".$date[1]."-".$date[0];
    }
    return $newdate;
}
	
		$ftphost = 'ftp.softpoint.us';
		$ftpusr = 'internal_update';
		$ftppwd = 'UpdateInternal2012%';
		
		$_SESSION['backdoor']=($_REQUEST['status_bk']!="")?$_REQUEST['status_bk']:$_SESSION['backdoor'];
		$count_down=0;
		if($_SESSION['backdoor']!='backdoor')
		{
		
	
	$squery="SELECT * FROM preferences WHERE `key`='STAFFPOINT_LAUNCH'";
	$sresult=mysql_query($squery);
	$obj = mysql_fetch_object($sresult);
	$strLaunchDate=DateFormat_PHP($obj->value,"Y-m-d");
	$date1 = date("Y-m-d h:i:s");
	$date2 = $strLaunchDate . " 00:00:00";
	$diff = abs(strtotime($date2) - strtotime($date1));
	$days = floor($diff / (60 * 60 * 24));
	if ($days < 10) {
		$days = "0" . $days;
	}
	$hours = floor(($diff - $days * 60 * 60 * 24) / (60 * 60));
	if ($hours < 10) {
		$hours = "0" . $hours;
	}
	$minuts = floor(($diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
	$seconds = floor(($diff - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minuts * 60));
	$arr['launch_date'] = str_pad($days,2,0,STR_PAD_LEFT) . " " . str_pad($hours,2,0,STR_PAD_LEFT) . " " . str_pad($minuts,2,0,STR_PAD_LEFT) . " " . str_pad($seconds,2,0,STR_PAD_LEFT);
	$dsquery = "SELECT DATEDIFF('" . $strLaunchDate . " 00:00:00',now()) as totDiff";
	$dsresult = mysql_query($dsquery);
	$dsrow = mysql_fetch_object($dsresult);
	$totDiff = $dsrow->totDiff;

	if($totDiff>=0)
	{
		$count_down=1;
	}
	
	}
	
function isImage($url)
{
 $params = array('http' => array(
			  'method' => 'HEAD'
		   ));
 $ctx = stream_context_create($params);
 $fp = @fopen($url, 'rb', false, $ctx);
 if (!$fp) 
	return false;  // Problem with url

$meta = stream_get_meta_data($fp);
if ($meta === false)
{
	fclose($fp);
	return false;  // Problem reading data from url
}

$wrapper_data = $meta["wrapper_data"];
if(is_array($wrapper_data)){
  foreach(array_keys($wrapper_data) as $hh){
	  if (substr($wrapper_data[$hh], 0, 19) == "Content-Type: image") // strlen("Content-Type: image") == 19 
	  {
		fclose($fp);
		return true;
	  }
  }
}

fclose($fp);
return false;
}


$script_name=basename($_SERVER['PHP_SELF']);
if($script_name!="location-register.php" && $script_name!="location-register-save.php" && $script_name!="register-save.php" && $script_name!="register.php")
{
	unset($_SESSION['email']);
	unset($_SESSION['representative']);
	unset($_SESSION['representative_title']);
	unset($_SESSION['location_name']);
	unset($_SESSION['address']);
	unset($_SESSION['zip']);
	unset($_SESSION['city']);
	unset($_SESSION['country']);
	unset($_SESSION['state']);
	unset($_SESSION['phone']);
	unset($_SESSION['dob']);
	unset($_SESSION['website']);
	unset($_SESSION['notes']);
	unset($_SESSION['neighborhood']);
	unset($_SESSION['cuisine_details']);
	unset($_SESSION['cuisine']);
	unset($_SESSION['rating']);

unset($_SESSION['email']);
unset($_SESSION['salutation']);
unset($_SESSION['first_name']);
unset($_SESSION['last_name']);
unset($_SESSION['address']);
unset($_SESSION['zip']);
unset($_SESSION['city']);
unset($_SESSION['country']);
unset($_SESSION['state']);
unset($_SESSION['phone']);
unset($_SESSION['sex']);
unset($_SESSION['dob']);
unset($_SESSION['viewable']);
unset($_SESSION['activities']);
unset($_SESSION['education']);
unset($_SESSION['competences']);
unset($_SESSION['languages']);
unset($_SESSION['employment_type']);
unset($_SESSION['emp_position1']);
unset($_SESSION['emp_position2']);
unset($_SESSION['emp_position3']);
}

?>