<!DOCTYPE html>
<html>
<head>

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <!-- Compiled and minified CSS -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="materialize/css/materialize.min.css">
  <!-- Compiled and minified JavaScript -->
  <script type="text/javascript" src="materialize/js/jquery.min.js"></script>
  <script type="text/javascript" src="materialize/js/materialize.min.js"></script>
</head>
<body>
<div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <br><br>
      <h1 class="header center orange-text">(M)ission (I)mpossible (N)otes</h1>
      <div class="row center">
        <h5 class="header col s12 light">A self-destructing notes app</h5>
<?php

  $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB); 
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 

function generateRandomString($length = 50) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-()"), 0, $length);
}

$notepath = '.notes';
$id = $_GET['id'];
$password = $_GET['password'];

$message=$_POST['message'];

if(!empty($id)){
	$file = "$notepath/$id";
	$text = "";
	$deleted = false;
	if(file_exists($file)){
		$text = file_get_contents($file);
		$deleted = unlink($file);
		$text = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,  $password, $text, MCRYPT_MODE_ECB, $iv);
		echo $text;
		if($deleted){
			echo "<br><br><i>Message was deleted.</i>";
		}
	}else{
		echo "no message found";
	}
}else if(!empty($message)){
	$id = generateRandomString();
	$password = generateRandomString(16);
  	$message = mcrypt_encrypt(MCRYPT_RIJNDAEL_256,  $password, $message, MCRYPT_MODE_ECB, $iv); 
	$written = file_put_contents("$notepath/$id.txt", $message); 
	echo 'Diesen Link können Sie an den Empfänger übermitteln.<br>';
	echo '<a href="index.php?id=' . $id . '.txt&password=' . $password . '">Link</a>';
}
?>

<form class="col s12" method="POST" action="index.php">
  <div class="row">
    <div class="input-field center">
      <textarea name="message" id="message" class="materialize-textarea" placeholder="Ihre geheime Nachricht"></textarea>
    </div>
  </div>
  <div class="row">
    <input type="submit" class="waves-effect waves-light btn">
  </div>
</form>

      </div>
</div>

<div class="container">
    <div class="section">
      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">verified_user</i></h2>
            <h5 class="center">Sicher</h5>

            <p class="light">MIN speichert Ihre Nachrichten verschlüsselt auf dem Server zwischen und löscht beim ersten Lesen die Nachricht automatisch. Das Passwort zum Entschlüsseln wird jedesmal zufällig generiert und im erzeugten Link hinterlegt. Es wird nirgendwo bis zum Abruf der Nachricht gespeichert. Beim Abruf der Nachricht erscheint das Passwort zwar als Parameter im Webserver-Log, die Nachricht wird jedoch im gleichen Augenblick zerstört und damit wird das Passwort unbrauchbar.</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">group</i></h2>
            <h5 class="center">Zusammenarbeit</h5>

            <p class="light">Teilen Sie mit MIN geheime Informationen wie Passwörter, Kontodaten, etc. auf sichere Art und Weise.</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">description</i></h2>
            <h5 class="center">Quelloffen</h5>

            <p class="light">Der Code ist frei verf�gbar unter <a href="https://github.com/khannover/Mission-Impossible-Notes.git">GitHub</a></p>
          </div>
        </div>
      </div>

    </div>

      <!--Import jQuery before materialize.js-->
</body>
</html>
