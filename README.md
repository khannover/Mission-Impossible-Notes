# Mission-Impossible-Notes

![](https://hannover38.de/min/screenshot.png?fromGithub=true)

## Beschreibung

- Sicher
  - Jede Nachricht kann nur einmal gelesen werden.
  - MIN speichert Ihre Nachrichten AES-256-verschlüsselt auf dem Server zwischen und löscht beim ersten Lesen die Nachricht automatisch. 
  - Ein 32-stelliges Passwort zum Ver- und Entschlüsseln wird jedesmal zufällig generiert und im erzeugten Link hinterlegt. Es wird nirgendwo bis zum Abruf der Nachricht gespeichert. Beim Abruf der Nachricht erscheint das Passwort zwar als Parameter im Webserver-Log, die Nachricht wird jedoch im gleichen Augenblick zerstört und damit wird das Passwort unbrauchbar.
  - Optional kann der Ersteller eine E-Mail Adresse angeben, um nach Auslieferung benachrichtigt zu werden.
- Zusammenarbeit
  - Teilen Sie mit MIN geheime Informationen wie Passwörter, Kontodaten, etc. auf sichere Art und Weise anstatt im Klartext per E-Mail.
  - Einfach das Geheimnis eingeben, der erzeugte Link kann dann wiederum per E-Mail, Facebook, Whatsapp, ... verschickt werden.
- Hosting
  - Das Programm kann (und sollte) auf einem eigenen Server (z.B. Raspberry Pi o.ä.) gehostet werden. 
  - Dabei sollte aber dennoch unbedingt eine SSL-Verschlüsselung zum Einsatz kommen, um auch eine sichere Übertragung der Nachricht zu gewährleisten.
  - Prinzipiell sollte MIN aber auch auf jedem PHP5-fähigen Webspace laufen.

## Installation

### Git Clone

`git clone https://github.com/khannover/Mission-Impossible-Notes.git`

Das Verzeichnis `.notes` muss für den User, unter dem der Webserver läuft (z.B. www-data), beschreibbar sein.

**Es ist wichtig für die Sicherheit, dass das Programm über HTTPS aufgerufen wird, damit die Nachricht nicht unverschlüsselt und damit unsicher an den Server übertragen wird. Für private Zwecke reicht auch ein selbst signiertes SSL-Zertifikat.**

### Apache2

Der Zugriff auf Dateien im Verzeichnis `.notes` sollte unbedingt im Apache per Befehl verboten werden. Im Repository liegt eine .htaccess-Datei, die genau das bewirkt, falls die Direktive `AllowOverride Limit`oder `AllowOverride All` eingetragen ist.

Beispiel:

```
  <Directory /var/www/min>
    AllowOverride All
  </Directory>
```

Andernfalls können die Textdateien, welche die Nachricht verschlüsselt enthalten, über die Webseite heruntergeladen werden.

### Voraussetzungen

Dieses Tool benötigt das php Module ```mcrypt``` und ```json``` 

## API

Per POST-Request an die api.php können Nachrichten erstellt und gelesen werden.
Dazu muss als Payload ein JSON-Objekt übergeben werden.

### Nachricht erstellen

__Request:__

```json
  {
    "action": "create",
    "message": "hello world",
    "mail": "someone@gmail.com"}
  }
```

  * "action" muss "create" sein.
  * "message" enthält die zu verschlüsselnde Nachricht.
  * "mail" ist die Mailadresse, die über Erstellung und Abholung der Nachricht informiert wird (optional)
  * Beispiel mit curl:

```bash
curl -X POST \
  http://khannover.mooo.com/min/api.php \
  -H 'Content-Type: application/json' \
  -d '{"action": "create","message": "hello world","mail": "someone@gmail.com"}'
```

__Response:__

```json
{
    "link": "http://khannover.mooo.com/min/index.php?id=uJ1K3heMtQzkoI5P6SyGcdfm4DA7lBHqLawFXE2irR0v8WCg9b.txt&password=EUiPkN3x9HRTLZFMu7BaWAmjOVYw5hSd",
    "id": "uJ1K3heMtQzkoI5P6SyGcdfm4DA7lBHqLawFXE2irR0v8WCg9b",
    "password": "EUiPkN3x9HRTLZFMu7BaWAmjOVYw5hSd",
    "status": "success",
    "mailstatus": "sent to someone@gmail.com",
    "mail": "someone@gmail.com",
    "date": "20180301 22:50:33"
}
```

  * "link" enthält einen Link, der im Browser geöffnet werden kann und der die Nachricht entschlüsselt als HTML-Seite anzeigt.
  * "id" ist die ID der neu erstellten Nachricht
  * "password" ist das Passwort, welches zum Entschlüsseln der Nachricht benötigt wird
  * "status" zeigt, ob die Nachricht erfolgreich angelegt wurde
  * "mailstatus" zeigt, ob eine Mail über die Erstellung der Nachricht erfolgreich versendet wurde
  * "mail" ist die Mailadresse, die beim Erstellen der Nachricht angegeben wurde
  * "date" ist das Datum und die Zeit der Verarbeitung
  
### Nachricht abholen

__Request:__

```json 
{
  "action": "get",
  "id": "yxCIRf132wh9JlP8vmVNOoTcQuBUEa5KWAe-X7gjrGFzp4LHDt",
  "password": "idXZNeSm6bzsp1Ilr9Ukn2JKRyCghPTW"
}
```

  * "action" muss "get" sein
  * "id" ist die ID der abzuholenden Nachricht
  * "password" ist das Password, welches beim Erstellen der Nachricht generiert wurde
  * Beispiel mit curl:
```bash
curl -X POST \
  http://khannover.mooo.com/min/api.php \
  -H 'Content-Type: application/json' \
  -d '{"action": "get","id": "yxCIRf132wh9JlP8vmVNOoTcQuBUEa5KWAe-X7gjrGFzp4LHDt","password": "idXZNeSm6bzsp1Ilr9Ukn2JKRyCghPTW"}'
```  
  
__Response:__

```json
{
    "status": "success",
    "message": "hello world",
    "id": "yxCIRf132wh9JlP8vmVNOoTcQuBUEa5KWAe-X7gjrGFzp4LHDt",
    "date": "20180301 22:51:03"
}
```

  * "status" zeigt ob der Request erfolgreich angenommen wurde
  * "message" ist die entschlüsselte Nachricht
  * "id" ist die ID der Nachricht, die entschlüsselt wurde
  * "date" ist der Zeitpunkt der Abholung

## Demo

[Demo](https://hannover38.de/min)

## Donate

If you like my work and want to support me, please donate :-)
Any amount is welcome ;-)

[Donate](https://www.paypal.me/khannover)
