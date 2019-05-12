<?php //error_reporting("E^NOTICE");
	
	include_once("internalaccess/connectdb.php");
	include_once("internalaccess/url.php");
	
	//$intLesson_id=$_SESSION['lesson_id_d'];
	$intLesson_id=0;
	if($_REQUEST['lesson_id']!=""){
		$intLesson_id=$_REQUEST['lesson_id'];
	}
	
	$client_id=0;
	if($_REQUEST['client_id']!=""){
		$client_id=$_REQUEST['client_id'];
	}
	
	echo $intLesson_id." ".$client_id;
	$strFlag="";
	if(isset($_REQUEST['flag'])){
		
	
	if($_REQUEST['flag']!=""){
		$strFlag=$_REQUEST['flag'];
	}
	}
$squery="SELECT training_lessons.author,training_lessons.author_type,training_lessons.lesson_id, training_lessons.name, training_lessons.price, training_lessons.lesson_image , training_lessons.price, training_lessons.version, training_lessons.module,
			training_lessons.keywords, training_lessons.valid_period, training_lessons.lesson_descr,training_lessons.created_on,
			training_video_groups.groupname, training_video_types.types, training_products.product,
			locations.name as location_name,
			Employees.first_name, Employees.last_name,
			(SELECT count(id) FROM training_lesson_videos WHERE training_lesson_videos.lesson_id=training_lessons.lesson_id) as TotalVideo
			FROM training_lessons
			JOIN training_video_groups ON training_video_groups.id = training_lessons.group
			JOIN training_video_types ON training_video_types.id = training_lessons.type
			JOIN training_products ON training_products.product_id = training_lessons.product
			LEFT JOIN locations ON (locations.id=training_lessons.author AND training_lessons.author_type='Location')
			LEFT JOIN Employees ON (Employees.id=training_lessons.author AND training_lessons.author_type='Employee')
			WHERE
			training_lessons.status='active' AND training_lessons.public='yes'
			AND training_lessons.lesson_id=".$intLesson_id;

//	exit;
	$sresult=mysql_query($squery);
	$srow=mysql_fetch_object($sresult);
	//var_dump($srow);exit;
?>
<!doctype html>
<!--[if IE 7 ]>    <html lang="en-gb" class="isie ie7 oldie no-js"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en-gb" class="isie ie8 oldie no-js"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en-gb" class="isie ie9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en-gb" class="no-js"> <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!--[if lt IE 9]> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>LearnTUBE | Public Lessons</title>
    <META NAME="description" CONTENT="LearnTUBE is software training tool specific to food and beverage, hospitality, retail and more industry specific technology. Our training tutorial video library with online courses help managers ensure that their employees are retaining the knowledge of their software that is used throughout the work place properly." />
<META NAME="keywords" CONTENT="online training, software training, online video training , online learning, online video tutorials, video tutorials, Microsoft software training, video training, training videos,  software training videos, SoftPoint, SoftApps, Soft Apps, SoftApps by SoftPoint" />
<META NAME="author" content="learntube" />
    <META NAME="rating" CONTENT="General" />
    <META NAME="revisit-after" CONTENT="7 Day" />
    <META NAME="Robots" content="INDEX,FOLLOW" />
    <META NAME="distribution" CONTENT="Global" />
    <META NAME="copyright" CONTENT="SoftPoint LLC All rights reserved 2012">
    <META NAME="Reply-to" CONTENT="info@softPoint.us (Sofia Ferrai)">
    <META NAME="generator" Content="SoftPoint">
    <META charset="utf-8">
    
    <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
	<!--[if lte IE 8]>
		<script type="text/javascript" src="http://explorercanvas.googlecode.com/svn/trunk/excanvas.js"></script>
	<![endif]-->
    
    <!-- **Favicon** -->
	<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
    
    <!-- **CSS - stylesheets** -->
    <link id="default-css" href="style.css" rel="stylesheet" media="all" />
    <link id="shortcodes-css" href="css/shortcode.css" rel="stylesheet" media="all" />
	<link href="css/meanmenu.css" rel="stylesheet" type="text/css" media="all" />
    <link id="skin-css" href="skins/blue/style.css" rel="stylesheet" media="all" />
    
    <link href="css/responsive.css" rel="stylesheet" type="text/css" />
    
    <!-- **Animation stylesheets** -->
    <link href="css/animations.css" rel="stylesheet" type="text/css" />
	<link href="css/isotope.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/prettyPhoto.css" rel="stylesheet" type="text/css" media="all" />
    
    <!-- **Font Awesome** -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    
    <!--[if IE 7]>
    <link rel="stylesheet" href="css/font-awesome-ie7.min.css" />
    <![endif]-->
    
    <!-- **Google - Fonts** -->
    <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300italic,400italic,600' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    
    <!-- SLIDER STYLES STARTS -->
	<link rel="stylesheet" type="text/css" href="js/revolution/settings.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="js/layerslider/layerslider.css" media="screen">    
    <!-- SLIDER STYLES ENDS -->    
   <style>
   		.wrapper{
			width:99.9%;
		}
   </style>
    <!-- **jQuery** -->
    <script src="js/modernizr-2.6.2.min.js"></script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-21517618-27']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<body>
	<div class="wrapper" style="background-color:#F7F7F7;">
    	<div class="inner-wrapper">
    	<!-- Header div Starts here --><!-- Header div Ends here -->
        <div id="main">
        	
            <table width="100%" border="0" style="border:none;" cellpadding="2" cellspacing="2">
				<tr>
					<td width="40%" style="border:none; padding:13px 5px 13px 15px;">
						<?php if($srow->lesson_image!="") { ?>
							<img src="<?php echo API."images/".$srow->lesson_image; ?>" alt="portfolio" title="" width="250" height="175">
						<?php } else { ?>	
							<img src="images/No-Image.png" alt="portfolio" title="" width="250" height="175">
						<?php } ?>
					</td>
					<td style="vertical-align:top;border:none;padding:0px;">
						<table width="100%" border="0" style="border:none;margin:0px;" cellpadding="0" cellspacing="0">
							<tr>
								<td style="text-align:right;border:none;padding:3px;font-weight:bold;font-size:1.4em;"><?php echo $srow->price;?></td>
							</tr>
							<tr><td style="text-align:left;border:none;height:25px;">&nbsp;</td>
							<tr>
								<td style="text-align:left;border:none;padding:3px;font-weight:bold;font-size:1.4em;"><?php echo ucfirst(stripslashes($srow->name));?></td>
							</tr>
							<tr>
								<td style="text-align:left;border:none;padding:3px;"><?php echo ucfirst(stripslashes($srow->groupname));?></td>
							</tr>
							<tr>
								<td style="text-align:left;border:none;padding:3px;"><?php echo ucfirst(stripslashes($srow->types));?></td>
							</tr>
						</table>
					</td>
				</tr>
				
				<tr>
				
				</tr>
			</table>
			<div style="padding:0px 17px;">
			<p>
				Thank you for using LearnTube<br>
				You are confirming the purchase of the lesson listed above for the price indicated. You will have usage to this Lesson but will not be able to modify it. Please select the term and condition and press Confirm to finalize your purchase. You will then receive an email in order to make your payment .
			</p>
				<?php if(isset($_SESSION['email']) && $_SESSION['email']!=''){?>
			<input type="checkbox" id="verify">&nbsp;Agree to Terms and Conditions
			<?php }?>
			</div>
			<div >
						<div style="display:none">
						
						<a href="saverecord.php?lesson_id=<?php echo $srow->lesson_id; ?>&purchase_author_id=<?=$srow->author?>&owner_author_id=<?=$srow->author?>&owner_author_type=<?=$srow->author_type?>&price=<?=$srow->price?>&iframe=true&width=550&height=100" id="saverec" rel="prettyPhoto[iframes]" class="zoom" data-gal="prettyPhoto[gallery]"><span class="icon-search"></span></a>
						</div>
						<div style="margin: 0 auto;">
						<?php if(isset($_SESSION['email']) && $_SESSION['email']!=''){?>
						<input type="button" value="Confirm" class="button" name="btnClose" id="btnClose" disabled>
						<?php }else{?>
						<input type="button" value="Sign In" class="button" id="bsign">
						<?php }?>
						</div>
						</div>
		</div>
        
        
      </div>
    </div><!-- Wrapper End -->
    
    <!-- Java Scripts -->
    
    <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
    
    <script type="text/javascript" src="js/jquery.scrollTo.js"></script>
    <script type="text/javascript" src="js/jquery.inview.js"></script>

    <script type="text/javascript" src="js/jquery.nav.js"></script>
    <script type="text/javascript" src="js/jquery-menu.js"></script>    
	<script type="text/javascript" src="js/jquery.meanmenu.min.js"></script> 
    
	<script type="text/javascript" src="js/jquery.quovolver.min.js"></script>
    
	<script type="text/javascript" src="js/jquery.donutchart.js"></script>        

	<script type="text/javascript" src="js/jquery.isotope.min.js"></script>
    
	<script type="text/javascript" src="js/jquery.prettyPhoto.js"></script>
    
	<script type="text/javascript" src="js/jquery.validate.min.js"></script>    
  
	<script type="text/javascript" src="js/jquery.tabs.min.js"></script>
    
	<script type="text/javascript" src="js/jquery.nicescroll.min.js"></script>
	
	<!-- Layer Slider Starts -->
	<script src="js/layerslider/jquery-easing-1.3.js" type="text/javascript"></script>
    <script src="js/layerslider/jquery-transit-modified.js" type="text/javascript"></script>
    <script src="js/layerslider/layerslider.transitions.js" type="text/javascript"></script>
    <script src="js/layerslider/layerslider.kreaturamedia.jquery.js" type="text/javascript"></script>
    <script type="text/javascript">

	</script>
	<!-- Layer Slider Ends -->
    
    <!-- Revolution Slider Starts -->
    <script src="js/revolution/jquery.themepunch.revolution.min.js" type="text/javascript"></script>
    <script type="text/javascript">
	
	</script>
    <!-- Revolution Slider Ends -->
	
    <!-- **To Top** -->
    <script src="js/jquery.ui.totop.min.js"></script>
    
    <!-- **Contact Map** -->
    <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script src="js/jquery.gmap.min.js"></script>

	<script type="text/javascript" src="js/custom.js"></script>
	<script>
	$(document).on('click','#btnClose',function(){
		//window.parent.$.prettyPhoto.close();
		//alert("asda");
		document.getElementById('saverec').click();
		//$('#linkw').trigger('click');
		
		});
	$(document).on('click','#bsign',function(){
		window.parent.$.prettyPhoto.close();
		//alert("asda");
		

		window.top.location.href ="lessons.php#signup";
		//$('#linkw').trigger('click');
		
		});
	$('#verify').change(function(){
			if($(this).is(":checked"))
				$('#btnClose').removeAttr('disabled');
			else
				$('#btnClose').attr('disabled','disabled');
				

		});
	</script>
    
</body>
</html>