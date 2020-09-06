<?

/*
   BESCHREIBUNG:
   
   Aktualisiert die Eigenschaften von Gruppen
   
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
	require(PLUGIN_PATH."/includes/classes/mysql.class.php");        # Zusätzliche Plugin SQL Funktionen

	//Templates laden
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
	
	if ( $_POST['groups'] )
	{
		foreach ( $_POST['groups'] as $id => $value )
		{
			//Eigenschaften aktualisieren
			$psql->upd_attributes($id,$value["mode"]);
			
			$content = message(PLUGIN_PATH,"pics","attrupd_true",$SID);
		}
	}
	else $content = message(PLUGIN_PATH,"pics","attrupd_false",$SID);
	
	/*--- Gruppen einfügen --- ende --- */
	
	
	
	/*--- abschluss --- start --- */
	
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