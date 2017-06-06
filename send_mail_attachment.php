<?php
function send_mail_attachment($from , $to, $reply_to, $subject, $message, $attachment){
	$fileatt = $attachment;                 
	$fileatt_type = "application/octet-stream"; 
    $start=	strrpos($attachment, '/') == -1 ? strrpos($attachment, '//') : strrpos($attachment, '/')+1;
	$fileatt_name = substr($attachment, $start, strlen($attachment)); 

	 $email_from = $from; 
	 $email_subject =  $subject;
	$email_txt = $message; 
	
	$email_to = $to; 

	$headers .="From: " . $email_from . "\r\n";
	$headers .="Reply-To: ". $reply_to;
		
	$file = fopen($fileatt,'rb'); 
	$data = fread($file,filesize($fileatt)); 
	fclose($file); 
	$msg_txt="";

	$semi_rand = md5(time()); 
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
    
	$headers .= "\nMIME-Version: 1.0\n" . 
            "Content-Type: multipart/mixed;\n" . 
            " boundary=\"{$mime_boundary}\"" . "\r\n"; 

	$email_txt .= $msg_txt;
	
	$email_message .= "This is a multi-part message in MIME format.\n\n" . 
                "--{$mime_boundary}\n" . 
                "Content-Type:text/html; charset=\"iso-8859-1\"\n" . 
               "Content-Transfer-Encoding: 8bit\n\n" . 
	$email_txt . "\n\n"; 

	$data = chunk_split(base64_encode($data)); 

	$email_message .= "--{$mime_boundary}\n" . 
                  "Content-Type: {$fileatt_type};\n" . 
                  " name=\"{$fileatt_name}\"\n" . 
         
                  "Content-Transfer-Encoding: base64\n\n" . 
                 $data . "\n\n" . 
                  "--{$mime_boundary}--\n"; 

	$ok = mail($email_to, $email_subject, $email_message, $headers); 

	if($ok) {
		echo $email_to; 
		echo "<meta http-equiv='refresh' content='0;url=confirm.html'>"; 
	} else { 
		die("Sorry but the email could not be sent. Please go back and try again!"); 
	} 
}

?>
