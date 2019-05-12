<?php
$token=base64_encode(strtotime('-7 hours')."+0");
echo $token; 
?>