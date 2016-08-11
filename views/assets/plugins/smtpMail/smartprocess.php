<?php 

	$msg_subject = "Mesaj nou | Orar ACE";

	$sendername = strip_tags(trim($_POST["sendername"]));
	$sendersubject = strip_tags(trim($_POST["sendersubject"]));
	$sendermessage = strip_tags(trim($_POST["sendermessage"]));

	require "PHPMailerAutoload.php";
	require "smartmessage.php";

	$mail = new PHPMailer(); // create a new object
	
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true; // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465; // or 587
	$mail->IsHTML(true);
	$mail->Username = "rand.acc0unt321@gmail.com";
	$mail->Password = "randompassword";
	$mail->AddAddress("fmihayi@yahoo.com","Orar ACE"); // aici setezi adresa pe care vrei sa primesti mail-urile
	$mail->SetFrom('admin@ace.ucv.ro',$sendername);
	$mail->Subject = $msg_subject;
	$mail->Body = $message;
	
	
	if($mail->Send()) {
		echo 1; 
	} else {
		echo 0;
	}

?>