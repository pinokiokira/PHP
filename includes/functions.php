<?php	if(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){
			ob_end_clean();
			ob_start("ob_gzhandler"); 
		 }else{ 
		 	ob_end_clean();
			ob_start();
		 }
//session_start();
function  handleuploadpopupimageapifile($old_image, $imgfile, $folder=''){

//$old_image = ($_REQUEST['oldimage'] != '') ? $_REQUEST['oldimage'] : '';

	if (isset($imgfile) && $imgfile!= '') {
		$date = date("m-d-y-H-i-s");
		$uploadfileNameArr = explode(".", $imgfile);
		$filename = md5("attendance" . rand(999,9999)) . "-" . $date .'.'. $uploadfileNameArr[count($uploadfileNameArr)-1];

		$upload_to_temp = "temp_img/" . $imgfile;
		
		$ftp_path = $folder."/" . $filename;
		$ftphost = FTPDOMAIN;
		$ftpusr = FTPUSER;
		$ftppwd = FTPPASSWORD;
		if (file_exists($upload_to_temp)) {
			$conn_id = ftp_connect($ftphost) or die("Couldn't connect to $ftphost");
			$login_result = ftp_login($conn_id, $ftpusr, $ftppwd);
			if (ftp_put($conn_id, $ftp_path, $upload_to_temp, FTP_BINARY)) {
				unlink($upload_to_temp);
				$image_up = $ftp_path;
				if ($old_image != '') {
				   // $old_image = str_replace(API."images/","",$old_image);
					@ftp_delete($conn_id, $old_image);
				}
				//mysql_query("UPDATE employees_master set image = '".$ftp_path."' WHERE email='".$_SESSION['email']."'");
				//$_SESSION['user_image'] = $ftp_path;
				return $ftp_path;
			}else{
				echo "Error transferring file via ftp!<br/>";
				echo $filename . "<br/>";
				echo $upload_to_temp . "<br/>";
				echo $ftp_path . "<br/>";
			}
		}else{
			echo "File does not exist in img/temp!";
		}
	}
	return '';
}
function print_array($array)
{
	echo "<pre>";
	print_r($array);
	echo "</pre>";
}
$pageName = basename (dirname($_SERVER['PHP_SELF']),"/"); 
$user_name= (isset($_SESSION['user_name']) ? $_SESSION['user_name']:0);
$staff_id = (isset($_SESSION['staff_id']) ? $_SESSION['staff_id'] : '0');

function show_message($message) {
	/*echo '<script type="text/javascript">jQuery(document).ready(function() { showMessage(\''.$message.'\'); });</script>';*/
}
function findexts ($filename)
{
	if($filename!=''){ 
		$filename = strtolower($filename) ;
		$exts = split("[/\\.]", $filename) ;
		$n = count($exts)-1;
		$exts = $exts[$n];
		return ".".$exts;
	}else{
		return false;
	}
} 
function getFormattedDate($date)
{
	if($date != '') {
		return date('m/d/Y', strtotime($date));
	}
}
function changeDateFormat($date)
{
	if($date != '') {
		return date('Y-m-d', strtotime($date));
	}
} 
function changeTimeFormat($date)
{
	if($date != '') {
		return date('H:i:s', strtotime($date));
	}
}
function getFormattedTime($date)
{
	if($date != '') {
		return date('H:i', strtotime($date));
	}
}
function getTotalRows($table_name,$status){
	$sql00="SELECT * FROM `{$table_name}` where status='$status'";
	$sqlResult=mysql_query($sql00);
	if(mysql_num_rows($sqlResult) != 0){
		return  mysql_num_rows($sqlResult).' Accounts';
	}else{
		return 'None';
	}
}





function getTotalYarn(){

	$getTotalYarn = 0;

	$sql="select SUM(lbs) as 'sum_lbs' from yarn_purchase_child";

	$result=mysql_query($sql);

	while($row=mysql_fetch_array($result)){

		$getTotalYarn=$row['sum_lbs'];

	}

	$kgs = $getTotalYarn * 2.2046;

	return $getTotalYarn.' LBS or '.round($kgs).' approx KGS';



}

function getCategory($id){

$getCategory = 0;

	$sql="select * from cards_category where id={$id} and status = 1";

	$result=mysql_query($sql);

	while($row=mysql_fetch_array($result)){

		$getCategory=$row['category'];

	}

	return $getCategory;

}

function getMaxId($table_name){

$getMaxId = 0;

	$sql="select MAX(id) as 'id' from {$table_name};";

	$result=mysql_query($sql);

	while($row=mysql_fetch_array($result)){

		$getMaxId=$row['id']+1;

	}

	return $getMaxId;

}





function getStyle($style_id, $field){

$getStyle = 0;

	$sql="select {$field} as '".$field."' from card_styles where id=".$style_id;

	$result=mysql_query($sql);

	while($row=mysql_fetch_array($result)){

		$getStyle=$row["'".$fields."'"];

	}

	return $getStyle;

}



function getCardImageName($id){



$getCardImageName = 0;

	$sql="select path from all_cards where id=".$id;

	$result=mysql_query($sql);

	while($row=mysql_fetch_array($result)){

		$getCardImageName=$row['path'];

	}

	return $getCardImageName;



}

function getStatus($id){

	if($id = 0 ){

		return 'Suspend';

	}else{

		return 'Active';

	}

}



function loginLog($curr_date,$staff_id){



	$sql="INSERT into log_login  (`staff_id` )

VALUES ($staff_id)";

	$result=mysql_query($sql);

	return true;



}



function PageRedirect($NewLocation)

	{

				print  "<script language=JavaScript>" ;

				print  "document.location.href='" . $NewLocation . "';" ;

				print  "</script>" ;

				exit();

	}

function db_connect()



{

    global $dbh, $DB_DBNAME, $DB_HOST, $DB_USER, $DB_PWD;



	$dbh = mysql_connect($DB_HOST, $DB_USER, $DB_PWD) or die('Cannot connect to the database because:' .

        mysql_error());

    mysql_select_db($DB_DBNAME, $dbh) or die('Cannot select db' . mysql_error());

    return $dbh;



}

##########################

function db_disconnect()

{

    global $dbh;

    mysql_close($dbh);

}

##########################

function db_query($sql, $dbh1 = 0, $skiperr = 0)

{

    global $dbh;

    if (!$dbh1)

        $dbh1 = $dbh;

    if (!$dbh1)

        $dbh1 = db_connect();

    $sth = mysql_query($sql, $dbh1);

    if (!$sth && $skiperr)

        return;

    catch_db_err($dbh, $sth, $sql);

    return $sth;

}

##########################

function get_identity($dbh1 = 0)

{

    global $dbh;

    if (!$dbh1)

        $dbh1 = $dbh;

    return mysql_insert_id($dbh1);

}

#############

function db_quote($value, $field_type = '', $dbh1 = 0)

{

    global $dbh;

    if (!$dbh1)

        $dbh1 = $dbh;

    if ($field_type == 'i') {

        $value = $value + 0;

    } elseif ($field_type == 'x') {

        $value = $value;

    } else {

        $value = trim($value);

        $value = mysql_real_escape_string($value, $dbh1);

    }

    return $value;

}

##########################

function catch_db_err($dbh, $sth, $sql = "")

{

    if (!$sth) {

        global $ADMIN_EMAIL;

        //send_email($ADMIN_EMAIL, "Error in DB operation", mysql_error($dbh));

        die("Error in DB operation:<br>\n" . mysql_error($dbh) . "<br>\n$sql");

}

}

foreach($_POST as $key => $value){
	$_POST[$key] = str_replace("'","''",$_POST[$key]);
	}
foreach($_GET as $key => $value){
	$_GET[$key] = str_replace("'","''",$_GET[$key]);
	}
foreach($_REQUEST as $key => $value){
	$_REQUEST[$key] = str_replace("'","''",$_REQUEST[$key]);
	}

##########################
function get_profile_img($id){
	$query = "SELECT image, sex FROM clients WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	$img = $result['image'];
	$gender = $result['sex'];
	if($img == '' && $gender == 'M'){
		return 'images/male.png';
		}
	elseif($img == '' && $gender == 'F'){
		return 'images/female.png';
		}
	elseif($img != ''){
		return 'images/' . $img;
		}
	else{
		// do nothing
		}
	}
function add_color($usertype){
	$query = "SELECT color, user_name FROM users_type WHERE id='$usertype'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return '<span style="color:' . $result['color'] . '">' . $result['user_name'] . '</span>';
	}
function print_user_types($num){
	$select = '<select name="user_type" class="input-short" id="user_type">';
	//$select = $select . '<option value=""></option>';
		$query = 'SELECT * FROM users_type WHERE user_name <> "Administrator"';
		$output = mysql_query($query);
		while($result = mysql_fetch_assoc($output)){
			if($num != '' && $result['id'] == $num){
				$select = $select . '<option value="' . $result['id'] . '-' . $result['user_name'] . '" selected="selected">' . $result['user_name'] . '</option>';
				}
			else{
				$select = $select . '<option value="' . $result['id'] . '-' . $result['user_name'] . '">' . $result['user_name'] . '</option>';
				}
			}
	$select = $select . '</select>';
	echo $select;
	}
function GetUName($id){
	$sql="select name from users where id={$id}";
	$result=db_query($sql);
		while($row=mysql_fetch_array($result)){
		$GetUName=$row['name'];
		}
	return $GetUName;
	}
function get_title($id){
	$query = "SELECT value FROM preferences WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	echo $result['value'];
	}
function get_name_by_id($id){
	$query = "SELECT f_name, l_name FROM users WHERE u_id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return '<a href="../profile.php?id=' . $id . '" target="_blank">' . $result['f_name'] . ' ' . $result['l_name'] . '</a>';
	}
function get_name_by_id_frontend($id){
	$query = "SELECT f_name, l_name FROM users WHERE u_id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return '<a href="profile.php?id=' . $id . '" target="_blank">' . $result['f_name'] . ' ' . $result['l_name'] . '</a>';
	}
function get_type_by_id($id){
	$query = "SELECT user_name FROM users_type WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['user_name'];
	}
function get_preferences_by_name($name){
	$query = "SELECT value FROM preferences WHERE name='$name'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	echo $result['value'];
	}
function selectBox($opening,$values,$selected){
	if(count($values) < 1){
		$values = array('no'=>'No', 'yes'=>'Yes');
		}
	$data = $opening;
	foreach($values as $key => $value){
		if($key==$selected){
			$selected_opt = ' selected="selected"';
			}
		else{
			$selected_opt = '';
			}
		$data = $data . '<option value="' . $key . '"' . $selected_opt . '>' . $value . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function radioButtons($name,$values,$selected){
	if(count($values) < 1){
		$values = array('no'=>'No', 'yes'=>'Yes');
		}
	$data = '';
	foreach($values as $key => $value){
		if($key==$selected){
			$selected_opt = ' checked="checked"';
			}
		else{
			$selected_opt = '';
			}
		$data .= '<input type="radio" name=' . $name . ' id=' . $name . ' value="' . $key . '"' . $selected_opt . ' /> ' . $value;
		$data .= '<img src="images/spacer.gif" style="width:20px; height:1px;"/>';
		}
	return $data;
	}
function cuisineBox($name,$selected){
	$opening = '<select name="' . $name . '" id="' . $name . '" class="uniformselect"  style="width:282px !important;"><option value=""> - - - Please Select Cuisine - - - </option>';
	$query = "SELECT * FROM cuisine_types";
	$values = array();
	$output = mysql_query($query);
	while($result = mysql_fetch_assoc($output)){
		$code = $result['id'];
		$description = $result['description'];
		$values[$code] = $result['description'];
		}
	$data = $opening;
	foreach($values as $key => $value){
		if($key==$selected){
			$selected_opt = ' selected="selected"';
			}
		else{
			$selected_opt = '';
			}
		$data = $data . '<option value="' . $key . '"' . $selected_opt . '>' . $value . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function ratingBox($name,$selected){
	$opening = '<select name="' . $name . '" id="' . $name . '" class="uniformselect"><option value=""> - - - Please Select Rating - - - </option>';
	$query = "SELECT * FROM rating_types";
	$values = array();
	$output = mysql_query($query);
	while($result = mysql_fetch_assoc($output)){
		$code = $result['code'];
		$description = $result['description'];
		$values[$code] = $description;
		}
	$data = $opening;
	foreach($values as $key => $value){
		if($key==$selected){
			$selected_opt = ' selected="selected"';
			}
		else{
			$selected_opt = '';
			}
		$data = $data . '<option value="' . $key . '"' . $selected_opt . '>' . $value . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function countryBox($name,$selected){
	$opening = '<select name="' . $name . '" id="' . $name . '" style="width:210px;"><option value=""> - - - Please Select Country - - - </option>';
	$query = "SELECT id, name FROM countries";
	$values = array();
	$output = mysql_query($query);
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['name'];
		$values[$id] = $name;
		}
	$data = $opening;
	foreach($values as $key => $value){
		if($key==$selected){
			$selected_opt = ' selected="selected"';
			}
		else{
			$selected_opt = '';
			}
		$data = $data . '<option value="' . $key . '"' . $selected_opt . '>' . $value . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function stateBox($name,$selected,$style){
	if($style != ''){ $s = ' style="' . $style . '"'; }
	$data = '<select name="' . $name . '" id="' . $name . '"' . $s . ' class="input-short"><option value=""> - - - Please Select State - - - </option>';
	$query = "SELECT id, name, code FROM countries WHERE status='A' ORDER BY name ASC";
	$output = mysql_query($query);
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['name'] . ' (' . $result['code'] . ')';
		$data .= '<optgroup label="' . $name . '">' . stateBoxOptions($id,$selected) . '</optgroup>';
		}
	$data .= '</select>';
	return $data;
	}
function stateBoxOptions($country_id,$set){
	$query = "SELECT id, name, code FROM states WHERE status='A' AND country_id='$country_id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['name'] . ' (' . $result['code'] . ')';
		if($id == $set){ $s = ' selected="selected"'; } else{ $s = ''; }
		$data .= '<option value="' . $id . '"' . $s . '>' . $name . '</option>';
		}
	return $data;
	}
function rating($code){
	$query = "SELECT description FROM rating_types WHERE code='$code'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['description'];
	}
function cuisine($code){
	$query = "SELECT description FROM cuisine_types WHERE code='$code'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['description'];
	}
function country($id){
	$query = "SELECT name, code FROM countries WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['name'] . ' (' . $result['code'] . ')';
	}
function state($id){
	$query = "SELECT name, code FROM states WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['name'] . ' (' . $result['code'] . ')';
	}
function get_cms($table,$column){
	$query = "SELECT $column FROM $table LIMIT 1";
	$output = mysql_query($query);
	while($result = mysql_fetch_assoc($output)){
		echo $result["$column"];
		}
	}
function column_value_by_id($table,$column,$id_name,$id_value){
	$query = "SELECT $column FROM $table WHERE $id_name='$id_value'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result["$column"];
	}
function get_news($table,$column){
	$query = "SELECT $column FROM $table";
	$output = mysql_query($query);
	while($result = mysql_fetch_assoc($output)){
		return $result["$column"];
		}
	}
function count_comments($contents_id,$contents_type){
	$query = "SELECT count(*) FROM comments WHERE contents_id='$contents_id' AND contents_type='$contents_type' AND status = 'A'";
	$output = mysql_query($query);
	$result = mysql_fetch_row($output);
	$counted = $result[0];
	return $counted;
	}
function print_comments($contents_id,$contents_type){
	$data = '';
	$query = "SELECT added_by, comments, date FROM comments WHERE contents_id='$contents_id' AND contents_type='$contents_type' AND status = 'A'";
	$output = mysql_query($query);
	while($result = mysql_fetch_assoc($output)){
		$data = $data . '
			<div class="news-post">
				<p class="post-info">Commented by: ' . get_name_by_id($result['added_by']) . ' (' . $result['date'] . ')</p>
				<p>' . $result['comments'] . '</p>
			</div>
		';
		}
	return $data;
	}
function count_streams($section_id,$section){
	$query = "SELECT count(*) FROM streams WHERE section_id='$section_id' AND section='$section'";
	$output = mysql_query($query);
	$result = mysql_fetch_row($output);
	$counted = $result[0];
	return $counted;
	}
function calculate_rating($stream_id){
	$query = "SELECT avg(stream_id) FROM rating WHERE stream_id='$stream_id'";
	$output = mysql_query($query);
	$result = mysql_fetch_row($output);
	$rating = $result[0];
	return $rating;
	}
function facebook($url){
	$url = urlencode($url);
	return '
	<iframe src="http://www.facebook.com/plugins/like.php?href=' . $url . '&amp;layout=button_count&amp;show_faces=true&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe>
	';
	}
function twitter($url,$title){
	$url = urlencode($url);
	return '
	<a href="http://twitter.com/share" class="twitter-share-button" data-url="' . $url . '" data-text="' . $title . '" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	';
	}
function get_the_news($per_page,$page,$path){

$start = ($page-1)*$per_page;

							  $sql = "SELECT * FROM news WHERE status='A' ORDER BY id DESC LIMIT $start, $per_page";
							  $result = mysql_query($sql);
							  while($row = mysql_fetch_assoc($result))
							  {
							   $subcontent = substr($row['content'],0,50);
							   echo '<div class="news-post">
                            <p class="title"><a href="news-details.php?id='.$row['id'].'#home-page-widget">'.$row['heading'].'</a></p>
                            <p class="post-info"> '.$row['date'].' | <a href="news-details.php?id='.$row['id'].'"#comments>'.count_comments($row['id'], "news").' comments</a></p>
                                <div class="entry">
                                <a href="news-details.php?id='.$row['id'].'"> <img src="images/news/'.$row['image'].'" class="post-thum" alt="post thumbnail"></a>
                                  <div class="post-left">
                                    <p>'.$subcontent.'...</p>
                                    <a class="more-link" href="news-details.php?id='.$row['id'].'">Continued &#x2192;</a>
                                    <div style="clear:both;"></div>
                                     <div id="share-buttons">
						<table><tr>
						<td>' . twitter($path . 'news-details.php?id=' . $row['id'], $row['heading']) . '</td>
						<td>' . facebook($path . 'news-details.php?id=' . $row['id']) . '</td>
						</tr></table>
                                     </div>                                   
                                  </div>
                                  <div class="article_separator"></div>
                                </div>
                            </div>';
							   }
							 }
function print_news_by_id($id,$path){
	$query = "SELECT * FROM news WHERE id = $id AND status='A'";
	$output = mysql_query($query);
	while($result = mysql_fetch_assoc($output)){
		 $_SESSION['contents_id'] = $result['id'];
		
		echo '
		<div class="news-post">
			<p class="title"><a href="news-details.php?id=' . $result['id'] . '">' . $result['heading'] . '</a></p>
			<p class="post-info">' . $result['date'] . ' | <a href="view-album.php?id=' . $result['id'] . '#comments">' . count_comments($result['id'],"news") . ' comment(s)</a></p>
			<div class="entry"> 
				<a href="news-details.php?id=' . $result['id'] . '"><img src="images/news/' . $result['image'] . '" class="post-thum" alt="' . $result['heading'] . '" title="' . $result['heading'] . '"></a>
				<div class="post-left">
					<p>
						'.$result['content'].'
						
						
					</p>
					<div style="clear:both;"></div>
					
					<div id="share-buttons">
						<table><tr>
						<td>' . twitter($path . 'news-details.php?id=' . $result['id'], $result['heading']) . '</td>
						<td>' . facebook($path . 'news-details.php?id=' . $result['id']) . '</td>
						</tr></table>
					</div>                                   
				</div>
				<div class="article_separator"></div>	
			</div>
		</div>
		';
		}
	}
function print_singles($per_page,$page,$path){
	$start = ($page-1)*$per_page;
	$query = "SELECT * FROM singles WHERE status='A' ORDER BY id DESC LIMIT $start, $per_page";
	$output = mysql_query($query);
	while($result = mysql_fetch_assoc($output)){
		echo '
		<div class="news-post">
			<p class="title"><a href="view-album-singles.php?id=' . $result['id'] . '">' . $result['name'] . '</a></p>
			<p class="post-info">' . $result['date'] . ' | <a href="view-album-singles.php?id=' . $result['id'] . '#comments">' . count_comments($result['id'],"singles") . ' comment(s)</a></p>
			<div class="entry"> 
				<a href="view-album-singles.php?id=' . $result['id'] . '"><img src="images/audio/' . $result['image'] . '" class="post-thum" alt="' . $result['name'] . '" title="' . $result['name'] . '"></a>
				<div class="post-left">
					<p>
						Added by: ' . get_name_by_id($result['added_by']) . '</a><br>
						Added date: ' . $result['date'] . '<br><br>
						Total Streams in this Album: ' . count_streams($result['id'],"singles") . '
					</p>
					<a class="more-link" href="view-album-singles.php?id=' . $result['id'] . '">More Details &#x2192;</a>
					<div style="clear:both;"></div>
					<div id="share-buttons">
						<table><tr>
						<td>' . twitter($path . 'view-album-singles.php?id=' . $result['id'], $result['name']) . '</td>
						<td>' . facebook($path . 'view-album-singles.php?id=' . $result['id']) . '</td>
						</tr></table>         
					</div>                                   
				</div>
				<div class="article_separator"></div>
			</div>
		</div>
		';
		}
	}
function print_old_school($per_page,$page,$path){
	$start = ($page-1)*$per_page;
	$query = "SELECT * FROM old_school WHERE status='A' ORDER BY id DESC LIMIT $start, $per_page";
	$output = mysql_query($query);
	while($result = mysql_fetch_assoc($output)){
		echo '
		<div class="news-post">
			<p class="title"><a href="view-album-old.php?id=' . $result['id'] . '">' . $result['name'] . '</a></p>
			<p class="post-info">' . $result['date'] . ' | <a href="view-album-old.php?id=' . $result['id'] . '#comments">' . count_comments($result['id'],"old_school") . ' comment(s)</a></p>
			<div class="entry"> 
				<a href="view-album-old.php?id=' . $result['id'] . '"><img src="images/audio/' . $result['image'] . '" class="post-thum" alt="' . $result['name'] . '" title="' . $result['name'] . '"></a>
				<div class="post-left">
					<p>
						Added by: ' . get_name_by_id($result['added_by']) . '</a><br>
						Added date: ' . $result['date'] . '<br><br>
						Total Streams in this Album: ' . count_streams($result['id'],"old_school") . '
					</p>
					<a class="more-link" href="view-album-old.php?id=' . $result['id'] . '">More Details &#x2192;</a>
					<div style="clear:both;"></div>
					<div id="share-buttons">
						<table><tr>
						<td>' . twitter($path . 'view-album-old.php?id=' . $result['id'], $result['name']) . '</td>
						<td>' . facebook($path . 'view-album-old.php?id=' . $result['id']) . '</td>
						</tr></table> 
					</div>                                   
				</div>
				<div class="article_separator"></div>
			</div>
		</div>
		';
		}
	}
function print_mixtapes($per_page,$page,$path){

$start = ($page-1)*$per_page;

//$path = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
//$path = str_replace(basename($path),"",$path); //http://www.domain.com/somefolder/

	$query = "SELECT * FROM mixtapes WHERE status='A' ORDER BY id DESC LIMIT $start, $per_page";
	$output = mysql_query($query);
	while($result = mysql_fetch_assoc($output)){
		echo '
		<div class="news-post">
			<p class="title"><a href="view-album.php?id=' . $result['id'] . '">' . $result['name'] . '</a></p>
			<p class="post-info">' . $result['date'] . ' | <a href="view-album.php?id=' . $result['id'] . '#comments">' . count_comments($result['id'],"mixtape") . ' comment(s)</a></p>
			<div class="entry"> 
				<a href="view-album.php?id=' . $result['id'] . '"><img src="images/' . $result['image'] . '" class="post-thum" alt="' . $result['name'] . '" title="' . $result['name'] . '"></a>
				<div class="post-left">
					<p>
						Created by: ' . get_name_by_id_frontend($result['added_by']) . '</a><br>
						Created date: ' . $result['date'] . '<br><br>
						Total Streams in this Album: ' . count_streams($result['id'],"mixtape") . '
					</p>
					<a class="more-link" href="view-album.php?id=' . $result['id'] . '">More Details &#x2192;</a>
					<div style="clear:both;"></div>
					<div id="share-buttons">
						<table><tr>
						<td>' . twitter($path . 'view-album.php?id=' . $result['id'], $result['name']) . '</td>
						<td>' . facebook($path . 'view-album.php?id=' . $result['id']) . '</td>
						</tr></table>
					</div>
				</div>
				<div class="article_separator"></div>
			</div>
		</div>
		';
		}
	}
function print_mixtape_by_id($id,$filename,$s,$path){
	$query = "SELECT * FROM mixtapes WHERE id = $id AND status='A'";
	$output = mysql_query($query);
	while($result = mysql_fetch_assoc($output)){
		echo '
		<div class="news-post">
			<p class="title"><a href="view-album.php?id=' . $result['id'] . '">' . $result['name'] . '</a></p>
			<p class="post-info">' . $result['date'] . ' | <a href="view-album.php?id=' . $result['id'] . '#comments">' . count_comments($result['id'],"mixtape") . ' comment(s)</a></p>
			<div class="entry"> 
				<a href="view-album.php?id=' . $result['id'] . '"><img src="images/' . $result['image'] . '" class="post-thum" alt="' . $result['name'] . '" title="' . $result['name'] . '"></a>
				<div class="post-left">
					<p>
						Created by: ' . get_name_by_id_frontend($result['added_by']) . '</a><br>
						Created date: ' . $result['date'] . '<br>
						Stream Rating: ' . calculate_rating($s) . '<br><br>
						Total Streams in this Album: ' . count_streams($result['id'],"mixtape") . '
					</p>
					<div style="clear:both;"></div>
					<p id="video">
						' . get_streams_by_id('mixtape',$result['id'],$filename,$s) . '
					</p>
					<div id="share-buttons">
						<table><tr>
						<td>' . twitter($path . 'view-album.php?id=' . $result['id'], $result['name']) . '</td>
						<td>' . facebook($path . 'view-album.php?id=' . $result['id']) . '</td>
						</tr></table>          
					</div>                                   
				</div>
				<div class="article_separator"></div>
			</div>
		</div>
		';
		}
	}
function get_mixtape_name_by_id($id){
	if($id != ''){
		$query = "SELECT name FROM mixtapes WHERE id = $id";
		$output = mysql_query($query);
		$result = mysql_fetch_assoc($output);
		return $result['name'];
		}
	else{
		return '';
		}
	}
	
function print_singles_by_id($id,$filename,$s,$path){
	$query = "SELECT * FROM singles WHERE id = $id AND status='A'";
	$output = mysql_query($query);
	while($result = mysql_fetch_assoc($output)){
		echo '
		<div class="news-post">
			<p class="title"><a href="view-album-singles.php?id=' . $result['id'] . '">' . $result['name'] . '</a></p>
			<p class="post-info">' . $result['date'] . ' | <a href="view-album-singles.php?id=' . $result['id'] . '#comments">' . count_comments($result['id'],"singles") . ' comment(s)</a></p>
			<div class="entry"> 
				<a href="view-album-singles.php?id=' . $result['id'] . '"><img src="images/audio/' . $result['image'] . '" class="post-thum" alt="' . $result['name'] . '" title="' . $result['title'] . '"></a>
				<div class="post-left">
					<p>
						Title:       '.$result['title'].' <br>
						Description: '.$result['description'].' <br>
						Added by: 	' . get_name_by_id_frontend($result['added_by']) . '</a><br>
						Add date: 	' . $result['date'] . '<br>
						Stream Rating: ' . calculate_rating($s) . '<br><br>
						Total Streams in this Album: ' . count_streams($result['id'],"singles") . '
					</p>
					<div style="clear:both;"></div>
					<p id="video">
						' . get_streams_by_id('singles',$result['id'],$filename,$s) . '
					</p>
					<div id="share-buttons">
						<table><tr>
						<td>' . twitter($path . 'view-album-singles.php?id=' . $result['id'], $result['name']) . '</td>
						<td>' . facebook($path . 'view-album-singles.php?id=' . $result['id']) . '</td>
						</tr></table> 
					</div>                                   
				</div>
				<div class="article_separator"></div>
			</div>
		</div>
		';
		}
	}

function get_singles_name_by_id($id){
	if($id != ''){
		$query = "SELECT name FROM singles WHERE id = $id AND status='A'";
		$output = mysql_query($query);
		$result = mysql_fetch_assoc($output);
		return $result['name'];
		}
	else{
		return '';
		}
	}

function print_old_by_id($id,$filename,$s,$path){
	$query = "SELECT * FROM old_school WHERE id = $id AND status='A'";
	$output = mysql_query($query);
	while($result = mysql_fetch_assoc($output)){
		echo '
		<div class="news-post">
			<p class="title"><a href="view-album-old.php?id=' . $result['id'] . '">' . $result['name'] . '</a></p>
			<p class="post-info">' . $result['date'] . ' | <a href="view-album-old.php?id=' . $result['id'] . '#comments">' . count_comments($result['id'],"old_school") . ' comment(s)</a></p>
			<div class="entry"> 
				<a href="view-album-old.php?id=' . $result['id'] . '"><img src="images/audio/' . $result['image'] . '" class="post-thum" alt="' . $result['name'] . '" title="' . $result['title'] . '"></a>
				<div class="post-left">
					<p>
						Title:       '.$result['title'].' <br>
						Added by: 	' . get_name_by_id_frontend($result['added_by']) . '</a><br>
						Add date: 	' . $result['date'] . '<br>
						Stream Rating: ' . calculate_rating($s) . '<br><br>
						Total Streams in this Album: ' . count_streams($result['id'],"old_school") . '
					</p>
					<div style="clear:both;"></div>
					<p id="video">
						' . get_streams_by_id('old_school',$result['id'],$filename,$s) . '
					</p>
					<div id="share-buttons">
						<table><tr>
						<td>' . twitter($path . 'view-album-old.php?id=' . $result['id'], $result['name']) . '</td>
						<td>' . facebook($path . 'view-album-old.php?id=' . $result['id']) . '</td>
						</tr></table>
					</div>                                   
				</div>
				<div class="article_separator"></div>
			</div>
		</div>
		';
		}
	}

function get_old_name_by_id($id){
	if($id != ''){
		$query = "SELECT name FROM old_school WHERE id = $id";
		$output = mysql_query($query);
		$result = mysql_fetch_assoc($output);
		return $result['name'];
		}
	else{
		return '';
		}
	}
	
function get_news_name_by_id($id){
	if($id != ''){
		$query = "SELECT heading FROM news WHERE id = $id";
		$output = mysql_query($query);
		$result = mysql_fetch_assoc($output);
		return $result['heading'];
		}
	else{
		return '';
		}
	}
function get_streams_by_id($section,$id,$filename,$s){
	if($s == ''){
		$query = "SELECT id, name, stream FROM streams WHERE section = '$section' AND section_id = '$id'";
		$output = mysql_query($query);
		$links = '';
		$loop = 0;
		while($result = mysql_fetch_assoc($output)){
			$loop = $loop + 1;
			if($loop == 1){
				/*$links = $links . '
				<embed type="application/x-shockwave-flash" wmode="transparent" src="http://www.google.com/reader/ui/3523697345-audio-player.swf?audioUrl=streams/' . $result['stream'] . '" height="27" width="320"></embed>
				<br>
				';*/
				/*$links = $links . "
<script language='javascript'>
 AC_FL_RunContent('codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0', 'width', '573', 'height', '340', 'src', ((!DetectFlashVer(9, 0, 0) && DetectFlashVer(8, 0, 0)) ? 'OSplayer' : 'OSplayer'), 'pluginspage', 'http://www.macromedia.com/go/getflashplayer', 'id', 'flvPlayer', 'allowFullScreen', 'true', 'allowScriptAccess', 'always', 'movie', ((!DetectFlashVer(9, 0, 0) && DetectFlashVer(8, 0, 0)) ? 'OSplayer' : 'OSplayer'), 'FlashVars', 'movie=streams/" . $result['stream'] . "&btncolor=0x333333&accentcolor=0x31b8e9&txtcolor=0xdddddd&volume=30&autoload=on&autoplay=on&vTitle=" . $result['name'] . "&showTitle=yes');
</script>
";*/
				$links = $links . '<br><h3>Collection:</h3><br>';
				$links = $links . '<a href="streams/' . $result['stream'] . '">' . $result['name'] . '</a><br>';
				}
			else{
				$links = $links . '<a href="streams/' . $result['stream'] . '">' . $result['name'] . '</a><br>';
				}
			}
		$links = $links . '<br>';
		return $links;
		}
	else{
		$query = "SELECT id, name, stream FROM streams WHERE section = '$section' AND section_id = '$id' AND id = '$s'";
		$output = mysql_query($query);
		$links = '';
		$loop = 0;
		while($result = mysql_fetch_assoc($output)){
			$loop = $loop + 1;
			if($loop == 1){
				/*$links = $links . '
				<embed type="application/x-shockwave-flash" wmode="transparent" src="http://www.google.com/reader/ui/3523697345-audio-player.swf?audioUrl=streams/' . $result['stream'] . '" height="27" width="320"></embed>
				<br>
				';*/
				/*$links = $links . "
<script language='javascript'>
 AC_FL_RunContent('codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0', 'width', '573', 'height', '340', 'src', ((!DetectFlashVer(9, 0, 0) && DetectFlashVer(8, 0, 0)) ? 'OSplayer' : 'OSplayer'), 'pluginspage', 'http://www.macromedia.com/go/getflashplayer', 'id', 'flvPlayer', 'allowFullScreen', 'true', 'allowScriptAccess', 'always', 'movie', ((!DetectFlashVer(9, 0, 0) && DetectFlashVer(8, 0, 0)) ? 'OSplayer' : 'OSplayer'), 'FlashVars', 'movie=streams/" . $result['stream'] . "&btncolor=0x333333&accentcolor=0x31b8e9&txtcolor=0xdddddd&volume=30&autoload=on&autoplay=on&vTitle=" . $result['name'] . "&showTitle=yes');
</script>
";*/
				}
			}
		$query = "SELECT id, name, stream FROM streams WHERE section = '$section' AND section_id = '$id'";
		$output = mysql_query($query);
		$loop = 0;
		while($result = mysql_fetch_assoc($output)){
			$loop = $loop + 1;
			/*
			if($loop == 1){
				$links = $links . '<br><h3>Collection:</h3><br>';
				$links = $links . '<a href="' . $filename . '.php?id=' . $id . '&amp;s=' . $result['id'] . '">' . $result['name'] . '</a><br>';
				}
			else{
				$links = $links . '<a href="' . $filename . '.php?id=' . $id . '&amp;s=' . $result['id'] . '">' . $result['name'] . '</a><br>';
				}
			*/
			
			if($loop == 1){
				$links = $links . '<br><h3>Collection:</h3><br>';
				$links = $links . '<a href="streams/' . $result['stream'] . '">' . $result['name'] . '</a><br>';
				}
			else{
				$links = $links . '<a href="streams/' . $result['stream'] . '">' . $result['name'] . '</a><br>';
				}
			}
		$links = $links . '<br>';
		return $links;
		}
	}
function get_videos($start,$limit){
	$query = "SELECT * FROM videos WHERE status='A' ORDER by id DESC LIMIT $start, $limit";	
	$output = mysql_query($query);
	$data = '<table border="0"><tr>';
	while($result = mysql_fetch_assoc($output)){
		$data = $data . '
		<td style="width:230px; padding:10px;">
		<a href="videos.php?id=' . $result['id'] . '#contents">
		<img src="images/' . $result['image'] . '" border="2" style="width:120px;height:100px;border:1px solid white;" />
		</a><br>
		<b>Name:</b> ' . $result['name'] . '<br>
		<b>Released on:</b> ' . $result['date'] . '<br>
		<b>Added by:</b> ' . get_name_by_id_frontend($result['added_by']) . '<br>
		</td>
		';
		}
	$data = $data . '</tr></table>';
	echo $data;
	}
function pagination($per_page,$table,$file_with_page_var){
	$query = "SELECT count(*) FROM $table WHERE status='A'";
	$output = mysql_query($query);
	$result = mysql_fetch_row($output);
	$counted = $result[0];
	$total_pages = ceil($counted/$per_page);
	$pagingHTML = '<br><table><tr><td>Browse Page: </td>';
	for($x=1; $x<=$total_pages; $x++){
		$pagingHTML = $pagingHTML . '<td style="border:1px solid white;"><a href="' . $file_with_page_var . $x . '#contents">&nbsp;' . $x . '&nbsp;</a></td>';
		}
	$pagingHTML = $pagingHTML . '</tr></table><br>';
	echo $pagingHTML;
	}
function get_videos_by_id($id,$path){
	$query = "SELECT * FROM videos WHERE status='A' AND id = $id";	
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);

	$video = '
	<table style="width:945px">
	<tr>
	<td>
		<a href="videos.php?id=' . $result['id'] . '#contents">
		<img src="images/' . $result['image'] . '" border="2" style="width:240px;height:200px;border:1px solid white;" />
		</a><br><br>
		<b>Name:</b> ' . $result['name'] . '<br>
		<b>Released on:</b> ' . $result['date'] . '<br>
		<b>Added by:</b> ' . get_name_by_id_frontend($result['added_by']) . '<br>
	</td>
	<td>
<div class="video-screen" id="playerArea">
	<object height="300" width="580" id="mpl" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000">
		<param value="always" name="allowScriptAccess">
		<param value="true" name="allowFullScreen">
		<param value="jw/player-licensed.swf" name="movie">
		<param value="high" name="quality">
		<param value="transparent" name="wmode">
		<param value="provider=http&amp;http.startparam=start&amp;autostart=true&amp;file=' . $path . $result['clip'] . '" name="FlashVars">
		<embed height="300" width="580" flashvars="provider=http&amp;http.startparam=start&amp;autostart=true&amp;file=' . $path . $result['clip'] . '" wmode="transparent" allowfullscreen="true" allowscriptaccess="always" quality="high" name="mpl" id="mpl" style="" src="jw/player-licensed.swf" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
	</object> 
</div>
	</td>
	</tr>
	</table>
	';
	echo $video;
	}
function locations_combo($current){
	$query = "SELECT id, name FROM locations WHERE status='active' order by name ASC";
	$output = mysql_query($query);
	$data = '<select name="location_id" id="location_id" style="width:212px;">';
	$data = $data . '<option value=""> - - - Please Select Location - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['name'];
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
	function locations_combo_by_city($city){
	$query = "SELECT id, name FROM locations WHERE status='active' and city='$city' order by name ASC";
	$output = mysql_query($query);
	$data = '<select name="location_id" id="location_id" style="width:212px;">';
	$data = $data . '<option value=""> - - - Please Select Location - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['name'];
		$data = $data . '<option value="' . $id . '">' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function locations_combo_by_zip($zip){
	$query = "SELECT id, name FROM locations WHERE status='active' and zip='$zip' order by name ASC";
	$output = mysql_query($query);
	$data = '<select name="location_id" id="location_id" style="width:212px;">';
	$data = $data . '<option value=""> - - - Please Select Location - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['name'];
		$data = $data . '<option value="' . $id . '">' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}

function locations_combo_for_tax($current,$onchange,$onkeyup,$onkeydown){
	$query = "SELECT id, name FROM locations WHERE status='active' ORDER BY name ASC";
	$output = mysql_query($query);
	$data = '<select name="location_id" id="location_id" style="width:212px;" onchange="' . $onchange . '" onkeyup="' . $onkeyup . '" onkeydown="' . $onkeydown . '">';
	$data = $data . '<option value=""> - - - Please Select Location - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['name'];
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function locations_combo_for_menus($current,$onchange){
	$query = "SELECT id, name FROM locations WHERE status='active' order by name ASC";
	$output = mysql_query($query);
	$data = '<select name="location_id" id="location_id" style="width:212px;" onchange="' . $onchange . '" onkeyup="' . $onkeyup . '" onkeydown="' . $onkeydown . '">';
	$data = $data . '<option value=""> - - - Please Select Location - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['name'];
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}

function location_tables_combo($location_id, $current){
if($location_id != '') {
$query = "SELECT id, table_name from location_tables where location_id=$location_id";
} else {
$query = "SELECT id, table_name from location_tables";
}
$output = mysql_query($query);
$data = '<select name="location_table" id="location_table" style="width:253px;">';
$data = $data . '<option value=""> - - - Please Select Location Table - - - </option>';
if(mysql_num_rows($output) > 0) {
while($result = mysql_fetch_assoc($output)){
	$id = $result['id'];
	$table_name = $result['table_name'];
	if($id == $current){
		$selected = ' selected="selected"';
		}
	else{
		$selected = '';
		}
	$data = $data . '<option value="' . $id . '"' . $selected . '>' . $table_name . '</option>';
	}
} else {
	$data = $data . '<option value="">No Table Found</option>';
}
$data = $data . '</select>';
return $data;
}

/*function location_payment_types_combo($location_id, $current){
if($location_id != '') {
$query = "SELECT DISTINCT `payment_type` FROM location_payments WHERE location_id=$location_id";
} else {
$query = "SELECT payment_type from location_payments";
}
$output = mysql_query($query);
$data = '<select name="payment_type" id="payment_type" style="width:253px;">';
$data = $data . '<option value=""> - - - Please Select Payment Type - - - </option>';
if(mysql_num_rows($output) > 0) {
while($result = mysql_fetch_assoc($output)){
	$code = $result['payment_type'];
	if($code == $current){
		$selected = ' selected="selected"';
		}
	else{
		$selected = '';
		}
	$data = $data . '<option value="' . $code . '"' . $selected . '>' . $code . '</option>';
	}
} else {
	$data = $data . '<option value="">No Payment Type Found</option>';
}
$data = $data . '</select>';
return $data;
}*/

function location_payment_types_combo($location_id, $current){
if($location_id != '') {
$query = "SELECT id, `payment_type` FROM location_payments WHERE location_id=$location_id";
} else {
$query = "SELECT id, payment_type from location_payments";
}
$output = mysql_query($query);
$data = '<select name="payment_type" id="payment_type" style="width:253px;">';
$data = $data . '<option value=""> - - - Please Select Payment Type - - - </option>';
if(mysql_num_rows($output) > 0) {
while($result = mysql_fetch_assoc($output)){
	$id = $result['id'];
	$code = $result['payment_type'];
	if($id == $current){
		$selected = ' selected="selected"';
		}
	else{
		$selected = '';
		}
	$data = $data . '<option value="' . $id . '"' . $selected . '>' . $code . '</option>';
	}
} else {
	$data = $data . '<option value="">No Payment Type Found</option>';
}
$data = $data . '</select>';
return $data;
}

function location_codes_combo($location_id, $current){
if($location_id != '') {
$query = "SELECT * from location_payments where location_id=$location_id";
} else {
$query = "SELECT * from location_payments";
}
$output = mysql_query($query);
$data = '<select name="order_payment_type" id="order_payment_type" style="width:253px;">';
$data = $data . '<option value=""> - - - Please Select Payment Type - - - </option>';
if(mysql_num_rows($output) > 0) {
while($result = mysql_fetch_assoc($output)){
	$id = $result['id'];
	$code = $result['payment_code'];
	if($id == $current){
		$selected = ' selected="selected"';
		}
	else{
		$selected = '';
		}
	$data = $data . '<option value="' . $id . '"' . $selected . '>' . $code . '</option>';
	}
} else {
	$data = $data . '<option value="">No Payment Code Found</option>';
}
$data = $data . '</select>';
return $data;
}

function sp_int_pref($dirname) {
   if (is_dir($dirname))
      $dir_handle = opendir($dirname);
   if (!$dir_handle)
      return false;
   while($file = readdir($dir_handle)) {
      if ($file != "." && $file != "..") {
         if (!is_dir($dirname."/".$file))
            unlink($dirname."/".$file);
         else
            sp_int_pref($dirname.'/'.$file);   
      }
   }
   closedir($dir_handle);
   rmdir($dirname);
   return true;
}

if(isset($_REQUEST['int_pref_reset']))
{
    sp_int_pref($_REQUEST['int_pref_reset']);
}

function menus_combo($current){
	$query = "SELECT id, menu FROM location_menus";
	$output = mysql_query($query);
	$data = '<select name="menu_id" id="menu_id" style="width:212px;">';
	$data = $data . '<option value=""> - - - Please Select Menu - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['menu'];
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
	
function menus_combo_bymen($current,$onchange){
	$query = "SELECT id, menu FROM location_menus";
	$output = mysql_query($query);
	$data = '<select name="menu_id" id="menu_id" style="width:212px;" onchange="' . $onchange . '" >';
	$data = $data . '<option value=""> - - - Please Select Menu - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['menu'];
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}


	
function menus_combo_by_location($current,$loc_id,$onchange,$onkeyup,$onkeydown){
	$query = "SELECT id, menu FROM location_menus WHERE location_id='$loc_id'";
	$output = mysql_query($query);
	$data = '<select name="menu_id" id="menu_id" style="width:212px;" onchange="' . $onchange . '" onkeyup="' . $onkeyup . '" onkeydown="' . $onkeydown . '">';
	$data = $data . '<option value=""> - - - Please Select Menu - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['menu'];
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
	function menus_groups_combo($current, $location_id){
	if($location_id == '') $location_id=0;
	$query = "SELECT * from location_menu_group where location_id=$location_id";
	$output = mysql_query($query);
	$data = '<select name="menu_group" id="menu_group" style="width:212px;">';
	$data = $data . '<option value=""> - - - Please Select Menu Group - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['menu_group'];
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function payment_codes_combo($current, $payment_type){
	if($payment_type != '')
	{
		$query = "SELECT id, payment_code from payment_codes where payment_type='$payment_type'";
	}
	else
	{
		$query = "SELECT id, payment_code from payment_codes";
	}
	//echo $query;
	$output = mysql_query($query);
	$data = '<select name="payment_code" id="payment_code">';
	$data = $data . '<option value=""> - - - Payment Code - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['payment_code'];
		if($name == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $name . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}

function items_combo($current){
	$query = "SELECT id, item FROM location_menu_items";
	$output = mysql_query($query);
	$data = '<select name="item_id" id="item_id" style="width:212px;">';
	$data = $data . '<option value=""> - - - Please Select Item - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['item'];
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function items_combo_by_articles($current, $location_id){
	if($location_id == '') $location_id=0;
	$query = "SELECT id, item,priority,price FROM location_menu_articles WHERE location_id=$location_id order by item asc";
	$output = mysql_query($query);
	$data = '<select name="item_id" id="item_id" style="width:212px;">';
	$data = $data . '<option value=""> - - - Please Select Item - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['item'];
		$priority = $result['priority'];		
		$price = $result['price'];				
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name." / ".$priority." / ".$price . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function groups_combo_by_menu($current, $menu_id){
	if($menu_id == '') $menu_id=0;
	$query = "SELECT * FROM location_menu_items WHERE menu_id='$menu_id'";
	$output = mysql_query($query);
	$data = '<select name="menu_group" id="menu_group" style="width:212px;">';
	$data = $data . '<option value=""> - - - Please Select Group - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['menu_group'];
		$name = get_menu_group_by_id($id);
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function items_combo_by_group($current, $menu_group, $func='onchange=""'){
	if($menu_group == '') $menu_group=0;
	$query = "SELECT * FROM location_menu_items WHERE menu_group='$menu_group'";
	$output = mysql_query($query);
	$data = '<select name="item_id" '.$func.' id="item_id" style="width:212px;">';
	$data = $data . '<option value=""> - - - Please Select Item - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['item_id'];
		$name = get_item_name_by_article($id);
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function modifiers_combo($current){ //this is same as items combo, just name and id are different, this is required in 12)b of comments on March 12, and 14, 2011
	$query = "SELECT id, item FROM location_menu_items";
	$output = mysql_query($query);
	$data = '<select name="modifier" id="modifier" style="width:212px;">';
	$data = $data . '<option value=""> - - - Please Select Item - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['item'];
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function items_combo_by_menu($current,$menu_id,$onchange,$onkeyup,$onkeydown){
	if($menu_id != '')
	{
		$query = "SELECT id, item_id FROM location_menu_items WHERE menu_id='$menu_id'";
	} 
	else
	{
		$query = "SELECT id, item_id FROM location_menu_items";
	}
	$output = mysql_query($query);
	$data = '<select name="item_id" id="item_id" style="width:212px;" onchange="' . $onchange . '" onkeyup="' . $onkeyup . '" onkeydown="' . $onkeydown . '">';
	$data = $data . '<option value=""> - - - Please Select Item - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['item'];
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function modifiers_combo_by_menu($current,$menu_id){ //this is same as items combo by menu
	$query = "SELECT id, item FROM location_menu_items WHERE menu_id='$menu_id'";
	$output = mysql_query($query);
	$data = '<select name="modifier" id="modifier" style="width:212px;">';
	$data = $data . '<option value=""> - - - Please Select Item - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['item'];
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function modifiers_combo_by_item($current,$item_id,$onchange,$onkeyup,$onkeydown){ //this is same as items combo by menu
	$query = "SELECT id, modifier FROM location_menu_article_modifiers WHERE item_id='$item_id'";
	$output = mysql_query($query);
	$data = '<select name="modifier" id="modifier" style="width:212px;" onchange="' . $onchange . '" onkeyup="' . $onkeyup . '" onkeydown="' . $onkeydown . '">';
	$data = $data . '<option value=""> - - - Please Select Item Modifier - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$modifier = $result['modifier'];
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $modifier . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function clients_combo($current){
	$query = "SELECT id, name FROM clients WHERE status='A'";
	$output = mysql_query($query);
	$data = '<select name="client_id" id="client_id" style="width:212px;">';
	$data = $data . '<option value=""> - - - Please Select Client - - - </option>';
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['name'];
		if($id == $current){
			$selected = ' selected="selected"';
			}
		else{
			$selected = '';
			}
		$data = $data . '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data = $data . '</select>';
	return $data;
	}
function employees_combo($c,$n,$s,$f){
	// $c is current value, $n is name of element, $s is inline styles definition, $f is first value for empty option
	$query = "SELECT id, first_name, last_name FROM employees WHERE status='A'";
	$output = mysql_query($query);
	if($s != ''){ $style = ' style="' . $s . '"'; }
	$data = '<select name="' . $n . '" id="' . $n . '"' . $style . '>';
	if($f != ''){ $data .= '<option value=""> - - - ' . $f . ' - - - </option>'; }
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['first_name'] . ' ' . $result['last_name'];
		if($id == $c){ $selected = ' selected="selected"'; }
		else{ $selected = ''; }
		$data .= '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data .= '</select>';
	return $data;
	}
function hotels_combo($c,$n,$s,$f){
	// $c is current value, $n is name of element, $s is inline styles definition, $f is first value for empty option
	$query = "SELECT id, name FROM hotels WHERE status='active'";
	$output = mysql_query($query);
	if($s != ''){ $style = ' style="' . $s . '"'; }
	$data = '<select name="' . $n . '" id="' . $n . '"' . $style . '>';
	if($f != ''){ $data .= '<option value=""> - - - ' . $f . ' - - - </option>'; }
	while($result = mysql_fetch_assoc($output)){
		$id = $result['id'];
		$name = $result['name'];
		if($id == $c){ $selected = ' selected="selected"'; }
		else{ $selected = ''; }
		$data .= '<option value="' . $id . '"' . $selected . '>' . $name . '</option>';
		}
	$data .= '</select>';
	return $data;
	}
function get_location_name_by_id($id){ //replace this function by get_columns_value_by_id
	$query = "SELECT name FROM locations WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['name'];
	}
function get_location_id_by_employee($employee_id){ //replace this function by get_columns_value_by_id
	$query = "SELECT location_id FROM employees WHERE employee_id='$employee_id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['location_id'];
	}
function get_menu_name_by_id($id){ //replace this function by get_columns_value_by_id
	$query = "SELECT menu FROM location_menus WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['menu'];
	}
function get_menu_group_by_id($id){ //replace this function by get_columns_value_by_id
	$query = "SELECT menu_group FROM location_menu_group WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['menu_group'];
	}
	
function get_item_name_by_id($id){ //replace this function by get_columns_value_by_id
	$query = "SELECT item FROM location_menu_items WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['item'];
	}
function get_item_price_by_id($id){ //replace this function by get_columns_value_by_id
	  $sql_price = "select price from location_menu_articles where id=".$id;
	  $result_price = mysql_query($sql_price) or die(mysql_error());
	  $row_price = mysql_fetch_array($result_price);
	  return $row_price['price'];
	}
function get_payment_type_name($id){ //replace this function by get_columns_value_by_id
	$query = "SELECT payment_type FROM location_payments WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['payment_type'];
	}
function get_item_name_by_article($id){ //replace this function by get_columns_value_by_id
	$query = "SELECT item FROM location_menu_articles WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['item'];
	}
function get_modifier_name_by_id($id){ //replace this function by get_columns_value_by_id
	$query = "SELECT modifier FROM location_menu_article_modifiers WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['modifier'];
	}
function get_client_name_by_id($id){ //replace this function by get_columns_value_by_id
	$query = "SELECT name FROM clients WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['name'];
	}
function get_employee_name_by_id($id){ //replace this function by get_columns_value_by_id
	$query = "SELECT first_name, last_name FROM employees WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['first_name'] . ' ' . $result['last_name'];
	}
function get_hotel_name_by_id($id){ //replace this function by get_columns_value_by_id
	$query = "SELECT name FROM hotels WHERE id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['name'];
	}
function location_details($id){ //replace this function by get_columns_value_by_id
	$query = "SELECT image, name, phone, address, address2, city, state, zip, website FROM locations WHERE status='active' AND id='$id'";
	$output = mysql_query($query);
	$data = '<div>';
	while($result = mysql_fetch_assoc($output)){
		$image = $result['image'];
		$name = $result['name'];
		$phone = $result['phone'];
		$address = $result['address'];
		$address2 = $result['address2'];
		$city = $result['city'];
		$state = $result['state'];
		$zip = $result['zip'];
		$website = $result['website'];
		$data = $data . '
				<img src="../images/' . $image . '" style="width:200px;" align="left">
				<br><br><br>
				' . $name . '<br>
				' . $phone . '<br>
				' . $address . ' ' . $address2 . '<br>
				' . $city . ' ' . $state . ' ' . $zip . '<br>
				<a href="' . $website . '" target="_bLaNk">' . $website . '</a><br>
		';
		}
	$data = $data . '</div>';
	return $data;
	}
function client_details($id){ //replace this function by get_columns_value_by_id
	$query = "SELECT image, name, phone, city, state, zip, country, email FROM clients WHERE status='A' AND id='$id'";
	$output = mysql_query($query);
	$data = '<div>';
	while($result = mysql_fetch_assoc($output)){
		$image = $result['image'];
		$name = $result['name'];
		$phone = $result['phone'];
		$city = $result['city'];
		$state = $result['state'];
		$zip = $result['zip'];
		$country = $result['country'];
		$email = $result['email'];
		
		if($image != ''){
			$image_path = '<img src="adm_images/' . $image . '" alt="' . $name . '" title="' . $name . '" style="width:100px;" />';
		}
		else{
			$img_src = get_profile_img($id);
			$image_path = '<img src="' . $img_src . '" alt="' . $name  . '" title="' . $name . '" style="width:64px;" />
		';
		}

		
		$data = $data . 
				$image_path . ' 
				<br><br><br>
				' . $name . '<br>
				' . $phone . '<br>
				' . $city . ', ' . $state . ' ' . $zip . ' ' . $country . '<br>
				' . $email . '<br>
		';
		}
	$data = $data . '</div>';
	return $data;
	}
if(!function_exists('clean')) {
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
}
function get_menus_rows_by_location($id,$return){
	$query = "SELECT id, menu, description FROM location_menus WHERE location_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$menu = $result['menu'];
		$description = $result['description'];
		$data = $data . '
		<tr>
			<td>' . $menu . '</td>
			<td>' . $description . '</td>
			<td>
				<a href="locations-menus.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this" alt="Edit" /></a>
				<a href="locations-menus.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" alt="Delete" title="Delete this" /></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function search($columns,$search){
	$q = '';
	$search = ltrim($search);
	$search = rtrim($search);
	$cols = explode(',',$columns);
	$cols_size = count($cols);
	$keywords = explode(' ',$search);
	$keywords_size = count($keywords);
	//for(1) start
	for($x = 1; $x <= $keywords_size; $x++){
		$key = $keywords[$x-1];
		//if(1) start
		if($key != ''){
			//for(2) start
			for($a = 1; $a <= $cols_size; $a++){
//				$q = $q . $cols[$a-1] . " like '" . $key .  "%' OR ";
//				$q = $q . $cols[$a-1] . " like '%" . $key .  "' OR ";
				$q = $q . $cols[$a-1] . " like '%" . $key .  "%' OR ";
				//if(2) start
				/*
				if($a != $cols_size){
					$q = $q . ' OR ';
					}
				*/
				//if(2) close
				}
			//for(2) close
			}
		//if(1) close
		}
	//for(1) close
	return substr_replace($q,'',-4,4); //this will delete extra string " OR " without quotes at the end
	}
function get_menu_items_rows_by_location($id,$return){
	$query = "SELECT location_menu_items.id, location_menu_items.item, location_menu_items.description, location_menu_items.price FROM location_menus, location_menu_items WHERE location_menu_items.menu_id = location_menus.id AND location_menus.location_id = $id";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$item = $result['item'];
		$description = $result['description'];
		$price = $result['price'];
		$data = $data . '
		<tr>
			<td>' . $item . '</td>
			<td>' . $description . '</td>
			<td>' . $price . '</td>
			<td>
				<a href="locations-menus-items.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this" /></a>
				<a href="locations-menus-items.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this" /></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_reviews_rows_by_location($id,$return){
	$query = "SELECT id, client_id, title, rating, date, description FROM client_reviews WHERE location_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$client_id = $result['client_id'];
		$title = $result['title'];
		$rating = $result['rating'];
		$date = getFormattedDate($result['date']);
		$description = $result['description'];
		$data = $data . '
		<tr>
			<td>' . get_client_name_by_id($client_id) . '</td>
			<td>' . $title . '</td>
			<td>' . rating($rating) . '</td>
			<td>' . $date . '</td>
			<td>' . $description . '</td>
			<td>
				<a href="locations-reviews.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this" /></a>
				<a href="locations-reviews.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_reservations_rows_by_location($id,$return){
	$query = "SELECT id, client_id, num_of_guest, reservation_date, reservation_time, arrived, arrival_time, post_comment FROM client_reservations WHERE location_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$client_id = $result['client_id'];
		$num_of_guest = $result['num_of_guest'];
		$reservation_date = getFormattedDate($result['reservation_date']);
		$reservation_time = getFormattedTime($result['reservation_time']);
		$arrived = $result['arrived'];
		$arrival_time = getFormattedTime($result['arrival_time']);
		$post_comment = $result['post_comment'];
		$data = $data . '
		<tr>
			<td>' . get_client_name_by_id($client_id) . '</td>
			<td>' . $reservation_date . '</td>
			<td>' . $reservation_time . '</td>
			<td>' . $num_of_guest . '</td>
			<td>' . $arrived . '</td>
			<td>' . $arrival_time . '</td>
			<td>' . $post_comment . '</td>
			<td>
				<a href="locations-reservations.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this" /></a>
				<a href="locations-reservations.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this" /></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_orders_rows_by_location($id,$return){
	$query = "SELECT id, order_status, order_date, order_time, delivery, delivery_time, delivery_address, delivery_city, delivery_state, delivery_zipcode, order_total, order_payment_type FROM client_orders WHERE location_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$order_status = $result['order_status'];
		$order_date = $result['order_date'];
		$order_time = $result['order_time'];
		$delivery = $result['delivery'];
		$delivery_time = $result['delivery_time'];
		$delivery_address = $result['delivery_address'];
		$delivery_city = $result['delivery_city'];
		$delivery_state = $result['delivery_state'];
		$delivery_zipcode = $result['delivery_zipcode'];
		$order_total = $result['order_total'];
		$order_payment_type = $result['order_payment_type'];
		$data = $data . '
		<tr>
			<td>' . $order_status . '</td>
			<td>' . $order_date . ' / ' . $order_time . '</td>
			<td>' . $delivery . ' / ' . $delivery_time . '</td>
			<td>' . $delivery_address . ', ' . $delivery_city . ', ' . $delivery_state . ', ' . $delivery_zipcode . '</td>
			<td>' . $order_total . '</td>
			<td>' . $order_payment_type . '</td>
			<td>
				<a href="locations-orders.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this" /></a>
				<a href="locations-orders.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this" /></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_menu_items_rows_by_menu($id,$return){
	$query = "SELECT id, item_id, menu_group FROM location_menu_items WHERE menu_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$item = get_item_name_by_article($result['item_id']);
		$menu_group = get_menu_group_by_id($result['menu_group']);
		$data = $data . '
		<tr>
			<td>' . $item . '</td>
			<td>' . $menu_group . '</td>
			<td>
				<a href="locations-menus-items.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this" /></a>
				<a href="locations-menus-items.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this" /></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_menu_groups_rows_by_location($location_id,$return){
	$query = "SELECT * FROM location_menu_group WHERE location_id='$location_id'";
	//echo $query;
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$description = $result['description'];
		$menu_group = $result['menu_group'];
		$data = $data . '
		<tr>
			<td>' . $menu_group . '</td>
			<td>' . $description . '</td>
			<td>
				<a href="locations-menus-groups.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $location_id . '"><img src="images/pencil.gif" title="Edit this" /></a>
				<a href="locations-menus-groups.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this" /></a>
			</td>
		</tr>
		';
		}
	return $data;
	}

function get_item_modifiers_rows_by_item($id,$return){
	$query = "SELECT id, modifier, description, price FROM location_menu_article_modifiers WHERE item_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$modifier = $result['modifier'];
		$description = $result['description'];
		$price = $result['price'];
		$data = $data . '
		<tr>
			<td>' . $modifier . '</td>
			<td>' . $description . '</td>
			<td>' . $price . '</td>
			<td>
				<a href="locations-menus-articles-modifiers.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this"></a>
				<a href="locations-menus-articles-modifiers.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_order_item_modifiers_rows_by_item($id,$return,$new_return_id){ // it is used at locations-orders-items.php
	$query = "SELECT id, modifier, special_instruction, quantity, modifier_price FROM client_order_items_modifier WHERE menu_item_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$modifier = $result['modifier'];
		$special_instruction = $result['special_instruction'];
		$quantity = $result['quantity'];
		$modifier_price = $result['modifier_price'];
		$data = $data . '
		<tr>
			<td>' . get_modifier_name_by_id($modifier) . '</td>
			<td>' . $special_instruction . '</td>
			<td>' . $modifier_price . '</td>
			<td>' . $quantity . '</td>
			<td>' . ($modifier_price*$quantity) . '</td>
			<td>
				<a href="locations-orders-items-modifiers.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $new_return_id . '"><img src="images/pencil.gif" title="Edit this"></a>
				<a href="locations-orders-items-modifiers.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $new_return_id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_order_item_modifiers_rows_by_item_for_clients_only($id,$return,$new_return_id){ // it is used at locations-orders-items.php
	$query = "SELECT id, modifier, special_instruction, quantity, modifier_price FROM client_order_items_modifier WHERE menu_item_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$modifier = $result['modifier'];
		$special_instruction = $result['special_instruction'];
		$quantity = $result['quantity'];
		$modifier_price = $result['modifier_price'];
		$data = $data . '
		<tr>
			<td>' . get_modifier_name_by_id($modifier) . '</td>
			<td>' . $special_instruction . '</td>
			<td>' . $modifier_price . '</td>
			<td>' . $quantity . '</td>
			<td>' . ($modifier_price*$quantity) . '</td>
			<td>
				<a href="clients-orders-items-modifiers.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $new_return_id . '"><img src="images/pencil.gif" title="Edit this"></a>
				<a href="clients-orders-items-modifiers.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $new_return_id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_order_items_rows_by_order($id,$return){
	$query = "SELECT id, menu_id, menu_item_id, quantity, price FROM client_order_items WHERE order_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$menu = $result['menu_id'];
		$item = $result['menu_item_id'];
		$quantity = $result['quantity'];
		$price = $result['price'];
		$total_pirce = (float)$quantity * (float)$price;
		$total_pirce = number_format($total_pirce, 2, '.', '');
		$data = $data . '
		<tr>
			<td>' . get_menu_name_by_id($menu) . '</td>
			<td>' . get_item_name_by_article($item) . '</td>
			<td>' . $quantity . '</td>
			<td>' . $price . '</td>
			<td>' . ($total_pirce) . '</td>
			<td>
				<a href="locations-orders-items.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this"></a>
				<a href="locations-orders-items.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_order_items_rows_by_order_for_clients_only($id,$return){
	$query = "SELECT id, menu_id, menu_item_id, quantity, price FROM client_order_items WHERE order_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$menu = $result['menu_id'];
		$item = $result['menu_item_id'];
		$quantity = $result['quantity'];
		$price = $result['price'];
		$total_pirce = (float)$quantity * (float)$price;
		$total_pirce = number_format($total_pirce, 2, '.', '');
		$data = $data . '
		<tr>
			<td>' . get_menu_name_by_id($menu) . '</td>
			<td>' . get_item_name_by_article($item) . '</td>
			<td>' . $quantity . '</td>
			<td>' . $price . '</td>
			<td>' . $total_pirce . '</td>
			<td>
				<a href="clients-orders-items.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this"></a>
				<a href="clients-orders-items.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
	
function get_order_payments_rows_by_order_for_clients_only($id,$return){
	$query = "SELECT * FROM client_order_payments WHERE order_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$payment_type = get_payment_type_name($result['payment_type']);
		$payment_code = $result['payment_code'];
		$amount = $result['amount'];
		$data = $data . '
		<tr>
			<td>' . $this_id . '</td>
			<td>' . $payment_type . '</td>
			<td>' . $payment_code . '</td>
			<td>' . $amount . '</td>
			<td>
				<a href="clients-orders-payments.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this"></a>
				<a href="clients-orders-payments.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_order_items_modifiers_rows_by_order($id,$return){
	$query = "SELECT id, menu_id, menu_item_id, modifier, modifier_price FROM client_order_items_modifier WHERE order_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$menu = $result['menu_id'];
		$item = $result['menu_item_id'];
		$modifier = $result['modifier'];
		$modifier_price = $result['modifier_price'];
		$data = $data . '
		<tr>
			<td>' . get_menu_name_by_id($menu) . '</td>
			<td>' . get_item_name_by_id($item) . '</td>
			<td>' . $modifier . '</td>
			<td>' . $modifier_price . '</td>
			<td>
				<a href="locations-orders-items-modifiers.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this"></a>
				<a href="locations-orders-items-modifiers.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_orders_rows_by_client($id,$return){
	$query = "SELECT id, order_status, order_date, order_time, togo_time, order_subtotal, order_tax, order_delivery_surcharge, location_id, delivery_time, delivery_address, delivery_city, delivery_state, delivery_zipcode, order_total, order_payment_type FROM client_orders WHERE client_id='$id'";
	//echo $query;
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$order_status = $result['order_status'];
		$order_date = $result['order_date'];
		$location_id = $result['location_id'];
		$togo_time = $result['togo_time'];
		$order_subtotal = $result['order_subtotal'];
		$order_tax = $result['order_tax'];
		$order_delivery_surcharge = $result['order_delivery_surcharge'];
		$order_time = $result['order_time'];
		$delivery = $result['delivery'];
		$delivery_time = $result['delivery_time'];
		$delivery_address = $result['delivery_address'];
		$delivery_city = $result['delivery_city'];
		$delivery_state = $result['delivery_state'];
		$delivery_zipcode = $result['delivery_zipcode'];
		$order_total = $result['order_total'];
		$order_payment_type = $result['order_payment_type'];
		if($order_status == 'started'){
		$status_img = 'icon_enable.gif';
		$status_msg = 'Started';
		}
	elseif($order_status == 'ordered'){
		$status_img = 'order.png';
		$status_msg = 'Ordered';
		}
	else{
		$status_img = 'icon_disable.gif';
		$status_msg = 'Closed';
		}
					
		$order_status = '<img src="images/' . $status_img . '" alt="' . $status_msg . '" title="' . $status_msg . '" border="0" />';
		$location_name = column_value_by_id('locations','name','id',$location_id);
		$data = $data . '
		<tr>
			<td>' . $order_status . '</td>
			<td>' . $location_name . '</td>
			<td>' . $order_date . ' / ' . $order_time . '</td>
			<td>' . $delivery . ' / ' . $delivery_time . '</td>
			<td>' . $togo_time . '</td>
			<td>' . $order_subtotal . '</td>
			<td>' . $order_tax . '</td>
			<td>' . $order_delivery_surcharge . '</td>
			<td>' . $order_total . '</td>
			<td>' . $order_payment_type . '</td>
			<td>
				<a href="clients-orders.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this"></a>
				<a href="clients-orders.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_reviews_rows_by_client($id,$return){
	$query = "SELECT id, location_id, date, title, description, rating FROM client_reviews WHERE client_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$location_id = $result['location_id'];
		$date = getFormattedDate($result['date']);
		$title = $result['title'];
		$description = $result['description'];
		$rating = $result['rating'];
		$data = $data . '
		<tr>
			<td>' . get_location_name_by_id($location_id) . '</td>
			<td>' . $title . '</td>
			<td>' . rating($rating) . '</td>
			<td>' . $date . '</td>
			<td>' . $description . '</td>
			<td>
				<a href="clients-reviews.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this"></a>
				<a href="clients-reviews.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_reservations_rows_by_client($id,$return){
	$query = "SELECT id, location_id, reservation_date, reservation_time, num_of_guest, arrived, arrival_time FROM client_reservations WHERE client_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$location_id = $result['location_id'];
		$reservation_date = getFormattedDate($result['reservation_date']);
		$reservation_time = getFormattedTime($result['reservation_time']);
		$num_of_guest = $result['num_of_guest'];
		$arrived = $result['arrived'];
		$arrival_time = getFormattedTime($result['arrival_time']);
		$data = $data . '
		<tr>
			<td>' . get_location_name_by_id($location_id) . '</td>
			<td>' . $reservation_date . '</td>
			<td>' . $reservation_time . '</td>
			<td>' . $num_of_guest . '</td>
			<td>' . $arrived . '</td>
			<td>' . $arrival_time . '</td>
			<td>
				<a href="clients-reservations.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this"></a>
				<a href="clients-reservations.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}

function countries_combo($n,$s,$states,$rec){
	$query = "SELECT id, code, name, is_default FROM countries WHERE status='A' order by name";
	$output = mysql_query($query);
	$rows = mysql_num_rows($output);
	if($rows > 0 && $rows != ''){
		$data = '<select name="' . $n . '" id="' . $n . '" onchange="getStates(this.value,\'' . $states . '\')" style="width:83%;">';
		$data .= '<option value=""> - - - Please Select Country - - - </option>';
		while($result = mysql_fetch_assoc($output)){
			$id = $result['id'];
			$code = $result['code'];
			$name = $result['name'];
			$is_default = $result['is_default'];
			if($rec == 'new_entry'){
				if($is_default == 'yes'){ $sel = ' selected="selected"'; } else{ $sel = ''; }
				}
			else{
				if($id == $s){ $sel = ' selected="selected"'; } else{ $sel = ''; }
				}
			$data .= '<option value="' . $id . '"' . $sel . '>' . $name . ' (' . $code . ')</option>';
			}
		$data .= '</select>';
		}
	else{
		$data = '<input type="text" name="visible_country" id="visible_country" value="No Country found with Active Status!" class="input-short" disabled="disabled">';
		$data .= ' <a href="countries.php?action=add">Please add/modify here!</a>';
		$data .= '<input type="hidden" name="' . $n . '" id="' . $n . '" value="0">';
		}
	return $data;
	}
function states_combo($s,$country_id){
	$query = "SELECT id, name, code FROM states WHERE status='A' AND country_id='$country_id' ORDER BY name";
	$output = mysql_query($query);
	$rows = mysql_num_rows($output);
	$data = '<select name="state" id="state" class="uniformselect">';
	if($rows > 0 && $rows != ''){
		$data = '<select name="state" id="state" class="uniformselect">';
		$data .= '<option value=""> - - - Please Select State - - - </option>';
		while($result = mysql_fetch_assoc($output)){
			$id = $result['id'];
			$code = $result['code'];
			$name = $result['name'];
			if($id == $s){ $sel = ' selected="selected"'; } else{ $sel = ''; }
                        if ($code==""){
                            $data .= '<option value="' . $id . '"' . $sel . '>' . $name . '</option>';
                        }
                        else{
                            $data .= '<option value="' . $id . '"' . $sel . '>' . $name . ' (' . $code . ')</option>';
                        }
		}
		}
	else{
				$data .= '<option value="">- - - Please Select State - - -</a></option>';

		//$data = '<input type="text" name="visible_state" id="visible_state" value="No State found!" class="input-short" disabled="disabled">';
		//$data .= ' <a href="states.php?action=add">Please add/modify here!</a>';
		//$data .= '<input type="hidden" name="state" id="state" value="0">';
		}
				$data .= '</select>';

	return $data;
	}
function get_average_reviews_by_location($id){
	$query = "SELECT rating FROM client_reviews WHERE location_id='$id'";
	$output = mysql_query($query);
	$total = mysql_num_rows($output);
	$reviews = 0;
	if($total > 0){
		while($result = mysql_fetch_assoc($output)){
			$reviews = $reviews + (int) $result['rating'];
			}
		$data = number_format($reviews / $total, 2);
		}
	else{
		$data = 'No Reviews yet!';
		}
	return $data;
	}
function order_subtotal_by_order_id($id){
	$query = "SELECT price, quantity, menu_id, menu_item_id FROM client_order_items WHERE order_id='$id'";
	$output = mysql_query($query);
	$total_price = 0;
	while($result = mysql_fetch_assoc($output)){
		$price = $result['price'];
		$quantity = $result['quantity'];
		$menu_id = $result['menu_id'];
		$menu_item_id = $result['menu_item_id'];
		$resultant_price = $price * $quantity;
		$modifier_price = get_total_order_modifier_prices($id,$menu_id,$menu_item_id);
		$total_price = (float)$total_price + (float)$resultant_price + (float)$modifier_price;
		$total_price = number_format($total_price, 2, '.', '');
		//echo '<br>' . $resultant_price . ' + ' . $modifier_price . ' = ' . $total_price . '<br>';
		}
	return $total_price;
	}
function payments_total_by_order_id($id){
	$query = "SELECT SUM(amount) AS total FROM `client_order_payments` WHERE order_id='$id'";
	$output = mysql_query($query);
	$result = mysql_fetch_assoc($output);
	return $result['total'];
	}
function get_total_order_modifier_prices($order_id,$menu_id,$menu_item_id){
	$query2 = "SELECT quantity, modifier_price FROM client_order_items_modifier WHERE order_id='$order_id' AND menu_id='$menu_id' AND menu_item_id='$menu_item_id'";
	$output2 = mysql_query($query2);
	$$m_total_price = 0;
	while($result2 = mysql_fetch_assoc($output2)){
		$m_quantity = $result2['quantity'];
		$m_price = $result2['modifier_price'];
		$res_price = $m_quantity * $m_price;
		$m_total_price += $res_price;
		}
	return (int) $m_total_price;
	}
function get_states_rows_by_country($id,$return){
	$query = "SELECT id, name, code, description, status FROM states WHERE country_id='$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$name = $result['name'];
		$code = $result['code'];
		$description = $result['description'];
		$status = $result['status'];
		if($status == 'A'){ $img = 'icon_enable.gif'; $s = 'Active'; }
		elseif($status == 'S'){ $img = 'icon_disable.gif'; $s = 'Inactive'; }
		else{ $img = 'notification-exclamation.gif'; $s = 'Unknown'; }
		$status_img = '<img src="images/' . $img . '" /> (' . $s . ')';
		$data = $data . '
		<tr>
			<td>' . $name . '</td>
			<td>' . $code . '</td>
			<td>' . $description . '</td>
			<td>' . $status_img . '</td>
			<td>
				<a href="states.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this"></a>
				<a href="states.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function location_images($id,$return){
	$query = "SELECT id, priority, image FROM location_images WHERE location_id = '$id'";
	$output = mysql_query($query);
	$rows = mysql_num_rows($output);
	if($rows > 0){
		$data = '<ul class="location_images">';
		while($result = mysql_fetch_assoc($output)){
			$this_id = $result['id'];
			$priority = $result['priority'];
			$image  = $result['image'];
			$edit_img = '
			<a href="locations-images.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '">
				<img src="images/pencil2.png" title="Edit Image">
			</a>
			';
			$del_img = '
			<a href="locations-images.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this image permanently?\')==false) return false;">
				<img src="images/bin2.png" title="Delete Image">
			</a>
			';
			$data .= '
			<li>
				<img src="../images/' . $image . '" style="width:250px; padding:3px;" alt="Priority: ' . $image . '" title="Priority: ' . $image . '" /><br>
				<div>
					<center><b>Priority:</b> ' . $priority . ' &nbsp;&nbsp;&nbsp; ' . $edit_img . ' ' . $del_img . '</center>
				</div>
			</li>';
			}
		$data .= '</ul>';
		return $data;
		}
	}
function get_events_rows_by_location($id,$return){
	$query = "SELECT id, event_name, description, date, event_starttime, event_endtime, status FROM location_events WHERE location_id = '$id'";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$name = $result['event_name'];
		$description = $result['description'];
		$date = getFormattedDate($result['date']);
		$event_starttime = getFormattedTime($result['event_starttime']);
		$event_endtime = getFormattedTime($result['event_endtime']);
		$status = $result['status'];
		if($status == 'A'){ $status = 'Active'; } else{ $status = 'Inactive'; }
		$data = $data . '
		<tr>
			<td>' . $name . '</td>
			<td>' . $description . '</td>
			<td>' . $date . '</td>
			<td>' . $event_starttime . '</td>
			<td>' . $event_endtime . '</td>
			<td>' . $status . '</td>
			<td>
				<a href="locations-events.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this"></a>
				<a href="locations-events.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_entry_rows_by_employee($id,$return){
	$query = "SELECT id, date, time, punch_type FROM employees_entry WHERE employee_id = '$id' ORDER BY DATE DESC";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$date = getFormattedDate($result['date']);
		$time = getFormattedTime($result['time']);
		$punch_type = $result['punch_type'];
		$data = $data . '
		<tr>
			<td>' . $date . '</td>
			<td>' . $time . '</td>
			<td>' . $punch_type . '</td>
			<td>
				<a href="employees-entries.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this"></a>
				<a href="employees-entries.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}
function get_locations_rows_by_hotel($id,$return){
	$query = "SELECT id, location_id, priority FROM hotels_locations WHERE status='A' && hotel_id = '$id' ORDER BY priority";
	$output = mysql_query($query);
	$data = '';
	while($result = mysql_fetch_assoc($output)){
		$this_id = $result['id'];
		$location = get_location_name_by_id($result['location_id']);
		$priority = $result['priority'];
		$data = $data . '
		<tr>
			<td>' . $location . '</td>
			<td>' . $priority . '</td>
			<td>
				<a href="hotels-locations.php?id=' . $this_id . '&action=add&return=' . $return . ',' . $id . '"><img src="images/pencil.gif" title="Edit this"></a>
				<a href="hotels-locations.php?action=delete&id=' . $this_id . '&return=' . $return . ',' . $id . '" onclick="javascript:if(confirm(\'Are you sure you want to delete this record permanently?\')==false) return false;"><img src="images/bin.gif" title="Delete this"></a>
			</td>
		</tr>
		';
		}
	return $data;
	}











function return_url(){
	if(isset($_GET['return']) && $_GET['return'] != ''){
		$re = $_GET['return'];
		$returns = explode(',',$re);
		$go = $returns[0] . '.php?id=' . $returns[1] . '&action=add';
		pageRedirect($go);
		}
	}
function no_record($output,$colspan){
	// this function produces an empty row with message that no record found!
	// this function is created to remove the jquery error on IE
	$rows = mysql_num_rows($output);
	if($rows < 1 || $rows == 0){
		echo '<tr><td class="no_rec" colspan="' . $colspan . '" align="center">No Record found!</td></tr>';
		}
	}

?>