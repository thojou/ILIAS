# ILIAS LTI Provider mit SSL hinter Load Balancer

Dieser Patch behebt ein Problem mit dem Aufruf von ILIAS als LTI Provider,
siehe: https://redmine.databay.de/issues/35298

## Patch-Markierungen

Patches wurden mit `databay-patch: begin lti-provider-ssl` und `databay-patch: end lti-provider-ssl` markiert.

## Änderungen

Angepasst wurden im Rahmen der Funktionalität folgende Dateien:

* Services/LTI/src/ToolProvider/System.php

### Problem

Die Signaturprüfung beim LTI Tool Provider schlägt fehl, wenn ILIAS hinter einem Load Balancer betrieben wird, der SSL terminiert.

In die Signaturprüfung beim Aufruf des Tool Providers fließt eine normalisierte URL des Tool Providers ein.
Beim Tool-Consumer ist die URL z.B. als https://aekno.iliasnet.de/lti.php konfiguriert.
Bei der Signaturpüfung wird sie als https://aekno.iliasnet.de:80/lti.php übergeben.

Die 80 kommt daher, dass die Plattform von außen per HTTPS aufgerufen, aber über den Loadbalancer mit Port 80 angesprochen wird.

Die normalisierte URL wird in OAuthRequest::get_normalized_http_url() erzeugt. 
Es wertet die URL aus, die an den Konstruktor von OAuthRequest übergeben wird.
Dort wird https://aekno.iliasnet.de:80/lti.php?client_id=aekno übergeben.
Da der Port nicht der Default-Port des Schemas ist, wird er in der normalisierten URL angehängt.

Die URL wird in OAuthRequest::fromRequest ermittelt, da ilLTIToolProvider dafür keine Parameter übergibt.
Hier wird $_SERVER ausgewertet.

* `$_SERVER['HTTPS']` ist 'on' => als Schema wird 'https' gewählt.
* `$_SERVER['SERVER_PORT']` ist 80 => der Port wird so angehängt.

### Lösung

Patch von \ilLTIToolProvider::authenticate

* Original: `$request = OAuth\OAuthRequest::from_request();`
* Gepatcht: `$request = OAuth\OAuthRequest::from_request(null, ILIAS_HTTP_PATH . '/lti.php');`

Der `http_path` muss in der Datei ilias.ini.php richtig konfiguriert sein. Beim ILIAS-Setup wird er aus der Datei `config.json` gelesen, z.B.:
````
"http" : {
        "path" : " https://aekno.iliasnet.de"
},
````

## Spezifikation

Das Problem trat ursprünglich in der Plattform der ärztlichen Akademie für medizinische Fort- und Weiterbildung in Nordrhein (https://aekno.iliasnet.de) auf. Dort sollen Kurse per LTI freigegeben werden und von anderen Plattformen eingebunden werden können. Beim Aufruf aus der Plattform der Akademie für medizinische Fortbildung (https://ilias.aekwl.de) trat ein Fehler mit der Meldung "Oauth signatore checks failed" auf.

Es wurde zunächst im ILIAS7-Branch des Kunden gefixt: https://gitlab.databay.de/ilias/aekno/-/tree/7_aekno
Da das Problem genereller Natur ist und weitere Plattformen betreffen kann, wird es als Patch in den Hosting-Branch für ILIAS 9 übernommen.




