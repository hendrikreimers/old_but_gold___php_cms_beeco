<?

/*
   BESCHREIBUNG:

   Deaktiviert die globalisierung der Variablen
   um somit das CMS System besser zu schützen.
   Einfach vor jedem Script als include einbinden

   Quelle: http://de.php.net/manual/de/function.ini-set.php

*/

// Effectively turn off register_globals without having to edit php.ini
if (ini_get("register_globals")) { // If register globals are enabled

   // Unset $_GET keys
   foreach ($_GET as $get_key => $get_value) {
       @eval("unset(\${$get_key});");
   }

   // Unset $_POST keys
   foreach ($_POST as $post_key => $post_value) {
       @eval("unset(\${$post_key});");
   }

   // Unset $_REQUEST keys
   foreach ($_REQUEST as $request_key => $request_value) {
       @eval("unset(\${$request_key});");
   }
}

/* --------------------------------------------------------------------- */
/* SQL Injections Schutz (added by Hendrik Reimers, www.kern23.de) */

//Alle POST's verarbeiten
if ( sizeof($_POST) > 0 ) {
    //Zeilenumbrueche nicht umwandeln [Escapen] (Schutz generieren)
    $time = time();          # Eindeutigen Zeitstempel
    $md5_N = md5("N".$time); # UNIX Zeilenumbruch Marker
    $md5_R = md5("R".$time); # Windows Zeilenumbruch Marker

    foreach ( $_POST as $key => $val ) {
        //Post verwerfen
        unset($_POST[$key]);

		//Keine Arrays verändern
		if ( !is_array($val) ) {
	        //Zeilenumbrueche aendern
	        $val = str_replace("\n","#".$md5_N."#",str_replace("\r","#".$md5_R."#",$val));
	
	        //Escapen und Zeilenumbrueche wiederherstellen
	        $val = str_replace("#".$md5_N."#","\n",str_replace("#".$md5_R."#","\r",addslashes($val)));
		}

        //POST neu setzen
        $_POST[addslashes(strip_tags(trim($key)))] = $val;
    }
    
    //Speicher freigeben
    unset($time);
    unset($md5_N);
    unset($md5_R);
    unset($key);
    unset($val);
}

//Alle GET's verarbeiten
if ( sizeof($_GET) > 0 ) {
    foreach ( $_GET as $key => $val ) {
        //GET verwerfen
        unset($_GET[$key]);

        // Da GET ueber die Browser URL uebergeben wird,
        // wird der Zeilenumbruch nicht beruecksichtigt.
        $_GET[addslashes(strip_tags(trim($key)))] = addslashes($val);
    }
    
    //Speicher freigeben
    unset($key);
    unset($val);
}

?>