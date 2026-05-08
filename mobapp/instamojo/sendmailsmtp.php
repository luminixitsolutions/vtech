<?php
if($to!="")
{
 
	$mail = new PHPMailer();
	$mail->IsSMTP();                                   // send via SMTP
	$mail->Host     = "mail.nearbystore.in"; // SMTP server
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Username = "noreply@nearbystore.in";  // SMTP username
	$mail->Password = "mKg0BS5M}wbX"; // SMTP password
	$mail->From     = "noreply@nearbystore.in";
	$mail->FromName = "Near By Store";                    
     $mail->SMTPSecure = 'tls';                    
     $mail->Port = 587;   
	$mail->IsHTML(true); 
	
	$mail->AddAddress($to);
    $mail->addBCC('rajatdh07@gmail.com');
	
	$mail->AddReplyTo("noreply@nearbystore.in");
	
	$mail->WordWrap = 100;                              // set word wrap
	//$mail->AddAttachment($attachment);      // attachment
	$mail->IsHTML(true);                               // send as HTML
	$mail->Subject  =  $subject;
	$mail->Body   =  $body;
	
	 //temp disable
	if(!$mail->Send())
	{
	echo "Message was not sent";
	echo "Mailer Error: " . $mail->ErrorInfo;
	exit;
	}
	else
	{
	//echo "Message was sent";
	}
	

} ?>