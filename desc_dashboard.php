<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

// if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
//     ob_start("ob_gzhandler");
// } else {
//     ob_start();
// }

require_once 'require/security.php';
// include 'config/accessConfig.php';


// $sqltest=mysql_query("select vendor_id from vendor_distribution");

// print_r($sqltest);
// echo "hi";
// die;


// $httpp = 'http://';
// if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
//     $httpp = 'https://';
// }


// $API = API;
// if ($httpp != 'https://') {
//     if (strpos($API, 'www.') == FALSE) {
//         if (strpos($API, '://')) {
//             $APIs = explode('://', $API);
//             $API = $APIs[0] . '://www.' . $APIs[1];
//         }
//         //$API = 'www.'. $API;
//     }
// }
/*if(!isset($_REQUEST['lang']) || $_REQUEST['lang']=="")	
{
	$url="";
	if($_SERVER['HTTPS']=="on")
	{
		$url=str_replace('http:/','https://',str_replace('//','/',$API.$_SERVER['PHP_SELF']));
	}
	else
	{
		$url=str_replace('http:/','http://',str_replace('//','/',$API.$_SERVER['PHP_SELF']));
	}
	$url.="?".$_SERVER['QUERY_STRING']."&lang=".$_SESSION['lang']."#googtrans(en|".$_SESSION['lang'].")";
	header("Location: ".$url);
	exit;
}

*  @modified Ionut Irofte - juniorionut @ elance
*  @version $Id: changes.sql ,v 1.0 2:01 PM 6/23/2014 juni $
*  -> [req 1.32  - 30.06.2014]
		-> Code Indentation
		-> Make changes as requested
		-> organise all -> too many sql's, query, fetch array , confusing... created new function in jcustom.php
		-> Too many chages & code removed to keep track in this file. Compare with original in backup folder to have a list of changes
*/
//include_once 'includes/session.php';
//include_once '../internalaccess/url.php';
//include_once("../internalaccess/connectdb.php");
include_once 'includes/jcustom.php';
/**
 * set menu active options for this page
 */
$customerHead = "active";
$customerDropDown = "display:block;";
$customerMenu1 = "active";
//
$location_id = $_SESSION['loc'];
//-> juni location data
$currencySign = "$";
$access_restaurant = "no";
$access_retail = "no";
$access_hotel = "no";
$noOfRestaurants = 0;
$noOfHotels = 0;
$noOfRetail = 0;
$sql = "SELECT currency_symbol,access_pos,access_hotel,access_register FROM locations WHERE id='$location_id'";
$results = mysql_query($sql) or die(mysql_error());
if (mysql_affected_rows() > 0) {
    while ($row = mysql_fetch_object($results)) {
        $currencySign = $row->currency_symbol;
        //print_r($currencySign); die;
        $access_restaurant = $row->access_pos;
        $access_retail = $row->access_register;
        $access_hotel = $row->access_hotel;
    }
}
//Number of records in table for each location (so that i can display or not the accordion on the right side)
$noOfRestaurants = getRecord("SELECT COUNT(*) AS total FROM client_orders co WHERE co.order_status= 'closed' AND co.location_id=$location_id", 'total');
$noOfRetail = getRecord("SELECT COUNT(*) AS total FROM client_sales cs,client_sales_payments csp WHERE cs.sales_id=csp.sales_id AND cs.status='closed' AND cs.location_id=csp.location_id AND cs.location_id=$location_id", 'total');
$noOfHotels = getRecord("SELECT COUNT(*) AS total FROM location_hotelacct lh,location_hotelacct_folio lhf WHERE lhf.account_id=lh.account_id AND lh.location_id=lhf.location_id AND lh.status='Checkout' AND lhf.type='Payment' AND lhf.location_id=$location_id", 'total');


//juni -> 02.07.2014 -> add date filter
//04.07.2014 -> change behaviour -> default to current year, but display statistics for today, this month, this year
$start_date = "";
$end_date = "";
/*
if ($_REQUEST['start_date'] == '') 
	$start_date = date('Y-m-d');
	//$_REQUEST['start_date'] = date('Y-m-d',strtotime(date("m/d/Y", strtotime('- 30 days'))));
if ($_REQUEST['end_date'] == '')
	$end_date = date('Y-m-d');
	//$_REQUEST['end_date'] = date('Y-m-d');
	*/
//
if ($_REQUEST['start_date'] != '' && $_REQUEST['end_date'] != '') {
    $datedisplay = $_REQUEST['start_date'] . " To " . $_REQUEST['end_date'];
    $start_date = date('Y-m-d', strtotime($_REQUEST['start_date']));
    $end_date = date('Y-m-d', strtotime($_REQUEST['end_date']));
    $dd = date('Y-m-d', strtotime($_REQUEST['start_date']));


    //difference between days
    //$datediff = $end_date - $start_date;

    function dateDiff($date1, $date2) 
    {
      $date1_ts = strtotime($date1);
      $date2_ts = strtotime($date2);
      $diff = $date2_ts - $date1_ts;
      return round($diff / 86400);
    }
    $no_of_days= dateDiff($start_date, $end_date);

   // $no_of_days=date_diff($start_date,$end_date);//round($datediff / (60 * 60 * 24));
//echo $no_of_days;die;

    //juni -> not neeed, but kept here "just in case"
    //$mm = date('Y-m-d',strtotime($_REQUEST['start_date']));
    //$ddSql = "STR_TO_DATE('" . $today . "','%Y-%m-%d')";
    $ddnextDay = date('Y-m-d', strtotime($_REQUEST['end_date'] . " +1 day")); // -> because i used between i need to add one day
    //$mmnextDay = date('Y-m-d',strtotime($_REQUEST['end_date'] . " +31 day")); // -> because i used between i need to add one day
    //client_orders table
    $yyClientOrdersdate = "AND co.`order_date` between STR_TO_DATE('" . $dd . "','%Y-%m-%d') and STR_TO_DATE('" . $ddnextDay . "','%Y-%m-%d')";
    //client_order_items
    $yyClientOrdersItemsdate = "AND coi.`datetime` between STR_TO_DATE('" . $dd . "','%Y-%m-%d') and STR_TO_DATE('" . $ddnextDay . "','%Y-%m-%d')";
    //client_sales table
    $yyClientSalesDate = "AND cs.`date` between STR_TO_DATE('" . $dd . "','%Y-%m-%d') and STR_TO_DATE('" . $ddnextDay . "','%Y-%m-%d')";
    //client_sales_payments table
    $yyClientSalesPaymentsDate = "AND csp.`date` between STR_TO_DATE('" . $dd . "','%Y-%m-%d') and STR_TO_DATE('" . $ddnextDay . "','%Y-%m-%d')";
    //client_sales_items table
    $yyClientSalesItemsDate = "AND csi.`date` between STR_TO_DATE('" . $dd . "','%Y-%m-%d') and STR_TO_DATE('" . $ddnextDay . "','%Y-%m-%d')";
    //location_hotelacct table
    $yyLocHotAcctDate = "AND lh.`departure` between STR_TO_DATE('" . $dd . "','%Y-%m-%d') and STR_TO_DATE('" . $ddnextDay . "','%Y-%m-%d')";
    //location_hotelacct_folio table
    $yyLocHotAcctFolioDate = "AND lhf.`date` between STR_TO_DATE('" . $dd . "','%Y-%m-%d') and STR_TO_DATE('" . $ddnextDay . "','%Y-%m-%d')";
    //client_reservations table
    $yyClientReservationDate = "AND cr.`reservation_date` between STR_TO_DATE('" . $dd . "','%Y-%m-%d') and STR_TO_DATE('" . $ddnextDay . "','%Y-%m-%d')";
    //location_clicks table
    $yyLocClicksDate = "AND lc.`date` between STR_TO_DATE('" . $dd . "','%Y-%m-%d') and STR_TO_DATE('" . $ddnextDay . "','%Y-%m-%d')";
    //client_reviews table
    $yyClientReviewsDate = "AND cr.`date` between STR_TO_DATE('" . $dd . "','%Y-%m-%d') and STR_TO_DATE('" . $ddnextDay . "','%Y-%m-%d')";
    //location_chat table
    $yyLocChatDate = "AND lc.`date` between STR_TO_DATE('" . $dd . "','%Y-%m-%d') and STR_TO_DATE('" . $ddnextDay . "','%Y-%m-%d')";
} else {
    $datedisplay = date("m/d/Y", strtotime('- 30 days')) . " To " . date("m/d/Y");
    $dd = date('Y-m-d');
    $yearFirstDay = mktime(0, 0, 0, 1, 1, date('Y'));
    $yearLastDay = mktime(0, 0, 0, 12, 31, date('Y'));
    $start_date = date('Y-m-d', strtotime('today - 29 days'));
    $end_date = date('Y-m-d');//date('Y-m-d',$yearLastDay);

     function dateDiff($date1, $date2) 
    {
      $date1_ts = strtotime($date1);
      $date2_ts = strtotime($date2);
      $diff = $date2_ts - $date1_ts;
      return round($diff / 86400);
    }
    $no_of_days= dateDiff($start_date, $end_date);

    //$yyfistDay = date('Y-m-d',$yearFirstDay . " -1 day"); // -> because i used between i need to subtract one day
    $yyfistDay = date('Y-m-d', $yearFirstDay);
    $yynextDay = date('Y-m-d', $yearLastDay . " +1 day"); // -> because i used between i need to add one day
    //client_orders table
    $yyClientOrdersdate = "AND co.`order_date` between STR_TO_DATE('" . $yyfistDay . "','%Y-%m-%d') and STR_TO_DATE('" . $yynextDay . "','%Y-%m-%d')";
    //client_order_items
    $yyClientOrdersItemsdate = "AND coi.`datetime` between STR_TO_DATE('" . $yyfistDay . "','%Y-%m-%d') and STR_TO_DATE('" . $yynextDay . "','%Y-%m-%d')";
    //client_sales table
    $yyClientSalesDate = "AND cs.`date` between STR_TO_DATE('" . $yyfistDay . "','%Y-%m-%d') and STR_TO_DATE('" . $yynextDay . "','%Y-%m-%d')";
    //client_sales_payments table
    $yyClientSalesPaymentsDate = "AND csp.`date` between STR_TO_DATE('" . $yyfistDay . "','%Y-%m-%d') and STR_TO_DATE('" . $yynextDay . "','%Y-%m-%d')";
    //client_sales_items table
    $yyClientSalesItemsDate = "AND csi.`date` between STR_TO_DATE('" . $yyfistDay . "','%Y-%m-%d') and STR_TO_DATE('" . $yynextDay . "','%Y-%m-%d')";
    //location_hotelacct table
    $yyLocHotAcctDate = "AND lh.`departure` between STR_TO_DATE('" . $yyfistDay . "','%Y-%m-%d') and STR_TO_DATE('" . $yynextDay . "','%Y-%m-%d')";
    //location_hotelacct_folio table
    $yyLocHotAcctFolioDate = "AND lhf.`date` between STR_TO_DATE('" . $yyfistDay . "','%Y-%m-%d') and STR_TO_DATE('" . $yynextDay . "','%Y-%m-%d')";
    //client_reservations table
    $yyClientReservationDate = "AND cr.`reservation_date` between STR_TO_DATE('" . $yyfistDay . "','%Y-%m-%d') and STR_TO_DATE('" . $yynextDay . "','%Y-%m-%d')";
    //location_clicks table
    $yyLocClicksDate = "AND lc.`date` between STR_TO_DATE('" . $yyfistDay . "','%Y-%m-%d') and STR_TO_DATE('" . $yynextDay . "','%Y-%m-%d')";
    //client_reviews table
    $yyClientReviewsDate = "AND cr.`date` between STR_TO_DATE('" . $yyfistDay . "','%Y-%m-%d') and STR_TO_DATE('" . $yynextDay . "','%Y-%m-%d')";
    //location_chat table
    $yyLocChatDate = "AND lc.`date` between STR_TO_DATE('" . $yyfistDay . "','%Y-%m-%d') and STR_TO_DATE('" . $yynextDay . "','%Y-%m-%d')";
}
//Starting old query blocks ;)
/* Clicks */
/*
$reservation_mon_sql = "SELECT COUNT(*) as day_total FROM location_clicks cr WHERE cr.`location_id`=$location_id AND date(cr.`date`) = date(now())";
$reservation_mon_res =  mysql_query($reservation_mon_sql) or die(mysql_error());
$reservation_mon 	 = 	mysql_fetch_array($reservation_mon_res);
$click_day 			 = 	$reservation_mon['day_total'];

$reservation_mon_sql = "SELECT COUNT(*) as monthly_total FROM location_clicks cr WHERE cr.`location_id`=$location_id AND MONTH(cr.`date`) = MONTH(CURDATE())";
$reservation_mon_res = mysql_query($reservation_mon_sql) or die(mysql_error());
$reservation_mon 	 = mysql_fetch_array($reservation_mon_res);
$click_mon 			 = $reservation_mon['monthly_total'];

$reservation_year_sql = "SELECT COUNT(*) as yearly_total FROM location_clicks cr WHERE  cr.`location_id`=$location_id AND YEAR(cr.`date`) = YEAR(CURDATE())";
$reservation_year_res = mysql_query($reservation_year_sql) or die(mysql_error());
$reservation_year 	  = mysql_fetch_array($reservation_year_res);
$click_year 	 	  = $reservation_year['yearly_total'];
*/

/*Reservation*/
/*
$reservation_mon_sql 	= 	"SELECT COUNT(*) as day_total FROM client_reservations cr WHERE cr.`status`<>'C' AND cr.`location_id`=$location_id AND date(cr.`reservation_date`) = date(now())";
$reservation_mon_res 	= 	mysql_query($reservation_mon_sql) or die(mysql_error());
$reservation_mon 		= 	mysql_fetch_array($reservation_mon_res);
$reservation_day 		= 	$reservation_mon['day_total'];

$reservation_mon_sql 	= 	"SELECT COUNT(*) as monthly_total FROM client_reservations cr WHERE cr.`status`<>'C' AND cr.`location_id`=$location_id AND MONTH(cr.`reservation_date`) = MONTH(CURDATE())";
$reservation_mon_res 	= 	mysql_query($reservation_mon_sql) or die(mysql_error());
$reservation_mon 		= 	mysql_fetch_array($reservation_mon_res);
$reservation_mon 		= 	$reservation_mon['monthly_total'];

$reservation_year_sql 	= 	"SELECT COUNT(*) as yearly_total FROM client_reservations cr WHERE cr.`status`<>'C' AND cr.`location_id`=$location_id AND YEAR(cr.`reservation_date`) = YEAR(CURDATE())";
$reservation_year_res 	= 	mysql_query($reservation_year_sql) or die(mysql_error());
$reservation_year	 	= 	mysql_fetch_array($reservation_year_res);
$reservation_year 		= 	$reservation_year['yearly_total'];*/

/* Reviews */
/*
$reviews_mon_sql 	= "SELECT COUNT(*) as day_total FROM `client_reviews` cr WHERE cr.`location_id`=$location_id AND date(cr.`date`) = date(now())";
$reviews_mon_res 	= mysql_query($reviews_mon_sql) or die(mysql_error());
$reviews_mon 		= mysql_fetch_array($reviews_mon_res);
$reviews_day 		= $reviews_mon['day_total'];

$reviews_mon_sql 	= "SELECT COUNT(*) as monthly_total FROM `client_reviews` cr WHERE cr.`location_id`=$location_id AND MONTH(cr.`date`) = MONTH(CURDATE())";
$reviews_mon_res 	= mysql_query($reviews_mon_sql) or die(mysql_error());
$reviews_mon 		= mysql_fetch_array($reviews_mon_res);
$reviews_mon 		= $reviews_mon['monthly_total'];

$reviews_year_sql 	= "SELECT COUNT(*) as yearly_total FROM `client_reviews` cr WHERE cr.`location_id`=$location_id AND YEAR(cr.`date`) = YEAR(CURDATE())";
$reviews_year_res 	= mysql_query($reviews_year_sql) or die(mysql_error());
$reviews_year 		= mysql_fetch_array($reviews_year_res);
$reviews_year 		= $reviews_year['yearly_total'];*/

/* Chatter*/
/*
$chatters_mon_sql 	= "SELECT COUNT(*) as day_total FROM location_chat lc left JOIN clients c ON lc.`client_id`=c.`id` WHERE  lc.`location_id`=$location_id AND date(lc.`date`) = date(now())";
$chatters_mon_res 	= mysql_query($chatters_mon_sql) or die(mysql_error());
$chatters_mon 		= mysql_fetch_array($chatters_mon_res);
$chatters_day 		= $chatters_mon['day_total'];

$chatters_mon_sql 	= "SELECT COUNT(*) as monthly_total FROM location_chat lc left JOIN clients c ON lc.`client_id`=c.`id` WHERE  lc.`location_id`=$location_id AND MONTH(lc.`date`) = MONTH(CURDATE())";
$chatters_mon_res 	= mysql_query($chatters_mon_sql) or die(mysql_error());
$chatters_mon 		= mysql_fetch_array($chatters_mon_res);
$chatters_mon 		= $chatters_mon['monthly_total'];

$chatters_year_sql 	= "SELECT COUNT(*) as yearly_total FROM location_chat lc left JOIN clients c ON lc.`client_id`=c.`id` WHERE lc.`location_id`=$location_id AND YEAR(lc.`date`) = YEAR(CURDATE())";
$chatters_year_res 	= mysql_query($chatters_year_sql) or die(mysql_error());
$chatters_year 		= mysql_fetch_array($chatters_year_res);
$chatters_year 		= $chatters_year['yearly_total'];*/

/*Revenue*/
/*
$revenue_mon_sql	= 	"SELECT SUM(order_subtotal) AS day_total FROM `client_orders` co WHERE co.`order_status`<> 'Cancelled' AND co.`location_id`=$location_id AND date(co.`order_date`) = date(now())";
$revenue_mon_res 	= 	mysql_query($revenue_mon_sql) or die(mysql_error());
$revenue_mon 		= 	mysql_fetch_array($revenue_mon_res);
$revenue_day 		= 	$revenue_mon['day_total'];

$revenue_mon_sql 	= 	"SELECT SUM(order_subtotal) AS monthly_total FROM `client_orders` co WHERE co.`order_status`<> 'Cancelled' AND co.`location_id`=$location_id AND MONTH(co.`order_date`) = MONTH(CURDATE())";
$revenue_mon_res 	= 	mysql_query($revenue_mon_sql) or die(mysql_error());
$revenue_mon 		= 	mysql_fetch_array($revenue_mon_res);
$revenue_mon 		= 	$revenue_mon['monthly_total'];

$revenue_year_sql 	= 	"SELECT SUM(order_subtotal) AS yearly_total FROM `client_orders` co WHERE co.`order_status`<> 'Cancelled' AND co.`location_id`=$location_id AND YEAR(co.`order_date`) = YEAR(CURDATE())";
$revenue_year_res 	= 	mysql_query($revenue_year_sql) or die(mysql_error());
$revenue_year 		= 	mysql_fetch_array($revenue_year_res);
$revenue_year 		= 	$revenue_year['yearly_total'];*/


/*		Covers		*/
/*
$covers_mon_sql 	= 	"SELECT SUM(covers) as day_total FROM `client_orders` co WHERE co.`order_status`<> 'Cancelled'  AND co.`location_id`=$location_id AND date(co.`order_date`) = date(now())";
$covers_mon_res 	= 	mysql_query($covers_mon_sql) or die(mysql_error());
$covers_mon 		= 	mysql_fetch_array($covers_mon_res);
$covers_day 		=	$covers_mon['day_total'];

$covers_mon_sql 	= 	"SELECT SUM(covers) as monthly_total FROM `client_orders` co WHERE co.`order_status`<> 'Cancelled' AND co.`location_id`=$location_id AND MONTH(co.`order_date`) = MONTH(CURDATE())";
$covers_mon_res 	= 	mysql_query($covers_mon_sql) or die(mysql_error());
$covers_mon 		= 	mysql_fetch_array($covers_mon_res);
$covers_mon 		= 	$covers_mon['monthly_total'];

$covers_year_sql 	= 	"SELECT SUM(covers) as yearly_total FROM `client_orders` co WHERE co.`order_status`<> 'Cancelled' AND co.`location_id`=$location_id AND YEAR(co.`order_date`) = YEAR(CURDATE())";
$covers_year_res 	= 	mysql_query($covers_year_sql) or die(mysql_error());
$covers_year 		= 	mysql_fetch_array($covers_year_res);
$covers_year 		= 	$covers_year['yearly_total'];*/

/*		Orders		*/
/*
$orders_mon_sql 	= 	"SELECT COUNT(id) as day_total FROM `client_orders` co WHERE co.`order_status`<> 'Cancelled' AND co.`location_id`=$location_id AND date(co.`order_date`) = date(now())";
$orders_mon_res 	= 	mysql_query($orders_mon_sql) or die(mysql_error());
$orders_mon 		= 	mysql_fetch_array($orders_mon_res);
$orders_day 		= 	$orders_mon['day_total'];

$orders_mon_sql 	= 	"SELECT COUNT(id) as monthly_total FROM `client_orders` co WHERE co.`order_status`<> 'Cancelled' AND co.`location_id`=$location_id AND MONTH(co.`order_date`) = MONTH(CURDATE())";
$orders_mon_res 	= 	mysql_query($orders_mon_sql) or die(mysql_error());
$orders_mon 		= 	mysql_fetch_array($orders_mon_res);
$orders_mon 		= 	$orders_mon['monthly_total'];

$orders_year_sql 	= 	"SELECT COUNT(id) as yearly_total FROM `client_orders` co WHERE co.`order_status`<> 'Cancelled'  AND co.`location_id`=$location_id AND YEAR(co.`order_date`) = YEAR(CURDATE())";
$orders_year_res 	= 	mysql_query($orders_year_sql) or die(mysql_error());
$orders_year 		= 	mysql_fetch_array($orders_year_res);
$orders_year 		= 	$orders_year['yearly_total'];*/

/*	======> START Restaurant Dashboard		*/
/*		Revenue		*/

$revenue_dd = getRecord("SELECT SUM(order_payments) AS total FROM `client_orders` co WHERE co.`order_status`= 'closed' AND co.`location_id`=$location_id AND date(co.`order_date`) = date('$dd')", 'total');
$revenue_mm = getRecord("SELECT SUM(order_payments) AS total FROM `client_orders` co WHERE co.`order_status`= 'closed' AND co.`location_id`=$location_id AND MONTH(co.`order_date`) = MONTH('$dd')", 'total');
//$revenue_yy = getRecord("SELECT SUM(order_payments) AS total FROM `client_orders` co WHERE co.`order_status`= 'closed' AND co.`location_id`=$location_id AND YEAR(co.`order_date`) = YEAR('$dd')",'total');
$revenue_yy = getRecord("SELECT SUM(order_payments) AS total FROM `client_orders` co WHERE co.`order_status`= 'closed' AND co.`location_id`=$location_id $yyClientOrdersdate", 'total');

/*		Orders		*/
$orders_dd = getRecord("SELECT COUNT(id) as total FROM `client_orders` co WHERE co.`order_status`= 'closed' AND co.assigned_server!='' AND co.`location_id`=$location_id AND date(co.`order_date`) = date('$dd')", 'total');
$orders_mm = getRecord("SELECT COUNT(id) as total FROM `client_orders` co WHERE co.`order_status`= 'closed' AND co.assigned_server!='' AND co.`location_id`=$location_id AND MONTH(co.`order_date`) = MONTH('$dd')", 'total');
$orders_yy = getRecord("SELECT COUNT(id) as total FROM `client_orders` co WHERE co.`order_status`= 'closed' AND co.assigned_server!='' AND co.`location_id`=$location_id $yyClientOrdersdate", 'total');

/*		Covers		*/
$covers_dd = getRecord("SELECT SUM(covers) as total FROM `client_orders` co WHERE co.`order_status`= 'closed'  AND co.assigned_server!='' AND co.`location_id`=$location_id AND date(co.`order_date`) = date('$dd')", 'total');
$covers_mm = getRecord("SELECT SUM(covers) as total FROM `client_orders` co WHERE co.`order_status`= 'closed'  AND co.assigned_server!='' AND co.`location_id`=$location_id AND MONTH(co.`order_date`) = MONTH('$dd')", 'total');
$covers_yy = getRecord("SELECT SUM(covers) as total FROM `client_orders` co WHERE co.`order_status`= 'closed'  AND co.assigned_server!='' AND co.`location_id`=$location_id $yyClientOrdersdate", 'total');

/*		END Restaurant Dashboard <====== */

/*	======> START Retail Dashboard		*/
/*		Revenue		*/
//SELECT IFNULL(COUNT(DISTINCT(cs.id)),0) AS total 
$ret_revenue_dd = getRecord("SELECT SUM(payment_amt) AS total FROM client_sales cs,client_sales_payments csp WHERE cs.sales_id=csp.sales_id AND cs.status='closed' AND cs.location_id=csp.location_id AND cs.location_id=$location_id AND date(csp.`date`) = date('$dd')", 'total');
$ret_revenue_mm = getRecord("SELECT SUM(payment_amt) AS total FROM client_sales cs,client_sales_payments csp WHERE cs.sales_id=csp.sales_id AND cs.status='closed' AND cs.location_id=csp.location_id AND cs.location_id=$location_id AND MONTH(csp.`date`) = MONTH('$dd')", 'total');
//$ret_revenue_yy  = getRecord("SELECT SUM(payment_amt) AS total FROM client_sales cs,client_sales_payments csp WHERE cs.sales_id=csp.sales_id AND cs.status='closed' AND cs.location_id=csp.location_id AND cs.location_id=$location_id AND YEAR(csp.`date`) = YEAR('$dd')",'total');
$ret_revenue_yy = getRecord("SELECT SUM(payment_amt) AS total FROM client_sales cs,client_sales_payments csp WHERE cs.sales_id=csp.sales_id AND cs.status='closed' AND cs.location_id=csp.location_id AND cs.location_id=$location_id $yyClientSalesDate", 'total');

/*		Checks		*/
$ret_checks_dd = getRecord("SELECT COUNT(DISTINCT(cs.id)) AS total FROM client_sales cs WHERE cs.status='closed' AND cs.location_id=$location_id AND DATE(cs.`date`) = DATE('$dd')", 'total');
$ret_checks_mm = getRecord("SELECT COUNT(DISTINCT(cs.id)) AS total FROM client_sales cs WHERE cs.status='closed' AND cs.location_id=$location_id AND MONTH(cs.`date`) = MONTH('$dd')", 'total');
$ret_checks_yy = getRecord("SELECT COUNT(DISTINCT(cs.id)) AS total FROM client_sales cs WHERE cs.status='closed' AND cs.location_id=$location_id $yyClientSalesDate", 'total');

/*		Items Sold		*/
$ret_items_dd = getRecord("SELECT SUM(qty) as total FROM client_sales cs,client_sales_items csi WHERE cs.sales_id=csi.sales_id AND cs.status='closed' AND cs.location_id=csI.location_id AND cs.location_id=$location_id AND date(csi.`date`) = date('$dd')", 'total');
$ret_items_mm = getRecord("SELECT SUM(qty) as total FROM client_sales cs,client_sales_items csi WHERE cs.sales_id=csi.sales_id AND cs.status='closed' AND cs.location_id=csI.location_id AND cs.location_id=$location_id AND MONTH(csi.`date`) = MONTH('$dd')", 'total');
$ret_items_yy = getRecord("SELECT SUM(qty) as total FROM client_sales cs,client_sales_items csi WHERE cs.sales_id=csi.sales_id AND cs.status='closed' AND cs.location_id=csI.location_id AND cs.location_id=$location_id $yyClientSalesDate", 'total');

/*		END Retail Dashboard <====== */


/*	======> START Hotel Dashboard		*/
/*		Revenue		*/
$hot_revenue_dd = getRecord("SELECT COALESCE(SUM(lhf.amount),0) AS total FROM location_hotelacct lh,location_hotelacct_folio lhf WHERE lhf.account_id=lh.account_id AND lh.location_id=lhf.location_id AND lh.status='Checkout' AND lhf.type='Payment' AND lhf.location_id=$location_id AND date(lhf.`date`) = date('$dd')", 'total');
$hot_revenue_mm = getRecord("SELECT COALESCE(SUM(lhf.amount),0) AS total FROM location_hotelacct lh,location_hotelacct_folio lhf WHERE lhf.account_id=lh.account_id AND lh.location_id=lhf.location_id AND lh.status='Checkout' AND lhf.type='Payment' AND lhf.location_id=$location_id AND MONTH(lhf.`date`) = MONTH('$dd')", 'total');
//$hot_revenue_yy  = getRecord("SELECT COALESCE(SUM(lhf.amount),0) AS total FROM location_hotelacct lh,location_hotelacct_folio lhf WHERE lhf.account_id=lh.account_id AND lh.location_id=lhf.location_id AND lh.status='Checkout' AND lhf.type='Payment' AND lhf.location_id=$location_id AND YEAR(lhf.`date`) = YEAR('$dd')",'total');
$hot_revenue_yy = getRecord("SELECT COALESCE(SUM(lhf.amount),0) AS total FROM location_hotelacct lh,location_hotelacct_folio lhf WHERE lhf.account_id=lh.account_id AND lh.location_id=lhf.location_id AND lh.status='Checkout' AND lhf.type='Payment' AND lhf.location_id=$location_id $yyLocHotAcctFolioDate", 'total');

/*		Accounts		*/
$hot_accounts_dd = getRecord("SELECT COALESCE(count(DISTINCT(lh.id)),0) AS total FROM location_hotelacct lh,location_hotelacct_folio lhf WHERE lhf.account_id=lh.account_id AND lh.location_id=lhf.location_id AND lh.status='Checkout' AND lhf.type='Payment' AND lhf.location_id=$location_id AND date(lhf.`date`) = date('$dd')", 'total');
$hot_accounts_mm = getRecord("SELECT COALESCE(count(DISTINCT(lh.id)),0) AS total FROM location_hotelacct lh,location_hotelacct_folio lhf WHERE lhf.account_id=lh.account_id AND lh.location_id=lhf.location_id AND lh.status='Checkout' AND lhf.type='Payment' AND lhf.location_id=$location_id AND MONTH(lhf.`date`) = MONTH('$dd')", 'total');
$hot_accounts_yy = getRecord("SELECT COALESCE(count(DISTINCT(lh.id)),0) AS total FROM location_hotelacct lh,location_hotelacct_folio lhf WHERE lhf.account_id=lh.account_id AND lh.location_id=lhf.location_id AND lh.status='Checkout' AND lhf.type='Payment' AND lhf.location_id=$location_id $yyLocHotAcctFolioDate", 'total');

/*		Number of guests		*/
/* -> wrong sql -> 02.07.2014 
$hot_guests_dd  = getRecord("SELECT COALESCE(SUM(lh.adults+lh.child1+lh.child2),0) AS total FROM location_hotelacct lh,location_hotelacct_folio lhf WHERE lhf.account_id=lh.account_id AND lh.location_id=lhf.location_id AND lh.status='Checkout' AND lhf.type='Payment' AND lhf.location_id=$location_id AND date(lhf.`date`) = date(now())",'total');
*/
$hot_guests_dd = getRecord("SELECT COALESCE(SUM(lh.adults),0)+COALESCE(SUM(lh.child1),0)+COALESCE(SUM(lh.child2),0) AS total FROM location_hotelacct lh WHERE lh.status='Checkout' AND lh.location_id=$location_id AND date(lh.`departure`) = date('$dd')", 'total');
$hot_guests_mm = getRecord("SELECT COALESCE(SUM(lh.adults),0)+COALESCE(SUM(lh.child1),0)+COALESCE(SUM(lh.child2),0) AS total FROM location_hotelacct lh WHERE lh.status='Checkout' AND lh.location_id=$location_id AND MONTH(lh.`departure`) = MONTH('$dd')", 'total');
$hot_guests_yy = getRecord("SELECT COALESCE(SUM(lh.adults),0)+COALESCE(SUM(lh.child1),0)+COALESCE(SUM(lh.child2),0) AS total FROM location_hotelacct lh WHERE lh.status='Checkout' AND lh.location_id=$location_id $yyLocHotAcctDate", 'total');
/*		END Hotel Dashboard <====== */

/*		END Hotel Dashboard <====== */


/*	======>	START PIE CHART		*/

/*		Reservation		*/
$reservation_dd = getRecord("SELECT COUNT(*) as total FROM client_reservations cr WHERE cr.`status`<>'C' AND cr.`location_id`=$location_id AND DATE(cr.`reservation_date`) = DATE('$dd')", 'total');
$reservation_mm = getRecord("SELECT COUNT(*) as total FROM client_reservations cr WHERE cr.`status`<>'C' AND cr.`location_id`=$location_id AND MONTH(cr.`reservation_date`) = MONTH('$dd')", 'total');
$reservation_yy = getRecord("SELECT COUNT(*) as total FROM client_reservations cr WHERE cr.`status`<>'C' AND cr.`location_id`=$location_id $yyClientReservationDate", 'total');

/*		Clicks		*/
$click_dd = getRecord("SELECT COUNT(*) as total FROM location_clicks lc WHERE lc.`location_id`=$location_id AND date(lc.`date`) = date('$dd')", 'total');
$click_mm = getRecord("SELECT COUNT(*) as total FROM location_clicks lc WHERE lc.`location_id`=$location_id AND MONTH(lc.`date`) = MONTH('$dd')", 'total');
$click_yy = getRecord("SELECT COUNT(*) as total FROM location_clicks lc WHERE lc.`location_id`=$location_id $yyLocClicksDate", 'total');

/*		Reviews		*/
$reviews_dd = getRecord("SELECT COUNT(*) as total FROM `client_reviews` cr WHERE cr.`location_id`=$location_id AND date(cr.`date`) = date('$dd')", 'total');
$reviews_mm = getRecord("SELECT COUNT(*) as total FROM `client_reviews` cr WHERE cr.`location_id`=$location_id AND MONTH(cr.`date`) = MONTH('$dd')", 'total');
$reviews_yy = getRecord("SELECT COUNT(*) as total FROM `client_reviews` cr WHERE cr.`location_id`=$location_id $yyClientReviewsDate", 'total');

/*		Chatter		*/
$chatters_dd = getRecord("SELECT COUNT(*) as total FROM location_chat lc left JOIN clients c ON lc.`client_id`=c.`id` WHERE  lc.`location_id`=$location_id AND date(lc.`date`) = date('$dd')", 'total');
$chatters_mm = getRecord("SELECT COUNT(*) as total FROM location_chat lc left JOIN clients c ON lc.`client_id`=c.`id` WHERE  lc.`location_id`=$location_id AND MONTH(lc.`date`) = MONTH('$dd')", 'total');
$chatters_yy = getRecord("SELECT COUNT(*) as total FROM location_chat lc left JOIN clients c ON lc.`client_id`=c.`id` WHERE  lc.`location_id`=$location_id $yyLocChatDate", 'total');
/*		END  PIE CHART	 <======	*/

/*	-------------	*/
/*	======>	START DATA FOR THE RIGHT DIV		*/
//get top 10 best clients
/** juni -> old query -> without amounts from each trx table**/
/*
$sql = "SELECT co.client_id, SUM(co.order_payments) as total, c.name
	FROM client_orders co
	LEFT JOIN clients c ON co.client_id=c.id
	WHERE co.location_id=$location_id AND client_id > 1
		$yyClientOrdersdate
	GROUP BY co.client_id
	ORDER BY total DESC LIMIT 10";
//echo $sql;
$topClientRes = mysql_query($sql) or die(mysql_error());
*/
//$sql = "SELECT c.id as client_id,COALESCE(SUM(co.order_total),0)+COALESCE(SUM(csp.payment_amt),0)+COALESCE(SUM(lhf.amount),0)  as total, C.name
//juni -> 03.07.2014 -> reverting sql's to one displayed in BP CRM - Jul 01 14 - Jk 03b.jpg
/*
$sql = "SELECT c.id AS client_id, C.name,
COALESCE(SUM(co.order_total),0)+
COALESCE(SUM(csp.payment_amt),0) + 
COALESCE(SUM(lhf.amount),0)  AS total
FROM clients c 
	LEFT JOIN client_orders co ON co.client_id=c.id AND co.order_status='closed' AND co.location_id=$location_id
	LEFT JOIN client_sales cs ON cs.client_id=c.id AND cs.location_id=$location_id  AND cs.status='closed'
	LEFT JOIN client_sales_payments csp ON cs.sales_id=csp.sales_id  AND cs.location_id=csp.location_id 
	LEFT JOIN location_hotelacct_folio lhf ON lhf.client_id=c.id AND lhf.type='Payment' AND lhf.location_id=$location_id 
	LEFT JOIN location_hotelacct lh ON lhf.account_id=lh.account_id  AND lh.status='Checkout' AND lh.location_id=lhf.location_id 
WHERE c.id > 1 
GROUP BY c.id, C.name
	HAVING TOTAL  <> 0
ORDER BY total DESC LIMIT 10";
*/

/*$sql = "SELECT c.id AS client_id, C.name,
(SELECT COALESCE(SUM(co.order_total),0) 
	FROM clients c2
	INNER JOIN client_orders co ON co.client_id=c2.id AND co.order_status='closed' AND co.location_id=$location_id
		WHERE c2.id=c.id
) +
(SELECT COALESCE(SUM(csp.payment_amt),0)
	FROM clients c2
	INNER JOIN client_sales cs ON cs.client_id=c2.id AND cs.status='closed' AND cs.location_id=$location_id 
	INNER JOIN client_sales_payments csp ON cs.sales_id=csp.sales_id  AND cs.location_id=csp.location_id 
		WHERE c2.id=c.id
) +
(SELECT COALESCE(SUM(lhf.amount),0) 
	FROM clients c2
	INNER JOIN location_hotelacct_folio lhf ON lhf.client_id=c2.id AND lhf.type='Payment' AND lhf.location_id=$location_id  
	INNER JOIN location_hotelacct lh ON lhf.account_id=lh.account_id  AND lh.status='Checkout'  AND lh.location_id=lhf.location_id
		WHERE c2.id=c.id
) AS total
FROM clients c 
WHERE c.id > 1 
GROUP BY c.id, C.name
	HAVING TOTAL  <> 0
ORDER BY total DESC LIMIT 10";

//echo $sql;
$topClientRes = mysql_query($sql) or die(mysql_error());*/

//get top 5 best cashiers
/** juni -> old query -> without amounts from each trx table**/
$sql = "SELECT e.id as employee_id, CONCAT(e.first_name,' ', e.last_name) as name,COALESCE(SUM(csp.payment_amt),0) as total
	FROM employees e
	INNER JOIN client_sales cs on cs.employee_id=e.id
	LEFT JOIN client_sales_payments csp ON cs.sales_id=csp.sales_id AND cs.status='closed' AND cs.location_id=csp.location_id AND cs.location_id=$location_id
	WHERE cs.location_id=$location_id AND e.id > 1
		$yyClientSalesDate
	GROUP BY e.id
	ORDER BY total DESC LIMIT 5";
//echo $sql;
$topCashierRes = mysql_query($sql) or die(mysql_error());


// Market Payments
$sqlMarket = "SELECT omnivore_tickets_payments.status,omnivore_tickets_payments.amount,omnivore_tickets_payments.ticket_id,omnivore_tickets_payments.clover_payment_id, omnivore_tickets_payments.paid, COALESCE(omnivore_tickets_payments.refunded_amount,0) as refunded_amount , omnivore_tickets_payments.amount, omnivore_tickets_payments.`change`, omnivore_tickets_payments.tip 
			FROM omnivore_tickets_payments WHERE omnivore_tickets_payments.location_id ='$locationId_id'";
$sqlMarketRes = mysql_query($sqlMarket) or die(mysql_error());

//------------------------------ Top clients-------------------------------------//

// $sql = "SELECT co.client_id, SUM(co.order_payments) as total, c.name, c.phone, c.email
//     FROM client_orders co
//     LEFT JOIN clients c ON co.client_id=c.id
//     WHERE co.location_id=$location_id
//     GROUP BY co.client_id
//     ORDER BY total DESC LIMIT 10";


// $topClientRes = mysql_query($sql) or die(mysql_error());

//get top 5 best servers
//$sql = "SELECT assigned_server, COUNT(*) as num_of_orders, employees.first_name, employees.last_name,client_id
$sql = "SELECT co.assigned_server, SUM(co.covers) as total, e.first_name, e.last_name,co.client_id
	FROM client_orders co
	LEFT JOIN employees e ON co.assigned_server=e.id
	WHERE co.location_id=$location_id AND co.assigned_server!='' AND co.`order_status`= 'closed'
		$yyClientOrdersdate
	GROUP BY co.assigned_server
	ORDER BY total DESC
	LIMIT 5";
$topServerRes = mysql_query($sql) or die(mysql_error());

//get top 10 rooms types
/* $sql1 = "SELECT lhr.roomtype ,COALESCE(SUM(lhr.rate),0) as total, lhrt.code
	FROM location_hotel_roomtype lhrt
	INNER JOIN location_hotelacct_rates lhr ON lhr.roomtype=lhrt.id AND lhr.location_id=lhrt.location_id
	INNER JOIN location_hotelacct lh ON lhr.account_id=lh.account_id AND lh.location_id=lhr.location_id 
	WHERE lhr.location_id=$location_id
		AND lh.status='Checkout'
		$yyLocHotAcctDate
	GROUP BY lhr.roomtype
		HAVING total  <> 0
	ORDER BY total DESC
	LIMIT 10"; */
$sql1 = "SELECT lhr.roomtype ,lhr.rate as total, lhrt.code
	FROM location_hotel_roomtype lhrt
	INNER JOIN location_hotelacct_rates lhr ON lhr.roomtype=lhrt.id AND lhr.location_id=lhrt.location_id
	INNER JOIN location_hotelacct lh ON lhr.account_id=lh.account_id AND lh.location_id=lhr.location_id 
	WHERE lhr.location_id=$location_id
		AND lh.status='Checkout'
		$yyLocHotAcctDate
	LIMIT 10";
//echo $sql1;
$topSellingRooms = mysql_query($sql1) or die(mysql_error());


//get top 10 best selling articles
$sql = "SELECT menu_item_id, COUNT(quantity) as quantity, location_menu_articles.item
	FROM client_order_items coi
		LEFT JOIN location_menu_articles ON coi.menu_item_id=location_menu_articles.id
	WHERE coi.location_id=$location_id
		$yyClientOrdersItemsdate
	GROUP BY menu_item_id
		ORDER BY quantity DESC
	LIMIT 10";
$topSellingArticles = mysql_query($sql) or die(mysql_error());

//get top 10 best selling items
$sql = "SELECT menu_item_id, COUNT(qty) as quantity, lma.item
	FROM  client_sales cs
	LEFT JOIN client_sales_items csi ON cs.sales_id=csi.sales_id AND cs.location_id=csi.location_id
	LEFT JOIN location_menu_articles lma ON csi.menu_item_id=lma.id
	WHERE cs.status='closed' 
		AND cs.location_id=$location_id
			$yyClientSalesDate
	GROUP BY menu_item_id
			HAVING quantity  <> 0
	ORDER BY quantity DESC
	LIMIT 10";
//echo $sql;
$topSellingItems = mysql_query($sql) or die(mysql_error());
/*	======>	END DATA FOR THE RIGHT DIV		*/


//if ($_SERVER['REMOTE_ADDR'] == '37.252.71.44') {

    $date_start = "'" . $start_date . ' 00:00:00' . "'";
    $date_end = "'" . $end_date . ' 23:59:59' . "'";

// ini_set('memory_limit','256M');
$clients = array();
$client_favorite_location_clients = mysql_query("select clients_id from client_favorite_locations where locations_id='$location_id'") or die(mysql_error());
$client_orders_clients = mysql_query("select client_id from client_orders where location_id='$location_id'") or die(mysql_error());
$client_sales_clients = mysql_query("select client_id from client_sales where location_id='$location_id'") or die(mysql_error());
$location_hotelacct_client_clients = mysql_query("select client_id from location_hotelacct_client where location_id='$location_id'") or die(mysql_error());
$omnivore_tickets_payments_clients = mysql_query("select client_id from omnivore_tickets_payments where location_id='$location_id'") or die(mysql_error());
$client_reviews_clients = mysql_query("select client_id from client_reviews where location_id='$location_id'") or die(mysql_error());
$client_reservations_clients = mysql_query("select client_id from client_reservations where location_id='$location_id'") or die(mysql_error());

while ($row = mysql_fetch_array($client_favorite_location_clients, MYSQL_ASSOC)) {
     array_push($clients, $row['clients_id']);      
    }
while ($row = mysql_fetch_array($client_orders_clients, MYSQL_ASSOC)) {
     array_push($clients, $row['client_id']);      
    }
while ($row = mysql_fetch_array($client_sales_clients, MYSQL_ASSOC)) {
     array_push($clients, $row['client_id']);      
    }
while ($row = mysql_fetch_array($location_hotelacct_client_clients, MYSQL_ASSOC)) {
     array_push($clients, $row['client_id']);      
    }
while ($row = mysql_fetch_array($omnivore_tickets_payments_clients, MYSQL_ASSOC)) {
     array_push($clients, $row['client_id']);      
    }
while ($row = mysql_fetch_array($client_reviews_clients, MYSQL_ASSOC)) {
     array_push($clients, $row['client_id']);      
    }
while ($row = mysql_fetch_array($client_reservations_clients, MYSQL_ASSOC)) {
     array_push($clients, $row['client_id']);      
    }

 $unique_clients = array_unique($clients);
 $array_client_string = implode("','",$unique_clients);
 //
 // echo count($unique_clients);
 // print_r($array_client_string);
    // die;




    // $created_at = mysql_query(
    //     " Select COUNT(Id) as total, CONCAT(year(`created_datetime`), '-',month(`created_datetime`), '-', day(`created_datetime`)) as created_date 
    //                   from clients where `created_datetime` between " . $date_start . " AND " . $date_end .
    //     " AND id IN ('".$array_client_string."') AND created_datetime >= (NOW() - INTERVAL 90 DAY) GROUP BY created_date ORDER BY created_datetime DESC "
    // ) or die(mysql_error());


    // $last_at = mysql_query(
    //     " Select COUNT(Id) as total, CONCAT(year(`last_datetime`), '-',month(`last_datetime`), '-', day(`last_datetime`)) as last_date 
    //                   from clients where `last_datetime` between " . $date_start . " AND " . $date_end .
    //     " AND id IN ('".$array_client_string."') AND last_datetime >= (NOW() - INTERVAL 90 DAY) GROUP BY last_date ORDER BY last_datetime DESC "
    // ) or die(mysql_error());



    $created_at = mysql_query(
        " Select COUNT(bay_id) as total, CONCAT(year(`created_datetime`), '-',month(`created_datetime`), '-', day(`created_datetime`)) as created_date 
                      from vendor_distribution where `created_datetime` between " . $date_start . " AND " . $date_end .
        " AND created_datetime >= (NOW() - INTERVAL 90 DAY) GROUP BY created_date ORDER BY created_datetime DESC "
    ) or die(mysql_error());


    $last_at = mysql_query(
        " Select sum(cartons) as total, CONCAT(year(`created_datetime`), '-',month(`created_datetime`), '-', day(`created_datetime`)) as last_date 
                      from vendor_distribution_routes where `created_datetime` between " . $date_start . " AND " . $date_end .
        " AND created_datetime >= (NOW() - INTERVAL 90 DAY) GROUP BY last_date ORDER BY created_datetime DESC "
    ) or die(mysql_error());

   // print_r($last_at);

//------------------------------ Top clients-------------------------------------//
$sql = "SELECT co.client_id, SUM(co.order_payments) as total, c.name, c.phone, c.email
   FROM client_orders co
   LEFT JOIN clients c ON co.client_id=c.id
   WHERE c.id IN('".$array_client_string."')
   GROUP BY co.client_id
   ORDER BY total DESC LIMIT 10";

$topClientRes = mysql_query($sql) or die(mysql_error());

//------------------------------END Top clients-------------------------------------//
//testing adding dates

$end_date_for=date("Y-n-j",strtotime($end_date));

$d = array();
for($i = 0; $i <=$no_of_days; $i++) 
{
    $d[] = date("Y-n-j", strtotime($end_date_for.'-'. $i .' days'));
}




    $created_datetime = [];
    $created_counts = [];
    $last_datetime = [];
    $last_counts = [];

    while ($row = mysql_fetch_array($created_at, MYSQL_ASSOC)) {
        // echo "<pre>"; print_r($row);
        $created_datetime[] = $row['created_date'];
        $created_counts[$row['created_date']] = (int)$row['total'];

    }

    while ($row = mysql_fetch_array($last_at, MYSQL_ASSOC)) {
        $last_datetime[] = $row['last_date'];
        $last_counts[$row['last_date']] = (int)$row['total'];

    }


        $dates = array_unique(array_merge($created_datetime, $last_datetime,$d));


        // print_r($created_datetime)."<br>";
        //         print_r($last_datetime)."<br>";
        // print_r($d)."<br>";
        // print_r($dates)."<br>";


        // die;

    //after merge we have incorrect order of data, so we need to sort array as dates
    function date_compare($a, $b)
    {
        $t1 = strtotime($a);
        $t2 = strtotime($b);
        return $t1 - $t2;
      
    }
    usort($dates, 'date_compare');

    //search each date from chart in arrays for lines and append day total or 0 if not found
    $createdTotal = [];
    foreach ($dates as $key => $date){
        if(in_array($date,$created_datetime)){
            $createdTotal[$date] = $created_counts[$date];
        }else{
            $createdTotal[$date] = 0;
        }
    }
    $lastTotal = [];
    foreach ($dates as $key => $date){
        if(in_array($date,$last_datetime)){
            $lastTotal[$date] = $last_counts[$date];
        }else{
            $lastTotal[$date] = 0;
        }
    }

    // now manually get only values from arrays as points and
    // don't trust on php native functions as this is very important :)
    $clientsCreated = [];
    foreach ($createdTotal as $ct){
        $clientsCreated[] = $ct;
    }
    $clientsUpdated = [];
    foreach ($lastTotal as $lt){
        $clientsUpdated[] = $lt;
    }
 // print_r($createdTotal)."<br>";
 // print_r($lastTotal); die;

//}






$noDataHtml =
    '<tr class="gradeX odd" id="" style="cursor:pointer;">
	<td>&nbsp;</td>
	<td>No Data Available!</td>
	<td>&nbsp;</td>
</tr>';


// echo date("Y-m-d");die;

//get no of routes
$sql = mysql_query("SELECT routes FROM vendor_distribution_routes where date(created_datetime)=CURDATE()");



$no_of_routes=0;
 while ($row = mysql_fetch_assoc($sql)) 
 {
    $no_of_routes = $no_of_routes + 1 ;   

 } 
 

//get average load time
$sql1 = mysql_query("SELECT load_time FROM vendor_distribution_routes where date(created_datetime)=CURDATE()");

$avg_load=0;
$count=0;
 while ($row = mysql_fetch_assoc($sql1)) 
 {
$count=$count + 1;
    $avg_load = $avg_load + $row['load_time'] ;   

    } 

$avg_load_time=$avg_load / $count;

//get quick load time
$sql2 = mysql_query("SELECT * FROM vendor_distribution_routes where date(created_datetime)=CURDATE()");
//$active_clients_address = str_replace("Resource id #"," ",mysql_query($sql2));

//echo $sql2;die;
$qlt=array();
 while($row2 = mysql_fetch_assoc($sql2))
 {
 	array_push($qlt, $row2['load_time']);
 }
// print_r($qlt);
 $quick_load_time=min($qlt);
// echo $quick_load_time."<br>";
 // {

 //    $active_clients_address = $active_clients_address + 1 ;   

 //    } 

//get longest load time
$sql3 = mysql_query("SELECT * FROM vendor_distribution_routes where date(created_datetime)=CURDATE()");
$llt=array();
while($row3 = mysql_fetch_assoc($sql3))
{
	 	array_push($llt, $row3['load_time']);

}
// print_r($llt);
 $longest_load_time=max($llt);
 // echo $longest_load_time;die;

//$active_clients_phone = str_replace("Resource id #"," ",mysql_query($sql3));
// $active_clients_phone=0;
//  while ($row = mysql_fetch_assoc($sql3)) 
//  {
//     $active_clients_phone = $active_clients_phone + 1 ;   

//     } 

//get first time out

$sql4 = mysql_query("SELECT * FROM vendor_distribution_routes where date(created_datetime)=CURDATE()");
$fto=array();
while($row4 = mysql_fetch_assoc($sql4))
{
	 	array_push($fto, foo($row4['time_out']));

}
 // print_r($fto);
 $first_time_out=min($fto);
 // echo $first_time_out."<br>";


//get last time out

$sql5 = mysql_query("SELECT * FROM vendor_distribution_routes where date(created_datetime)=CURDATE()");
$lto=array();
while($row5 = mysql_fetch_assoc($sql5))
{
	 	array_push($lto, foo($row5['time_out']));

}
// print_r($lto);
 $last_time_out=max($lto);
 // echo $last_time_out;die;


 //get case counts

$sql6 = mysql_query("SELECT * FROM vendor_distribution_routes where date(created_datetime)=CURDATE()");
$cc=0;
while($row6 = mysql_fetch_assoc($sql6))
{
	 	$cc=$cc + $row6['cartons'];

}
// print_r($lto);
 $case_counts=$cc;
 // echo $last_time_out;die;



function foo($seconds) {
  $t = round($seconds);
  return sprintf('%02d:%02d', ($t/3600),($t/60%60));
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SoftPoint | VendorPanel</title>
    <link rel="stylesheet" href="css/style.default.css" type="text/css"/>
    <link rel="stylesheet" href="css/responsive-tables.css">
    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
    <script type="text/javascript" src="js/modernizr.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <script type="text/javascript" src="js/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
    <script type="text/javascript" src="js/flot/jquery.flot.resize.min.js"></script>
    <script type="text/javascript" src="js/flot/jquery.flot.pie.min.js"></script>

    <!--   highchart-->
    <!--    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">-->
    <!--    </script>-->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <!--   highchart-->
    <script type="text/javascript" src="js/chart.js"></script>

    <script type="text/javascript" src="js/responsive-tables.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>
    <script type="text/javascript" src="js/jquery.alerts.js"></script>
    <!-- juni - Added custom css ,js and php files which contain global needed changes -->
    <?php include_once 'includes/jcustom.php'; ?>
    <link rel="stylesheet" href="css/jcustom.css" type="text/css"/>
    <script type="text/javascript" src="js/jcustom.js"></script>
    <!--[if lte IE 8]>
    <script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
    <style>
        #pop_wrap table td {
            border: none !important;
            color: #333333 !important;
        }

        .modal-header h3 {
            color: #333333 !important;
        }

        .modal-footer {
            color: #333333 !important;
        }

        .response_modal {
            width: 420px !important;
        }

        .blue {
            background: #680fb3;
            border: solid 1px #cccccc;
            padding: 3px;
            width: 5px;
            height: 4px;
            margin: 5px 3px 0 6px;
            float: left;
        }

        .green {
            background: #9ab30f;
            border: solid 1px #cccccc;
            padding: 3px;
            width: 5px;
            height: 4px;
            margin: 5px 3px 0 6px;
            float: left;
        }

        .brown {
            background: #a44646;
            border: solid 1px #cccccc;
            padding: 3px;
            width: 5px;
            height: 4px;
            margin: 5px 3px 0 6px;
            float: left;
        }

        .orange {
            background: #de8445;
            border: solid 1px #cccccc;
            padding: 3px;
            width: 5px;
            height: 4px;
            margin: 5px 3px 0 6px;
            float: left;
        }

        .lightblue {
            background: #376193;
            border: solid 1px #cccccc;
            padding: 3px;
            width: 5px;
            height: 4px;
            margin: 5px 3px 0 6px;
            float: left;
        }

        .widget_title {
            float: left;
            width: 14%;
        }

        .widget_statistics {
            float: left;
            width: 28%;
            height: 90px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .widget-header {
            font-size: 24px;
        }

        .widget_statistics table tr:first-child {
            font-weight: bold;
            font-size: 12px;
        }

        .widget_statistics > table {
            border: medium none !important;
        }


        .piecharts {
            height: 100% !important;
            width: 100% !important;
        }

        .widget-details_title td {
            font-size: 13px;
        }

        .widget-details {
            height: 20px;
            font-size: 12px;
        }

        .highcharts-credits {
            display: none
        }

        .searchbar input {
            background-image: none;
            padding: 8px 8px 8px 10px;
            height: initial;
        }

        .date-submit-btn {
            vertical-align: top;
        }

        #start_date,#end_date{
            font-size: 12px!important;
            padding: 3px 11px!important;
            width: 87px!important;
            height: 35px!important;
        }


        @media only screen  and (max-width: 950px) {
            .widget-header {
                font-size: 18px;
            }

            .widget-details {
                height: 20px;
                font-size: 11px;
            }
        }

        @media only screen  and (max-width: 860px) {
            .widget-header {
                font-size: 15px;
            }

            .widget-details {
                height: 20px;
                font-size: 12px;
            }

            .widget-details_title td {
                padding: 2px;
                font-size: 12px;
            }

            #dashboard-left {
                width: 100%;
            }
        }

        @media only screen  and (max-width: 710px) {
            .widgetcontent.nopadding {
                display: flex;
                flex-direction: column;
            }
        }

        /*
        .piecharts canvas {
            height: 100% !important;
            width: 100% !important;
        }
        */

    </style>
    <script type="text/javascript">

        function slideupDown(cls) {
            if (jQuery("." + cls).is(":hidden")) {
                jQuery(".mybar").slideUp("slow");
                jQuery("." + cls).slideDown("slow");
            } else {
                jQuery("." + cls).slideUp("slow");
            }
        }

        jQuery(document).ready(function () {
            jQuery('#start_date').datepicker({dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true});
            jQuery('#end_date').datepicker({dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true});
        });

        function searchValidate() {
            var start_date = jQuery("#start_date").val();
            var end_date = jQuery("#end_date").val();

            if (start_date > end_date) {
                jAlert('Start date is after end date. Please revise search.', 'Alert Dialog');
                return false;
            } else {
                jQuery('#forderdate').submit();
            }
        }
    </script>
</head>
<body>
<div class="mainwrapper">
    <?php include_once 'require/top.php'; ?>
    <div class="leftpanel">
        <?php include_once 'require/left_nav.php'; ?>
    </div>
    <!-- leftpanel -->
    <div class="rightpanel">
        <ul class="breadcrumbs">
            <li><a href="messages.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Distribution <span class="separator"></span></li>
            <li> Dashboard</li>
            <li class="right"><a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Color
                    Skins</a>
                <ul class="dropdown-menu pull-right skin-color">
                    <li><a href="default">Default</a></li>
                    <li><a href="navyblue">Navy Blue</a></li>
                    <li><a href="palegreen">Pale Green</a></li>
                    <li><a href="red">Red</a></li>
                    <li><a href="green">Green</a></li>
                    <li><a href="brown">Brown</a></li>
                </ul>
            </li>
            <?php require_once("lang_code.php"); ?>
        </ul>
        <div class="pageheader">
             <div id="search" class="searchbar" style="top:36px;float:right">
                <form id="forderdate" name="forderdate" method="get">
                    <div class="date_search_box"><span style="position: relative; bottom: 3px; font-size: 15px;">From&nbsp;</span>
                        <input id="start_date" name="start_date" type="text" autocomplete="off" placeholder="Start Date"
                               value="<?= $start_date ?>"
                               style="/* background: url('images/icons/search.png') no-repeat scroll 88px 12px #FFFFFF;  */font-size: 12px; width: 64px;">
                        &nbsp;
                        <span style="position: relative; bottom: 3px; font-size: 15px;">To&nbsp;</span>
                        <input id="end_date" name="end_date" type="text" autocomplete="off" placeholder="End Date"
                               value="<?= $end_date ?>"
                               style="/* background: url('images/icons/search.png') no-repeat scroll 87px 12px #FFFFFF;  */font-size: 12px; width: 64px;">
                        <button type="submit" class="btn btn-success btn-large date-submit-btn" onclick="javascript: return searchValidate();">Go</button>
                    </div>
<!--                      <div class="date_search"> <a href="javascript:document.forms.forderdate.submit();"><img width="47" src="images/search_btn.png"></a> </div>-->
              </form>
            </div> 
            <div class="pageicon"><span class="iconfa-comments"></span></div>
            <div class="pagetitle">
                <h5>Browse through your locations and customers.</h5>
                <h1>Dashboard</h1>
            </div>
        </div>
        <!--pageheader-->
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
                    <div id="dashboard-left" class="span8" style="width:67%">
                        <!--  Customer block-->
                        <h4 class="widgettitle">Distribution</h4>
                        <div class="widgetcontent ">
                            <div class="widgetcontent nopadding"
                                 style="border: 1px solid rgb(8, 102, 198); margin-bottom: 17px; padding:15px 5px 0 0; height:auto; overflow: hidden; ">
                                <div id="chart-container" style="width: 100%; height: 400px"></div>
                            </div>
                        </div>


                        
                            <!--end Market statistics -->

                            <!-- start Guest old block -->
                            <!--                            <div class="widgetcontent nopadding"-->
                            <!--                                 style="border: 1px solid rgb(8, 102, 198); margin-bottom: 17px; padding:15px 5px 0 0; height:auto; overflow: hidden;">-->
                            <!--                                <div class="widget_title">-->
                            <!--                                    <table width="95%" border="0" cellspacing="0" cellpadding="0"-->
                            <!--                                           style="float:left; padding: 5px 5px 10px;margin:1px 1px 17px 10px;">-->
                            <!--                                        <tr>-->
                            <!--                                            <td colspan="2" align="left" style="font-weight: bold; font-size: 15px;">-->
                            <!--                                                Guest-->
                            <!--                                            </td>-->
                            <!--                                        </tr>-->
                            <!--                                    </table>-->
                            <!--                                </div>-->
                            <!--                                <div>-->
                            <!--                                    <div class="widget_statistics">-->
                            <!--                                        <table width="95%" border="0" cellspacing="0" cellpadding="0"-->
                            <!--                                               style="float:left;border: 1px solid rgb(8, 102, 198); margin:1px 1px 17px 10px; padding: 5px 5px 10px;">-->
                            <!--                                            <tr>-->
                            <!--                                                <td colspan="2" align="center">Today</td>-->
                            <!--                                            </tr>-->
                            <!--                                            <tr>-->
                            <!--                                                <td colspan="2" align="center">-->
                            <!--                                                    <div id="piechart" class="piecharts"-->
                            <!--                                                         style="margin:1px; width:250px; height:250px;"></div>-->
                            <!--                                                </td>-->
                            <!--                                            </tr>-->
                            <!--
								<tr>
									<td align="center">
										<table width="80%" border="0" cellpadding="0" cellspacing="0">
										<?php //if ($access_restaurant=="yes") {  ?>
											<tr>
												<td><span class="blue"></span></td>
												<td>Restaurant Orders</td>
												<td><?php //echo number_format($orders_dd); ?></td>
											</tr>
										<?
                            //if ($access_hotel=="yes") {
                            ?>
											<tr>
												<td><span class="brown"></span></td>
												<td>Hotel Bookings </td>
												<td ><?php //echo number_format($hot_accounts_dd); ?></td>
											</tr>
										<?
                            //if ($access_retail=="yes") {
                            ?>
											<tr>
												<td><span class="lightblue"></span></td>
												<td>Retail Purchases </td>
												<td ><?php //echo number_format($ret_checks_dd); ?></td>
											</tr>
										<?
                            //if ($access_restaurant=="yes") {  ?>
											<tr>
												<td><span class="green"></span></td>
												<td>Reservations </td>
												<td ><?php //echo number_format($reservation_dd); ?></td>
											</tr>
										<? ?>
											<tr>
												<td><span class="orange"></span></td>
												<td>Reviews </td>
												<td ><?php //echo number_format($reviews_dd); ?></td>
											</tr>
											<tr style="height:23px;">
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
										</table>
									</td>
								</tr>
                                -->
                            <!--                                        </table>-->
                            <!--                                    </div>-->
                            <!--                                    <div class="widget_statistics">-->
                            <!--                                        <table width="95%" border="0" cellspacing="0" cellpadding="0"-->
                            <!--                                               style="float:left;border: 1px solid rgb(8, 102, 198); margin:1px 1px 17px 10px; padding: 5px 5px 10px;">-->
                            <!--                                            <tr>-->
                            <!--                                                <td colspan="2" align="center">Monthly</td>-->
                            <!--                                            </tr>-->
                            <!--                                            <tr>-->
                            <!--                                                <td colspan="2" align="center">-->
                            <!--                                                    <div id="piechart1" class="piecharts"-->
                            <!--                                                         style="margin:1px; width:250px; height:250px;"></div>-->
                            <!--                                                </td>-->
                            <!--                                            </tr>-->
                            <!--
                                <tr>
									<td align="center">
										<table width="80%" border="0" cellpadding="0" cellspacing="0">
									<?php // if ($access_restaurant=="yes") {  ?>
											<tr>
												<td><span class="blue"></span></td>
												<td>Restaurant Orders</td>
												<td><?php //echo number_format($orders_mm); ?></td>
											</tr>
										<?
                            // if ($access_hotel=="yes") {
                            ?>
											<tr>
												<td><span class="brown"></span></td>
												<td>Hotel Bookings </td>
												<td ><?php //echo number_format($hot_accounts_mm); ?></td>
											</tr>
										<?
                            //if ($access_retail=="yes") {
                            ?>
											<tr>
												<td><span class="lightblue"></span></td>
												<td>Retail Purchases </td>
												<td ><?php //echo number_format($ret_checks_mm); ?></td>
											</tr>
										<?
                            //	if ($access_restaurant=="yes") {  ?>
											<tr>
												<td><span class="green"></span></td>
												<td>Reservations </td>
												<td ><?php // echo number_format($reservation_mm); ?></td>
											</tr>
										<? ?>
											<tr>
												<td><span class="orange"></span></td>
												<td>Reviews </td>
												<td ><?php //echo number_format($reviews_mm); ?></td>
											</tr>
											<tr style="height:23px;">
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
										</table>
									</td>
								</tr>
                                -->
                            <!--                                        </table>-->
                            <!--                                    </div>-->
                            <!--                                    <div class="widget_statistics">-->
                            <!--                                        <table width="95%" border="0" cellspacing="0" cellpadding="0"-->
                            <!--                                               style="float:left;border: 1px solid rgb(8, 102, 198); margin:1px 1px 17px 10px; padding: 5px 5px 10px;">-->
                            <!--                                            <tr>-->
                            <!--                                                <td colspan="2" align="center">Yearly</td>-->
                            <!--                                            </tr>-->
                            <!--                                            <tr>-->
                            <!--                                                <td colspan="2" align="center">-->
                            <!--                                                    <div id="piechart2" class="piecharts"-->
                            <!--                                                         style="margin:1px; width:250px; height:250px;"></div>-->
                            <!--                                                </td>-->
                            <!--                                            </tr>-->
                            <!--
                                <tr>
									<td align="center">
										<table width="80%" border="0" cellpadding="0" cellspacing="0">
									<?php //if ($access_restaurant=="yes") {  ?>
											<tr>
												<td><span class="blue"></span></td>
												<td>Restaurant Orders</td>
												<td><?php // echo number_format($orders_yy); ?></td>
											</tr>
										<?
                            // if ($access_hotel=="yes") {
                            ?>
											<tr>
												<td><span class="brown"></span></td>
												<td>Hotel Bookings </td>
												<td ><?php // echo number_format($hot_accounts_yy); ?></td>
											</tr>
										<?
                            // if ($access_retail=="yes") {
                            ?>
											<tr>
												<td><span class="lightblue"></span></td>
												<td>Retail Purchases </td>
												<td ><?php // echo number_format($ret_checks_yy); ?></td>
											</tr>
										<?
                            // if ($access_restaurant=="yes") {  ?>
											<tr>
												<td><span class="green"></span></td>
												<td>Reservations </td>
												<td ><?php // echo number_format($reservation_yy); ?></td>
											</tr>
										<? ?>
											<tr>
												<td><span class="orange"></span></td>
												<td>Reviews </td>
												<td ><?php // echo number_format($reviews_yy); ?></td>
											</tr>
											<tr style="height:23px;">
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
											</tr>
										</table>
									</td>
								</tr>
                                -->
                            <!--                                        </table>-->
                            <!--                                    </div>    -->
                            <!--                                </div> -->
                            <!--                            </div>-->
                            <!-- end Guest old block -->
                        </div><!--widgetcontent-->


<!--new code-->
                     <div class="span4" style="padding: 0;float:right">
            <div class="clearfix"> 
                <h4 class="widgettitle">Today</h4>
            </div>
            <div class="widgetcontent" style="padding: 0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered responsive">
                   <!--  <tr class="title">
                        &nbsp;
                    </tr>
                    <tr>
                        <h3 align="center"><b><?php echo number_format($active_clients); ?></b></h3>
                        <p align="center" style="margin: 0;">Number of Active Clients</p>
                    </tr>
                    <tr class="title">
                        &nbsp;
                    </tr> -->
                    <thead>
                        <tr>
                            <th class="head1" style="text-align:center;word-spacing: 2px;"># of Routes</th>
                            <th class="head0" style="text-align:center;word-spacing: 2px;">Avg Load Time</th>
                            <th  class="head1" style="text-align:center;word-spacing: 2px;">Tot Case Count</th>
                        </tr>
                    </thead>
                    <tr>
                        <td class="center"><?php echo number_format($no_of_routes); ?></td>
                        <td class="center"><?php echo foo($avg_load_time); ?></td>
                        <td  class="center"><?php echo number_format($case_counts); ?></td>
                    </tr>
                </table>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered responsive">

                    <thead>
                        <tr>
                            <th class="head1" style="text-align:center; word-spacing: 2px;">Quick&nbsp;Load&nbsp;Time</th>
                            <th class="head0" style="text-align:center;word-spacing: 2px;">Longest&nbsp;Load&nbsp;Time</th>

                        </tr>
                    </thead>
                    <tr>
                        <td style="width: 50%" class="center"><?php echo foo($quick_load_time); ?></td>
                        <td style="width: 50%" class="center"><?php echo foo($longest_load_time); ?></td>
                    </tr>
                </table>

                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered responsive">

                    <thead>
                        <tr>
                            <th class="head1" style="text-align:center;word-spacing: 2px;">First Time Out</th>
                            <th class="head0" style="text-align:center;word-spacing: 2px;">Last Time Out</th>

                        </tr>
                    </thead>
                    <tr>
                        <td style="width: 50%" class="center"><?php echo $first_time_out; ?></td>
                        <td style="width: 50%" class="center"><?php echo $last_time_out; ?></td>
                    </tr>
                </table>

            </div> 
        </div>

<!--new code ends-->
<!-- 


                    <div id="dashboard-right" class="span4" style="padding: 0;float: right">

                        <h4 class="widgettitle" onClick="slideupDown('myclient')"
                            style="cursor:pointer; border-bottom:1px solid white;">Top Clients</h4>
                        <div class="widgetcontent nopadding mybar myclient" style="display:none; margin-bottom:0px;"
                             id="top_client_div">
                            <table id="dyntable" class="table table-bordered responsive">
                                <thead>
                                <tr>
                                    <th class="head0 center" style="width:40%">Name</th>
                                    <th class="head1 center" style="width:30%">Phone</th>
                                    <th class="head0 center" style="width:30%">Email</th>
                                </tr>
                                </thead>
                                <tbody role="alert" aria-live="polite" aria-relevant="all">
                                <?php
                                $i = 0;
                                while ($row = mysql_fetch_array($topClientRes)) {
                                    $i++;
                                    ?>
                                    <tr class="gradeX odd" id="<?= $row['client_id']; ?>" style="cursor:pointer;">
                                        <td><?php echo $row['name'] ?></td>
                                        <td><?php echo $row["phone"]; ?></td>
                                        <td align="right" valign="top"
                                            class="right"><?php echo $currencySign . "" . $row["email"]; ?></td>
                                    </tr>
                                <?php }
                                if ($i == 0)
                                    echo $noDataHtml;
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($access_retail == "yes" || $noOfRetails > 0) { ?>
                            <h4 class="widgettitle" onClick="slideupDown('mycashier')"
                                style="cursor:pointer; border-bottom:1px solid white;">Top Cashiers</h4>
                            <div class="widgetcontent nopadding mybar mycashier"
                                 style="display:none; margin-bottom:0px;">
                                <table id="dyntable" class="table table-bordered responsive">
                                    <thead>
                                    <tr>
                                        <th class="head0 center" style="width:4%">#</th>
                                        <th class="head1 center">Cashier</th>
                                        <th class="head0 center"># of Sales</th>
                                    </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                    <?php
                                    $i = 0;
                                    while ($row = mysql_fetch_array($topCashierRes)) {
                                        $i++;
                                        ?>
                                        <tr class="gradeX odd" id="<?= $row['employee_id']; ?>" style="cursor:pointer;">
                                            <td><?php echo $i ?></td>
                                            <td><?php echo $row["name"]; ?></td>
                                            <td align="right" valign="top"
                                                class="right"><?php echo $currencySign . "" . $row["total"]; ?></td>
                                        </tr>
                                    <?php }
                                    if ($i == 0)
                                        echo $noDataHtml;
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                        <?php if ($access_restaurant == "yes" || $noOfRestaurants > 0) { ?>
                            <h4 class="widgettitle" onClick="slideupDown('myserver')"
                                style="cursor:pointer; border-bottom:1px solid white;">Top Servers</h4>
                            <div class="widgetcontent nopadding mybar myserver"
                                 style="display:none; margin-bottom:0px;">
                                <table id="dyntable" class="table table-bordered responsive">
                                    <thead>
                                    <tr>
                                        <th class="head0 center" style="width:4%">#</th>
                                        <th class="head1 center">Server</th>
                                        <th class="head0 center"># Served</th>
                                    </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                    <?php
                                    $i = 0;
                                    while ($row = mysql_fetch_array($topServerRes)) {
                                        $i++;
                                        ?>
                                        <tr class="gradeX odd">
                                            <td><?php echo $i ?></td>
                                            <td><?php echo $row["first_name"] . " " . $row['last_name']; ?></td>
                                            <td><?php echo $row["total"]; ?></td>
                                        </tr>
                                    <?php }
                                    if ($i == 0)
                                        echo $noDataHtml;
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                        <?php if ($access_hotel == "yes" || $noOfHotels > 0) { ?>
                            <h4 class="widgettitle" onClick="slideupDown('myrooms')"
                                style="cursor:pointer; border-bottom:1px solid white;">Best Selling Room Types</h4>
                            <div class="widgetcontent nopadding mybar myrooms" style="display:none; margin-bottom:0px;">
                                <table id="dyntable" class="table table-bordered responsive">
                                    <thead>
                                    <tr>
                                        <th class="head0 center" style="width:4%">#</th>
                                        <th class="head1 center">Rooms</th>
                                        <th class="head0 center">Revenue</th>
                                    </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                    <?php
                                    $i = 0;
                                    while ($row = mysql_fetch_array($topSellingRooms)) {
                                        $i++;
                                        ?>
                                        <tr class="gradeX odd">
                                            <td><?php echo $i ?></td>
                                            <td><?php echo $row["code"]; ?></td>
                                            <td align="right" valign="top"
                                                class="right"><?php echo $currencySign . "" . $row["total"]; ?></td>
                                        </tr>
                                    <?php }
                                    if ($i == 0)
                                        echo $noDataHtml;
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                        <?php if ($access_restaurant == "yes" || $noOfRestaurants > 0) { ?>
                            <h4 class="widgettitle" onClick="slideupDown('myarticles')"
                                style="cursor:pointer; border-bottom:1px solid white;">Best Selling Menu Articles</h4>
                            <div class="widgetcontent nopadding mybar myarticles"
                                 style="display:none; margin-bottom:0px;">
                                <table id="dyntable" class="table table-bordered responsive">
                                    <thead>
                                    <tr>
                                        <th class="head0 center" style="width:4%">#</th>
                                        <th class="head1 center">Item</th>
                                        <th class="head0 center">Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                    <?php
                                    $i = 0;
                                    while ($row = mysql_fetch_array($topSellingArticles)) {
                                        $i++;
                                        ?>
                                        <tr class="gradeX odd">
                                            <td><?php echo $i ?></td>
                                            <td><?php echo $row["item"]; ?></td>
                                            <td><?php echo $row["quantity"]; ?></td>
                                        </tr>
                                    <?php }
                                    if ($i == 0)
                                        echo $noDataHtml;
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                        <?php if ($access_retail == "yes" || $noOfRetail > 0) { ?>
                            <h4 class="widgettitle" onClick="slideupDown('myitems')" style="cursor:pointer;">Best
                                Selling Items</h4>
                            <div class="widgetcontent nopadding mybar myitems" style="display:none; margin-bottom:0px;">
                                <table id="dyntable" class="table table-bordered responsive">
                                    <thead>
                                    <tr>
                                        <th class="head0 center" style="width:4%">#</th>
                                        <th class="head1 center">Item</th>
                                        <th class="head0 center">Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all">
                                    <?php
                                    $i = 0;
                                    while ($row = mysql_fetch_array($topSellingItems)) {
                                        $i++;
                                        ?>
                                        <tr class="gradeX odd">
                                            <td><?php echo $i ?></td>
                                            <td><?php echo $row["item"]; ?></td>
                                            <td><?php echo $row["quantity"]; ?></td>
                                        </tr>
                                    <?php }
                                    if ($i == 0)
                                        echo $noDataHtml;
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div> dashboard-right-->
              <!--  </div>row-fluid-->
 <?php include_once 'require/footer.php'; ?>
            </div>     <!--maincontentinner-->
        </div> <!--maincontent-->
    </div>  <!--rightpanel-->
</div><!--mainwrapper-->

<script language="JavaScript">
    jQuery(document).ready(function ($) {
        //you can now use $ as your jQuery object.
        var xAxis = {
            categories: <?= json_encode($dates); ?>
        };
        var yAxis = {
            title: {
                text: ''
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        };

        var tooltip = {
            shared: true
        };

        var legend = {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            floating: true,
            borderWidth: 0,
            x: 60,
            y: -15
        };

        var series = [
            {
                name: 'Number of bays by day',
                data:  <?= json_encode($clientsCreated); ?>
            },
            {
                name: 'Number of case counts by day',
                data: <?= json_encode($clientsUpdated); ?>
            }
        ];

        var json = {};
        json.title = ' ';
        json.xAxis = xAxis;
        json.yAxis = yAxis;
        json.tooltip = tooltip;
        json.legend = legend;
        json.series = series;

        $('#chart-container').highcharts(json);
    });

</script>

<script type="text/javascript">
    jQuery(document).ready(function () {
        /**PIE CHART IN MAIN PAGE WHERE LABELS ARE INSIDE THE PIE CHART**/
        <?php
        //juni -> 03.07.2014 -> display piechart based on access to restaurant, hotel and retail
        $resData_dd = "";$resData_mm = "";$resData_yy = "";$resColor = "";
        $hotData_dd = "";$hotData_mm = "";$hotData_yy = "";$hotColor = "";
        $retData_dd = "";$retData_mm = "";$retData_yy = "";    $retColor = "";
        if ($access_restaurant == "yes") {
            $resData_dd = "{data:" . number_format($orders_dd) . "},{data:" . number_format($reservation_dd) . "},";
            $resData_mm = "{data:" . number_format($orders_mm) . "},{data:" . number_format($reservation_mm) . "},";
            $resData_yy = "{data:" . number_format($orders_yy) . "},{data:" . number_format($reservation_yy) . "},";
            $resColor = "'#680fb3','#9ab30f',";
        }
        if ($access_hotel == "yes") {
            $hotData_dd = "{data:" . number_format($hot_accounts_dd) . "},{data:" . number_format($reviews_dd) . "},";
            $hotData_mm = "{data:" . number_format($hot_accounts_mm) . "},{data:" . number_format($reviews_mm) . "},";
            $hotData_yy = "{data:" . str_replace(',', '', number_format($hot_accounts_yy)) . "},{data:" . str_replace(',', '', number_format($reviews_yy)) . "},";
            $hotColor = "'#a44646','#de8445',";
        } else { //i do not hide reviews from the pie chart (as requested)
            $hotData_dd = "{data:" . number_format($reviews_dd) . "},";
            $hotData_mm = "{data:" . number_format($reviews_mm) . "},";
            $hotData_yy = "{data:" . str_replace(',', '', number_format($reviews_yy)) . "},";
            $hotColor = "'#de8445',";
        }
        if ($access_retail == "yes") {
            $retData_dd = "{data:" . number_format($ret_checks_dd) . "}";
            $retData_mm = "{data:" . number_format($ret_checks_mm) . "}";
            $retData_yy = "{data:" . str_replace(',', '', number_format($ret_checks_yy)) . "}";
            $retColor = "'#376193'";
        }
        $ddData = rtrim($resData_dd . "" . $hotData_dd . "" . $retData_dd, ',');
        $mmData = rtrim($resData_mm . "" . $hotData_mm . "" . $retData_mm, ',');
        $yyData = rtrim($resData_yy . "" . $hotData_yy . "" . $retData_yy, ',');

        $color = rtrim($resColor . "" . $hotColor . "" . $retColor, ',');
        if($orders_dd != 0 || $reservation_dd != 0 || $hot_accounts_dd != 0 || $reviews_dd != 0 || $ret_checks_dd != 0){?>
        /*
        var data = [{data:<?php echo number_format($orders_dd); ?>},{data:<?php echo number_format($reservation_dd); ?>}
		,{data:<?php echo number_format($hot_accounts_dd); ?>},{data:<?php echo number_format($reviews_dd); ?>},{data:<?php echo number_format($ret_checks_dd); ?>}]
		jQuery.plot(jQuery("#piechart"), data, {
		colors: ['#680fb3','#9ab30f','#a44646','#de8445','#376193'],		   
		series: {pie: { show: true}}
		});*/
        var data = [<?=$ddData?>];
        var pieData = [];
        var labels = ['Restaurant Orders', 'Hotel Bookings', 'Retail Purchases', 'Reservations', 'Reviews'];
        var color = ['#3498db', '#e74c3c', '#1abc9c', '#f1c40f', '#a66bbe', '#ecf0f1', '#95a5a6', '#34495e', '#2ecc71', '#e67e22'];
        var ir = 0;
        var data_exist = false;
        jQuery.each(data, function (i, curr) {
            console.log(curr);
            /* Set data */


            /* 1. Check data empty */

            if (curr.data != '' && curr.data != '0') {
                data_exist = true;
                var rev_object = new Object();
                rev_object.order = parseInt(ir + 1);
                rev_object.value = parseInt(curr.data);
                //rev_object.label 	 = labels[i] +':';
                //rev_object.label 	 = labels[i] + ': -|nl|- ' + curr.data;
                // rev_object.label 	 = labels[i] + ': ' + curr.data;
                rev_object.label = labels[i] + ': ' + parseInt(curr.data);
                rev_object.color = color[ir];
                rev_object.highlight = color[ir];
                pieData.push(rev_object);
                ir = ir + 1;
                console.log('object : ');
                console.log(rev_object);
            }
        });
        var options = {

            tooltips: {
                callbacks: {
                    label: function (tooltipItems, data) {
                        return data.labels[tooltipItems.index] +
                            " : " +
                            data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] +
                            ' %';
                    }
                }
            },

            tooltipTemplate: "<%= label %>",
            onAnimationComplete: function () {
            },
            responsive: true,
            //tooltipEvents: [],
            showTooltips: true,
            tooltipFontSize: 16,
            tooltipCornerRadius: 2,
            radius: 1,
            showTooltip: function (ChartElements, forceRedraw) {
            }

        }
        var pca = '<div id="piechart-chart-holder" style="text-align: center;"><canvas id="piechart-chart-area" width="175" height="175" style="width: 100% !important; height: 100% !important;"/></div>';
        jQuery('#piechart').html(pca);
        var ctx = document.getElementById("piechart-chart-area").getContext("2d");
        window.myPie = new Chart(ctx).Pie(pieData, options);
        /*
        jQuery.plot(jQuery("#piechart"), data, {
            colors: [<?=$color?>],
			series: {pie: { show: true}}
		});
		*/

        <?php } ?>

        <?php if(number_format($orders_mm) != 0 || number_format($reservation_mm) != 0 || number_format($hot_accounts_mm) != 0 || number_format($reviews_mm) != 0 || number_format($ret_checks_mm) != 0 ){?>
        var data = [<?=$mmData?>];
        var pieData = [];
        var labels = ['Restaurant Orders', 'Hotel Bookings', 'Retail Purchases', 'Reservations', 'Reviews'];
        var color = ['#3498db', '#e74c3c', '#1abc9c', '#f1c40f', '#a66bbe', '#ecf0f1', '#95a5a6', '#34495e', '#2ecc71', '#e67e22'];
        var ir = 0;
        var data_exist = false;
        jQuery.each(data, function (i, curr) {
            if (curr.data != '' && curr.data != '0') {
                data_exist = true;
                var rev_object = new Object();
                rev_object.order = parseInt(ir + 1);
                rev_object.value = parseInt(curr.data);
                rev_object.label = labels[i] + ': ' + parseInt(curr.data);
                rev_object.color = color[ir];
                rev_object.highlight = color[ir];
                pieData.push(rev_object);
                ir = ir + 1;
                console.log('object : ');
                console.log(rev_object);
            }
        });
        var options = {

            tooltips: {
                callbacks: {
                    label: function (tooltipItems, data) {
                        return data.labels[tooltipItems.index] +
                            " : " +
                            data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] +
                            ' %';
                    }
                }
            },

            tooltipTemplate: "<%= label %>",
            onAnimationComplete: function () {
            },
            responsive: true,
            //tooltipEvents: [],
            showTooltips: true,
            tooltipFontSize: 16,
            tooltipCornerRadius: 2,
            radius: 1,
            showTooltip: function (ChartElements, forceRedraw) {
            }

        }
        var pca = '<div id="piechart1-chart-holder" style="text-align: center;"><canvas id="piechart1-chart-area" width="175" height="175" style="width: 100% !important; height: 100% !important;"/></div>';
        jQuery('#piechart1').html(pca);
        var ctx = document.getElementById("piechart1-chart-area").getContext("2d");
        window.myPie = new Chart(ctx).Pie(pieData, options);


        <?php } ?>

        <?php if(number_format($orders_yy) != 0 || number_format($reservation_yy) != 0 || number_format($hot_accounts_yy) != 0 || number_format($reviews_yy) != 0 || number_format($ret_checks_yy) != 0 ){?>
        var data = [<?=$yyData?>];
        var pieData = [];
        var labels = ['Restaurant Orders', 'Hotel Bookings', 'Retail Purchases', 'Reservations', 'Reviews'];
        var color = ['#3498db', '#e74c3c', '#1abc9c', '#f1c40f', '#a66bbe', '#ecf0f1', '#95a5a6', '#34495e', '#2ecc71', '#e67e22'];
        var ir = 0;
        var data_exist = false;
        jQuery.each(data, function (i, curr) {
            if (curr.data != '' && curr.data != '0') {
                data_exist = true;
                var rev_object = new Object();
                rev_object.order = parseInt(ir + 1);
                rev_object.value = parseInt(curr.data);
                rev_object.label = labels[i] + ':' + curr.data;
                rev_object.color = color[ir];
                rev_object.highlight = color[ir];
                pieData.push(rev_object);
                ir = ir + 1;
                console.log('object : ');
                console.log(rev_object);
            }
        });
        var options = {
            tooltips: {
                callbacks: {
                    label: function (tooltipItems, data) {
                        return data.labels[tooltipItems.index] +
                            " : " +
                            data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] +
                            ' %';
                    }
                }
            },

            tooltipTemplate: "<%= label %>",
            onAnimationComplete: function () {
            },
            responsive: true,
            //tooltipEvents: [],
            showTooltips: true,
            tooltipFontSize: 16,
            tooltipCornerRadius: 2,
            radius: 1,
            showTooltip: function (ChartElements, forceRedraw) {
            }
        }
        var pca = '<div id="piechart2-chart-holder" style="text-align: center;"><canvas id="piechart2-chart-area" width="175" height="175" style="width: 100% !important; height: 100% !important;"/></div>';
        jQuery('#piechart2').html(pca);
        var ctx = document.getElementById("piechart2-chart-area").getContext("2d");
        window.myPie = new Chart(ctx).Pie(pieData, options);
        <?php } ?>



        //datepicker
        jQuery('#datepicker').datepicker();
        // tabbed widget
        jQuery('.tabbedwidget').tabs();
    });
    var $j = jQuery.noConflict();
    $j(document).ready(function () {
        $j('[data-toggle="modal"]').bind('click', function (e) {
            jQuery('#start_date').datepicker('disable');
            e.preventDefault();
            var url = $j(this).attr('href');
            if (url.indexOf('#') == 0) {
                $j('#response_modal').modal('open');
            } else {
                $j.get(url, function (data) {
                    $j('#response_modal').html(data);
                    $j('#response_modal').modal();
                    jQuery('#start_date').datepicker('enable');
                }).success(function () {
                    //$j('input:text:visible:first').focus();  //focuses datetimepicker
                });
            }
        });

        setTimeout(function () {
            jQuery('#top_client_div').load('crm_top_clients.php', function (response, status, xhr) {
                if (status == "error") {
                    var msg = "Sorry but there was an error: ";
                    console.log(msg + xhr.status + " " + xhr.statusText);
                    //jQuery( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
                }
            });
        }, 2000);
    });

</script>
</body>
</html>