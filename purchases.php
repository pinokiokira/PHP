<?php
require_once 'require/security.php';
include 'config/accessConfig.php';
$empmaster_id=$_SESSION['client_id'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
$vendor_id = $vender['StorePoint_vendor_Id'];


//print_r("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id);



 $query_act = "SELECT p.id,p.status,
				   CASE p.status
				   when 'Ordered'   then DATE_FORMAT(p.order_datetime,'%Y-%m-%d %H:%i')
				   when 'Shipped'   then DATE_FORMAT(p.lastchange_datetime,'%Y-%m-%d %H:%i')
				   END as order_datetime,
				 p.po,vt.code as terms,p.subtotal,
				 (select COALESCE(sum(ordered_tax_percentage),0) from purchase_items where purchase_id =p.id) as  				 				tax_total,
				 p.total,p.comments,l.image,
				 gc.symbol,l.id as loc_id, l.name as location_name, l.city,st.name as state_name,lt.name as location_type,CONCAT(e.first_name,' ',e.last_name) as emp_name,l.zip,st.code,p.vendor_invoice_num
				 FROM purchases as p
				 LEFT JOIN locations l ON l.id = p.location_id
				 LEFT JOIN global_currency as gc ON gc.id = l.currency_id
				 LEFT JOIN states as st on st.id = l.state
				 LEFT JOIN location_types as lt ON lt.id = l.primary_type
				 LEFT JOIN employees as  e ON e.id = p.lastchange_employee_id				 
				 LEFT JOIN vendors_terms_types AS vt ON vt.vendors_terms_types = p.terms	
				 WHERE p.status IN('Ordered')  AND p.vendor_id='".$vendor_id."' order by p.order_datetime desc";//,'Shipped'
$res_act = mysql_query($query_act) or die(mysql_error());

$query_shipped = "SELECT p.id,p.status,
				   CASE p.status
				   when 'Ordered'   then DATE_FORMAT(p.order_datetime,'%Y-%m-%d %H:%i')
				   when 'Shipped'   then DATE_FORMAT(p.lastchange_datetime,'%Y-%m-%d %H:%i')
				   END as order_datetime,
				 p.po,vt.code as terms,p.subtotal,
				 (select COALESCE(sum(ordered_tax_percentage),0) from purchase_items where purchase_id =p.id) as  				 				tax_total,
				 p.total,p.comments,l.image,
				 gc.symbol,l.id as loc_id, l.name as location_name, l.city,st.name as state_name,lt.name as location_type,CONCAT(e.first_name,' ',e.last_name) as emp_name,l.zip,st.code,p.vendor_invoice_num
				 FROM purchases as p
				 LEFT JOIN locations l ON l.id = p.location_id
				 LEFT JOIN global_currency as gc ON gc.id = l.currency_id
				 LEFT JOIN states as st on st.id = l.state
				 LEFT JOIN location_types as lt ON lt.id = l.primary_type
				 LEFT JOIN employees as  e ON e.id = p.lastchange_employee_id				 
				 LEFT JOIN vendors_terms_types AS vt ON vt.vendors_terms_types = p.terms	
				 WHERE p.status IN('Shipped')  AND p.vendor_id='".$vendor_id."' order by p.order_datetime desc";//,'Shipped'
$res_shipped = mysql_query($query_shipped) or die(mysql_error());

 $query_shop = "SELECT p.id,p.status,
				   CASE p.status
				   when 'Shopping' then DATE_FORMAT(p.shopping_datetime,'%Y-%m-%d %H:%i')
				   END as order_datetime,
				 p.po,vt.code as terms,p.subtotal,
				 (select COALESCE(sum(ordered_tax_percentage),0) from purchase_items where purchase_id =p.id) as  				 				tax_total,
				 p.total,p.comments,l.image,
				 gc.symbol,l.id as loc_id, l.name as location_name, l.city,st.name as state_name,lt.name as location_type,CONCAT(e.first_name,' ',e.last_name) as emp_name,l.zip,st.code,p.vendor_invoice_num
				 FROM purchases as p
				 LEFT JOIN locations l ON l.id = p.location_id
				 LEFT JOIN global_currency as gc ON gc.id = l.currency_id
				 LEFT JOIN states as st on st.id = l.state
				 LEFT JOIN location_types as lt ON lt.id = l.primary_type
				 LEFT JOIN employees as  e ON e.id = p.lastchange_employee_id				 
				 LEFT JOIN vendors_terms_types AS vt ON vt.vendors_terms_types = p.terms	
				 WHERE p.status = 'Shopping'  AND p.vendor_id='".$vendor_id."' order by p.order_datetime desc";
$res_shop = mysql_query($query_shop) or die(mysql_error());

$query_his = "SELECT p.id,p.status,
				   CASE p.status
				   when 'Cancelled' then DATE_FORMAT(p.cancelled_datetime,'%Y-%m-%d %H:%i')
				   when 'Completed' then DATE_FORMAT(p.completed_datetime,'%Y-%m-%d %H:%i')
				   END as order_datetime,
				 p.po,vt.code as terms,p.subtotal,
				 (select COALESCE(sum(ordered_tax_percentage),0) from purchase_items where purchase_id =p.id) as tax_total,p.total,p.comments,l.image,
				 gc.symbol,l.id as loc_id,l.name as location_name, l.city,st.name as state_name,lt.name as location_type,CONCAT(e.first_name,' ',e.last_name) as emp_nam,p.vendor_invoice_num
				 FROM purchases as p
				 LEFT JOIN locations l ON l.id = p.location_id
				 LEFT JOIN global_currency as gc ON gc.id = l.currency_id
				 LEFT JOIN states as st on st.id = l.state				 
				 LEFT JOIN location_types as lt ON lt.id = l.primary_type
				 LEFT JOIN employees as  e ON e.id = p.order_employee_id
				 LEFT JOIN vendors_terms_types AS vt ON vt.vendors_terms_types = p.terms	
				 WHERE p.status NOT IN('Ordered','Shipped','Shopping','') AND p.vendor_id=".$vendor_id;
$res_his = mysql_query($query_his);
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
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="js/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="js/jquery.smartWizard.min.js"></script>
<script type="text/javascript" src="js/jquery.dateLists.min.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.blockUI.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/detectizr.min.js"></script>
<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>

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
			
			jQuery('#licence_table,#licence_table1').dataTable({
				"sPaginationType": "full_numbers",
				"aaSorting": [[1,'asc']],
				"bJQuery": true,
				"fnDrawCallback": function(oSettings) {
					jQuery.uniform.update();
				}
			});
	
		
		var $tabs = jQuery('.tabbedwidget').tabs({
				activate: function (event, ui) {
					 selected = ui.newTab.context.id;
					 if(selected=="ui-id-1"){
						jQuery("#licence_table>tbody>tr:first").trigger('click');
					 }else if(selected=="ui-id-2"){
					 	 jQuery("#licence_table1>tbody>tr:first").trigger('click');
					 }
					
					//tabSettings(selected);
				  }
			});
		var selected = $tabs.tabs('option', 'active');
		//tabSettings(selected);
		if(window.location.hash = '#googtrans(en|<?php echo $_SESSION['lang'];?>)'){
			jQuery("input[type='text']").css("height","30px");	
		}
	
	});
	function get_detail(id){
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
				data:{id:id},
				success:function(data){
				jQuery('#details').html(data);
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
	  <li>Sales <span class="separator"></span></li>
      <li>Orders</li>
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
                <a href="backoffice_purchases.php?flag" style="color: white !important;" class="btn btn-success btn-large new_purchase pull-right">Add</a>
                </div>
      <div class="pageicon"><span class="iconfa-shopping-cart"></span></div>
      <div class="pagetitle">
        <h5>Browse through your Orders</h5>
        <h1>Orders</h1>
      </div>
    </div>
    <!--pageheader-->
 
    <div class="maincontent">
      <div class="maincontentinner">
       <div class="row-fluid">
          <div class="span8" style="padding-left: 2px; width:66% !important;">
            <div class="tabbedwidget tab-primary">
              <ul>
				<li><a href="#e-9">Shopping</a></li>
                <li><a href="#e-7">Ordered</a></li>
                <li><a href="#e-6">Shipped</a></li>
                <li><a href="#e-8">History</a></li>
              </ul>
			  <div id="e-9">
                <div style=" overflow-y:auto;">
                  <table id="licence_table" class="table table-bordered responsive">
                    <colgroup>
                    <col class="con0" style="align: center; width:5%; vertical-align:middle" />
                    <col class="con1" style="width:5%;" />
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:25%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:5%;"/>                    
                    </colgroup>
                    <thead>
                      <tr>
                        <th class="head0" >Image</th>
                        <th class="head1" >S</th>
                        <th class="head0" >Date & Time</th>
                        <th class="head1" >INVOICE#</th>
                        <th class="head0" >PO#</th>
                        <th class="head1" >Location</th>
                        <th class="head0" >Order By</th>
                        <th class="head1" >Terms</th>                        
                        <th class="head0" >Total</th>
                        <th class="head1" >Action</th> 
                        <!--<th class="head1" >Sub</th>
                        <th class="head0" >Tax</th>  -->                
                      </tr>
                    </thead>
                    <tbody>
                    <?php while($row_act = mysql_fetch_array($res_shop)){
						if($row_act['tax_total']!=""){							
							$row_act['tax_total'] = number_format((($row_act['tax_total']*$row_act['subtotal'])/100),2,'.',',');
							$row_act['total'] = number_format(($row_act['subtotal']+$row_act['tax_total']),2,'.',',');
						}
					
					?>
                    	<tr onClick="get_detail(<?php echo $row_act['id']; ?>)" data-comments="<?php echo $row_act['comments']; ?>" id="row_<?php echo $row_act['id']; ?>">
                        	<td>&nbsp;<img onerror="this.src='images/Default Primary Type - Restaurants.png'" src="<?php if($row_act['image']!=""){ echo APIPHP.'images/'.$row_act['image'];} else{echo 'images/Default Primary Type - Restaurants.png';} ?>" width="50" height="50" ></td>
                            <td class="center" style="vertical-align:middle;"><a href="backoffice_purchases.php?flag&purchase_id=<?php echo $row_act['id']; ?>&loc_id=<?php echo $row_act['loc_id']; ?>" >&nbsp;<?php echo getstatus($row_act['status']); ?></a></td>                            
                            <td>&nbsp;<?php echo $row_act['order_datetime']; ?></td>                            
                            <td>&nbsp;<?php echo $row_act['vendor_invoice_num']; ?></td>
                            <td>&nbsp;<?php echo $row_act['po']; ?></td>
                            <td><?php echo "<strong>".$row_act['location_name']."</strong>"."<br>".$row_act['city'].", ".$row_act['code']."<br>".$row_act['zip']; ?></td>
                            <td>&nbsp;<?php if($row_act['emp_name'] != "") echo $row_act['emp_name']; else echo "-";?></td>                            
                            <td>&nbsp;<?php echo $row_act['terms']; ?></td>                            
                            <td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['total']; ?></td>
                            <td class="center" style="vertical-align:middle;">
                            <a href="backoffice_purchases.php?flag&purchase_id=<?php echo $row_act['id']; ?>&loc_id=<?php echo $row_act['loc_id']; ?>" >
                            <img title="Edit"  src="images/edit.png" ></a>
                            <img  src="images/icons/search.png" ></td>
                            <!--<td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['subtotal']; ?></td>
                            <td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['tax_total']; ?></td>-->
                        </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div id="e-7">
                <div style=" overflow-y:auto;">
                  <table id="licence_table" class="table table-bordered responsive">
                    <colgroup>
                    <col class="con0" style="align: center; width:5%; vertical-align:middle" />
                    <col class="con1" style="width:5%;" />
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:25%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:5%;"/>                    
                    </colgroup>
                    <thead>
                      <tr>
                        <th class="head0" >Image</th>
                        <th class="head1" >S</th>
                        <th class="head0" >Date & Time</th>
                        <th class="head1" >INVOICE#</th>
                        <th class="head0" >PO#</th>
                        <th class="head1" >Location</th>
                        <th class="head0" >Order By</th>
                        <th class="head1" >Terms</th>                        
                        <th class="head0" >Total</th>
                        <th class="head1" >Action</th> 
                        <!--<th class="head1" >Sub</th>
                        <th class="head0" >Tax</th>  -->                
                      </tr>
                    </thead>
                    <tbody>
                    <?php while($row_act = mysql_fetch_array($res_act)){
						if($row_act['tax_total']!=""){							
							$row_act['tax_total'] = number_format((($row_act['tax_total']*$row_act['subtotal'])/100),2,'.',',');
							$row_act['total'] = number_format(($row_act['subtotal']+$row_act['tax_total']),2,'.',',');
						}
					
					?>
                    	<tr onClick="get_detail(<?php echo $row_act['id']; ?>)" data-comments="<?php echo $row_act['comments']; ?>" id="row_<?php echo $row_act['id']; ?>">
                        	<td>&nbsp;<img onerror="this.src='images/Default Primary Type - Restaurants.png'" src="<?php if($row_act['image']!=""){ echo APIPHP.'images/'.$row_act['image'];} else{echo 'images/Default Primary Type - Restaurants.png';} ?>" width="50" height="50" ></td>
                            <td class="center" style="vertical-align:middle;"><a href="backoffice_purchases.php?flag&purchase_id=<?php echo $row_act['id']; ?>&loc_id=<?php echo $row_act['loc_id']; ?>" >&nbsp;<?php echo getstatus($row_act['status']); ?></a></td>                            
                            <td>&nbsp;<?php echo $row_act['order_datetime']; ?></td>                            
                            <td>&nbsp;<?php echo $row_act['vendor_invoice_num']; ?></td>
                            <td>&nbsp;<?php echo $row_act['po']; ?></td>
                            <td><?php echo "<strong>".$row_act['location_name']."</strong>"."<br>".$row_act['city'].", ".$row_act['code']."<br>".$row_act['zip']; ?></td>
                            <td>&nbsp;<?php if($row_act['emp_name'] != "") echo $row_act['emp_name']; else echo "-";?></td>                            
                            <td>&nbsp;<?php echo $row_act['terms']; ?></td>                            
                            <td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['total']; ?></td>
                            <td class="center" style="vertical-align:middle;">
                            <a href="backoffice_purchases.php?flag&purchase_id=<?php echo $row_act['id']; ?>&loc_id=<?php echo $row_act['loc_id']; ?>" >
                            <img title="Edit"  src="images/edit.png" ></a>
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
                  <table id="licence_table" class="table table-bordered responsive">
                    <colgroup>
                    <col class="con0" style="align: center; width:5%; vertical-align:middle" />
                    <col class="con1" style="width:5%;" />
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:25%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:10%;"/>
                    <col class="con0" style="width:10%;"/>
                    <col class="con1" style="width:5%;"/>                    
                    </colgroup>
                    <thead>
                      <tr>
                        <th class="head0" >Image</th>
                        <th class="head1" >S</th>
                        <th class="head0" >Date & Time</th>
                        <th class="head1" >INVOICE#</th>
                        <th class="head0" >PO#</th>
                        <th class="head1" >Location</th>
                        <th class="head0" >Order By</th>
                        <th class="head1" >Terms</th>                        
                        <th class="head0" >Total</th>
                        <th class="head1" >Action</th> 
                        <!--<th class="head1" >Sub</th>
                        <th class="head0" >Tax</th>  -->                
                      </tr>
                    </thead>
                    <tbody>
                    <?php while($row_act = mysql_fetch_array($res_shipped)){
						if($row_act['tax_total']!=""){							
							$row_act['tax_total'] = number_format((($row_act['tax_total']*$row_act['subtotal'])/100),2,'.',',');
							$row_act['total'] = number_format(($row_act['subtotal']+$row_act['tax_total']),2,'.',',');
						}
					
					?>
                    	<tr onClick="get_detail3(<?php echo $row_act['id']; ?>)" data-comments="<?php echo $row_act['comments']; ?>" id="row_<?php echo $row_act['id']; ?>">
                        	<td>&nbsp;<img onerror="this.src='images/Default Primary Type - Restaurants.png'" src="<?php if($row_act['image']!=""){ echo APIPHP.'images/'.$row_act['image'];} else{echo 'images/Default Primary Type - Restaurants.png';} ?>" width="50" height="50" ></td>
							<td class="center" style="vertical-align:middle;"><a href="backoffice_purchases.php?flag&purchase_id=<?php echo $row_act['id']; ?>&loc_id=<?php echo $row_act['loc_id']; ?>" >&nbsp;<?php echo getstatus($row_act['status']); ?></a></td>
                            <td>&nbsp;<?php echo $row_act['order_datetime']; ?></td>                            
                            <td>&nbsp;<?php echo $row_act['vendor_invoice_num']; ?></td>
                            <td>&nbsp;<?php echo $row_act['po']; ?></td>
                            <td><?php echo "<strong>".$row_act['location_name']."</strong>"."<br>".$row_act['city'].", ".$row_act['code']."<br>".$row_act['zip']; ?></td>
                            <td>&nbsp;<?php if($row_act['emp_name'] != "") echo $row_act['emp_name']; else echo "-";?></td>                            
                            <td>&nbsp;<?php echo $row_act['terms']; ?></td>                            
                            <td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['total']; ?></td>
                            <td class="center" style="vertical-align:middle;">
                            <a href="backoffice_purchases.php?flag&purchase_id=<?php echo $row_act['id']; ?>&loc_id=<?php echo $row_act['loc_id']; ?>" >
                            <img title="Edit"  src="images/edit.png" ></a>
                            <img  src="images/icons/search.png" ></td>
                            <!--<td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['subtotal']; ?></td>
                            <td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['tax_total']; ?></td>-->
                        </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              
			  
              <div id="e-8">
                <div>
                  <table id="licence_table1" class="table table-bordered responsive">
                     <colgroup>
                        <col class="con0" style="align: center; width:5%; vertical-align:middle" />
                        <col class="con1" style="width:5%;" />
                        <col class="con0" style="width:10%;"/>
                        <col class="con1" style="width:10%;"/>
                        <col class="con0" style="width:10%;"/>
						<col class="con1" style="width:25%;"/>
                        <col class="con0" style="width:10%;"/>
                        <col class="con1" style="width:10%;"/>
                        <col class="con0" style="width:10%;"/>
                        <col class="con1" style="width:5%;"/>
                    </colgroup>
                    <thead>
                      <tr>
                        <th class="head0" >Image</th>
                        <th class="head1" >S</th>
                        <th class="head0" >Date & Time</th>
                        <th class="head1" >INVOICE#</th>
                        <th class="head0" >PO#</th>
                        <th class="head1" >Location</th>
                        <th class="head0" >Order By</th>
                        <th class="head1" >Terms</th>                        
                        <th class="head0" >Total</th>
                        <th class="head1" >Action</th> 
                        <!--<th class="head1" >Sub</th>
                        <th class="head0" >Tax</th>  -->                        
                      </tr>
                    </thead>
                    <tbody>
                     <?php while($row_his = mysql_fetch_array($res_his)){?>
                    	<tr onClick="get_detail2(<?php echo $row_his['id']; ?>)" data-comments="<?php echo $row_his['comments']; ?>" id="row_<?php echo $row_his['id']; ?>">
                        	
                            <td>&nbsp;<img onerror="this.src='images/Default Primary Type - Restaurants.png'" src="<?php if($row_his['image']!=""){ echo APIPHP.'images/'.$row_his['image'];} else{echo 'images/Default Primary Type - Restaurants.png';} ?>" width="50" height="50" ></td>
                            <td class="center" style="vertical-align:middle;"><a href="backoffice_purchases.php?flag&purchase_id=<?php echo $row_his['id']; ?>&loc_id=<?php echo $row_his['loc_id']; ?>&history=Yes" >&nbsp;<?php echo getstatus($row_his['status']); ?></a></td>
                            <td>&nbsp;<?php echo $row_his['order_datetime']; ?></td>
                            <td>&nbsp;<?php echo $row_his['vendor_invoice_num']; ?></td>
                            <td>&nbsp;<?php echo $row_his['po']; ?></td>
                            <td>&nbsp;<?php echo $row_his['location_name']."<BR>".$row_his['city'].", ".$row_his['code']."<BR>".$row_his['zip']; ?></td>
                            <td>&nbsp;<?php echo $row_his['emp_nam']; ?></td>
                            <td>&nbsp;<?php echo $row_his['terms']; ?></td>                            
                            <td class="right" >&nbsp;<?php echo $row_his['symbol'].''.$row_his['total']; ?></td>                            
                            <td class="center" style="vertical-align:middle;"><a href="backoffice_purchases.php?flag&purchase_id=<?php echo $row_his['id']; ?>&loc_id=<?php echo $row_his['loc_id']; ?>&history=Yes" >
                            <img title="Edit"  src="images/edit.png" ></a>
                            <img  src="images/icons/search.png" ></td>
                            <!--<td class="right">&nbsp;<?php echo $row_his['symbol'].''.$row_his['subtotal']; ?></td>
                            <td class="right" >&nbsp;<?php echo $row_his['symbol'].''.$row_his['tax_total']; ?></td>-->
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
          <div class="span4 profile-left" id="comp_tab" style="width: 33%; margin-left:1% !important; float:right;">
          <div class="widgetbox company-photo">
              <h4 class="widgettitle">Comments</h4>
              <div class="widgetcontent">
                <div class="profilethumb" style="text-align:left;">
                <label>Comments:</label>
                <div id="comments">
                
                </div>
                </div>
                
                <br />
                
              </div>
            </div>
           
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