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
    <a href="https://github.com/khannover/Mission-Impossible-Notes"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://github.blog/wp-content/uploads/2008/12/forkme_right_orange_ff7600.png?resize=149%2C149" alt="Fork me on GitHub" ></a>
      <br><br>
      <h1 class="header center orange-text">(M)ission (I)mpossible (N)otes</h1>
      <div class="row center">
        <h5 class="header col s12 light">A self-destructing notes app</h5>
<?php


function generateRandomString($length = 50) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-") . str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, $length);
}


$cipher = "BF";
//$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
$iv_size = openssl_cipher_iv_length($cipher);
//$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
$iv = $_GET['iv'] ?? random_bytes($iv_size);
$notepath = '.notes';
$id = $_GET['id'];
$password = $_GET['password'];

$protocol = "https";
$script_path = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$message=$_POST['message'];
$mail=$_POST['mail'];

if(!empty($id)){
        $file = "$notepath/$id";
        $mail = "$notepath/" . str_replace(".txt", ".mail", $id);
        $text = "";
        $deleted = false;
        if(file_exists($file)){
		$options = 0;
                $text = file_get_contents($file);
                $deleted = unlink($file);
		$text = openssl_decrypt ($text, $cipher, $password, $options, hex2bin($iv));
?>

<div class="row center">
  <div class="card blue-grey darken-1">
    <div class="card-content white-text">
      <span class="card-title">Hinterlegte Nachricht</span>
      <p> <?php echo nl2br(htmlentities($text)); ?>
      </p>
    </div>
  </div>
</div>

<?php
                                if($deleted){
                                                echo "<br><br><i>Nachricht gelesen und gelöscht.</i>";
echo '
<div class="content">
  <div class="background-highlight"></div>

  <div class="page">
    <div class="highlight"></div>
    <div class="text">
      <h1>IT WAS A PLEASURE TO BURN</h1>
      <p>
        It was a special pleasure to see things eaten, to see things blackened and changed. With the
        brass nozzle in his fists, with this great python spitting its venomous kerosene upon the world,
        the blood pounded in his head, and his hands were the hands of some amazing conductor playing
        all the symphonies of blazing and burning to bring down the tatters and charcoal ruins of history.
        With his symbolic helmet numbered 451 on his stolid head, and his eyes all orange flame with
        the thought of what came next, he flicked the igniter and the house jumped up in a gorging fire
        that burned the evening sky red and yellow and black. He strode in a swarm of fireflies. He
        wanted above all, like the old joke, to shove a marshmallow on a stick in the furnace, while the
        flapping pigeon-winged books died on the porch and lawn of the house. While the books went up
        in sparkling whirls and blew away on a wind turned dark with burning.
        Montag grinned the fierce grin of all men singed and driven back by flame.
        He knew that when he returned to the firehouse, he might wink at himself, a minstrel man, burntcorked,
        in the mirror. Later, going to sleep, he would feel the fiery smile still gripped by his face
        muscles, in the dark. It never went away, that smile, it never ever went away, as long as he
        remembered.
      </p>
    </div>
    <div class="burn">
      <div class="flame"></div>
      <div class="flame"></div>
      <div class="flame"></div>
      <div class="flame"></div>
      <div class="flame"></div>
      <div class="flame"></div>
      <div class="flame"></div>
      <div class="flame"></div>
      <div class="flame"></div>
      <div class="flame"></div>
    </div>
  </div>
</div>';



                                                if(file_exists($mail)){
							$options = 0;
                                                        $mailcontent = file_get_contents($mail);
							$to = openssl_decrypt ($mailcontent, $cipher, $password, $options, hex2bin($iv));
                                                        mail(
                                                                $to, //TO
                                                                "Nachricht $id wurde gelesen", // SUBJECT
                                                                "Ihre Nachricht mit der ID $id wurde am " . date("d.m.Y") . " um " . date("H:i:s") . " Uhr gelesen und gelöscht." //MESSAGE
                                                        );
                                                        $maildeleted = unlink($mail);
                                                }
                                }
                                }else{
                                                echo "<span style='color:red'>Keine Nachricht gefunden.</span>";
                                }
                }else if(!empty($message)){
			$options = 0;
                        $id = generateRandomString();
                        $password = generateRandomString(32);
                        //$message = mcrypt_encrypt(MCRYPT_RIJNDAEL_256,  $password, $message, MCRYPT_MODE_ECB, $iv);
			$message = openssl_encrypt($message, $cipher, $password, $options, $iv);
			$written = file_put_contents("$notepath/$id.txt", $message);

                        echo 'Diesen Link können Sie an den Empfänger übermitteln.<br>';
                        echo '<a href="' . $script_path. '?id=' . $id . '.txt&password=' . $password . '&iv='.bin2hex($iv).'">Link</a>';
                        echo '<br><br><input type="button" id="copy-btn" class="waves-effect waves-light orange btn" data-clipboard-text="' .
                        $script_path. '?id=' . $id . '.txt&password=' . $password . '&iv='.bin2hex($iv).'" value="Kopieren">';

                        echo '<br><br><a href="' . 'mailto:?subject=Eine Nachricht wurde für Sie hinterlegt (' . $id . ')&body=Bitte klicken Sie auf den folgenden Link%0D%0A%0D%0A' . $script_path . "?id=$id.txt%26password=$password" . ".%0D%0A%0D%0A Anschlie&szlig;end wird diese Nachricht unwiederbringlich gel&ouml;scht!" . '">Per Mail teilen</a>';

                        if($mail){
				$to = openssl_encrypt($mail, $cipher, $password, $options, $iv);
                                $mailwritten = file_put_contents("$notepath/$id.mail", $to);
                                mail(
                                        $mail, //TO
                                        "Nachricht $id wurde erstellt", // SUBJECT
                                        "Ihre Nachricht mit der ID $id wurde am " . date("d.m.Y") . " um " . date("H:i:s") . " Uhr erstellt. \n" .  //MESSAGE
                                        "Sie erhalten eine weitere Mail wenn Sie vom Emfpänger gelesen wurde."
                                );
                        }
                }
?>

<br><hr><b>Neue Nachricht</b>
<form class="col s12" method="POST" action="index.php">
  <div class="row">
    <div class="input-field center">
      <textarea name="message" id="message" class="materialize-textarea" placeholder="Ihre geheime Nachricht"></textarea>
      <input name="mail" id="mail" class="matrialize-textinput" placeholder="Lesebestätigung an diese E-Mail Adress (optional)" type="text">
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
    <hr>
    <div class="footer-copyright">
      <div class="container">
      Made by <a class="orange-text text-lighten-3" href="http://materializecss.com">Materialize</a>
      </div>
    </div>

<style>

.content {
  position: relative;
  height: 500px;
  width: 500px;
  margin: auto;
  animation: content-shift 15s infinite;
}

@keyframes content-shift {
  0% {
    opacity: 1;
  }
  70% {
    opacity: 1;
  }
  80% {
    opacity: 0;
  }
  90% {
    opacity: 1;
  }
  100% {
    opacity: 1;
  }
}


.page {
  height: 100%;
  width: 100%;
  background-color: #7e5d38;
  padding: 20px;
  box-sizing: border-box;
  box-shadow: inset 0px 0px 37px 0px rgba(0,0,0,0.7);
  -webkit-clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);
  clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);
}

.text {
  font-family: 'Courier New', Courier, monospace;
  position: absolute;
  left: 0;
  top: 0;
  margin: 32px;
}

p {
  line-height: 24px;
}

.burn {
  position: absolute;
  height: 0px;
  width: 0px;
  background-color: rgb(30,30,30);
  border-radius: 50%;
  top: 50%;
  right: 50%;
  animation: 15s burn-grow linear infinite;
  border: 3px solid rgb(49, 22, 1);
  -webkit-box-shadow: inset 0px 0px 6px 2px #fffb5c00, inset 0px 0px 8px 3px rgb(52, 21, 0), 0px 0px 6px 2px #3f1c0100, 0px 0px 15px 10px rgba(105, 46, 0, 0), 0px 0px 17px 18px #401d02eb, inset 0px 0px 29px 22px #c4720f00;
  -moz-box-shadow: inset 0px 0px 6px 2px #fffb5c00, inset 0px 0px 8px 3px rgb(52, 21, 0), 0px 0px 6px 2px #3f1c0100, 0px 0px 15px 10px rgba(105, 46, 0, 0), 0px 0px 17px 18px #401d02eb, inset 0px 0px 29px 22px #c4720f00;
  box-shadow: inset 0px 0px 6px 2px #fffb5c00, inset 0px 0px 8px 3px rgb(52, 21, 0), 0px 0px 6px 2px #3f1c0100, 0px 0px 15px 10px rgba(105, 46, 0, 0), 0px 0px 17px 18px #401d02eb, inset 0px 0px 29px 22px #c4720f00;
}

@keyframes burn-grow {
  0% {
    opacity: 1;
    height: 0px;
    width: 0px;
    top: 50%;
    right: 50%;
    border: 3px solid #FFFB5C;
    -webkit-box-shadow: inset 0px 0px 6px 2px #FFFB5C, inset 0px 0px 5px 6px rgba(243, 108, 0,0.5), 0px 0px 6px 2px #FFFB5C, 0px 0px 15px 10px rgba(241, 124, 4, 0.6), 0px 0px 8px 11px #1c0901eb, inset 0px 0px 29px 22px #c4720f42;
    -moz-box-shadow: inset 0px 0px 6px 2px #FFFB5C, inset 0px 0px 5px 6px rgba(243, 108, 0,0.5), 0px 0px 6px 2px #FFFB5C, 0px 0px 15px 10px rgba(241, 124, 4, 0.6), 0px 0px 8px 11px #1c0901eb, inset 0px 0px 29px 22px #c4720f42;
    box-shadow: inset 0px 0px 6px 2px #FFFB5C, inset 0px 0px 5px 6px rgba(243, 108, 0,0.5), 0px 0px 6px 2px #FFFB5C, 0px 0px 15px 10px rgba(241, 124, 4, 0.6), 0px 0px 8px 11px #1c0901eb, inset 0px 0px 29px 22px #c4720f42;

  }
  60% {
    height: 350px;
    width: 350px;
    top: calc(50% - 175px);
    right: calc(50% - 175px);
    border: 3px solid #FFFB5C;
    -webkit-box-shadow: inset 0px 0px 6px 2px #FFFB5C, inset 0px 0px 5px 6px rgba(243, 108, 0,0.5), 0px 0px 6px 2px #FFFB5C, 0px 0px 15px 10px rgba(241, 124, 4, 0.6), 0px 0px 8px 11px #1c0901eb, inset 0px 0px 29px 22px #c4720f42;
    -moz-box-shadow: inset 0px 0px 6px 2px #FFFB5C, inset 0px 0px 5px 6px rgba(243, 108, 0,0.5), 0px 0px 6px 2px #FFFB5C, 0px 0px 15px 10px rgba(241, 124, 4, 0.6), 0px 0px 8px 11px #1c0901eb, inset 0px 0px 29px 22px #c4720f42;
    box-shadow: inset 0px 0px 6px 2px #FFFB5C, inset 0px 0px 5px 6px rgba(243, 108, 0,0.5), 0px 0px 6px 2px #FFFB5C, 0px 0px 15px 10px rgba(241, 124, 4, 0.6), 0px 0px 8px 11px #1c0901eb, inset 0px 0px 29px 22px #c4720f42;

  }
  66% {
    opacity: 1;
    border: 3px solid rgb(49, 22, 1);
    -webkit-box-shadow: inset 0px 0px 6px 2px #fffb5c00, inset 0px 0px 8px 3px rgb(52, 21, 0), 0px 0px 6px 2px #3f1c0100, 0px 0px 15px 10px rgba(105, 46, 0, 0), 0px 0px 17px 18px #401d02eb, inset 0px 0px 29px 22px #c4720f00;
    -moz-box-shadow: inset 0px 0px 6px 2px #fffb5c00, inset 0px 0px 8px 3px rgb(52, 21, 0), 0px 0px 6px 2px #3f1c0100, 0px 0px 15px 10px rgba(105, 46, 0, 0), 0px 0px 17px 18px #401d02eb, inset 0px 0px 29px 22px #c4720f00;
    box-shadow: inset 0px 0px 6px 2px #fffb5c00, inset 0px 0px 8px 3px rgb(52, 21, 0), 0px 0px 6px 2px #3f1c0100, 0px 0px 15px 10px rgba(105, 46, 0, 0), 0px 0px 17px 18px #401d02eb, inset 0px 0px 29px 22px #c4720f00;
  }
  75% {
    opacity: 1;
    height: 350px;
    width: 350px;
    top: calc(50% - 175px);
    right: calc(50% - 175px);
  }
  80% {
    opacity: 0;
  }
  100% {
    opacity: 0;
  }
}

.burn .flame {
  background-color: #fffc98;
  position: absolute;
  -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
  -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
  box-shadow: 0px 0px 0px 0px #FFFB5C;
}

.burn .flame:nth-of-type(1) {
  border-radius: 50% 0;
  animation: 15s flame-1 linear infinite;
  transform-origin: bottom left;
  opacity: 0;
}

@keyframes flame-1 {
  0% {
    opacity: 1;
    height: 0px;
    width: 0px;
    left: 8%;
    bottom: 76%;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
    background-color: #fffc98;
  }
  2% {
    transform: rotate(-15deg);
  }
  5% {
    transform: rotate(-45deg);
  }
  9% {
    transform: rotate(-15deg);
  }
  11% {

    transform: rotate(-45deg);
  }
  15% {
    height: 20px;
    width: 20px;
    transform: rotate(-15deg);
  }
  18% {
    transform: rotate(-45deg);
  }
  21% {
    transform: rotate(-15deg);
  }
  24% {
    transform: rotate(-45deg);
  }
  28% {
    height: 5px;
    width: 5px;
    transform: rotate(-15deg);
  }
  31% {
    transform: rotate(-45deg);
  }
  34% {
    transform: rotate(-15deg);
  }
  38% {
    transform: rotate(-45deg);
  }
  41% {
    transform: rotate(-20deg);
  }
  46% {
    height: 20px;
    width: 20px;
    left: 5%;
    bottom: 76%;
    -webkit-box-shadow: 0px 0px 5px 4px #FFFB5C;
    -moz-box-shadow: 0px 0px 5px 4px #FFFB5C;
    box-shadow: 0px 0px 5px 4px #FFFB5C;
    transform: rotate(-40deg);
    opacity: 1;
  }
  48% {
    transform: rotate(-20deg);
  }
  56% {
    transform: rotate(-40deg);
    height: 0px;
    width: 0px;
    left: 7%;
    bottom: 76%;
    opacity: 0;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
  }
}


.burn .flame:nth-of-type(2) {
  border-radius: 50% 0;
  animation: 15s flame-2 linear infinite;
  transform-origin: bottom left;
  opacity: 0;
}

@keyframes flame-2 {
  0% {
    height: 0px;
    width: 0px;
    left: 31%;
    transform: rotate(-17deg);
    top: 2%;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
    opacity: 1;
  }
  3% {
    transform: rotate(-2deg);
  }
  6% {
    height: 0px;
    width: 0px;
    top: 2%;
    transform: rotate(-32deg);
  }
  10% {
    transform: rotate(-2deg);
  }
  13% {
    transform: rotate(-32deg);
  }
  16% {
    height: 20px;
    width: 20px;
    top: -13%;
    transform: rotate(-2deg);
  }
  20% {
    transform: rotate(-32deg);
  }
  23% {
    transform: rotate(-2deg);
  }
  26% {
    transform: rotate(-32deg);
  }
  30% {
    transform: rotate(-2deg);
  }
  33% {
    transform: rotate(-32deg);
  }
  36% {
    transform: rotate(-2deg);
  }
  31% {

    transform: rotate(-32deg);
  }
  43% {
    transform: rotate(-7deg);
  }
  46% {
    height: 0px;
    width: 0px;
    left: 31%;
    top: 3%;
    -webkit-box-shadow: 0px 0px 5px 4px #FFFB5C;
    -moz-box-shadow: 0px 0px 5px 4px #FFFB5C;
    box-shadow: 0px 0px 5px 4px #FFFB5C;
    transform: rotate(-27deg);
    opacity: 1;
  }
  50% {
    transform: rotate(-7deg);
  }
  56% {
    transform: rotate(-27deg);
    height: 0px;
    width: 0px;
    left: 31%;
    top: 3%;
    opacity: 0;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
  }
}

.burn .flame:nth-of-type(3) {
  border-radius: 50% 0;
  animation: 15s flame-3 linear infinite;
  transform-origin: bottom left;
  opacity: 0;
}

@keyframes flame-3 {
  0% {
    height: 0px;
    width: 0px;
    left: 40%;
    transform: rotate(-15deg);
    top: -6%;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
    opacity: 1;
  }
  3% {
    transform: rotate(15deg);
  }
  7% {
    transform: rotate(-15deg);
  }
  10% {

    transform: rotate(15deg);
  }
  13% {
    transform: rotate(-15deg);
  }
  17% {
    height: 0px;
    width: 0px;
    transform: rotate(15deg);
  }
  20% {
    transform: rotate(-15deg);
  }
  23% {
    height: 20px;
    width: 20px;
    top: -10%;
    transform: rotate(15deg);
  }
  26% {
    transform: rotate(-15deg);
  }
  30% {
    transform: rotate(15deg);
  }
  33% {
    transform: rotate(-15deg);
  }
  36% {
    transform: rotate(15deg);
  }
  40% {
    transform: rotate(-15deg);
  }
  43% {
    transform: rotate(10deg);
  }
  46% {
    left: 40%;
    top: -2%;
    -webkit-box-shadow: 0px 0px 5px 4px #FFFB5C;
    -moz-box-shadow: 0px 0px 5px 4px #FFFB5C;
    box-shadow: 0px 0px 5px 4px #FFFB5C;
    transform: rotate(-10deg);
    opacity: 1;
  }
  50% {
    transform: rotate(10deg);
  }
  56% {
    transform: rotate(-10deg);
    height: 0px;
    width: 0px;
    left: 40%;
    top: -2%;
    opacity: 0;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
  }
}

.burn .flame:nth-of-type(4) {
  border-radius: 0 50%;
  animation: 15s flame-4 linear infinite;
  transform-origin: bottom right;
  opacity: 0;
}

@keyframes flame-4 {
  0% {
    height: 0px;
    width: 0px;
    right: 20%;
    transform: rotate(0deg);
    top: 5%;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
    opacity: 1;
  }
  3% {
    transform: rotate(40deg);
  }
  7% {

    transform: rotate(0deg);
  }
  10% {
    transform: rotate(40deg);
  }
  13% {
    transform: rotate(0deg);
  }
  17% {
    height: 0px;
    width: 0px;
    transform: rotate(40deg);
  }
  20% {
    transform: rotate(0deg);
  }
  23% {
    transform: rotate(40deg);
  }
  26% {
    transform: rotate(0deg);
  }
  30% {
    transform: rotate(40deg);
  }
  33% {
    transform: rotate(0deg);
  }
  36% {
    transform: rotate(40deg);
  }
  40% {
    transform: rotate(0deg);
  }
  43% {
    transform: rotate(30deg);
  }
  46% {
    height: 20px;
    width: 20px;
    right: 20%;
    top: 5%;
    -webkit-box-shadow: 0px 0px 5px 4px #FFFB5C;
    -moz-box-shadow: 0px 0px 5px 4px #FFFB5C;
    box-shadow: 0px 0px 5px 4px #FFFB5C;
    transform: rotate(10deg);
    opacity: 1;
  }
  50% {
    transform: rotate(20deg);
  }
  56% {
    transform: rotate(10deg);
    height: 0px;
    width: 0px;
    right: 20%;
    top: 7%;
    opacity: 0;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
  }
}

.burn .flame:nth-of-type(5) {
  border-radius: 0 50%;
  animation: 15s flame-5 linear infinite;
  transform-origin: bottom right;
  opacity: 0;
}

@keyframes flame-5 {
  0% {
    height: 0px;
    width: 0px;
    left: 98%;
    transform: rotate(15deg);
    top: 38%;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
    opacity: 1;
  }
  3% {
    transform: rotate(-15deg);
  }
  7% {
    transform: rotate(15deg);
  }
  10% {
    transform: rotate(-15deg);
  }
  13% {
    transform: rotate(15deg);
  }
  17% {
    height: 0px;
    width: 0px;
    left: 98%;
    transform: rotate(-15deg);
  }
  20% {
    transform: rotate(15deg);
  }
  23% {
    transform: rotate(-15deg);
  }
  26% {
    transform: rotate(15deg);
  }
  30% {
    transform: rotate(-15deg);
  }
  33% {
    height: 20px;
    width: 20px;
    left: 90%;
    transform: rotate(15deg);
  }
  36% {
    transform: rotate(-15deg);
  }
  40% {
    transform: rotate(15deg);
  }
  43% {
    transform: rotate(10deg);
  }
  46% {
    top: 38%;
    height: 20px;
    width: 20px;
    -webkit-box-shadow: 0px 0px 5px 4px #FFFB5C;
    -moz-box-shadow: 0px 0px 5px 4px #FFFB5C;
    box-shadow: 0px 0px 5px 4px #FFFB5C;
    transform: rotate(-10deg);
    opacity: 1;
  }
  50% {
    transform: rotate(10deg);
  }
  56% {
    transform: rotate(-10deg);
    height: 0px;
    width: 0px;
    left: 98%;
    top: 38%;
    opacity: 0;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
  }
}

.burn .flame:nth-of-type(6) {
  border-radius: 0 50%;
  animation: 15s flame-6 linear infinite;
  transform-origin: bottom right;
  opacity: 0;
}

@keyframes flame-6 {
  0% {
    height: 0px;
    width: 0px;
    left: 96%;
    transform: rotate(45deg);
    top: 35%;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
    opacity: 1;
  }
  2% {
    transform: rotate(75deg);
  }
  5% {
    transform: rotate(45deg);
  }
  8% {
    transform: rotate(75deg);
  }
  11% {
    transform: rotate(45deg);
  }
  15% {
    height: 0px;
    width: 0px;
    left: 96%;
    transform: rotate(75deg);
  }
  18% {
    transform: rotate(45deg);
  }
  21% {
    transform: rotate(75deg);
  }
  24% {
    transform: rotate(45deg);
  }
  28% {
    transform: rotate(75deg);
  }
  31% {
    height: 20px;
    width: 20px;
    left: 90%;
    transform: rotate(45deg);
  }
  34% {
    transform: rotate(75deg);
  }
  38% {
    transform: rotate(45deg);
  }
  41% {
    transform: rotate(70deg);
  }
  44% {
    height: 20px;
    width: 20px;
    left: 90%;
    top: 35%;
    -webkit-box-shadow: 0px 0px 5px 4px #FFFB5C;
    -moz-box-shadow: 0px 0px 5px 4px #FFFB5C;
    box-shadow: 0px 0px 5px 4px #FFFB5C;
    transform: rotate(50deg);
    opacity: 1;

  }
  48% {
    transform: rotate(70deg);
  }
  56% {
    transform: rotate(50deg);
    height: 0px;
    width: 0px;
    left: 100%;
    top: 38%;
    opacity: 0;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
  }
}

.burn .flame:nth-of-type(7) {
  border-radius: 0 50%;
  animation: 15s flame-7 linear infinite;
  transform-origin: bottom right;
  opacity: 0;
}

@keyframes flame-7 {
  0% {
    height: 0px;
    width: 0px;
    left: 63%;
    transform: rotate(70deg);
    top: 91%;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
    opacity: 1;
  }
  3% {
    transform: rotate(40deg);
  }
  7% {
    transform: rotate(70deg);
  }
  10% {
    transform: rotate(40deg);
  }
  13% {
    transform: rotate(70deg);
  }
  17% {
    transform: rotate(40deg);
  }
  20% {
    transform: rotate(70deg);
  }
  23% {
    transform: rotate(40deg);
  }
  26% {
    height: 20px;
    width: 20px;
    left: 60%;
    top: 84%;
    transform: rotate(70deg);
  }
  30% {
    transform: rotate(40deg);
  }
  33% {
    transform: rotate(70deg);
  }
  36% {
    transform: rotate(40deg);
  }
  40% {
    transform: rotate(70deg);
  }
  43% {
    transform: rotate(45deg);
    -webkit-box-shadow: 0px 0px 5px 4px #FFFB5C;
    -moz-box-shadow: 0px 0px 5px 4px #FFFB5C;
    box-shadow: 0px 0px 5px 4px #FFFB5C;
    opacity: 1;
  }
  46% {
    height: 0px;
    width: 0px;
    left: 80%;
    top: 93%;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
    transform: rotate(65deg);
    opacity: 0;
  }
}

.burn .flame:nth-of-type(8) {
  border-radius: 0 50%;
  animation: 15s flame-8 linear infinite;
  transform-origin: bottom right;
  opacity: 0;
}

@keyframes flame-8 {
  0% {
    height: 0px;
    width: 0px;
    left: 69%;
    transform: rotate(80deg);
    top: 80%;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
    opacity: 1;
  }
  3% {
    transform: rotate(50deg);
  }
  7% {
    transform: rotate(80deg);
  }
  10% {
    transform: rotate(50deg);
  }
  13% {
    transform: rotate(80deg);
  }
  17% {
    transform: rotate(50deg);
  }
  20% {
    transform: rotate(80deg);
  }
  23% {
    transform: rotate(50deg);
  }
  26% {
    transform: rotate(80deg);
  }
  30% {
    transform: rotate(50deg);
  }
  33% {
    height: 30px;
    width: 30px;
    left: 66%;
    top: 74%;
    transform: rotate(80deg);
  }
  36% {
    transform: rotate(50deg);
  }
  40% {
    transform: rotate(80deg);
  }
  43% {
    transform: rotate(55deg);
  }
  46% {
    height: 0px;
    width: 0px;
    left: 79%;
    top: 92%;
    -webkit-box-shadow: 0px 0px 5px 4px #FFFB5C;
    -moz-box-shadow: 0px 0px 5px 4px #FFFB5C;
    box-shadow: 0px 0px 5px 4px #FFFB5C;
    transform: rotate(75deg);
    opacity: 1;
  }
  50% {
    transform: rotate(55deg);
  }
  56% {
    transform: rotate(75deg);
    height: 0px;
    width: 0px;
    left: 79%;
    top: 90%;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
    opacity: 0;
  }
}

.burn .flame:nth-of-type(9) {
  border-radius: 0 50%;
  transform: rotate(65deg);
  animation: 15s flame-9 linear infinite;
  transform-origin: bottom right;
  opacity: 0;
}

@keyframes flame-9 {
  0% {
    height: 0px;
    width: 0px;
    left: 23%;
    top: 85%;
    transform: rotate(70deg);
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
    opacity: 1;
  }
  5% {
    transform: rotate(40deg);
  }
  8% {
    transform: rotate(70deg);
  }
  11% {
    transform: rotate(40deg);
  }
  15% {
    transform: rotate(70deg);
  }
  18% {
    transform: rotate(40deg);
  }
  22% {
    height: 20px;
    width: 20px;
    left: 23%;
    top: 85%;
    transform: rotate(70deg);
  }
  24% {
    transform: rotate(40deg);
  }
  28% {
    transform: rotate(70deg);
  }
  31% {
    transform: rotate(40deg);
  }
  35% {
    transform: rotate(70deg);
  }
  38% {
    transform: rotate(40deg);
  }
  42% {
    transform: rotate(70deg);
  }
  44% {
    transform: rotate(45deg);
  }
  48% {
    height: 20px;
    width: 20px;
    left: 23%;
    top: 87%;
    -webkit-box-shadow: 0px 0px 5px 4px #FFFB5C;
    -moz-box-shadow: 0px 0px 5px 4px #FFFB5C;
    box-shadow: 0px 0px 5px 4px #FFFB5C;
    transform: rotate(65deg);
    opacity: 1;
  }
  51% {
    transform: rotate(45deg);
  }
  56% {
    transform: rotate(65deg);
    height: 0px;
    width: 0px;
    left: 29%;
    top: 97%;
    opacity: 0;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
  }
}

.burn .flame:nth-of-type(10) {
  border-radius: 0 50%;
  animation: 15s flame-10 linear infinite;
  transform-origin: bottom right;
  opacity: 0;
}

@keyframes flame-10 {
  0% {
    height: 0px;
    width: 0px;
    top: 57%;
    left: 0%;    
    transform: rotate(-10deg);
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
    opacity: 1;
  }
  3% {
    transform: rotate(10deg);
  }
  7% {
    transform: rotate(-10deg);
  }
  10% {
    transform: rotate(10deg);
  }
  13% {
    transform: rotate(-10deg);
  }
  17% {
    transform: rotate(10deg);
  }
  20% {
    transform: rotate(-10deg);
  }
  23% {
    height: 15px;
    width: 15px;
    top: 57%;
    left: -7%;  
    transform: rotate(10deg);
  }
  26% {
    transform: rotate(-10deg);
  } 
  30% {
    transform: rotate(10deg);
  }
  33% {
    transform: rotate(-10deg);
  }
  36% {
    transform: rotate(10deg);
  }
  40% {
    transform: rotate(-10deg);
  }
  43% {
    transform: rotate(10deg);
  }
  46% {
    height: 10px;
    width: 10px;
    top: 57%;
    left: -7%;
    opacity: 1;
    -webkit-box-shadow: 0px 0px 5px 4px #FFFB5C;
    -moz-box-shadow: 0px 0px 5px 4px #FFFB5C;
    box-shadow: 0px 0px 5px 4px #FFFB5C;
    transform: rotate(-10deg);
  }
  50% {
    transform: rotate(10deg);
  }
  56% {
    transform: rotate(-10deg);
    height: 0px;
    width: 0px;
    top: 57%;
    left: 0%;  
    opacity: 0;
    -webkit-box-shadow: 0px 0px 0px 0px #FFFB5C;
    -moz-box-shadow: 0px 0px 0px 0px #FFFB5C;
    box-shadow: 0px 0px 0px 0px #FFFB5C;
  }
}

.highlight {
  position: absolute;
  border-radius: 50%;
  height: 350px;
  width: 350px;
  top: calc(50% - 175px);
  right: calc(50% - 175px);
  box-shadow: 0px 0px 71px 101px transparent;
  animation: 15s grow-highlight linear infinite;
}

@keyframes grow-highlight {
  0% {
    height: 0px;
    width: 0px;
    top: 50%;
    right: 50%;
    box-shadow: 0px 0px 71px 101px #dcaa71;
  }
  53% {
    box-shadow: 0px 0px 71px 101px #dcaa71;
  }
  66% {
    height: 350px;
    width: 350px;
    top: calc(50% - 175px);
    right: calc(50% - 175px);
    box-shadow: 0px 0px 71px 101px transparent;
  }
}

</style>


<script type="text/javascript">
new Clipboard('#copy-btn');
</script>
</body>
</html>
