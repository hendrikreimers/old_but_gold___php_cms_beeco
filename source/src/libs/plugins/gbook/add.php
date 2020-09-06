<?

/*
   BESCHREIBUNG:
   
   Zeigt die Menüpunkte an und den inhalt der vom plugin geladen wird
   
*/

function initialize($dObj)
{
	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	#$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];
	
	/*--- Grundaktionen --- start --- */

	require(USER_PATH."/settings/plugins/gbook/other.settings.php");
	require(BASE_PATH."/includes/functions/imagekey.functions.php"); # ImageKey Generator
	require(BASE_PATH."/includes/settings/imagekey.settings.php");   # Konfiguration für ImageKey

	//Plugin Template
	$plugtpl = new template_class; # Neues Objekt
	$plugtpl->path = USER_PATH."/templates/plugins/gbook/"; # Template Pfad
	$plugtpl->load(( ($settings["gbook"]["security"] == "1") ? "add_security" : "add" )); # Template laden

	/*--- Grundaktionen --- ende --- */



	/*--- hinzufügen --- start --- */

	$status = "";

	//Wurde auf hinzufügen gedrückt?
	if ( $_POST["id"] )
	{
	    //Bei bedarf sicherheitschlüssel checken
		if ( $settings["gbook"]["security"] == "1" )
		{
			if ( $_POST['seckey'] == crypt_password($_POST['secval'],$settings["base"]["checksum"]) )
			{
			    $key_correct = 1;
			}
			else $key_correct = 0;
		} else $key_correct = 1;

		//Sind die benötigten felder ausgefüllt?
		if ( ($_POST["name"]) && ($_POST["text"]) && ($key_correct == "1") )
		{
			//Daten prüfen und verschlüsseln
			$name = base64_encode(strip_tags($_POST['name']));
			$text = ( $settings["gbook"]["enable_html"] == "1" ) ? base64_encode($_POST['text']) : base64_encode(strip_tags($_POST['text']));

			//Daten ggf. prüfen (je nach other.settings.php)
			if ( $settings["gbook"]["ignore_email"] == "0" ) {
				$email    = ( preg_match("/^([a-z0-9\.\-\_]*)@([a-z0-9\-]*)\.([a-z]{2,4})$/i",strip_tags($_POST['email'])) ) ? base64_encode(htmlentities(strip_tags($_POST['email']))) : "";
			} else $email = base64_encode(mysql_real_escape_string(htmlentities(strip_tags($_POST['email'])))); # Mindestschutz
			
			if ( $settings["gbook"]["ignore_icq"] == "0" ) {
				$icq      = ( preg_match("/^([0-9\-]*)$/i",$_POST['icq']) ) ? base64_encode(htmlentities(str_replace("-","",strip_tags($_POST['icq'])))) : "";
			} else $icq = base64_encode(mysql_real_escape_string(htmlentities(strip_tags($_POST['icq'])))); # Mindestschutz
			
			if ( $settings["gbook"]["ignore_homepage"] == "0" ) {
				$homepage = ( preg_match("=^(http://)(.*)$=i",$_POST['homepage']) ) ? base64_encode(htmlentities(strip_tags($_POST['homepage']))) : base64_encode("http://".htmlentities(strip_tags($_POST['homepage'])));
			} else $homepage = base64_encode(mysql_real_escape_string(htmlentities(strip_tags($_POST['homepage'])))); # Mindestschutz
			
			//Hinzufügen
			require(PLUGIN_PATH."/includes/classes/mysql.class.php"); # Neue MySQL Klasse
			$gbsql = new gbook_mysql_class;              # Objekt erstellen
			$gbsql->prefix = $settings["mysql"]["table_prefix"];

			$gbsql->add_entry($name,$email,$icq,$homepage,$text,$settings["gbook"]["send_notice"],$settings["gbook"]["notice_email"]);
			unset($gbsql);
		}
		else
		{
			//Felder wieder befüllen
			$plugtpl->insertVar("name",htmlentities(strip_tags($_POST['name'])));
			$plugtpl->insertVar("icq",htmlentities(strip_tags($_POST['icq'])));
			$plugtpl->insertVar("email",htmlentities(strip_tags($_POST['email'])));
			$plugtpl->insertVar("homepage",htmlentities(strip_tags($_POST['homepage'])));
			$plugtpl->insertVar("text",( ($settings["gbook"]["enable_html"] == "1") ? $_POST['text'] : htmlentities(strip_tags($_POST['text'])) ));
		
			//Status bereithalten
			$status = message(PLUGIN_PATH,"gbook","false");
		}
	}

	/*--- hinzufügen --- ende --- */



	/*--- abschluss --- start --- */

	//Sonstige informationen
	$plugtpl->insertVar("id",$_GET['id']);               # Menüpunkt ID
	if ( $settings["base"]["template_override"] == "1" ) # Template
	{
		$plugtpl->insertVar("style",$_GET['style']);
	}

	//Für Rewrite Engine benötigte Daten laden
	if ( $settings["display"]["rewrite"] == "1" ) {
		$iData = $sql->load_item_data($_GET['id']);
		$iTitle = strtolower(umlRewEncode(trim(base64_decode($iData["title"]))));
		unset($iData);
	}

	//weiterleiten?
	if ( (!$_POST['id']) && (!$_POST['name']) && (!$_POST['text']) )
	{
		//Neuen Schlüssel generieren
		if ( $settings["gbook"]["security"] == "1" )
		{
		    $checksum = imagekey($settings["imgGen"],$settings["base"]["checksum"],$settings["tmp_dir"]);

	   		$plugtpl->insertVar("seckey",$checksum);

			if ( $settings["display"]["rewrite"] == "1" ) {
				$plugtpl->insertVar("url_img",REL_PATH."/".$iTitle."/g/".$checksum.".jpg");
			} else $plugtpl->insertVar("url_img","?id=".$_GET['id']."&amp;init=loadimg&amp;seckey=".$checksum);
		}

		//URLs einfügen
		if ( $settings["display"]["rewrite"] == "1" ) {
			$plugtpl->insertVar("url_back",REL_PATH."/".$iTitle.".html");
		} else $plugtpl->insertVar("url_back","?id=".$_GET['id'].( ($settings["base"]["template_override"] == 1) ? "&amp;style=".$_GET['style'] : ""));

	    //Plugin Template für Ausgabe bereit machen
		$content = $plugtpl->getResult();

		//Inhalt ins Grund Template einfügen
		$content = $status.$content; # Inhalt
	}
	else
	{
		if ( !$status )
		{
	        //Weiterleiten
			if ( $settings["display"]["rewrite"] == "1" ) {
				$iData = $sql->load_item_data($_POST['id']);
				$iTitle = strtolower(umlRewEncode(trim(base64_decode($iData["title"]))));
				unset($iData);

				$sql->close();

				header("Location: ".REL_PATH."/".$iTitle.".html");
			}
			else 
			{
				$sql->close();
				header("Location: index.php?id=".$_POST['id']);
			}
		}
		else
		{	
			//Neuen Schlüssel generieren
			if ( $settings["gbook"]["security"] == "1" )
			{
			    $checksum = imagekey($settings["imgGen"],$settings["base"]["checksum"],$settings["tmp_dir"]);

		   		$plugtpl->insertVar("seckey",$checksum);

				if ( $settings["display"]["rewrite"] == "1" ) {
					$plugtpl->insertVar("url_img",REL_PATH."/".$iTitle."/g/".$checksum.".jpg");
				} else $plugtpl->insertVar("url_img","?id=".$_GET['id']."&amp;init=loadimg&amp;seckey=".$checksum);
			}

			//URLs einfügen
			if ( $settings["display"]["rewrite"] == "1" ) {
				$plugtpl->insertVar("url_back",REL_PATH."/".$iTitle.".html");
			} else $plugtpl->insertVar("url_back","?id=".$_GET['id'].( ($settings["base"]["template_override"] == 1) ? "&amp;style=".$_GET['style'] : ""));

		    //Plugin Template für Ausgabe bereit machen
			$content = $plugtpl->getResult();
	
			//Inhalt ins Grund Template einfügen
			$content = $status.$content; # Inhalt

	    	//Verbindung beenden
	    	$sql->close();
		}
	}

	//Inhalt ins Grund Template einfügen
	if ( $settings["display"]["pluginground"] == "1" )
	{
		$retVal = array("implode" => 1,"content" => &$content);
	} else {
		$retVal = array("implode" => 0,"content" => &$content);
	}
	
	$sql->close();
	$plugtpl->clear();
	
	return $retVal;
	
	/*--- abschluss --- ende --- */
}

?>