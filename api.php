<?php

  $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

function generateRandomString($length = 50) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-") . str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
}

$notepath = '.notes';
$protocol = "http";
if(!empty($_SERVER['HTTPS'])){
  $protocol .= "s";
}
$script_path = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$payload = json_decode(file_get_contents('php://input'));
$result = array();
if($payload->action){
	$file = "$notepath/" . $payload->id . ".txt";
	$mail = "$notepath/" . $payload->id . ".mail";
	$text = "";
	$deleted = false;
	if($payload->action == 'get'){
		if(!$payload->id){
			$result["status"] = "Error: no id for action 'get' given";
		}else if(!$payload->password){
			$result["status"] = "Error: no password for action 'get' given";
		}else if(file_exists($file)){
				$text = file_get_contents($file);
				$deleted = unlink($file);
				$text = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256,  $payload->password, $text, MCRYPT_MODE_ECB, $iv));
			if($deleted){
				$result["status"] = "success";
				$result["message"] = $text;
				$result["id"] = $payload->id;
				if(file_exists($mail)){
				$mailcontent = file_get_contents($mail);
					$to = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,  $payload->password, $mailcontent, MCRYPT_MODE_ECB, $iv);
					mail(
						$to, //TO
						"Nachricht $id wurde gelesen", // SUBJECT 
						"Ihre Nachricht mit der ID $id wurde am " . date("d.m.Y") . " um " . date("H:i:s") . " Uhr gelesen und gelöscht." //MESSAGE
					);
					$maildeleted = unlink($mail);
					$result["mailstatus"] = "sent to $to";
				}
			}
		}else{
			$result["status"] = "Error: no message with id " . $payload->id . " found";
		}
	}else if($payload->action == 'create'){
		if(!$payload->message){
			$result["status"] = "Error: no message for action 'create' given";
		}else{
			$id = generateRandomString();
			$password = generateRandomString(32);
			$message = mcrypt_encrypt(MCRYPT_RIJNDAEL_256,  $password, trim($payload->message), MCRYPT_MODE_ECB, $iv);
			$written = file_put_contents("$notepath/$id.txt", $message);
			
			$result["link"] = str_replace("api.php", "index.php", $script_path) . '?id=' . $id . '.txt&password=' . $password;
			$result["id"] = $id;
			$result["password"] = $password;
			$result["status"] = "success";
			
			if($payload->mail){
				$to = mcrypt_encrypt(MCRYPT_RIJNDAEL_256,  $password, $payload->mail, MCRYPT_MODE_ECB, $iv);
				$mailwritten = file_put_contents("$notepath/$id.mail", $to);
				mail(
					$payload->mail, //TO
					"Nachricht $id wurde erstellt", // SUBJECT 
					"Ihre Nachricht mit der ID $id wurde am " . date("d.m.Y") . " um " . date("H:i:s") . " Uhr erstellt. \n" .  //MESSAGE
					"Sie erhalten eine weitere Mail wenn Sie vom Emfpänger gelesen wurde."
				);
				$result["mailstatus"] = "sent to " . $payload->mail;
				$result["mail"] = $payload->mail;
			}
		}
	}else {
		$result["status"] = "Error: Unknown action";
	}
}else{
	$result["status"] = "Error: no payload";
}

$result["date"] = date("Ymd H:i:s");
echo json_encode($result);

?>
