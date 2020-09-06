<?

/*
   BESCHREIBUNG:
   
   L�scht eine Gruppe mit allen bildern
   
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
	require(PLUGIN_PATH."/includes/classes/mysql.class.php");        # Zus�tzliche Plugin SQL Funktionen
	require(USER_PATH."/settings/plugins/pics/other.settings.php");    # Zus�tzliche Konfiguration laden
	
	//Templates laden
	$ground_tpl->load("ground");
	
	//Klasse einrichten f�r die Galerie
	$psql = new pics_mysql_class;
	$psql->prefix = $settings["mysql"]["table_prefix"];
	
	//Installationspr�fung
	if ( !$sql->install_check("pics") )
	{
		$sql->close();
		die("<b>Bildergalerie noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}
	
	/*--- Grundaktionen --- ende --- */
	
	
	
	/*--- L�schen --- start --- */
	
	//Alle bilder l�schen
	if ( $_GET['id'] )
	{
		//Alle Bilder laden
		$items = $psql->load_items($_GET['id']);
		
		//Sofern vorhanden, Bilder l�schen
		if ( $items[0]["id"] )
		{
			//Eintr�ge verarbeiten
			foreach ( $items as $item )
			{
				//Sofern vorhanden l�schen
				if ( file_exists($settings["pics"]["img_path"]."/".$item["id"]."b.jpg") && file_exists($settings["pics"]["img_path"]."/".$item["id"]."s.jpg") )
				{
					unlink($settings["pics"]["img_path"]."/".$item["id"]."b.jpg");
					unlink($settings["pics"]["img_path"]."/".$item["id"]."s.jpg");
					$psql->del_item($item["id"]);
				}
			}
		}
		
		//Gruppe l�schen
		$psql->del_group($_GET['id']);
		
		$content = message(PLUGIN_PATH,"pics","delgrp_true",$SID);
	}
	else
	{
		$content = message(PLUGIN_PATH,"pics","delgrp_false",$SID);
	}
	
	/*--- L�schen --- ende --- */
	
	
	
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