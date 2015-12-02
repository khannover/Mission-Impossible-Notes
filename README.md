# Mission-Impossible-Notes

![](http://khannover.mooo.com/impossible/demo/min_snap1.PNG)

## Beschreibung

- Sicher
  - Jede Nachricht kann nur einmal gelesen werden.
  - MIN speichert Ihre Nachrichten AES-256-verschlüsselt auf dem Server zwischen und löscht beim ersten Lesen die Nachricht automatisch. 
  - Ein 32-stelliges Passwort zum Ver- und Entschlüsseln wird jedesmal zufällig generiert und im erzeugten Link hinterlegt. Es wird nirgendwo bis zum Abruf der Nachricht gespeichert. Beim Abruf der Nachricht erscheint das Passwort zwar als Parameter im Webserver-Log, die Nachricht wird jedoch im gleichen Augenblick zerstört und damit wird das Passwort unbrauchbar.
- Zusammenarbeit
  - Teilen Sie mit MIN geheime Informationen wie Passwörter, Kontodaten, etc. auf sichere Art und Weise anstatt im Klartext per E-Mail, Facebook oder Whatsapp.
  - Einfach das Geheimnis eingeben, der erzeugte Link kann dann wiederum per E-Mail, Facebook, Whatsapp, ... verschickt werden.
- Hosting
  - Das Programm kann (und sollte) auf einem eigenen Server (z.B. Raspberry Pi o.ä.) gehostet werden. 
  - Dabei sollte aber dennoch unbedingt eine SSL-Verschlüsselung zum Einsatz kommen, um auch eine sichere Übertragung der Nachricht zu gewährleisten.
  - Prinzipiell sollte MIN aber auch auf jedem PHP5-fähigen Webspace laufen.

## Installation

`git clone https://github.com/khannover/Mission-Impossible-Notes.git`

Das Verzeichnis `.notes` muss für den User, unter dem der Webserver läuft (z.B. www-data), beschreibbar sein.

Es ist wichtig für die Sicherheit, dass das Programm über HTTPS aufgerufen wird, damit die Nachricht nicht unverschlüsselt und damit unsicher an den Server übertragen wird. Für private Zwecke reicht auch ein selbst signiertes SSL-Zertifikat.

## Demo

[Demo (Self signed certificate)](https://khannover.mooo.com)
