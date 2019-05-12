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
    </head>
    <!--head-->

    <body>

        <div class="mainwrapper">
            <?php require_once('require/top.php'); ?>
            <?php require_once('require/left_nav.php'); ?>
            <div class="rightpanel">
                <ul class="breadcrumbs">
                    <li><a href="help.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
                    <li>Receiving <span class="separator"></span> Distribution</li>
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
                        <h5>Displays the items received and where they are to be distributed to</h5>
                        <h1>Receiving Distribution</h1>
                    </div>                    
                </div>
                <!--pageheader-->
               <div class="maincontent">
                    <div class="maincontentinner">
                        <!--row-fluid-->
						<div class="row-fluid" id="receivedItems" style="margin: 6px;">
							  <div class="span7" style="width:60%">
								<div class="widgetbox">
									<h4 class="widgettitle">Received Items</h4>
									<div class="widgetcontent">
                                        <table id="items_table" class="table table-bordered table-infinite">
                                            <colgroup>
                                                <col class="con1"/>
                                                <col class="con0"/>
                                                <col class="con1"/>
                                                <col class="con0"/>
                                                <col class="con1"/>
                                                <col class="con0"/>
                                                <col class="con1"/>
                                                <col class="con0"/>
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th class="head0">Name</th>
                                                    <th class="head1">Pack Unit Type</th>
                                                    <th class="head0">Qty In Pack</th>
                                                    <th class="head1">Qty In Pack Unit Type</th>
                                                    <th class="head0">Pack Size</th>
                                                    <th class="head1">Tax</th>
                                                    <th class="head0">Price</th>
                                                    <th class="head1">Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $sql = "SELECT DISTINCT iiu.unit_type as pack_unittype, vpi.ordered_qty_in_pack, iiu2.unit_type 
                                                            as qty_unittype, vpi.ordered_pack_size, vpi.ordered_tax_percentage, vpi.ordered_price, 
                                                            vpi.ordered_quantity, vpi.vendor_purchases_items_id, lii.local_item_desc
                                                        FROM vendor_purchases_items vpi
                                                            LEFT JOIN inventory_item_unittype iiu ON iiu.id = vpi.ordered_pack_unittype
                                                            LEFT JOIN location_inventory_items lii ON lii.inv_item_id = vpi.inv_item_id
                                                            LEFT JOIN inventory_item_unittype iiu2 ON iiu2.id = vpi.ordered_qty_in_pack_unittype
                                                        WHERE vpi.buying_vendor_id = '" . $_SESSION['StorePointVendorID'] . "'";
                                                    $res = mysql_query($sql) or die(mysql_error());

                                                    while($row = mysql_fetch_array($res)){
                                                        ?>
                                                <tr onclick="loadDistribution(<?=$row['vendor_purchases_items_id']?>)" id="row_<?=$row['vendor_purchases_items_id']?>">
                                                    <td>
                                                        <?=$row['local_item_desc']?>
                                                    </td>
                                                    <td>
                                                        <?=$row['pack_unittype']?>
                                                    </td>
                                                    <td>
                                                        <?=$row['ordered_qty_in_pack']?>
                                                    </td>
                                                    <td>
                                                        <?=$row['qty_unittype']?>
                                                    </td>
                                                    <td>
                                                        <?=$row['ordered_pack_size']?>
                                                    </td>
                                                    <td>
                                                        <?=number_format($row['ordered_price'] * ($row['ordered_tax_percentage'] / 100), 2)?>
                                                    </td>
                                                    <td>
                                                        <?=number_format($row['ordered_price'], 2)?>
                                                    </td>
                                                    <td>
                                                        <?=round($row['ordered_quantity'], 2)?>
                                                    </td>
                                                </tr>
                                                        <?php
                                                    }
                                                ?>
                                                
                                            </tbody>
                                        </table>
									</div>
								</div>
							  </div>
							  
							  <div class="span5"style="width: 38.5%;"> <!-- 42.94% -->
								<div class="widgetbox"> 
									<h4 class="widgettitle itemnameD">Distribution</h4>
									<div class="widgetcontent" id="distribution">
                                        <table id="dist_table" class="table table-bordered table-infinite">
                                            <colgroup>
                                                <col class="con1">
                                                <col class="con0">
                                                <col class="con1">
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th class="head0">Should go to Storeroom</th>
                                                    <th class="head1">Put in Storeroom</th>
                                                    <th class="head0">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
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
    <script type="text/javascript">
            jQuery(document).ready(function ($) {

                jQuery('#items_table').dataTable({
                    "sPaginationType": "full_numbers",
                    "aaSorting": [[0, "asc"]],
                    "bJQuery": true,
                    "fnDrawCallback": function (oSettings) {
                        //  jQuery.uniform.update();
                    }
                });
            });
    </script>
</html>


