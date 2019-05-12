<?php 
session_start();
/*if ($_SESSION['bouser'] == "" || $_SESSION['access_purchases'] != 'yes') {
    header("Location: index.php");
}*/
//echo "here"; exit;
include_once("../../internalaccess/connectdb.php"); 
if($_POST['submitted'] == 1){
    $response = array('code' => 0);

    foreach(array_keys($_POST) as $p){
        $_POST[$p] = mysql_real_escape_string($_POST[$p]);
    }

    if($_POST['item'] == ''){//new global item
        $query1 = "SELECT id FROM inventory_items WHERE description = '" . $_POST['description']  . "' LIMIT 1";
        $result1 = mysql_query($query1) or die(mysql_error());
        if(mysql_num_rows($result1) == 0){
            $query2 = "INSERT INTO inventory_items
                      SET item_id='" . $_POST['item_id']  . "',
                          inv_group_id='" . $_POST['group']  . "',
                          status='active',
                          description='" . $_POST['description']  . "',
                          notes='" . $_POST['notes']  . "',
                          unit_type='" . $_POST['unit_type']  . "',
                          manufacturer='" . $_POST['manufacturer']  . "',
                          model_number='',
                          image='',
                          taxable='" . $_POST['taxable']  . "',
                          created_by='" . $_SESSION['bouser'] . "',
                          created_on='BackOffice',
                          created_dt='" . date('Y-m-d H:i:s')  . "'";
            $result2 = mysql_query($query2) or die(mysql_error());
            $_POST['item'] = mysql_insert_id();
        }else{
            $row1 = mysql_fetch_row($result1);
            $_POST['item'] = $row1[0];
        }
    }

    $query3 = "SELECT * FROM vendor_items WHERE vendor_id='" . $_POST['vendor']  . "' AND inv_item_id='" . $_POST['item'] . "'";
    $result3 = mysql_query($query3) or die(mysql_error());
    if(mysql_num_rows($result3) == 0){
        $query4 = "INSERT INTO vendor_items
              SET vendor_id='" . $_POST['vendor']  . "',
                  inv_item_id='" . $_POST['item']  . "',
                  pack_size='" . $_POST['pack_size']  . "',
                  pack_unittype='" . $_POST['pack_unittype']  . "',
                  qty_in_pack='" . $_POST['qty_in_pack']  . "',
                  qty_in_pack_unittype='" . $_POST['qty_in_pack_unittype']  . "',
                  tax_percentage='" . $_POST['tax_percentage']  . "',
                  price='" . $_POST['price']  . "',
                  promotion='',
                  promotion_price=''";
        $result4 = mysql_query($query4) or die(mysql_error());

        $response['code'] = 1;
        $response['group'] = $_POST['group'];
        $response['inventory_item'] =  $_POST['item'];
        $response['vendor_item'] = mysql_insert_id();
        $response['unit_type'] = $_POST['unit_type'];
        $response['qty_sz'] = $_POST['unit_type'];
        $response['qty_sz_unit'] = $_POST['qty_sz'];
        $response['price'] = $_POST['price'];

    }else{//vendor item already exists
        $response['code'] = 2;
    }
    echo json_encode($response);
}