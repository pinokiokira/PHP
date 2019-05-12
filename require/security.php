<?php
session_name("VENDOR");
session_start();
if(!isset($_SESSION['client_id']) || intval($_SESSION['client_id']) == ''){ ?>
    <script>
		window.location.href='index.php';
	</script>
<?php	
	//header('location:index.php');
 } ?>