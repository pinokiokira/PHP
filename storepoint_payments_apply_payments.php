<?php
include_once 'require/security.php';
include_once("config/accessConfig.php");
?>
<style type="text/css">
#apply_payments_frm input[type="text"]{
  border-bottom-left-radius:0;
  border-bottom-right-radius:0;
  border-top-left-radius:0;
  border-top-right-radius:0;
  color:#555555;
  display:inline-block;
  font-size:12px;
  margin-bottom:0px;
  padding:0px;
  vertical-align:middle;
  width:100%;
}
.itm_inpt{
text-align:right;}
.{
background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
border: medium none;
}
</style>
<?php 
$empmaster_id=$_SESSION['client_id'];
$vender  = mysql_fetch_array(mysql_query("select StorePoint_vendor_Id from employees_master where empmaster_id=".$empmaster_id));
$vendor_id = $vender['StorePoint_vendor_Id'];

	$location=$_REQUEST["vendor_id"];
	$order_id = $_REQUEST['orderid'];
	$query = "SELECT p.id,p.completed_datetime,p.subtotal,p.tax_total,p.total,p.total-p.applied_amount AS unapplied_amt, SUM(pi.ordered_quantity) AS qty
		FROM purchases p
		LEFT JOIN purchase_items PI ON pi.purchase_id=p.id
		WHERE p.location_id=" . $location . " AND p.vendor_id=" . $vendor_id . " AND p.status='Completed' 
		GROUP BY p.id";
	$result = mysql_query($query) or die(mysql_error());
	
	
	
?>
<div class="row-fluid">   
			<?php 	



			$empmaster_idasd=$_SESSION['client_id'];
				$venderasd  = mysql_fetch_array(mysql_query("select * from employees_master where empmaster_id=".$empmaster_idasd));
				$vendor_idasd = $venderasd;




			$query_pay ="SELECT pp.*,concat(e.first_name,' ',e.last_name)as name  FROM purchases_payments as pp LEFT JOIN employees as e on e.id = pp.employee_id WHERE pp.id=".$order_id;
					$res_pay = mysql_query($query_pay);
					$row_pay= mysql_fetch_array($res_pay);
					$available = $row_pay['amount']-$row_pay['applied_amount'];
			 ?>
			<form action="apply_payments.php" onsubmit="return validate()" method="get" name="payments_frm" id="apply_payments_frm">
           <input type="hidden" value="<?php echo $row_pay['id'];?>" name="payment_id">
  <!--           <input type="hidden" value="19" name="vendor_id">-->
            <input type="hidden" value="submitted" name="apply_payments_frm">
            <table class="table table-bordered responsive">
                <tbody><tr>
                    <td colspan="5" style="border:none;">
                        <div>
                            <div style="float:left;display: inline;">
                                <div>Type:<input type="text" style="width:215px;margin-left:57px;margin-bottom: 10px;" value="<?php echo $row_pay['type']; ?>" class="" id="type" disabled="disabled"></div>
                                <div>Ref:<input type="text" style="width:215px;margin-left:65px;margin-bottom: 10px;" value="<?php echo $row_pay['reference']; ?>" class="" id="reference" disabled="disabled"></div>
                                <div>Employee:<input type="text" style="width:215px;margin-left: 30px;margin-bottom: 10px;padding-bottom:1px;" value="<?php echo $vendor_idasd['first_name']. ' ' . $vendor_idasd['last_name'] . ' (ID: ' . $vendor_idasd['empmaster_id'] . ')' ?>" id="employee" class="" disabled="disabled"></div>
                            </div>
                            <div style="float:right;width:175px;">
                                <div style="float:right;">Amount:<input type="text" class="" style="width:75px;text-align:right;margin-left:15px;margin-bottom: 10px;" value="<?php echo $row_pay['amount']; ?>" id="amt" disabled="disabled"></div>
                                <input type="hidden" name="id" value="<?=$_REQUEST["orderid"];?>" />
                                <div style="float:right;">Applied:<input type="text" class="" style="width:75px;text-align:right;margin-left: 15px;margin-bottom: 10px;" value="<?php echo $row_pay['applied_amount']; ?>" id="applied_amt" name="applied_amt" disabled="disabled"></div>
                                <div style="float:right;">Available:<input type="text" class="" style="width:75px;text-align:right;margin-left: 15px;margin-bottom: 10px;" value="<?php echo  number_format($available, 2, '.', ''); ?>" id="available" disabled="disabled"></div>
                            </div>
							<div style="float:left;">
								<div>Description:<textarea disabled="disabled" class="" style="resize:none;margin-left: 20px;width:80.5%" cols="100" rows="5"><?php echo $row_pay['description']; ?></textarea></div>
							</div>
                        </div>
						
                        <!--<span style="vertical-align:top;">Description:</span><textarea disabled="disabled" class="" style="resize:none;margin-left: 20px;" cols="49" rows="1"></textarea>-->
					
                    </td>
                </tr>
                <tr style="height:10px;">
				<td style="border:none;">&nbsp;</td>
				<td style="border:none;">&nbsp;</td>
				</tr>
            </tbody>
            </table>


            <?php

	            //echo "Test!";
	            //echo mysql_num_rows($result);


            	$v = 0;
            	while ($row = mysql_fetch_array($result)) {
					if($row['unapplied_amt']!="0"){ 
						$v++;
					}
				}

            	if ($v>0) {

            ?>



            <table class="table table-bordered responsive" id="datatable">
            	<thead>
                <tr class="title">
                    <th>Date</th>
                    <th>Terms</th>
                    <th>Balance</th>
                    <th>Applied Amt</th>
                    <th>New Balance</th>
                </tr>
                </thead>
                <tbody id="dataBody">
                <?php
				$a=1;
					while ($row = mysql_fetch_array($result)) {
					if($row['unapplied_amt']!="0"){ 
				?>
                <tr id="1" class="abc">
                     <td><?= date('Y-m-d h:i:s');?></td>
                     <td>On Delivery</td>
                     <?php
					 	$query_p ="SELECT SUM(amount_applied) as amount_applied  FROM purchases_payments_applied where purchase_id=".$row['id'];
						$res_p = mysql_query($query_p);
						$res_p = mysql_fetch_array($res_p);
					 	$bal = $row['total']-$res_p['amount_applied'];
					 ?>
                     <td><input type="text"  id="balance<?php echo $a;?>" disabled="disabled" value="<?php echo number_format($row['unapplied_amt'], 2, '.', '');?>" style="width:90px;text-align:right;"></td>
                     <td><input type="text" style="width:75px;text-align:right;" value="0" onblur="decimal(<?php echo $a; ?>)"  onkeyup="ok(<?php echo $a;?>)" class="amt" id="amount<?php echo $a;?>" name="amount[]" ></td>
                     <td><input type="text" id="new_balance<?php echo $a;?>" disabled="disabled"  value="<?php echo number_format($row['unapplied_amt'], 2, '.', '');?>" style="width:75px;text-align:right;"></td>
                     <input type="hidden" name="purchase_id[]" value="<?=$row['id'];?>">
                </tr>
                    <?php $a++;}} ?>
                    <input type="hidden" name="count_a" id="count_a" value="<?php echo $a; ?>"  />
                    <input type="hidden" name="location_id" value="<?=$_REQUEST["vendor_id"];?>">
               </tbody></table>



               <?php

               		}

               ?>




        </form>
       
      <script type="text/javascript">
	  function validate(){
		var count_a = jQuery('#count_a').val();
		var total_amt = 0;
		var total_bal = document.getElementById("available").value;
		//->juni [req REQ_018] - 2014-09-21 - split mesages
		var recordsWith0 = 0;
		var recordsTotal = 0;
		//<-juni [req REQ_018] - 2014-09-21
		for(j=1;j<count_a;j++){
			if(jQuery('#amount'+j).val()==""){
				jAlert('Please Enter Amount!','Alert Dialog');
				return false;
			}
			//->juni [req REQ_018] - 2014-09-21 - split mesages
			/* if(!isNaN(jQuery('#amount'+j).val() && jQuery('#amount'+j).val()==0)) */
			if(jQuery('#amount'+j).val()==0)
				recordsWith0++;
			else
				recordsTotal++;
			console.log(jQuery('#amount'+j).val() ,recordsWith0,recordsTotal);
			//->juni [req REQ_018] - 2014-09-21 - split mesages	
			var entered_amt = jQuery('#amount'+j).val();
			total_amt = (total_amt+parseFloat(entered_amt));  
		}
		if(parseFloat(total_amt)>parseFloat(total_bal)){
			jAlert('You have allocated an amount greater than the total available ('+total_bal+')','Alert Dialog');
			 return false;
		}			
 
		if(recordsWith0 > 0 && recordsTotal < 1) {//->juni [req REQ_018] - 2014-09-21 - split mesages
				jAlert('Please enter payment amount > 0','Alert Dialog');
			 return false;
		}
			
	  }
	  function decimal(i){
	  	
	  	var amount  = jQuery("#amount"+i).val();
		if(amount!=""){
	  	var n = decimal_point(amount);
		jQuery("#amount"+i).val(n);
		}
	  
	  }
	   function ok(i)
	  {
		  var bal=document.getElementById("balance"+i).value;
		  var amount=document.getElementById("amount"+i).value;
		  var total_bal = document.getElementById("available").value;
		  if(isNaN(amount))
					{
						jAlert('Sorry! Please Enter atleast 0 number', 'Alert Dialog', function(){ 
						});
						//alert("Sorry! Please Enter atleast 0 number");
						document.getElementById("amount"+i).value="";
						
					}
					
                    /*else if(parseFloat(amount)>parseFloat(total_bal)){
						jAlert('You have allocated an amount greater than the total available('+total_bal+')','Alert Dialog');
						jQuery('#amount'+i).val('');
					}*/else
					{
					var due=bal-amount;
					var n = due.toFixed(2);
					document.getElementById("new_balance"+i).value=n;
					
					}
	  }
	function decimal_point(val_n){ 
	var decimals =2;
	var val = Math.round(val_n * Math.pow(10, decimals)) / Math.pow(10, decimals);
	var n = val.toFixed(2);
	return n;
}
	   		/*jQuery(document).ready(function($) {
				var i=1;
				
				$("#datatable tbody").keyup('#amount',function(event){
					//$('#dataBody tr').each(function (index){
						
					var bal= $("#balance").val();
					var  amount= $("#amount").val();
					alert(amount);
					if(i=1&& isNaN(amount))
					{
						jAlert('Sorry! Please Enter atleast 0 number', 'Alert Dialog', function(){ 
						});
						//alert("Sorry! Please Enter atleast 0 number");
						$("#amount").val("");
					}
					
                    else
					{
					var due=bal-amount;
					$("#new_balance").val(due);
					}
					//});
				});
					
				
				
			});*/
		</script>
       

</div>
