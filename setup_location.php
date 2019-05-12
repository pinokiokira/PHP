<?php 
require_once 'require/security.php';
require_once 'config/accessConfig.php'; 


$client_id = $_SESSION['client_id'];
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | VendorPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/jquery.dateLists.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />

<?php 
	if(!isset($_REQUEST["lang"]) || $_REQUEST["lang"]=="")
{
	header("Location: setup_location.php?lang=".$_SESSION['lang']."#googtrans(en|".$_SESSION['lang'].")");
	exit;
}

include('language_info2.php');
?>

<script>
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en',
    includedLanguages: '<?=$_SESSION['lang']?>'
  }, 'google_translate_element');
}
</script>
<script src=
"http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="js/jquery.smartWizard.min.js"></script>
<script type="text/javascript" src="js/jquery.dateLists.min.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.blockUI.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript">
var client_id = <?php echo $client_id;?>;
</script>
<style>
	div#LocationsLinkedWithEmployee {
    overflow: hidden;
}
.side {
    overflow: hidden;
    position: relative;
}

body {
top:0px!important;
}
.goog-te-banner-frame{  margin-top: -50px!important; }
.error {
	color: #FF0000;
	padding-left:10px;
}
</style>
</head>

<body>
<div class="mainwrapper">
  <?php require_once('require/top.php');?>
  <?php require_once('require/left_nav.php');?>
  <div class="rightpanel">
    <ul class="breadcrumbs">
      <li><a href="help.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
      <li>Setup<span class="separator"></span></li>
      <li>Locations</li>
      <li class="right"> <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
        <ul class="dropdown-menu pull-right skin-color">
          <li><a href="default">Default</a></li>
          <li><a href="navyblue">Navy Blue</a></li>
          <li><a href="palegreen">Pale Green</a></li>
          <li><a href="red">Red</a></li>
          <li><a href="green">Green</a></li>
          <li><a href="brown">Brown</a></li>
        </ul>
      </li>
	  <li class="right">
			 <a href="" data-toggle="dropdown" class="dropdown-toggle capital_word notranslate"><?=$_SESSION['mainlang']?></a>
			 
			  <ul class="dropdown-menu pull-right notranslate">
                  <li  <?php if( $_SESSION['lang']=='en'){ echo "class='active'";} ?>>
				  <a href="setup_location.php?lang=en#googtrans(en|en)" >English</a>				  
				  </li>
                  <li <?php if( $_SESSION['lang']=='ar'){ echo "class='active'"; } ?> >
				     <a  href="setup_location.php?lang=ar#googtrans(en|ar)" >Arabic</a>
				  
				  </li>
                  <li <?php if( $_SESSION['lang']=='zh-CN'){ echo "class='active'"; } ?>>
				  <a href="setup_location.php?lang=zh-CN#googtrans(en|zh-CN)">Chinese</a>
				  
				  </li>
                  <li <?php if( $_SESSION['lang']=='nl'){ echo "class='active'"; } ?>  ><a href="setup_location.php?lang=nl#googtrans(en|nl)">Dutch</a></li>
                  <li <?php if( $_SESSION['lang']=='fi'){ echo "class='active'"; } ?> >
				  <a href="setup_location.php?lang=fi#googtrans(en|fi)">Finnish</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='fr'){ echo "class='active'"; } ?> >
				  <a href="setup_location.php?lang=fr#googtrans(en|fr)">French</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='de'){ echo "class='active'";} ?>>
				  <a href="setup_location.php?lang=de#googtrans(en|de)">German</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='el'){ echo "class='active'";} ?>  >
				  <a href="setup_location.php?lang=el#googtrans(en|el)">Greek</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='hi'){ echo "class='active'";} ?> >
				  <a href="setup_location.php?lang=hi#googtrans(en|hi)">Hindi</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='it'){ echo "class='active'";} ?> >
				  <a href="setup_location.php?lang=it#googtrans(en|it)">Italian</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='ja'){ echo "class='active'";} ?> >
				  <a href="setup_location.php?lang=ja#googtrans(en|ja)">Japanese</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='ko'){ echo "class='active'";} ?> >
				  <a href="setup_location.php?lang=ko#googtrans(en|ko)">Korean</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='no'){ echo "class='active'";  } ?> >
				  <a href="setup_location.php?lang=no#googtrans(en|no)">Norwegian</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='pt'){ echo "class='active'";} ?>>
				  <a href="setup_location.php?lang=pt#googtrans(en|pt)">Portuguese</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='ru'){ echo "class='active'"; } ?> >
				  <a href="setup_location.php?lang=ru#googtrans(en|ru)">Russian</a>
				  </li>
                  <li <?php if( $_SESSION['lang']=='es'){ echo "class='active'"; }?>>
				  <a href="setup_location.php?lang=es#googtrans(en|es)">Spanish</a>
				  </li>
                  <li  <?php if( $_SESSION['lang']=='sv'){ echo "class='active'";} ?> >
				  <a href="setup_location.php?lang=sv#googtrans(en|sv)">Swedish</a>
				  </li>  
               </ul>
			</li>
    </ul>
    <div class="pageheader">
      <!--<form action="results.html" method="post" class="searchbar">
        <input type="text" name="keyword" placeholder="To search type and hit enter..." />
      </form>-->
      <div class="pageicon"><span class="iconfa-cog"></span></div>
      <div class="pagetitle">
        <h5>Location that You Are Associated With</h5>
        <h1>Locations</h1>
      </div>
    </div>
    <!--pageheader-->
    
    <div class="maincontent">
      <div class="maincontentinner">
        <div class="row-fluid"> 
          
          <!--span4-->
          <div class="span8" id="locationsDiv" style="width: 100%;">
            <div class="widgetbox login-information" >
              <h4 class="widgettitle">Locations Associated With Employee Team Master</h4>
<div class="widgetcontent">
  <div id="LocationsAssociatedWithEmployee"> </div>
</div>
<h4 class="widgettitle">Locations Linked With Employee Email</h4>
<div class="widgetcontent">
  <div id="LocationsLinkedWithEmployee"> </div>
</div>
            </div>
          </div>
          <!--span8--> 
        </div>
        <!--row-fluid-->
        
       <?php include_once 'require/footer.php';?>
        <!--footer--> 
        
      </div>
      <!--maincontentinner--> 
    </div>
    <!--maincontent--> 
    
  </div>
  <!--rightpanel--> 
  
</div>
<!--mainwrapper-->

</body>
</html>
