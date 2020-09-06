<?

/*
   BESCHREIBUNG:
   
   Zeigt die Menüpunkte an und den inhalt der vom plugin geladen wird
   
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
	require(BASE_PATH."/includes/functions/text.functions.php");  # Text Funktionen

	//Templates laden
	$tpl->path = PLUGIN_PATH."/templates/add/";
	$tpl->load("index");
	$ground_tpl->load("ground");

	//Installationsprüfung
	if ( !$sql->install_check("news") )
	{
		$sql->close();
		die("<b>News noch nicht installiert!</b><br>Lesen Sie dazu die Anleitung!<br>");
	}

	/*--- Grundaktionen --- ende --- */



	/*--- hinzufügen --- start --- */

	$status = "";

	//Wurde auf hinzufügen gedrückt?
	if ( $_POST["SID"] )
	{
		//Sind die benötigten felder ausgefüllt?
		if ( ($_POST["title"]) && ($_POST["text"]) )
		{
			//Zeitformatierungen prüfen
			$date_y = ( preg_match("/^[0-9]{4}$/i",$_POST['date_y']) ) ? $_POST['date_y'] : date("Y");
			$date_d = ( preg_match("/^[0-9]{2}$/i",$_POST['date_d']) ) ? $_POST['date_d'] : date("d");
			$date_m = ( preg_match("/^[0-9]{2}$/i",$_POST['date_m']) ) ? $_POST['date_m'] : date("m");
			$time_m = ( preg_match("/^[0-9]{2}$/i",$_POST['time_m']) ) ? $_POST['time_m'] : date("i");
			$time_h = ( preg_match("/^[0-9]{2}$/i",$_POST['time_h']) ) ? $_POST['time_h'] : date("h");
	
			//Daten prüfen und verschlüsseln
			$title = base64_encode($_POST['title']);
			$date  = $date_y."-".$date_m."-".$date_d;
			$time  = $time_h.":".$time_m.":00";
			$text  = base64_encode($_POST['text']);
		
			//Hinzufügen
			require(PLUGIN_PATH."/includes/classes/mysql.class.php"); # Neue MySQL Klasse
			$nsql = new news_mysql_class;                      # Objekt erstellen
			$nsql->prefix = $settings["mysql"]["table_prefix"];
			$nsql->add_entry($date,$time,$title,$text);
			unset($nsql);
		
			$tpl->insertVar("date_y",date("Y"));
			$tpl->insertVar("date_m",date("m"));
			$tpl->insertVar("date_d",date("d"));
			$tpl->insertVar("time_m",date("i"));
			$tpl->insertVar("time_h",date("H"));
			$status = message(PLUGIN_PATH,"news","true");
			unset($_POST['text']);
		}
		else
		{
			//Felder wieder befüllen
			$tpl->insertVar("title",$_POST['title']);
			$tpl->insertVar("date_y",$_POST['date_y']);
			$tpl->insertVar("date_m",$_POST['date_m']);
			$tpl->insertVar("date_d",$_POST['date_d']);
			$tpl->insertVar("time_h",$_POST['time_h']);
			$tpl->insertVar("time_m",$_POST['time_m']);
			
			$status = message(PLUGIN_PATH,"news","false");	
		}
	}
	else
	{
		$tpl->insertVar("date_y",date("Y"));
		$tpl->insertVar("date_m",date("m"));
		$tpl->insertVar("date_d",date("d"));
		$tpl->insertVar("time_m",date("i"));
		$tpl->insertVar("time_h",date("H"));
	}

	/*--- hinzufügen --- ende --- */



	/*--- abschluss --- start --- */

	//Plugin Template für Ausgabe bereit machen
	$tpl->insertVar("path",REL_PATH);
	$tpl->insertVar("SID",$SID);
	
	$tpl->insertVar("content",$_POST['text']);	
	$content = $status.$tpl->getResult();

	//Ausgabe
	$ground_tpl->insertVar("text",$content);
	$ground_tpl->insertVar("path",REL_PATH);
	$ground_tpl->insertVar("title","News");
	$ground_tpl->insertVar("name","news");
	$ground_tpl->insertVar("SID",$SID);
	
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