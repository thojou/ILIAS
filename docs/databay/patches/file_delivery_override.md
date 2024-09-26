# File Delivery Override

Dieser Patch definiert `X-Accel` als Standard-Auslieferungsmethoden für Dateien.

## Patch-Markierungen

Patches wurden mit `databay-patch: begin file_delivery_override` und `databay-patch: end file_delivery_override` markiert.

## Änderungen

Angepasst wurden im Rahmen der Funktionalität folgende Dateien:

* Services/FileDelivery/classes/override.php

## Spezifikation

Hostings von ILIAS 9, sollen performant Dateien über HTTP-Server-Mechanismen ausliefern laufen. Gerade im Kontext
des "WebAccessChecker" ist dies die richtige Maßnahme, um ein langsames Auslieferen oder Streamen von Dateien
über PHP zu verhindern.

Siehe auch:

* https://gitlab.databay.de/ilias-hosting/ilias/-/issues/6
* [docs/configuration/secure.md](./../../configuration/secure.md#use-webaccesschecker)