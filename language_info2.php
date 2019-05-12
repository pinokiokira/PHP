<?php 
$_SESSION['lang'] =  'en';
$sql = "SELECT * FROM  translate";
$result = mysql_query($sql);
while ($row = mysql_fetch_assoc($result)){
   if($_GET['lang'] =='fr')
   {
  $_SESSION[$row['Word_on_page']] =$row['French'];
   $_SESSION['lang'] =  'fr';
   $_SESSION['mainlang'] =  'French';
   } 
  else if($_GET['lang'] =='es')
   {
    $_SESSION[$row['Word_on_page']] =$row['Spanish'];
    $_SESSION['lang'] =  'es';
    $_SESSION['mainlang'] =  'Spanish';
   } 
   else if($_GET['lang'] =='nl')
   {
  $_SESSION[$row['Word_on_page']] =$row['Dutch'];
   $_SESSION['lang'] =  'nl';
   $_SESSION['mainlang'] =  'Dutch';
   } 
   else if($_GET['lang'] =='ja')
   {
    $_SESSION[$row['Word_on_page']] =$row['Japanese'];
   $_SESSION['lang'] =  'ja';
   $_SESSION['mainlang'] =  'Japanese';
   } 
   else if($_GET['lang'] =='ar')
   {
   $_SESSION[$row['Word_on_page']] =$row['Arabic'];
   $_SESSION['lang'] =  'ar';
    $_SESSION['mainlang'] =  'Arabic';
   }
    else if($_GET['lang'] =='pt')
   {
   $_SESSION[$row['Word_on_page']] =$row['Portuguese'];
   $_SESSION['lang'] =  'pt';
    $_SESSION['mainlang'] =  'Portuguese';
   }
    else if($_GET['lang'] =='it')
   {
   $_SESSION[$row['Word_on_page']] =$row['Italian'];
   $_SESSION['lang'] =  'it';
    $_SESSION['mainlang'] =  'Italian';
   }
    else if($_GET['lang'] =='ru')
   {
   $_SESSION[$row['Word_on_page']] =$row['Russian'];
   $_SESSION['lang'] =  'ru';
    $_SESSION['mainlang'] =  'Russian';
   }
     else if($_GET['lang'] =='ko')
   {
   $_SESSION[$row['Word_on_page']] =$row['Korean'];
   $_SESSION['lang'] =  'ko';
   $_SESSION['mainlang'] =  'Korean';
   }
     else if($_GET['lang'] =='el')
   {
   $_SESSION[$row['Word_on_page']] =$row['Greek'];
   $_SESSION['lang'] =  'el';
   $_SESSION['mainlang'] =  'Greek';
   }
     else if($_GET['lang'] =='de')
   {
   $_SESSION[$row['Word_on_page']] =$row['German'];
   $_SESSION['lang'] =  'de';
   $_SESSION['mainlang'] =  'German';
   }
      else if($_GET['lang'] =='hi')
   {
   $_SESSION[$row['Word_on_page']] =$row['Hindi'];
   $_SESSION['lang'] =  'hi';
   $_SESSION['mainlang'] =  'Hindi';
   }
      else if($_GET['lang'] =='no')
   {
   $_SESSION[$row['Word_on_page']] =$row['Norwegian'];
   $_SESSION['lang'] =  'no';
   $_SESSION['mainlang'] =  'Norwegian';
   }
      else if($_GET['lang'] =='fi')
   {
   $_SESSION[$row['Word_on_page']] =$row['Finnish'];
   $_SESSION['lang'] =  'fi';
   $_SESSION['mainlang'] =  'Finnish';
   }
   else if($_GET['lang'] =='sv')
   {
   $_SESSION[$row['Word_on_page']] =$row['Swedish'];
   $_SESSION['lang'] =  'sv';
    $_SESSION['mainlang'] =  'Swedish';
   }
   else if($_GET['lang'] =='zh-CN')
   {
   $_SESSION[$row['Word_on_page']] =$row['Chinese'];
   $_SESSION['lang'] =  'zh-CN';
    $_SESSION['mainlang'] =  'Chinese';
   }
   else if($_GET['lang'] =='en'){
   $_SESSION[$row['Word_on_page']] = $row['Word_on_page'];
	$_SESSION['lang'] =  'en';
	 $_SESSION['mainlang'] =  'English';
   } 
		
   
}
	
?>
