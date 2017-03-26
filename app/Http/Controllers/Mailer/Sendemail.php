<?php
require 'phpmailer/PHPMailerAutoload.php';

function Sendemail($fromname, $to, $namereciever, $subject, $body)
{

	$mail = new PHPMailer(true);
	//Set PHPMailer to use SMTP.
		//Enable SMTP debugging. 
	$mail->SMTPDebug = 2;  // SET to 3 to see errors
	$mail->isSMTP(); 
                              
	           
	//Set SMTP host name  
	$mail->Mailer = "smtp";                        
	$mail->Host = "smtp.gmail.com";
	//Set this to true if SMTP host requires authentication to send email
	$mail->SMTPAuth = true;                          
	//Provide username and password - DO NOT CHANGE IT -    
	$mail->Username = "chenda.loeurt@gmail.com";                 
	$mail->Password = "chendapnc";                           
	//If SMTP requires TLS encryption then set it
	$mail->SMTPSecure = "tls";                           
	//Set TCP port to connect to 
	$mail->Port = 587;                                   

	$mail->From = "vcgroup3laravel@gmail.com";


	// HERE YOU CAN CUSTOMIZE DEPENDING YOUR GROUP/PROJECT
	// MAIL THAT YOU WANT TO SEND
	$mail->FromName = $fromname;
	$mail->addAddress($to, $namereciever); // Define address of destination
	$mail->isHTML(false);

	// DEFINE THE MAIl CONTENT
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = "This is the plain text version of the email content";

	if(!$mail->send()) // calling send(), send the email. Return true if success return false otherwize
	{
	    echo "Mailer Error: " . $mail->ErrorInfo;
	} 
	else 
	{
		// \Session::flash('message_required_activate','Result has been Sent!');
	 //    \Session::flash('message_success_send','Result has been Sent!');
		echo "Email has been Sent Success!";
	}
}
?>