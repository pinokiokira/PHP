<?php
 header("Access-Control-Allow-Origin: *");
session_start(); 
 if (file_exists('../includes/connectdb.php'))
   include_once("../includes/connectdb.php");
  $ret="1";
  $mes="";
  $dest="";
   if (MAINTENANCE_MODE){
	  $mes=MAINTENANCE_MESSAGE;
	   $ret="2";
  } 
   if (MAINTENANCE_MODE && LOGIN_ACCESSIBLE == false){
	   $mes=MAINTENANCE_MESSAGE;
	   $ret="3";
           $dest = UNDER_MAINTENANCE;
  }
  if ($connection!=""){
           $mes=MAINTENANCE_MESSAGE;
	   $ret="3";
           $dest = UNDER_MAINTENANCE;
  }
      
  $kj=array(mes=>$mes,ret=>$ret,dest=>$dest);
  
echo json_encode( $kj);
?>