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
                    <li><a href="messages.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
                    <li>Sales</li>
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
                    <div style="float: right;margin-top: 10px;" class="messagehead">      
                        <!-- <button onClick="getaddLocation(<?php //echo $_SESSION["StorePointVendorID"]; ?>)" id="addcode01" style="margin-left:10px;" class="btn btn-success btn-large">Add</button> -->

                        <button class="btn btn-success btn-large">Add</button>
                    </div>      
                    <div class="pageicon"><span class="iconfa-tags"></span></div>
                    <div class="pagetitle">
                        <h5>Browse through your locations and customers</h5>
                        <h1>Sales</h1>
                    </div>                    
                </div>
                <!--pageheader-->
                                        <?php //echo "<pre>"; print_r($_SESSION);
                                        ?>
               <div class="maincontent">
                    <div class="maincontentinner" style="padding-right:0;">
                        <div class="row-fluid">
                            <div class="span8" style="width:69%;float:left;">
                                <div class="clearfix">
                                    <h4 class="widgettitle">Sales</h4>
                                </div>
                                <table class="table table-bordered responsive" id="global_tbl">
                                    <colgroup>
                                        <col class="con0" style="width:5%;"/>
                                        <col class="con1" style="width:25%;"/>
                                        <col class="con0" style="width:25%;"/>
                                        <col class="con1" style="width:25%;"/>
                                        <col class="con1" style="width:15%;"/>
                                        <col class="con0" style="width:5%;"/>
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th class="head0">Image</th>
                                            <th class="head1">Name</th>
                                            <th class="head0">Primary Type</th>
                                            <th class="head1 center">Last Message</th>
                                            <th class="head0 center">Last Order Date</th>
                                            <th class="head1 center">Variance</th>
                                            <th class="head1 center">Reminder</th>
                                            <th class="head0" style="text-align:center;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div> <!--end span8-->
                           <!--  <div class="span4">
                                <div class="clearfix">
                                    <h4 class="widgettitle">Client Details</h4>
                                </div>
                                <div class="widgetcontent">
                                    <div class="widgetbox" id="fdetail" style="">Nothing To Display!
                                    </div>
                                </div>
                            </div> --> <!--end span4-->

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

