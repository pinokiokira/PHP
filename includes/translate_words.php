<?php
/*
*  @created Ionut Irofte - juniorionut @ elance
*  @version $Id: translate_words.php,v 1.0 2:34 PM 7/16/2014 juni $
*  -> [req 1.36  - 16.07.2014]
		-> Simple script to get translations for a language
*/

if ($_SESSION['mainlang']!='') {
	
	//$sql = "SELECT DISTINCT(Word_on_page) as Word_on_page FROM  translate where  Word_on_page NOT LIKE ('%!%') order by Word_on_page";
	if ($_SESSION['mainlang']=='English')
		$sql = "SELECT DISTINCT(Word_on_page) as Word_on_page FROM  translate order by Word_on_page";
	else
		$sql = "SELECT DISTINCT(Word_on_page) as Word_on_page,".$_SESSION['mainlang']." FROM translate order by Word_on_page";
	
	mysql_set_charset("utf8");
	$result = mysql_query($sql) or die (mysql_error());
	 while ($row = mysql_fetch_assoc($result)){
		if ($row['Word_on_page']!='lang') {
			
			if ($_SESSION['mainlang']=='English') {
				$word = preg_replace('[^A-Za-z0-9\. -]', '', $row['Word_on_page']);
				if (strlen($word)>1)
					$_SESSION[$word] = $row['Word_on_page'];
			} else  {
				
				$word =  preg_replace('[^A-Za-z0-9\. -]', '',$row[$_SESSION['mainlang']]);
				if (strlen($word)>1)
					$_SESSION[$row['Word_on_page']] =	$word ;
			}
		}
	} 
}
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";die;
?>
