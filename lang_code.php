<?php
if(isset($_GET['lang']) && $_GET['lang']!=""){
	$_SESSION['lang']= $_GET['lang'];
} else if (isset($_POST['lang']) && $_POST['lang']!=""){
	$_SESSION['lang'] = $_POST['lang'];
}


if (empty($_SERVER['QUERY_STRING']))//display something
	$_SERVER['QUERY_STRING'] = "by=".$_SESSION["SITENAME"];
switch ($_SESSION['lang']) {
	case 'fr':
		$_SESSION['mainlang'] =  'French'; break;
	case 'es':
		$_SESSION['mainlang'] =  'Spanish'; break;
	case 'nl':
		$_SESSION['mainlang'] =  'Dutch'; break;		
	case 'ja':
		$_SESSION['mainlang'] =  'Japanese'; break;		
	case 'ar':
		$_SESSION['mainlang'] =  'Arabic'; break;		
	case 'pt':
		$_SESSION['mainlang'] =  'Portuguese'; break;		
	case 'it':
		$_SESSION['mainlang'] =  'Italian'; break;		
	case 'ru':
		$_SESSION['mainlang'] =  'Russian'; break;		
	case 'ko':
		$_SESSION['mainlang'] =  'Korean'; break;		
	case 'el':
		$_SESSION['mainlang'] =  'Greek'; break;		
	case 'de':
		$_SESSION['mainlang'] =  'German'; break;		
	case 'hi':
		$_SESSION['mainlang'] =  'Hindi'; break;		
	case 'no':
		$_SESSION['mainlang'] =  'Norwegian'; break;		
	case 'fi':
		$_SESSION['mainlang'] =  'Finnish'; break;		
	case 'sv':
		$_SESSION['mainlang'] =  'Swedish'; break;		
	case 'zh-CN':
		$_SESSION['mainlang'] =  'Chinese'; break;		
	default:
		$_SESSION['mainlang'] =  'English'; break;
}	
$_SERVER['QUERY_STRING']	=	preg_replace('/&?lang=[^&]*/', '', $_SERVER['QUERY_STRING']);
include('includes/translate_words.php');
?>

<?php  
//juni -> 2014-10-28 -> commented out as i have replaced code
// include('language_info2.php');
$httpp = 'http://';
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on'){
	$httpp = 'https://';
}
?>

<?php

$API = API;
if(strpos($API, 'www.') == FALSE){
	if(strpos($API, '://')){
		$APIs = explode('://', $API);
		$API = $APIs[0] .'://www.'. $APIs[1];
	}
	//$API = 'www.'. $API;
}


if(strtolower($_SESSION['lang'])!='en'){
?>

<script>

function googleTranslateElementInit() {
	
  new google.translate.TranslateElement({
    pageLanguage: 'en',
    includedLanguages: '<?php echo $_SESSION['lang']?>'
  }, 'google_translate_element');
 
}
</script>
<script src="<?php echo $httpp; ?>translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<?php } ?>
<style>
body {
top:0px!important;
}
.goog-te-banner-frame{  margin-top: -50px!important; }
.maincontentinner {
    padding: 15px 20px 20px;
}
</style>
<li class="right">
			 <a href="" data-toggle="dropdown" class="dropdown-toggle capital_word notranslate"><?=$_SESSION['mainlang']?></a>
			 
			  <ul class="dropdown-menu pull-right notranslate">
                  <li  <?php if( $_SESSION['lang']=='en'){ echo "class='active'";} ?>>
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=en#googtrans(en|en)" >English</a>				  
				  </li>
                  <li <?php if( $_SESSION['lang']=='ar'){ echo "class='active'"; } ?> >
				     <a  href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=ar#googtrans(en|ar)" >Arabic</a>
				  
				  </li>
                  <li <?php if( $_SESSION['lang']=='zh-CN'){ echo "class='active'"; } ?>>
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=zh-CN#googtrans(en|zh-CN)">Chinese</a>
				  
				  </li>
                  <li <?php if( $_SESSION['lang']=='nl'){ echo "class='active'"; } ?>  ><a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=nl#googtrans(en|nl)">Dutch</a></li>
                  <li <?php if( $_SESSION['lang']=='fi'){ echo "class='active'"; } ?> >
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=fi#googtrans(en|fi)">Finnish</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='fr'){ echo "class='active'"; } ?> >
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=fr#googtrans(en|fr)">French</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='de'){ echo "class='active'";} ?>>
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=de#googtrans(en|de)">German</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='el'){ echo "class='active'";} ?>  >
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=el#googtrans(en|el)">Greek</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='hi'){ echo "class='active'";} ?> >
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=hi#googtrans(en|hi)">Hindi</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='it'){ echo "class='active'";} ?> >
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=it#googtrans(en|it)">Italian</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='ja'){ echo "class='active'";} ?> >
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=ja#googtrans(en|ja)">Japanese</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='ko'){ echo "class='active'";} ?> >
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=ko#googtrans(en|ko)">Korean</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='no'){ echo "class='active'";  } ?> >
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=no#googtrans(en|no)">Norwegian</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='pt'){ echo "class='active'";} ?>>
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&<?php echo $_SERVER['QUERY_STRING']; ?>&lang=pt#googtrans(en|pt)">Portuguese</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='ru'){ echo "class='active'"; } ?> >
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=ru#googtrans(en|ru)">Russian</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='es'){ echo "class='active'"; }?>>
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=es#googtrans(en|es)">Spanish</a>
				  </li>
                  <li  <?php if( $_SESSION['lang']=='sv'){ echo "class='active'";} ?> >
				  <a href="<?php echo $_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=sv#googtrans(en|sv)">Swedish</a>
                                      
</li>
               </ul>
			   </li>
               <script>
var abc =window.location.hash;

if(abc == '')
{ 
//juni -> had url with two //
var https  = '<?php echo $_SERVER['HTTPS']; ?>';

//window.location.href = '<?php echo API.$_SERVER['PHP_SELF'];?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=<?php echo $_SESSION['lang'];?>#googtrans(en|<?php echo $_SESSION['lang'];?>)';
if(https=='on'){

var url = '<?php echo str_replace('http:/','https://',str_replace('//','/',$API.$_SERVER['PHP_SELF']))?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=<?php echo $_SESSION['lang'];?>#googtrans(en|<?php echo $_SESSION['lang'];?>)';
}else{
var url = '<?php echo str_replace('http:/','http://',str_replace('//','/',$API.$_SERVER['PHP_SELF']))?>?<?php echo $_SERVER['QUERY_STRING']; ?>&lang=<?php echo $_SESSION['lang'];?>#googtrans(en|<?php echo $_SESSION['lang'];?>)';
}
	console.log('url');		
	console.log(url);
	
	console.log('<?= API ?>');
<?php
//echo 'ok';
//exit();
?>

//window.location.href  = url;
}
</script>