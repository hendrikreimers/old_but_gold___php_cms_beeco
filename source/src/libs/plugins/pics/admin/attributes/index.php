<?

/*
   BESCHREIBUNG:
   
   Zeigt die Gruppen an
   
*/

function initialize($dObj)
{

	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];
	
	/*--- Grundaktionen --- start --- */
	
	//Standard Aktionen laden
	#require($path."/includes/functions/text.functions.php");  # Text Funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php");        # Zusätzliche Plugin SQL Funktionen
	
	//Templates laden
	$tpl->path = PLUGIN_PATH."/templates/attributes/";
	$tpl->load("index",1);
	$ground_tpl->load("ground");
	
	//Klasse einrichten für die Galerie
	$psql = new pics_mysql_class;
	$psql->prefix = $settings["mysql"]["table_prefix"];
	
	//Installationsprüfung
	if ( !$sql->install_check("pics") )
	{
		$sql->close();
		die("<b>Bildergalerie noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}
	
	/*--- Grundaktionen --- ende --- */
	
	
	
	/*--- Gruppen einfügen --- start --- */
	
	//Gruppen laden
	$groups = $psql->load_groups($settings["pics"]["order_groups"]);
	
	if ( $groups[0] )
	{
		foreach ( $groups as $group )
		{
			//Zusätzliche Werte
			$group["path"] = REL_PATH; # Root Pfad
			$group[(($group["mode"] == 0) ? "selected_active" : (($group["mode"] == 1) ? "selected_invisible" : "selected_deactivated" ))] = " checked"; # Modus auswählen
			
			//Einfügen
			$tpl->insertArray($group,array("group"));
		}
	}
	
	/*--- Gruppen einfügen --- ende --- */
	
	
	
	/*--- abschluss --- start --- */
	
	//Plugin Template für Ausgabe bereit machen
	$tpl->insertVar("SID",$SID);
	$content = $tpl->getResult();
	
	//Ausgabe
	$ground_tpl->insertVar("text",$content);
	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title","Bildergalerie");
	$ground_tpl->insertVar("name","pics");
	$retVal = $ground_tpl->getResult();
	
	//Verbindung beenden
	$sql->close();
	
	//Speicher freigeben
	$tpl->clear();
	$ground_tpl->clear();
	
	return $retVal;
	
	/*--- abschluss --- ende --- */
}

?>