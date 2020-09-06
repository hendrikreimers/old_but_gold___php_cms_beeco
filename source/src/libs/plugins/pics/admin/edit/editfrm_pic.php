<?

/*
   BESCHREIBUNG:

   Zeigt das Best�tigungsformular zum l�schen an

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
	
	//Templates laden
	$tpl->path = PLUGIN_PATH."/templates/edit/";
	$tpl->load("editfrm_pic",1);
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
	
	
	
	/*--- INHALT F�LLEN --- START --- */
	
	$tpl->insertVar("id",$_GET['id']);
	$tpl->insertVar("group",$_GET['group']);
	
	$item = $psql->load_item($_GET['id']);
	
	if ( $item["id"] )
	{
		$tpl->insertVar("title",$item["title"]);
		$tpl->insertVar("desc",$item["desc"]);
		
		$groups = $psql->load_groups($settings["pics"]["order"]);
		
		if ( $groups )
		{
			foreach ( $groups as $group )
			{
				$group["title"]    = $group["title"];
				$group["selected"] = ( $group["id"] == $_GET['group'] ) ? " selected" : "";
				$tpl->insertArray($group,array("group"));
			}
		}
	}
	
	/*--- INHALT F�LLEN --- ende --- */
	
	
	
	/*--- abschluss --- start --- */
	
	//Plugin Template f�r Ausgabe bereit machen
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