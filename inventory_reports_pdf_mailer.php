<?php
/*
*  @modified Ionut Irofte - juniorionut @ elance
*  @version $Id: backoffice_reports_pdf.php , v 1.0 9:36 PM 9/4/2014 juni $
*  -> [REQ_014  - 04.09.2014]
		->  BP - Receivables Settlements
*/

include_once 'includes/session.php';
include_once("config/accessConfig.php");
include("pdflib/fpdf.php");
include_once 'includes/jcustom.php';

// error_reporting(E_ALL);


function GetLocationTimeFromServer($intLocationID, $servertime){
	/*$jsonurl = API ."API2/getlocationtime_daylightsaving.php?intLocationID=".$intLocationID."&server_time=".urlencode($servertime);*/

    $jsonurl = "api/getlocationtime_daylightsaving.php?intLocationID=".$intLocationID."&server_time=".urlencode($servertime);

	$json = @file_get_contents($jsonurl,0,null,null);  
	$datetimenow= json_decode($json);
	$datetimenowk1 = $datetimenow->servertolocation_datetime;    
	$ldatetitme = date('Y-m-d H:i:s',strtotime($datetimenowk1));
	return $ldatetitme;
}
	
function getLocationName($locid) {
    $sql = "SELECT name FROM locations where id=" . $locid;
    $rs = mysql_query($sql);
    $d = mysql_fetch_array($rs);
    $nameloc = $d["name"];
    return $nameloc;
}

function sec2hm($sec, $padHours = false) {
    $hm = "";
    $hours = intval(intval($sec) / 3600);
    $hm .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT) . ":" : $hours . ":";
    $minutes = intval(($sec / 60) % 60);
    $hm .= str_pad($minutes, 2, "0", STR_PAD_LEFT);
    return $hm;
}


function getStatus($status){
    switch ($status){
        case "S":
            $newstatus = "Suspended";
            break;
        
        case "I":
            $newstatus = "Inactive";
            break;
    
        case "D":
            $newstatus = "Deceased";
            break;  
        
        case "T":
            $newstatus = "Terminated";
            break;  
    }
    return $newstatus;
}

function getSex($sex){
    switch ($sex){
        case "F":
            $sexTxt = "Female";
            break;
        
        case "M":
            $sexTxt = "Male";
            break;
    }
    return $sexTxt;	
}



$hdata['empName'] = $_SESSION['user_full_name'];
$hdata['empID'] = $_SESSION['user'];
$hdata['location'] = getLocationName($_SESSION['loc'])." (ID#: ".$_SESSION['loc']." )";
$date = GetLocationTimeFromServer($_SESSION['loc'],date("Y/m/d").' '.date("H:i"));
$arrDate['date'] = date("Y-m-d", strtotime($date)); //substr($date,0,10);
$arrDate['time'] = date("H:i:s", strtotime($date)); //substr($date2,10,10);
$hdata['empDate'] = $arrDate['date']." - ".$arrDate['time'];


//set PDF page title
if($_GET['download'] =='item')
{
	$txtprepage = 'Items';
}
else if($_GET['download'] =='item_group')
{
	$txtprepage = 'Items by Group';
}
else if($_GET['download'] =='item_stroom')
{
	$txtprepage = 'Items by Storeroom';
}
else if($_GET['download'] =='inv_date')
{	
	$txtprepage = 'Inventory by Date';
}
else if($_GET['download'] =='inv_item')
{	
	$txtprepage = 'Inventory by Item';
}
else if($_GET['download'] =='low_invertory')
{	
	$txtprepage = 'Low Inventory';
}
else if($_GET['download'] =='inv_stroom')
{	
	$txtprepage = 'Inventory by Storeroom';
}
else if($_GET['download'] =='inv_vendor')
{	
	$txtprepage = 'Inventory by Vendor';
}
else if($_GET['download'] =='inv_emp')
{	
	$txtprepage = 'Inventory by Employee';
}
else if($_GET['download'] =='order_group')
{	
	$txtprepage = 'Orders by Group';
}
//->juni [REQ_014]
 else if($_GET['download'] =='receivable_ageing'){	
	if ($_GET['detail_level'] =='report_summary') {
		$txtprepage = 'Receivable Ageing - Summary';
	} else if($_GET['detail_level'] =='report_summary_balance'){	
		$txtprepage = 'Receivable Ageing - Summary only with balance';
	} else if($_GET['detail_level'] =='report_detail'){	
		$txtprepage = 'Receivable Ageing - Details';
	} else if($_GET['detail_level'] =='report_all_detail'){	
		$txtprepage = 'Receivable Ageing - Details with Settlements';
	}
}	
//<-juni [REQ_014]
else
{
	$txtprepage = 'Line Check';
}
	
class PDF extends FPDF 
{
	function Header() 
	{
		global $txtprepage,$hdata;
		
		if($_GET['download'] =='receivable_ageing'){//->juni [REQ_014] -> move page number to footer
			
			
			$this->SetMargins(0.5,0.5,0.5);
			$x_ = $this->GetX();
            $this->SetX($x_+1);
            
            $this->SetTextColor(0);
            $this->SetY(0.1);
            $this->SetFont('Times','',8);
            $this->Cell(0,0.2, "Page ".$this->PageNo(), 0, 2, "R");
            $this->SetY(0.5);  
            
            $this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(3.0,0.25,$hdata['empName']." - ".$hdata['empID'],'TL',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(3.00,0.25,'','T',0,'C');
            $this->Cell(4.20,0.25,'','TR',0,'C');
			$this->Ln();
			$this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(3.0,0.0,'','L',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(5.2,0.0,strtoupper($txtprepage),'',0,'C');
            $this->Cell(4.0,0.0,'','R',0,'C');
            $this->Ln();
            
            $this->SetFont('Times','B',10);
            $this->Cell(3.0,0.25,$hdata['location'],'LB',0,'P');
            $this->Cell(3.0,0.25,'','B',0,'C'); 
                  //Header Row
            $this->Cell(4.20,0.25,$hdata['empDate'],'BR',0,'R');
            $this->SetY(0.76);
            $this->Ln();
			
			
		}else if($_GET['download']  =='inv_vendor'){ //landscap pdf
			$this->SetMargins(0.5,0.5,0.5);
			$x_ = $this->GetX();
            $this->SetX($x_+1);
            
            $this->SetTextColor(0);
            $this->SetY(0.1);
            $this->SetFont('Times','',8);
            $this->Cell(0,0.2, "Page ".$this->PageNo(), 0, 2, "R");
            $this->SetY(0.5);  
            
            $this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(3.10,0.25,$hdata['empName']." - ".$hdata['empID'],'TL',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(4.50,0.25,'','T',0,'C');
            $this->Cell(3.10,0.25,'','TR',0,'C');
			$this->Ln();
			$this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(3.10,0.0,'','L',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(4.50,0.0,strtoupper($txtprepage),'',0,'C');
            $this->Cell(3.10,0.0,'','R',0,'C');
            $this->Ln();
            
            $this->SetFont('Times','B',10);
            $this->Cell(3.10,0.25,$hdata['location'],'LB',0,'P');
            $this->Cell(3.10,0.25,'','B',0,'C'); 
                  //Header Row
            $this->Cell(4.50,0.25,$hdata['empDate'],'BR',0,'R');
            $this->SetY(0.76);
            $this->Ln();
		}else if($_GET['download']  =='inv_stroom'){ //landscap pdf
			$this->SetMargins(0.5,0.5,0.5);
			$x_ = $this->GetX();
            $this->SetX($x_+1);
            
            $this->SetTextColor(0);
            $this->SetY(0.1);
            $this->SetFont('Times','',8);
            $this->Cell(0,0.2, "Page ".$this->PageNo(), 0, 2, "R");
            $this->SetY(0.5);  
            
            $this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(3.10,0.25,$hdata['empName']." - ".$hdata['empID'],'TL',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(4.50,0.25,'','T',0,'C');
            $this->Cell(3.10,0.25,'','TR',0,'C');
			$this->Ln();
			$this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(3.10,0.0,'','L',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(4.50,0.0,strtoupper($txtprepage),'',0,'C');
            $this->Cell(3.10,0.0,'','R',0,'C');
            $this->Ln();
            
            $this->SetFont('Times','B',10);
            $this->Cell(3.10,0.25,$hdata['location'],'LB',0,'P');
            $this->Cell(3.10,0.25,'','B',0,'C'); 
                  //Header Row
            $this->Cell(4.50,0.25,$hdata['empDate'],'BR',0,'R');
            $this->SetY(0.76);
            $this->Ln();
		}else if($_GET['download']  =='inv_emp'){ //landscap pdf
			$this->SetMargins(0.5,0.5,0.5);
			$x_ = $this->GetX();
            $this->SetX($x_+1);
            
            $this->SetTextColor(0);
            $this->SetY(0.1);
            $this->SetFont('Times','',8);
            $this->Cell(0,0.2, "Page ".$this->PageNo(), 0, 2, "R");
            $this->SetY(0.5);  
            
            $this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(3.10,0.25,$hdata['empName']." - ".$hdata['empID'],'TL',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(4.3,0.25,'','T',0,'C');
            $this->Cell(3.10,0.25,'','TR',0,'C');
			$this->Ln();
			$this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(3.10,0.0,'','L',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(4.3,0.0,strtoupper($txtprepage),'',0,'C');
            $this->Cell(3.10,0.0,'','R',0,'C');
            $this->Ln();
            
            $this->SetFont('Times','B',10);
            $this->Cell(3.10,0.25,$hdata['location'],'LB',0,'P');
            $this->Cell(3.10,0.25,'','B',0,'C'); 
                  //Header Row
            $this->Cell(4.3,0.25,$hdata['empDate'],'BR',0,'R');
            $this->SetY(0.76);
            $this->Ln();
		}else if($_GET['download']  =='order_group'){ //landscap pdf
			$this->SetMargins(0.5,0.5,0.5);
			$x_ = $this->GetX();
            $this->SetX($x_+1);
            
            $this->SetTextColor(0);
            $this->SetY(0.1);
            $this->SetFont('Times','',8);
            $this->Cell(0,0.2, "Page ".$this->PageNo(), 0, 2, "R");
            $this->SetY(0.5);  
            
            $this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(3.10,0.25,$hdata['empName']." - ".$hdata['empID'],'TL',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(4.36,0.25,'','T',0,'C');
            $this->Cell(3.10,0.25,'','TR',0,'C');
			$this->Ln();
			$this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(3.10,0.0,'','L',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(4.36,0.0,strtoupper($txtprepage),'',0,'C');
            $this->Cell(3.10,0.0,'','R',0,'C');
            $this->Ln();
            
            $this->SetFont('Times','B',10);
            $this->Cell(3.10,0.25,$hdata['location'],'LB',0,'P');
            $this->Cell(3.10,0.25,'','B',0,'C'); 
                  //Header Row
            $this->Cell(4.36,0.25,$hdata['empDate'],'BR',0,'R');
            $this->SetY(0.76);
            $this->Ln();
		}else if($_GET['download']  =='inv_date'){ //landscap pdf
			$this->SetMargins(0.5,0.5,0.5);
			$x_ = $this->GetX();
            $this->SetX($x_+1);
            
            $this->SetTextColor(0);
            $this->SetY(0.1);
            $this->SetFont('Times','',8);
            $this->Cell(0,0.2, "Page ".$this->PageNo(), 0, 2, "R");
            $this->SetY(0.5);  
            
            $this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(3.0,0.25,$hdata['empName']." - ".$hdata['empID'],'TL',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(3.85,0.25,'','T',0,'C');
            $this->Cell(3.0,0.25,'','TR',0,'C');
			$this->Ln();
			$this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(3.0,0.0,'','L',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(3.85,0.0,strtoupper($txtprepage),'',0,'C');
            $this->Cell(3.0,0.0,'','R',0,'C');
            $this->Ln();
            
            $this->SetFont('Times','B',10);
            $this->Cell(3.0,0.25,$hdata['location'],'LB',0,'P');
            $this->Cell(3.0,0.25,'','B',0,'C'); 
                  //Header Row
            $this->Cell(3.85,0.25,$hdata['empDate'],'BR',0,'R');
            $this->SetY(0.76);
            $this->Ln();
		}else if($_GET['download']  =='inv_item'){ //landscap pdf
			$this->SetMargins(0.5,0.5,0.5);
			$x_ = $this->GetX();
            $this->SetX($x_+1);
            
            $this->SetTextColor(0);
            $this->SetY(0.1);
            $this->SetFont('Times','',8);
            $this->Cell(0,0.2, "Page ".$this->PageNo(), 0, 2, "R");
            $this->SetY(0.5);  
            
            $this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(3.0,0.25,$hdata['empName']." - ".$hdata['empID'],'TL',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(3.15,0.25,'','T',0,'C');
            $this->Cell(3.0,0.25,'','TR',0,'C');
			$this->Ln();
			$this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(3.0,0.0,'','L',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(3.15,0.0,strtoupper($txtprepage),'',0,'C');
            $this->Cell(3.0,0.0,'','R',0,'C');
            $this->Ln();
            
            $this->SetFont('Times','B',10);
            $this->Cell(3.0,0.25,$hdata['location'],'LB',0,'P');
            $this->Cell(3.0,0.25,'','B',0,'C'); 
                  //Header Row
            $this->Cell(3.15,0.25,$hdata['empDate'],'BR',0,'R');
            $this->SetY(0.76);
            $this->Ln();
		} else {
			$x_ = $this->GetX();
            $this->SetX($x_+1);
            
            $this->SetTextColor(0);
            $this->SetY(0.1);
            $this->SetFont('Times','',8);
            $this->Cell(0,0.2, "Page ".$this->PageNo(), 0, 2, "R");
            $this->SetY(0.5);  
            
            $this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(2.76,0.25,$hdata['empName']." - ".$hdata['empID'],'TL',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(3.78,0.25,'','T',0,'C');
            $this->Cell(2.36,0.25,'','TR',0,'C');
            $this->Ln();
			$this->SetLineWidth(0.02);
            $this->SetFont('Times','B',12);
            $this->Cell(2.76,0.0,'','L',0,'P');
            $this->SetFont('Times','B',12);
            $this->Cell(3.78,0.0,strtoupper($txtprepage),'',0,'C');
            $this->Cell(2.36,0.0,'','R',0,'C');
            $this->Ln();
            
            $this->SetFont('Times','B',10);
            $this->Cell(2.76,0.25,$hdata['location'],'LB',0,'P');
            $this->Cell(2.36,0.25,'','B',0,'C'); 
                  //Header Row
            $this->Cell(3.78,0.25,$hdata['empDate'],'BR',0,'R');
            $this->SetY(0.76);
            $this->Ln();
		}
			
		
	}
	
	function Footer(){	//->juni [REQ_014] -> move page number to footer
		if($_GET['download'] =='receivable_ageing'){
			
			$this->Ln();
			$this->SetY(-0.5);
			$this->SetFont('Arial', '', 8);
			//if($this->isFinished){ //in case i want just on the last page
				$this->Cell(10.2,0.25,$_SESSION["SITENAME"] . " - " . $_SESSION["SITE_URL"],'TRBL', 0, 'C');
				$this->SetY(-0.5); 
				$this->Cell(2,0.25,"Designed By: ".$_SESSION["DESIGNEDBY_NAME"],  0, 0, 'L');
				$this->Cell(8.2,0.25,'Page '.$this->PageNo().'/{nb}', 0, 0, 'R');
			//}
		}

	}
	//<- juni [REQ_014] -> move page number to footer
}

$pdf=new PDF("L","in","A4");

$pdf->SetMargins(1,1,1);
if($_GET['download'] =='receivable_ageing')	//->juni [REQ_014] -> move page number to footer
	$pdf->AliasNbPages();
 
//page reports
$location = getLocationName($_SESSION['loc']);
if($_GET['download'] =='item'){
	
	$pdf->AddPage('L');
	$pdf->SetFont('Times','',12);

	$orderField = $_GET['orderfield'];
	$orderType = $_GET['type'];
	$orderBy = '';
		
	if(!empty($orderField))
	{
		$orderBy = $orderField;
	}
	else
	{
		$orderBy = "type";
	}
		
	if(!empty($orderType))
	{
		$orderBy .=	' '.$orderType;
	}
	else
	{
		$orderBy .= ' ASC';
	}
		
	$sql = "(SELECT ii.description as description, ig.description as `itemgroup`, lii.priority,lii.type,iiu.unit_type
           FROM location_inventory_items lii
           INNER JOIN inventory_items ii ON lii.inv_item_id=ii.id
           INNER JOIN inventory_groups ig ON ii.inv_group_id=ig.id
           LEFT JOIN inventory_item_unittype iiu ON iiu.id=ii.unit_type
           WHERE location_id=" . $_SESSION['loc'] . " AND lii.status='active')
           UNION ALL
           (SELECT lii.local_item_desc as description, ig.description as `itemgroup`, lii.priority,type,iiu.unit_type
           FROM location_inventory_items lii
           INNER JOIN inventory_groups ig ON ig.id=lii.local_group_id
           LEFT JOIN inventory_item_unittype iiu ON iiu.id=lii.local_unit_type
           WHERE location_id=" . $_SESSION['loc'] . " AND lii.status='active')
           ORDER BY ".$orderBy;
		   
	$query = mysql_query($sql) or die(mysql_error());
		
	$pdf->SetFillColor(102, 100, 102);
	$pdf->SetTextColor(255);
	$pdf->SetFont('Arial','B',9);
    
	$header = array('Type', 'Group', 'Item', 'Unit Type','Priority');
	$header = array_map('strtoupper', $header);

	$pdf->Cell(1,0.25,$header[0],'TRB',0,'C',true);
	$pdf->Cell(2,0.25,$header[1],1,0,'C',true);  
	$pdf->Cell(3.8,0.25,$header[2],1,0,'C',true);
	$pdf->Cell(1.1,0.25,$header[3],1,0,'C',true);
	$pdf->Cell(1,0.25,$header[4],'TLB',0,'C',true);
	$pdf->Ln();			

	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',10);
	$fill = false;

	while ($emprow = mysql_fetch_assoc($query)) 
	{ 
		$pdf->Cell(1,0.25,$emprow['type'],'1', 0, "L");
		$pdf->Cell(2,0.25,$emprow['itemgroup'],1, 0, "L");
		$pdf->Cell(3.8,0.25,$emprow['description'],1, 0, "L");
		$pdf->Cell(1.1,0.25,$emprow['unit_type'],1, 0, "L");
		$pdf->Cell(1,0.25,$emprow['priority'],'1', 0, "C");			
		$pdf->Ln();					
	}
	

$filename = $location.'_Items Report - '. $arrDate['date'] .'.pdf';
//end of Item List Report 
}
else if($_GET['download'] =='item_group')
{
	$pdf->AddPage('L');
	$pdf->SetFont('Times','',12);

	$groupField = $_GET['group'];
				
	if(!empty($groupField))
	{
		$s = mysql_real_escape_string($_GET['group']);
    	$group_search = ' AND ig.id = ' . $s;
	}
	else
	{
    	$group_search = '';
	}
	
	$sql = "SELECT tbl.*,iiu.unit_type FROM (
               (SELECT ii.description as description, ig.description as `itemgroup`,lii.priority,lii.type,ii.unit_type
               FROM location_inventory_items lii
               INNER JOIN inventory_items ii ON lii.inv_item_id=ii.id
               INNER JOIN inventory_groups ig ON ii.inv_group_id=ig.id
               WHERE location_id=" . $_SESSION['loc'] . " AND lii.status='active'" . $group_search . ")
               UNION ALL
               (SELECT lii.local_item_desc as description, ig.description as `group`,lii.priority,lii.type,lii.local_unit_type
               FROM location_inventory_items lii
               INNER JOIN inventory_groups ig ON ig.id=lii.local_group_id
               WHERE location_id=" . $_SESSION['loc'] . " AND lii.status='active'" . $group_search . ")
           ) as tbl
           LEFT JOIN inventory_item_unittype iiu ON tbl.unit_type=iiu.id
           ORDER BY itemgroup ASC";
		   
	$query = mysql_query($sql) or die(mysql_error());
		
	$pdf->SetFillColor(102, 100, 102);
	$pdf->SetTextColor(255);
	$pdf->SetFont('Arial','B',9);
    
	$header = array('Type', 'Group', 'Item', 'UT','P');
	$header = array_map('strtoupper', $header);
	
	$pdf->Cell(1,0.25,$header[0],'TRB',0,'C',true);
	$pdf->Cell(2.2,0.25,$header[1],1,0,'C',true);  
	$pdf->Cell(4.0,0.25,$header[2],1,0,'C',true);
	$pdf->Cell(1,0.25,$header[3],1,0,'C',true);
	$pdf->Cell(0.7,0.25,$header[4],'TLB',0,'C',true);
	$pdf->Ln();			

	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',10);
	$fill = false;

	while ($emprow = mysql_fetch_assoc($query)) 
	{ 
		$pdf->Cell(1,0.25,$emprow['type'],'1', 0, "L");
		$pdf->Cell(2.2,0.25,$emprow['itemgroup'],1, 0, "L");
		$pdf->Cell(4.0,0.25,$emprow['description'],1, 0, "L");
		$pdf->Cell(1,0.25,$emprow['unit_type'],1, 0, "L");
		$pdf->Cell(0.7,0.25,$emprow['priority'],'1', 0, "C");			
		$pdf->Ln();					
	}					
	$filename= $location.'_item_by_group_report_'.$arrDate['date'].'.pdf';	
}
//end of Item By Group Report 

else if($_GET['download'] =='item_stroom')
{
	$pdf->AddPage('L');
	$pdf->SetFont('Times','',12);

	$stroom = $_GET['stroom'];
				
	if(!empty($stroom))
	{
    	$s = mysql_real_escape_string($_GET['stroom']);
    	$stroom = ' AND lis.storeroom_id=' . $s;
	}
	else
	{
    	$stroom = '';
	}
	
	$sql = "(SELECT DISTINCT lii.id, ii.description as description, ig.description as `itemgroup`,stroom_id, lii.priority,lii.type,iiu.unit_type,lis.priority as p1
            FROM location_inventory_storeroom_items lisi
            INNER JOIN location_inventory_items lii ON lisi.inv_item_id=lii.id
            INNER JOIN location_inventory_storerooms lis ON lisi.storeroom_id=lis.storeroom_id
            INNER JOIN inventory_items ii ON lii.inv_item_id=ii.id
            INNER JOIN inventory_groups ig ON ii.inv_group_id=ig.id
            LEFT JOIN inventory_item_unittype iiu ON iiu.id=ii.unit_type
            WHERE lisi.location_id=" . $_SESSION['loc'] . " AND lii.status='active'" . $stroom . ")
            UNION ALL
            (SELECT DISTINCT lii.id,lii.local_item_desc as description, ig.description as `group`,stroom_id, lii.priority,lii.type,iiu.unit_type,lis.priority as p1
            FROM location_inventory_storeroom_items lisi
            INNER JOIN location_inventory_items lii ON lisi.inv_item_id=lii.id
            INNER JOIN location_inventory_storerooms lis ON lisi.storeroom_id=lis.storeroom_id
            INNER JOIN inventory_groups ig ON ig.id=lii.local_group_id
            LEFT JOIN inventory_item_unittype iiu ON iiu.id=lii.local_unit_type
            WHERE lisi.location_id=" . $_SESSION['loc'] . " AND lii.status='active'" . $stroom . ")
            ORDER BY p1 ASC";
			
	$query = mysql_query($sql) or die(mysql_error());
		
	$pdf->SetFillColor(102, 100, 102);
	$pdf->SetTextColor(255);
	$pdf->SetFont('Arial','B',9);
    
	$header = array('Storeroom', 'Group', 'Item', 'Type','P');
	$header = array_map('strtoupper', $header);
	
	$pdf->Cell(1,0.25,$header[0],'TRB',0,'C',true);
	$pdf->Cell(2.2,0.25,$header[1],1,0,'C',true);  
	$pdf->Cell(4,0.25,$header[2],1,0,'C',true);
	$pdf->Cell(1,0.25,$header[3],1,0,'C',true);
	$pdf->Cell(0.7,0.25,$header[4],'TLB',0,'C',true);
	$pdf->Ln();			

	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',10);
	$fill = false;

	while ($emprow = mysql_fetch_assoc($query)) 
	{ 
		$pdf->Cell(1,0.25,$emprow['stroom_id'],'1', 0, "L");
		$pdf->Cell(2.2,0.25,$emprow['itemgroup'],1, 0, "L");
		$pdf->Cell(4,0.25,$emprow['description'],1, 0, "L");
		$pdf->Cell(1,0.25,ucfirst($emprow['type']),1, 0, "L");
		$pdf->Cell(0.7,0.25,$emprow['priority'],'1', 0, "C");			
		$pdf->Ln();					
	}					
	$filename= $location.'_item_by_storeroom_report_'.$arrDate['date'].'.pdf';  						                         	                            																			
}
//end of Item By Storeroom Report 

else if($_GET['download'] =='inv_date')
{
	$pdf->AddPage('L');
	$pdf->SetFont('Times','',12);

	$date = '';

	if($_GET['date'] != ''){
		$date = " AND date_counted='" . mysql_real_escape_string($_GET['date']) . "'";
	}

	$sql = "(SELECT local_item_desc as item,ig.description as `itemgroup`, lic.type,date_counted,time_counted,quantity,stroom_id,iiu.unit_type, lii.priority
				FROM location_inventory_counts lic
				INNER JOIN location_inventory_items lii ON lic.inv_item_id=lii.id
				INNER JOIN location_inventory_storerooms lis ON lis.storeroom_id=lic.storeroom_id
				INNER JOIN inventory_groups ig ON ig.id=lii.local_group_id
				INNER JOIN inventory_item_unittype iiu ON iiu.id=lic.unit_type
				WHERE lic.location_id=" . $_SESSION['loc'] . " $date AND lic.Type='Count')
				UNION ALL
				(SELECT ii.description as item,ig.description as `group`, lic.type,date_counted,time_counted,quantity,stroom_id,iiu.unit_type, lii.priority
				FROM location_inventory_counts lic
				INNER JOIN location_inventory_items lii ON lic.inv_item_id=lii.id
				INNER JOIN location_inventory_storerooms lis ON lis.storeroom_id=lic.storeroom_id
				INNER JOIN inventory_items ii ON lii.inv_item_id=ii.id
				INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
				INNER JOIN inventory_item_unittype iiu ON iiu.id=lic.unit_type
				WHERE lic.location_id=" . $_SESSION['loc'] . " $date AND lic.Type='Count')
				ORDER BY time_counted DESC, `itemgroup` ASC, item ASC, stroom_id ASC";
			
	$query = mysql_query($sql) or die(mysql_error());
		
	$pdf->SetFillColor(102, 100, 102);
	$pdf->SetTextColor(255);
	$pdf->SetFont('Arial','B',9);
    
	$header = array('Date', 'Time', 'Group', 'Item', 'Storeroom', 'P', 'Type', 'UY','Qty');
	$header = array_map('strtoupper', $header);
	$pdf->Cell(0.9,0.25,$header[0],'TRB',0,'C',true);
	$pdf->Cell(0.7,0.25,$header[1],1,0,'C',true);  
	$pdf->Cell(1.5,0.25,$header[2],1,0,'C',true);
	$pdf->Cell(3.15,0.25,$header[3],1,0,'C',true);
	$pdf->Cell(1,0.25,$header[4],'1',0,'C',true);
	$pdf->Cell(0.5,0.25,$header[5],1,0,'C',true);  
	$pdf->Cell(0.75,0.25,$header[6],1,0,'C',true);
	$pdf->Cell(0.75,0.25,$header[7],1,0,'C',true);
	$pdf->Cell(0.6,0.25,$header[8],'TLB',0,'C',true);
	$pdf->Ln();			

	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',10);
	$fill = false;

	while ($emprow = mysql_fetch_assoc($query)) 
	{ 
		$pdf->Cell(0.9,0.25,$emprow['date_counted'],'1', 0, "C");
		$pdf->Cell(0.7,0.25,$emprow['time_counted'],1, 0, "C");
		$pdf->Cell(1.5,0.25,$emprow['itemgroup'],1, 0, "L");
		$pdf->Cell(3.15,0.25,$emprow['item'],1, 0, "L");
		$pdf->Cell(1,0.25,$emprow['stroom_id'],'1', 0, "L");	
		$pdf->Cell(0.5,0.25,$emprow['priority'],1, 0, "C");
		$pdf->Cell(0.75,0.25,$emprow['type'],1, 0, "L");
		$pdf->Cell(0.75,0.25,$emprow['unit_type'],1, 0, "L");
		$pdf->Cell(0.6,0.25,$emprow['quantity'],'1', 0, "C");
		$pdf->Ln();					
	}					
	$filename= $location.'_inventory_by_date_report_'.$arrDate['date'].'.pdf';
}
//end of Inventory By Date Report

else if($_GET['download'] =='inv_item')
{
	$pdf->AddPage('L');
	$pdf->SetFont('Times','',12);

	$date = '';
	$d= 'All';
	
	if($_GET['date'] != ''){
		$date = " AND date_counted='" . mysql_real_escape_string($_GET['date']) . "'";
		$d = $_GET['date'];
	}

	$sql = "SELECT tbl.*,lis.stroom_id FROM (
                (SELECT lii.id,lii.local_item_desc as item,ig.description as `itemgroup`,lic.storeroom_id,lii.type,lic.date_counted,lic.time_counted,lic.quantity,lii.priority
                FROM location_inventory_counts lic
                INNER JOIN location_inventory_items lii ON lii.id=lic.inv_item_id
                INNER JOIN inventory_groups ig ON ig.id=lii.local_group_id
                WHERE lic.location_id=" . $_SESSION['loc'] . " AND lii.type!='global' ".$date."  AND lic.Type='Count')
                UNION ALL
                (SELECT lii.id,ii.description as item,ig.description as `group`,lic.storeroom_id,lii.type,lic.date_counted,lic.time_counted,lic.quantity,lii.priority
                FROM location_inventory_counts lic
                INNER JOIN location_inventory_items lii ON lii.id=lic.inv_item_id
                INNER JOIN inventory_items ii ON ii.id=lii.inv_item_id
                INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
                WHERE lic.location_id=" . $_SESSION['loc'] . " AND lii.type='global' $date  AND lic.Type='Count')
            ) as tbl
            LEFT JOIN location_inventory_storerooms lis ON lis.storeroom_id=tbl.storeroom_id
            ORDER BY item ASC, stroom_id ASC, date_counted DESC,time_counted ASC";
			
	$query = mysql_query($sql) or die(mysql_error());
		
	$pdf->SetFillColor(102, 100, 102);
	$pdf->SetTextColor(255);
	$pdf->SetFont('Arial','B',9);
    
	$header = array('Item', 'Storeroom', 'Date', 'Group', 'Type', 'P', 'Qty');
	$header = array_map('strtoupper', $header);
	$pdf->Cell(3,0.25,$header[0],'TRB',0,'C',true);
	$pdf->Cell(1,0.25,$header[1],1,0,'C',true);  
	$pdf->Cell(1.3,0.25,$header[2],1,0,'C',true);
	$pdf->Cell(2,0.25,$header[3],1,0,'C',true);
	$pdf->Cell(0.75,0.25,$header[4],'1',0,'C',true);
	$pdf->Cell(0.5,0.25,$header[5],1,0,'C',true);  
	$pdf->Cell(0.6,0.25,$header[6],'TLB',0,'C',true);
	$pdf->Ln();			

	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',10);
	$fill = false;

	while ($emprow = mysql_fetch_assoc($query)) 
	{ 
		$pdf->Cell(3,0.25,$emprow['item'],'1', 0, "L");
		$pdf->Cell(1,0.25,$emprow['stroom_id'],1, 0, "L");
		$pdf->Cell(1.3,0.25,$emprow['date_counted'] . " " . substr($emprow['time_counted'],0,5),1, 0, "C");
		$pdf->Cell(2,0.25,$emprow['itemgroup'],1, 0, "L");
		$pdf->Cell(0.75,0.25,$emprow['type'],'1', 0, "L");	
		$pdf->Cell(0.5,0.25,$emprow['priority'],1, 0, "C");
		$pdf->Cell(0.6,0.25,$emprow['quantity'],'1', 0, "C");
		$pdf->Ln();					
	}					
	$filename= $location.'_inventory_by_item_report_'.$arrDate['date'].'.pdf';
}
//end of Inventory By Item Report


else if($_GET['download'] =='low_invertory')
{
	$pdf->AddPage('L');
	$pdf->SetFont('Times','',12);

	$date = '';
	$d= 'All';
	
	if($_GET['date'] != ''){
		$date = " AND date_counted='" . mysql_real_escape_string($_GET['date']) . "'";
		$d = $_GET['date'];
	}

	$sql = "SELECT tbl.*,lis.stroom_id FROM (
                (SELECT lii.id,lii.local_item_desc as item,ig.description as `itemgroup`,lic.storeroom_id,lii.type,lic.date_counted,lic.time_counted,lic.quantity,lii.priority,lic.unit_type,lii.low_alert_unittype,lii.low_alert_count
                FROM location_inventory_counts lic
                INNER JOIN location_inventory_items lii ON lii.id=lic.inv_item_id
                INNER JOIN inventory_groups ig ON ig.id=lii.local_group_id
                WHERE lic.location_id=" . $_SESSION['loc'] . " AND lii.type!='global' ".$date."  AND lic.Type='Count')
                UNION ALL
                (SELECT lii.id,ii.description as item,ig.description as `group`,lic.storeroom_id,lii.type,lic.date_counted,lic.time_counted,lic.quantity,lii.priority,lic.unit_type,lii.low_alert_unittype,lii.low_alert_count
                FROM location_inventory_counts lic
                INNER JOIN location_inventory_items lii ON lii.id=lic.inv_item_id
                INNER JOIN inventory_items ii ON ii.id=lii.inv_item_id
                INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
                WHERE lic.location_id=" . $_SESSION['loc'] . " AND lii.type='global' $date  AND lic.Type='Count')
            ) as tbl
            LEFT JOIN location_inventory_storerooms lis ON lis.storeroom_id=tbl.storeroom_id
            ORDER BY item ASC, stroom_id ASC, date_counted DESC,time_counted ASC";
			
	$query = mysql_query($sql) or die(mysql_error());
		
	$pdf->SetFillColor(102, 100, 102);
	$pdf->SetTextColor(255);
	$pdf->SetFont('Arial','B',9);
    
	$header = array('Item', 'Storeroom', 'Date', 'Group', 'Type', 'P', 'Qty');
	$header = array_map('strtoupper', $header);
	$pdf->Cell(3,0.25,$header[0],'TRB',0,'C',true);
	$pdf->Cell(1,0.25,$header[1],1,0,'C',true);  
	$pdf->Cell(1.3,0.25,$header[2],1,0,'C',true);
	$pdf->Cell(1.7,0.25,$header[3],1,0,'C',true);
	$pdf->Cell(0.75,0.25,$header[4],'1',0,'C',true);
	$pdf->Cell(0.5,0.25,$header[5],1,0,'C',true);  
	$pdf->Cell(0.6,0.25,$header[6],'TLB',0,'C',true);
	$pdf->Ln();			

	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',10);
	$fill = false;

	while ($emprow = mysql_fetch_assoc($query)) 
	{ 
	if($emprow['unit_type'] == $emprow['low_alert_unittype']){
			if($emprow['quantity'] < $emprow['low_alert_count']){
		$pdf->Cell(3,0.25,$emprow['item'],'1', 0, "L");
		$pdf->Cell(1,0.25,$emprow['stroom_id'],1, 0, "L");
		$pdf->Cell(1.3,0.25,$emprow['date_counted'] . " " . substr($emprow['time_counted'],0,5),1, 0, "C");
		$pdf->Cell(1.7,0.25,$emprow['itemgroup'],1, 0, "L");
		$pdf->Cell(0.75,0.25,$emprow['type'],'1', 0, "L");	
		$pdf->Cell(0.5,0.25,$emprow['priority'],1, 0, "C");
		$pdf->Cell(0.6,0.25,$emprow['quantity'],'1', 0, "C");
		$pdf->Ln();					
	}		
	}}			
	$filename= $location.'_low_inventory_report_'.$arrDate['date'].'.pdf';
}


else if($_GET['download'] =='inv_stroom')
{
	$pdf->AddPage('L');
	$pdf->SetFont('Times','',12);
	
	$stroomField = $_GET['stroom'];

	if(!empty($stroomField))
	{
		$storeroom = " and storeroom_id='" . mysql_real_escape_string($_GET['stroom']) . "'";
	}
	else
	{
		$storeroom = '';
	}
	if($_GET['date'] != '')
	{
		$datefield = $_GET['date'];
		$date1 = " and date_counted = '".$datefield."'" ;
	}
	

	$sql = "select tbl.*,lis.stroom_id,e.first_name,e.last_name,iiu.unit_type from(
            (select ig.description as `itemgroup`,lii.local_item_desc as item,lic.date_counted,lic.storeroom_id,lic.employee_id,lii.priority,lic.quantity,lic.unit_type,lic.time_counted
            from location_inventory_counts lic
            LEFT JOIN location_inventory_items lii on lii.id = lic.inv_item_id
            LEFT JOIN inventory_groups ig ON lii.local_group_id=ig.id
            where lic.location_id = " . $_SESSION['loc'] . " $date1 and lii.type !='global' $storeroom  AND lic.Type='Count')
            union all
            (select ig.description as `group`,ii.description as item,lic.date_counted,lic.storeroom_id,lic.employee_id,lii.priority,lic.quantity,lic.unit_type,lic.time_counted
            from location_inventory_counts lic
            LEFT JOIN location_inventory_items lii on lii.id = lic.inv_item_id
            left join inventory_items ii on ii.id=lii.inv_item_id
            LEFT JOIN inventory_groups ig ON ii.inv_group_id=ig.id
            where lic.location_id = " . $_SESSION['loc'] . " $date1 and lii.type ='global' $storeroom  AND lic.Type='Count')
        )as tbl
        left join location_inventory_storerooms lis on tbl.storeroom_id=lis.storeroom_id
        left join employees e ON e.id=tbl.employee_id
        left join inventory_item_unittype iiu ON iiu.id=tbl.unit_type
        order by stroom_id asc, item asc, time_counted ASC";
		
	$query = mysql_query($sql) or die(mysql_error());
		
	$pdf->SetFillColor(102, 100, 102);
	$pdf->SetTextColor(255);
	$pdf->SetFont('Arial','B',9);
    
	$header = array('Storeroom', 'Item', 'Date', 'Time', 'Group', 'Employee', 'P', 'UT', 'Qty');
	$header = array_map('strtoupper', $header);
	$pdf->Cell(1,0.25,$header[0],'TRB',0,'C',true);
	$pdf->Cell(3.7,0.25,$header[1],1,0,'C',true);  
	$pdf->Cell(0.8,0.25,$header[2],1,0,'C',true);
	$pdf->Cell(0.6,0.25,$header[3],1,0,'C',true);
	$pdf->Cell(1.7,0.25,$header[4],'1',0,'C',true);
	$pdf->Cell(1.05,0.25,$header[5],1,0,'C',true);  
	$pdf->Cell(0.5,0.25,$header[6],'1',0,'C',true);
	$pdf->Cell(0.75,0.25,$header[7],1,0,'C',true);  
	$pdf->Cell(0.6,0.25,$header[8],'TLB',0,'C',true);
	$pdf->Ln();			

	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',10);
	$fill = false;

	while ($emprow = mysql_fetch_assoc($query)) 
	{ 
		$pdf->Cell(1,0.25,$emprow['stroom_id'],'1', 0, "L");
		$pdf->Cell(3.7,0.25,$emprow['item'],1, 0, "L");
		$pdf->Cell(0.8,0.25,$emprow['date_counted'],1, 0, "C");
		$pdf->Cell(0.6,0.25,$emprow['time_counted'],1, 0, "C");
		$pdf->Cell(1.7,0.25,$emprow['itemgroup'],'1', 0, "L");	
		$pdf->Cell(1.05,0.25,$emprow['last_name'].", ".$emprow['first_name'],1, 0, "L");
		$pdf->Cell(0.5,0.25,$emprow['priority'],'1', 0, "C");
		$pdf->Cell(0.75,0.25,$emprow['unit_type'],1, 0, "L");
		$pdf->Cell(0.6,0.25,$emprow['quantity'],'1', 0, "C");
		$pdf->Ln();					
	}				
	$filename= $location.'_inventory_by_storeroom_report_'.$arrDate['date'].'.pdf';
}
else if($_GET['download'] =='inv_vendor')
{
	
	$pdf->AddPage('L');
	$pdf->SetFont('Times','',12);
	
	$stroomField = $_GET['stroom'];
	$fvendor = $_GET['fvendor'];

	if(!empty($stroomField))
	{
		$storeroom = " and storeroom_id='" . mysql_real_escape_string($_GET['stroom']) . "'";
	}
	else
	{
		$storeroom = '';
	}
	if($_GET['date'] != '')
	{
		$datefield = $_GET['date'];
		$date = " and date_counted = '".$datefield."'" ;
	}
	else
	{
		$date = '';// date('Y-m-d');
	}
	$vendor_where1='';
	$vendor_where2='';
	if($fvendor!=''){
		$vendor_where1 =" AND ii.vendor_default = '". $fvendor ."'";
		$vendor_where2 =" AND lii.default_vendor = '". $fvendor ."'";
	}

	$sql = "select tbl.*,lis.stroom_id,e.first_name,e.last_name,iiu.unit_type from(
            (select ig.description as `itemgroup`,lii.local_item_desc as item,lic.date_counted,lic.storeroom_id,lic.employee_id,lii.priority,lic.quantity,lic.unit_type,lic.time_counted,ve.name as vendor_name
            from location_inventory_counts lic
            LEFT JOIN location_inventory_items lii on lii.id = lic.inv_item_id
            LEFT JOIN inventory_groups ig ON lii.local_group_id=ig.id
			LEFT JOIN vendors ve ON ve.id=lii.default_vendor
            where lic.location_id = " . $_SESSION['loc'] . " $date and lii.type !='global' $storeroom  AND lic.Type='Count' $vendor_where2)
            union all
            (select ig.description as `group`,ii.description as item,lic.date_counted,lic.storeroom_id,
			lic.employee_id,lii.priority,lic.quantity,lic.unit_type,lic.time_counted,ve.name as vendor_name
            from location_inventory_counts lic
            LEFT JOIN location_inventory_items lii on lii.id = lic.inv_item_id
            left join inventory_items ii on ii.id=lii.inv_item_id
            LEFT JOIN inventory_groups ig ON ii.inv_group_id=ig.id
			LEFT JOIN vendors ve ON ve.id=ii.vendor_default
            where lic.location_id = " . $_SESSION['loc'] . " $date and lii.type ='global' $storeroom  AND lic.Type='Count' $vendor_where1)
        )as tbl
        left join location_inventory_storerooms lis on tbl.storeroom_id=lis.storeroom_id
        left join employees e ON e.id=tbl.employee_id
        left join inventory_item_unittype iiu ON iiu.id=tbl.unit_type
        order by  stroom_id asc, item asc, time_counted";
		
	$query = mysql_query($sql) or die($sql.'<br>'.mysql_error());
		
	$pdf->SetFillColor(102, 100, 102);
	$pdf->SetTextColor(255);
	$pdf->SetFont('Arial','B',8);
    
	$header = array('Storeroom','Vendor','Item', 'Date', 'Time', 'Group', 'Employee', 'P', 'UT', 'Qty');
	$header = array_map('strtoupper', $header);
	$pdf->Cell(0.755,0.25,$header[0],'TRB',0,'C',true);//0.9
	$pdf->Cell(1.3,0.25,$header[1],'TRB',0,'C',true);//1.6
	$pdf->Cell(2.6,0.25,$header[2],1,0,'C',true);  
	$pdf->Cell(0.8,0.25,$header[3],1,0,'C',true);
	$pdf->Cell(0.7,0.25,$header[4],1,0,'C',true);
	$pdf->Cell(1.65,0.25,$header[5],'1',0,'C',true);
	$pdf->Cell(1.05,0.25,$header[6],1,0,'C',true);  
	$pdf->Cell(0.5,0.25,$header[7],'1',0,'C',true);
	$pdf->Cell(0.75,0.25,$header[8],1,0,'C',true);  
	$pdf->Cell(0.6,0.25,$header[9],'TLB',0,'C',true);
	$pdf->Ln();				

	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',8);
	$fill = false;

	while ($emprow = mysql_fetch_assoc($query)) 
	{ 
		$pdf->Cell(0.755,0.25,$emprow['stroom_id'],'1', 0, "L");
		$pdf->Cell(1.3,0.25,$emprow['vendor_name'],'1', 0, "L");			
		$pdf->Cell(2.6,0.25,ucfirst(strtolower(substr($emprow['item'],0,44))),1, 0, "L");
		$pdf->Cell(0.8,0.25,$emprow['date_counted'],1, 0, "C");
		$pdf->Cell(0.7,0.25,substr($emprow['time_counted'],0,5),1, 0, "C");
		$pdf->Cell(1.65,0.25,$emprow['itemgroup'],'1', 0, "L");	
		$pdf->Cell(1.05,0.25,$emprow['last_name'].", ".$emprow['first_name'],1, 0, "L");
		$pdf->Cell(0.5,0.25,$emprow['priority'],'1', 0, "C");
		$pdf->Cell(0.75,0.25,$emprow['unit_type'],1, 0, "L");
		$pdf->Cell(0.6,0.25,$emprow['quantity'],'1', 0, "C");
		$pdf->Ln();					
	}					
	$filename= $location.'_inventory_by_vendor_report_'.$arrDate['date'].'.pdf';
}

//end of Inventory By Storeroom Report

else if($_GET['download'] =='inv_emp')
{
	$pdf->AddPage('L');
	$pdf->SetFont('Times','',12);
	
	$empField = $_GET['emp'];

	$date = date('Y-m-d');
	if(!empty($empField))
	{
		$e = mysql_real_escape_string($_GET['emp']);
		$empq = ' AND lic.employee_id=' .$e;
	}
	else
	{
		$empq = '';
	}

	$sql = "(SELECT date_counted,ii.description as item,ig.description as `itemgroup`,e.first_name,e.last_name,lii.priority,lic.type,lic.quantity,iiu.unit_type,lis.stroom_id
            FROM location_inventory_counts lic
            INNER JOIN location_inventory_items lii ON lic.inv_item_id=lii.id
            INNER JOIN inventory_items ii ON lii.inv_item_id=ii.id
            INNER JOIN inventory_groups ig ON ig.id=ii.inv_group_id
            INNER JOIN employees e ON e.id=lic.employee_id
            INNER JOIN inventory_item_unittype iiu ON iiu.id=lic.unit_type
            INNER JOIN location_inventory_storerooms lis ON lis.storeroom_id=lic.storeroom_id
            WHERE lii.status = 'active'" . $empq . " AND lic.location_id = " . $_SESSION['loc'] . " AND lic.Type='Count')
            UNION ALL
            (SELECT date_counted,lii.local_item_desc as item,ig.description as `group`,e.first_name,e.last_name,lii.priority,lic.type,lic.quantity,iiu.unit_type,lis.stroom_id
            FROM location_inventory_counts lic
            INNER JOIN location_inventory_items lii ON lic.inv_item_id=lii.id
            INNER JOIN inventory_groups ig ON ig.id=lii.local_group_id
            INNER JOIN employees e ON e.id=lic.employee_id
            INNER JOIN inventory_item_unittype iiu ON iiu.id=lic.unit_type
            INNER JOIN location_inventory_storerooms lis ON lis.storeroom_id=lic.storeroom_id
            WHERE lii.status = 'active'" . $empq . " AND lic.location_id = " . $_SESSION['loc'] . " AND lic.Type='Count')
            ORDER BY last_name ASC, first_name ASC";
	$query = mysql_query($sql) or die(mysql_error());
		
	$pdf->SetFillColor(102, 100, 102);
	$pdf->SetTextColor(255);
	$pdf->SetFont('Arial','B',9);
    
	$header = array('Date', 'LastName', 'FirstName', 'Item', 'Group', 'Storeroom', 'P', 'Type', 'UT', 'Qty');
	$header = array_map('strtoupper', $header);
	$pdf->Cell(0.8,0.25,$header[0],'TRB',0,'C',true);
	$pdf->Cell(1,0.25,$header[1],1,0,'C',true);  
	$pdf->Cell(1,0.25,$header[2],1,0,'C',true);
	$pdf->Cell(3.1,0.25,$header[3],1,0,'C',true);
	$pdf->Cell(1,0.25,$header[4],'1',0,'C',true);
	$pdf->Cell(1,0.25,$header[5],1,0,'C',true);  
	$pdf->Cell(0.5,0.25,$header[6],'1',0,'C',true);
	$pdf->Cell(0.75,0.25,$header[7],1,0,'C',true);  
	$pdf->Cell(0.75,0.25,$header[8],'1',0,'C',true);
	$pdf->Cell(0.6,0.25,$header[9],'TLB',0,'C',true);
	$pdf->Ln();			

	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',10);
	$fill = false;

	while ($emprow = mysql_fetch_assoc($query)) 
	{ 
		$pdf->Cell(0.8,0.25,$emprow['date_counted'],'1', 0, "C");
		$pdf->Cell(1,0.25,$emprow['last_name'],1, 0, "L");
		$pdf->Cell(1,0.25,$emprow['first_name'],1, 0, "L");
		$pdf->Cell(3.1,0.25,$emprow['item'],1, 0, "L");
		$pdf->Cell(1,0.25,$emprow['itemgroup'],'1', 0, "L");	
		$pdf->Cell(1,0.25,$emprow['stroom_id'],1, 0, "L");
		$pdf->Cell(0.5,0.25,$emprow['priority'],'1', 0, "C");
		$pdf->Cell(0.75,0.25,$emprow['type'],'1', 0, "L");
		$pdf->Cell(0.75,0.25,$emprow['unit_type'],1, 0, "L");
		$pdf->Cell(0.6,0.25,$emprow['quantity'],'1', 0, "C");
		$pdf->Ln();					
	}						
	$filename= $location.'_inventory_by_employee_report_'.$arrDate['date'].'.pdf';
}
//end of Inventory By Employee Report

else if($_GET['download'] =='order_group')
{
	$pdf->AddPage('L');
	$pdf->SetFont('Times','',12);
	
	$groupfield = $_GET['group'];

	if(!empty($groupfield)){
    	$groupl = " AND lii.local_group_id=" . mysql_real_escape_string($_GET['group']);
    	$groupg = " AND ii.inv_group_id=" . mysql_real_escape_string($_GET['group']);
	}else{
    	$groupl = '';
    	$groupg = '';
	}

	$sql = "SELECT tbl.item,tbl.priority,tbl.datetime,tbl.`group`,e.first_name,e.last_name,iiu.unit_type,tbl.required_quantity FROM (
                (SELECT lion.priority,lion.`datetime`,lion.employee_id,lion.unit_type,lion.required_quantity,ig.description as `group`,lii.local_item_desc as item
                FROM location_inventory_order_needed lion
                LEFT JOIN location_inventory_items lii ON lii.id=lion.inv_item_id
                LEFT JOIN inventory_groups ig ON ig.id=lii.local_group_id
                WHERE lii.type != 'global' AND lion.location_id=" . $_SESSION['loc'] . " $groupl)
                UNION ALL
                (SELECT lion.priority,lion.`datetime`,lion.employee_id,lion.unit_type,lion.required_quantity,ig.description as `group`,ii.description as item
                FROM location_inventory_order_needed lion
                LEFT JOIN location_inventory_items lii ON lii.id=lion.inv_item_id
                LEFT JOIN inventory_items ii ON ii.id=lii.inv_item_id
                LEFT JOIN inventory_groups ig ON ig.id=ii.inv_group_id
                WHERE lii.type='global' AND lion.location_id=" . $_SESSION['loc'] . " $groupg)
            ) as tbl
            LEFT JOIN employees e ON e.id=tbl.employee_id
            LEFT JOIN inventory_item_unittype iiu ON iiu.id=tbl.unit_type"; // ORDER BY ".$orderBy;
		
	$query = mysql_query($sql) or die(mysql_error());
		
	$pdf->SetFillColor(255,255,255);
	$pdf->SetTextColor(0);	
		
	/*$pdf->SetFillColor(102, 100, 102);
	$pdf->SetTextColor(255);*/
	$pdf->SetFont('Arial','B',9);
    
	$header = array('Group','Item','Unit Type','Qty','P','Datetime','Employee');
	$header = array_map('strtoupper', $header);
	$pdf->Cell(2,0.25,$header[0],'1',0,'C',true);
	$pdf->Cell(3,0.25,$header[1],1,0,'C',true);  
	$pdf->Cell(0.75,0.25,$header[2],1,0,'C',true);
	$pdf->Cell(0.6,0.25,$header[3],1,0,'C',true);
	$pdf->Cell(0.7,0.25,$header[4],'1',0,'C',true);
	$pdf->Cell(1.5,0.25,$header[5],1,0,'C',true);  
	$pdf->Cell(2.02,0.25,$header[6],'1',0,'C',true);
	$pdf->Ln();			

	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',10);
	$fill = false;

	while ($emprow = mysql_fetch_assoc($query)) 
	{ 
		$pdf->Cell(2,0.25,$emprow['group'],'1', 0, "L");
		$pdf->Cell(3,0.25,$emprow['item'],1, 0, "L");
		$pdf->Cell(0.75,0.25,$emprow['unit_type'],1, 0, "L");
		$pdf->Cell(0.6,0.25,$emprow['required_quantity'],1, 0, "C");
		$pdf->Cell(0.7,0.25,ucfirst($emprow['priority']),'1', 0, "C");	
		$pdf->Cell(1.5,0.25,$emprow['datetime'],1, 0, "C");
		$pdf->Cell(2.02,0.25,substr($emprow['last_name'].", ".$emprow['first_name'],0,20),'1', 0, "L");
		$pdf->Ln();					
	}						
	$filename= $location.' - Orders by Group Report - '.$arrDate['date'].'.pdf';
}
//end of Orders By Group Report
//->juni [REQ_014] -> backoffice_reports_receivable_ageing.php
 else if($_GET['download'] =='receivable_ageing'){	
	//
	$pdf->AddPage('L');
	$pdf->SetFont('Times','',12);
	
	$orderField = $_GET['orderfield'];
	$orderType = $_GET['type'];
	$orderBy = ' ORDER BY ';
	if(!empty($orderField)){
		$orderBy .= $orderField;
	}else{
		$orderBy .= " company_name";
	}
	if(!empty($orderType)){
		$orderBy .=	' '.$orderType;
	}else{
		$orderBy .= ' ASC';
	}
	//
	$detail_level = 'report_summary';//default 
	if(isset($_GET['detail_level'])&& $_GET['detail_level'] != '' )
		$detail_level = $_GET['detail_level'];

	$pdf->SetFillColor(102, 100, 102);
	$pdf->SetTextColor(255);
	$pdf->SetFont('Arial','B',11);
    
/* 	if ($_GET['detail_level'] =='report_summary') {
	} else if($_GET['detail_level'] =='report_summary_balance'){	
		$txtprepage = 'Receivable Ageing - Summary report only with balance';
	} else if($_GET['detail_level'] =='report_detail'){	
		$txtprepage = 'Receivable Ageing - Detail report';
	} else if($_GET['detail_level'] =='report_all_detail'){	
		$txtprepage = 'Receivable Ageing - All detail report';
	} */
	
	if ($detail_level=='report_summary' || $detail_level=='report_summary_balance' ) {
		$pdf->SetFont('Arial','',9);
		$header = array('Company', 'Current', '30-60 Days', '61-90 Days','90-120 Days','Over 120 Days','Amount due');
		$header = array_map('strtoupper', $header);
		
		$pdf->Cell(2,0.25,$header[0],'TLRB',0,'C',true);
		$pdf->Cell(1.3,0.25,$header[1],1,0,'C',true);  
		$pdf->Cell(1.3,0.25,$header[2],1,0,'C',true);
		$pdf->Cell(1.3,0.25,$header[3],1,0,'C',true);
		$pdf->Cell(1.3,0.25,$header[4],1,0,'C',true);
		$pdf->Cell(1.5,0.25,$header[5],1,0,'C',true);
		$pdf->Cell(1.5,0.25,$header[6],1,0,'C',true);
		$pdf->Ln();			

		$pdf->SetFillColor(224,235,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',10);
		$fill = false;
		
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
			}} 		
			if ($detail_level!='report_summary_balance' || ($detail_level=='report_summary_balance' && $total_amt != 0)) {
				$pdf->Cell(2,0.25,$cRow['company_name'],'TLRB', 0, "L");
				$pdf->Cell(1.3,0.25,formatNumberWithCurrency($currency,$due_0_29,2),1, 0, "R");
				$pdf->Cell(1.3,0.25,formatNumberWithCurrency($currency,$due_30_60,2),1, 0, "R");
				$pdf->Cell(1.3,0.25,formatNumberWithCurrency($currency,$due_61_90,2),1, 0, "R");
				$pdf->Cell(1.3,0.25,formatNumberWithCurrency($currency,$due_90_120,2),1, 0, "R");			
				$pdf->Cell(1.5,0.25,formatNumberWithCurrency($currency,$due_over_120,2),1, 0, "R");			
				$pdf->Cell(1.5,0.25,formatNumberWithCurrency($currency,$total_amt,2),1, 0, "R");			
				$pdf->Ln();				
			} //end if ($detail_level!='report_summary_balance' && ($detail_level=='report_summary_balance' && $total_amt != 0)) {
		}}
	} else if ($detail_level=='report_detail' || $detail_level=='report_all_detail' )  {
 		$pdf->SetFont('Arial','',9);
		$header = array('Name', 'Represensative', 'Phone Number', 'State','Country','Balance');
		$header = array_map('strtoupper', $header);
		$pdf->Cell(2,0.25,$header[0],'TLRB',0,'C',true);//Name
		$pdf->Cell(2,0.25,$header[1],1,0,'C',true);  //Represensative
		$pdf->Cell(2,0.25,$header[2],1,0,'C',true); //Phone Number
		$pdf->Cell(1.5,0.25,$header[3],1,0,'C',true); //State
		$pdf->Cell(1.5,0.25,$header[4],1,0,'C',true); //Country
		$pdf->Cell(1.2,0.25,$header[5],1,0,'C',true); //Balance
		$pdf->Ln();			

		$pdf->SetFillColor(224,235,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('Arial','',10);
		$fill = false; 
		
		
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
			//
			$query_rec = "SELECT cr.company_receivables_id as c_id, com.name as compani_name,com.company_id, com.representative,com.phone,
				st.name as state_name,cou.name as country_name,
				COALESCE((CASE 
					WHEN crp.payment <> 0 THEN 0
					ELSE cr.amount
				END) -sum(crp.payment) , 0) as amount,l.currency_symbol  
			FROM companies as com 
				INNER JOIN companies_receivables as cr ON cr.company_id = com.company_id 
				LEFT OUTER JOIN companies_receivables_payments crp ON cr.company_id = com.company_id AND crp.companies_receivables_id=cr.company_receivables_id
				INNER JOIN locations l ON l.id=cr.location_id
				LEFT JOIN states as st ON st.id = com.state 
				LEFT JOIN countries as cou ON cou.id = com.country 
			WHERE com.company_id ='".$cRow['company_id']."'
			GROUP BY cr.company_id";
			$row_rec = mysql_query($query_rec) or die(mysql_error());	
			while($res_rec= mysql_fetch_array($row_rec)){
				$currency = $res_rec["currency_symbol"];	
				//juni -> temp method to get balance //TODO: fix me
				$balance = 0;
				$sql = "SELECT (
					SELECT COALESCE(SUM(cr.amount),0)-COALESCE(SUM(cr.payment),0) FROM companies_receivables cr 
						WHERE cr.company_id = '".$cRow['company_id']."'
						AND cr.location_id ='".$_SESSION['loc']."'
						AND cr.amount<>cr.payment
				) - (
					SELECT COALESCE(SUM(crp.payment),0)-COALESCE(SUM(crp.settled_payment),0)
				FROM companies_receivables_payments crp 
					WHERE crp.company_id = '".$cRow['company_id']."'
					AND crp.location_id ='".$_SESSION['loc']."'
					AND crp.payment<>crp.settled_payment
				) AS balance";

				$res = mysql_query($sql) or die(mysql_error());
				if ($row = mysql_fetch_array($res))
					$balance = $row['balance'];
				else 
					$balance  = 0;		
				$pdf->Cell(2,0.25,$cRow['company_name'],'TRBL', 0, "L");
				$pdf->Cell(2,0.25,$res_rec['representative'],1, 0, "L");
				$pdf->Cell(2,0.25,$res_rec['phone'],1, 0, "L");
				$pdf->Cell(1.5,0.25,$res_rec['state_name'],1, 0, "L");
				$pdf->Cell(1.5,0.25,$res_rec['country_name'],1, 0, "L");				
				$pdf->Cell(1.2,0.25,formatNumberWithCurrency($currency,$balance,2),1, 0, "R");				
				$pdf->Ln();					
			} 	
			//
			$pdf->SetFont('Arial','',9);
			$header = array('Company', 'Date', 'Source', 'Type','Reference','Name','Employee','Amount','Payment','Settled','Balance');
			$header = array_map('strtoupper', $header);
			
			$pdf->Cell(1.5,0.25,$header[0],'TRBL',0,'C',true);//Company
			$pdf->Cell(1.5,0.25,$header[1],1,0,'C',true);  //Date
			$pdf->Cell(0.6,0.25,$header[2],1,0,'C',true); //Source
			$pdf->Cell(0.6,0.25,$header[3],1,0,'C',true); //Type
			$pdf->Cell(0.7,0.25,$header[4],1,0,'C',true); //Reference
			$pdf->Cell(1,0.25,$header[5],1,0,'C',true); //Name
			$pdf->Cell(1.5,0.25,$header[6],1,0,'C',true); //Employee
			$pdf->Cell(0.7,0.25,$header[7],1,0,'C',true); //Amount
			$pdf->Cell(0.7,0.25,$header[8],1,0,'C',true);
			$pdf->Cell(0.7,0.25,$header[9],1,0,'C',true);
			$pdf->Cell(0.7,0.25,$header[10],1,0,'C',true);
			$pdf->Ln();			

			$pdf->SetFillColor(224,235,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('Arial','',10);
			$fill = false;			
			//
			$total_amt = "";
			$currency  = "";      	
			if ($detail_level=='report_detail') 	{
				$activeWhere = " cr.balance <> 0 ";
				$activeWhereCRP = " (crp.balance <> 0 or crp.payment <> 0) ";
			} else if ($detail_level=='report_all_detail' ) {
				//$activeWhere = " cr.balance = 0 ";
				$activeWhere = " 1 = 1 "; //i want all records ;)
				$activeWhereCRP = " 1 = 1 ";  //i want all records ;)
				//$activeWhereCRP = " (cr.balance = 0 or crp.payment = 0) "; 
			} else
				;
				;
		
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
				AND cr.company_id='".$cRow['company_id']."' 
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
			 ORDER BY date_time DESC";	
			//echo $query;
			$res = mysql_query($query) or die(mysql_error());
			if(mysql_num_rows($res)>0){
			while($row = mysql_fetch_array($res)){
				if ($row['row_id'] == 0)
					continue;
				if ($detail_level!='report_all_detail' ) {
					if ($row['amount']-$row['payment'] == 0)//30.08.2014
						continue;								
				}
				$reference = null;
				if($row['hotelacct_id']!=""){
					$row['type']="Hotel";
					$query_q = mysql_fetch_array(mysql_query("SELECT c.name  as client_name 
						from location_hotelacct as lh 
							INNER JOIN location_hotelacct_client lhc on lh.account_id=lhc.account_id and lh.location_id=lhc.location_id
							LEFT JOIN clients as c on c.id = lhc.client_id
					where lh.account_id='".$row['hotelacct_id']."' AND lh.location_id =".$_SESSION['loc']));											
					$row['client_name'] = $query_q['client_name'];
					$reference = $row['hotelacct_id'];
				}else if($row['client_sales_id']!=""){
					$row['type']="Sales";
					$query_q = mysql_fetch_array(mysql_query("SELECT c.name as client_name from client_sales as cs LEFT JOIN clients as c on c.id = cs.client_id where cs.client_sales_id='".$row['client_sales_id']."' AND cs.location_id =".$_SESSION['loc']));
					$row['client_name'] = $query_q['client_name'];
					$reference = $row['client_sales_id'];
				}else if($row['client_orders_id']!=""){
					$row['type']="Pos";
					$query_q = mysql_fetch_array(mysql_query("SELECT c.name as client_name from client_orders as cs LEFT JOIN clients as c on c.id = cs.client_id where cs.id='".$row['client_orders_id']."' AND cs.location_id =".$_SESSION['loc']));
					$row['client_name'] = $query_q['client_name'];
					$reference = $row['client_orders_id'];
				}
				$row["balance"]=$row["currency_symbol"].$row["balance"];	
				$total_amt = $total_amt + ($row['amount']-$row['payment']);
				//echo $total_amt;
				$currency = $row["currency_symbol"];	
				if ($row['amount']==0)
					$row['amount'] = '';
				if ($row['payment']==0)
					$row['payment'] = '';			
				if ($row['settled_payment']==0)
					$row['settled_payment'] = '';						
				if ($row['payment']!='')
					$payment = "(".formatNumberWithCurrency($currency,$row['payment'],2).")";
				else
					$payment = '';		
				if ($row['settled_payment']!='')
					//$settled_payment = "(".formatNumberWithCurrency($currency,$row['settled_payment'],2).")";
					$settled_payment = formatNumberWithCurrency($currency,$row['settled_payment'],2); //-> BP - Receivables - Sep 06 - SF10.jpg
				else
					$settled_payment = '';		
				if ($row['source'] == 'Payments') {//stupid method to have payments not display amount -> Recaivable Statement - Aug 31 - SF52.jpg
					$amount = '';
				} else {
					$amount = $row['amount'];	
					$payment = ''; //-> payments should be blank for charges BP - Receivables Report - Sep 06 - SF20.jpg & BP - Receivables Report - Sep 06 - SF22.jpg
				}											

			
				$pdf->Cell(1.5,0.25,$row['company_name'],'TRBL', 0, "L");
				$pdf->Cell(1.5,0.25,$row['date_time'],1, 0, "L");
				$pdf->Cell(0.6,0.25,$row['source'],1, 0, "L");
				$pdf->Cell(0.6,0.25,$row['type'],1, 0, "L");
				$pdf->Cell(0.7,0.25,$reference,1, 0, "L");			
				$pdf->Cell(1,0.25,$row['client_name'],1, 0, "L");			
				$pdf->Cell(1.5,0.25,$row['employee'],1, 0, "L");			
				$pdf->Cell(0.7,0.25,formatNumberWithCurrency($currency,$amount,2),1, 0, "R");			
				$pdf->Cell(0.7,0.25,$payment,1, 0, "R");			
				$pdf->Cell(0.7,0.25,$settled_payment,1, 0, "R");			
				$pdf->Cell(0.7,0.25,formatNumberWithCurrency($currency,($row['amount']-$row['payment']),2),1, 0, "R");			
				$pdf->Ln();
			}}
		}}		
	} else //can't be ;)
		;
	
	$arr_details_level=array(
		"report_summary"=>"Summary",
		"report_summary_balance"=>"Summary_only_with_balance",
		"report_detail"=>"Details",
		"report_all_detail"=>"Details with settlements"
	);
	
	if($detail_level!="")
	{
		$str_detail_level=$arr_details_level[$detail_level]."_report";
	}
	else
	{
		$str_detail_level="";
	}
	
	$filename= $location.'_receivable_ageing_'.$str_detail_level.'_'.$arrDate['date'].'.pdf';
}
//<-juni [REQ_014]

else
{
	$pdf=new PDF("L","in","A4");
	$pdf->SetMargins(0.5,0.5,0.5);
	$pdf->AddPage('L');
	$pdf->SetFont('Times','',12);
	
	$datefield = $_GET['date'];

	if(!empty($datefield)){
		$id= mysql_real_escape_string($_GET['date']);
		$q = " AND liliv.datetime='".$id."'";
		//$q = " AND liliv.id=" . $id;
		$o = '';
	}else{
		$q = " AND lili.location_id = " . $_SESSION['loc'];
		$o = " ORDER BY datetime DESC LIMIT 1";
	}

	$sql = "(SELECT lili.area,lili.shelflife,lili.storage_unit,lili.par,lili.quality_spec, lili.temp_req,
                   lis.stroom_id storeroom,ii.description item, iiu.unit_type, liliv.quantity_unit_type,
                   liliv.quantity,liliv.temp_verified,liliv.quality,liliv.comments,iiu2.unit_type as type2, liliv.datetime
    	        FROM location_inventory_line_items lili
        	    LEFT JOIN location_inventory_items lii ON lii.id=lili.inv_item_id
            	LEFT JOIN inventory_items ii ON ii.id=lii.inv_item_id
            	LEFT JOIN inventory_item_unittype iiu ON iiu.id=lili.par_unit_type
            	LEFT JOIN location_inventory_storerooms lis ON lis.storeroom_id=lili.storeroom_id
            	LEFT JOIN location_inventory_line_items_verify liliv ON liliv.line_item_id=lili.id
            	LEFT JOIN inventory_item_unittype iiu2 ON iiu.id=liliv.quantity_unit_type
            	WHERE lii.type='global'" . $q . ")
            	UNION ALL
            	(SELECT lili.area,lili.shelflife,lili.storage_unit,lili.par,lili.quality_spec, lili.temp_req,
                   lis.stroom_id storeroom,lii.local_item_desc as item, iiu.unit_type, liliv.quantity_unit_type,
                   liliv.quantity,liliv.temp_verified,liliv.quality,liliv.comments,iiu2.unit_type as type2, liliv.datetime
            	FROM location_inventory_line_items lili
            	LEFT JOIN location_inventory_items lii ON lii.id=lili.inv_item_id
            	LEFT JOIN inventory_item_unittype iiu ON iiu.id=lili.par_unit_type
            	LEFT JOIN location_inventory_storerooms lis ON lis.storeroom_id=lili.storeroom_id
            	LEFT JOIN location_inventory_line_items_verify liliv ON liliv.line_item_id=lili.id
            	LEFT JOIN inventory_item_unittype iiu2 ON iiu.id=liliv.quantity_unit_type
            	WHERE lii.type!='global'" . $q . ")" . $o;
		
	$query = mysql_query($sql) or die(mysql_error());
		
	$pdf->SetFillColor(102, 100, 102);
	$pdf->SetTextColor(255);
	$pdf->SetFont('Arial','B',9);
    
	$header = array('Storeroom', 'Area', 'Item', 'ShelfLife', 'Storage', 'ParUnits', 'Par', 'Quality', 'ReqTemp', 'QtyUnits', 'Qty', 'Temp', 'Quality', 'Comment');
	$header = array_map('strtoupper', $header);
	
	$pdf->Cell(1,0.25,$header[0],'TRB',0,'C',true);
	$pdf->Cell(0.5,0.25,$header[1],1,0,'C',true);  
	$pdf->Cell(1,0.25,$header[2],1,0,'C',true);
	$pdf->Cell(0.75,0.25,$header[3],1,0,'C',true);
	$pdf->Cell(0.75,0.25,$header[4],'1',0,'C',true);
	$pdf->Cell(0.75,0.25,$header[5],1,0,'C',true);  
	$pdf->Cell(0.5,0.25,$header[6],'1',0,'C',true);
	$pdf->Cell(0.5,0.25,$header[7],1,0,'C',true);  
	$pdf->Cell(0.75,0.25,$header[8],'1',0,'C',true);
	$pdf->Cell(0.75,0.25,$header[9],'1',0,'C',true);
	$pdf->Cell(0.5,0.25,$header[10],'1',0,'C',true);
	$pdf->Cell(0.5,0.25,$header[11],'1',0,'C',true);
	$pdf->Cell(0.75,0.25,$header[12],'1',0,'C',true);
	$pdf->Cell(0.75,0.25,$header[13],'TLB',0,'C',true);
	$pdf->Ln();			

	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('Arial','',10);
	$fill = false;

	while ($emprow = mysql_fetch_assoc($query)) 
	{ 
		$pdf->Cell(1,0.25,$emprow['storeroom'],'TRB', 0, "L");
		$pdf->Cell(0.5,0.25,$emprow['area'],1, 0, "L");
		$pdf->Cell(1,0.25,$emprow['item'],1, 0, "L");
		$pdf->Cell(0.75,0.25,$emprow['shelflife'],1, 0, "L");
		$pdf->Cell(0.75,0.25,$emprow['storage_unit'],'1', 0, "L");	
		$pdf->Cell(0.75,0.25,$emprow['unit_type'],1, 0, "L");
		$pdf->Cell(0.5,0.25,$emprow['par'],'1', 0, "L");
		$pdf->Cell(0.5,0.25,$emprow['quality_spec'],'1', 0, "L");
		$pdf->Cell(0.75,0.25,$emprow['temp_req'],1, 0, "L");
		$pdf->Cell(0.75,0.25,$emprow['type2'],'1', 0, "L");
		$pdf->Cell(0.5,0.25,$emprow['quantity'],'1', 0, "L");
		$pdf->Cell(0.5,0.25,$emprow['temp_verified'],'1', 0, "L");
		$pdf->Cell(0.75,0.25,$emprow['quality'],'1', 0, "L");
		$pdf->Cell(0.75,0.25,$emprow['comments'],'TLB', 0, "L");
		$pdf->Ln();					
	}					
	$filename= $location.'_line_check_report_'.$arrDate['date'].'.pdf';


//end of Line Check Report
}



//start to create PDF
$path = 'temp_img/';
$fullpath = $path.$filename; 
$pdf->Output($fullpath);


/*
header('Content-Transfer-Encoding: binary');  // For Gecko browsers mainly
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($fullpath)) . ' GMT');
header('Accept-Ranges: bytes');  // For download resume
header('Content-Length: ' . filesize($fullpath));  // File size
header('Content-Encoding: none');
header('Content-Type: application/pdf');  // Change this mime type if the file is not PDF
header('Content-Disposition: attachment; filename=' . $filename);  // Make the browser display the Save As dialog
readfile($fullpath);
*/

include_once ('libraries/phpmailer/class.phpmailer.php');
include_once ('libraries/phpmailer/language/phpmailer.lang-en.php');

$targetmail = $_GET['mailaddress'];
$targetname = $_GET['destname'];
		
		$mail = new PHPmailer();
		//$mail->IsSMTP(); // telling the class to use SMTP
		$mail->SetLanguage("en", "phpmailer/language");
		$mail->From = "reports@softpointcloud.com";
		$mail->FromName = "Soft Point";
		$mail->Subject = $txtprepage." Report";
		//$mail->SMTPAuth  =  "true";
		//$mail->SMTPDebug  = 1;
		$mail->Body = $content;
		$mail->IsHTML(true);
		$mail->AddAttachment($fullpath);
        $mail->AddReplyTo('info@softpointcloud.com','Soft Point');
		$mail->AddAddress($targetmail,$targetname);
		if($mail->Send()){
			$sendResult = "Report Successfully sent to the address";
		}else{
			//$sendResult = $mail->ErrorInfo;
			$sendResult = 'An error occurred when sending your information. Please try again. ';
		}
		
		
echo json_encode($sendResult);

?>