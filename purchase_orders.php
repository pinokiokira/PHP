<?php
require_once 'require/security.php';
include 'config/accessConfig.php';
$empmaster_id=$_SESSION['client_id'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
$vendor_id = $vender['StorePoint_vendor_Id'];


$f = mysql_fetch_array(mysql_query("SELECT location_link FROM vendors WHERE id = '".$vendor_id."'"));


//print_r("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id);

//S.A. Lowell
/*
$res_act = mysql_query("SELECT * FROM vendor_purchases LIMIT 1");
var_dump(mysql_fetch_array($res_act));
echo '<br><br>';

$res_act = mysql_query("SELECT * FROM vendor_purchases_items LIMIT 1");
var_dump(mysql_fetch_array($res_act));
echo '<br><br>';

$res_act = mysql_query("SELECT * FROM vendor_items LIMIT 1");
var_dump(mysql_fetch_array($res_act));
echo '<br><br>';

$res_act = mysql_query("SELECT * FROM inventory_items LIMIT 1");
var_dump(mysql_fetch_array($res_act));
echo '<br><br>';

$res_act = mysql_query("SELECT * FROM inventory_groups LIMIT 1");
var_dump(mysql_fetch_array($res_act));
echo '<br><br>';

$res_act = mysql_query("SELECT * FROM employees_master LIMIT 1");
var_dump(mysql_fetch_array($res_act));
echo '<br><br>';
*/
//created_by
//361

 $query_act = "SELECT employees_master.first_name as first_name, employees_master.last_name as last_name, v.status as vendorstatus, v.phone as phone, v.contact as contact, v.StorePoint_image as image, v.address as address, v.address2 as address2, p.created_by as created_by, p.vendor_purchases_id as id,p.status,
				   CASE p.status
				   when 'Ordered'   then DATE_FORMAT(p.order_datetime,'%Y-%m-%d %H:%i')
				   when 'Shipped'   then DATE_FORMAT(p.lastchange_datetime,'%Y-%m-%d %H:%i')
				   END as order_datetime,
				 '' as po,vt.code as terms,p.subtotal,
				 (select COALESCE(sum(ordered_tax_percentage),0) from purchase_items where purchase_id =p.vendor_purchases_id) as  				 				tax_total,
				 p.total,p.comments,v.StorePoint_image as image,
				 gc.symbol,v.id as loc_id, v.name as location_name, v.city,st.name as state_name,CONCAT(e.first_name,' ',e.last_name) as emp_name,v.zip,st.code,p.vendor_invoice_num
				 FROM vendor_purchases as p
				 LEFT JOIN vendors v ON p.vendor_id = v.id
				 LEFT JOIN global_currency as gc ON gc.id = v.currency_id
				 LEFT JOIN states as st on st.id = v.state
				 LEFT JOIN employees as  e ON e.id = p.lastchange_employee_id
LEFT JOIN employees_master ON employees_master.empmaster_id = p.created_by
				 LEFT JOIN vendors_terms_types AS vt ON vt.vendors_terms_types = p.terms	
				 WHERE p.status IN('Ordered') order by p.order_datetime desc";
	//echo $query_act;
$res_act = mysql_query($query_act) or die(mysql_error());

$query_shipped = "SELECT employees_master.first_name as first_name, employees_master.last_name as last_name, v.status as vendorstatus, v.phone as phone, v.contact as contact, v.StorePoint_image as image, v.address as address, v.address2 as address2, p.created_by as created_by, p.vendor_purchases_id as id,p.status,
				   CASE p.status
				   when 'Ordered'   then DATE_FORMAT(p.order_datetime,'%Y-%m-%d %H:%i')
				   when 'Shipped'   then DATE_FORMAT(p.lastchange_datetime,'%Y-%m-%d %H:%i')
				   END as order_datetime,
				 '' as po,vt.code as terms,p.subtotal,
				 (select COALESCE(sum(ordered_tax_percentage),0) from purchase_items where purchase_id =p.vendor_purchases_id) as  				 				tax_total,
				 p.total,p.comments,v.StorePoint_image as image,
				 gc.symbol,v.id as loc_id, v.name as location_name, v.city,st.name as state_name,CONCAT(e.first_name,' ',e.last_name) as emp_name,v.zip,st.code,p.vendor_invoice_num
				 FROM vendor_purchases as p
				 LEFT JOIN vendors v ON p.vendor_id = v.id
				 LEFT JOIN global_currency as gc ON gc.id = v.currency_id
				 LEFT JOIN states as st on st.id = v.state
				 LEFT JOIN employees as  e ON e.id = p.lastchange_employee_id	
LEFT JOIN employees_master ON employees_master.empmaster_id = p.created_by			 
				 LEFT JOIN vendors_terms_types AS vt ON vt.vendors_terms_types = p.terms	
				 WHERE p.status IN('Shipped')  order by p.order_datetime desc";
$res_shipped = mysql_query($query_shipped) or die(mysql_error());



$query_completed = "SELECT employees_master.first_name as first_name, employees_master.last_name as last_name, v.status as vendorstatus, v.phone as phone, v.contact as contact, v.StorePoint_image as image, v.address as address, v.address2 as address2, p.created_by as created_by, p.vendor_purchases_id as id,p.status,
				   CASE p.status
				   when 'Ordered'   then DATE_FORMAT(p.order_datetime,'%Y-%m-%d %H:%i')
				   when 'Shipped'   then DATE_FORMAT(p.lastchange_datetime,'%Y-%m-%d %H:%i')
				   when 'Completed' then DATE_FORMAT(p.order_datetime,'%Y-%m-%d %H:%i')
				   when 'Shopping'  then DATE_FORMAT(p.order_datetime,'%Y-%m-%d %H:%i')
				   END as order_datetime,
				 '' as po,vt.code as terms,p.subtotal,
				 (select COALESCE(sum(ordered_tax_percentage),0) from purchase_items where purchase_id =p.vendor_purchases_id) as  				 				tax_total,
				 p.total,p.comments,v.StorePoint_image as image,
				 gc.symbol,v.id as loc_id, v.name as location_name, v.city,st.name as state_name,CONCAT(e.first_name,' ',e.last_name) as emp_name,v.zip,st.code,p.vendor_invoice_num
				 FROM vendor_purchases as p
				 LEFT JOIN vendors v ON p.vendor_id = v.id
				 LEFT JOIN global_currency as gc ON gc.id = v.currency_id
				 LEFT JOIN states as st on st.id = v.state
				 LEFT JOIN employees as  e ON e.id = p.lastchange_employee_id	
LEFT JOIN employees_master ON employees_master.empmaster_id = p.created_by			 
				 LEFT JOIN vendors_terms_types AS vt ON vt.vendors_terms_types = p.terms	
				 WHERE p.status IN('Completed')  order by p.order_datetime desc";
$res_completed = mysql_query($query_completed) or die(mysql_error());



$query_shopping = "SELECT employees_master.first_name as first_name, employees_master.last_name as last_name, v.status as vendorstatus, v.phone as phone, v.contact as contact, v.StorePoint_image as image, v.address as address, v.address2 as address2, p.created_by as created_by, p.vendor_purchases_id as id,p.status,
				   CASE p.status
				   when 'Ordered'   then DATE_FORMAT(p.order_datetime,'%Y-%m-%d %H:%i')
				   when 'Shipped'   then DATE_FORMAT(p.lastchange_datetime,'%Y-%m-%d %H:%i')
				   when 'Completed' then DATE_FORMAT(p.order_datetime,'%Y-%m-%d %H:%i')
				   when 'Shopping'  then DATE_FORMAT(p.order_datetime,'%Y-%m-%d %H:%i')
				   END as order_datetime,
				 '' as po,vt.code as terms,p.subtotal,
				 (select COALESCE(sum(ordered_tax_percentage),0) from purchase_items where purchase_id =p.vendor_purchases_id) as  				 				tax_total,
				 p.total,p.comments,v.StorePoint_image as image,
				 gc.symbol,v.id as loc_id, v.name as location_name, v.city,st.name as state_name,CONCAT(e.first_name,' ',e.last_name) as emp_name,v.zip,st.code,p.vendor_invoice_num
				 FROM vendor_purchases as p
				 LEFT JOIN vendors v ON p.vendor_id = v.id
				 LEFT JOIN global_currency as gc ON gc.id = v.currency_id
				 LEFT JOIN states as st on st.id = v.state
				 LEFT JOIN employees as  e ON e.id = p.lastchange_employee_id				 
LEFT JOIN employees_master ON employees_master.empmaster_id = p.created_by
				 LEFT JOIN vendors_terms_types AS vt ON vt.vendors_terms_types = p.terms	
				 WHERE p.status IN('Shopping')  order by p.order_datetime desc";
$res_shopping = mysql_query($query_shopping) or die(mysql_error());



function getstatus($status){
	switch($status){
	case 'Cancelled':
		$image = '<img src="images/Closed, Cancelled & Terminated - 16.png" title="Cancelled">';
		break;
	case 'Completed': 
		$image = '<img src="images/Active, Corrected, Delivered - 16.png" title="Completed">';
		break;
	case 'Ordered':
		$image = '<img src="images/Ordered - 16.png" title="Ordered">';
		break;
	case 'Shipped': 
		$image = '<img src="images/Shipped - 16.png" title="Shipped">';
		break;
	case 'Shopping': 
		$image = '<img src="images/Shopping - 16.png" title="Shopping">';
		break;
	default:
		$image ='';
	}
	return $image;
}

?>
<!DOCTYPE html>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/jquery.dateLists.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />

<link href="css/prettyPhoto.css" rel="stylesheet" type="text/css" media="all" />
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<!-- <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js"></script> -->

<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js"></script>

<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
<link rel="stylesheet" href="prettify/prettify.css" type="text/css" />
<!--<script type="text/javascript" src="js/jquery.jgrowl.js"></script>-->
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
<script type="text/javascript" src="js/jquery.blockUI.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/main.js"></script>

<script type="text/javascript" src="js/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="js/jquery.dd.js"></script>
<script type="text/javascript" src="js/jquery.session.js"></script>

<style>





div#licence_table_filter input[type="text"] {
    height: auto !important;
}
body {
top:0px!important;
}
.goog-te-banner-frame{  margin-top: -50px!important; }
.sorting_asc {
	background: url('images/sort_asc.png') no-repeat center right !important;
	background-color: #333333 !important;
}
.sorting_desc {
	background: url('images/sort_desc.png') no-repeat center right !important;
	background-color: #333333 !important;
}
table.table tbody tr.ui-selected, table.table tfoot tr.ui-selected {
	background-color:rgb(128,128,128);
}
//table.table tbody tr .ui-selected {
background: #ffffff;
}
.progress {
	position: relative;
	width: 100%;
	border: 1px solid #ddd;
	padding: 1px;
	border-radius: 3px;
	display: none;
	margin-top: 10px;
}
.bar {
	background-color: #B4F5B4;
	width: 0%;
	height: 20px;
	border-radius: 3px;
}
.percent {
	position: absolute;
	display: inline-block;
	top: 3px;
	left: 48%;
}
.ui-datepicker-month {
	width: 70px
}
.ui-datepicker-year {
	width: 70px
}
/*.table th, .table td {
	padding:0.5%;
}*/
.tdname {
	font-size:1.1em;
	font-weight:bold;
}
.ui-tabs-panel {
	color: #000000;
}
.pp_details {
	display:none;
}
.modal-header {
	border-bottom: 1px solid #EEEEEE;
	padding: 9px 15px;
}
.modal-header .close {
	margin-top: 2px;
}
.close {
	text-shadow: 1px 1px rgba(255, 255, 255, 0.4);
}
.close {
	color: #000000;
	float: right;
	font-size: 20px;
	font-weight: bold;
	line-height: 20px;
	opacity: 0.2;
	text-shadow: 0 1px 0 #FFFFFF;
}
.modal-body {
	overflow-y:hidden;
}
@media screen and (max-width: 1152px) {
 .table th, .table td {
 padding:0.4%;
}
}
</style>
<script>
	jQuery(document).ready(function(){
		
			<?php if(isset($_REQUEST['msg']) && $_REQUEST['msg']!=""){ ?>
					var msg = '<?php echo $_REQUEST['msg']; ?>';
					jAlert(msg,'Alert Dialog');
			<?php } ?>
			
			jQuery('#licence_table,#licence_table1,#licence_table2,#licence_table3').dataTable({
				"sPaginationType": "full_numbers",
				"aaSorting": [[3,'asc']],
				"bJQuery": true,
				"fnDrawCallback": function(oSettings) {
					jQuery.uniform.update();
				}

			});

		/* var $tabs = jQuery('.tabbedwidget').tabs({
				activate: function (event, ui) {
					 selected = ui.newTab.context.id;
					 if(selected=="ui-id-1"){
						jQuery("#licence_table>tbody>tr:first").trigger('click');
					 }else if(selected=="ui-id-2"){
					 	 jQuery("#licence_table1>tbody>tr:first").trigger('click');
					 }else if(selected=="ui-id-3"){
					 	 jQuery("#licence_table2>tbody>tr:first").trigger('click');
					 }else if(selected=="ui-id-4"){
					 	 jQuery("#licence_table3>tbody>tr:first").trigger('click');
					 }
				  }
			}); */
		var selected = $tabs.tabs('option', 'active');
		
		if(window.location.hash = '#googtrans(en|<?php echo $_SESSION['lang'];?>)'){
			jQuery("input[type='text']").css("height","30px");	
		}
	
	});
	function get_detail(id){
/*
		var comments = jQuery('#row_'+id).attr('data-comments');
		if(comments!=""){
			jQuery("#comments").html(comments);
		}else{
			jQuery("#comments").html("<strong>There are no Comments For this Purchase!</strong>")
		}
		
		jQuery('.line3').removeClass('line3');
		jQuery('#row_'+id).addClass('line3');
		jQuery('#details').html('');
		jQuery.ajax({
				url:"purchase_order_details.php",
				type:'POST',
				data:{id:id},
				success:function(data){
				jQuery('#details').html(data);
				}
			
		});
*/
	}

function getPurchaseOrdersItems(id){
	console.log('getPurchaseOrdersItems');

	var comments = jQuery('#row_'+id).attr('data-comments');

	if(comments!=""){
		jQuery("#comments").html(comments);
	}else{
		jQuery("#comments").html("<strong>There are no Comments For this Purchase!</strong>")
	}

	jQuery('.line3').removeClass('line3');
	jQuery('#row_'+id).addClass('line3');
	jQuery('#details').html('');

	jQuery.ajax(
	{
		url:"purchase_order_items.php",
		type:'POST',
		data:{vendor_purchases_items:id},
		success: function(a,b,c)
		{
			console.log('success');
			console.log(a);
			console.log(b);
			console.log(c);
			jQuery('#details').html(a);
		},
		failure:function(a,b,c)
		{
			console.log('FAILURE');
			console.log(a);
			console.log(b);
			console.log(c);
		}
	});
}


	function get_detail2(id){
		var comments = jQuery('#row_'+id).attr('data-comments');
		if(comments!=""){
			jQuery("#comments").html(comments);
		}else{
			jQuery("#comments").html("<strong>There are no Comments For this Purchase!</strong>")
		}
		
		jQuery('.line3').removeClass('line3');
		jQuery('#row_'+id).addClass('line3');
		jQuery('#details').html('');
		jQuery.ajax({
				url:"storepoint_get_purchase_details.php",
				type:'POST',
				data:{history:'Yes',id:id},
				success:function(data){
				jQuery('#details').html(data);
				}
			
		});
	}
	function get_detail3(id){
		var comments = jQuery('#row_'+id).attr('data-comments');
		if(comments!=""){
			jQuery("#comments").html(comments);
		}else{
			jQuery("#comments").html("<strong>There are no Comments For this Purchase!</strong>")
		}
		
		jQuery('.line3').removeClass('line3');
		jQuery('#row_'+id).addClass('line3');
		jQuery('#details').html('');
		jQuery.ajax({
				url:"storepoint_get_purchase_details.php",
				type:'POST',
				data:{history:'shipped',id:id},
				success:function(data){
				jQuery('#details').html(data);
				}
			
		});
	}
	

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
      <li>purchase Orders</li>
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
            <div class="pull-right" style=" margin-top: 14px;">
                <a href="buying_purchases_order.php?flag&loc_id=<?php echo $f['location_link']; ?>&po=yes" style="color: white !important;" class="btn btn-success btn-large new_purchase pull-right">Add</a>
                </div>
      <div class="pageicon"><span class="iconfa-credit-card"></span></div>
      <div class="pagetitle">
        <h5>LIST OF PURCHASE ORDER</h5>
        <h1>Purchase Order</h1>
      </div>
	  
    </div>
    <!--pageheader-->
 
    <div class="maincontent">
      <div class="maincontentinner">
       <div class="row-fluid">
          <div class="span8" style="padding-left: 2px; width:50% !important;">
            <script type="text/javascript" src="js/elements.js"></script>
			<div class="tabbedwidget tab-primary display_details">
              <ul>
                <li><a href="#e-7">Ordered</a></li>
                <li><a href="#e-6">Shipped</a></li>
                <li><a href="#e-5">Completed</a></li>
                <li><a href="#e-4">Shopping</a></li>
              </ul>
              <div id="e-7">
                <div style=" overflow-y:auto;">
                  <table id="licence_table" class="table table-bordered responsive">
                    <colgroup>
                    <!--<col class="con0" style="align: center; width:5%; vertical-align:middle" />-->
                    <col class="con1" style="width:5%;" />
                    <col class="con0" style="width:5%;"/>
                    <col class="con1" style="width:3%;"/>
                    <col class="con0" style="width:20%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:15%;"/> 
                    <col class="con0" style="width:8%;"/> 
                    <col class="con1" style="width:5%;"/>                    
                    </colgroup>
                    <thead>
                      <tr>
                        <th class="head1" >ORD S</th>
                        <th class="head0" >Image</th>
                        <th class="targetS center head0" >S</th>
                        <th class="head0" >NAME</th>
                        <th class="head0" >PHONE</th>
                        <th class="head1" >INVOICE #</th>
                        <th class="head0" >Ordered</th>
                        <th class="head0" >Total</th>
                        <th class="head1" >Action</th> 
                      </tr>
                    </thead>
                    <tbody>
                    <?php while($row_act = mysql_fetch_array($res_act)){
						if($row_act['tax_total']!=""){							
							$row_act['tax_total'] = number_format((($row_act['tax_total']*$row_act['subtotal'])/100),2,'.',',');
							$row_act['total'] = number_format(($row_act['subtotal']+$row_act['tax_total']),2,'.',',');
						}
					
					?>
                    	<tr onClick="get_detail(<?php echo $row_act['id']; ?>);getPurchaseOrdersItems(<?php echo $row_act['id']; ?>)" data-comments="<?php echo $row_act['comments']; ?>" id="row_<?php echo $row_act['id']; ?>">
                        	<!--<td>&nbsp;<img onerror="this.src='images/Default Primary Type - Restaurants.png'" src="<?php if($row_act['image']!=""){ echo APIPHP.'images/'.$row_act['image'];} else{echo 'images/Default Primary Type - Restaurants.png';} ?>" width="50" height="50" ></td>-->
                            <td class="center" style="vertical-align:middle;"><a href="buying_purchases_order.php?flag&purchase_id=<?php echo $row_act['id']; ?>&loc_id=<?php echo $f['location_link']; ?>&po=yes" >&nbsp;<?php echo getstatus($row_act['status']); ?></a></td>   
<td style="line-height: 0px !important;text-align: center;vertical-align: middle;">&nbsp;<img style="height:25px;width:25px" onerror="this.src='images/Default - User.png'" src="<?php echo explode(getcwd(),"\\")[2]."/images/".$row_act['image']; ?>" ></td>
													<td class="targetS center"> 
													<?php
														if($row_act['vendorstatus'] == 'active'){
															echo "<img src='images/Active, Corrected, Delivered.png' title='Active'>";
														} else {
															echo "<img src='images/Inactive & Missing Punch.png' title='Inactive'>";
														}
													?>
													</td>

<td>
<?php echo '<b>'.$row_act['location_name']. '</b> (ID: '.$row_act['id'].')'; ?><br>
<?php echo $row_act['address']." ".$row_act['address2']; ?><br>
<?php $arr = mysql_fetch_array(mysql_query("select name from states where id = '".$row_act['state']."'")); if(!empty($arr['name'])){echo $arr['name'];} ?>
<?php if(!empty($row_act['city'])){echo $row_act['city'];} ?>, <?php echo $row_act['zip']; ?>
</td>     
<td class=" " style="vertical-align:middle;">
<?php echo $row_act['phone']; ?><br><?php echo $row_act['contact']; ?>
</td>
                            <td class=" " style="vertical-align:middle;">&nbsp;<?php echo $row_act['vendor_invoice_num']; ?></td>
                            <td class=" " style="vertical-align:middle;">&nbsp;<?php echo $row_act['first_name'].' '.$row_act['last_name'].'<br>';?><?php echo '&nbsp;'.$row_act['order_datetime']; ?></td>    

                           <? /* <td>&nbsp;<?php echo $row_act['po']; ?></td>
                            <td><?php echo "<strong>".$row_act['location_name']."</strong>"."<br>".$row_act['city'].", ".$row_act['code']."<br>".$row_act['zip']; ?></td> */ ?>
                            <!--<td>&nbsp;<?php if($row_act['created_by'] != "") echo $row_act['created_by']; else echo "-";?></td>-->                            
                          
                            <td class="center right" style="vertical-align:middle;">&nbsp;<?php echo $row_act['symbol'].''.$row_act['total']; ?></td>
                            <td class="center" style="vertical-align:middle;">
                            <a href="buying_purchases_order.php?flag&purchase_id=<?php echo $row_act['id']; ?>&loc_id=<?php echo $f['location_link']; ?>&po=yes" >
                            <img title="Edit"  src="images/edit.png" ></a>&nbsp;&nbsp;
                            <img  src="images/icons/search.png" ></td>
                            <!--<td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['subtotal']; ?></td>
                            <td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['tax_total']; ?></td>-->
                        </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              
            <div id="e-6">
                <div style=" overflow-y:auto;">
                  <table id="licence_table3" class="table table-bordered responsive">
                    <colgroup>
                    <!--<col class="con0" style="align: center; width:5%; vertical-align:middle" />-->
                    <col class="con1" style="width:5%;" />
                    <col class="con0" style="width:5%;"/>
                    <col class="con1" style="width:3%;"/>
                    <col class="con0" style="width:20%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:15%;"/> 
                    <col class="con0" style="width:8%;"/> 
                    <col class="con1" style="width:5%;"/>                    
                    </colgroup>
                    <thead>
                      <tr>
                        <th class="head1" >ORD S</th>
                        <th class="head0" >Image</th>
                        <th class="targetS center head0" >S</th>
                        <th class="head0" >NAME</th>
                        <th class="head0" >PHONE</th>
                        <th class="head1" >INVOICE #</th>
                        <th class="head0" >Ordered</th>
                        <th class="head0" >Total</th>
                        <th class="head1" >Action</th> 
                      </tr>
                    </thead>
                    <tbody>
                    <?php while($row_act = mysql_fetch_array($res_shipped)){
						if($row_act['tax_total']!=""){							
							$row_act['tax_total'] = number_format((($row_act['tax_total']*$row_act['subtotal'])/100),2,'.',',');
							$row_act['total'] = number_format(($row_act['subtotal']+$row_act['tax_total']),2,'.',',');
						}
					
					?>
                    	<tr onClick="get_detail(<?php echo $row_act['id']; ?>);getPurchaseOrdersItems(<?php echo $row_act['id']; ?>)" data-comments="<?php echo $row_act['comments']; ?>" id="row_<?php echo $row_act['id']; ?>">
                        	<!--<td>&nbsp;<img onerror="this.src='images/Default Primary Type - Restaurants.png'" src="<?php if($row_act['image']!=""){ echo APIPHP.'images/'.$row_act['image'];} else{echo 'images/Default Primary Type - Restaurants.png';} ?>" width="50" height="50" ></td>-->
                            <td class="center" style="vertical-align:middle;"><a href="buying_purchases_order.php?flag&purchase_id=<?php echo $row_act['id']; ?>&loc_id=<?php echo $f['location_link']; ?>&po=yes" >&nbsp;<?php echo getstatus($row_act['status']); ?></a></td>   
<td style="line-height: 0px !important;text-align: center;vertical-align: middle;">&nbsp;<img style="height:25px;width:25px" onerror="this.src='images/Default - User.png'" src="<?php echo explode(getcwd(),"\\")[2]."/images/".$row_act['image']; ?>" ></td>
													<td class="targetS center"> 
													<?php
														if($row_act['vendorstatus'] == 'active'){
															echo "<img src='images/Active, Corrected, Delivered.png' title='Active'>";
														} else {
															echo "<img src='images/Inactive & Missing Punch.png' title='Inactive'>";
														}
													?>
													</td>

<td>
<?php echo '<b>'.$row_act['location_name']. '</b> (ID: '.$row_act['id'].')'; ?><br>
<?php echo $row_act['address']." ".$row_act['address2']; ?><br>
<?php $arr = mysql_fetch_array(mysql_query("select name from states where id = '".$row_act['state']."'")); if(!empty($arr['name'])){echo $arr['name'];} ?>
<?php if(!empty($row_act['city'])){echo $row_act['city'];} ?>, <?php echo $row_act['zip']; ?>
</td>     
<td class=" " style="vertical-align:middle;">
<?php echo $row_act['phone']; ?><br><?php echo $row_act['contact']; ?>
</td>
                            <td class=" " style="vertical-align:middle;">&nbsp;<?php echo $row_act['vendor_invoice_num']; ?></td>
                            <td class=" " style="vertical-align:middle;">&nbsp;<?php echo $row_act['first_name'].' '.$row_act['last_name'].'<br>';?><?php echo '&nbsp;'.$row_act['order_datetime']; ?></td>  

                           <? /* <td>&nbsp;<?php echo $row_act['po']; ?></td>
                            <td><?php echo "<strong>".$row_act['location_name']."</strong>"."<br>".$row_act['city'].", ".$row_act['code']."<br>".$row_act['zip']; ?></td> */ ?>
                            <!--<td>&nbsp;<?php if($row_act['created_by'] != "") echo $row_act['created_by']; else echo "-";?></td>-->                            
                          
                            <td class=" right" style="vertical-align:middle;">&nbsp;<?php echo $row_act['symbol'].''.$row_act['total']; ?></td>
                            <td class="center" style="vertical-align:middle;">
                            <a href="buying_purchases_order.php?flag&purchase_id=<?php echo $row_act['id']; ?>&loc_id=<?php echo $f['location_link']; ?>&po=yes" >
                            <img title="Edit"  src="images/edit.png" ></a>&nbsp;&nbsp;
                            <img  src="images/icons/search.png" ></td>
                            <!--<td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['subtotal']; ?></td>
                            <td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['tax_total']; ?></td>-->
                        </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>










              <div id="e-5">
                <div style=" overflow-y:auto;">
                  <table id="licence_table2" class="table table-bordered responsive">
                    <colgroup>
                    <!--<col class="con0" style="align: center; width:5%; vertical-align:middle" />-->
                    <col class="con1" style="width:5%;" />
                    <col class="con0" style="width:5%;"/>
                    <col class="con1" style="width:3%;"/>
                    <col class="con0" style="width:20%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:15%;"/> 
                    <col class="con0" style="width:8%;"/> 
                    <col class="con1" style="width:5%;"/>                    
                    </colgroup>
                    <thead>
                      <tr>
                        <th class="head1" >ORD S</th>
                        <th class="head0" >Image</th>
                        <th class="targetS center head0" >S</th>
                        <th class="head0" >NAME</th>
                        <th class="head0" >PHONE</th>
                        <th class="head1" >INVOICE #</th>
                        <th class="head0" >Ordered</th>
                        <th class="head0" >Total</th>
                        <th class="head1" >Action</th> 
                      </tr>
                    </thead>
                    <tbody>
                    <?php while($row_act = mysql_fetch_array($res_completed)){
						if($row_act['tax_total']!=""){							
							$row_act['tax_total'] = number_format((($row_act['tax_total']*$row_act['subtotal'])/100),2,'.',',');
							$row_act['total'] = number_format(($row_act['subtotal']+$row_act['tax_total']),2,'.',',');
						}
					
					?>
                    	<tr onClick="get_detail(<?php echo $row_act['id']; ?>);getPurchaseOrdersItems(<?php echo $row_act['id']; ?>)" data-comments="<?php echo $row_act['comments']; ?>" id="row_<?php echo $row_act['id']; ?>">
                        	<!--<td>&nbsp;<img onerror="this.src='images/Default Primary Type - Restaurants.png'" src="<?php if($row_act['image']!=""){ echo APIPHP.'images/'.$row_act['image'];} else{echo 'images/Default Primary Type - Restaurants.png';} ?>" width="50" height="50" ></td>-->
                            <td class="center" style="vertical-align:middle;"><a href="buying_purchases_order.php?flag&purchase_id=<?php echo $row_act['id']; ?>&loc_id=<?php echo $f['location_link']; ?>&po=yes" >&nbsp;<?php echo getstatus($row_act['status']); ?></a></td>   
<td style="line-height: 0px !important;text-align: center;vertical-align: middle;">&nbsp;<img style="height:25px;width:25px" onerror="this.src='images/Default - User.png'" src="<?php echo explode(getcwd(),"\\")[2]."/images/".$row_act['image']; ?>" ></td>
													<td class="targetS center"> 
													<?php
														if($row_act['vendorstatus'] == 'active'){
															echo "<img src='images/Active, Corrected, Delivered.png' title='Active'>";
														} else {
															echo "<img src='images/Inactive & Missing Punch.png' title='Inactive'>";
														}
													?>
													</td>

<td>
<?php echo '<b>'.$row_act['location_name']. '</b> (ID: '.$row_act['id'].')'; ?><br>
<?php echo $row_act['address']." ".$row_act['address2']; ?><br>
<?php $arr = mysql_fetch_array(mysql_query("select name from states where id = '".$row_act['state']."'")); if(!empty($arr['name'])){echo $arr['name'];} ?>
<?php if(!empty($row_act['city'])){echo $row_act['city'];} ?>, <?php echo $row_act['zip']; ?>
</td>     
<td class="" style="vertical-align:middle;">
<?php echo $row_act['phone']; ?><br><?php echo $row_act['contact']; ?>
</td>
                            <td class=" " style="vertical-align:middle;">&nbsp;<?php echo $row_act['vendor_invoice_num']; ?></td>
                            <td class=" " style="vertical-align:middle;">&nbsp;<?php echo $row_act['first_name'].' '.$row_act['last_name'].'<br>';?><?php echo '&nbsp;'.$row_act['order_datetime']; ?></td>  

                           <? /* <td>&nbsp;<?php echo $row_act['po']; ?></td>
                            <td><?php echo "<strong>".$row_act['location_name']."</strong>"."<br>".$row_act['city'].", ".$row_act['code']."<br>".$row_act['zip']; ?></td> */ ?>
                            <!--<td>&nbsp;<?php if($row_act['created_by'] != "") echo $row_act['created_by']; else echo "-";?></td>-->                            
                          
                            <td class="right" style="vertical-align:middle;">&nbsp;<?php echo $row_act['symbol'].''.$row_act['total']; ?></td>
                            <td class="center" style="vertical-align:middle;">
                            <a href="buying_purchases_order.php?flag&purchase_id=<?php echo $row_act['id']; ?>&loc_id=<?php echo $f['location_link']; ?>&po=yes" >
                            <img title="Edit"  src="images/edit.png" ></a>&nbsp;&nbsp;
                            <img  src="images/icons/search.png" ></td>
                            <!--<td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['subtotal']; ?></td>
                            <td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['tax_total']; ?></td>-->
                        </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
             <div id="e-4">
                <div style=" overflow-y:auto;">
                  <table id="licence_table3" class="table table-bordered responsive">
                    <colgroup>
                    <!--<col class="con0" style="align: center; width:5%; vertical-align:middle" />-->
                    <col class="con1" style="width:5%;" />
                    <col class="con0" style="width:5%;"/>
                    <col class="con1" style="width:3%;"/>
                    <col class="con0" style="width:20%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:15%;"/> 
                    <col class="con0" style="width:8%;"/> 
                    <col class="con1" style="width:5%;"/>                    
                    </colgroup>
                    <thead>
                      <tr>
                        <th class="head1" >ORD S</th>
                        <th class="head0" >Image</th>
                        <th class="targetS center head0" >S</th>
                        <th class="head0" >NAME</th>
                        <th class="head0" >PHONE</th>
                        <th class="head1" >INVOICE #</th>
                        <th class="head0" >Ordered</th>
                        <th class="head0" >Total</th>
                        <th class="head1" >Action</th> 
                      </tr>
                    </thead>
                    <tbody>
                    <?php while($row_act = mysql_fetch_array($res_shopping)){
						if($row_act['tax_total']!=""){							
							$row_act['tax_total'] = number_format((($row_act['tax_total']*$row_act['subtotal'])/100),2,'.',',');
							$row_act['total'] = number_format(($row_act['subtotal']+$row_act['tax_total']),2,'.',',');
						}
					
					?>
                    	<tr onClick="get_detail(<?php echo $row_act['id']; ?>);getPurchaseOrdersItems(<?php echo $row_act['id']; ?>)" data-comments="<?php echo $row_act['comments']; ?>" id="row_<?php echo $row_act['id']; ?>">
                        	<!--<td>&nbsp;<img onerror="this.src='images/Default Primary Type - Restaurants.png'" src="<?php if($row_act['image']!=""){ echo APIPHP.'images/'.$row_act['image'];} else{echo 'images/Default Primary Type - Restaurants.png';} ?>" width="50" height="50" ></td>-->
                            <td class="center" style="vertical-align:middle;"><a href="buying_purchases_order.php?flag&purchase_id=<?php echo $row_act['id']; ?>&loc_id=<?php echo $f['location_link']; ?>&po=yes" >&nbsp;<?php echo getstatus($row_act['status']); ?></a></td>   
							<td style="line-height: 0px !important;text-align: center;vertical-align: middle;">&nbsp;<img style="height:25px;width:25px" onerror="this.src='images/Default - User.png'" src="<?php echo explode(getcwd(),"\\")[2]."/images/".$row_act['image']; ?>" ></td>
							<td class="targetS center"> 
							<?php
								if($row_act['vendorstatus'] == 'active'){
									echo "<img src='images/Active, Corrected, Delivered.png' title='Active'>";
								} else {
									echo "<img src='images/Inactive & Missing Punch.png' title='Inactive'>";
								}
							?>
							</td>

							<td>
							<?php echo '<b>'.$row_act['location_name']. '</b> (ID: '.$row_act['id'].')'; ?><br>
							<?php echo $row_act['address']." ".$row_act['address2']; ?><br>
							<?php $arr = mysql_fetch_array(mysql_query("select name from states where id = '".$row_act['state']."'")); if(!empty($arr['name'])){echo $arr['name'];} ?>
							<?php if(!empty($row_act['city'])){echo $row_act['city'];} ?>, <?php echo $row_act['zip']; ?>
							</td>     
							<td class=" " style="vertical-align:middle;">
							<?php echo $row_act['phone']; ?><br><?php echo $row_act['contact']; ?>
							</td>
                            <td class=" " style="vertical-align:middle;">&nbsp;<?php echo $row_act['vendor_invoice_num']; ?></td>
                            <td class=" " style="vertical-align:middle;">&nbsp;<?php echo $row_act['first_name'].' '.$row_act['last_name'].'<br>';?><?php echo '&nbsp;'.$row_act['order_datetime']; ?></td>   

                           <? /* <td>&nbsp;<?php echo $row_act['po']; ?></td>
                            <td><?php echo "<strong>".$row_act['location_name']."</strong>"."<br>".$row_act['city'].", ".$row_act['code']."<br>".$row_act['zip']; ?></td> */ ?>
                            <!--<td>&nbsp;<?php if($row_act['created_by'] != "") echo $row_act['created_by']; else echo "-";?></td>-->                            
                          
                            <td class="right" style="vertical-align:middle;">&nbsp;<?php echo $row_act['symbol'].''.$row_act['total']; ?></td>
                            <td class="center" style="vertical-align:middle;">
                            <a href="buying_purchases_order.php?flag&purchase_id=<?php echo $row_act['id']; ?>&loc_id=<?php echo $f['location_link']; ?>&po=yes" >
                            <img title="Edit"  src="images/edit.png" ></a>&nbsp;&nbsp;
                            <img  src="images/icons/search.png" ></td>
                            <!--<td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['subtotal']; ?></td>
                            <td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['tax_total']; ?></td>-->
                        </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>







              
            </div>
          </div>
          <!--span13-->
            	
          <!--span4 profile-left-->
          <div class="span4 profile-left" id="comp_tab" style="width: 49%; margin-left:1% !important; float:right;">
		  
            <!--<div class="widgetbox company-photo">
              <h4 class="widgettitle">Comments</h4>
              <div class="widgetcontent">
                <div class="profilethumb" style="text-align:left;">
                <label>Comments:</label>
                <div id="comments">
                </div>
                </div>
                <br />
              </div>
            </div>-->
           
            <div class="widgetbox company-photo">
              <h4 class="widgettitle">Details</h4>
              <div id="details" class="widgetcontent">
              </div>
            </div>
           
          </div>
          <!--span4 profile-left-->
         
          <input type="hidden" id="hidLessonPass" name="hidLessonPass" value="<?php echo $l_ispass;?>">
          <input type="hidden" id="hidLessonID" name="hidLessonID" value="<?php echo $lessid;?>">
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

</body>
</html>
<script>







jQuery(document).ready(function(){
	jQuery("#licence_table>tbody>tr:first").trigger('click');
});
</script>