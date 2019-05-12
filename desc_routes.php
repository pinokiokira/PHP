<?php
require_once 'require/security.php';
include 'config/accessConfig.php';

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
                 <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" type="text/css" />
        <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" type="text/css" /> -->

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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

         <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
<!--         <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
 --><!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

 -->  
   <style>
            body {
                top:0px!important;
            }
            .orderBox1{
                min-height: 250px;
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
			.line_vehicles{	background-color:#808080;color:#000000 !important; }

        </style>
        <script type="text/javascript">
            
            
            jQuery(document).ready(function ($) {

            	 jQuery('#vendor_routes_tbl').dataTable({
                    language: {
                                paginate: {
                                  next: '>', // or '→'
                                  previous: '<' // or '←' 
                                }
                              },
                    //select: true,
                    "sPaginationType": "full_numbers",
                    "bJQuery": true,
                  // select: true,
                    "fnDrawCallback": function (oSettings) {
                        //  jQuery.uniform.update();
                          var remember_val=jQuery('#remember_val').val();
                        // alert(remember_val);
                                jQuery('.line_vehicles').removeClass('line_vehicles');
                                        jQuery('#row_'+remember_val).addClass('line_vehicles');
                    }



                });
            	 jQuery('#vendor_routes_tbl_wrapper .dataTables_filter input').unbind('keypress keyup').bind('keypress keyup', function(e){
					jQuery('.line_vehicles').removeClass('line_vehicles');
					jQuery("#row_"+jQuery('#vehicles_back').val()).addClass('line_vehicles');
				});

              //     jQuery('#row_select').click(function(){


              //     jQuery('#vendor_routes_tbl').dataTable({
              //       language: {
              //                   paginate: {
              //                     next: '>', // or '→'
              //                     previous: '<' // or '←' 
              //                   }
              //                 },
              //       //select: true,
              //       "sPaginationType": "full_numbers",
              //       "bJQuery": true,
              //      select: true,
              //       "fnDrawCallback": function (oSettings) {
              //           //  jQuery.uniform.update();
              //       }



              //   });

              // });



                // jQuery('#vendor_routes_tbl').dataTable({
                //     "sPaginationType": "full_numbers",
                //     "bJQuery": true,
                //      "scrollY": 200,
                //     "scrollX": true,
                //     "fnDrawCallback": function (oSettings) {
                //         //  jQuery.uniform.update();
                //     }
                // });
                
                jQuery('#vendor_routes_loc_tbl').dataTable({
                    "sPaginationType": "full_numbers",
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

            function get_detail(id,id1){

                  var remember_val=jQuery('#remember_val').val();
        var comments = jQuery('#row_'+id1).attr('data-comments');
        if(comments!=""){
            jQuery("#comments").html(comments);
        }else{
            jQuery("#comments").html("<strong>There are no Comments For this Purchase!</strong>")
        }
        

//        var table = $('#vehicles_tbl').DataTable();


// var selectedRows = table.rows({ selected: true });
// alert(selectedRows);
// if(selectedRows)
// {
   
        jQuery('.line_vehicles').removeClass('line_vehicles');
        jQuery('#row_'+id1).addClass('line_vehicles');
        jQuery('#details').html('');
          jQuery('#remember_val').val(id1);

         var table = jQuery('#vendor_routes_tbl').DataTable();
 
  //alert( 'Rows '+table.rows( '.selected' ).count()+' are selected' );

var count=table.rows( '.selected' ).count();
    //      if(count==0)
    // {
        jQuery.ajax({
                url:"desc_route_vendor_locations.php",
                type:'POST',
                data:{id:id},
                success:function(data){
                jQuery('#details').html(data);
                }
            
        });
    }
   // }

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
                    <li><span class="separator"></span> Routes </li>
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
                    <div style="float:right;margin-top: 10px; display:block;">
                        <button class="btn btn-success btn-large hidden" >Add</button>
                        <button style="margin-right:-6px" data-toggle="modal" data-target="#squarespaceModal" class="btn btn-success btn-large">Add</button>
                        <div class="center">
                            
                        </div>

                      </div> 
                    <div class="pageicon"><span class="iconfa-truck"></span></div>
                    <div class="pagetitle">
                        <h5>The Following are the various established routes and the locations to deliver too</h5>
                        <h1>Routes</h1>
                    </div>                    
                </div>
                <!--pageheader-->
                                        <?php //echo "<pre>"; print_r($_SESSION);
                                        ?>
               <div class="maincontent">
                    <div class="maincontentinner" >
                       

                        <div class="row-fluid" id="#!" style="margin: 6px;">
                            <div class="span8" style="width: 50%;float:left;overflow:auto;margin-right: 10px;">
                                <div class="clearfix1">
                                    <h4 class="widgettitle">Vendor Routes</h4>
                                </div>
                                <div class="widgetcontent  orderBox">
                                <table id="vendor_routes_tbl" class="table table-bordered table-infinite">
                                										<input type="hidden" id="vehicles_back">
                                                                                                            <input type="hidden" id="remember_val">


									<colgroup>
										<col class="con0" style="width:5%;"/>
										<col class="con1"/>
										<col class="con0"/>
										<col class="con1"/>
										<col class="con0"/>
										<col class="con1"/>
										<col class="con0"/>
										 <col class="con1"/>
										<!--<col class="con0"/> -->
									</colgroup>
                                    <thead>
                                        <tr>
                                            <th>S</th>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Estimated Time</th>
                                            <th>Last On</th>
                                            <!-- <th>Last By</th> -->
                                           <!--  <th>Created On</th>
                                            <th>Created By</th> -->
                                            <th>Created Datetime</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                                     $empmaster_id=$_SESSION['client_id'];
// $curr = mysql_fetch_array(mysql_query("SELECT gc.symbol from employees_master as e LEFT JOIN global_currency as gc ON gc.id = e.currency_id where empmaster_id =".$empmaster_id));
// $c_symbol = $curr['symbol'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
$vendor_id = $vender['StorePoint_vendor_Id'];

                                             $res_routes = mysql_query("SELECT * FROM vendor_routes where vendor_id='$vendor_id'");
                                                while($row_routes = mysql_fetch_assoc($res_routes)){
                                            ?>
                                           <tr onClick="get_detail(<?php echo $row_routes['vendor_id'];?>,<?php echo $row_routes['vendor_routes_id'];?>)"  data-comments="" id="row_<?php echo $row_routes['vendor_routes_id'];?>">
                                            <td><img src="images/Active, Corrected, Delivered.png"></td>
                                            <td><?= $row_routes['route_code'];  ?></td>
                                            <td><?= $row_routes['route_name'];  ?></td>
                                            <td><?= $row_routes['route_description'];  ?></td>
                                            <td><?= $row_routes['route_estimatedtime'];  ?></td>
                                            <td><?= $row_routes['last_on'];  ?></td>
                                           <!--  <td><?= $row_routes['created_on'];  ?></td>
                                            <td><?= $row_routes['created_by'];  ?></td> -->
                                            <td><?= $row_routes['created_datetime'];  ?></td>
                                            <td style="text-align: center;"><img src="images/edit.png"></i></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            
                            <div style="width: 49%;float: left;">
                            <div class="span8" style="width:100%;float:right; overflow:auto;margin-right: -3px">
                                <div class="clearfix1">
                                    <h4 class="widgettitle">Vendor Routes Locations</h4>
                                </div>
                                <div id="details" class="widgetcontent"></div>
                            </div>
                        </div>

                            <div class="span12" style="width:99.7%; overflow:auto;margin-left: 0;">
                                <div class="clearfix1">
                                    <h4 class="widgettitle">Map</h4>
                                </div>
                                <div class="widgetcontent orderBox2">
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



<!-- Model starts -->

<!-- line modal -->
<div class="modal fade" style="width:450px" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button"  class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            <h3 class="modal-title" id="lineModalLabel">Add Location</h3>
        </div>
        <div class="modal-body" style="margin-left: -55px;margin-right: -40px" >
            
            <!-- content goes here -->
            <form method="post" action="store_desc_route_add_location.php"> 
                <div class="form-group" style="display: flex;width: 80%;margin: 0 auto;">
                                    <label for="route" class="col-md-4" style="width: 25%;">Route:</label>

                    
                    <select class="form-control" id="route" placeholder="Route" name="route" style="width: 75%;">
                        <option>--Please select route--</option>
                        <?php  
                   $empmaster_id=$_SESSION['client_id'];
// $curr = mysql_fetch_array(mysql_query("SELECT gc.symbol from employees_master as e LEFT JOIN global_currency as gc ON gc.id = e.currency_id where empmaster_id =".$empmaster_id));
// $c_symbol = $curr['symbol'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
$vendor_id = $vender['StorePoint_vendor_Id'];

                    // echo $client_id;die;
                    $query1 = "select * from vendor_routes where vendor_id='$vendor_id'";
                    $res1 = mysql_query($query1);
                    while($row1=mysql_fetch_array($res1))
                    {
                    
                    ?>
                        <option value="<?php echo $row1['route_code'];?>"><?php echo $row1['route_name'];?></option>
                         <?php } ?>

                    </select>
<!--                 <input type="text" class="form-control" id="route" placeholder="Route" name="route" style="width: 75%;" disabled="disabled">
 -->              
           
              </div>
              <div class="form-group" style="display: flex;width: 80%;margin: 0 auto;">
                <label for="priority" class="col-md-4" style="width: 25%;">Priority:</label>
                <input type="text" class="form-control" id="priority" placeholder="Priority" name="priority" style="width: 75%;">
              </div>
              <div class="form-group" style="display: flex;width: 80%;margin: 0 auto;">
                <label for="location" class="col-md-4" style="width: 25%;">Location:</label>
                <input type="text" class="form-control" id="location" placeholder="Location" name="location" style="width: 75%;">
              </div>
              <div class="form-group" style="display: flex;width: 80%;margin: 0 auto;">
                <label for="estTimeFromPre" class="col-md-4" style="width: 25%;">Est time from Previews:</label>
                <input type="text" class="form-control" id="estTimeFromPre" placeholder="Est time from Previews" name="pre_time" style="width: 75%;">
              </div>
              <div class="form-group" style="display: flex;width: 80%;margin: 0 auto;">
                <label for="assignBy" class="col-md-4" style="width: 25%;">Assign By:</label>
                <input type="text" class="form-control" id="assignBy" placeholder="Assign By" name="assign_by" style="width: 75%;">
              </div>
              <div class="form-group" style="display: flex;width: 80%;margin: 0 auto;">
                <label for="createdBy" class="col-md-4" style="width: 25%;">Created By:</label>
                <input type="text" class="form-control" id="createdBy" placeholder="Created By" name="created_by" style="width: 75%;">
              </div>
               <div class="form-group" style="display: flex;width: 80%;margin: 0 auto;">
                <label for="createdBy" class="col-md-4" style="width: 25%;">Created On:</label>
                <input type="text" class="form-control" id="created_on" placeholder="Created On" name="created_on" style="width: 75%;">
              </div>
              <div class="form-group" style="display: flex;width: 80%;margin: 0 auto;">
                <label for="createdBy" class="col-md-4" style="width: 25%;">Created Date:</label>
                <input type="text" class="form-control" id="created_datetime" placeholder="Created Date" name="created_datetime" style="width: 75%;">
              </div>
              <!-- <div class="form-group" style="display: flex;width: 80%;margin: 0 auto;">
                <label for="exampleInputEmail1" class="col-md-4" style="width: 25%;">Created On</label>
                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Created On" style="width: 75%;">
              </div> -->
               <div class="modal-footer" style="width:420px;margin-bottom:-15px;margin-left:40px;text-align: center;max-height:50px !important;">
                    <p class="stdformbutton">
                        <button type="button" role="button" id="btnCancel" data-dismiss="modal" class="btn btn-default" style="padding: 5px 12px 5px;" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="btnCompose" name="btnCompose"  class="btn btn-primary" style="padding: 5px 12px 5px;" >Submit</button>
                    </p>
                </div>
<!--               <button style="margin-left: 152px;margin-bottom: -12px" type="submit" class="btn btn-success model_btn" id="model_btn_new" value="Submit">Submit</button>
 -->            </form>
            <!-- <?php  
if (isset($_POST["model_btn"])) {
    $routre = $_POST['route'];
    $priority = $_POST['priority'];
    $location = $_POST['location'];
    $pre_time = $_POST['pre_time'];
    $assign_by = $_POST['assign_by'];
    $created_by = $_POST['created_by'];

    $sql = "INSERT INTO vendor_routes_locations (vendor_routes_locations_id,priority,location_id,vendor_id, created_by, created_on)
    VALUES ('$routre', '$priority', '$location','$pre_time', '$assign_by', '$created_by')";
    if ($conn->query($sql) === TRUE) {
        echo "Data Insert Successfully";
    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
            ?> -->


            <script type="text/javascript">

            	  jQuery(document).ready(function () {
         jQuery('#created_datetime').datepicker({dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true});
           // jQuery('#end_date').datepicker({dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true});
        });




                function model_btn_new(){
                    var route = $('#route').val();
                    var priority = $('#priority').val();
                    var location = $('#location').val();
                    var estTimeFromPre = $('#estTimeFromPre').val();
                    var assignBy = $('#assignBy').val();
                    var createdBy = $('#createdBy').val();

                    var dataurl = "store_desc_route_add_location.php";
                jQuery.ajax({
                    url: dataurl,
                    type: "post",
                    cache: false,
                    async: false,  
                    data: { route: "route",priority: "priority",location: "location",estTimeFromPre: "estTimeFromPre",assignBy: "assignBy",createdBy: "createdBy" },                  
                    success: function (data) {                        
                           if(data==1)
                           {
                            alert("data submitted");
                           }
                           else
                           {
                                alert("data not submitted");

                           }
                    }
                });
                }
            </script>
        </div>
       <!--  <div class="modal-footer">
            <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                <div class="btn-group" role="group">
                    <button style="margin-bottom: -12px" type="button" class="btn btn-default" data-dismiss="modal"  role="button">Close</button>
                </div>
                <div class="btn-group btn-delete hidden" role="group">
                    <button type="button" id="delImage" class="btn btn-default btn-hover-red" data-dismiss="modal"  role="button">Delete</button>
                </div>
                <div class="btn-group hidden" role="group">
                    <button type="button" id="saveImage" class="btn btn-default btn-hover-green" data-action="save" role="button">Save</button>
                </div>
            </div>
        </div> -->
    </div>
  </div>
</div>

<!-- Model ends -->












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

                if(flag == 0)
                {
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

        <script type="text/javascript">
            // jQuery(function(){
            //     var h = jQuery('.header').height()+jQuery('.breadcrumbs').height()+jQuery('.pageheader').height();
            //     var x = h+150;
            //     var w = jQuery(window).height()-x;
            //      jQuery('.orderBox').css({'height':w+'px'});
            // });
        </script>

        <script type="text/javascript">
            jQuery(function(){
                var h = jQuery('.header').height()+jQuery('.breadcrumbs').height()+jQuery('.pageheader').height();
                var x = h+150+300+42;
                var w = jQuery(window).height()-x;
                 jQuery('.orderBox2').css({'height':w+'px'});
            });
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

