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
	require(BASE_PATH."/includes/functions/text.functions.php");         # Text Funktionen
	require(LIB_PATH."/plugins/pics/includes/classes/mysql.class.php"); # Zus�tzliche Plugin SQL Funktionen

	//Templates laden
	$tpl->path = LIB_PATH."/plugins/pics/admin/xml/";
	$tpl->fsuffix = ".temp.xml";
	$tpl->load("index",1);

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



	/*--- Gruppen einf�gen --- start --- */

	//Gruppen laden
	$groups = $psql->load_groups($settings["pics"]["order_groups"]);

	if ( $groups[0] )
	{
	    foreach ( $groups as $group )
	    {
			if ( $group["mode"] != "2" )
			{
		   	    $group["title"] = utf8_encode(strip_tags($group["title"]));
			    $tpl->insertArray($group,array("group"));
			}
    	}
	}

	/*--- Gruppen einf�gen --- ende --- */



	/*--- abschluss --- start --- */
	//Ausgabe
	$tpl->insertVar("SID",$SID);
	$tpl->insertVar("path",$path);

	header("Content-Type: application/xml");
	$retVal = $tpl->getResult();

	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$tpl->clear();
	$ground_tpl->clear();

	return $retVal;

	/*--- abschluss --- ende --- */
}

?>