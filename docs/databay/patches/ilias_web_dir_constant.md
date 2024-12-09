# Fix `ILIAS_WEB_DIR` constant

Dieser Patch korrigiert eine falsche Belegung der globalen PHP-Konstante `ILIAS_WEB_DIR` im
Setup-Kontext.

## Patch-Markierungen

Patches wurden mit `databay-patch: begin ilias_web_dir` und `databay-patch: end ilias_web_dir` markiert.

## Änderungen

Angepasst wurden im Rahmen der Funktionalität folgende Dateien:

* Services/Component/classes/Setup/class.ilComponentActivatePluginsObjective.php
* Services/Component/classes/Setup/class.ilComponentInstallPluginObjective.php
* Services/Component/classes/Setup/class.ilComponentUpdatePluginObjective.php
* Services/Language/classes/Setup/class.ilPluginLanguageUpdatedObjective.php
* Services/ResourceStorage/classes/Setup/class.ilResourceStorageMigrationHelper.php

## Spezifikation

See: https://mantis.ilias.de/view.php?id=43107#c110424