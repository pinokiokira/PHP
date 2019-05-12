<?php
ob_start("ob_gzhandler");
include_once 'includes/session.php';
include_once("config/accessConfig.php");
include_once 'includes/functions.php';

include  $_SERVER['DOCUMENT_ROOT']."/includes/db_class.php";
$rp = new db_class();

function aste() {
    $rp = new db_class();
    echo "<a style='color:red;text-decoration:none'>*</a>";
}

$setupHead = "active";
$financeDropDown = "display: block;";
$setupDropDown = "display: block;";
$set_back_invventoryDropDown  = "display: block;";
$financeHead = "active";
$financeMenu7 = "active";

$query1 = "SELECT * FROM location_inventory_storerooms WHERE location_id = " . $_SESSION['loc'] . " ORDER BY priority ASC";
$result1 = $rp->rp_query($query1) or die(mysql_error());
$query2 = "SELECT DISTINCT storeroom_id from location_inventory_counts where location_id = " . $_SESSION['loc'];
$result2 = $rp->rp_query($query2) or die(mysql_error());
$nodelete = array();
while ($row2 = $rp->rp_fetch_array($result2)) {
    $nodelete[$row2['storeroom_id']] = 1;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>SoftPoint | VendorPanel</title>
        <link rel="stylesheet" href="css/style.default.css" type="text/css" />
        <link rel="stylesheet" href="css/responsive-tables.css">
        <style>
			.greybg{
				background-color:#808080;				
				}
			.left{
				text-align:left;
			}
            .sorting_asc {
                background: url('images/sort_asc.png') no-repeat center right !important;
                background-color: #333333 !important;
            }
            .sorting_desc {
                background: url('images/sort_desc.png') no-repeat center right
                    !important;
                background-color: #333333 !important;
            }
        </style>
        <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery.uniform.min.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="js/jquery.cookie.js"></script>
        <script type="text/javascript" src="js/modernizr.min.js"></script>
        <script type="text/javascript" src="js/responsive-tables.js"></script>
		<script type="text/javascript" src="js/jquery.alerts.js"></script>
        <script type="text/javascript" src="js/custom.js"></script>
		<script type="text/javascript" src="js/custom_webz.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                // dynamic table
                jQuery('#dyntable').dataTable({
                    "sPaginationType": "full_numbers",
                    "aaSorting": [[ 0, "asc" ]],
                    "bJQuery": true
                });
				jQuery('.edit').live('click',function(){
					var s = jQuery(this).attr('rel');
					jQuery('.greybg').removeClass('greybg');
					jQuery('#'+s).addClass('greybg');
					});
                jQuery('#storeroom_form').on('submit<?php echo '.' . time(); ?>', function(e){
                    if(jQuery('#short_name').val() == ''){
                        //alert('Please enter a Store name');
						jAlert('Enter Short Name!', 'Alert');
                        return false;
					
                    }else if(allnumeric(jQuery("#priority").val())==false && jQuery("#priority").val()!=""){
						jAlert('Enter Numerical value in Priority Field!','Alert');
						return false;
					}
					else {
						return true;
					
						/*if(jQuery("#terminal_id").val()==""){
							alert('added');
							return true;							
						}else{
						alert('edited');
							return true;
						}		*/			
                        
                    }
                }).on('datafill', function(e){
					
                    if ( !e.sourceData ) return;
                    var data = e.sourceData;
                    if ( 'short_name' in data ){ jQuery('#short_name').val(data.short_name); 	jQuery("#reset").hide(); } else { jQuery('#short_name').val(''); jQuery("#reset").show(); }
                    if ( 'des' in data ){ jQuery('#des').val(data.des); } else { jQuery('#des').val(''); }
                    if ( 'priority' in data ){ jQuery('#priority').val(data.priority); } else { jQuery('#priority').val(''); }
			
                    if ( 'located' in data ){ jQuery('#located').val(data.located); } else { jQuery('#located').val(''); }
                    if ( 'access' in data ){ jQuery('#access').val(data.access); } else { jQuery('#access').val(''); }

                    if ( 'line' in data ){ jQuery('#line').val(data.line); } else { jQuery('#line').val(''); }
			
			
                    if ( 'terminal_id' in data ){ jQuery('#terminal_id').val(data.terminal_id); } else { jQuery('#terminal_id').val(''); }
                });

                var current_fill = null;
                jQuery(document).on('click', '[data-toggle="datafill"]', function(e){
					
                    e.preventDefault();
                    var $this = jQuery(this), data = $this.data(), $target = jQuery('#storeroom_form');
                    if ( 'target' in data && jQuery(data.target).length ){
                        $target = jQuery(data.target);
                    }
                    if ($target.length){
                        current_fill = data;
                        var event = jQuery.Event('datafill', {
                            sourceData: data
                        });
                        jQuery('#storeroom_form').trigger(event);
                    }
            
                });

                jQuery('#storeroom_form input[type="reset"]').click(function(e){
                    e.preventDefault();
                    if ( current_fill ){
                        var event = jQuery.Event('datafill', {
                            sourceData: current_fill
                        });
                        jQuery('#storeroom_form').trigger(event);
                    }
                });
            });
        </script>
        
		
        <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
    </head>


    <body>
		<?php
		if($_REQUEST['msg']!=""){
		 if($_REQUEST['msg']=='edit'){
			echo "<script> jAlert('Storeroom Updated Successfully','Storerooms Edit'); </script>";
			}else {
			echo "<script> jAlert('Storeroom Inserted Successfully','Storerooms Add'); </script>";
			} 
		}
			?>

        <div class="mainwrapper">

            <?php include_once 'require/top.php'; ?>

            <div class="leftpanel">

                <?php include_once 'require/left_nav.php'; ?>

            </div>
            <!-- leftpanel -->

            <div class="rightpanel">

                <ul class="breadcrumbs">
                    <li><a href="messages.php"><i class="iconfa-home"></i> </a> <span
                            class="separator"></span></li>
                    <li>Setup</li>
                    <li><span class="separator"></span></li>
                    <li>Inventory</li>
					<li><span class="separator"></span></li>
                    <li>Storerooms</li>
                    <li class="right"><a href="" data-toggle="dropdown"
                                         class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
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
                    <div style="float: right; margin-top: 11px; margin-right:6px;">
                        <a data-toggle="datafill" data-target="#storeroom_form" href="newterminal.php"><button
                                class="btn btn-success btn-large">Add</button> </a>
                    </div>
                    <div class="pageicon">
                        <span class="iconfa-cog"></span>
                    </div>
                    <div class="pagetitle">
                        <h5>The storeroom setup module allows you to create physical storerooms and areas where you will manage and maintain items.</h5>
                        <h1>Storerooms</h1>

                    </div>
                </div>
                <!--pageheader-->

                <div class="maincontent">
                    <div class="maincontentinner">
                        <div class="row-fluid">
                            <div class="span8" style="width: 67.2%;">
                            <h4 class="widgettitle">Storerooms</h4>
                                <div class="table-holder widgetcontent">
                                    <table id="dyntable" class="table table-bordered">
                                        <colgroup>
                                            <col class="con0 center" />
                                            <col class="con1" />
                                            <col class="con0" />
                                            <col class="con1" />
                                            <col class="con0" />
                                            <col class="con1" />
                                            <col class="con0" />
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th class="head0 center" style="width: 12%">Priority</th>
                                                <th class="head1 left">Short Name</th>
                                                <th class="head0 left">Description</th>
                                                <th class="head1 left">Located</th>
                                                <th class="head0 left">Access</th>
                                                <th class="head1 left">Line</th>
                                                <th class="head0 center nosort" style="width: 4%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = $rp->rp_fetch_array($result1)) {
                                                ?>
                                                 <tr rel="<?php echo $row['storeroom_id']; ?>" style="cursor:pointer;"  id="<?php echo $row['storeroom_id']; ?>" class="edit gradeX" data-toggle="datafill"
                                                                data-target="#storeroom_form"
                                                                data-short_name="<?php echo $row['stroom_id']; ?>"
                                                                data-des="<?php echo $row['description']; ?>"
                                                                data-priority="<?php echo $row['priority']; ?>"
                                                                data-located="<?php echo $row['located']; ?>"
                                                                data-access="<?php echo $row['access']; ?>"
                                                                data-line="<?php echo $row['line']; ?>"
                                                                data-terminal_id="<?php echo $row['storeroom_id']; ?>"
                                                                href="insertStoreroom.php?id=<?php echo $row["storeroom_id"]; ?>"  >
                                                    <td class="center"><?php echo $row['priority']; ?></td>
                                                    <td><?php echo $row['stroom_id']; ?></td>
                                                    <td><?php echo ucfirst($row['description']); ?></td>
                                                    <td><?= $row['located']; ?></td>
                                                    <td><?= $row['access']; ?></td>
                                                    <td><?=ucfirst($row['line']); ?></td>
                                                  
                                                    <td style="text-align: center;"><span style="margin-right: 10px; vertical-align: middle; font-size: 14px;">                                                        

                                                        <img alt="edit"  src="images/Edit - 16.png">
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="span4">
                                <div class="widgetbox">
                                    <div class="headtitle">
                                        <h4 class="widgettitle">Add/Edit Storerooms</h4>
                                    </div>

                                    <div class="widgetcontent">

                                        <form name="storeroom_form" id="storeroom_form" action="setup_finance_storerooms_insert.php" method="post">

                                            <label for="short_name" class="control-label">Short Name: <?php aste(); ?></label>
                                            <input name="short_name" id="short_name" value="" type="text" />

                                            <label for="path" class="control-label">Description: </label>
                                            <input type="text" name="description" id="des" value=""  />

                                            <label for="cashier_bank" class="control-label">Priority: </label>
                                            <input type="text" name="priority" id="priority" value=""  />

                                            <label for="cashier_bank" class="control-label">Located: </label>
                                            <input type="text" name="located" id="located" value=""  />


                                            <label for="cashier_bank" class="control-label">Access:</label>
                                            <input type="text" name="access" id="access" value=""  />

                                            <label for="cashier_bank" class="control-label">Line:</label>
                                            <select name="line" id="line" class="addplanning" style="width:220px;">
                                                <option value="">- - - Select Line - - -</option>
                                                <option value="no" >No</option>
                                                <option value="yes" >Yes</option>
                                            </select>

                                            <p>
                                                <input type="submit" style="height:38px;" name="submit01" class="btn btn-primary" value="Submit"/>
                                                
                                                <button type="reset"  class="btn btn-primary" style="height:38px;"  name="clear" id="reset">Reset</button>
                                            </p>
                                            <input type="hidden" id="terminal_id" name="id" value="<?php echo $_GET['id'] ?>">
											
											<input id="step_child" name="step" type="hidden" value="<?php echo $_GET['step'];?>" />		

                                        </form>
                                    </div>
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

    </body>
</html>