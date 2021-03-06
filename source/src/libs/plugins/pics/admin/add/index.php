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
	$path    = REL_PATH;                                 # Pfad zu den einzubindenden Scripten
	require(BASE_PATH."/includes/functions/text.functions.php");  # Text Funktionen
	require(PLUGIN_PATH."/includes/classes/mysql.class.php");        # Zus�tzliche Plugin SQL Funktionen

	//Templates laden
	$tpl->path = PLUGIN_PATH."/templates/add/";
	$tpl->load("index",1);
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



	/*--- Gruppen einf�gen --- start --- */

	//Gruppen laden
	$groups = $psql->load_groups($settings["pics"]["order_groups"]);

	if ( $groups[0] )
	{
	    foreach ( $groups as $group )
	    {
	        $group["url"] = "?action=add&init=addpicfrm&plugin=pics&group=".$group["id"]."&SID=".$SID;
    	    $tpl->insertArray($group,array("group"));
	    }
	}

	/*--- Gruppen einf�gen --- ende --- */



	/*--- abschluss --- start --- */

	//Plugin Template f�r Ausgabe bereit machen
	$tpl->insertVar("SID",$SID);
	$content = $tpl->getResult();

	//Ausgabe
	$ground_tpl->insertVar("text",$content);
	$ground_tpl->insertVar("path",$path);
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