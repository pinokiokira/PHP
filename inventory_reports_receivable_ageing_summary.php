<table id="report_summary_report_summary_balance" class="table table-bordered responsive dataTable reports_table"  cellpadding="0" cellspacing="0">
	<colgroup>
		<col class="con0" />
		<col class="con0" />
		<col class="con1" />
		<col class="con1" />
		<col class="con0" />
		<col class="con0" />
		<col class="con0" />
	</colgroup>
	<thead>
		<tr>
			<th class="head0 center">Company</th>
			<th class="head0 center">Current</th>
			<th class="head1 center">30-60 days</th>
			<th class="head0 center">61-90 days</th>
			<th class="head0 center">90-120 days</th>
			<th class="head0 center">Over 120 days</th>
			<th class="head1 center">Amount due</th>
		</tr>
	</thead>
	<tbody role="alert" aria-live="polite" aria-relevant="all">
	<?php  
	//getting all the companies that have records in the companies_receivables for the signed-in location
	$sql = "SELECT DISTINCT(cr. company_id) as company_id,
			com.name as company_name 
		FROM companies AS com 
			INNER JOIN companies_receivables cr ON cr.company_id = com.company_id 
		WHERE cr.location_id='".$_SESSION['loc']."'"
		.$orderBy
	;
	$cRes = mysql_query($sql) or die (mysql_error());
	if (mysql_num_rows($cRes) > 0) {
	while ($cRow = mysql_fetch_array($cRes)) {
		//echo $sql;
		$currency  = "";   
		$due_0_29 = 0;
		$due_30_60 = 0;
		$due_61_90 = 0;
		$due_90_120 = 0;
		$due_over_120 = 0;	
		$total_amt = "";
		$activeWhere = " cr.balance <> 0 ";
		$activeWhereCRP = " (cr.balance <> 0 or crp.payment <> 0) ";
								
		$query = "SELECT 
			com.name as company_name,
			COALESCE(crp.company_receivables_payments_id,0) AS row_id,
			CONCAT(crp.date,' ',DATE_FORMAT(crp.time,'%H:%i')) AS date_time,
			crp.company_receivables_payments_id AS crp_id,
			NULL AS cr_id,
			'Payments' as source,
			crp.balance,
			crp.settled_payment AS amount,
			crp.payment,crp.settled_payment,NULL AS hotelacct_id,NULL AS client_sales_id,NULL AS client_orders_id,
			l.currency_symbol 
			,CONCAT(e.first_name,' ',e.last_name,' (ID: ',e.id,')') AS employee
		FROM companies AS com 
			INNER JOIN companies_receivables cr ON cr.company_id = com.company_id 
			LEFT OUTER JOIN companies_receivables_payments crp ON cr.company_id = com.company_id AND crp.companies_receivables_id=cr.company_receivables_id
			LEFT JOIN locations l ON l.id = crp.location_id 
			LEFT JOIN employees e ON e.id = crp.location_employee_id 
		WHERE $activeWhereCRP
			AND crp.company_id='".$cRow['company_id']."' 
			AND cr.location_id='".$_SESSION['loc']."' 
		UNION 
		SELECT 
			com.name as company_name,
			COALESCE(cr.company_receivables_id,0) AS row_id,
			CONCAT(cr.date,' ',DATE_FORMAT(cr.time,'%H:%i')) AS date_time,
			NULL AS crp_id,
			cr.company_receivables_id AS cr_id,
			'Charged' as source,
			cr.balance,
			cr.amount AS amount,
			cr.payment AS payment,cr.payment AS settled_payment,cr.hotelacct_id,cr.client_sales_id,cr.client_orders_id,
			l.currency_symbol 
			,CONCAT(e.first_name,' ',e.last_name,' (ID: ',e.id,')') AS employee
		FROM companies AS com 
			INNER JOIN companies_receivables AS cr ON cr.company_id = com.company_id 
			LEFT JOIN locations l ON l.id = cr.location_id 
			LEFT JOIN employees e ON e.id = cr.location_employee_id 
		WHERE $activeWhere
			AND cr.company_id='".$cRow['company_id']."' 
			AND cr.location_id='".$_SESSION['loc']."'
		 ORDER BY row_id ASC";	
		//echo $query;
		$res = mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($res)>0){
			while($row = mysql_fetch_array($res)){			
			if ($row['amount']-$row['payment'] == 0)//30.08.2014
				continue;	
			$currency = $row["currency_symbol"];		
			$total_amt = $total_amt + ($row['amount']-$row['payment']);
			//Calculating amounts per period
			$now = time();
			$rowdate = strtotime($row['date_time']);
			$datediff = $now - $rowdate;
			$daysdif =  floor($datediff/(60*60*24));
			//echo $daysdif;
			if ($daysdif >= 0 && $daysdif < 30)
				$due_0_29 += $row['amount'] - $row['payment'];
			else if ($daysdif > 29 && $daysdif < 61)
				$due_30_60 += $row['amount']  - $row['payment'];
			else if ($daysdif > 60 && $daysdif < 90)
				$due_61_90 += $row['amount']  - $row['payment'];
			else if ($daysdif > 89 && $daysdif < 120)
				$due_90_120 += $row['amount']  - $row['payment'];						
			else if ($daysdif > 120)
				$due_over_120 += $row['amount']  - $row['payment'];		
			else
				;
			if ($row['source'] == 'Payments')//stupid method to have payments display amount -> Recaivable Statement - Aug 31 - SF41.jpg
				$amount = $row['payment']*-1;//negate it to have it in brackets
			else
				$amount = $row['amount'];
			if ($row['settled_payment']!='')
				$row['settled_payment'] = $row['settled_payment']*-1;
		?>
		<?php }} 
		if ($detail_level!='report_summary_balance' || ($detail_level=='report_summary_balance' && $total_amt != 0)) {
		?>			
			<tr>
				<td><?=$cRow['company_name']; ?></td>
				<td class="right"><?=formatNumberWithCurrency($currency,$due_0_29,2)?></td>
				<td class="right"><?=formatNumberWithCurrency($currency,$due_30_60,2)?></td>
				<td class="right"><?=formatNumberWithCurrency($currency,$due_61_90,2)?></td>
				<td class="right"><?=formatNumberWithCurrency($currency,$due_90_120,2)?></td>
				<td class="right"><?=formatNumberWithCurrency($currency,$due_over_120,2)?></td>
				<td class="right"><?=formatNumberWithCurrency($currency,$total_amt,2)?></td>					
			</tr>	
		<?php 
		} //end if ($detail_level!='report_summary_balance' && ($detail_level=='report_summary_balance' && $total_amt != 0)) {
	}} 	else { //if (mysql_num_rows($cRes) > 0) { while ($cRow = mysql_fetch_array($cRes)) { ?>	
		<tr>
			<tr class="odd"><td valign="top" colspan="7" class="dataTables_empty">No data available in table</td></tr>									
		</tr>							
	<?php } ?>								
	</tbody>
</table> <!--end report_summary_report_summary_balance-->