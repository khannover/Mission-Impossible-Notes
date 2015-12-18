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
  <script type="text/javascript" src="clipboard.min.js"></script>
</head>
<body class="amber lighten-5">
<div class="section no-pad-bot" id="index-banner">
    <div class="container">
    <a href="https://github.com/khannover/Mission-Impossible-Notes"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/652c5b9acfaddf3a9c326fa6bde407b87f7be0f4/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f6f72616e67655f6666373630302e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_orange_ff7600.png"></a>
      <br><br>
      <h1 class="header center orange-text">(M)ission (I)mpossible (N)otes</h1>
      <div class="row center">
        <h5 class="header col s12 light">A self-destructing notes app</h5>
<?php

  $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

function generateRandomString($length = 50) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-") . str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
}

$notepath = '.notes';
$id = $_GET['id'];
$password = $_GET['password'];
$protocol = "http";
if(!empty($_SERVER['HTTPS'])){
  $protocol .= "s";
}
$script_path = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$message=$_POST['message'];

if(!empty($id)){
        $file = "$notepath/$id";
        $text = "";
        $deleted = false;
        if(file_exists($file)){
                $text = file_get_contents($file);
                $deleted = unlink($file);
                $text = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,  $password, $text, MCRYPT_MODE_ECB, $iv);
?>

<div class="row center">
  <div class="card blue-grey darken-1">
    <div class="card-content white-text">
      <span class="card-title">Hinterlegte Nachricht</span>
      <p> <?php echo htmlentities($text); ?>
      </p>
    </div>
  </div>
</div>

<?php
                if($deleted){
                        echo "<br><br><i>Nachricht gelesen und gelöscht.</i>";
                }
        }else{
                echo "Keine Nachricht gefunden.";
        }
}else if(!empty($message)){
        $id = generateRandomString();
        $password = generateRandomString(32);
        $message = mcrypt_encrypt(MCRYPT_RIJNDAEL_256,  $password, $message, MCRYPT_MODE_ECB, $iv);
        $written = file_put_contents("$notepath/$id.txt", $message);
        echo 'Diesen Link können Sie an den Empfänger übermitteln.<br>';
        echo '<a href="' . $script_path. '?id=' . $id . '.txt&password=' . $password . '">Link</a>';
        echo '<br><br><input type="button" id="copy-btn" class="waves-effect waves-light orange btn" data-clipboard-text="' .
        $script_path. '?id=' . $id . '.txt&password=' . $password . '" value="Kopieren">';
}
?>

<form class="col s12" method="POST" action="index.php">
  <div class="row">
    <div class="input-field center">
      <textarea name="message" id="message" class="materialize-textarea" placeholder="Ihre geheime Nachricht"></textarea>
    </div>
  </div>
  <div class="row">
    <input type="submit" class="waves-effect waves-light orange btn" value="Link erstellen">
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

            <p class="light">Der Code ist öffentlich verfügbar unter <a href="https://github.com/khannover/Mission-Impossible-Notes.git">Github</a></p>
          </div>
        </div>
      </div>

    </div>

<script type="text/javascript">
new Clipboard('#copy-btn');
</script>
</body>
</html>
