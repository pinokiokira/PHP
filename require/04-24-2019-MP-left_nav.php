<?php 
$page = basename($_SERVER['PHP_SELF']);

$get_vender = mysql_fetch_assoc(mysql_query("SELECT StorePoint_vendor_Id FROM employees_master WHERE empmaster_id = '".$_SESSION['client_id']."'"));
$_SESSION['StorePointVendorID'] = $get_vender['StorePoint_vendor_Id'];

$get_emp = mysql_fetch_assoc(mysql_query("SELECT id FROM employees WHERE email = '{$_SESSION["email"]}'"));
$_SESSION['employee_id'] = $get_emp['id'];
?>
<div class="leftpanel">
  <div class="leftmenu">
    <ul class="nav nav-tabs nav-stacked">
      <li class="nav-header">Navigation</li>
      <!-- <li <?php //echo $page=='dashboard.php'?'class="active"':'';?>><a href="dashboard.php">
        <div class="iconfa-laptop" style="float:left;width:25px;margin-top:4px;"></div>
        Dashboard</a></li> -->
	<?php 
		// print_r($_SESSION);
		$email = $_SESSION['email'];
		$qry_is_scheduling = "SELECT COUNT(id) AS count_id FROM employees WHERE email='". $email ."'";
		$rs_is_scheduling = mysql_query($qry_is_scheduling) or die($qry_is_scheduling .'-----'. mysql_error());
		if($rs_is_scheduling && mysql_num_rows($rs_is_scheduling) > 0 ) {
			$row_is_scheduling = mysql_fetch_assoc($rs_is_scheduling);
			$row_is_scheduling = $row_is_scheduling['count_id'];
		}
		$is_employee = false;
		if($row_is_scheduling > 0){
			$is_employee = true;
	 	} 
	?>

		<?php $sql_count3 = "Select id  from employee_messages where emp_id in (SELECT id FROM employees WHERE email = '{$_SESSION["email"]}') and readd='no' ";
			$res_count3=mysql_query($sql_count3);
			$nocnt3=mysql_num_rows($res_count3);
			
			$loc_check =mysql_query("SELECT id FROM locations where id in (SELECT location_id from employees WHERE email='{$_SESSION["email"]}') order by name ASC limit 2");
			if(mysql_num_rows($loc_check)>0){ ?>
	       <?php /* ?> <li <?php echo $page=='messages.php'?' class="active"':'';?>><a class="showLoad" href="messages.php">
	        <div class="iconfa-envelope" style="float:left;width:25px;margin-top:4px;"></div>
	        Store Messages <? if($nocnt3>0){?> (<? echo $nocnt3?>) <? }?></a></li><?php */ ?>
        	<?php } ?>
			
			
		<li class="dropdown <?php echo ($page=='pricing_sheet.php' || $page=='pricing_spot.php')?'active':'';?>">
			<a href=""><div class="iconfa-th" style="float:left;width:25px;margin-top:4px;"></div>Pricing</a>
			<ul <?php echo ($page=='pricing_sheet.php' || $page=='pricing_spot.php')?'style="display: block;"':'';?>>
			  <li <?php echo $page=='pricing_sheet.php'?'class="active"':'"';?>><a href="pricing_sheet.php">Price Sheet  </a></li>
			  <li <?php echo $page=='pricing_spot.php'?'class="active"':'"';?>><a href="pricing_spot.php">Spot Prices</a></li>
			</ul>
		</li> 
        	
     	<li <?php echo $page=='buying.php'?'class="active"':'"';?>><a href="buying.php"><span style="margin-right:10px;" class="iconfa-credit-card"></span>Buying</a></li>
		<li <?php echo $page=='accounts_payable.php'?'class="active"':'"';?>><a href="accounts_payable.php"><span style="margin-right:10px;" class="iconfa-user"></span>Purchase Order</a></li>
		<li class="dropdown <?php echo ($page=='receiving_intake.php' || $page=='receiving_destribution.php')?'active':'';?>"><a href="">
			<div class="iconfa-list-alt" style="float:left;width:25px;margin-top:4px;"></div>Receiving</a>
			<ul <?php echo ($page=='receiving_intake.php' || $page=='receiving_destribution.php')?'style="display: block;"':'';?>>
			  <li <?php echo $page=='receiving_intake.php'?'class="active"':'"';?>><a href="receiving_intake.php">Intake</a></li>
			  <li <?php echo $page=='receiving_destribution.php'?'class="active"':'"';?>><a href="receiving_destribution.php">Distribution</a></li>
			  <!--<li <?php echo $page=='receiving_home.php'?'class="active"':'"';?>><a href="receiving_home.php">Home</a></li>-->
			</ul>
		  </li>
		
		
		
      	<?php /* ?><li <?php echo $page=='clients.php'?'class="active"':'"';?>><a href="clients.php"><span style="margin-right:12px;" class="iconfa-user"></span>Clients</a></li> <?php */ ?>
		<?php /* ?><li class="dropdown <?php echo ($page=='storepoint.php'||$page=='storepoint_clients.php'||$page=='storepoint_messages_inquiry.php')?'active':'';?>">
			<?
				$sql_count = "select id from employee_master_location_storepoint a  where a.read = 'No'  and a.emp_master_id = '".$_SESSION['client_id']."' and a.sent_by_type='Location'";
				$res_count=mysql_query($sql_count);
				$nocnt=mysql_num_rows($res_count);
			?>
			<a href=""><div class="iconfa-user" style="float:left;width:25px;margin-top:4px;"></div>
			
			Clients&nbsp;<?php if($nocnt>0){?> (<? echo $nocnt?>) <? } else {echo '(0)';} ?></a>
			<ul <?php echo ($page=='storepoint.php'||$page=='storepoint_clients.php'||$page=='storepoint_messages_inquiry.php')?'style="display: block;"':'';?>>
			  <li <?php echo $page=='storepoint.php'?'class="active"':'"';?>><a href="storepoint.php">Messages <?php if($nocnt>0){?> (<? echo $nocnt?>) <? }?> </a></li>
			  <li <?php echo $page=='storepoint_clients.php'?'class="active"':'"';?>><a href="storepoint_clients.php">Clients</a></li>
			</ul>
		</li><?php */ ?>
      	<?php if (isset($_SESSION['StorePointVendorID']) && $_SESSION['StorePointVendorID']!=""){
				$p_countq = mysql_fetch_array(mysql_query("SELECT count(id) as tpid from purchases where vendor_id = '".$_SESSION['StorePointVendorID']."' and status = 'Ordered'")); 
				$tpid = $p_countq['tpid'];
			?>
       
		  
		<li class="dropdown <?php echo ($page=='backoffice_inventory.php' || $page=='backoffice_enter_inventory.php' || $page=='backoffice_movements.php')?'active':'';?>">
			<a href=""><div class="iconfa-cog" style="float:left;width:25px;margin-top:4px;"></div>Inventory</a>
			<ul <?php echo ($page=='backoffice_inventory.php'||$page=='inventory_reports.php'||$page=='backoffice_enter_inventory.php'||$page=='backoffice_movements.php')?'style="display: block;"':'';?>>
			 <li class="dropdown"><a href="">Internal Inventory</a>
				<ul <?php echo ($page=='backoffice_inventory.php'||$page=='inventory_reports.php'||$page=='backoffice_enter_inventory.php'||$page=='backoffice_movements.php')?'style="display: block;"':'';?>>
				  
				  <li <?php echo $page=='backoffice_inventory.php'?'class="active"':'"';?>><a href="backoffice_inventory.php">Inception</a></li>
				  <li <?php echo $page=='backoffice_enter_inventory.php'?'class="active"':'"';?>><a href="backoffice_enter_inventory.php">Enter Inventory</a></li>
				  <li <?php echo $page=='backoffice_movements.php'?'class="active"':'"';?>><a href="backoffice_movements.php">Movements</a></li>
				</ul>
			 </li>
			 <!--<li <?php echo $page=='inventory.php'?'class="active"':'"';?>><a href="inventory.php">eCommerce Inventory</a></li>-->
			 <li <?php echo $page=='inventory_reports.php'?'class="active"':'"';?>><a href="inventory_reports.php">Reports</a></li>
			</ul>
		</li>
		
		<li class="dropdown <?php echo ($page=='wip_packaging.php' || $page=='wip_movements.php' || $page=='wip_inventory.php')?'active':'';?>">
			<a href=""><div class="iconfa-th" style="float:left;width:25px;margin-top:4px;"></div>WIP Inventory</a>
			<ul <?php echo ($page=='wip_packaging.php' || $page=='wip_movements.php' || $page=='wip_inventory.php')?'style="display: block;"':'';?>>
			  <li <?php echo $page=='wip_movements.php'?'class="active"':'"';?>><a href="wip_movements.php">Movements</a></li>
			  <li <?php echo $page=='wip_inventory.php'?'class="active"':'"';?>><a href="wip_inventory.php">Inventory</a></li>
			  <li <?php echo $page=='wip_packaging.php'?'class="active"':'"';?>><a href="wip_packaging.php">Packaging</a></li>
			</ul>
		</li>
		
		<!--<li <?php echo $page=='wip_inventory.php'?'class="active"':'"';?>><a href="wip_inventory.php"><span style="margin-right:10px;" class="iconfa-user"></span>WIP Inventory</a></li>-->
		<li <?php echo $page=='inventory.php'?'class="active"':'"';?>><a href="inventory.php"><span style="margin-right:10px;" class="iconfa-user"></span>eCommerce</a></li>
		  
		<?php if (isset($_SESSION['StorePointVendorID']) && $_SESSION['StorePointVendorID']!=""){
			$p_countq = mysql_fetch_array(mysql_query("SELECT count(id) as tpid from purchases where vendor_id = '".$_SESSION['StorePointVendorID']."' and status = 'Ordered'")); 
			$tpid = $p_countq['tpid'];
		?>
		<li class="dropdown <?php echo ($page=='purchases.php' || $page=='storepoint.php' || $page=='storepoint_clients.php' || $page=='backoffice_purchases_compleated.php'  ||  $page=='backoffice_purchases.php')?'active':'';?>">
			<a href=""><div class="iconfa-tags" style="float:left;width:25px;margin-top:4px;"></div>Sales</a>
			<?
				$sql_count = "select id from employee_master_location_storepoint a  where a.read = 'No'  and a.emp_master_id = '".$_SESSION['client_id']."' and a.sent_by_type='Location'";
				$res_count=mysql_query($sql_count);
				$nocnt=mysql_num_rows($res_count);
			?>
			<ul <?php echo ($page=='purchases.php' || $page=='storepoint.php' || $page=='storepoint_clients.php' || $page=='backoffice_purchases_compleated.php'  ||  $page=='backoffice_purchases.php')?'style="display: block;"':'';?>>
			  <?php /* <li <?php echo $page=='sales.php'?'class="active"':'"';?>><a href="sales.php">Sales</a></li> */?>
			  <li <?php echo $page=='storepoint.php'?'class="active"':'"';?>><a href="storepoint.php">Messages <?php if($nocnt>0){?> (<? echo $nocnt?>) <? }?> </a></li>
			  <li <?php echo $page=='storepoint_clients.php'?'class="active"':'"';?>><a href="storepoint_clients.php">Clients</a></li>
			  <li <?php echo $page=='purchases.php' || $page=='backoffice_purchases_compleated.php'  ||  $page=='backoffice_purchases.php'?'class="active"':'"';?>><a href="purchases.php">Orders <?php if($tpid>0){ echo '('.$tpid.')'; }?></a></li>
			</ul>
		</li> 	
			
  		<?php } ?>
		
		<li class="dropdown <?php echo ($page=='desc_dashboard.php' || $page=='shipping.php' || $page=='desc_trucks.php' || $page=='desc_orders.php' || $page=='desc_matrix.php' )?'active':'';?>">
			<a href=""><div class="iconfa-truck" style="float:left;width:25px;margin-top:4px;"></div>Distribution</a>
			<ul <?php echo ($page=='desc_dashboard.php' || $page=='shipping.php' || $page=='desc_trucks.php' || $page=='desc_orders.php' || $page=='desc_routes.php' || $page=='desc_matrix.php') ?'style="display: block;"':'';?>>
			  <li <?php echo $page=='desc_dashboard.php'?'class="active"':'"';?>><a href="desc_dashboard.php">Dashboard</a></li>
			  <li <?php echo $page=='desc_trucks.php'?'class="active"':'"';?>><a href="desc_trucks.php">Trucks</a></li>
			  <li <?php echo $page=='desc_orders.php'?'class="active"':'"';?>><a href="desc_orders.php">Orders</a></li>
			  <li <?php echo $page=='shipping.php'?'class="active"':'"';?>><a href="shipping.php">Distribution</a></li>
			  <li <?php echo $page=='desc_routes.php'?'class="active"':'"';?>><a href="desc_routes.php">Routes</a></li>
			  <li <?php echo $page=='desc_matrix.php'?'class="active"':'"';?>><a href="desc_matrix.php">Matrix</a></li>
			</ul>
		</li>
		  
		<!--<li <?php echo $page=='shipping.php'?'class="active"':'"';?>><a href="shipping.php"><span style="margin-right:10px;" class="iconfa-truck"></span>Distribution</a></li>-->
		  
		<li <?php echo ($page=='payments.php' || $page=='storepoint_payments.php')?'class="active"':'"';?>><a href="payments.php"><span style="margin-right:10px;" class="iconfa-money"></span>Payments</a></li>
		  
		  <?php /* ?><li <?php echo $page=='human_resources.php'?'class="active"':'"';?>><a href="human_resources.php"><span style="margin-right:12px;" class="iconfa-user"></span>Human Resources</a></li>

		  <li <?php echo $page=='accounting.php'?'class="active"':'"';?>><a href="accounting.php"><span style="margin-right:10px;" class="iconfa-list-alt"></span>Accounting</a></li><?php */ ?>
            <?php }?>
       
        <li <?php echo ($page=='business_intelligence.php')?'class="active"':'"';?>><a href="business_intelligence.php"><span style="margin-right:10px;" class="iconfa-money"></span>Business Intelligence</a></li>
		
		<?
			if($_SESSION['accessStylistFN']=='Yes')
			{
		?>
		<li class="dropdown <?php echo ($page=='stylistfn_messages.php'||$page=='stylistfn_clients.php' || $page=='stylistfn_messages_inquiry.php' || $page=='stylistfn_items.php')?'active':'';?>"><a href="stylistfn_messages.php">
        <div class="iconsweets-tshirt" style="float:left;width:25px;margin-top:4px;"></div>
        <?
			$sql_count = "select id from employee_master_location_stylistfn a  where a.read = 'No'  and a.emp_master_id = '".$_SESSION['client_id']."' and (a.sent_by_type='Client' or a.sent_by_type='Location')";
			$res_count=mysql_query($sql_count);
			$nocnt=mysql_num_rows($res_count);
		?>
        StylistFN<? if($nocnt>0){?> (<? echo $nocnt?>) <? }?></a>
        <ul <?php echo ($page=='stylistfn_messages.php'||$page=='stylistfn_clients.php' || $page=='stylistfn_messages_inquiry.php' || $page=='stylistfn_items.php')?'style="display: block;"':'';?>>
          <li <?php echo $page=='stylistfn_items.php'?'class="active"':'"';?>><a href="stylistfn_items.php">My Items</a></li>
          <li <?php echo $page=='stylistfn_messages.php'?'class="active"':'"';?>><a href="stylistfn_messages.php">Messages</a></li>
          <li <?php echo $page=='stylistfn_clients.php'||$page=='stylistfn_messages_inquiry.php'?'class="active"':'"';?>><a href="stylistfn_clients.php">Clients / Businesses</a></li>
        </ul>
        </li>
		<?
			}
		?>
		<?
			if($_SESSION['accessChefedIN']=='Yes')
			{
		?>
		<li class="dropdown <?php echo ($page=='chefedin_messages.php'||$page=='chefedin_clients.php' || $page=='chefedin_services.php' || $page=='chefedin_messages_inquiry.php')?'active':'';?>"><a href="chefedin_messages.php">
        <div class="iconsweets-megaphone" style="float:left;width:25px;margin-top:4px;"></div>
		<?php
			$sql_count2 = "select id from employee_master_location_chefedin a  where a.read = 'No'  and a.emp_master_id = '".$_SESSION['client_id']."' and a.sent_by_type='Location'";
			$res_count2=mysql_query($sql_count2);
			$nocnt2=mysql_num_rows($res_count2);
		?>
        ChefedIN<? if($nocnt2>0){?> (<? echo $nocnt2?>) <? }?></a> 
        <ul <?php echo ($page=='chefedin_messages.php'||$page=='chefedin_clients.php' || $page=='chefedin_services.php' || $page=='chefedin_messages_inquiry.php')?'style="display: block;"':'';?>>
          <li <?php echo $page=='chefedin_messages.php'?'class="active"':'"';?>><a href="chefedin_messages.php">Messages</a></li>
          <li <?php echo ($page=='chefedin_clients.php' || $page=='chefedin_messages_inquiry.php')?'class="active"':'"';?>><a href="chefedin_clients.php">Clients</a></li>
          <?php if (isset($_SESSION['ChefedIN_Business_Name']) && $_SESSION['ChefedIN_Business_Name']!=""){?>
          <li <?php echo $page=='chefedin_services.php'?'class="active"':'"';?>><a href="chefedin_services.php">Services</a></li>
          <?php }?>
        </ul>
        </li>
		<? }?>

        <li <?php echo ($page=='learntube.php' || $page=='learntube_training_lessons_take_lesson.php')?'class="active"':'';?>><a href="learntube.php">
        <div class="iconfa-facetime-video" style="float:left;width:25px;margin-top:4px;"></div>
        LearnTube</a></li>

      	<li class="dropdown <?php echo ($page=='setup_editprofile.php'||$page=='setup_location.php' || $page=='setup_bays.php' || $page=='setup_vehicles.php')?'active':'';?>"><a href="">
        <div class="iconfa-cog" style="float:left;width:25px;margin-top:4px;"></div>Setup</a>
        <ul <?php echo ($page=='setup_editprofile.php'||$page=='setup_location.php'||$page=='setup_items.php'||$page=='setup_backoffice_global_items.php'||$page=='setup_backoffice_manage_items.php'||$page=='setup_backoffice_storerooms.php'||$page=='setup_backoffice_storeroom_priority.php'||$page=='setup_finance_global_items_add.php' || $page=='setup_finance_manage_items_add.php' ||$page=='setup_bays.php' || $page=='setup_vehicles.php')?'style="display: block;"':'';?>>
          <li <?php echo $page=='setup_editprofile.php'?'class="active"':'"';?>><a href="setup_editprofile.php">Profile</a></li>
          <li <?php echo $page=='setup_location.php'?'class="active"':'"';?>><a href="setup_location.php">Locations</a></li>
          <li <?php echo $page=='setup_items.php'?'class="active"':'"';?>><a href="setup_items.php">eCommerce Items</a></li>
		  <?php 
			$q = mysql_query("SELECT location_link FROM vendors WHERE id = '".$_SESSION['StorePointVendorID']."'");
			if(mysql_num_rows($q) > 0){
				$f = mysql_fetch_array($q);
				if($f['location_link'] != '' || $f['location_link'] != 0){
					
					$_SESSION['loc'] = $f['location_link'];
			  ?>
			  <li class="dropdown"><a href="#">Inventory</a>
			   <ul <?php echo ($page=='setup_backoffice_global_items.php'||$page=='setup_backoffice_manage_items.php' || $page=='setup_backoffice_storerooms.php' || $page=='setup_backoffice_storeroom_priority.php' || $page=='setup_finance_global_items_add.php' || $page=='setup_finance_manage_items_add.php')?'style="display: block;"':'';?>>                               
				   <li <?php echo $page=='setup_backoffice_global_items.php'?'class="active"':'"';?>><a href="setup_backoffice_global_items.php">Global Items</a></li>                               
				   <li <?php echo ($page=='setup_backoffice_manage_items.php' || $page=='setup_finance_global_items_add.php' || $page=='setup_finance_manage_items_add.php')?'class="active"':'"';?>><a href="setup_backoffice_manage_items.php">Manage Items</a></li>
				   <li <?php echo $page=='setup_backoffice_storerooms.php'?'class="active"':'"';?>><a href="setup_backoffice_storerooms.php">Storerooms</a></li>
				   <li <?php echo $page=='setup_backoffice_storeroom_priority.php'?'class="active"':'"';?>><a href="setup_backoffice_storeroom_priority.php">Storeroom Priority</a></li>
				</ul>
			   </li> 
			   <?php
				}
			}
		   ?>
		   <li <?php echo $page=='setup_bays.php'?'class="active"':'"';?>><a href="setup_bays.php">Bays</a></li>
		   <li <?php echo $page=='setup_vehicles.php'?'class="active"':'"';?>><a href="setup_vehicles.php">Vehicles</a></li>
		   <li <?php echo $page=='setup_routes.php'?'class="active"':'"';?>><a href="setup_routes.php">Routes</a></li>
		   
        </ul>
      </li>
    </ul>
  </div>
  <!--leftmenu--> 
  
</div>
<!-- leftpanel -->
<script> 
	jQuery('.showLoad').click(function(e){//19.03.2014 -> remove data on close
		 jQuery("#loading-header").show();
	});
</script>