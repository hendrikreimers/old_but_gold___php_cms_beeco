<?

/*
   BESCHREIBUNG:
   
   F�gt eine neue Gruppe hinzu
   
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



	/*--- Gruppe hinzuf�gen --- start --- */

	//Nur g�ltigen Eintrag hinzuf�gen
	if ( $_POST['groupname'] )
	{
	    //Hinzuf�gen
	    $psql->add_group($_POST['groupname'],$_POST['desc']);
    
	    //Meldung
	    $content = message(PLUGIN_PATH,"pics","true",$SID);
	}
	else
	{
 	   //Fehlermeldung
 	   $content = message(PLUGIN_PATH,"pics","false",$SID);
	}

	/*--- Gruppe hinzuf�gen --- ende --- */



	/*--- abschluss --- start --- */

	//Ausgabe
	$ground_tpl->insertVar("text",$content);
	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title","Bildergalerie");
	$ground_tpl->insertVar("name","pics");
	$retVal = $ground_tpl->getResult();

	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$ground_tpl->clear();

	return $retVal;

	/*--- abschluss --- ende --- */
}

?>