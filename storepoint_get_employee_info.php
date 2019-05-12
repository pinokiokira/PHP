<?php
require_once 'require/security.php';
include 'config/accessConfig.php';

$emp_id = $_GET['emp_id'];

$employee = mysql_query("SELECT id, emp_id, first_name, last_name, status, email, telephone FROM employees WHERE  emp_id = '$emp_id' ");
$row = mysql_fetch_assoc($employee);

echo json_encode($row); die;

