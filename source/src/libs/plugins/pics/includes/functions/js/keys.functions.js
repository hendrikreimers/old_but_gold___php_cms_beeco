
//Pfeiltasten Steuerung
function checkEvent(evt) {
	// MSIE Anpassung
	if ( !evt ) {
		evt = window.event;
	}
	
	//Pfeiltaste links
	if ( evt.keyCode == 37 ) {
		window.location = backLink.replace(/&amp;/g,'&');
	}

	//Pfeiltaste rechts
	if ( evt.keyCode == 39 ) {
		window.location = nextLink.replace(/&amp;/g,'&');
	}
}

//Event Handler initialisieren
document.onkeyup = checkEvent;
