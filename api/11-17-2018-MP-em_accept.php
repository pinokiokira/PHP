<?php
/**
 * This API is used to update whether an order is accepted or declined to be delivered by the employee.
 * @author Adnan Siddiqi
 * @since 1.0
 */

error_reporting(0);
$return = array();
$databasereadjson=0;

include_once("../../includes/connectdb.php");

include_once 'functions.php';
/**
 * ATTENTION: When goes live it should be changed to !=POST
 */
/*if($_SERVER["REQUEST_METHOD"] != "POST")
{
    $return = array
    (
        "response"=>"05",
        "desc" => "Only POST requests allowed"
    );
}
else
{*/
    if
        (
            !isset($_REQUEST["delivery_id"])
            &&
            !isset($_REQUEST["assign_accepted"])
            &&
            !isset($_REQUEST["empmaster_id"])
        )
        {
            $return[] = array
            (
                "response"=>"01",
                "desc" => "Input Parameters not found"
            );
        }
        elseif
        (
            empty($_REQUEST["delivery_id"])
            ||
            empty($_REQUEST["assign_accepted"])
            ||
            empty($_REQUEST["empmaster_id"])
        )
        {
            $return[] = array
            (
                "response"=>"02",
                "desc" => "All fields are required"
            );
        }
        else
        {
            $delivery_id = intval($_REQUEST["delivery_id"]);
            $assign_accepted = ucfirst(strtolower(mysql_real_escape_string($_REQUEST["assign_accepted"])));
            $emp = intval($_REQUEST['empmaster_id']);
            $date = date('Y-m-d H:i:s');
            if($assign_accepted == 'Yes'){
                $append = ",assign_accepted_datetime = '$date'";
                $append2 = ",Assign_accepted_datetime = '$date'";
            }else{
                $append = ",assign_declined_datetime = '$date'";
            }
			if($assign_accepted == 'Yes') {
            $sql = "UPDATE client_delivery_employee
                    SET assign_accepted = '$assign_accepted'
                    " . $append . "
                    WHERE delivery_id='$delivery_id'";
			}
			else {
				 $sql = "UPDATE client_delivery_employee
                    SET assign_accepted = '$assign_accepted'
                    " . $append . "
                    WHERE delivery_id='$delivery_id'  AND empmaster_id='$emp'";
			}
            $result = mysql_query($sql);

            if($assign_accepted == 'Yes'){
                $sql = "UPDATE client_delivery
                        SET Assign_accepted_by = '$emp'
                        " . $append2 . "
                        WHERE delivery_id = $delivery_id";
                $result = mysql_query($sql);
            }

            if(!$result)
            {
                $return[] = array(
                "response"=>"03",
                "desc" => "Syntax Error: ".mysql_error()
                );
            }
            else
            {
                $last_id = mysql_affected_rows();
                $return[] = array
                (
                    "response"=>"00",
                    "desc" => "OK",
                    "result" => $last_id
                );
            }
        }
//}
print json_encode($return);
?>
