<?php
//Refernce 

//Using phpMailer to Send Mail through PHP 
//http://www.inmotionhosting.com/support/email/send-email-from-a-page/ using-phpmailer-to-send-mail-through-php

//Bluehost Web Hosting Help
//How to access your email with Gmail
//https://my.bluehost.com/cgi/help/gmail


require './PHPMailer/PHPMailerAutoload.php';

class sendMailer{

	public function sendFormToMail($formValue){
		$mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'localhost';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'mail@jmispace.com';                 // SMTP username
		$mail->Password = 'Jmi1129@space';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 25;                                    // TCP port to connect to
		$mail->CharSet = "utf-8"; //郵件編碼
		$mail->setFrom('mail@jmispace.com', 'Snewup-server');
		$mail->addAddress('lililala0112@gmail.com', 'JamieHuang');     // Add a recipient
		$mail->addReplyTo('mail@jmispace.com', 'SNEWUP-REPLY');
		$mail->addCC('mail@jmispace.com');                           //cc mailer additional
		// $mail->addBCC('bcc@example.com');

		$mail->addAttachment('images/logo.png');         // Add attachments
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = '建台興網頁-詢問單';

		$htmlContent = '';
		$htmlContent .=  '<html>';
		$htmlContent .=  '<head>';
		$htmlContent .=  '<title>建台興網頁-線上詢問單</title>';
		$htmlContent .=  '</head>';
		$htmlContent .=  '<body>';
		$htmlContent .=  '<h2>建台興網頁-線上詢問單</h2>';
		$htmlContent .=  '<table cellspacing="0" style="border: 1px solid #DDD; width: 100%;">';

		$keyNames = array(
		 'name' => '聯絡人',
		 'company' => '公司名稱',
		 'phone' => '聯絡手機',
		 'mail' => '聯絡信箱',
		 'comment' => '需求說明',
		);
		foreach ($keyNames as $key => $value) {
			$htmlContent .=  '<tr><th style="padding:10px; border-bottom:solid 1px #DDD;text-align:left">'.$value.'</th><td style="border-bottom:solid 1px #DDD"">'.$formValue[$key].'</td></tr>';
		}
		$htmlContent .=  '</table>';
		$htmlContent .=  '</body>';
		$htmlContent .=  '</html>';
		$mail->Body    = $htmlContent;
		$mail->AltBody = '無任何回傳資料 請聯絡開發者解決此問題';


		return $mail->send(); 

	}

	
} 
?>
