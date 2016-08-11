<?php 

	$msg_subject = "antiSpamChat Account Register";
		
	require "../assets/plugins/contact-form/PHPMailerAutoload.php";
	require "../assets/plugins/contact-form/smartmessage.php";

	$mail = new PHPMailer(); // create a new object
	$mail->IsSMTP(); // enable SMTP
	//$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true; // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465; // or 587
	$mail->IsHTML(true);
	$mail->Username = "rand.acc0unt321@gmail.com";
	$mail->Password = "randompassword";
	$mail->SetFrom($email);
	$mail->Subject = $msg_subject;
	$mail->Body = $message;
	$mail->AddAddress("fmihayi@yahoo.com");

	if($mail->Send()) {
		echo 1; 
	} else {
		echo 0;
	}

?>