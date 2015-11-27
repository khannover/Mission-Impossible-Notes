# Mission-Impossible-Notes

![](http://khannover.mooo.com/impossible/demo/min_snap1.PNG)

## Beschreibung

- Sicher
  - MIN speichert Ihre Nachrichten verschlüsselt auf dem Server zwischen und löscht beim ersten Lesen die Nachricht automatisch. Das Passwort zum Entschlüsseln wird jedesmal zufällig generiert und im erzeugten Link hinterlegt. Es wird nirgendwo bis zum Abruf der Nachricht gespeichert. Beim Abruf der Nachricht erscheint das Passwort zwar als Parameter im Webserver-Log, die Nachricht wird jedoch im gleichen Augenblick zerstört und damit wird das Passwort unbrauchbar.
- Zusammenarbeit
  - Teilen Sie mit MIN geheime Informationen wie Passwörter, Kontodaten, etc. auf sichere Art und Weise.

## Installation

`git clone https://github.com/khannover/Mission-Impossible-Notes.git`

Es ist wichtig für die Sicherheit, dass das Programm über HTTPS aufgerufen wird.