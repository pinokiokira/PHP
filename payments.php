<?php
require_once 'require/security.php';
include 'config/accessConfig.php'; 

$empmaster_id=$_SESSION['client_id'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
$vendor_id = $vender['StorePoint_vendor_Id'];


function returnStatusImg($s){
    $img='';
    switch($s){
        case 'active':
            $img = '<img src="images/Active, Corrected, Delivered - 16.png" alt="Active" title="Active" />';
            break;
        case 'inactive':
            $img = '<img src="images/inactive.png" alt="Inactive" title="Inactive" />';
            break;
        case 'suspended':
            $img = '<img src="images/urgent.png" alt="Suspended" title="Suspended" />';
            break;
    }
    return $img;
}
if ($_POST['editvendorsubmit'] == "submitted") {
    $id = mysql_real_escape_string($_POST['id']);
    $location_id = mysql_real_escape_string($_POST['location_id']);
    $vendor_name = mysql_real_escape_string($_POST['vendor_name']);
    $contact_name = mysql_real_escape_string($_POST['vendor_contact']);
    $email = mysql_real_escape_string($_POST['email']);
    $address = mysql_real_escape_string($_POST['vendor_address']);
    $address2 = mysql_real_escape_string($_POST['vendor_address2']);
    $city = mysql_real_escape_string($_POST['vendor_city']);
    $state = mysql_real_escape_string($_POST['vendor_state']);
    $zip = mysql_real_escape_string($_POST['vendor_zip']);
    $country = mysql_real_escape_string($_POST['vendor_country']);
    $phone = mysql_real_escape_string($_POST['vendor_phone']);
    $fax = mysql_real_escape_string($_POST['vendor_fax']);
    $created_date = date('Y-m-d H:i:s');
    $created_on = "ManagePanel Website";
    $query = "UPDATE vendors SET  status='active', name='" . $vendor_name . "', contact='" . $contact_name . "', email='" . $email . "', address='" . $address . "', address2='" . $address2 . "', city='" . $city . "', state='" . $state . "', zip='" . $zip . "', country='" . $country . "', phone='" . $phone . "', fax='" . $fax . "', created_on='" . $created_on . "', created_date='" . $created_date . "' WHERE id=" . $location_id ;
    $result = mysql_query($query) or die(mysql_error());
	header('location:storepoint_payments.php?msg=Location Updated Successfully!'); 
}
if ($_POST['addvendorsubmit'] == "addvendorsubmit") {
    $id = mysql_real_escape_string($_POST['id']);
    $location_id = mysql_real_escape_string($_POST['location_id']);
    $vendor_name = mysql_real_escape_string($_POST['vendor_name']);
    $contact_name = mysql_real_escape_string($_POST['vendor_contact']);
    $email = mysql_real_escape_string($_POST['email']);
    $address = mysql_real_escape_string($_POST['vendor_address']);
    $address2 = mysql_real_escape_string($_POST['vendor_address2']);
    $city = mysql_real_escape_string($_POST['vendor_city']);
    $state = mysql_real_escape_string($_POST['vendor_state']);
    $zip = mysql_real_escape_string($_POST['vendor_zip']);
    $country = mysql_real_escape_string($_POST['vendor_country']);
    $phone = mysql_real_escape_string($_POST['vendor_phone']);
    $fax = mysql_real_escape_string($_POST['vendor_fax']);
    $created_date = date('Y-m-d H:i:s');
    $created_on = "ManagePanel Website";
    $query = "INSERT INTO vendors SET  status='active', name='" . $vendor_name . "', contact='" . $contact_name . "', email='" . $email . "', address='" . $address . "', address2='" . $address2 . "', city='" . $city . "', state='" . $state . "', zip='" . $zip . "', country='" . $country . "', phone='" . $phone . "', fax='" . $fax . "', created_on='" . $created_on . "', created_date='" . $created_date . "'";
    $result = mysql_query($query) or die(mysql_error());
	header('location:storepoint_payments.php?msg=Location Added Successfully!');
}
if($_POST['payment_frm'] == 'submitted'){
   // $location_id = $_SESSION['loc'];
    $employee_id = $vendor_id;
    $datetime = date('Y-m-d H:i:s');
    $location_id = mysql_real_escape_string($_POST['location_id']);
    $type = mysql_real_escape_string($_POST['type']);
    $amount = mysql_real_escape_string($_POST['amount']);
    $reference = mysql_real_escape_string($_POST['reference']);
	$bank_id = mysql_real_escape_string($_POST['bank_id']);
    $description = mysql_real_escape_string($_POST['description']);

    $query = "INSERT INTO purchases_payments SET
                    location_id='$location_id',
					bank_id = '$bank_id',
                    vendor_id='$employee_id',
                    datetime='$datetime',
                    `type`='$type',
                    amount='$amount',					
                    reference='$reference',
                    description='$description',
					created_datetime ='".date('Y-m-d H:i:s')."', 
					created_on='TeamPanel',
					created_by='".$_SESSION['client_id']."'";
					//die();
					
    $result = mysql_query($query) or die(mysql_error());
	header('location:storepoint_payments.php?location='.$location_id.'&msg=Payment added successfully');
}

function formatNumber($num){
    if($num < 0){
        return '($' . number_format(abs($num), 2, '.', ',') . ')';
    }else{
        return '$' . number_format($num, 2, '.', ',');
    }
}
function calcIntervalTotal($vendor,$greaterThan,$lessThan,$special){//calculates totals for time intervals
    $GTAppend = '';
    $LTAppend = '';
    $GTAppend2 = '';
    $LTAppend2 = '';
    if($special == ''){//normal scenario: between two points in time
        if($greaterThan != ''){
            $GTAppend = "AND completed_datetime <= '$lessThan'";
            $GTAppend2 = "AND datetime <= '$lessThan'";
        }
        if($lessThan != ''){
            $LTAppend = "AND completed_datetime > '$greaterThan'";
            $LTAppend2 = "AND datetime > '$greaterThan'";
        }
    }else{//special scenario: before or after a point in time
        if($special == 'before'){//occured before $lessThan time
            $LTAppend = "AND completed_datetime < '$lessThan'";
            $LTAppend2 = "AND datetime < '$lessThan'";
        }else if($special == 'after'){//occured after $greaterThan time
            $GTAppend = "AND completed_datetime > '$greaterThan'";
            $GTAppend2 = "AND datetime > '$greaterThan'";
        }
    }

    //get total for time period
   $query1 = "SELECT sum(total)-sum(applied_amount)
              FROM purchases p
              WHERE p.location_id=" . $vendor . " AND p.status='Completed' AND p.location_id=" . $vendor . " $LTAppend $GTAppend";
    $result1 = mysql_query($query1) or die(mysql_error());
    $row1 = mysql_fetch_row($result1);
	

    //get unapplied payment amount for time period
    $query2 = "SELECT sum(amount)-sum(applied_amount)
                   FROM purchases_payments
                   WHERE location_id=" . $vendor . " AND location_id=" . $vendor . " $LTAppend2 $GTAppend2 ";
    $result2 = mysql_query($query2) or die(mysql_error());
    $row2 = mysql_fetch_row($result2);
	
    return $row1[0]-$row2[0];
}

//calculate totals for each vendor
$vendor_totals = array();
$query1 = "SELECT location_id,sum(total)
           FROM purchases
           WHERE vendor_id=" . $vendor_id . " AND status='Completed'
           GROUP BY location_id";
$result1 = mysql_query($query1) or die(mysql_error());
while($row = mysql_fetch_row($result1)){
    $vendor_totals[$row[0]] = $row[1];//add sum of all purchases from vendor to array. array key is vendor id
}
$query2 = "SELECT location_id,sum(applied_amount)
           FROM purchases_payments ppa
           WHERE vendor_id=" . $vendor_id . "
           GROUP BY location_id";
$result2 = mysql_query($query2) or die(mysql_error());
while($row2 = mysql_fetch_row($result2)){
    $vendor_totals[$row2[0]] = $vendor_totals[$row2[0]]-$row2[1];//subtract sum of all payments ever applied to the vendor and set new value in array
}

if($_GET['location'] != ''){
    $location = mysql_real_escape_string($_GET['location']);

    //purchases payments
    $query4 = "SELECT pp.*,DATE_FORMAT(pp.datetime,'%Y-%m-%d %H:%i')as date_time ,e.first_name,e.last_name,e.emp_id,v.name
               FROM purchases_payments pp
               LEFT JOIN employees e ON e.id=pp.employee_id
               LEFT JOIN locations v ON v.id=pp.location_id
               WHERE pp.vendor_id=" . $vendor_id . " AND pp.location_id=" . $location . "
               ORDER BY datetime DESC";
    $result4 = mysql_query($query4) or die(mysql_error());

    $unapplied_total = 0;
	if($result3 && mysql_num_rows($result3)>0){
		while($row3 = mysql_fetch_assoc($result3)){
			$unapplied_total += ($row3['amount']-$row3['applied_amount']);
		}
		mysql_data_seek($result3,0);
	}

    //totals for each day interval
    $_30day = date('Y-m-d H:i:s', time() - (30 * 24 * 60 * 60));
    $_60day = date('Y-m-d H:i:s', time() - (60 * 24 * 60 * 60));
    $_90day = date('Y-m-d H:i:s', time() - (90 * 24 * 60 * 60));
    $_120day = date('Y-m-d H:i:s', time() - (120 * 24 * 60 * 60));

    $total0_3 = calcIntervalTotal($location,$_30day,'','after');
    $total3_6 = calcIntervalTotal($location,$_60day,$_30day,'');
    $total6_9 = calcIntervalTotal($location,$_90day,$_60day,'');
    $total9_120 = calcIntervalTotal($location,$_120day,$_90day,'');
    $total120 = calcIntervalTotal($location,'',$_120day,'before');
    $grand_total = $total0_3 + $total3_6 + $total6_9 + $total9_120 + $total120 - $unapplied_total;


		$query3 = "SELECT p.id,DATE_FORMAT(p.completed_datetime,'%Y-%m-%d %H:%i') as completed_datetime, vt.code terms,p.subtotal,p.tax_total,p.total,p.total-p.applied_amount AS unapplied_amt, SUM(pi.ordered_quantity) AS qty
		FROM  purchases p
		LEFT JOIN purchase_items PI ON pi.purchase_id=p.id		
		LEFT JOIN vendors_terms_types vt ON vt.vendors_terms_types=p.terms
		WHERE p.vendor_id=" . $vendor_id . " AND p.location_id=" . $location . " AND p.status='Completed'
		GROUP BY p.id";       
	       
    $result3 = mysql_query($query3) or die(mysql_error());
}
?>
<!DOCTYPE html>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/jquery.ui.css" type="text/css" />
<link rel="stylesheet" href="css/responsive-tables.css">

<link rel="stylesheet" href="css/bootstrap-timepicker.min.css" type="text/css" />
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/elements.js"></script>
<script type="text/javascript" src="prettify/prettify.js"></script>
<script type="text/javascript" src="js/jquery.printElement.min.js"></script>

<style>
body {
top:0px!important;
font-size:12px!important;
}
table {font-size:12px!important;}
.goog-te-banner-frame{  margin-top: -50px!important; }
.sorting_asc {
	background: url('images/sort_asc.png') no-repeat center right !important;
	background-color: #333333 !important;
}
.sorting_desc {
	background: url('images/sort_desc.png') no-repeat center right !important;
	background-color: #333333 !important;
}
.mymodal2:hover{
color:#0866C6;
}
.line3{
	background: #808080;
}
</style>
<script>
	jQuery("#keyword").live("keyup",function () {
    var value = this.value.toLowerCase().trim();

    jQuery("#licence_table tr").each(function (index) {
        if (!index) return;
        jQuery(this).find("td").each(function () {
            var id = jQuery(this).text().toLowerCase().trim();
			if(id!=""){			
            var not_found = (id.indexOf(value) == -1);
            jQuery(this).closest('tr').toggle(!not_found);			
            return not_found;
			}else{				
				return "No Match Found";
			}
        });
    });
});
jQuery("#keyword1").live("keyup",function () {
    var value = this.value.toLowerCase().trim();

    jQuery("#licence_table1 tr").each(function (index) {
        if (!index) return;
        jQuery(this).find("td").each(function () {
            var id = jQuery(this).text().toLowerCase().trim();
			if(id!=""){
            var not_found = (id.indexOf(value) == -1);
            jQuery(this).closest('tr').toggle(!not_found);
            return not_found;
			}else{
			
				return "No Match Found";
			}
        });
    });
});
	

</script>
<style>
.line3 { background-color:#808080; color:#000000 !important;}
</style>
</head>

<body>
<div class="mainwrapper">
  <?php require_once('require/top.php');?>
  <?php require_once('require/left_nav.php');?>
  <div class="rightpanel">
    <ul class="breadcrumbs">
      <li><a href="help.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
      <li>Payments</li>
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
      		<?php if(!isset($_GET['location'])){  ?>
			  <div style="position: absolute;margin-top: 0px;right: 19px;">
            <form action="" method="get" class="searchbar" style="position:static; margin:16px 3px 0 0; float:right;">
                <input type="text" id="keyword1" onKeyDown="javascript:if(event.keyCode==13){ return false; }" onBlur="search_ven(this.value)" name="loc_ser" placeholder="Search Locations" style="height:42px; width:190px;"/>
            </form>
            </div>
			<?php } else{ ?>
			<div style="position: absolute;margin-top: 16px;right: 19px;">
					
				 <a href="storepoint_payments.php" class="btn btn-primary btn-large" style="color:#FFFFFF;">Back</a>
				 <a data-toggle="modal" class="mymodal2 btn btn-success btn-large" client='<?php echo $_GET['location']; ?>' rel='<?php echo"1"; ?>' href="mymodal2" style="color:#FFFFFF;">Payment</a> <!--id="btnPay"-->
					
            </div>
			<div style="position: absolute;margin-top: 16px;right: 200px;">
			<!--<form action="" id="" method="get" class="searchbar" style="right:343px; padding-top:0px;">
                <input  type="text" id="keyword" onKeyDown="javascript:if(event.keyCode==13){ return false; }" name="keyword" onBlur="search_ven(this.value)" placeholder="Search Locations"  style="height:24px; width:150px;"/>	
            </form>-->
			<input type="text" id="keyword" onKeyDown="javascript:if(event.keyCode==13){ return false; }" name="keyword" onBlur="search_ven(this.value)" placeholder="Search Locations" class="go_search2" style="width: 190px; padding-left: 5px; height: 42px; float:right; margin-right:10px;">
			</div>
			<?php } ?>
      <div class="pageicon"><span class="iconfa-money"></span></div>
      <div class="pagetitle">
        <h5>Display all amount due from locations</h5>
        <h1>Payments</h1>
      </div>
    </div>
    <!--pageheader-->
 
    <div class="maincontent">
      <div class="maincontentinner">
       <div class="row-fluid">
          
        	<div class="span4" style="width:32% !important;">
						<div class="clearfix">
						<h4 class="widgettitle">Locations</h4>			
						</div>
						<table id="licence_table1" class="table table-bordered responsive">
										<colgroup>
											<col class="con0" />
											<col class="con1" />
											<col class="con0" />
											<col class="con1" />
										</colgroup>
										<thead>
											<tr>
												<th class="head1 center" style="width:11px;">#</th>
												<th class="head0 center" style="width:10px;">S</th>
												<th class="head1" style="width:95px;">Location</th>
												<th class="head0" style="width:100px;">Total</th>
											</tr>
										</thead>
										<tbody>
                                        
                                        	<?php 
												if ($_REQUEST["loc_ser"] != "") {
													$stquery = "AND (locations.name LIKE '%" . $_REQUEST["loc_ser"] . "%')";
												}
								
												if ($_REQUEST["sort"] != "") {
													$ordquery = " ORDER BY " . $_REQUEST["sort"] . " ASC";
												}
												$query = "SELECT location_id, locations.name,locations.status
													  FROM purchases p
													  INNER JOIN locations ON p.location_id=locations.id
													  WHERE vendor_id='".$vendor_id."' AND p.status = 'Completed' 
													  GROUP BY location_id  $stquery".  $ordquery;
												$result = mysql_query($query) or die(mysql_error());
												$i = 0;
												if(mysql_num_rows($result) > 0){
													while ($row = mysql_fetch_array($result)) {
														$i++;
														$class = '';
														if ($row["location_id"] == $_REQUEST["location"]) {
															$class = "class='line3'";
														}
	//totals for each day interval
   /* $_30daya = date('Y-m-d H:i:s', time() - (30 * 24 * 60 * 60));
    $_60daya = date('Y-m-d H:i:s', time() - (60 * 24 * 60 * 60));
    $_90daya = date('Y-m-d H:i:s', time() - (90 * 24 * 60 * 60));
    $_120daya = date('Y-m-d H:i:s', time() - (120 * 24 * 60 * 60));

    $total0_3a = calcIntervalTotal($row["location_id"],$_30daya,'','after');
    $total3_6a = calcIntervalTotal($row["location_id"],$_60daya,$_30daya,'');
    $total6_9a = calcIntervalTotal($row["location_id"],$_90daya,$_60daya,'');
    $total9_120a = calcIntervalTotal($row["location_id"],$_120daya,$_90daya,'');
    $total120a = calcIntervalTotal($row["location_id"],'',$_120daya,'before');
    $grand_totala = $total0_3a + $total3_6a + $total6_9a + $total9_120a + $total120a - $unapplied_total;*/
														?>
														
										    <tr <?=$class;?> style="cursor:pointer;" onClick="window.location.href='storepoint_payments.php?location=<?=$row['location_id'];?>'">
												<td style="text-align: center;"><?=$i; ?></td>
												<td style="text-align: center;"><?=returnStatusImg($row['status'])?></td>
												<td><?=$row['name']; ?></td>
												<td style="text-align:right;"><?=formatNumber($vendor_totals[$row['location_id']]); ?></td>
												<!--<td style="text-align:right;"><?=formatNumber($grand_totala); ?></td>-->
												
											</tr>
                                            
                                            <?php }?>
                                            
											<tr>
												<td colspan="3" style="text-align:right;"><b>Total:</b></td>
												<td style="text-align:right;"><b><?=formatNumber(array_sum($vendor_totals))?></b></td>
											</tr>
                                            
                                            <?php }else{ ?>
                                            <tr><td colspan="4" style="text-align: center;">No Location Available.</td></tr>
                                            <?php } ?>
											<!--
											Use this <tr> when the query returns 0 row.
											<tr>
											 <td><b>You currently have no payments.</b></td>
											</tr>-->
									    </tbody>
						</table>
					</div>
					<?php if ($_REQUEST["location"] != "") { ?>
					<div class="span4" style="width:67% !important;margin-left:0.90% !important;">
                        <div class="tabbedwidget tab-primary">
                  		<ul style="height:38px;">
               			  <li style="width:170px;text-align:center;"><a class="capital_word" href="#active_pur" >Active Purchases</a></li>
                			<li style="width:170px;text-align:center;"><a class="capital_word" href="#completed_pur" >Applied Purchases</a></li>
              			</ul>
                        <div id="active_pur">
<table id="licence_table" class="table table-bordered responsive">
										<colgroup>
											<col class="con0" />
											<col class="con1" />
											<col class="con0" />
											<col class="con1" />
											<col class="con0" />
											<col class="con1" />
											<col class="con0" />
											<col class="con1" />
										</colgroup>
										<thead>
											<tr>
												<th class="head1 center" style="width:11px;">#</th>
												<th class="head1" style="width:41px;">Completed Date</th>
												<th class="head0" style="width:41px;">Terms</th>
												<th class="head1" style="width:35px;">Qty</th>
												<th class="head0" style="width:40px;">Subtotal</th>
												<th class="head1" style="width:30px;">Tax</th>
												<th class="head0" style="width:30px;">Total</th>
												<th class="head1" style="width:40px;">Unapplied Amt</th>
											</tr>
										</thead>
										<tbody>
                                        <?php
										$total=0;
										 
											while ($row3 = mysql_fetch_array($result3)) {
											if($row3['unapplied_amt']>0){?>
												<tr class="<?=$class; ?>">
												<td style="text-align:center;">
                                                <!--<a class="order_details" href="#" data-query='?id=<?=$row3['id']?>'><?=$row3['id']?></a>-->
												<a data-toggle="modal" class="mymodal"  client='<?=$_REQUEST["location"]?>' rel='<?=$row3['id']?>' href="mymodal" style="text-decoration:underline;"><?=$row3['id']?></a>                                                </td>
												<td style="text-align:left;"><?=$row3["completed_datetime"]; ?></td>
												<td><?=$row3["terms"]; ?></td>
												<td style="text-align:left;"><?=$row3["qty"]; ?></td>
												<td style="text-align:right;">$<?=number_format(abs($row3["subtotal"]), 2, '.', ','); ?></td>
												<td style="text-align:right;">$<?=number_format(abs($row3["tax_total"]), 2, '.', ','); ?></td>
												<td style="text-align:right;">$<?=number_format(abs($row3["total"]), 2, '.', ','); ?></td>
												<td style="text-align:right;">$<?=number_format($row3['unapplied_amt'], 2, '.', ','); ?></td>
                                                <? $total+=number_format($row3['unapplied_amt'], 2, '.', ',');?>
										</tr>
                                        <?php } }  ?>
										</tbody>
						  </table>
                          </div>
                            <div id="completed_pur">
                            <table id="licence_table" class="table table-bordered responsive">
										<colgroup>
											<col class="con0" />
											<col class="con1" />
											<col class="con0" />
											<col class="con1" />
											<col class="con0" />
											<col class="con1" />
											<col class="con0" />
											<col class="con1" />
										</colgroup>
										<thead>
											<tr>
												<th class="head1 center" style="width:11px;">#</th>
												<th class="head1" style="width:41px;">Completed Date</th>
												<th class="head0" style="width:41px;">Terms</th>
												<th class="head1" style="width:35px;">Qty</th>
												<th class="head0" style="width:40px;">Subtotal</th>
												<th class="head1" style="width:30px;">Tax</th>
												<th class="head0" style="width:30px;">Total</th>
												<th class="head1" style="width:40px;">Unapplied Amt</th>
											</tr>
										</thead>
										<tbody>
                                        <?php
										$total=0;
										$result3 = mysql_query($query3) or die(mysql_error());
										while ($row3 = mysql_fetch_array($result3)) {
											if($row3['unapplied_amt']<=0.00){ $class.= ' zero';?>
												<tr class="<?=$class; ?>">
												<td style="text-align:center;">
                                                <!--<a class="order_details" href="#" data-query='?id=<?=$row3['id']?>'><?=$row3['id']?></a>-->
												<a data-toggle="modal" class="mymodal"  client='<?=$_REQUEST["location"]?>' rel='<?=$row3['id']?>' href="mymodal" style="text-decoration:underline;"><?=$row3['id']?></a>                                                </td>
												<td style="text-align:left;"><?=$row3["completed_datetime"]; ?></td>
												<td><?=$row3["terms"]; ?></td>
												<td style="text-align:left;"><?=$row3["qty"]; ?></td>
												<td style="text-align:right;">$<?=number_format(abs($row3["subtotal"]), 2, '.', ','); ?></td>
												<td style="text-align:right;">$<?=number_format(abs($row3["tax_total"]), 2, '.', ','); ?></td>
												<td style="text-align:right;">$<?=number_format(abs($row3["total"]), 2, '.', ','); ?></td>
												<td style="text-align:right;">$<?=number_format($row3['unapplied_amt'], 2, '.', ','); ?></td>
                                                <? $total+=number_format($row3['unapplied_amt'], 2, '.', ',');?>
										</tr>
                                        <?php } } ?>
										</tbody>
						  </table>
                            </div>
                      </div>
			      <br />
						<div class="clearfix"> 
							 <div class="tabbedwidget tab-primary" style="border-color:#339900 !important;">
							<ul style="height:38px; background-color:#339900 !important;">
							  <li style="width:170px;text-align:center;"><a class="capital_word" href="#active_pay" >Active Payment</a></li>
								<li style="width:170px;text-align:center;"><a class="capital_word" href="#completed_pay" >Applied Payment</a></li>
							</ul>
							<div id="active_pay"> 
							<table id="licence_table" class="table table-bordered responsive">
											<colgroup>
												<col class="con0" />
												<col class="con1" />
												<col class="con0" />
												<col class="con1" />
												<col class="con0" />
												<col class="con1" />
											</colgroup>
											<thead>
												<tr>
													
													<th class="head1" style="width:20%;">Date</th>
													<th class="head0" style="width:25%;">Employee</th>
													<th class="head1" style="width:15%;">Type</th>
													<th class="head0" style="width:15%;">Amount</th>
													<th class="head1" style="width:20%;">Unapplied Amt</th>
													<th class="head1" style="width:5%;">Action</th>
												</tr>
											</thead>
											<tbody>
											<?php
											while($row4 = mysql_fetch_assoc($result4)){
												if($row4['amount']-$row4['applied_amount']>0){
												
												if(number_format($row4['amount'],2) > number_format($row4['applied_amount'],2)){
													$unapplied = 'line2 unapplied'; $img="<img src='images/pencil.png' alt='Apply Payment' />";
												}else{
													$unapplied = 'line1'; $img='';
												}
												
												?>
											<tr class="<?=$unapplied;?>" id='<?=$row4['id'];?>'>
													
													<td style="text-align:left;">
													<?=$row4['date_time'];?>												</td>
													<td>
													<?=$row4['first_name'] . " " . $row4['last_name'] . " - " . $row4['emp_id'];?>												</td>
													<td>
													<?=ucfirst($row4['type']);?>												</td>
													<td style="text-align:right;">
													$<?=number_format($row4['amount'],2);?>												</td>
													<td style="text-align:right;">
													$<?=number_format(($row4['amount']-$row4['applied_amount']),2);?>												</td>
													
													<td style="text-align:center;">
													<a data-toggle="modal" class="mymodal3" unapplied_amount='<?=$total;?>' vendor_id='<?=$row4['location_id'];?>'   client='<?=$row4['id'];?>' rel='<?=$row4['id'];?>' href="mymodal3">
													<? //$img;?>
													<span class="icon-pencil"></span>                                                </a>												</td>
											</tr>
											<?php } }?>
											</tbody>
						  </table>
						  </div>
							<div id="completed_pay"> 
							<table id="licence_table" class="table table-bordered responsive">
											<colgroup>
												<col class="con0" />
												<col class="con1" />
												<col class="con0" />
												<col class="con1" />
												<col class="con0" />
												<col class="con1" />
											</colgroup>
											<thead>
												<tr>
													
													<th class="head1" style="width:20%;">Date</th>
													<th class="head0" style="width:25%;">Employee</th>
													<th class="head1" style="width:15%;">Type</th>
													<th class="head0" style="width:15%;">Amount</th>
													<th class="head1" style="width:20%;">Unapplied Amt</th>
													<th class="head1" style="width:5%;">Action</th>
												</tr>
											</thead>
				  <tbody>
											<?php
											$result4 = mysql_query($query4) or die(mysql_error());
											while($row4 = mysql_fetch_assoc($result4)){
												if($row4['amount']-$row4['applied_amount']<=0){
													$unapplied .= ' zero';
												
												
												if(number_format($row4['amount'],2) > number_format($row4['applied_amount'],2)){
													$unapplied = 'line2 unapplied'; $img="<img src='images/pencil.png' alt='Apply Payment' />";
												}else{
													$unapplied = 'line1'; $img='';
												}
												?>
												
											<tr class="<?=$unapplied;?>" id='<?=$row4['id'];?>'>
													
													<td style="text-align:left;">
													<?=$row4['date_time'];?>												</td>
													<td>
													<?=$row4['first_name'] . " " . $row4['last_name'] . " - " . $row4['emp_id'];?>												</td>
													<td>
													<?=ucfirst($row4['type']);?>												</td>
													<td style="text-align:right;">
													$<?=number_format($row4['amount'],2);?>												</td>
													<td style="text-align:right;">
													$<?=number_format(($row4['amount']-$row4['applied_amount']),2);?>												</td>
													
													<td style="text-align:center;">
													<a data-toggle="modal" class="mymodal3" unapplied_amount='<?=$total;?>' vendor_id='<?=$row4['location_id'];?>'   client='<?=$row4['id'];?>' rel='<?=$row4['id'];?>' href="mymodal3">
													<? //$img;?>
													<span class="icon-pencil"></span>                                                </a>												</td>
											</tr>
											<?php } } ?>
											</tbody>
						  </table>
						  </div>
						  </div>
								<br />
							<div class="clearfix"> 
							<h4 class="widgettitle">Totals</h4>			
							</div> 
							<table id="licence_table" class="table table-bordered responsive">
											<colgroup>
												<col class="con0" />
												<col class="con1" />
												<col class="con0" />
												<col class="con1" />
												<col class="con0" />
												<col class="con1" />
											</colgroup>
											<thead>
												<tr>
													<th class="head1" style="width:40px;">0 to 30 Days</th>
													<th class="head1" style="width:40px;">30 to 60 Days</th>
													<th class="head0" style="width:35px;">60 to 90 Days</th>
													<th class="head1" style="width:40px;">90 to 120 Days</th>
													<th class="head0" style="width:30px;">120 Days +</th>
													<th class="head1" style="width:35px;">Grand Total</th>
												</tr>
											</thead>
											<tbody>
											<tr>
													<td style="text-align:right;"><?=formatNumber($total0_3);?></td>
													<td style="text-align:right;"><?=formatNumber($total3_6);?></td>
													<td style="text-align:right;"><?=formatNumber($total6_9);?></td>
													<td style="text-align:right;"><?=formatNumber($total9_120);?></td>
													<td style="text-align:right;"><?=formatNumber($total120);?></td>
													<td style="text-align:right;"><?=formatNumber($grand_total);?></td>
											</tr>
											</tbody>
						  </table>
							</div> <!-- clearfix -->
                </div><!--span4-->
						<?php } ?>
          
        
        </div>
        
        <?php include_once 'require/footer.php';?>
        <!--footer--> 
        
      </div>
      <!--maincontentinner--> 
    </div>
    <!--maincontent--> 
    
  </div>
  <!--rightpanel--> 
  
</div>
<!--mainwrapper-->
<div id="mymodal" class="modal hide fade" style="width:760px;margin-left:-380px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3>Order Details</h3>
	</div>
	<div class="modal-body" id="mymodal_html">	</div>
	<div class="modal-footer" style="text-align:center;">
		<a data-dismiss="modal" href="#" class="btn">Close</a>
		<button href="javascript:void(0);" onClick="printDiv('mymodal_html')" class="btn btn-primary">Print</button>
	</div>
</div>
<div id="mymodal2" class="modal hide fade">
	<div class="modal-header" style="padding:18px 15px;">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3>New Payment</h3>
	</div>
	<div class="modal-body" id="mymodal_html2">	</div>
	<div class="modal-footer" style="text-align:center;">
		<a data-dismiss="modal" href="#" class="btn" style="color:#333333 !important;">Cancel</a>
		<button class="btn btn-primary newpayment_submit">Submit</button>
	</div>
</div>
<div id="mymodal3" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3>Apply Payments</h3>
	</div>
	<div class="modal-body" id="mymodal_html3">	</div>
	<div class="modal-footer" style="text-align:center;">
		<a data-dismiss="modal" href="#" class="btn" style="color:#333333 !important;">Cancel</a>
		<button href="javascript:void(0);" id="apply_payments" type="submit" value="submit" class="btn btn-primary">Submit</button>
	</div>
</div>

<div id="mymodal4" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3>Add Vendor</h3>
	</div>
    <form action="" onSubmit="return validate_vendor()" method="post" name="add_vendor" id="add_vendor">
	<div class="modal-body"  id="mymodal_html4">
    
    </div>
	<div class="modal-footer" style="text-align:center;">
		<a data-dismiss="modal" href="#" class="btn" style="color:#333333 !important;">Cancel</a>
		<button type="submit" name="addvendorsubmit" value="addvendorsubmit" href="javascript:void(0);" class="btn btn-primary">Submit</button>
	</div>
    </form>
</div>
<div id="mymodal5" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3>Add Vendor</h3>
	</div>
    <form action="" onSubmit="return validate_vendor()" method="post" name="add_vendor1" id="add_vendor1">
	<div class="modal-body" id="mymodal_html5">	</div>
	<div class="modal-footer" style="text-align:center;">
		<a data-dismiss="modal" href="#" class="btn" style="color:#333333 !important;">Cancel</a>
		<button type="submit" name="editvendorsubmit" value="submitted" href="javascript:void(0);" class="btn btn-primary">Submit</button>
	</div>
    </form>
</div>
<div id="_print" style ="display:none;"></div>
</body>
</html>
<script type="text/javascript">

function printDiv(divName) {
var pri = jQuery('#'+divName).html();
jQuery('#_print').html(pri);
jQuery("#_print").show();
//jQuery("#_print").printElement();


jQuery("#_print").printElement({
	 overrideElementCSS:[
		'css/style.defult.css',
	{ href:'css/print.css',media:'print'}]
 });
jQuery("#_print").hide();
}
    jQuery(document).ready(function() {
	
	
		// for modal
        jQuery(".mymodal").click(function(){
	    
			var orderid = jQuery(this).attr("rel");
			var client_id = jQuery(this).attr("client");
			jQuery.ajax({
				type: "POST",
				url: "storepoint_payments_order_details.php",
				data : { orderid : orderid, client_id : client_id },
			}).done(function(msg){
				jQuery("#mymodal_html").html(msg);
				jQuery("#mymodal").modal('show');
			});
			
	  	});
	  // for modal2
        jQuery(".mymodal2").click(function(){
	    
		var orderid   = jQuery(this).attr("rel");
		var client_id = jQuery(this).attr("client");
		jQuery.ajax({
            		type: "POST",
            		url: "storepoint_payments_payment.php",
			data : { orderid : orderid, client_id : client_id },
            
        	}).done(function(msg){
			jQuery("#mymodal_html2").html(msg);
			jQuery("#mymodal2").modal('show');
		});
	  });
	  
	  // for modal3
        jQuery(".mymodal3").click(function(){
	    
		var orderid=    jQuery(this).attr("rel");
		var client_id = jQuery(this).attr("client");
		var unapplied_amount=jQuery(this).attr("unapplied_amount");
		var vendor_id=jQuery(this).attr("vendor_id");
		
		jQuery.ajax({
            type: "POST",
            url: "storepoint_payments_apply_payments.php",
            
			data : { orderid : orderid, client_id : client_id, unapplied_amount:unapplied_amount, vendor_id : vendor_id },
            
        }).done(function(msg){
		jQuery("#mymodal_html3").html(msg);
		jQuery("#mymodal3").modal('show');
		
		
		});
	  });
	  // for modal4
	   jQuery(".mymodalMP").live('click',function(){
	   jQuery("#mymodalMP").modal('show');	   
	   });
        jQuery(".mymodal4").live('click',function(){
	    
		var orderid=    jQuery(this).attr("rel");
		var client_id = jQuery(this).attr("client");
		jQuery.ajax({
            type: "POST",
            url: "storepoint_payments_add_vendor.php",
            
			data : { orderid : orderid, client_id : client_id },
            
        }).done(function(msg){
		jQuery("#mymodal_html4").html(msg);
		jQuery("#mymodal4").modal('show');
		
		
		});
	  });
	  /* for modal5 (same form page for add/edit)*/
        jQuery(".mymodal5").click(function(){
	    
		var orderid=    jQuery(this).attr("rel");
		var client_id = jQuery(this).attr("client");
		jQuery.ajax({
            type: "POST",
            url: "storepoint_payments_add_vendor.php",
            
			data : { orderid : orderid, client_id : client_id },
            
        }).done(function(msg){
		jQuery("#mymodal_html5").html(msg);
		jQuery("#mymodal5").modal('show');
		
		
		});
	  });
	  
	  jQuery('.newpayment_submit').click(function(){
		jQuery('#payments_frm').submit();  
	  });
	  
	   jQuery('#apply_payments').click(function(){
		jQuery('#apply_payments_frm').submit();  
	  });
	  
	  
   });
   function save_vendor(){
   			var v_name = jQuery('#vname').val();
   		jQuery('#mymodal5').modal('toggle');
   	jQuery.ajax({
			url:"save_location.php",
			type:"POST",
			data:jQuery('#add_vendor1').serialize(),
			success:function(responce){
				if(responce>0){
					jQuery('#vendors').val(v_name);	
					jQuery('#vendor_id').val(responce);
				}
			}
			
		});
   }
</script>
<?php
 if(isset($_REQUEST['msg']))
 {
	?>
    <script>

    	jAlert('<?php echo $_REQUEST['msg']; ?>', 'Alert Dialog', function(){
		
            });
	
			
	</script>
<?	} ?>
<script >
jQuery(document).ready(function(){
	<?php if($_REQUEST['msg']!=""){?>
		var msg = '<?php echo $_REQUEST['msg']; ?>';
		jAlert(msg,'Alert Dialog');
	<?php } ?>
});

 function check_number(e,val){
			
			// Allow: backspace, delete, tab, escape, enter and .
			//alert(e.keyCode+'=>'+val);
			if (e.keyCode == 110 && val.indexOf('.') !== -1){
				e.preventDefault();
				return false;
			}
			
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
		}
function Calculate_total1(){
var subtotal =parseFloat(jQuery('#subtotal').val());
var tax_total =parseFloat(jQuery('#tax_total').val());

	if(!isNaN(tax_total)){
		total  = eval(subtotal)+eval(tax_total);
		jQuery('#total').val(total.toFixed(2));
		jQuery('#tax_total').val(tax_total.toFixed(2));
                
	}else{
		jQuery('#total').val(subtotal.toFixed(2));                
	}
        jQuery('#subtotal').val(subtotal.toFixed(2));
        
}
function Calculate_total2(){
var subtotal = parseFloat(jQuery('#subtotal').val());
var tax_total = parseFloat(jQuery('#tax_total').val());

	if(!isNaN(subtotal)){
		total  = eval(subtotal)+eval(tax_total);
		jQuery('#total').val(total.toFixed(2));
	}
        jQuery('#subtotal').val(subtotal.toFixed(2));
        jQuery('#tax_total').val(tax_total.toFixed(2));
}
function Calculate_total3(){
var total = parseFloat(jQuery('#total').val());
	if(!isNaN(total)){
	jQuery('#subtotal').val(total.toFixed(2));
	jQuery('#tax_total').val('0.00');
	}
}
jQuery('#mymodalMP').on('hidden',function(){
	jQuery('#manual_payble')[0].reset();
});
jQuery(".allownumericwithdecimal").blur(function(){
        var v = parseFloat(this.value);
        if (!isNaN(v)) {
            this.value = v.toFixed(2);
        }
    })
/*vendor search*/	
jQuery(document).ready(function(){
	jQuery('.add-on A').click(function(){
		if(jQuery(this).attr('rel')=='client'){
		jQuery('#keyword').val(jQuery('#vendors').val());
		}else{
		if(jQuery('#keyword').val().length<4){
		jAlert('Please enter More than 3 Characters','Alert Dialog');
		return false;
		}
		}
		GetVendor(1);
	});
	jQuery('#client_add').click(function(){
		jQuery.ajax({
            type: "POST",
            url: "storepoint_payments_add_vendor.php"}).done(function(msg){
		jQuery("#mymodal_html5").html(msg);
		jQuery('#filter_modal').modal('toggle');
		jQuery("#mymodal5").modal('show');
		
		});
	});
});

function loadVendor(id,email,phone,name,image)
{
	jQuery('#vendors').val(name);	
	jQuery('#vendor_id').val(id);	
	jQuery('#filter_modal').modal('toggle');
}	

</script>
