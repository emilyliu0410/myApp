<?php
class Mail{		
	const EMAIL_HOST = CFG_AWS_EMAIL_HOST;
	const EMAIL_PORT = CFG_AWS_EMAIL_PORT;
	const EMAIL_USERNAME = CFG_AWS_EMAIL_USERNAME;
	const EMAIL_PASSWORD = CFG_AWS_EMAIL_PASSWORD;
	const EMAIL_SMTPSECURE = CFG_AWS_EMAIL_SMTPSECURE;
	const EMAIL_SMTPAUTH = CFG_AWS_EMAIL_SMTPAUTH;
	const EMAIL_DEBUG = 0;
	
	const FROM_EMAIL = CFG_AWS_EMAIL_FROM_ADDRESS;
	const FROM_NAME = CFG_AWS_EMAIL_FROM_NAME;
	
	function getSendList($sendTo,		//收件人電郵串
						 $sendToName	//收件人名稱串
						){
		$sendToList = array();
		$cur = 0;
		if($sendTo[strlen($sendTo)-1]!=';'){
			$sendTo = $sendTo.';';
		}
		if($sendToName[strlen($sendToName)-1]!=';'){
			$sendToName = $sendToName.';';
		}
		
		while(strpos($sendTo,";")){
			$sendToList[$cur][0] = substr($sendTo,0,strpos($sendTo,";"));
			$sendTo = substr($sendTo,strpos($sendTo,";")+1);
			$sendToList[$cur][1] = substr($sendToName,0,strpos($sendToName,";"));
			$sendToName = substr($sendToName,strpos($sendToName,";")+1);
			$cur++;
		}
		
		return $sendToList;
	}
	
	function sendMail(	$sendFrom		=self::FROM_EMAIL,		//寄件人電郵
						$sendFromName	=self::FROM_NAME,		//寄件人名稱
						$sendTo,								//收件人電郵
						$sendToName,							//收件人名稱
						$subject, 								//郵件標題
						$content, 								//郵件內容
						$attachments,							//郵件附件
						$isHtml=true,							//是否HTML格式
						$sendCC,								//副本抄送電郵
						$sendCCName								//副本抄送人名稱
						){
		require_once("phpMailer/class.phpmailer.php");
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug = self::EMAIL_DEBUG; 
		$mail->SMTPAuth = self::EMAIL_SMTPAUTH;
		$mail->Host = self::EMAIL_HOST;		
		$mail->Port = self::EMAIL_PORT;
		$mail->Username = self::EMAIL_USERNAME;
		$mail->Password = self::EMAIL_PASSWORD;
		$mail->SMTPSecure = self::EMAIL_SMTPSECURE;
		$mail->From = $sendFrom;
		$mail->FromName = $sendFromName;
		$toList = self::getSendList($sendTo,$sendToName);
		foreach($toList as $key => $value){
			if($value[1]){
				$mail->AddAddress($value[0],$value[1]);
			}else{
				$mail->AddAddress($value[0]);
			}
		}
		if($sendCC){
			// $mail->AddCC($sendCC,$sendCCName);
			$ccList = self::getSendList($sendCC,$sendCCName);
			foreach($ccList as $key => $value){
				if($value[1]){
					$mail->AddCC($value[0],$value[1]);
				}else{
					$mail->AddCC($value[0]);
				}
			}
		}

		if($attachments){
			if(is_array($attachments)){
				foreach($attachments as $v){
					$mail->AddAttachment($v);
				}
			}else{
				$mail->AddAttachment($attachments);
			}
		}
		$mail->CharSet="utf-8";
		$mail->Encoding = "base64";
		$mail->IsHTML($isHtml);
		$mail->Subject=$subject;
		$mail->Body=$content;		
		
		//debug($mail);
		$sent = $mail->Send();
		return $sent;
	}
}

?>