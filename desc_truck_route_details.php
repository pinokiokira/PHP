<?php 
require_once 'require/security.php';
include 'config/accessConfig.php';

$id = $_POST['id'];
$date = $_POST['date'];


// $sqlloc="SELECT p.subtotal, (select COALESCE(sum(ordered_tax_percentage),0) from purchase_items where purchase_id =p.id) as tax_total, p.total,st.code,gc.symbol FROM purchases as p INNER JOIN locations lo ON lo.id = p.location_id LEFT JOIN global_currency as gc ON gc.id =lo.currency_id left join states st on st.id = lo.state WHERE p.id=".$id;
// $qrloc = mysql_query($sqlloc) or die(mysql_error());
// $lnloc=mysql_fetch_assoc($qrloc);
?>
<style>
.normal{ font-size:12px !important;}
</style>
<script type="text/javascript">
            jQuery(document).ready(function ($) {

                jQuery('#licence_table').dataTable({
                    language: {
                                paginate: {
                                  next: '>', // or '→'
                                  previous: '<' // or '←' 
                                }
                              },
                    //select: true,
                    "sPaginationType": "full_numbers",
                    "bJQuery": true,
                    select: true,
                    "fnDrawCallback": function (oSettings) {
                        //  jQuery.uniform.update();
                    }



                });
                jQuery('#vehicles_tbl_wrapper .dataTables_filter input').unbind('keypress keyup').bind('keypress keyup', function(e){
          jQuery('.line_vehicles').removeClass('line_vehicles');
          jQuery("#row_"+jQuery('#vehicles_back').val()).addClass('line_vehicles');
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
                </script>
<div class="popup" id="pop_wrap">
        <div style="clear:both;">
            <div style="min-height:108px; overflow-x:auto;" class="print_content" > 
            	<table id="licence_table" class="table table-bordered responsive">
                <colgroup>
                    <col class="con0" style="width:20%;" />
                    <col class="con1" style="width:20%;" />
                    <col class="con0" style="width:20%;"/>
                    <col class="con1" style="width:20%;"/>
                    <col class="con0" style="width:20%;"/>
                   
                   
                   </colgroup>
                   <thead>
                      <tr>
                        <th class="head1" >Route</th>
                        <th class="head0" >Load Time</th>
                        <th class="head1" >Route Loaded</th>
                        <th class="head1" >Case Count</th>
						<th class="head0" >Timeout</th>
                                                                
                      </tr>
                    </thead>
                    <tbody>
                   
                
                 
                <?php
                function foo($seconds) {
  $t = round($seconds);
  return sprintf('%02d:%02d', ($t/3600),($t/60%60));
}
				
				$query1 = "select * from vendor_distribution_routes where vehicle='$id' and date(created_datetime)='$date'";
				
				
				$res1 = mysql_query($query1);
               
             $Count=mysql_num_rows($res1);
				while($row1=mysql_fetch_array($res1)){?>
                <tr>
                  
                <td class="normal" ><?php echo $row1['routes'];?></td>
                <td class="normal" ><?php echo foo($row1['load_time']); ?></td>
                <td class="normal" ><?php echo foo($row1['route_time']); ?></td>
                <td class="normal" ><?php echo $row1['cartons']; ?></td>
				<td class="normal" ><?php echo foo($row1['time_out']); ?></td>
               
                </tr>
					
				<?php } ?>
				
            	    </tbody>
                    </table>             
            
				</table>               
				
			
							
            </div>
            
            
						
			
        </div>
</div>