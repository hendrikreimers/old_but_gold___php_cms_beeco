<?

/*
   BESCHREIBUNG:
   
   Entscheidet ob eine Weiterleitung oder Anzeige
   gewählt wurde. Anschließend wird der Inhalt ausgegeben
   
*/

function init_main($dObj) {
	//Der Kommentar der automatisch nach jedem BODY Tag eingefügt wird in der Seite
	$bodyComment = base64_decode("DQo8IS0tDQogICAgIFRoaXMgd2Vic2l0ZSBpcyBwb3dlcmVkIGJ5IEJFRUNPIC0g".
	     			     "Q29udGVudCBNYW5hZ2VtZW50IFN5c3RlbS4NCiAgICAgQmVlY28gaXMgYSBDTVMg".
				     "Y3JlYXRlZCBieSBIZW5kcmlrIFJlaW1lcnMuIEJlZWNvIGlzIGNvcHlyaWdodCBv".
				     "ZiBIZW5kcmlrIFJlaW1lcnMuDQogICAgIE1vcmUgSW5mb3JtYXRpb25zIChHZXJt".
				     "YW4pIGF0IGh0dHA6Ly93d3cuYmVlY28uZGUNCiAgICAgDQogICAgIERpZXNlIFdl".
				     "YnNlaXRlIHdpcmQgdmVyd2FsdGV0IG1pdCBCRUVDTyAtIENvbnRlbnQgTWFuYWdl".
				     "bWVudCBTeXN0ZW0uDQogICAgIEJlZWNvIGlzdCBlaW4gQ01TIGVyc3RlbGx0IHZv".
				     "biBIZW5kcmlrIFJlaW1lcnMuIERpZSBVcmhlYmVycmVjaHRlIGhhdCBIZW5kcmlrI".
				     "FJlaW1lcnMuDQogICAgIFdlaXRlcmUgSW5mb3JtYXRpb25lbiB1bnRlciBodHRwOi".
				     "8vd3d3LmJlZWNvLmRlDQotLT4NCg==");
	
	if ( defined("IMP_OVERRIDE") ) {
	    $bodyComment = "";
	}
	
	if ( defined("IMP_ATTACH") ) { 
	    $bodyComment .= "<!--\n".IMP_ATTACH."\n-->";
	}

	//Wandelt die Objekte wieder zurück
	$sql      = &$dObj["sql"];      # SQL Funktionen
	$tpl      = &$dObj["tpl"];      # Template Funktionen
	$settings = &$dObj["settings"]; # Einstellungen

	//Rewrite Engine check
	if ( $settings["display"]["rewrite"] == "1" ) {
		//Direkten Script Aufruf des Scriptes vermeiden
		if ( !preg_match("/^\/(.*)\.(htm|html|jpg)$/si",$_SERVER["REQUEST_URI"]) ) {
			header("Location: ".REL_PATH."/");
		}
	}

	/*--- choice --- start --- */

	//Text oder Redirect ausgeben?
	if ( $sql->redirect_check($_GET['id']) )
	{
    	//Weiterleitung laden
	    $redirect = base64_decode($sql->load_redirect($_GET['id']));

	    //Gucken ob die Weiterleitung in den Plugin Ordner fuehrt
	    if ( preg_match("=^(plugins)(.*)$=i",$redirect) )
	    {
	        $redirect .= "?id=".$_GET['id'];
	        
	        if ( $settings["base"]["template_override"] == "1" )
	        {
	            $redirect .= "&style=".$_GET['style'];
	        }
	    }
	
	    //Wenn für den Menüpunkt ein Redirect vorhanden ist diesen ausführen
	    header("Location: ".$redirect);
	
	    //NotStop falls Header nich funzen
	    die();
	}
	else if ( strlen($_GET['dad']) > 0 )
	{
		//Damit das Plugin nicht einfach so angezeigt wird obwohl der Menüpunkt unsichtbar ist
		//Muss eine Prüfung erfolgen
		$itemData = $sql->load_item_data($_GET['id']);

		if ( $itemData['is_active'] == 1 ) 
		{
			//Speicher freigeben
			unset($itemData);

			//Dient als alternative um darauf hinzuweisen dass der Link 
			//auf ein Plugin verweist (DirectADD benötigt das zum Beispiel)
			//Wenn nicht gültig wird ein ungültiger Name genommen um die Verzeichniss erkennung auszutricksen ;)
			if ( strlen($_GET['dad']) > 0 ) {
				$plugin = ( preg_match("=^([a-z]*)$=si",$_GET['dad']) ) ? $_GET['dad'] : '---?!NONE!?---';

				if ( is_dir(LIB_PATH."/plugins/".$plugin) ) {
					//Installationspruefung
		    		if ( !$sql->install_check($plugin) ) {
						$content = "<b>Plugin noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!";
				    } else {
				        //Script initialisierung
						$init = ( $_POST['init'] ) ? $_POST['init'] : $_GET['init'];
						$init = ( !preg_match("=^([a-z]{1,})$=i",$init) ) ? "index" : $init;
	
						//Pfad vorbereiten
						define("PLUGIN_PATH",LIB_PATH."/plugins/".$plugin);
	
						//Script laden
				        $load = PLUGIN_PATH."/".$init.".php";
	
						if ( file_exists($load) ) {
							require($load);
							$content = initialize($dObj);
						}
		    		}
				} # END OF "IS DIR"
			}
		} else header("Location: ".REL_PATH."/"); # END IS ACTIVE
	}
	else if ( ($plugin_id = $sql->plugin_check($_GET['id'])) > 0 )
	{
	    $itemData = $sql->load_item_data($_GET['id']);

	    if ( $itemData['is_active'] == 1 ) 
	    {
			unset($itemData);
			
			//Plugin Name
			$plugin = $sql->load_pluginname($plugin_id);
		
			//Installationspruefung
			if ( !$sql->install_check($plugin) ) {
			    $content = "<b>Plugin noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!";
			} else {
		   	    //Script initialisierung
		    	$init = ( $_POST['init'] ) ? $_POST['init'] : $_GET['init'];
				$init = ( !preg_match("=^([a-z]{1,})$=i",$init) ) ? "index" : $init;
	
			    //Pfad vorbereiten
			    define("PLUGIN_PATH",LIB_PATH."/plugins/".$plugin);
	
			    //Script laden
			    $load = PLUGIN_PATH."/".$init.".php";
	
			    if ( file_exists($load) ) {
					require($load);
					$content = initialize($dObj);
			    }
			}
	    } else header("Location: ".REL_PATH."/"); # END IS ACTIVE
	}
	else
	{
	    //Ansonsten dessen Inhalt laden
		
		// Item Informationen
		$itemData = $sql->load_item_data($_GET['id']);

		// Item Aktiv?
	    if ( $itemData['is_active'] == 1 ) 
	    {
			unset($itemData);
	  		$content = stripslashes(auto_nl2br(base64_decode($sql->load_content($_GET['id'])),$settings["base"]["nl2br"],base64_decode($settings["base"]["tagfilter"])));
		} else header("Location: ".REL_PATH."/"); # END IS ACTIVE
	}

	/*--- choice --- ende --- */



	/*--- abschluss --- start --- */

	// Empfangene Daten von Plugins aufteilen und weiterarbeiten
	if ( is_array($content) ) {
		$implodePlugin = $content["implode"]; # Entscheidet ob das Plugin in das Grundtemplate geladen wird
		$content       = $content["content"]; # Der Inhalt aus dem Plugin
	}

	//Content noch etwas modifizieren (unter anderem mit DirectADD	
	$text = directadd($content,LIB_PATH,$settings["base"]["nl2br"],$settings["base"]["tagfilter"],$settings);
	$text = ( $settings["display"]["textencode"] == "0" ) ? $text : htmlentities($text);

	//Link Umwandlung
	if ( $settings["display"]["autoLink"] == 1 ) {
		$text = findAndModifyLinks($text);
	}

	//Emails ggf. schützen
	if ( $settings["display"]["secureMail"] == 1 )
	{
		$text = findAndModifyMail($text);
	}

	//Einfügen
	$tpl->insertVar("text",$text); # Inhalt
	
	//Ausgabe
	if ( $settings["display"]["encodeUml"] == 1 ) {
		if ( $implodePlugin === 0 ) {
			$output = umlEncode($content);
		} else $output = ( IMP === 0 ) ? umlEncode($tpl->getResult()) : insertAfterBody(umlEncode($tpl->getResult()),$bodyComment); # Inhalt ausgeben
	} else {
		if ( $implodePlugin === 0 ) {
			$output = $content;
		} else $output = ( IMP === 0 ) ? $tpl->getResult() : insertAfterBody($tpl->getResult(),$bodyComment); # Inhalt ausgeben
	}
	
	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$tpl->clear();
	
	// ggf. Bilder aus der Galerie verkleinern
	if ( $settings["display"]["imgResize"] === '1' ) {
		if ( file_exists(LIB_PATH.'/plugins/pics/includes/functions/imgreplace.functions.php') ) {
			require(LIB_PATH.'/plugins/pics/includes/functions/imgreplace.functions.php');
			$output = picsImg_resizeReplace($output);
		}
	}

	// Ausgabe
	echo $output;

	/*--- abschluss --- ende --- */
}

?>