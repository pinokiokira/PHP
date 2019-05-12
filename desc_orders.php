<?php
require_once 'require/security.php';
include 'config/accessConfig.php';

$empmaster_id=$_SESSION['client_id'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
$vendor_id = $vender['StorePoint_vendor_Id'];


$query_act = "SELECT p.id,p.status,
                   CASE p.status
                   when 'Ordered'   then DATE_FORMAT(p.order_datetime,'%Y-%m-%d %H:%i')
                   when 'Shipped'   then DATE_FORMAT(p.lastchange_datetime,'%Y-%m-%d %H:%i')
                   END as order_datetime,
                 p.po,vt.code as terms,p.subtotal,
                 (select COALESCE(sum(ordered_tax_percentage),0) from purchase_items where purchase_id =p.id) as                                tax_total,
                 p.total,p.comments,l.image,
                 gc.symbol,l.id as loc_id, l.name as location_name, l.phone as phone,st.name as state_name,lt.name as location_type,CONCAT(e.first_name,' ',e.last_name) as emp_name,l.zip,st.code,p.vendor_invoice_num
                 FROM purchases as p
                 LEFT JOIN locations l ON l.id = p.location_id
                 LEFT JOIN global_currency as gc ON gc.id = l.currency_id
                 LEFT JOIN states as st on st.id = l.state
                 LEFT JOIN location_types as lt ON lt.id = l.primary_type
                 LEFT JOIN employees as  e ON e.id = p.lastchange_employee_id                
                 LEFT JOIN vendors_terms_types AS vt ON vt.vendors_terms_types = p.terms    
                 WHERE p.status IN('Ordered')  AND p.vendor_id='".$vendor_id."' order by p.order_datetime desc";//,'Shipped'
$res_act = mysql_query($query_act) or die(mysql_error());

if (isset($_REQUEST['m'])) {
   if ($_REQUEST['m']=="get_items_details") {
        //$html = '<table id="detail_table" class="table table-bordered responsive" style="height: auto !important;"> <colgroup> <col class="con0" style="align: center; width:10%; vertical-align:middle"/> <col class="con1" style="width:30%;"/> <col class="con1" style="width:15%;"/> <col class="con0" style="width:15%;"/> <col class="con0" style="width:15%;"/> <col class="con1" style="width:15%;"/> </colgroup> <thead> <tr> <th class="head0" >S</th> <th class="head1" >Name</th> <th class="head1" >Pack Size</th> <th class="head0" >Qty in Pack</th> <th class="head0" >Tax</th> <th class="head1" >Price</th><!--<th class="head1" >Sub</th> <th class="head0" >Tax</th> --> </tr></thead> <tbody>';
        $html = '';
        if (!empty($_REQUEST['loc_id'])) {

            $sql_purchase_items = "SELECT *, inventory_items.description as name FROM purchase_items 
                LEFT JOIN purchases ON purchase_items.purchase_id=purchases.id 
                LEFT JOIN inventory_items ON purchase_items.inv_item_id=inventory_items.id 
                WHERE purchase_items.location_id = '".$_REQUEST['loc_id']."' AND purchases.status='Ordered' ";
            $sql_purchase_items_res = mysql_query($sql_purchase_items);


            $html .='<table id="order_item_tbl_box" class="table table-bordered table-infinite responsive">';
            $html .='<colgroup>';
            $html .='<col class="con1" style="width:100%;" />';
            $html .='</colgroup>';
            $html .='<thead>';
            $html .='<tr>';
            $html .='<th>Name</th>';
            $html .='</tr>';
            $html .='</thead>';
            $html .='<tbody>';

            while ($row = mysql_fetch_array($sql_purchase_items_res)) {
                $html .= '<tr>';
                $html .= '<td>'.(($row['name']) ? $row['name'] : '').'</td>';
                /*$html .= '<td>'.(($row['pack_size']) ? $row['pack_size'] : '').'</td>';
                $html .= '<td>'.(($row['qty_in_pack']) ? $row['qty_in_pack'] : '').'</td>';
                $html .= '<td>'.(($row['tax_percentage']) ? $row['tax_percentage'] : '').'</td>';
                $html .= '<td>'.(($row['price']) ? $row['price'] : '').'</td>';*/
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        }
        
        /*$html .= '</tbody></table>';*/
        if (mysql_num_rows($sql_purchase_items_res)>0) { echo $html;
        }else {
            echo '<h5 style="text-align: center;padding-top: 5%;font-weight: bold;font-size: 0.7rem;line-height: 20px;padding: 7px;">No data available!</h5>';
        }
        exit;
   }
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
        <!-- <script type="text/javascript" src="js/jquery.dataTables.min.js"></script> -->

        <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
        <!-- <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css"> -->
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

            .dataTables_paginate .first{
                display: none;
            }
            .dataTables_paginate .last{
                display: none;
            }
            .dataTables_paginate .previous {
                border-left: 1px solid #ddd;
            }
           /* div#location_tbl_info {
                height: 50px;
                position: relative;
                display: block;
            }
            .dataTables_paginate {
                float: none;
                text-align: center;
            }*/

            
           /* .dataTables_info {
                border-top: 0;
                float: none;
                text-align: center;
                position: relative;
                background: #eee;
                padding: 10px;
                font-size: 11px;
                border-bottom:none;
            }
            .dataTables_paginate {
                float: none;
                text-align: center;
                background: #eeeeee;
                border-top: 1px solid #eee;
                padding: 9px;
                border: 1px solid #ddd;
                border-top: none; 
                position: relative;
                width: 100%;
            }*/
        

            body {
                top:0px!important;
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
            td.dataTables_empty {
                text-align: center;
            }
			.line_location{	background-color:#808080;color:#000000 !important; }
			.line_item{	background-color:#808080;color:#000000 !important; }
			.line_order{ background-color:#808080;color:#000000 !important; }
        </style>
        
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                jQuery('#location_tbl').dataTable({

                    language: {
                        paginate: {
                          next: '>', // or '→'
                          previous: '<' // or '←' 
                        }
                      },
                    "sPaginationType": "full_numbers",
                    "aaSorting": [[0, "asc"]],
                    // select: true,
                    "bJQuery": true,
                    "fnDrawCallback": function (oSettings) {
                        //  jQuery.uniform.update();
                    }
                });
				
				jQuery('#location_tbl_wrapper .dataTables_filter input').unbind('keypress keyup').bind('keypress keyup', function(e){
					jQuery('.line_location').removeClass('line_location');
					jQuery("#row_"+jQuery('#location_back').val()).addClass('line_location');
					
				});

               /* jQuery('#order_item_tbl_box').dataTable({
                    "sPaginationType": "full_numbers",
                     select: true,
                    "bJQuery": true,
                    "fnDrawCallback": function (oSettings) {
                        //  jQuery.uniform.update();
                    }
                });*/
                
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
                    <li>Distribution</li>
                    <li><span class="separator"></span> Orders </li>
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
                     
                    <div class="pageicon"><span class="iconfa-truck"></span></div>
                    <div class="pagetitle">
                        <h5>Update How Orders Are Shipped to the Clients</h5>
                        <h1>Orders</h1>
                    </div>                    
                </div>
                <!--pageheader-->
                                        <?php //echo "<pre>"; print_r($_SESSION);
                                        ?>
               <div class="maincontent">
                    <div class="maincontentinner" >
                       
                        <div class="row-fluid" id="#!" style="margin: 6px;">
                            <div class="span8 " style="width:30%;float:left; overflow:auto;">
                                <div class="clearfix1">
                                    <h4 class="widgettitle">Locations</h4>
                                </div>
                                <div class="widgetcontent  orderBox">
                                <table id="location_tbl" class="table responsive table-bordered table-infinite">
									<input type="hidden" id="location_back">
									<colgroup>
										<col class="con0"/>
										<col class="con1"/>
										<col class="con0"/>
										<col class="con1"/>										
									</colgroup>
                                    <thead>
                                        <tr>
                                            <th>Name</th>
											<th>Invoice#</th>
                                            <th>Phone</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php while($row_act = mysql_fetch_array($res_act))
                                        { ?>
                                        <tr onClick="get_detail(<?php echo $row_act['id']; ?>)" id="row_<?php echo $row_act['id']; ?>">
                                            <td><?= $row_act['location_name']; ?></td>
											<td><?= $row_act['vendor_invoice_num']; ?></td>
                                            <td><?= $row_act['phone']; ?></td>
                                            <td><?= $row_act['order_datetime']; ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>

                            <div class="span8" style="width:30%;float:left; overflow:auto;">
                                <div class="clearfix1">
                                    <h4 class="widgettitle">Items</h4>
                                </div>
                                <div class="widgetcontent orderBox">
                                    <div id="items_deatails"></div>
                                    <!-- <table id="order_item_tbl" class="table table-bordered table-infinite">
                                        <colgroup>
                                            <col class="con1" style="width:100%;" />
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody id="items_deatails">
                                        </tbody>
                                    </table> -->
                                </div>
                            </div>

                            <div class="span8" style="width:37%;float:left; overflow:auto;">
                                <div class="clearfix1">
                                    <h4 class="widgettitle">Pull Orders</h4>
                                </div>
                                <div class="widgetcontent orderBox">
                                    <div id="pull_order_deatails"></div>
                                </div>
                            </div>

                        </div>

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
            .widgetcontent.orderBox {
                margin-bottom: 0;
                overflow-y: auto;
            }
            tbody#items_deatails
            {
                background: #FFF;
                height: 80px;
                vertical-align: middle;
                text-align: center;
            }
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

        <script type="text/javascript">
            jQuery(function(){
                var h = jQuery('.header').height()+jQuery('.breadcrumbs').height()+jQuery('.pageheader').height();
                var x = h+150;
                var w = jQuery(window).height()-x;
                 jQuery('.orderBox').css({'height':w+'px'});
            });
        </script>

    </body>
</html>
<script>
<?php if ($firstrow != "") { ?>
        jQuery(document).ready(function () {
            jQuery("#<?php echo $firstrow; ?>").trigger("click");
        });
<?php } ?>
</script>


  <script type="text/javascript">


            function get_detail(id){
                var comments = jQuery('#row_'+id).attr('data-comments');
                if(comments!=""){
                    jQuery("#comments").html(comments);
                }else{
                    jQuery("#comments").html("<strong>There are no Comments For this Purchase!</strong>")
                }
                jQuery('#location_back').val(id);
                jQuery('.line_location').removeClass('line_location');
                jQuery('#row_'+id).addClass('line_location');
                jQuery('#details').html('');
                jQuery.ajax({
                        url:"vendore_get_ordered_location_details.php",
                        type:'POST',
                        data:{id:id},
                        success:function(data){
                        /*jQuery('#details').html(data);*/
                        jQuery('#pull_order_deatails').html('');
                        jQuery('#items_deatails').html('');
                        jQuery('#items_deatails').html(data);
                           jQuery('#order_item_tbl_box').dataTable({
                            language: {
                                paginate: {
                                  next: '>', // or '→'
                                  previous: '<' // or '←' 
                                }
                              },
                            "sPaginationType": "full_numbers",
                            // select: true,
                            "bJQuery": true,
                            "fnDrawCallback": function (oSettings) {
                                //  jQuery.uniform.update();
                            }
                        });
						jQuery('#order_item_tbl_box_wrapper .dataTables_filter input').unbind('keypress keyup').bind('keypress keyup', function(e){
							jQuery('.line_item').removeClass('line_item');
							jQuery("#row_"+jQuery('#location_back').val()).addClass('line_item');
							
						});
						
                        }
                    
                });
            }


             function pull_order(id,idd){
				
				jQuery('.line_item').removeClass('line_item');
                jQuery('#row_item_'+id).addClass('line_item');	
				jQuery('#item_back').val(id);					
				 
                jQuery.ajax({
                        url:"vendor_pull_order.php",
                        type:'POST',
                        data:{id:id,sub:idd},
                        success:function(data){
                        /*jQuery('#details').html(data);*/
                        jQuery('#pull_order_deatails').html('');
                        jQuery('#pull_order_deatails').html(data);
                           jQuery('#pulled_ordered_deatails').dataTable({
                            "sPaginationType": "full_numbers",
                             select: true,
                            "bJQuery": true,
                            "fnDrawCallback": function (oSettings) {
                                //  jQuery.uniform.update();
                            }
                        });
						jQuery('#pulled_ordered_deatails_wrapper .dataTables_filter input').unbind('keypress keyup').bind('keypress keyup', function(e){
							jQuery('.line_order').removeClass('line_order');
							jQuery("#row_"+jQuery('#item_back').val()).addClass('line_order');
							
						});
                        }
                    
                });
            }

            
            function loadDetails(loc_id) {

                //alert(vendor_id);

                //console.log("SELECTED ROW id => " + $(this).get(0).id);
                jQuery.ajax({
                      method: "POST",
                      url: "desc_orders.php",
                      data: { m: "get_items_details", loc_id: loc_id }
                    })
                      .done(function( msg ) {
                        console.log(msg);
                        jQuery('#items_deatails').html('');
                        jQuery('#pull_order_deatails').html('');
                        jQuery('#items_deatails').html(msg);

                      /*   jQuery('#order_item_tbl_box').dataTable({
                            "sPaginationType": "full_numbers",
                             select: true,
                            "bJQuery": true,
                            "fnDrawCallback": function (oSettings) {
                                //  jQuery.uniform.update();
                            }
                        });*/
                      });
            };


        </script>

        