<?php 
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

function sendmailer($message,$emails,$subject){

			
			if($subject=="Password Reset"){
				$messages="https://megaxsolutions.com/jcodes/forgotpassword/?resetpassword=true&resetcode=".$message;
			}else{
				$messages=$message;
			}

			$developmentMode = false;
			
			$mail = new PHPMailer(true);
			try {
			 
			$mail->isSMTP();                            // Set mailer to use SMTP
			$mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'megaxsolutions@gmail.com'; // Your Gmail email address
            $mail->Password   = 'oiwibzbgbokxmwwg'; // Your Gmail password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption
            $mail->Port       = 587; // TCP port to connect to                  // TCP port to connect to
					
			$mail->setFrom('megaxsolutions@gmail.com','megaxsolutions');
			foreach ($emails as $recipient) {
				$mail->addAddress($recipient);
			}
			
			$mail->isHTML(true);  // Set email format to HTML
			
			
			$bodyContent ="<!DOCTYPE html>";
			$bodyContent .="<html>";
			$bodyContent .="<head>";
			$bodyContent .="<meta charset='utf-8'>";
			$bodyContent .="</head>";
			$bodyContent .="<body>";
			$bodyContent .="<p>".$messages."</p>";
			$mail->Subject = $subject;
			$bodyContent .="</body>";
			$bodyContent .="</html>";
			$mail->Body    = $bodyContent;

				if(!$mail->send()) 
					{
						return "Error Sending Email!";

					} 
					else 
					{
						return true;
						
					}
					
				} catch (Exception $e) {
					return $e;
		
				}
}
?>