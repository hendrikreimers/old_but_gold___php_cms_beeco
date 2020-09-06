<?

/*
   BESCHREIBUNG:
   
   Zeigt die Menüpunkte an und das Kontaktformular
   
*/
function initialize($dObj)
{
	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];
	
	//Grundaktionen
	require(BASE_PATH."/includes/functions/imagekey.functions.php"); # ImageKey Generator
	require(BASE_PATH."/includes/settings/imagekey.settings.php");   # Konfiguration für ImageKey
	require(USER_PATH."/settings/plugins/kontakt/other.settings.php");                     # Sonstige Konfigurationen für das Formular

	//Installationspruefung
	if ( !$sql->install_check("kontakt") )
	{
	    $sql->close();
	    die("<b>Kontakt noch nicht installier!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	/*--- versand --- start --- */

	//Im Falle einer Datenrückgabe dieses verarbeiten
	if ( $_POST )
	{
	    //Speicher freigeben (sicher ist sicher)
		unset($status);
		unset($tecError);
	
		//Grunddaten vorbereiten
		$error = message(PLUGIN_PATH,"kontakt","needed_default"); # Benötigte Felder Meldung vorbereiten
		$regexp = $settings["kontakt"]["regexp"]; # Reguläre Ausdrücke laden
	
	    //Eingabe kontrolle
	    foreach ( $_POST as $key => $data )
		{
			//HTML Tags entfernen
			$data = strip_tags($data);
			$_POST[$key] = $data;
	
			//Nur prüfen wenn eingabe vorhanden
			if ( strlen($data) > 0 )
			{
			    //Algemeine Feld kontrolle (außer: E-Mail,Message,SecKey,SecVal)
			    if ( (preg_match("=".$regexp["default"]."=si",$data)) && ($key != "email") && ($key != "message") && ($key != "secval") )
				{
				    $status = ( $status == "0" ) ? "0" : "1";
				} else {
				    //Nur von Feldern den Status ändern wenn Sie nicht noch geprüft werden
				    if ( ($key != "email") && ($key != "message") && ($key != "secval") )
					{
					    $status = "0";
					}
				}

				//E-Mail Kontrolle
				if ( $key == "email" )
				{	
				    if ( preg_match("=".$regexp["email"]."=si",$data) )
					{
			        	$status = ( $status == "0" ) ? "0" : "1";
					} else $status = "0";
				}

				//Nachrichtentext kontrollieren
				if ( $key == "message" )
				{
				    if ( preg_match("=".$regexp["message"]."=siU",$data) )
					{
				    	$status = ( $status == "0" ) ? "0" : "1";
					} else $status = "0";
				}
			
				//Sicherheitsschlüssel prüfen
				if ( ($key == "secval") && ($settings["kontakt"]["security"] == "1") )
				{
				    if ( crypt_password($data,$settings["base"]["checksum"]) == $_POST['seckey'] )
					{
			    		$status = ( $status == "0" ) ? "0" : "1";
					} else $status = "0";
				}
			}
			
		  	//Wird das Feld benötigt, ist aber nicht ausgefüllt?
			$nVal = ( $key == "message" ) ? "nachricht" : (($key == "secval") ? "security" : $key); # Kleine Anpassung um nicht die Datenbank zu verändern (faulheit ich weiß)
	
			if ( ($settings["kontakt"][$nVal] == "1" ) && (strlen($data) == 0) )
			{
			    //Fehler merken und Status auf falsche Eingabe setzen
				$error .= message(PLUGIN_PATH,"kontakt","needed_".$key);
				$status = 0;
				
			} elseif ( strlen($data) == 0 ) {
			    $status = ( $status == "0" ) ? "0" : "1";
			}
		}

		//Bei OK versenden
		if ( $status == "1" )
		{
		    //E-Mail Text bauen
			$message = "IP: ".$_SERVER['REMOTE_ADDR']."\n".
	              	   "Host: ".gethostbyaddr($_SERVER['REMOTE_ADDR'])."\n".
					   "\n\n".
		    		   "Anrede: ".$_POST['anrede']."\n".
	                   "Name: ".$_POST['nachname']."\n".
					   "Vorname: ".$_POST['vorname']."\n".
					   "Firma: ".$_POST['firma']."\n".
					   "Straße: ".$_POST['strasse']."\n".
					   "PLZ: ".$_POST['plz']."\n".
					   "Ort: ".$_POST['ort']."\n".
					   "Telefon: ".$_POST['telefon']."\n".
					   "E-Mail: ".$_POST['email']."\n".
					   "Nachricht: \n\n".$_POST['message'];

			if ( !@mail(base64_decode($settings["kontakt"]["mailto"]),$settings["kontakt"]["subject"],$message,"FROM: ".$_POST['email']) )
			{
			    $tecError = "<b>During sending your Message, an error occured. Please try again later.</b><br/><br/>".
				        "Aufgrund technischer Probleme, konnte die Nachricht nicht gesendet werden.<br/>".
				        "Bitte versuchen Sie es zu einem späteren Zeitpunkt erneut.<br/><br/>";
			} else $isSend = 1;
			
			//Speicher freigeben
		    unset($_POST);
		}
		
		//Status ausgabe
		$status = ( $status == "1" ) ? message(PLUGIN_PATH,"kontakt","true") : message(PLUGIN_PATH,"kontakt","false");
		$error = ( strlen($error) > strlen(message(PLUGIN_PATH,"kontakt","needed_default")) ) ? $error : "";
		
		$status = ( strlen($tecError) == 0 ) ? $status.$error : $tecError;
	}

	/*--- versand --- ende --- */



	/*--- plugin ausgabe --- start --- */

	//Das Plugin ausgeben
	$plugin_tpl = new template_class;
	$plugin_tpl->path = USER_PATH."/templates/plugins/kontakt/";
	
	$plugin_tpl->load((($settings["kontakt"]["security"] == "1") ? "index_security" : "index")) or die("Cannot open Template");

	//Bei nicht korrekter ausfuellung inhalt eingeben
	if ( $isSend != "1" )
	{
	    $plugin_tpl->insertVar("nachname",$_POST['nachname']);
    	$plugin_tpl->insertVar("vorname",$_POST['vorname']);
	    $plugin_tpl->insertVar("firma",$_POST['firma']);
	    $plugin_tpl->insertVar("strasse",$_POST['strasse']);
	    $plugin_tpl->insertVar("plz",$_POST['plz']);
	    $plugin_tpl->insertVar("ort",$_POST['ort']);
	    $plugin_tpl->insertVar("telefon",$_POST['telefon']);
	    $plugin_tpl->insertVar("email",$_POST['email']);
	    $plugin_tpl->insertVar("message",$_POST['message']);
	}

	//Sicherheitsfeld einfügen
	if ($settings["kontakt"]["security"] == "1")
	{
	    $checksum = imagekey($settings["imgGen"],$settings["base"]["checksum"],$settings["tmp_dir"]);
	    $plugin_tpl->insertVar("seckey",$checksum);

	    //Werte einfügen
	    if ( $settings["display"]["rewrite"] == "1" ) {
			//Daten laden
			$iData = $sql->load_item_data($_GET['id']);
			$style = ( ($settings["base"]["template_override"] == "1") && (strlen($_GET['style']) > 0) ) ? $_GET['style'] : "index";

			//Einfgen
			$plugin_tpl->insertVar("url_img",REL_PATH."/".strtolower(umlRewEncode(trim(base64_decode($iData['title']))))."/k/".$checksum.".jpg");
		
			//Speicher freigeben
			unset($BASE_URL);
			unset($style);
			unset($iData);
	    } else $plugin_tpl->insertVar("url_img","?id=".$_GET['id']."&amp;init=loadimg&amp;seckey=".$checksum);
	}

	$plugin_tpl->insertVar("id",$_GET['id']);
	$plugin_tpl->insertVar("path",REL_PATH);

	$content = ( !$status ) ? $plugin_tpl->getResult() : $status.$plugin_tpl->getResult();

	/*--- plugin ausgabe --- start --- */



	/*--- abschluss --- start --- */

	//Inhalt ins Grund Template einfügen
	if ( $settings["display"]["pluginground"] == "1" )
	{
		$retVal = array("implode" => 1,"content" => &$content);
	} else {
		$retVal = array("implode" => 0,"content" => &$content);
	}

	$sql->close();
	$plugin_tpl->clear();

	return $retVal;

	/*--- abschluss --- ende --- */
}

?>