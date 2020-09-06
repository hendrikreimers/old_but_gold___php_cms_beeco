<?

/*
   BESCHREIBUNG:
   
   Zeigt das Bestätigungsformular zum löschen an
   
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
	$tpl->path = PLUGIN_PATH."/templates/delete/";
	$tpl->load("delfrm_group");
	$ground_tpl->load("ground");
	
	//Installationsprüfung
	if ( !$sql->install_check("pics") )
	{
		$sql->close();
		die("<b>Bildergalerie noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}
	
	/*--- Grundaktionen --- ende --- */
	
	
	
	/*--- abschluss --- start --- */
	
	//Plugin Template für Ausgabe bereit machen
	$tpl->insertVar("id",$_GET['id']);
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