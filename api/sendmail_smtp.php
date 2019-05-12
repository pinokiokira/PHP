<?php 
$pathMain = $_SERVER['DOCUMENT_ROOT'];
include_once($pathMain."/mailer/send_smtp_mail_function.php");
	 if(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){
		ob_start("ob_gzhandler"); 
	 }else{ 
		ob_start();
	 }
	function HtmlMailSend($to,$subject,$mailcontent,$from)
	{
		include_once('class.phpmailer.php');
		$mail = new PHPMailer;//($email_host,$email_port,$email_auth,$email_username,$email_pass);
		return $mail->HtmlMailSend($to,$subject,$mailcontent,$from);
	}
	function SimpleMailSend($to,$subject,$mailcontent1,$from)
	{
		include_once('class.phpmailer.php');
		$mail = new PHPMailer;
		return $mail->SimpleMailSend($to,$subject,$mailcontent1,$from);
	}
	function SendMail($to,$subject,$mailcontent,$from)
	{
		$array = split("@",$from,2);
		$SERVER_NAME = $array[1];
		$username =$array[0];
		$fromnew = "From: $username@$SERVER_NAME\nReply-To:$username@$SERVER_NAME\nX-Mailer: PHP";
		Send_Smtp_mail('','',$to, '', $subject,$mailcontent,'');
		//mail($to,$subject,$mailcontent,$fromnew);
	}
  
	function SendHTMLMail($to,$subject,$mailcontent,$from1)
	{
	
		$limite = "_parties_".md5 (uniqid (rand()));
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$headers .= "From: $from1\r\n";
		Send_Smtp_mail('','',$to, '', $subject,$mailcontent,'');
		//mail($to,$subject,$mailcontent,$headers);
	}
	
	function SendHTMLMail_Name($to,$subject,$mailcontent,$from1,$description='')
	{
		include_once('class.phpmailer.php');
		$mail = new PHPMailer;
		return $mail->SimpleMailSend($to,$subject,$mailcontent,$from1);
	}
	function SendMailYahoo($to,$subject,$mailcontent,$from1){
		include_once('class.phpmailer.php');
		$mail = new PHPmailer();		
		$mail->From = from1;
		$mail->FromName = "SoftPoint";
		$mail->Subject = $subject;
		$mail->Body = $mailcontent;
		$mail->IsHTML(true);		
        $mail->AddReplyTo(from1,"Softpoint");
		$mail->AddAddress($from1,$from1);
		$mail->Send();
	}
	
	
	
?>