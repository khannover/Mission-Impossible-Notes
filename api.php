<?php

$iv_size = openssl_cipher_iv_length($cipher);
$iv = hex2bin($_GET['iv']) ?? random_bytes($iv_size);
function generateRandomString($length = 50) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-") . str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
}

$notepath = '.notes';
$protocol = "https";
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
				$text = trim(openssl_decrypt ($text, $cipher, $payload->password, $options, $iv));
			if($deleted){
				$result["status"] = "success";
				$result["message"] = $text;
				$result["id"] = $payload->id;
				if(file_exists($mail)){
					$mailcontent = file_get_contents($mail);
					$to = openssl_decrypt ($mailcontent, $cipher, $payload->password, $options, $iv);
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
			$message = openssl_encrypt($payload->message, $cipher, $password, $options, $iv);
			$written = file_put_contents("$notepath/$id.txt", $message);
			
			$result["link"] = str_replace("api.php", "index.php", $script_path) . '?id=' . $id . '.txt&password=' . $password;
			$result["id"] = $id;
			$result["password"] = $password;
			$result["status"] = "success";
			
			if($payload->mail){
				$to = openssl_encrypt($payload->mail, $cipher, $password, $options, $iv);
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
