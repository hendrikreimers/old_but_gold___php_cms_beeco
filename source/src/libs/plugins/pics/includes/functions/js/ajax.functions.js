<!--//

//
//	AJAX JavaScript v1.0
//	von Hendrik Reimers
//
//	E-Mail:   info@kern23.de
//	Internet: www.kern23.de
//
//
//	BESCHREIBUNG:
//	Wer schon immer mal AJAX (Asynchronous JavaScript and XML) benutzen wollte,
//	kann es hiermit ein bisschen leichter. Es gibt zwar schon 1000 Klassen für PHP etc.
//	aber ich möchte nur das nötigste zur Verfügung stellen um es den Benutzer flexibel zu lassen.
//
//	Das ist mein erstes JavaScript in Sachen AJAX!
//	Da JavaScript gerne alles schnell abarbeitet, habe ich es ein wenig unkonventionell gelöst.
//	Es wird gleich beim einbinden dieses Scriptes das HTTP Objekt deklariert als Instanz.
//	Der Benutzer ruft lediglich sendRequest(yourURL,yourCallbackFunction); auf und das Ergebnis,
//	wird nach einer Menge hin und her an die angegebene Callback Funktion übergeben.
//	Denn ohne eine Callback Funktion würde JavaScript alles gemütlich weitermachen, obwohl
//	die HTTP Anfrage noch nicht abgeschlossen ist.
//
//	Kurzfassung:
//	Eine Callback Funktion schreiben musst du! Die du Variabel je nach Anwendungsgebiet aufrufen kannst.
//	die HTTP Anfrage kommt über sendRequest(deinURL,deinCallback);
//


//Variablen deklarieren
//var xmlHTTP = createRequestObj(); //HTTP Instanz
var xmlHTTP = false; //HTTP Instanz


//Sendet eine HTTP Anfrage mittels GET, das Ergebnis wird an eine Callback Funktion übergeben
function sendGETRequest(url,callbackFuncName) {
	// HINWEIS: Die xmlHTTP Instanz wird erst hier deklariert anstatt gleich zuvor,
	// weil in vielen Browsern die Instanz Ihre Gültigkeit ziemlich schnell verliert
	// und es damit nur noch zum Teil funktioniert

	//Instanz deklarieren
	xmlHTTP = createRequestObj();

    //Request absetzen
    xmlHTTP.onreadystatechange = new Function("processResponse('"+callbackFuncName+"');"); // Ereignis Funktion angeben
    xmlHTTP.open('GET',url,true);                                                          // Ziel öffnen
    xmlHTTP.send(null);                                                                    // Request senden
}


//Sendet eine HTTP Anfrage mittels POST, das Ergebnis wird an eine Callback Funktion übergeben
function sendPOSTRequest(url,callbackFuncName,postData) {
	// HINWEIS: Die xmlHTTP Instanz wird erst hier deklariert anstatt gleich zuvor,
	// weil in vielen Browsern die Instanz Ihre Gültigkeit ziemlich schnell verliert
	// und es damit nur noch zum Teil funktioniert

	//Instanz deklarieren
	xmlHTTP = createRequestObj();

    //Request absetzen
    xmlHTTP.onreadystatechange = new Function("processResponse('"+callbackFuncName+"');"); // Ereignis Funktion angeben
    xmlHTTP.open('POST',url);                                                              // Ziel öffnen
	xmlHTTP.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');         // POST Typ angeben
    xmlHTTP.send(postData);                                                                // Request senden
}


//Verarbeiten der HTTP Antwort
function processResponse(callbackFuncName) {
    //Übertragung abgeschlossen?
    if (xmlHTTP.readyState == 4) {
		// Übertragung OK?
		if (xmlHTTP.status == 200) {
	    	//Aufruf der Callback Funktion
			if (typeof window[callbackFuncName] == 'function') {
				window[callbackFuncName](xmlHTTP.responseXML);
			}
		} else {
	    	//Fehlermeldung
	    	alert('AJAX Error!\nCannot handling the Content\n\nHTTP-Status: '+xmlHTTP.status);
		}
    }
}


//Erstellt das HTTP Objekt
function createRequestObj() {
    //Werte zurücksetzen
    var http_request = false;
    
    //Instanz erstellen
    if (window.XMLHttpRequest) {
		//Instanz für Firefox, Mozilla, Safari, Opera, usw.
		http_request = new XMLHttpRequest();
	
		//MIME Typ erzwingen
		if (http_request.overrideMimeType) {
		    http_request.overrideMimeType('text/xml');
		}
    } else if (window.ActiveXObject) {
		//Instanz für IE (eine von 2 Möglichkeiten probieren)
		try {
		    //Instanz: Möglichkeit 1
		    http_request = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
	    	try {
				//Instanz: Möglichkeit 2
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
		    } catch (e) {}
		}
    }
    
    //Bei fehlerhafter Instanzierung, Meldung!
    if (!http_request) {
		alert('AJAX Error!\nCannot create an XMLHTTP instance');
    } else {
		//Ergebnis liefern
		return http_request;
	}
}

//-->