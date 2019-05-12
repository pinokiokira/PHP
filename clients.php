<?php
require_once 'require/security.php';
include 'config/accessConfig.php';
@session_start();

if( isset($_POST['chk_ven_loc']) && $_POST['chk_ven_loc'] == 'y' && isset($_POST['chk_ven_loc_id']) && $_POST['chk_ven_loc_id'] != '' && isset($_POST['chk_ven_id']) && $_POST['chk_ven_id'] != '' ) {
	
	$location_id = trim($_POST['chk_ven_loc_id']);
	$vendor_id = trim($_POST['chk_ven_id']);
	
	$check_v = "SELECT vendor_locations_id FROM vendor_locations WHERE vendor_id!=0 AND location_id = '". $location_id ."' AND vendor_id = '". $vendor_id ."'";
	$res_v = mysql_query($check_v);
    if( mysql_num_rows($res_v) > 0 ) {
		echo 'y';
	} else {
		echo 'n';
	}

exit(0);
}

function get_by($empmaster_id) {
	$qry_get_by = "SELECT * FROM employees_master WHERE empmaster_id='". $empmaster_id ."'";
	$res_get_by = mysql_query($qry_get_by) or die($qry_get_by .'-----'. mysql_error());
	$row_get_by = mysql_fetch_assoc($res_get_by);
	
	if( $row_get_by['first_name'] != '') {
		$return_str = trim($row_get_by['first_name'] .' '. $row_get_by['last_name'] .' (ID: '. $row_get_by['empmaster_id'] .')');
	} else {
		$return_str = '';
	}

return $return_str;
}


if(isset($_POST['editLocation']) && $_POST['editLocation'] == '1010'){
    //echo '<pre>';
	//print_r($_POST);
	//print_r($_SESSION);
	//echo '</pre>';
	//die();

    $id = $_POST['id'];
    $vendor_id = $_POST['client_id'];
    $location_id = $_POST['location_id'];
    $sales_variance = $_POST['sale_variance'];
    $default_delivery_type = @implode(',', $_POST['default_delivery_type']);
    $default_terms = @implode(',', $_POST['default_terms']);
    $default_payment_type = @implode(',', $_POST['default_payment_type']);
    $primary_contact_employee_id = $_POST['primary_contact_employee_id'];
    $primary_contact = $_POST['primary_contact'];
    $primary_contact_email = $_POST['primary_contact_email'];
    $primary_contact_phone = $_POST['primary_contact_phone'];
    $notes = addslashes($_POST['note']);
    $reminder = $_POST['reminder'];
	
    //$created_by = $_POST['created_by'];
    //$created_on = $_POST['created_on'];
    //$created_datetime = $_POST['created_datetime'];


	$vendor_locations_id = $_POST['vendor_locations_id'];    
	//echo 
	$check_v = "SELECT vendor_locations_id FROM vendor_locations WHERE location_id = '$location_id'  and  vendor_id = '$vendor_id'";
	
	$res_v = mysql_query($check_v);
    if(mysql_num_rows($res_v)>0){
     //echo 'ok1';   
		$last_on = 'TeamPanel';
		$last_by = $_SESSION['empmaster_id'];
		
        //echo 
		$insertSql = "UPDATE vendor_locations SET sales_variance = '$sales_variance', default_delivery_type = '$default_delivery_type', default_terms = '$default_terms', default_payment_type = '$default_payment_type', primary_contact_employee_id = '$primary_contact_employee_id', primary_contact = '$primary_contact', primary_contact_email = '$primary_contact_email', primary_contact_phone = '$primary_contact_phone', notes = '$notes', reminder = '$reminder',  last_on = '$last_on', last_by = '$last_by', last_datetime = NOW() WHERE vendor_locations_id = '$vendor_locations_id'  and  vendor_id = '$vendor_id'";
		//exit;
		 $ins = mysql_query($insertSql);
        
    }
    else{
	//echo 'ok2';
		$created_on = 'TeamPanel';
		$created_by = $_SESSION['empmaster_id'];
		
		//$insertSql = "INSERT INTO vendor_locations SET vendor_id = '$vendor_id', location_id = '$location_id', sales_variance = '$sales_variance', default_delivery_type = '$default_delivery_type', default_terms = '$default_terms', default_payment_type = '$default_payment_type', primary_contact_employee_id = '$primary_contact_employee_id', primary_contact = '$primary_contact', primary_contact_email = '$primary_contact_email', primary_contact_phone = '$primary_contact_phone', notes = '$notes', reminder = '$reminder', created_by = '$created_by', created_on = '$created_on', created_datetime = '$created_datetime' ";
		//echo 
		$insertSql = "INSERT INTO vendor_locations SET vendor_id = '$vendor_id', location_id = '$location_id', sales_variance = '$sales_variance', default_delivery_type = '$default_delivery_type', default_terms = '$default_terms', default_payment_type = '$default_payment_type', primary_contact_employee_id = '$primary_contact_employee_id', primary_contact = '$primary_contact', primary_contact_email = '$primary_contact_email', primary_contact_phone = '$primary_contact_phone', notes = '$notes', reminder = '$reminder', created_on = '$created_on', created_by = '$created_by', created_datetime = NOW()";
		//exit;
		$ins = mysql_query($insertSql);
	 
	 $getVend_name = mysql_fetch_assoc(mysql_query("SELECT name from vendors WHERE id = '".$vendor_id."'"));
	 $vnder_name = $getVend_name['name'];
	 $subject = $vnder_name.' Link';
	 $msg = $vnder_name.' has linked his offerring to you. Please take a look at the products we offer';
	 $inser_mes = "INSERT INTO employee_master_location_storepoint SET 
	 			  	sent_by_type = 'Employee Master',
				  	emp_master_id='".$_SESSION['empmaster_id']."',
					location_id = '".$location_id."',
					sent_datetime = NOW(),
					subject='".$subject."',
					message = '".$msg."',
					`read`='No'";
			mysql_query($inser_mes);		
	 
	}

    //echo $insertSql; die;
    // or die(mysql_error());

    $_SESSION['ins_Loc'] = 'ok';

    header('location: storepoint_clients.php');

}


?>



<?php


$now = date("Y-m-d");

$sql = " SELECT id,location_id1,status,email,`password`,primary_type,name,address,address2,city,state,zip,country,phone,image,createdon,date_added,created_by,
created_date,last_datetime,ids,sent_by_type,emp_master_id,location_id,location_employee_id,sent_datetime,subject,message,`read`,read_date,reply,tid,lname,subtype,unread_count from (SELECT
locations.id,
locations.id as location_id1,
locations.status,
locations.email,
locations.`password`,
locations.primary_type,
locations.name,
locations.address,
locations.address2,
locations.city,
locations.state,
locations.zip,
locations.country,
locations.phone,
locations.image,
locations.createdon,
locations.date_added,
locations.created_by,
locations.created_date,
locations.last_datetime,
employee_master_location_storepoint.id as ids,
employee_master_location_storepoint.sent_by_type,
employee_master_location_storepoint.emp_master_id,
employee_master_location_storepoint.location_id,
employee_master_location_storepoint.location_employee_id,
employee_master_location_storepoint.sent_datetime,
employee_master_location_storepoint.subject,
employee_master_location_storepoint.message,
employee_master_location_storepoint.`read`,
employee_master_location_storepoint.read_date,
employee_master_location_storepoint.read_time,
employee_master_location_storepoint.reply,
location_types.id as tid,
location_types.name as lname,
location_types.subtype,
(SELECT COUNT(message ) FROM employee_master_location_storepoint, locations WHERE employee_master_location_storepoint.`read` = 'No' AND employee_master_location_storepoint.location_id = locations.id AND employee_master_location_storepoint.emp_master_id = '" . $_SESSION['empmaster_id'] . "') AS unread_count
FROM
locations
Inner Join employee_master_location_storepoint ON locations.id = employee_master_location_storepoint.location_id
Inner Join location_types ON locations.primary_type = location_types.id  where employee_master_location_storepoint.emp_master_id = '" . $_SESSION['empmaster_id'] . "'
GROUP BY locations.id
UNION ALL
SELECT
locations.id,
locations.id as location_id1,
locations.status,
locations.email,
locations.`password`,
locations.primary_type,
locations.name,
locations.address,
locations.address2,
locations.city,
locations.state,
locations.zip,
locations.country,
locations.phone,
locations.image,
locations.createdon,
locations.date_added,
locations.created_by,
locations.created_date,
locations.last_datetime,
employee_master_location_storepoint.id as ids,
employee_master_location_storepoint.sent_by_type,
employee_master_location_storepoint.emp_master_id,
employee_master_location_storepoint.location_id,
employee_master_location_storepoint.location_employee_id,
employee_master_location_storepoint.sent_datetime,
employee_master_location_storepoint.subject,
employee_master_location_storepoint.message,
employee_master_location_storepoint.`read`,
employee_master_location_storepoint.read_date,
employee_master_location_storepoint.read_time,
employee_master_location_storepoint.reply,
location_types.id as tid,
location_types.name as lname,
location_types.subtype,
(SELECT COUNT(message ) FROM employee_master_location_storepoint WHERE employee_master_location_storepoint.`read` = 'No' AND employee_master_location_storepoint.location_id = locations.id AND employee_master_location_storepoint.emp_master_id = '" . $_SESSION['empmaster_id'] . "') AS unread_count
FROM
locations
INNER JOIN purchases as p ON p.location_id =  locations.id
INNER JOIN employees_master ON p.vendor_id = employees_master.StorePoint_vendor_Id
INNER Join employee_master_location_storepoint ON locations.id = employee_master_location_storepoint.location_id
Inner Join location_types ON locations.primary_type = location_types.id
where employees_master.empmaster_id = '" . $_SESSION['empmaster_id'] . "'
GROUP BY locations.id

UNION ALL
SELECT
locations.id,
locations.id as location_id1,
locations.status,
locations.email,
locations.`password`,
locations.primary_type,
locations.name,
locations.address,
locations.address2,
locations.city,
locations.state,
locations.zip,
locations.country,
locations.phone,
locations.image,
locations.createdon,
locations.date_added,
locations.created_by,
locations.created_date,
locations.last_datetime,
employee_master_location_storepoint.id as ids,
employee_master_location_storepoint.sent_by_type,
employee_master_location_storepoint.emp_master_id,
employee_master_location_storepoint.location_id,
employee_master_location_storepoint.location_employee_id,
employee_master_location_storepoint.sent_datetime,
employee_master_location_storepoint.subject,
employee_master_location_storepoint.message,
employee_master_location_storepoint.`read`,
employee_master_location_storepoint.read_date,
employee_master_location_storepoint.read_time,
employee_master_location_storepoint.reply,
location_types.id as tid,
location_types.name as lname,
location_types.subtype,
(SELECT COUNT(message ) FROM employee_master_location_storepoint WHERE employee_master_location_storepoint.`read` = 'No' AND employee_master_location_storepoint.location_id = locations.id AND employee_master_location_storepoint.emp_master_id = '" . $_SESSION['empmaster_id'] . "') AS unread_count
FROM
locations
INNER JOIN vendor_locations ON vendor_locations.location_id = locations.id
LEFT JOIN purchases as p ON p.location_id =  locations.id
JOIN employees_master ON vendor_locations.vendor_id = employees_master.StorePoint_vendor_Id
LEFT Join employee_master_location_storepoint ON locations.id = employee_master_location_storepoint.location_id
Inner Join location_types ON locations.primary_type = location_types.id
where employees_master.empmaster_id = '" . $_SESSION['empmaster_id'] . "'
GROUP BY locations.id

)  t group by id";

$resultJobs = mysql_query($sql) or die(mysql_error());


//echo $numr = mysql_num_rows($resultJobs); die;
function isImage($url) {
    $params = array('http' => array(
            'method' => 'HEAD'
    ));
    $ctx = stream_context_create($params);
    $url = str_replace(" ", "%20", $url);
    $fp = @fopen($url, 'rb', false, $ctx);
    if (!$fp)
        return false;  // Problem with url

    $meta = stream_get_meta_data($fp);
    if ($meta === false) {
        fclose($fp);
        return false;  // Problem reading data from url
    }

    $wrapper_data = $meta["wrapper_data"];
    if (is_array($wrapper_data)) {
        foreach (array_keys($wrapper_data) as $hh) {
            if (substr($wrapper_data[$hh], 0, 19) == "Content-Type: image") { // strlen("Content-Type: image") == 19
                fclose($fp);
                return true;
            }
        }
    }

    fclose($fp);
    return false;
}

function getPrimaryTypeImage($primary_type) {
    $img = "";

    if ($primary_type == '1') {
        $img = "primary-type/Default Primary Type - Restaurants.png";
    }

    if ($primary_type == '2') {
        $img = "primary-type/Default Primary Type - Bar.png";
    }

    if ($primary_type == '3') {
        $img = "primary-type/Default Primary Type - Lounge.png";
    }

    if ($primary_type == '4') {
        $img = "primary-type/Default Primary Type - Private.png";
    }

    if ($primary_type == '7') {
        $img = "primary-type/Default Primary Type - Club.png";
    }

    if ($primary_type == '6') {
        $img = "primary-type/Default Primary Type - Health.png";
    }

    if ($primary_type == '9') {
        $img = "primary-type/Default Primary Type - Home.png";
    }

    if ($primary_type == '67') {
        $img = "primary-type/Default Primary Type - Other.png";
    }

    if ($primary_type == '71') {
        $img = "primary-type/Default Primary Type - Quick Service.png";
    }

    if ($primary_type == '5') {
        $img = "primary-type/Default Primary Type - Retail.png";
    }

    if ($primary_type == '10') {
        $img = "primary-type/Default Primary Type - Travel.png";
    }

    if ($primary_type == '8') {
        $img = "primary-type/Default Primary Type - Recreation.png";
    }
    return $img;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>SoftPoint | VendorPanel</title>
        <link rel="stylesheet" href="css/style.default.css" type="text/css" />
        <link rel="stylesheet" href="css/responsive-tables.css" />
        <link rel="stylesheet" href="css/jquery.dateLists.css" type="text/css" />
        <link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
        
        <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
        <script type="text/javascript" src="js/modernizr.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/jquery.uniform.min.js"></script>
        <script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
        <link rel="stylesheet" href="prettify/prettify.css" type="text/css" />
        <script type="text/javascript" src="js/jquery.jgrowl.js"></script>
        <script type="text/javascript" src="js/jquery.alerts.js"></script>
        <script type="text/javascript" src="js/elements.js"></script>
        <script type="text/javascript" src="prettify/prettify.js"></script>
        <script type="text/javascript" src="js/jquery.dateLists.min.js"></script>
        <script type="text/javascript" src="js/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
        <script type="text/javascript" src="js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="js/jquery.cookie.js"></script>
        <script type="text/javascript" src="js/jquery.form.min.js"></script>
        <script type="text/javascript" src="js/jquery.alerts.js"></script>
        <!--<script type="text/javascript" src="js/jquery.blockUI.js"></script>-->
        <script type="text/javascript" src="js/responsive-tables.js"></script>
        <script type="text/javascript" src="js/custom.js"></script>
        <!--<script type="text/javascript" src="js/main.js"></script>-->
        <style>
            body {
                top:0px!important;
            }
            .widgetcontent {
                background: #fff;
                padding: 15px 12px;
                border: 2px solid #0866c6;
                border-top: 0;
                margin-bottom: 20px;
            }
            .goog-te-banner-frame{  margin-top: -50px!important; }
            .error {
                color: #FF0000;
                padding-left:10px;
            }
            /*.row-fluid .span4 {
                width: 32.6239%;
                    margin-left:10px;
            }*/
            .span4 {
                float:left;
                width:28.5%!important;
                min-height:600px;
                margin-left:1.5%!important;
            }
            /*.unread showJobs selected{background-color:#cccccc;}*/
            table.table tbody tr.selected, table.table tfoot tr.selected {
                background-color: #808080;
            }
            .dataTables_filter input{ height:28px !important;}
			.dataTables_filter {
			  top: 5px;
			}
        </style>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                jQuery('#global_tbl').dataTable({
                    "sPaginationType": "full_numbers",
                    "aaSorting": [[2, "asc"]],
                    "bJQuery": true,
                    "fnDrawCallback": function (oSettings) {
                        //  jQuery.uniform.update();
                    }
                });
				
				jQuery(".cl_order1").live('click', function () {
					var ths  = jQuery(this).parents('tr');
					console.log(ths);
                    jQuery(".gradeX").attr("class", "gradeX cl_order");
                    jQuery(ths).attr("class", "gradeX cl_order selected");
                    callajax1(ths);
                });
				
                jQuery(".cl_order").click(function () {
					var ths  = jQuery(this).parents('tr');
					console.log(ths);
                    jQuery(".gradeX").attr("class", "gradeX cl_order");
                    jQuery(ths).attr("class", "gradeX cl_order selected");
                    callajax(ths);

                });

            });
            function getStorepointLocation(sId, loc_id)
            {
                var dataurl = "storepoint_getStorepointLocationInq.php?sId=" + sId + "&loc_id=" + loc_id;
                jQuery.ajax({
                    url: dataurl,
                    type: "get",
                    cache: false,
                    async: false,
                    dataType: "text",
                    success: function (data) {
                        if (data == '0') {
                            //location.reload();
                            return false;
                        }
                        else {
                            jQuery("#fdetail").css("display", "block");
                            jQuery("#fdetail").html(data);
                        }
                    }
                });
            }
			function callajax1(obj){
				var jobId = jQuery(obj).attr("id");
                var loc_id = jQuery(obj).attr("l_id");
                //jQuery("#".jobId).css("background-color","#808080");
                jQuery("#loc_id").val(loc_id);

                getStorepointLocation(jobId, loc_id);

                getEditLocation(jobId, loc_id);
			
			}
            function callajax(obj)
            {
                var jobId = jQuery(obj).attr("id");
                var loc_id = jQuery(obj).attr("l_id");
                //jQuery("#".jobId).css("background-color","#808080");
                jQuery("#loc_id").val(loc_id);

                getStorepointLocation(jobId, loc_id);

                //getEditLocation(jobId, loc_id);
            }
            var submitAction = true;
            function submitPostListing()
            {
                /*var data = jQuery("#frmInquiry").serialize();
                 if(jQuery("#message").val().length<=5)
                 {
                 alert("Please enter a proper message containing at least 6 characters.");
                 }
                 if(submitAction)
                 {
                 submitAction=false;
                 jQuery.post("post_job_inquiry.php", data, function(response){
                 alert("Thank you for inquiring about this job listing. Your message and allowed share profile has been sent to the employer.");
                 submitAction=true;

                 jQuery("#closePostListing").click();
                 });
                 }*/


            }
        </script>


        <style>
            .ui-datepicker{ z-index: 1100 !important; }
			
			#default_delivery_type_chzn ul.chzn-choices li.search-field input[type="text"] {
				_height: 32px !important;
				height: 14px !important;
			}
			#default_terms_chzn ul.chzn-choices li.search-field input[type="text"] {
				_height: 32px !important;
				height: 14px !important;
			}
			#default_payment_type_chzn ul.chzn-choices li.search-field input[type="text"] {
				_height: 32px !important;
				height: 14px !important;
			}
			.textCenter 
			{
				 text-align:ceter !important;
			}
			
			
        </style>
    </head>
    <!--head-->

    <body>

        <div class="mainwrapper">
            <?php require_once('require/top.php'); ?>
            <?php require_once('require/left_nav.php'); ?>
            <div class="rightpanel">
                <ul class="breadcrumbs">
                    <li><a href="help.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
                    <li>Clients</li>
                    <li class="right"> <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
                        <ul class="dropdown-menu pull-right skin-color">
                            <li><a href="default">Default</a></li>
                            <li><a href="navyblue">Navy Blue</a></li>
                            <li><a href="palegreen">Pale Green</a></li>
                            <li><a href="red">Red</a></li>
                            <li><a href="green">Green</a></li>
                            <li><a href="brown">Brown</a></li>
                        </ul>
                    </li>
                    <?php require_once("lang_code.php");?>
                </ul>
                <div class="pageheader">
                    <!--<form action="results.html" method="post" class="searchbar">
                              <input type="text" name="keyword" placeholder="To search type and hit enter..." />
                          </form>-->
                    <div class="pageicon"><span class="iconfa-user"></span></div>
                    <div class="pagetitle">
                        <h5>Browse through your Clients and customers</h5>
                        <h1>Clients</h1>
                    </div>                    
                </div>
                <!--pageheader-->
                                        <?php //echo "<pre>"; print_r($_SESSION);
                                        ?>
                <div class="maincontent">
                    <div class="maincontentinner"><!--  style="padding-right:0;" -->
                        <!--row-fluid-->

<?php include_once 'require/footer.php'; ?>
                        <!--footer-->

                    </div>
                    <!--maincontentinner-->
                </div>
                <!--maincontent-->

            </div>
            <!--rightpanel-->

        </div>
        <!--mainwrapper-->
        <div id="mymodal" class="modal hide fade">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">&times;</button>
                <h3>Inquiry</h3>
            </div>
            <div class="modal-body" id="mymodal_html">

            </div>
            <div class="modal-footer" style="text-align:center;">
                <a data-dismiss="modal" href="#" class="btn" id="closePostListing" style="color:#0866C6 !important;">Cancel</a>
                <button  onClick="submitPostListing();" class="btn btn-primary">Submit</button>
            </div>
        </div>

        <style>
            .modal-body label { margin-top: 3px; }
            .modal-body td { vertical-align: top; }
            .btn-default{ color: #000 !important; }
            .chzn-container{ width: 310px !important; margin-bottom: 10px; }
            .default { height: 32px !important; }
            .search-field {  min-height: 28px !important; }
        </style>

        <div id="composeModal" class="modal hide fade" style="max-height:500px !important;">
            <form id="frmComposeEdit" name="" action="" method="post" class="">
                
                <div class="modal-header" style="max-height:50px !important;">
                    <button type="button" onClick="cancel_edit_popup()"  class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Edit Location Details</h3>
                </div>
                <div class="modal-body" id="editLoca_body" style="max-height:350px !important;">
                    <input type="hidden" name="editLocation" value="1010" />
                    <input type="hidden" id="loc_id" name="location_id" value="<?php echo $_SESSION['loc'];?>" />
                    <input type="hidden" name="client_id" value="<?php echo $_SESSION['StorePointVendorID'];?>" />
                    
                </div>
                <div class="modal-footer" style="text-align: center;max-height:50px !important;">
                    <p class="stdformbutton">
                        <button type="button" id="btnCancel" data-dismiss="modal" class="btn btn-default" style="padding: 5px 12px 5px;" onClick="cancel_edit_popup();">Cancel</button>
                        <button type="button" id="btnCompose" name="btnCompose"  class="btn btn-primary" style="padding: 5px 12px 5px;" onClick="return validate_edit_popup();">Submit</button>
                    </p>
                </div>
            </form>
        </div>
        
       
        <div id="composeModalAdd" class="modal hide fade" style="max-height:500px !important;">
            <form id="frmCompose" name="frmCompose" action="" method="post" class="frmComposClss">
                
                <div class="modal-header" style="max-height:50px !important;">
                    <button type="button" onClick="cancel_edit_popup()" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Add Location Details</h3>
                </div>
                <div class="modal-body" id="addLoca_body" style="max-height:350px !important;">
                    <input type="hidden" name="editLocation" value="1010" />
                    <input type="hidden" id="loc_id" name="location_id" value="<?php echo $_SESSION['loc'];?>" />
                    <input type="hidden" name="client_id" value="<?php echo $_SESSION['StorePointVendorID'];?>" />
                    
                </div>
                <div class="modal-footer" style="text-align: center;max-height:50px !important;">
                    <p class="stdformbutton">
                        <button type="button" id="btnCancel" data-dismiss="modal" class="btn btn-default" style="padding: 5px 12px 5px;" onClick="cancel_edit_popup();">Cancel</button>
                        <button type="button" id="btnCompose" name="btnCompose"  class="btn btn-primary" style="padding: 5px 12px 5px;" onClick="return validate_edit_popup1();">Submit</button>
                    </p>
                </div>
            </form>
        </div>

        <?php if (isset($_SESSION['ins_Loc'])) { ?>
        <script>                      
            jAlert('Record updated.','Alert');
            return false;            
            <?php unset($_SESSION['ins_Loc']); ?>
        </script>
        <?php } ?>
        
        <script type="text/javascript">
			 function getaddLocation(id){
                var dataurl = "storepoint_get_vender_location_info.php?id=" + id + "&loc_id=";
                jQuery.ajax({
                    url: dataurl,
                    type: "get",
                    cache: false,
                    async: false,                    
                    success: function (data) {                        
                           jQuery('#addLoca_body').html(data);
                           jQuery("#composeModalAdd").modal("show");
                    }
                });
            }
			
            jQuery(document).ready(function(event){
                jQuery("#default_terms").chosen();
                jQuery("#default_delivery_type").chosen();
                jQuery("#default_payment_type").chosen();
            });

            jQuery(function () {
                jQuery("#reminder").datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true,
                    //minDate: 0
                });

            });
			 function validate_edit_popup1(){
                var myForm = document.getElementById('frmCompose');
                var flag = 0;
				if(jQuery("#loc_id_search").val()==''){
					jAlert('Please select Location Id from Search!','Alert');
					flag = 1; return false;
				}else if(jQuery("#primary_contact").val()==''){               
                    jAlert('Please enter primary contact!','Alert');
                    flag = 1; return false;
                }
//                if(myForm.sale_variance.value == ''){
//                    jAlert('Please enter sale variance!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.default_delivery_type.value == ''){
//                    jAlert('Please enter default delivery type!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.default_terms.value == ''){
//                    jAlert('Please enter default terms!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.default_payment_type.value == ''){
//                    jAlert('Please enter default payment type!','Alert');
//                    return false;
//                }
//                if(myForm.note.value == ''){
//                    jAlert('Please enter note!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.reminder.value == ''){
//                    jAlert('Please enter reminder!','Alert');
//                    flag = 1; return false;
//                }
                if(flag == 0){
                    jQuery('.frmComposClss').submit();
                }
            }
            function validate_edit_popup(){
                var myForm = document.getElementById('frmComposeEdit');
                var flag = 0;
				if(jQuery("#loc_id").val()==''){
					jAlert('Please select Location Id from Search!','Alert');
					flag = 1; return false;
				}else if(jQuery("#primary_contact").val()==''){               
                    jAlert('Please enter primary contact!','Alert');
                    flag = 1; return false;
                }
//                if(myForm.sale_variance.value == ''){
//                    jAlert('Please enter sale variance!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.default_delivery_type.value == ''){
//                    jAlert('Please enter default delivery type!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.default_terms.value == ''){
//                    jAlert('Please enter default terms!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.default_payment_type.value == ''){
//                    jAlert('Please enter default payment type!','Alert');
//                    return false;
//                }
//                if(myForm.note.value == ''){
//                    jAlert('Please enter note!','Alert');
//                    flag = 1; return false;
//                }
//                if(myForm.reminder.value == ''){
//                    jAlert('Please enter reminder!','Alert');
//                    flag = 1; return false;
//                }
                if(flag == 0){
                    jQuery('#frmComposeEdit').submit();
                }
            }
            function cancel_edit_popup(){ 
				 window.location.reload();
				 return false;              
                var myForm = document.getElementById('frmComposeEdit');
                myForm.primary_contact.value = '';
                myForm.sale_variance.value = '';
                myForm.default_delivery_type.value = '';
                myForm.default_terms.value = '';
                myForm.default_payment_type.value = '';
                myForm.primary_contact_employee_id.value = '';
                myForm.primary_contact_email.value = '';
                myForm.primary_contact_phone.value = '';
                myForm.note.value = '';
                myForm.reminder.value = '';                                            
                myForm.created_datetime.value = '<?php echo date('Y-m-d h:i');?>';                               jQuery('#created_datetime').val('<?php echo date('Y-m-d h:i:s');?>');                
                jQuery('#last_tbl').remove();
            }

            function getEditLocation(id, loc_id){
                var dataurl = "storepoint_get_vender_location_info.php?id=" + id + "&loc_id=" + loc_id;
                jQuery.ajax({
                    url: dataurl,
                    type: "get",
                    cache: false,
                    async: false,                    
                    success: function (data) {
                        if (data == 'notfound') {
                            cancel_edit_popup();
                            return false;
                        }
                        else {
                            jQuery('#editLoca_body').html(data);
                            

                        }
                    }
                });
            }
            
            function get_employee(emp_id){
                var dataurl = "storepoint_get_employee_info.php?emp_id=" + emp_id ;
                jQuery.ajax({
                    url: dataurl,
                    type: "get",
                    cache: false,
                    async: false,                    
                    success: function (data) {
                        var res = jQuery.parseJSON(data);
						
						if(res.first_name!=undefined){
						
                        jQuery('#primary_contact').val(res.first_name+' '+res.last_name);
						}else{
						
						jQuery('#primary_contact').val(' ');
						}
                        jQuery('#primary_contact_email').val(res.email);
                        jQuery('#primary_contact_phone').val(res.telephone);
                    }
                });
            }
                
        </script>
        <?php //print_r($_SESSION); ?>
    </body>
</html>
<script>
<?php if ($firstrow != "") { ?>
        jQuery(document).ready(function () {
            jQuery("#<?php echo $firstrow; ?>").trigger("click");
        });
<?php } ?>
</script>

