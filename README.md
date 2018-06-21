# Mission-Impossible-Notes

Wird nun hier fortgeführt:
https://hannover38.de/gitea/kai/Mission-Impossible-Notes

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

https://github.com/khannover/Mission-Impossible-Notes/wiki/Installation

## API

https://github.com/khannover/Mission-Impossible-Notes/wiki/API

## Demo

[Demo](https://hannover38.de/min)

## Donate

If you like my work and want to support me, please donate :-)
Any amount is welcome ;-)

[Donate](https://www.paypal.me/khannover)
