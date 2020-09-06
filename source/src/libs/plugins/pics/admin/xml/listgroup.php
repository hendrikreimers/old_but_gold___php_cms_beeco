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
	require(BASE_PATH."/includes/functions/text.functions.php");  # Text Funktionen
	require(LIB_PATH."/plugins/pics/includes/classes/mysql.class.php");        # Zusätzliche Plugin SQL Funktionen
	require(USER_PATH."/settings/plugins/pics/other.settings.php"); # Zusätzliche Konfiguration

	//Templates laden
	$tpl->path = LIB_PATH."/plugins/pics/admin/xml/";
	$tpl->fsuffix = ".temp.xml";
	$tpl->load("listgroup",1);

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



	/*--- Bilder einfügen --- start --- */

	//Einträge laden
	$items = $psql->load_items($_GET['group'],$settings["pics"]["order_items"]);

	//In die Kopfzeile alles einfügen
	$tpl->insertVar("SID",$SID);
	$tpl->insertVar("group",$_GET['group']);
	$tpl->insertVar("path",$path);
	$tpl->insertVar("SID",$SID);

	//Einfügen
	if ( $items[0] )
	{
	    foreach ( $items as $item )
		{
			$item["size"]  = round(filesize($settings["pics"]["img_path"]."/".$item["id"]."b.jpg")/1024);		
			$item["title"] = utf8_encode(htmlentities($item["title"]));

			//URL einfügen (ggf. Rewrite Engine)
			if ( $settings["display"]["rewrite"] == "1" ) {
				$item["url"]   = htmlentities( (( substr(base64_decode($settings["base"]["url"]),strlen(base64_decode($settings["base"]["url"]))-1,1) == "/" ) ? base64_decode($settings["base"]["url"])."show/p/big".$item["id"].".jpg" : base64_decode($settings["base"]["url"])."/show/p/big".$item["id"].".jpg") ); 
				$item["small"] = htmlentities( (( substr(base64_decode($settings["base"]["url"]),strlen(base64_decode($settings["base"]["url"]))-1,1) == "/" ) ? base64_decode($settings["base"]["url"])."show/p/small".$item["id"].".jpg" : base64_decode($settings["base"]["url"])."/show/p/small".$item["id"].".jpg") );
			} else {
				$item["url"]   = htmlentities( (( substr(base64_decode($settings["base"]["url"]),strlen(base64_decode($settings["base"]["url"]))-1,1) == "/" ) ? base64_decode($settings["base"]["url"])."?dad=pics&init=loadimg&size=b&pid=".$item["id"] : base64_decode($settings["base"]["url"])."/?dad=pics&init=loadimg&size=b&pid=".$item["id"]) ); 
				$item["small"] = htmlentities( (( substr(base64_decode($settings["base"]["url"]),strlen(base64_decode($settings["base"]["url"]))-1,1) == "/" ) ? base64_decode($settings["base"]["url"])."?dad=pics&init=loadimg&size=s&pid=".$item["id"] : base64_decode($settings["base"]["url"])."/?dad=pics&init=loadimg&size=s&pid=".$item["id"]) );
			}

			$tpl->insertArray($item,array("entries"));
		}
	}

	/*--- Bilder einfügen --- ende --- */



	/*--- abschluss --- start --- */
		
	//Ausgabe
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