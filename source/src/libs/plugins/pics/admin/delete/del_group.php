<?

/*
   BESCHREIBUNG:
   
   Löscht eine Gruppe mit allen bildern
   
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
	require(USER_PATH."/settings/plugins/pics/other.settings.php");    # Zusätzliche Konfiguration laden
	
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
	
	
	
	/*--- Löschen --- start --- */
	
	//Alle bilder löschen
	if ( $_GET['id'] )
	{
		//Alle Bilder laden
		$items = $psql->load_items($_GET['id']);
		
		//Sofern vorhanden, Bilder löschen
		if ( $items[0]["id"] )
		{
			//Einträge verarbeiten
			foreach ( $items as $item )
			{
				//Sofern vorhanden löschen
				if ( file_exists($settings["pics"]["img_path"]."/".$item["id"]."b.jpg") && file_exists($settings["pics"]["img_path"]."/".$item["id"]."s.jpg") )
				{
					unlink($settings["pics"]["img_path"]."/".$item["id"]."b.jpg");
					unlink($settings["pics"]["img_path"]."/".$item["id"]."s.jpg");
					$psql->del_item($item["id"]);
				}
			}
		}
		
		//Gruppe löschen
		$psql->del_group($_GET['id']);
		
		$content = message(PLUGIN_PATH,"pics","delgrp_true",$SID);
	}
	else
	{
		$content = message(PLUGIN_PATH,"pics","delgrp_false",$SID);
	}
	
	/*--- Löschen --- ende --- */
	
	
	
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