<?php

/*
 * Run this script in CLI using key as parameter 1 and secret as parameter 2, e.g.
 * 			php test.php yourFactualKey yourFactualSecret [logFile]
 * See https://github.com/Factual/factual-php-driver/wiki/Getting-Started
 */

//if (isset($argv[1])){
	$key = 'b7tFhSS94ZceQeOCy3D7HGcKFzvSVP7L0ERq9HrQ';
	$secret = 'oYmNcDUxQpGFr8HEDxDZgpKbizzOoEuVKIDH1sfx';
	if (isset($argv[3])){$logFile = $argv[3];} else {$logFile="";}
/*} else {
	echo "Usage: php test.php key secret [log file]\n";
	echo "Add your key and secret as parameters to this command line script. See https://github.com/Factual/factual-php-driver/wiki/Getting-Started for more info\n";
	exit;
}*/

if (empty($key) || empty($secret)){
	echo "Your Facual Key and Secret are required parameters. See https://github.com/Factual/factual-php-driver/wiki/Getting-Started for more info\n";
	exit;
}


//Set error level -- best not to change this.
error_reporting (E_ERROR);


require_once('Factual.php');
$factual = new Factual($key,$secret);
$tableName = "products-cpg";
$query = new FactualQuery;
    $query->limit(3);
    $res = $factual->fetch("places", $query);
    print_r($res->getData());
echo "<br>TESTET<br>";
$query = new FactualQuery;
$query->field("upc")->equal("052000131512"); 
$res = $factual->fetch($tableName, $query); 
print_r($res->getData());


/*require_once('FactualTest.php');
	
//Run tests	
$factualTest = new factualTest($key,$secret);	
$factualTest->setLogFile($logFile);   
$factualTest->test();
*/
?>
