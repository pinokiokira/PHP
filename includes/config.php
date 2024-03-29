<?php
	#AUTOGENERATED BY HYBRIDAUTH 2.1.1-dev INSTALLER - Tuesday 27th of August 2013 03:01:16 AM

/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

include_once($_SERVER['DOCUMENT_ROOT'] .'\panels\internalaccess\url.php');

return
	array(
		"base_url" => sprintf(
		    "%s://%s%s/index.php",
		    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
		    $_SERVER['SERVER_NAME'],
		    dirname($_SERVER['REQUEST_URI'])
		  ),

		"providers" => array (
			// openid providers
			"OpenID" => array (
				"enabled" => false
			),

			"AOL"  => array (
				"enabled" => false
			),

			"Yahoo" => array (
				"enabled" => false,
				"keys"    => array ( "id" => "", "secret" => "" )
			),

			"Google" => array (
				"enabled" => true,
                "redirect_uri" => API ."Panels/TeamPanel/includes/?hauth.done=Google",
				//"keys"    => array ( "id" => "1084313905243.apps.googleusercontent.com", "secret" => "xfFcftBsyP4KMeV_GdmFC2vO" )
                //"keys"    => array ( "id" => "1084313905243-lbll2cmpkldfncabl6m1dbgkuiabpsiv.apps.googleusercontent.com", "secret" => "bHHeaZngu6s2W1l5_AiaytGG" )
				"keys" => array ( "id" => "862531238737-0cbtktg2ahmto80iqieq779g0g6ll52q.apps.googleusercontent.com", "secret" => "sZLToFvVvB4VSJymeGJNpPLK" ) // added 11-28-2016.
			),

			"Facebook" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "1430040057216915", "secret" => "4945ddbfaa09b20b973e455be8c0a602" )
			),

			"Twitter" => array (
				"enabled" => true,
				"keys"    => array ( "key" => "sw4COOtE2gEPgDAUUSz0uDqtF", "secret" => "VNjLFnlrEujrBoXhZUseTtGc61futEMWjjwt2CuLrOLJtK3GrR" )
			),

			// windows live
			"Live" => array (
				"enabled" => false,
				"keys"    => array ( "id" => "", "secret" => "" )
			),

			"MySpace" => array (
				"enabled" => false,
				"keys"    => array ( "key" => "", "secret" => "" )
			),

			"LinkedIn" => array (
				"enabled" => true,
				"keys"    => array ( "key" => "759v8yvhqc6nfx", "secret" => "hsJ9HqWRxAzpbeCJ" )
			),

			"Foursquare" => array (
				"enabled" => false,
				"keys"    => array ( "id" => "", "secret" => "" )
			),
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => false,

		"debug_file" => ""
	);
