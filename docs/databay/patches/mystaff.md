# Performance-Optimierung bei deaktivierter "My Staff"-Komponente

Dieser Patch verhindert die Ausführung performance-kritischer Datenbankzugriffe der Komponente "MyStaff", wenn sie deaktiviert ist:
Siehe: https://gitlab.databay.de/ilias-hosting/ilias/-/issues/3

## Patch-Markierungen

Patches wurden mit `databay-patch: begin mystaff` und `databay-patch: end mystaff` markiert.

## Änderungen

Angepasst wurden im Rahmen der Funktionalität folgende Dateien:

* Services/MyStaff/classes/class.ilMyStaffAccess.php
* Services/MyStaff/classes/Provider/StaffMainBarProvider.php

Die performancekritischen Zugriffe (Bereinigung temporärer Tabellen) finden in ilMyStaffAccess::getInstance statt.
Der Patch prüft an dieser Stelle und im Provider für das Hauptmenü ob "MyStaff" aktiv ist und verhindert die Ausführung. 

"My Staff" wird über "Administration > Settings > Organisational Units > Enable Main Menu Entry" aktiviert.

Stellen außerhalb der Komponente, in denen "mystaff" gefunden wurde, müssen nicht angepasst werden:
* ilDashboardGUI: nur Code zur Weiterleitung an MyStaffGUI, der ohne Organisationseinheiten nicht durchlaufen wird
* ilUserStartingPointRepository: hier greift der Patch in ilMyStaffAccess

## Spezifikation

Hostings von ILIAS 9, die keine Organisationseinheiten verwenden, sollen performant laufen.