<?

/*
   BESCHREIBUNG:
   
   Überprüft die Änderungen und übernimmt diese in die DB
*/

function initialize($dObj)
{
	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];

	/*--- defaults --- start --- */

	//Standard Aktionen laden
	$path    = REL_PATH;                                       # Pfad zu den einzubindenden Scripten

	//Änderungshinweis
	$text = message(BASE_PATH,"settings","true",$SID);

	/*--- defaults --- ende --- */



	/*--- update --- start --- */

	//Neue Einstellungen verpacken
	$new_settings["base"]["url"]               = base64_encode($_POST['url']);       # URL zur Startseite
	$new_settings["base"]["nl2br"]             = $_POST['auto_nl2br'];           # Zeilenumbruch
	$new_settings["base"]["tagfilter"]         = base64_encode($_POST['tagfilter']); # Tag-Filter
	$new_settings["base"]["template_override"] = $_POST['template_override'];    # Template Override
	$new_settings["base"]["user"]              = base64_encode($_POST['username']);  # Benutzername

	//Kontrolle ob etwas geändert wurde
	if ( ($settings["base"]["url"]               != $new_settings["base"]["url"])               ||
	     ($settings["base"]["nl2br"]             != $new_settings["base"]["nl2br"])             ||
	     ($settings["base"]["tagfilter"]         != $new_settings["base"]["tagfilter"])         ||
	     ($settings["base"]["template_override"] != $new_settings["base"]["template_override"]) ||
	     ($settings["base"]["user"]              != $new_settings["base"]["user"])              ||
	     $_POST['old_password']                                                                 ||
	     $_POST['password']                                                                     ||
	     $_POST['password_phrase'] )
	{
	    //Für die Ausgabe eine Prüfsumme (spart die Mega IF bei der Ausgabe)
	    $done_update = 1; # Prüfung ob eine Meldung ausgegeben werden soll
	    $stop        = 0; # Prüfung ob etwas abgebrochen werden muss
	
	    //Passwort gegebenfalls ändern
	    if ( $_POST['old_password'] || $_POST['password'] || $_POST['password_phrase'] )
	    {
	        //Passwörter verschlüsseln (zum einfachen vergleich)
	        $old_password    = base64_encode(crypt_password($_POST['old_password'],$settings["base"]["checksum"]));
			$new_password    = base64_encode(crypt_password($_POST['password'],$settings["base"]["checksum"]));
			$password_phrase = base64_encode(crypt_password($_POST['password_phrase'],$settings["base"]["checksum"]));

	        //Fehler bei zu wenig Zeichen
	        if ( strlen($_POST['password']) < 4 )
	        {
	            //Bei zu wenig Zeichen
	            $stop = 1;
	            $text = message(BASE_PATH,"settings","minpwsize",$SID);
	        }
	        else if ( !$sql->update_password($old_password,$new_password,$password_phrase,$settings["base"]["checksum"]) )
	        {
	            //Fehler bei ungültigen Eingaben
	            $stop = 1;
	            $text = message(BASE_PATH,"settings","wrongpw",$SID);
	        }
	    }
	    
	    //Standard Einstellungen ändern (nur wenn zuvor kein stop war)
	    if ( $stop != 1 )
	    {
	        //Einstellungen übernehmen
	        if ( !$sql->update_settings("base",$new_settings) )
	        {
	            //Fehlermeldung ausgeben
	            $text = message(BASE_PATH,"settings","empty",$SID);
	        }
	    }
	}
	
	/*--- update --- ende --- */
	
	
	
	/*--- ausgabe --- start --- */
	
	if ( $done_update === 1 )
	{
	    //Templates laden
	    $ground_tpl->load("ground");
	
	    //Standard Variablen einfügen
    	$ground_tpl->insertVar("path",$path);
	    $ground_tpl->insertVar("title",base64_decode($settings["base"]["title"]));
	    $ground_tpl->insertVar("name",$settings["base"]["name"]);
	
		//Formular einfügen
	    $ground_tpl->insertVar("text",$text);
	
	    //Alles ausgeben
	    $retVal = $ground_tpl->getResult();
	}
	else
	{
	    //Verbindung beenden
	    $sql->close();
	
	    //Speicher freigeben
	    $ground_tpl->clear();
	
	    //Weiterleitung ohne Änderungen
	    header("Location: index.php?SID=".$SID."&action=settings");
	}
	
	/*--- Ausgabe --- ende --- */
	
	
	
	/*--- abschluss --- start --- */
	
	//Verbindung beenden
	$sql->close();
	
	//Speicher freigeben
	$ground_tpl->clear();
	
	return $retVal;
	
	/*--- abschluss --- ende --- */
}

?>
