<?php
require_once 'require/security.php';
include 'config/accessConfig.php';







if (isset($_REQUEST['m'])) {
   if ($_REQUEST['m']=="get_vendor_details") {

        //$html = '<table id="detail_table" class="table table-bordered responsive" style="height: auto !important;"> <colgroup> <col class="con0" style="align: center; width:10%; vertical-align:middle"/> <col class="con1" style="width:30%;"/> <col class="con1" style="width:15%;"/> <col class="con0" style="width:15%;"/> <col class="con0" style="width:15%;"/> <col class="con1" style="width:15%;"/> </colgroup> <thead> <tr> <th class="head0" >S</th> <th class="head1" >Name</th> <th class="head1" >Pack Size</th> <th class="head0" >Qty in Pack</th> <th class="head0" >Tax</th> <th class="head1" >Price</th><!--<th class="head1" >Sub</th> <th class="head0" >Tax</th> --> </tr></thead> <tbody>';
		$html = '';
        if (!empty($_REQUEST['vendor_id'])) {

            $sql_vendor_items = "SELECT * FROM vendor_items WHERE vendor_id = '".$_REQUEST['vendor_id']."'";
            $sql_vendor_items_res = mysql_query($sql_vendor_items);

            while ($row = mysql_fetch_assoc($sql_vendor_items_res)) {

                $html .= '<tr>';
                $html .= '<td>'.(($row['status']) ? $row['status'] : '').'</td>';
				//$html .= '<td>'.(($row['name']) ? $row['name'] : '').'</td>';
                $html .= '<td>'.(($row['description']) ? $row['description'] : '').'</td>';
                $html .= '<td>'.(($row['pack_size']) ? $row['pack_size'] : '').'</td>';
                $html .= '<td>'.(($row['qty_in_pack']) ? $row['qty_in_pack'] : '').'</td>';
                $html .= '<td>'.(($row['tax_percentage']) ? $row['tax_percentage'] : '').'</td>';
                $html .= '<td>'.(($row['price']) ? $row['price'] : '').'</td>';
                $html .= '</tr>';
            }
        }
		
        //$html .= '</tbody></table>';



        if (mysql_num_rows($sql_vendor_items_res)>0) {
            echo $html;
        }else {
            echo '<h5 style="text-align: center;padding-top: 5%;font-weight: bold;font-size: 0.7rem;">No data available!</h5>';
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

                jQuery('#licence_table').dataTable({
                    "sPaginationType": "full_numbers",
                    "aaSorting": [[2, "asc"]],
                    "bJQuery": true,
                    "fnDrawCallback": function (oSettings) {
                        //  jQuery.uniform.update();
                    }
                });
				
				jQuery('#detail_table').dataTable({
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
            #details_of_vendor {
                background: #FFF; height: 80px; vertical-align: middle; text-align: center;
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
                    <li>Buying</li>
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
                    <div class="pageicon"><span class="iconfa-credit-card"></span></div>
                    <div class="pagetitle">
                        <h5>List of Wholesalers Vendor Purchases From</h5>
                        <h1>Buying</h1>
                    </div>                    
                </div>
                <!--pageheader-->
				
				<div class="maincontent">
					<div class="maincontentinner" >
						<div class="row-fluid" id="inventoryTable" style="margin: 6px;">
							  <div class="span7" style="width:56%">
								<div class="widgetbox">
									<h4 class="widgettitle">Inventory Items </h4>
									<div class="widgetcontent">

										<table id="licence_table" class="table table-bordered table-infinite">
											<colgroup>
												<col class="con1" style="width:7%;" />
												<col class="con0" style="width:5%;"/>
												<col class="con1" style="width:20%;"/>
												<col class="con0" style="width:25%;"/>
												<col class="con1" style="width:10%;"/>
												<col class="con0" style="width:10%;"/>
												<col class="con1" style="width:10%;"/>
												<col class="con0" style="width:5%;"/>
											</colgroup>
											<thead>
												<tr>
													<th class="head0" >Image</th>
													<th class="head1" >Status</th>
													<th class="head0" >Name</th>
													<th class="head0" >Address</th>
													<th class="head1" >Location</th>
													<th class="head1" >Type</th>                        
													<th class="head0" >Terms</th>
													<th class="head1" >Action</th> 
												</tr>
											</thead>
											<tbody>
											<?php 

												$res_act = mysql_query("SELECT * FROM vendors  WHERE TRIM(name) <> 'Peddler\'s Son' and name is not NULL and name <> '' and type is not null and type <> ''  ");

												while($row_act = mysql_fetch_assoc($res_act)){
													
												
											
											?>
												<tr onClick="loadDetails(<?php echo $row_act['id']?>)" data-comments="" id="row_">
													<td style="line-height: 0px !important;text-align: center;">&nbsp;<img onerror="this.src='images/Default - User.png'" src="<?php echo explode(getcwd(),"\\")[2]."/images/".$row_act['StorePoint_image']; ?>" ></td>													                           
													<td class="center">&nbsp;<?php echo $row_act['status']; ?><!-- <img onerror="this.src='images/Default - User.png'" src="<?php //echo explode(getcwd(),"\\")[2]."/images/".$row_act['status'].'png'; ?>" > --></td>								
													<td>&nbsp;<?php echo $row_act['name']; ?></td>
													<td><?php echo $row_act['address']." ".$row_act['address2']; ?><br>
														<?php $arr = mysql_fetch_array(mysql_query("select name from countries where id = '".$row_act['country']."'"));echo $arr['name']; ?>
														<?php $arr = mysql_fetch_array(mysql_query("select name from states where id = '".$row_act['state']."'")); if(!empty($arr['name'])){echo ' , '.$arr['name'];} ?>
														<?php if(!empty($row_act['city'])){echo ' , '.$row_act['city'];} ?>
														 <br>
														 <?php echo $row_act['zip']; ?></td>
													<td>&nbsp;<?php echo $row_act['location_link']; ?></td>                          
													<td>&nbsp;<?php 
															$exploded = explode(",", $row_act['type']); 
															foreach ($exploded as $type) {
																$innerType = mysql_fetch_array(mysql_query("SELECT * FROM vendors_types WHERE vendor_type_id = '".$type."'"));
																echo $innerType['code'];
																echo "<br>";
															}       ?></td>                            
													<td class="right">&nbsp;<?php echo $row_act['terms_types']; ?>
														<?php 
															$exploded = explode(",", $row_act['terms_types']); 
															foreach ($exploded as $type) {
																$innerType = mysql_fetch_array(mysql_query("SELECT * FROM vendors_terms_types WHERE vendors_terms_types = '".$type."'"));
																echo $innerType['code'];
																echo "<br>";
															}     ?>
													</td>
													<td class="center" style="vertical-align:middle;">
													<!--<a href="backoffice_purchases.php?flag&purchase_id=" >
													<img title="Edit"  src="images/edit.png" ></a>-->
													<img  src="images/icons/search.png" onclick="loadDetails(<?php echo $row_act['id']?>)"></td>
													<!--<td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['subtotal']; ?></td>
													<td class="right">&nbsp;<?php echo $row_act['symbol'].''.$row_act['tax_total']; ?></td>-->
												</tr>
											<?php } ?>
											</tbody>
										</table>

									</div>
								</div>
							  </div>
							  
							  <div class="span5"style="width: 688.3px;"> <!-- 42.94% -->
								<div class="widgetbox"> 
									<h4 class="widgettitle itemnameD">Items Available </h4>
									<div class="widgetcontent">
											<table id="" class="table table-bordered responsive" style="height: auto !important;"> 
												<colgroup> 
													<col class="con0"/> 
													<col class="con1"/> 
													<col class="con1"/> 
													<col class="con0"/> 
													<col class="con0"/> 
													<col class="con1"/> 
												</colgroup> 
												<thead> 
													<tr> 
														<th class="head0" >Status</th> 
														<th class="head1" >Name</th> 
														<th class="head0" >Pack Size</th> 
														<th class="head1" >Qty in Pack</th> 
														<th class="head0" >Tax</th> 
														<th class="head1" >Price</th> 
													</tr>
												</thead> 
												<tbody id="details_of_vendor">
													<tr><td colspan='6'>No record found</td></tr>
												</tbody>
											</table>
									</div>
								</div>
							  </div>
							 
						</div>
							
						  
						  <?php include_once 'require/footer.php';?>
						<!--footer-->
						
					</div><!--maincontentinner-->
				</div><!--maincontent-->
				
            </div>
        </div>
            <!--rightpanel-->
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
                        console.log("get_employee, res.first_name: " + res.first_name);
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



        <script type="text/javascript">
            
            function loadDetails(vendor_id) {

                //alert(vendor_id);

				//console.log("SELECTED ROW id => " + $(this).get(0).id);
                jQuery.ajax({
                      method: "POST",
                      url: "buying.php",
                      data: { m: "get_vendor_details", vendor_id: vendor_id }
                    })
                      .done(function( msg ) {
                        

                        console.log(msg);
                        jQuery('#details_of_vendor').html('');
                        jQuery('#details_of_vendor').html(msg);


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


<?php /*

<div class="span4">
                                <div class="clearfix">
                                    <h4 class="widgettitle">Vendors</h4>          
                                </div>
							<div class="widgetcontent">
							<table id="licence_table" class="table table-bordered responsive" style="height: auto !important;"> 
							<colgroup> 
								<col class="con0"/> 
								<col class="con1"/> 
								<col class="con1"/> 
								<col class="con0"/> 
								<col class="con0"/> 
								<col class="con1"/> 
							</colgroup> 
							<thead> 
								<tr> 
									<th class="head0" >S</th> 
									<th class="head1" >Name</th> 
									<th class="head0" >Pack Size</th> 
									<th class="head1" >Qty in Pack</th> 
									<th class="head0" >Tax</th> 
									<th class="head1" >Price</th> 
								</tr>
							</thead> 
							<tbody>
							<?php
							$sql_vendor_items = "SELECT * FROM vendor_items WHERE vendor_id = '12'";
							$sql_vendor_items_res = mysql_query($sql_vendor_items);
								while ($row = mysql_fetch_assoc($sql_vendor_items_res)) {
							?>
								<tr>
									<td><?php echo (($row['status']) ? $row['status'] : ''); ?></td>
									<td><?php echo (($row['description']) ? $row['description'] : ''); ?></td>
									<td><?php echo (($row['pack_size']) ? $row['pack_size'] : ''); ?></td>
									<td><?php echo (($row['qty_in_pack']) ? $row['qty_in_pack'] : ''); ?></td>
									<td><?php echo (($row['tax_percentage']) ? $row['tax_percentage'] : ''); ?></td>
									<td><?php echo (($row['price']) ? $row['price'] : ''); ?></td>
								</tr>
							<?php
								}
							?>
							
                            </tbody>
							</div>
                        </div> <!-- clearfix -->

*/?>
