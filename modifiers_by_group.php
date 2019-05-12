<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'includes/session.php';
include_once("config/accessConfig.php");

include  $_SERVER['DOCUMENT_ROOT']."/includes/db_class.php";
$rp = new db_class();

$setupHead      = "active";
$setupDropDown1 = "display: block;";
$setupDropDown  = "display: block;";/*
$setupResMenu14  = "active";*/
$setupMenu3     = "active";
$setupResMenu1052 = "active";

if(isset($_POST) && $_POST['action']=='delete' && $_REQUEST['menugrp_id']>0){
	$del = "DELETE FROM location_menu_group WHERE id = '".$_REQUEST['menugrp_id']."'";
	$res = $rp->rp_query($del);
	if($res){
		echo 'yes';
	}
	exit();
}

$menu=$_REQUEST['menu'];
$menu_id=$_REQUEST['menu_id'];

$sql = "SELECT id, menu, image, description, TIME_FORMAT( starttime,'%k:%i') starttime , TIME_FORMAT( endtime,'%k:%i') endtime FROM location_menus where location_ID = " . $_SESSION['loc'] . " AND (`type` is null OR `type` = 'POS' ) ORDER BY menu ASC";
$query = $rp->rp_query($sql) or die(mysql_error());

if($rp->rp_affected_rows($query)!=0 && $menu=='')
{
	$rowmenu=$rp->rp_fetch_array($query);
	$menu=$rowmenu['id'];
}
$querysel = "SELECT * FROM location_menu_group WHERE location_id=" . $_SESSION['loc'] . " ORDER BY menu_group ASC";
$resultgroup = $rp->rp_query($querysel);



$sqlpayroll = "SELECT currency_symbol FROM locations WHERE id=".$_SESSION["loc"];
$resultpayroll = $rp->rp_query($sqlpayroll);
$rowperiod = $rp->rp_fetch_array($resultpayroll);

$currency = $rowperiod["currency_symbol"];
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | BusinessPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/responsive-tables.css">
<style>
.line3 { background:#808080 !important; color:#FFFFFF !important ;} 
.progress { position:relative; width:100%; border: 1px solid #ddd; padding: 1px; border-radius: 3px;  display:none; margin-top:10px; }
.bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
.percent { position:absolute; display:inline-block; top:3px; left:48%; }
#dyntable2_filter input
{
    width: 70px
}
.paginate_button, .dataTables_paginate .next, .dataTables_paginate .last {
padding: 5px 8px !important;
}
.dataTables_paginate .first, .dataTables_paginate .previous, .dataTables_paginate .paginate_active, .dataTables_paginate .paginate_button, .dataTables_paginate .next, .dataTables_paginate .last{
 padding: 5px 8px !important;   
}
.modal-footer {
    -webkit-border-radius: 0 !important;
    -moz-border-radius: 0 !important;
     border-radius: 0 !important;
    
}   
</style>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="prettify/prettify.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<!-- <script type="text/javascript" src="js/jquery.dataTables.min.js"></script> -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.jgrowl.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/datetime-picker.min.js"></script>
<script type="text/javascript" src="js/elements.js"></script>
<script type="text/javascript" src="js/tablednd.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/token.js"></script>
<style>
    .s_panel{
        border-bottom: 1px solid lightgray;
    }
.dataTables_scrollBody{ position:inherit !important;}	
	
</style>
<script type="text/javascript">
    function clearForm(){
		jQuery('#ResetButton').show();
        jQuery("#menuform")[0].reset();
		clearField();
		jQuery('.line3').removeClass('line3');
    }
	
	
	
	function SubmitItemBFLeaving(returnUrl){
		jQuery("#hidItemIds").val(jQuery("#hidItemIds").val().slice(0,-2));
		jQuery.ajax({
				type: "POST",
				url: "ajax_add_menu_article_drop.php",
				data: {itemid:0, menugroupid: jQuery("#hidMenuGroupID").val(),menu:jQuery("#hidMenu").val(),itemIds:jQuery("#hidItemIds").val()},
				success: function (data) {
					
						jQuery("#btn_submit").addClass("btn-active");
						jAlert('Items are added to menu group successfully!', 'Alert', function (r){
							if(r){										
								window.location.href = returnUrl;
							}
						});
					}

			   });
	}
	
    jQuery(document).ready(function(){
        
        jQuery(".menuitems").on("click",function(){
            jQuery(".line3").removeClass("line3");
            jQuery(this).addClass("line3");
        })
        
        <?php if ($_GET["insert"]=="yes"){?>
                jAlert("Item was updated successfully!");
        <?php }
		
		
		?>
		
		
		jQuery("#btn_submit").click(function(){
		
			if(jQuery(this).hasClass("btn-active")){
			}else{
				//jQuery("#hidItemIds").val(jQuery("#hidItemIds").val().slice(0,-2));
				jQuery.alerts.okButton = 'OK';
				jQuery.alerts.cancelButton = 'Cancel'; 
				jConfirm("Would you like to apply the changes now?", "Confirm Dialog", function(r){
					if(r){

						changePriority_sort();

						if(jQuery("#hidItemIds").val()==''){
							location.reload();
						}
						jQuery.ajax({						
							type: "POST",
							url: "ajax_add.php",
							data: {itemid:0, menugroupid: jQuery("#hidMenuGroupID").val(),menu:jQuery("#hidMenu").val(),itemIds:jQuery("#hidItemIds").val()},
							success: function (data) {
								jAlert('Added successfully!', 'Alert', function () {
								location.reload();		
								});		
									
							}
						}); // end jQuery.ajax
					}else{
						return false;
					}
				});
			
				
				}
		});
		  
        jQuery(".droppable").droppable({
                over: function(event, ui) {
                    jQuery(this).css("background","gray");
                    jQuery(this).find(".accordion-toggle").trigger("click");
                 },
                
				drop: function (event, ui) {
                   
				   
                   var $this = jQuery(this);
                   var menugroupid = $this.data("id");
                   var menu = $this.data("menu");
				   
				   var itemIds = "";
				   jQuery(".menuitems_checkBox").each(function(){
				   		if(this.checked){
							if(itemIds.toString().indexOf(jQuery(this).attr('rel'))==-1){
								itemIds = itemIds + jQuery(this).attr('rel')+',';
							}
						}
				   });
				   if(itemIds!=''){
				   		if(itemIds.toString().indexOf(ui.helper.data("id"))==-1){
				   			itemIds = itemIds + ui.helper.data("id");
						}else{
							itemIds = itemIds.slice(0,-1);		
						}
				   }
				   
					     
				   console.log("Menu Group: " + menugroupid);
				   console.log("Menu: " + menu);
				   console.log("Item Ids: " + itemIds);
				  
				   jConfirm("Are you sure you want to add these Article Modifier Defaults to the Modifiers Groups?","Confirm Dialog",function(r){
                  // if (ui.helper.hasClass("menuitem")){
				    if (r){
				   		var t = jQuery('#menu_table_'+menugroupid).DataTable();
						var strPrio = jQuery("#priority_"+menu).val();
						
						if(itemIds!=''){
							var itemIdss  = itemIds.split(','); 
							jQuery.each(itemIdss,function(index,value){
								if(value!=ui.helper.data("id")){
									var obj = jQuery("#dyntable2 #"+value+" .menuitem");
									console.log(obj);
									var editbuttons = '&nbsp;&nbsp;<a data-toggle="modal" data-target="#edit_item_modal" href="ajax-edit-menu-item.php?itemid='+obj.data("id")+'&menu='+ menu +'&group='+ menugroupid +'" style="background:none;!important;  line-height: 10px !important;margin-top: 5px !important;    padding: 1px !important; "><span class="a"><img src="images/edit.png"></span></a>';
									editbuttons = editbuttons + '&nbsp;<span class="deletemenu1" data-menugroupid="'+menugroupid+'" data-id="'+ obj.data("id") +'" style="cursor:pointer!important;"><img src="images/Delete.png" class="removeme"></span>';
									
									t.row.add( [
										''+ obj.data("priority") + '',
										''+ obj.data("modifier") + '',
										''+ obj.data("type") + '',
										''+ obj.data("article_type") + '',
										''+ obj.data("price") + '',
										'' + editbuttons + ''
									] ).draw( false );
									strPrio++;
									jQuery("#dyntable2 #"+value+" .menuitems_checkBox").trigger('click');
									jQuery("#hidItemIds").val(obj.data("id")+'|'+ menugroupid + "," + jQuery("#hidItemIds").val());
								}
							});
						}
						var editbuttons = '<a data-toggle="modal" data-target="#edit_item_modal" href="ajax-edit-menu-item.php?itemid='+ui.helper.data("id")+'&menu='+ menu +'&group='+ menugroupid +'" style="background:none;!important;  line-height: 10px !important;margin-top: 5px !important;    padding: 1px !important; "><span class="a"><img src="images/edit.png"></span></a>';
                            editbuttons = editbuttons + '<span class="deletemenu1" data-menugroupid="'+menugroupid+'" data-id="'+ ui.helper.data("id") +'" style="cursor:pointer!important;"><img src="images/Delete.png"></span>';
						
						t.row.add( [
							 
							''+ ui.helper.data("priority") + '',
							''+ ui.helper.data("modifier") + '',
							''+ ui.helper.data("type") + '',
							''+ ui.helper.data("article_type") + '', 
							''+ ui.helper.data("price") + '',
							'' + editbuttons + ''
						] ).draw( false );
						
						jQuery("#menu_table_"+menugroupid+" tr").each(function(){							
							jQuery(this).find('td:nth-child(8)').css('text-align','right');
							//jQuery(this + ' td:nth-child(2)').css('color','red');
						});
						strPrio++;
						
				   		jQuery("#hidItemIds").val(ui.helper.data("id")+'|'+ menugroupid + "," + jQuery("#hidItemIds").val());
						jQuery("#hidMenuGroupID").val(menugroupid);
						jQuery("#hidMenu").val(menu);
						jQuery("#btn_submit").removeClass("btn-active");
						jQuery("#btn_submit").addClass("btn-success");
						jQuery("#priority_"+menu).val(eval(jQuery("#priority_"+menu).val())+1); 
						 jQuery("#dyntable2 #"+ui.helper.data("id")+" .menuitems_checkBox").trigger('click');
						 console.log("Item ID:" + ui.helper.data("id"));
						 
						  console.log("hidItemIds ID:" + jQuery("#hidItemIds").val().slice(0,-2));
						  jQuery("#priority_"+menu).val(strPrio);
				   	jQuery(".draggable").draggable({
                            tolerance: 'fit',
                            helper: "clone"
                        });
				   t.destroy();
				   }
				});
				
				
                   
                }
				
				
            }
			
			
            
        );
            jQuery(".draggable").draggable({
                tolerance: 'fit',
                helper: "clone"
            }
            
        );
        // dynamic table
        jQuery('#dyntable').dataTable({
            "sPaginationType": "full_numbers",
         //   "aaSortingFixed": [[0,'asc']],
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });
        jQuery('#dyntable2').dataTable({
            "sPaginationType": "full_numbers",
            "info":     false,
            "bPaginate": false,
            "sScrollY": "500px",
			
           "bFilter": false, "bInfo": false,
           "aaSorting": [],
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });
        jQuery("#dyntable2_length").css("display","none");  
	    jQuery("#dyntable2_filter input").css("width","110px"); 
	    jQuery("#dyntable2_filter input").css("margin-bottom","5px");

	    jQuery('.dyntable3').dataTable({
            "sPaginationType": "full_numbers",
            "info":     false,
            "bPaginate": false,
        //    "bDestroy": true,
            "bFilter": false, "bInfo": false,
         //   "aaSortingFixed": [[0,'asc']],
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });
    
          jQuery('#ccstarttime').timepicker();
        jQuery('#ccendtime').timepicker();   
		
	jQuery("#menuselect" ).change(function() {
		window.location="<?=$_SERVER['PHP_SELF']?>?menu="+jQuery(this).val();
	});
	
	
	
	
	 jQuery('#edit_modifier_modal').on('hidden', function(e){
			jQuery(this).removeData('modal');
			jQuery('.modal-body', this).empty();
		});
                
         jQuery('#edit_item_modal').on('hidden', function(e){
			jQuery(this).removeData('modal');
			jQuery('.modal-body', this).empty();
		});       
	
	
    });
	

function validacMenu() {
    
        if (document.forms.menuform.item_group.value == "") {
         	jAlert('Please Select a Menu Group!', 'Alert Dialog', function(){
            });
			
        } else if (document.forms.menuform.ccitem.value == "") {
           jAlert('please insert Menu Item!', 'Alert Dialog', function(){
            });
        } else if (document.forms.menuform.item_priority.value == "") {
            jAlert('please insert Item Priority!', 'Alert Dialog', function(){
            });
			jQuery('#item_priority').removeAttr("disabled");
        }else if (document.forms.menuform.ccplu.value == "") {
           jAlert('This item requires a PLU!', 'Alert Dialog', function(){
            });
			jQuery('#ccplu').removeAttr("disabled");
        } else if (document.forms.menuform.ccpriority.value == "") {
          jAlert('Please insert Article Priority!', 'Alert Dialog', function(){
            });
			jQuery('#ccpriority').removeAttr("disabled");
        } else if (document.forms.menuform.ccprice.value == "") {
          	jAlert('Please insert Article Price!', 'Alert Dialog', function(){
            });
			jQuery('#ccprice').removeAttr("disabled");
        } else if (document.forms.menuform.cctaxable.value == "") {
           jAlert('Please select Taxable!', 'Alert Dialog', function(){
            });
			jQuery('#cctaxable').removeAttr("disabled");
        } /*else if (document.forms.menuform.ccmax_quantity.value == "") {
           jAlert('Please insert Maximum Quantity!', 'Alert Dialog', function(){
            });
			jQuery('#ccmax_quantity').removeAttr("disabled");
        } else if (document.forms.menuform.cctogo.value == "") {
           jAlert('Please select Togo!', 'Alert Dialog', function(){
            });
			jQuery('#cctogo').removeAttr("disabled");
        } else if (document.forms.menuform.ccdelivery.value == "") {
          	jAlert('Please select Delivery!', 'Alert Dialog', function(){
            });
			jQuery('#ccdelivery').removeAttr("disabled");
        } else if (document.forms.menuform.ccrequire_temperature.value == "") {
           jAlert('Please select Require Temperature!', 'Alert Dialog', function(){
            });
			jQuery('#ccrequire_temperature').removeAttr("disabled");
        } else if (document.forms.menuform.ccdrink.value == "") {
           jAlert('Please select Drink!', 'Alert Dialog', function(){
            });
			jQuery('#ccdrink').removeAttr("disabled");
        } else if (document.forms.menuform.ccglass.value == "") {
          jAlert('Please select Glass!', 'Alert Dialog', function(){
            });
			jQuery('#ccglass').removeAttr("disabled");
		} else if (document.forms.menuform.ccglass.value == "yes" && document.forms.menuform.ccglass_price.value == " ") {
          jAlert('Please insert Glass Price!', 'Alert Dialog', function(){
            });
			jQuery('#ccglass_price').removeAttr("disabled");
		} else if (document.forms.menuform.ccglass.value == "yes" && document.forms.menuform.ccglass_price2.value == " ") {
          jAlert('Please insert 2nd Glass Price!', 'Alert Dialog', function(){
            });
			jQuery('#ccglass_price2').removeAttr("disabled");
        } else if (document.forms.menuform.ccdivide.value == "") {
           jAlert('Please select Divide!', 'Alert Dialog', function(){
            });
			jQuery('#ccdivide').removeAttr("disabled");
        } else if (document.forms.menuform.ccmax_divide.value == "" && document.forms.menuform.ccdivide.value == "yes") {
            jAlert('Please insert Max Divide!', 'Alert Dialog', function(){
            });
			jQuery('#ccmax_divide').removeAttr("disabled");
        } else if (document.forms.menuform.ccfire_order.value == "") {
           jAlert('Please select Fire Order!', 'Alert Dialog', function(){
            });
			jQuery('#ccfire_order').removeAttr("disabled");
        } else if (document.forms.menuform.ccsides.value == "") {
           jAlert('Please select Sides!', 'Alert Dialog', function(){
            });
			jQuery('#ccsides').removeAttr("disabled");
        }  else if (document.forms.menuform.ccrefills.value == "") {
            jAlert('Please select Refills!', 'Alert Dialog', function(){
            });
			jQuery('#ccrefills').removeAttr("disabled");
        } */
        else if(jQuery("#promotion_type").val()=="Fixed Amount" && jQuery("#promotion_amount").val()=="" && jQuery("#promotion").val()=="Yes"){
            jAlert('Please enter promotion amount!', 'Alert Dialog', function(){
            });
        } else if(jQuery("#promotion_type").val()=="Percentage" && jQuery("#promotion_percentage").val()=="" && jQuery("#promotion").val()=="Yes"){
            jAlert('Please enter promotion percentage!', 'Alert Dialog', function(){
            });
        } else if(jQuery("#promotion_type").val()=="Percentage" && jQuery("#percentage_round").val()=="" && jQuery("#promotion").val()=="Yes"){
            jAlert('Please enter promotion percentage round!', 'Alert Dialog', function(){
            });
        } else if(jQuery("#promotion_type").val()=="Percentage" && jQuery("#percentage_round_to").val()=="" && jQuery("#promotion").val()=="Yes"){
            jAlert('Please enter promotion percentage round to!', 'Alert Dialog', function(){
            });
        } else if(jQuery("#promotion").val()=="Yes" && jQuery("#promotion_req_qty").val()==''){
            //jAlert('Promotion Required Quantity has to be greater than zero!', 'Alert Dialog', function(){
			jAlert('Please enter promotion required quantity!', 'Alert Dialog', function(){
            });
		} else if(jQuery("#promotion").val()=="Yes" && jQuery("#promotion_req_qty").val()==0){
            jAlert('Promotion Required Quantity has to be greater than zero!', 'Alert Dialog', function(){			
            });
        }else if(jQuery("#promotion").val()=="Yes" && jQuery("#promotion_continued").val()==""){
            jAlert('Please select promotion continued!', 'Alert Dialog', function(){
            });
        }else if(jQuery("#promotion_continued").val()=="Yes" && (jQuery("#promotion_dow").val()=="" || jQuery("#promotion_dow").val()==null)){
            jAlert('Please select promotion days of week!', 'Alert Dialog', function(){
            });
        } else {
            
            jQuery('Input,select,Textarea').removeAttr("disabled");
			
			jQuery.ajax({
				url:'insertmenuarticle_rest.php',
				type:'POST',
				data:jQuery("#menuform").serialize(),
				success:function(data){
					var menu_id = jQuery("#menuform #menu_id").val();
					jQuery("#delete_items_"+menu_id).html(data);
					jQuery("#menuform")[0].reset();
					jQuery("#edit_item_modal").modal("hide");
					jAlert("Item Updated Successfully!","Alert Dialog");
					return false;
					
				}
			});
			
			//return true;
         }
        return false;
    }

	
    function SearchItems(){
	
        jQuery.ajax({
                    type: "POST",
                    url: "ajax-article-modifier-default-search.php",
                    data: { menu:'<?php echo $menu;?>', articletype: jQuery("#articletype").val(), mtype: jQuery("#mtype").val(),desc:jQuery("#search_n").val() },
                    async: false,
                    success: function(data){
                        
                        jQuery("#dyntable2").html(data);
                        jQuery(".menuitems_checkBox").uniform();
                        jQuery(".draggable").draggable({
                            tolerance: 'fit',
                            helper: "clone"
                        });
                    }     
             })       
    
	}
	
	/*window.onhashchange = function() {
		jAlert("Back Button Pressed");
		return false;
	}*/
	jQuery('.leftmenu a').live('click',function(e){		
		var ths = jQuery(this);
		var href = jQuery(ths).attr('href');
		if(href!='' && href!='javascript:void(0)' && href!='#'){
			if(jQuery("#btn_submit").hasClass("btn-active")){			
							
			}else{
				e.preventDefault();
				jQuery.alerts.okButton = 'Yes';
				jQuery.alerts.cancelButton = 'No'; 
				jConfirm("Would you like to apply the changes before leaving the page?","Confirm Dialog",function(r){
					if(r){
						var href = jQuery(ths).attr('href');
						SubmitItemBFLeaving(href);					
					}else{
						jQuery("#btn_submit").addClass("btn-active")
						var href = jQuery(ths).attr('href');
						window.location.href = href;
					}
				});
			}	
		}	
	});
	jQuery('.header a').live('click',function(e){		
		var ths = jQuery(this);
		var href = jQuery(ths).attr('href');
		if(href!='' && href!='javascript:void(0)' && href!='#'){
			if(jQuery("#btn_submit").hasClass("btn-active")){			
							
			}else{
				e.preventDefault();
				jQuery.alerts.okButton = 'Yes';
				jQuery.alerts.cancelButton = 'No'; 
				jConfirm("Would you like to apply the changes before leaving the page?","Confirm Dialog",function(r){
					if(r){
						var href = jQuery(ths).attr('href');
						SubmitItemBFLeaving(href);					
					}else{
						jQuery("#btn_submit").addClass("btn-active")
						var href = jQuery(ths).attr('href');
						window.location.href = href;
					}
				});
			}
		}		
	});
	
	/*jQuery(window).bind("beforeunload",function(event) {
		if(jQuery("#btn_submit").hasClass("btn-active")){
		
		}else{
			return "You have some unsaved changes";
		}
	});*/
</script>
</head>
<body >
<div class="mainwrapper">
  <div class="header">
    <?php include_once 'includes/header.php';?>
  </div>
  <div class="leftpanel">
    <div class="leftmenu">
      <?php include_once 'includes/left_menu.php';?>
    </div>
    <!--leftmenu-->
  </div>
  <!-- leftpanel -->
  <div class="rightpanel">
    <ul class="breadcrumbs">
      <li><a href="messages.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
      <li>Setup <span class="separator"></span> Restaurant <span class="separator"></span></li>
      <li>Modifier By Group </li>
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
      <!--
                        <div style="float:right;margin-top: 11px;">	
<a href="javascript:clearForm();" role="button"  ><button id="add" class="btn btn-success btn-large">Add</button></a>
</div>-->
      <div style="float:right;margin-top: 10px;">
        <button id="btn_submit" class="btn btn-active" style="height: 42px;">Submit</button>
        <input type="hidden" id="hidItemIds" name="hidItemIds" value="0">
        <input type="hidden" id="hidMenu" name="hidMenu" value="0">
        <input type="hidden" id="hidMenuGroupID" name="hidMenuGroupID" value="0">
        <select name="menuselect" style="width:180px; margin: 0px 0px 0px 5px; height: 42px; display:none;" id="menuselect">
          <?
if($rp->rp_affected_rows($query)!=0)
{
	$rp->rp_data_seek($query,0);
	while ($row_allworker = $rp->rp_fetch_array($query)) 
	{
				?>
          <option value="<?=$row_allworker['id']?>" <?  if($menu==$row_allworker['id']){?> selected <? }?> ><? echo $row_allworker['menu'];?></option>
          <?
	}
}
			  ?>
        </select>
      </div>
      <div class="pageicon"><span class="iconfa-cog"></span></div>
      <div class="pagetitle">
        <h5>ADD AND EDIT MODIFIERS BY GROUP</h5>
        <h1>Modifier By Group</h1>
      </div>
    </div>
    <!--pageheader-->
    <div class="maincontent">
      <div class="maincontentinner">
        <div class="widgetbox" style="width:30%;float:left;">
          <div class="headtitle">
            <h4 class="widgettitle">Article Modifier Defaults</h4>
          </div>
          <div class="widgetcontent" style="min-height:255px;">
            <div style="width:100%;background-color: whitesmoke;border: 1px solid #dddddd;position: relative;padding-bottom: 38px;">
              <input type="hidden" name="search_txt" value="">
              <span style="position: absolute;right: 10px;padding-top: 4px;">
              <input type="text" name="search_n" id="search_n" onChange="SearchItems();" style="margin-right:10px;width:100px"  class="input-short" placeholder="Search Items">
			  
			   <select name="mtype" id="mtype" style="width: 145px;" onChange="SearchItems();">
                    <option value=""> - - - Select Type - - - </option>
                    <?php 
						$getEnum = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'location_menu_article_modifiers' AND COLUMN_NAME = 'Type' AND table_schema = DATABASE()";
						$resEnum = $rp->rp_query($getEnum);
						$rowEnum = $rp->rp_fetch_array($resEnum);
						$enumList = explode(",", str_replace("'", "", substr($rowEnum['COLUMN_TYPE'], 5, (strlen($rowEnum['COLUMN_TYPE'])-6))));
						foreach($enumList as $value){
							$selected = '';
							if($value==$row['type']){
								$selected = 'selected="selected"';
							}
							echo "<option ".$selected." value=\"$value\">$value</option>";
						}		
					?> 
                </select>
			  
			  
			 <select name="articletype" id="articletype" style="width: 145px;" onChange="SearchItems();">
                    <option value=""> - - - Select Article Type - - - </option>
						<option value="Bar" <?php if($row['article_type'] == 'Bar') echo 'selected'; ?>>Bar</option>
						<option value="Beverage" <?php if($row['article_type'] == 'Beverage') echo 'selected'; ?>>Beverage</option>
						<option value="Beer" <?php if($row['article_type'] == 'Beer') echo 'selected'; ?>>Beer</option>
						<option value="Dessert" <?php if($row['article_type'] == 'Dessert') echo 'selected'; ?>>Dessert</option>
						<option value="Food" <?php if($row['article_type'] == 'Food') echo 'selected'; ?>>Food</option>
						<option value="Liquor" <?php if($row['article_type'] == 'Liquor') echo 'selected'; ?>>Liquor</option> 
						<option value="Retail" <?php if($row['article_type'] == 'Retail') echo 'selected'; ?>>Retail</option>
						<option value="Wine" <?php if($row['article_type'] == 'Wine') echo 'selected'; ?>>Wine</option>
						<option value="Other" <?php if($row['article_type'] == 'Other') echo 'selected'; ?>>Other</option>
                </select> 
			  
			  
		 
              <input type="hidden" name="pageSize" id="pageSize" value="">
              </span></div>
            <table id="dyntable2" class="table table-bordered responsive" style="width:100%;">
              <colgroup>
              <col class="con1" style="width:10%;"  />
              <col class="con0" style="width:40%;" />
              <col class="con1" style="width:25%;" />
              <col class="con0"  style="width:25%;"/>
              </colgroup>
              <thead>
                <tr>
                  <th class="head1"><input type="checkbox" class="checkbox menuitems_checkBox_top"  rel="<?php echo $lnrv["id"];?>"   style="margin-right:5px; margin-top:0px;" ></th>
                  <th class="head0 nosort">Modifier</th>
                  <th class="head1">Type</th>
                  <th class="head0">Article Type</th>
                </tr>
              </thead>
              <tbody>
                <?php 
			  				$art_where = "";

if($_REQUEST['article_type']!=''){
	$art_where = " AND lma.article_type = '".$_REQUEST['article_type']."'";
}
$limit = 500;
$search ="";
if(isset($_REQUEST['search_txt']) && trim($_REQUEST['search_txt']) == ''){
	$limit = 500;
}else if(isset($_REQUEST['search_txt']) && trim($_REQUEST['search_txt']) != ''){
	$searchtxt=  $rp->add_security($_GET['search_txt']);
	$search = " AND (
	 lma.type like '%{$searchtxt}%' OR
	 lma.modifier like '%{$searchtxt}%' OR
	 lma.description like '%{$searchtxt}%' OR
	 lma.togo like '%{$searchtxt}%' OR
	 lma.max_quantity like '%{$searchtxt}%' OR
	 lma.price like '%{$searchtxt}%')";
	$limit = 500;							 
}

$sql = "SELECT lma.id,lma.status,lma.image,lma.article_type,lma.type,lma.modifier,lma.description,lma.togo,lma.delivery,lma.max_quantity,lma.taxable,lma.price,lma.size_price,l.currency_symbol FROM location_menu_article_modifiers_default as lma LEFT JOIN locations as l ON l.id = lma.location_id WHERE lma.location_id=".$_SESSION['loc']." $search  $art_where ORDER BY modifier ASC LIMIT $limit";
			  
			
                                    $query = $rp->rp_query($sql) or die(mysql_error());
                        while ($lnrv = $rp->rp_fetch_array($query)) {
						if(empty($lnrv['priority'])){ $priority = "0";}else{$priority = $lnrv['priority'];}
                            ?>
                <tr id='<?php echo $lnrv["id"];?>' class="menuitems">
                  <td><div data-id='<?php echo $lnrv["id"];?>' data-itemname="<?php echo $lnrv['modifier']; ?>"
				  data-priority="<?php echo $priority; ?>"
				  data-modifier="<?php echo $lnrv['modifier']; ?>"
				  data-type="<?php echo $lnrv['type']; ?>"
				  data-price="<?php echo $currency.number_format($lnrv['price'], 2); ?>"
				  data-article_type="<?php echo $lnrv['article_type']; ?>" class="draggable menuitem">
                      <ul style="list-style:none;">
                        <li>
                          <input type="checkbox" class="checkbox menuitems_checkBox"  rel="<?php echo $lnrv["id"];?>"   style="margin-right:5px; margin-top:0px;" >
                        </li>
                        </li>
                      </ul>
                    </div></td>
                  <td><div data-id='<?php echo $lnrv["id"];?>' data-itemname="<?php echo $lnrv['modifier']; ?>"
				  data-priority="<?php echo $priority; ?>"
				  data-modifier="<?php echo $lnrv['modifier']; ?>"
				  data-type="<?php echo $lnrv['type']; ?>"
				  data-price="<?php echo $currency.number_format($lnrv['price'], 2); ?>"
				  data-article_type="<?php echo $lnrv['article_type']; ?>" class="draggable menuitem">
                      <ul style="list-style:none;">
                        <li> <?php echo $lnrv['modifier']; ?></li>
                        </li>
                      </ul>
                    </div></td>
                  <td ><div data-id='<?php echo $lnrv["id"];?>' data-itemname="<?php echo $lnrv['modifier']; ?>"
				  data-priority="<?php echo $priority; ?>"
				  data-modifier="<?php echo $lnrv['modifier']; ?>"
				  data-type="<?php echo $lnrv['type']; ?>"
				  data-price="<?php echo $currency.number_format($lnrv['price'], 2); ?>"
				  data-article_type="<?php echo $lnrv['article_type']; ?>" class="draggable menuitem">
                      <ul style="list-style:none;">
                        <li><?php echo $lnrv['type']; ?></li>
                        </li>
                      </ul>
                    </div></td>
                  <td ><div data-id='<?php echo $lnrv["id"];?>' data-itemname="<?php echo $lnrv['modifier']; ?>"
				  data-priority="<?php echo $priority; ?>"
				  data-modifier="<?php echo $lnrv['modifier']; ?>"
				  data-type="<?php echo $lnrv['type']; ?>"
				  data-price="<?php echo $currency.number_format($lnrv['price'], 2); ?>"
				  data-article_type="<?php echo $lnrv['article_type']; ?>" class="draggable menuitem">
                      <ul style="list-style:none;">
                        <li><?php echo $lnrv['article_type']; ?></li>
                        </li>
                      </ul>
                    </div></td>
                </tr>
                <?php }  ?>
              </tbody>
            </table>
          </div>
        </div>
        <div style="width:69%;float:right;">
          <?
               if ($menu!="" && $menu!=0){
			/*$newGroupId = implode("','",explode(',',$_REQUEST['newgroup_id']));			   
		  $query = "SELECT *
          FROM location_menu_group
          WHERE location_ID = '" . $_SESSION['loc'] . "' and (id in (SELECT DISTINCT (menu_group) FROM location_menu_items WHERE menu_id=$menu AND status<>'Inactive') OR id IN('".$newGroupId."') )
          ORDER BY priority ASC, menu_group ASC";
$qrgrv = $rp->rp_query($query) or die(mysql_error());*/

$search = " AND location_id = '".$_SESSION['loc']."'";
$query = "SELECT menu_article_modifier_group_id as id,description,status,modifier_group FROM location_menu_article_modifiers_groups  WHERE 1 $search ORDER BY description asc";
$qrgrv = $rp->rp_query($query) or die(mysql_error());

			   ?>
          <div class="row-fluid">
            <div class="span6" style="width:100%!important; ">
              <div style="background-color:#0866c6; width:100%!important; font-size:14px; color:#FFFFFF; padding:12px  0px 12px  0px">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                  <tr>
                    <td align="left" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <tr>
                          <td align="left" valign="top" width="95%" style="color:#FFFFFF; padding:0px 10px 0px 10px " >Modifiers Groups</td>
                          <td align="center" valign="top" width="5%" >&nbsp;</td>
                        </tr>
                      </table></td>
                  </tr>
                </table>
              </div>
              <div  id="accordion" class="widgetcontent">
                <?php while($lngrv = $rp->rp_fetch_array($qrgrv)){ 
                	$array_menus[] = $lngrv["id"];
                	//echo $lngrv["id"];
                              // $file = API.'images/'.$lngrv['image'];
                               
?>
                <div  class="s_panel droppable" id="ac_menugroup_<?php echo $lngrv["id"];?>" data-id="<?php echo $lngrv["id"];?>" data-menu="<?php echo $menu;?>" data-target="<?php echo $lngrv["id"];?>">
                  <h3>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="accordion-toggle" >
                      <tr id="del_<?php echo $lngrv["id"];?>">
                        <td align="left" width="95%" style="padding: 4px 0 0 4px !important; height:30px; vertical-align:middle;"><span>
                          <?=$lngrv['description']?>
                          </span></td>
                        <td style="padding: 4px 0 0 4px !important; height:30px; vertical-align:middle;"><!--<a data-toggle="modal" data-target="#edit_modifier_modal" href="" style="background:none;!important; "> </a>-->
                          <img src="images/edit.png" onClick="editGroup('<?php echo $lngrv["id"];?>','<?php echo $lngrv["modifier_group"];?>','<?php echo $lngrv["description"];?>','<?php echo $lngrv["status"];?>');"> &nbsp; <img src="images/Delete.png" onClick="deleteGroup('<?php echo $lngrv["id"];?>');"> </td>
                      </tr>
                    </table>
                  </h3>
                  <?php
                        /*                                        $query2 = "SELECT it.id,lp.printer_name,it.item_id,gu.menu_group,gu.id as menu_id,art.drink,art.fire_order,art.item,art.taxable,it.price,it.priority,art.image,art.description,art.id as artid,art.glass_price,lmam.id modifier,art.glass_price2,art.plu
                                                                            FROM location_menu_items it
                                                                            JOIN location_menu_articles art on art.id = it.item_id
                                                                            JOIN location_menu_group gu on gu.id = it.menu_group
                                                                            LEFT JOIN location_menu_article_modifiers lmam on lmam.item_id=it.item_id
                                                                            LEFT JOIN location_printers lp ON lp.id = art.printer_id
                                                                            WHERE  it.menu_id = '" . $menu . "' and it.menu_group = '" . $lngrv["id"] . "'  and art.location_ID = '" . $_SESSION['loc'] . "' AND it.status<>'Inactive'
                                                                            GROUP BY it.id
                                                                            ORDER BY it.priority ASC";//it.priority IS NOT NULL AND
                                                                
                                                                $qritrv = $rp->rp_query($query2) or die(mysql_error());*/
$gid = $lngrv['id'];																
//$query2 = "SELECT * FROM location_menu_article_modifiers_default  WHERE  location_menu_article_modifiers_groups_id IN($gid)";																	
       $query2 = "SELECT * FROM location_menu_article_modifiers_default  WHERE  FIND_IN_SET($gid,location_menu_article_modifiers_groups_id) ";	                                                         
                                                                $qritrv = $rp->rp_query($query2) or die(mysql_error());																
                                                                //if ($rp->rp_affected_rows($qritrv) != 0)
																//{
																
																?>
                  <div>
                    <table class="table table-bordered responsive dyntable3" rel = "<? echo $lngrv['id'];?>" id="menu_table_<? echo $lngrv['id'];?>">
                      <colgroup>
                      <col class="con0" style="align: center; width: 4%" />
                      <col class="con1" style="width:30% " />
                      <col class="con0" style="width:4% "/>
					  <col class="con1" style="width:16% "/>
                      <col class="con0" style="width:16% "/>
                      <col class="con1" style="width:20% " />
                      <col class="con0" style="width:10px;"/>
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0 center" onClick="activeOrder()">Priority</th>
                          <th class="head1" onClick="activeOrder()">Modifier</th>
						   <th class="head0" onClick="activeOrder()">I</th>
                          <th class="head1 center" onClick="activeOrder()">Type</th>
                          <th class="head0 center" onClick="activeOrder()">Article Type</th>
                          <th class="head1" style="text-align:right;" onClick="activeOrder()">Price</th>
                          <th class="head0 nosort" valign="middle" style="text-align:center;width:35px;" >Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php  if ($rp->rp_affected_rows($qritrv) != 0){
						 			while($result = $rp->rp_fetch_array($qritrv)){
						 ?>
	                        				<tr class="gradeX delete_items_<?php echo $result['id']?>" id="delete_items_<?php echo $result['id'];?>">
					                          <td class="center"><?php echo $result['priority'];?></td>
					                          <td><?php echo $result['modifier'];?></td>
											    <td class="center">
				 <?php if($result['included'] == 'yes'){ $checked = 'checked';}else{$checked = ''; }?><input id="chk_i_<?php echo $result['id']; ?>" <?php echo $checked; ?>  type="checkbox" name="checkmod_i"  class="p_chk" onClick="addInclude('<?=$result['id']?>','<?php //echo $checked; ?>');" data-id="<?=$result['id']?>" /></td>
				 
					                          <td class="center"><?php echo $result['type'];?></td>
					                          <td class="center"><?php echo $result['article_type'];?></td>
					                          <td style="text-align:right;"><?php echo $currency.number_format($result['price'], 2); ?></td>
					                          <td style="text-align:center;"><a data-toggle="modal" data-target="#edit_modifier_modal" href="ajax-edit-modifier-default.php?id=<?php echo $result["id"];?>"><img src="images/edit.png"></a> &nbsp;<img src="images/Delete.png" onClick="deleteGrpFromModifier('<?php echo $gid;?>','<?php echo $result['id'];?>','<?php echo $result['id'];?>');"></td>
				                        </tr>
                        <?php 
                    				}
								}
						 ?>
                      
                      </tbody>
                      
                    </table>
                    <input type="hidden" id="priority_<?php echo $menu;?>" value="<?php echo $lastPriority; ?>">
                    <script type="text/javascript">
					
	


					
function compareTimes(start,end){
        
        var starttime = '1/1/2013' + " " + start;
        var endtime = '1/1/2013';
        
        if (end >= '00:00' && end <= '04:30'){
            endtime='1/2/2013';
            
        } 
        endtime = endtime + " " + end;
        
        
        var startdatetime = new Date(starttime);
        var enddatetime = new Date(endtime);
        
        if (enddatetime < startdatetime) {
            return false;
        } 
     
        else{
           
            return true;
        }
       
    }
jQuery(document).ready(function() {

	table<? echo $lngrv['id'];?> = document.getElementById('menu_table_<? echo $lngrv['id'];?>');
	var tableDnD_<? echo $lngrv['id'];?> = new TableDnD();
	tableDnD_<? echo $lngrv['id'];?>.init(table<? echo $lngrv['id'];?>);

	// Redefine the onDrop so that we can display something
	tableDnD_<? echo $lngrv['id'];?>.onDrop = function(table<? echo $lngrv['id'];?>, row) {


    var rows = table<? echo $lngrv['id'];?>.tBodies[0].rows;
	console.log('rows-- '+rows);
    var debugStr = "";
    for (var i=0; i<rows.length; i++) {
		var iid = rows[i].id;
		if(iid!=''){
			iid = iid.toString().replace('delete_items_','');
		}
        debugStr += iid+"|";
    }
	var menu = '<?php echo $menu; ?>';
	var table =  '#menu_table_<? echo $lngrv['id'];?>';
	var location_id = '<?php echo $_SESSION["loc"]; ?>';
	var group_id = jQuery(table).attr('rel');
	var itemidtoadd = jQuery("#hidItemIds").val().slice(0,-2);
	/*
	if(itemidtoadd!='' && typeof(itemidtoadd)!='undefined'){
		if(itemidtoadd.toString().indexOf(group_id)>-1){
			
			jQuery.ajax({
				type: "POST",
				url: "ajax_add_menu_article_drop.php",
				data: {itemid:0, menugroupid: jQuery("#hidMenuGroupID").val(),menu:jQuery("#hidMenu").val(),itemIds:jQuery("#hidItemIds").val()},
				success: function (data) {
					jQuery("#hidItemIds").val('');
					jQuery.ajax({
						url:"update_menu_items_priority.php?location_id="+location_id+"&menu="+menu+"&group_id="+group_id+"&item=" + debugStr + "&sid=" + Math.random(),
						success:function (data) {
							jQuery('#menu_table_<? echo $lngrv['id'];?> tbody').html(data);
						}
					});	
				  }
			   });
			return false;

		}
	}*/
		
	
		jQuery("#btn_submit").removeClass("btn-active");
		jQuery("#btn_submit").addClass("btn-success");
		/*
		//console.log(debugStr);
		jQuery.ajax({
            url:"update_menu_items_priority.php?location_id="+location_id+"&menu="+menu+"&group_id="+group_id+"&item=" + debugStr + "&sid=" + Math.random(),
            success:function (data) {
				//jQuery('#menu_table_<? echo $lngrv['id'];?> tbody').html(data);
			}
        });
        */
	
}


 });


</script>
                  </div>
                  <?php //}?>
                </div>
                <?php } }?>
              </div>
              <!--#accordion-->
              <br>
              <br>
            </div>
          </div>
        </div>
        <br style="clear: both;" />
        <!--footer-->
        <?php include_once 'includes/footer.php';?>
      </div>
      <!--maincontentinner-->
    </div>
    <!--maincontent-->
  </div>
  <!--rightpanel-->
</div>
<!--mainwrapper-->
<div id="edit_modifier_modal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
    <h3>Add/Edit Article Modifier Default</h3>
  </div>
  <form id="modifier_default_frm1" name="modifier_default_frm" action="insertmenuarticlemodifier_frombygrp.php" method="post" class="form-horizontal" onSubmit="return validac(event)">
    <div class="modal-body" style="height:400px"> </div>
    <!-- <div class="modal-footer" style="text-align: center;">
		<a data-dismiss="modal" href="#" class="btn">Cancel</a>
		<a id="submit_zone" href="#" class="btn btn-primary">Submit</a>
	</div> -->
    <div style="text-align:center;" class="modal-footer">
      <input class="btn" style="width: 40px;" value="Cancel" data-dismiss="modal">
      <input  type="submit" name="submit" id="submit" value="Submit" class="btn btn-primary">
      <!--<input class="btn" type="reset" name="clear" price="Clear" />-->
    </div>
  </form>
</div>
<div id="edit_modifier_group_modal" class="modal hide fade" style="width:450px;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
    <h3>Edit Modifiers Group</h3>
  </div>
  <div class="modal-body" style="max-height: 450px;">
    <input type="hidden" name="menu_article_modifier_group_id" id="menu_article_modifier_group_id" value="">
    <input type="hidden" name="returnUrl" id="returnUrl" value="<?php echo $_SERVER['QUERY_STRING'];?>">
    <table width="100%" border="0">
      <tr>
        <td style="width:30%;">Status:</td>
        <td style="width:65%;"><select id="status" name="status">
            <option value=""> - - - Select Status - - - </option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select></td>
      </tr>
      <tr>
        <td>Modifier Group Name:</td>
        <td><input type="text" name="modifier_name" id="modifier_name" class="input-short" value=""></td>
      </tr>
    </table>
  </div>
  <div class="modal-footer" style="text-align:center;">
    <button aria-hidden="true" data-dismiss="modal" class="btn ">Cancel</button>
    <button class="btn btn-primary" onClick="editGroupAction();" id="save_imgdigital"><span class="icon-ok icon-white"></span> Save Changes</button>
  </div>
</div>
</body>
</html>


<script type="text/javascript">

		var array_menus = '<? echo json_encode($array_menus);?>';
		array_menus = JSON.parse(array_menus);

	    function changePriority_sort(){
    	
    	console.log('xxxx');
    	console.log(array_menus);
		<? 
			for($w =0; $w< sizeof($array_menus);$w++){
		?>
		//for(var x=0; x<= array_menus.length; x++){

			console.log(<? echo sizeof($array_menus);?>)

			console.log(<? echo $array_menus[$w];?>)
			//var rows = table+lngrv_id.tBodies[0].rows;
			var rows = table<? echo $array_menus[$w]; ?>.tBodies[0].rows;
			console.log('rows-- '+rows);
		    var debugStr = "";
		    for (var i=0; i<rows.length; i++) {
				var iid = rows[i].id;
				if(iid!=''){
					iid = iid.toString().replace('delete_items_','');
				}
		        debugStr += iid+"|";
		    }
			var menu = '<?php echo $menu; ?>';
			var table =  '#menu_table_<? echo $array_menus[$w];?>';
			var location_id = '<?php echo $_SESSION["loc"]; ?>';
			var group_id = jQuery(table).attr('rel');
			var itemidtoadd = jQuery("#hidItemIds").val().slice(0,-2);
			
			if(itemidtoadd!='' && typeof(itemidtoadd)!='undefined'){
				if(itemidtoadd.toString().indexOf(group_id)>-1){

					
					jQuery.ajax({
						type: "POST",
						url: "ajax_add_menu_article_drop.php",
						data: {itemid:0, menugroupid: jQuery("#hidMenuGroupID").val(),menu:jQuery("#hidMenu").val(),itemIds:jQuery("#hidItemIds").val()},
						success: function (data) {
							jQuery("#hidItemIds").val('');
							jQuery.ajax({
								url:"update_menu_items_priority.php?location_id="+location_id+"&menu="+menu+"&group_id="+group_id+"&item=" + debugStr + "&sid=" + Math.random(),
								success:function (data) {
									jQuery('#menu_table_<? echo $array_menus[$w];?> tbody').html(data);
								}
							});	
						  }
					   });
				
					return false;
				}
			}

			jQuery("#btn_submit").removeClass("btn-active");
			jQuery("#btn_submit").addClass("btn-success");

				console.log(menu);
				console.log(table);
				console.log(location_id);
				console.log(group_id);

				jQuery.ajax({
		            url:"update_menu_article_modifire_priority.php?location_id="+location_id+"&menu="+menu+"&group_id="+group_id+"&item=" + debugStr + "&sid=" + Math.random()+ "&type=default", 
		            success:function (data) {
						//jQuery('#menu_table_<? echo $lngrv['id'];?> tbody').html(data);
					}
		        });

				console.log('w-- <? echo $w;?>');
		<? } ?>			
			//}
	}

	
	function activeOrder(){
		console.log('active');
		jQuery("#btn_submit").removeClass("btn-active");
		jQuery("#btn_submit").addClass("btn-success");
	}


</script>


<script type="text/javascript" src="simpleautocomplete/js/simpleAutoComplete.js"></script>
<link rel="stylesheet" type="text/css" href="simpleautocomplete/css/simpleAutoComplete.css"/>
<script type="text/javascript">
	var menu_id = '<?php echo $menu; ?>';

    var newArticle = true;
    var stop = false;

    var location_id = '<?php echo $_SESSION['loc'];?>';
    var apiToken = generatetoken();
    
    jQuery(document).ready(function () {
	
	

jQuery("#accordion").accordion({
    collapsible: true,
        active: true,
        heightStyle: "content",
        header: 'h3'
    })
    .sortable({
    items: '.s_panel',
    stop: function( event, ui ) {
        var items=[];
        ui.item.siblings().andSelf().each( function(){
               items.push(jQuery(this).data('id'));
        });
        console.log(items);
        jQuery.ajax({
            url:"ajax-update-menu-groups-priority.php?groups=" + items,
           // dataType:'json',
            success:function (item) {
                
            }
        })        
    }
	});
	jQuery('#accordion').find('div[id*="ac_menugroup_<?php echo $menu_id; ?>"]').closest('h3').trigger('click'); 
	jQuery('#accordion').find('ac_menugroup_<?php echo $menu_id; ?>').closest('h3').trigger('click');
//jQuery('.accordion-toggle').mouseover(function(){
//    jQuery( this ).click();
//});

jQuery('#accordion').on('accordionactivate', function (event, ui) {
        if (ui.newPanel.length) {
           jQuery('#accordion').sortable('disable');
        } else {
            jQuery('#accordion').sortable('enable');
        }
    });

		
        jQuery('#ccitem').simpleAutoComplete('ajax_article_query.php?menu_id='+menu_id, {
            autoCompleteClassName:'autocomplete',
            selectedClassName:'sel',
            attrCallBack:'rel',
            extraParamFromInput:'#group_name',
            identifier:'article'
        }, itemCallback);
        jQuery('#item_group').change(function(){
            jQuery.get('ajax-menu-group-priority.php?menu=<?=$menu?>&group=' + jQuery(this).val(),function(data){
                jQuery('#item_priority').val(data);
            });
        });
        jQuery('#ccitem').blur(function(){
            if(newArticle){
                jQuery.get('ajax-menu-article-priority.php',function(data){
                    jQuery('#ccpriority').val(data);
                });
            }
        });
        if(jQuery('#item_group').val() != '' && jQuery('#item_priority').val() == ''){
            jQuery('#item_group').change();
        }
        <?php if($_GET['idads'] != ''){ ?>
            jQuery('.art').prop('disabled',true);
        <?php }?>

    });
	
    function clearField() {
		jQuery('#item_priority').val('');
        jQuery('#item_price').val('');
        jQuery('#ccitem').prop('readonly',false).val('');
        jQuery('#ccpriority').prop('disabled',false).val('');
        jQuery('#cctaxable').prop('disabled',false).val('');
        jQuery('#ccprice').prop('disabled',false).val('');
        jQuery('#ccmax_quantity').prop('disabled',false).val('');
        jQuery('#cctogo').prop('disabled',false).val('');
        jQuery('#ccdelivery').prop('disabled',false).val('');
        jQuery('#ccrequire_temperature').prop('disabled',false).val('');
        jQuery('#ccdrink').prop('disabled',false).val('');
        jQuery('#ccglass').prop('disabled',false).val('');
        jQuery('#ccglass_price').prop('disabled',false).val('');
        jQuery('#ccglass_price2').prop('disabled',false).val('');
        jQuery('#ccdivide').prop('disabled',false).val('');
        jQuery('#ccmax_divide').prop('disabled',false).val('');
        jQuery('#ccfire_order').prop('disabled',false).val('');
        jQuery('#ccsides').prop('disabled',false).val('');
        jQuery('#ccrefills').prop('disabled',false).val('');
        jQuery('#printer_id').prop('disabled',false).val('');
        jQuery('#ccdescription').prop('disabled',false).val('');
        jQuery('#video').prop('disabled',false).val('');
        jQuery('#image').prop('disabled',false).val('');
		jQuery('#ccplu').prop('disabled',false).val('');
		jQuery("#imagebox").html('');
		jQuery("#video_canvas").html('');
        newArticle = true;
    }
    function itemCallback(par) {
        newArticle = false;
        jQuery("#ccitem").val((par[1]));
        jQuery.ajax({
            url:"getArticleData.php?item=" + encodeURIComponent(par[1]) + "&sid=" + Math.random() + "&loc=<?=$_SESSION['loc'];?>",
            dataType:'json',
            success:function (item) {
                if(jQuery('#item_price').val() == ''){
                    jQuery('#item_price').val(item.price);
                }
		//		jQuery("#item_priority").val(item.priority);//.prop('readonly',true);
                jQuery("#ccitem").prop('readonly',true);
				jQuery('#ccplu').val(item.plu).prop('disabled',true);
                jQuery('#ccpriority').val(item.priority).prop('disabled',true);
                jQuery('#ccprice').val(item.price).prop('disabled',true);
                jQuery('#cctaxable').val(item.taxable).prop('disabled',true);
                jQuery('#ccmax_quantity').val(item.max_quantity).prop('disabled',true);
                jQuery('#cctogo').val(item.togo).prop('disabled',true);
                jQuery('#ccdelivery').val(item.delivery).prop('disabled',true);
                jQuery('#ccrequire_temperature').val(item.require_temperature).prop('disabled',true);
                jQuery('#ccdrink').val(item.drink).prop('disabled',true);
                jQuery('#ccglass').val(item.glass).prop('disabled',true);
                jQuery('#ccglass_price').val(item.glass_price).prop('disabled',true);
                jQuery('#ccglass_price2').val(item.glass_price).prop('disabled',true);
                jQuery('#ccdivide').val(item.divide).prop('disabled',true);
                jQuery('#ccmax_divide').val(item.max_divide).prop('disabled',true);
                jQuery('#ccfire_order').val(item.fire_order).prop('disabled',true);
                jQuery('#ccsides').val(item.sides).prop('disabled',true);
                jQuery('#ccrefills').val(item.Refills).prop('disabled',true);
                jQuery('#printer_id').val(item.printer_id).prop('disabled',true);
                jQuery('#ccdescription').val(item.description).prop('disabled',true);
               
				if(item.image!=null && item.image!="")
		{
			
			jQuery('#old_image').val(item.image);
			jQuery("#imagebox").html('<img src="<?php echo APIIMAGE;?>images/'+item.image+'" width="100px;">');
		}
		else
		{	
			jQuery("#imagebox").html('');
		}
		if(item.video!=null && item.video!="")
		{	
			jQuery('#old_video').val(item.video);
			var video='<object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" >';
                video+=' <param name="movie" value="player.swf"/>';
                video+='   <param name="allowfullscreen" value="true"/>';
            	video+='    <param name="allowscriptaccess" value="always"/>';
            	video+='    <param name="wmode" value="opaque"/>';
            	video+='    <param name="flashvars" value="file=<?php echo APIIMAGE; ?>images/'+item.video+'"/>';
           		video+='     <embed type="application/x-shockwave-flash" id="player2" name="player2" src="player.swf" allowscriptaccess="always" allowfullscreen="true" flashvars="file=<?php echo APIIMAGE; ?>images/'+item.video+'"/>';
           		video+='    </object>';
				jQuery("#video_canvas").html(video);
		}
		else
		{
			jQuery("#video_canvas").html('');
		}
				
				
                jQuery.uniform.update();
            },error:function(t,s,p){
                console.log(t);
                console.log(s);
                console.log(p);
            }
        });
    }
</script>
<script>
var codeid=0;
var cObject="";

function DeleteMenuGroup(menugrpid){	
	jConfirm("Would you like to remove this group?","Confirm Dialog",function(r){
		if(r){
			jQuery.ajax({
				url:'setup_rest_menu_details_page.php',
				type:'POST',
				data:{action:'delete',menugrp_id:menugrpid, menu_id: menu_id },
				success:function(data){
					if(data=='yes'){
						jAlert("Group Deleted successfully!","Alert Dialog",function(r){
							if(r){
								jQuery("#btn_submit").addClass("btn-active");
								window.location.reload();
								//jQuery("#del_"+menugrpid).fadeOut('slow');
							}
						});
					}else{
						jAlert('Can not delete this Group','Alert Dialog');
					}
				}
			});
		}
	});
}


function resetBtn()
{
	
        if(codeid==0)
	{
		document.getElementById("menuform").reset();
	}
	else
	{
		/*jConfirm('Do you want to discard your changes?', 'Confirm', function(r) {
			if (r)
			{	
				clearForm();
			}
                });*/
                
	}
}
function loadData(cObject){
	  jQuery("#menu_item").val(cObject.data("id"));
	   jQuery("#item_group").val(cObject.data("menu_id"));
	   jQuery("#item_priority").val(cObject.data("priority"));
	   jQuery("#item_price").val(cObject.data("price"));
		codeid=cObject.data("id");
		var myarray = new Array();
	   myarray[0]=cObject.data("item_id");
	   myarray[1]=cObject.data("id");
	  itemCallback(myarray);
}
function Confirm(){
		jConfirm("This Menu Article is already offered on this Menu Would you like to continue?", 'Confirmation Dialog', function(r) {
							if(r)
							{
								
							}else{
								clearField();
							}
					});
		
}

 jQuery(document).ready(function(){ 
     jQuery('#videoModal').on('hidden', function() {
    jQuery(this).removeData('modal');
});
  jQuery('#imageModal').on('hidden', function() {
    jQuery(this).removeData('modal');
});
	
	
    jQuery(".codedata").click(function(){
	jQuery('.line3').removeClass('line3');
	var id = jQuery(this).data('id');
	jQuery('#'+id).addClass('line3');
	
	jQuery("#ResetButton").hide();
		
		cObject=jQuery(this);
	    loadData(cObject);
		
	
	 
	   
    });
    
    jQuery(".addmenu").click(function(){
	
	jQuery("#ResetButton").show();
		 codeid=0;
		 cObject="";
	    jQuery("#menu_item").val("");
		jQuery("#item_group").val(jQuery(this).data("id"));
		
    })
	
	
	
			 jQuery(".deletemenu1").live("click",function(){
                             
			 		var id=jQuery(this).data("id");
					var ths = jQuery(this);
			 		jConfirm("Are you sure you want to Delete?", 'Confirmation Dialog', function(r) {
							if(r)
							{
								//window.location="delete_menu_items.php?menu=<?=$menu?>&id="+id;
								
								var item_ids = jQuery("#hidItemIds").val();
								var menugroupid = jQuery(ths).attr('data-menugroupid');
								var itemId = jQuery(ths).attr('data-id');
								var string = itemId+'|'+ menugroupid + ","; 
								console.log(string);
								if(item_ids!=''){
									item_ids = item_ids.toString().replace(string,'');
									console.log(item_ids);
									jQuery("#hidItemIds").val(item_ids);
									
								}
								
								
								jQuery(ths).closest('tr').remove();
								jAlert("Deleted successfully!","Alert Dialog");	
								//jQuery("#hidItemIds").val(obj.data("id")+'|'+ menugroupid + "," + jQuery("#hidItemIds").val());
								
								
							}
					});
			 });
	 });
  function itemtoInactive(id){
  			jConfirm("This Menu Item has been used before. Are you sure you want to delete it from the Menu?", 'Confirmation Dialog', function(r) {
					if(r)
					{
						//window.location="delete_menu_items.php?menu=<?=$menu?>&id="+id;
						jQuery.ajax({
							url:'delete_menu_items.php?menu=<?=$menu?>&id='+id+'&type=Inactive',
							type:'POST',
							success:function(data){
								if(data){											
									jAlert("Item Deleted successfully!","Alert Dialog");	
									jQuery("#delete_items_"+id).css('display','none');
								}else{
									jAlert("Item Can not be deleted This time","Alert Dialog");
								}
							}
						});
						
					}
			});
  }
	 
  function itemtodelete(id){  
        jConfirm("This Menu Item has been used before. Are you sure you want to delete it from the Menu?", 'Confirmation Dialog', function(r) {
					if(r)
					{
						//window.location="delete_menu_items.php?menu=<?=$menu?>&id="+id;
						jQuery.ajax({
							url:'delete_menu_items.php?menu=<?=$menu?>&id='+id,
							type:'POST',
							success:function(data){
								if(data){											
									jAlert("Item Deleted successfully!","Alert Dialog");	
									jQuery("#delete_items_"+id).css('display','none');
								}else{
									jAlert("Item Can not be deleted This time","Alert Dialog");
								}
							}
						});
						
					}
			});
  }
  
  
  //////////////////aj javascript /////
  function editGroup(id,modifier_group,description,status){
		jQuery('#menu_article_modifier_group_id').val(id);
		jQuery('#modifier_name').val(modifier_group);
		jQuery('#status').val(status);
		jQuery("#edit_modifier_group_modal").modal('toggle');
  }
  
  
   function deleteGroup(id){
  	 jConfirm("Are you sure you want to delete?", 'Confirmation Dialog', function(r) {
					if(r){
						jQuery.ajax({
							url:'ajax-action-modifier-groups.php?type=delete&id='+id,
							type:'POST',
							success:function(data){
								if(data){											
									jAlert("Group Deleted successfully!","Alert Dialog");
									//jAlert(data);
									location.reload();
								}else{
									jAlert("Error occoured","Alert Dialog");
								}
							}
						});
						
					}
			});
  }
  
   function editGroupAction(){
  	if(jQuery('#status').val() == ''){
		jAlert("Please select status!","Alert Dialog");
		return false;
	}
	if(jQuery('#modifier_name').val() == ''){
		jAlert("Please enter Group name!","Alert Dialog");
		return false;
	}
	jQuery.ajax({
			url:'ajax-action-modifier-groups.php?type=edit&menu_article_modifier_group_id='+jQuery('#menu_article_modifier_group_id').val(),
			type:'POST',
			data: {
				status: jQuery("#status").val(),
				modifier_name:jQuery("#modifier_name").val()},
			success:function(data){
				if(data){	

						menuURL2 = "ajax_update_livemenu.php?location_id="+location_id+"&token="+apiToken+"&editmenu=restaurant";
                            jQuery.ajax({
                                type: "GET",
                                url: menuURL2,
                                success:function(data){
                                    //jQuery('#loading123').hide();
                                    console.log(data);
                                    jAlert("Group edited successfully!","Alert Dialog");
									 //jAlert(data);
									location.reload();
                                }
                            });

					
				}else{
					jAlert("Error occoured","Alert Dialog");
				}
			}
		});
	
  }
  
  
  		jQuery('.menuitems_checkBox_top').click(function(){
    		if(jQuery(this). prop("checked") == true){
				jQuery('.menuitems_checkBox').prop( "checked", true );
			}else{
				jQuery('.menuitems_checkBox').prop( "checked", false );
			}
		});
		
		
		
function deleteGrpFromModifier(group_id,mid,rowid){
   	 jConfirm("Are you sure you want to delete?", 'Confirmation Dialog', function(r) {
					if(r){
						jQuery.ajax({
							url:'ajax-action-modifier-groups.php?type=deleteGrpFromModifier',
							type:'POST',
							data: {
								group_id:group_id,
								mid:mid
							},
							success:function(data){
								if(data){											
									jAlert("Deleted successfully!","Alert Dialog");
									//jQuery("#"+rowid).hide();
									jQuery("#delete_items_"+rowid).css('display','none');
									console.log("#delete_items_"+rowid);
									//location.reload();
								}else{
									jAlert("Error occoured","Alert Dialog");
								}
							}
						});
						
					}
			});
}
  
  
  function validac(e) {
		 var ok = true;
		
		 if (document.forms.modifier_default_frm.status.value == "") {
            jAlert('Please select Status!','Alert Dialog');
			return false;
		} else if (document.forms.modifier_default_frm.article_type.value == "") {
            jAlert('Please select Article Type!','Alert Dialog');
			return false;	
        }else if (document.forms.modifier_default_frm.type.value == "--") {
            jAlert('Please select Type!','Alert Dialog');
			return false;
        } else if (document.forms.modifier_default_frm.modifier.value == "") {
            jAlert('Please insert Modifier!','Alert Dialog');
			return false;
		}else if (document.forms.modifier_default_frm.plu.value == "") {
            jAlert('Please insert PLU!','Alert Dialog');
			return false;
        }else if (document.forms.modifier_default_frm.description.value == "") {
            jAlert('Please insert Description!','Alert Dialog');
			return false;
        } else if (document.forms.modifier_default_frm.price.value == "" && document.forms.modifier_default_frm.type.value != "SIZE") {
            jAlert('Please enter Price!','Alert Dialog');
			return false;
        } else if (document.forms.modifier_default_frm.max_qty.value == "" && document.forms.modifier_default_frm.type.value != "SIZE") {
            jAlert('Please enter Max Qty!','Alert Dialog');
			return false;
        } else if (document.forms.modifier_default_frm.taxable.value == "") {
            jAlert('Please select Taxable!','Alert Dialog');
			return false;
        } else if (document.forms.modifier_default_frm.delivery.value == "") {
            jAlert('Please select Delivery!','Alert Dialog');
			return false;
        } else if (document.forms.modifier_default_frm.togo.value == "") {
            jAlert('Please select Togo!','Alert Dialog');
			return false;
		
		}else if(jQuery("#hiddenPrice").val()!="" && jQuery("#hiddenPrice").val()!=jQuery("#price").val()){
			e.preventDefault();
			jConfirm("Would you like to Update the Price of Modifier that use this Modifier default?",'Confirm Dialog',function(r){
				if(r){
						
						jQuery("#hiddenPrice").val(jQuery("#price").val());
						jQuery("#chnage_price").val('Yes');
						jQuery("#modifier_default_frm1 select").attr('disabled',false);
						jQuery("#modifier_default_frm1 input").attr('disabled',false);
						jQuery('#submit').trigger('click');
						
						}else{
						jQuery("#hiddenPrice").val(jQuery("#price").val());
						jQuery("#chnage_price").val('No');
						jQuery("#modifier_default_frm1 select").attr('disabled',false);
						jQuery("#modifier_default_frm1 input").attr('disabled',false);
						jQuery('#submit').trigger('click');					
						}
					});
			
		}else{
			menuURL2 = "ajax_update_livemenu.php?location_id="+location_id+"&token="+apiToken+"&editmenu=restaurant";
            jQuery.ajax({
                type: "GET",
                url: menuURL2,
                success:function(data){
                    console.log(data);
                    return true;
                }
            });
			
		}
    }

  
   
</script>
