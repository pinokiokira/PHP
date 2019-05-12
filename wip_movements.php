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
            
            .span4 {
                float:left;
                width:28.5%!important;
                min-height:600px;
                margin-left:1.5%!important;
            }
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
            });    
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
                    <li> WIP Inventory </li>
                    <li><span class="separator"></span> Movements </li>
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
                    <div class="pageicon"><span class="iconfa-credit-card"></span></div>
                    <div class="pagetitle">
                        <h5>Browse through your locations and customers</h5>
                        <h1>Movements</h1>
                    </div>                    
                </div>
                
               <div class="maincontent">
                    <div class="maincontentinner">
                        <!--row-fluid-->
						<div class="row-fluid" id="inventoryTable" style="margin: 6px;">
							  <div class="span7" style="width:50%">
								<div class="widgetbox">
									<h4 class="widgettitle">ITEMS</h4>
									<div class="widgetcontent">

									</div>
								</div>
							  </div>
							  
							  <div class="span5"style="width: 48.5%;"> <!-- 42.94% -->
								<div class="widgetbox"> 
									<h4 class="widgettitle itemnameD">INCOMING</h4>
									<div class="widgetcontent" id="details_of_vendor">
									</div>
								</div>
							  </div>
							 
						</div>
							
						  <div style="clear: both;"></div>
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
      
    </body>
</html>


