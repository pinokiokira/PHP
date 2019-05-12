<?php 
	session_start(); 

	// change the following paths if necessary 
	$config = dirname(__FILE__) . '/config.php';
	require_once( "Hybrid/Auth.php" );


	// if user select a provider to login with
	// then inlcude hybridauth config and main class
	// then try to authenticate te current user
	// finally redirect him to his profile page
	if( isset( $_GET["provider"]) && $_GET["provider"]):
		//echo 'test';die;
		try {

			// create an instance for Hybridauth with the configuration file path as parameter
			$hybridauth = new Hybrid_Auth( $config );

			// set selected provider name
			$provider = @ trim( strip_tags( $_GET["provider"]  ));

			// try to authenticate the selected $provider
			$adapter = $hybridauth->authenticate( $provider,$param);
			// if okey, we will redirect to user profile page 
			$hybridauth->redirect( "profile.php?provider=$provider" );
		}
		catch( Exception $e ) {
			// well, basically your should not display this to the end user, just give him a hint and move on..
			
			?>
			
			<script type="text/javascript">  window.close();</script>
			<?
			die;
		}
    endif;
	
?>
