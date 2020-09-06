<?

/*
   BESCHREIBUNG:

   Zeigt die Men�s an um dort einen Men�punkt
   einf�gen zu k�nnen.

*/

function initialize($dObj)
{
	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];

	/*--- defaults --- start --- */

	//Standard Aktionen laden
	$tpl->path .= "add/";   # Template pfad (f�r den inhalt)
	$path       = REL_PATH; # Pfad zu den einzubindenden Scripten

	//Variablen definieren
	if ( !preg_match("=^([0-9]*)$=",$_GET['id']) ) { $_GET['id'] = "0"; }
	if ( !preg_match("/^[0-9]*$/i",$_GET['parent']) ) { $_GET['parent'] = "0"; }
	if ( !preg_match("/^[0-9]*$/i",$_POST['parent']) ) { $_POST['parent'] = "0"; }
	if ( !$_GET['id'] ) { $_GET['id'] = "0"; }

	//Die obere ID um zur�ck zu gelangen
	$back_parent = ( $_GET['id'] > 0 ) ? $sql->get_parent($_GET['id']) : "0";

	/*--- defaults --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");
	$tpl->load("index",1);

	//Standard Informationen einsetzen
	$tpl->insertVar("path",$path);
	$tpl->insertVar("SID",$SID);
	$tpl->insertVar("parent",$_GET['id']);
	$tpl->insertVar("back_parent",$back_parent);

	//Men�s einf�gen
	$items = $sql->load_menue($_GET['id'],"1");

	//Sortieren
	$deep[0] = $items;
	$deep = sort_menues($deep);
	$items   = $deep[0];
	unset($deep);

	if ( $items )
	{
		//Ausgabe
		foreach ( $items as $item )
		{
		    $replacements = array("path"  => $path,
			                      "SID"   => $SID,
								  "id"    => $item["id"],
								  "title" => base64_decode($item["title"]),
								  "url"   => ( $sql->redirect_check($item["id"]) ) ? "javascript:alert('Weiterleitung: Keine Unterpunkte m�glich!')" : "?id=".$item["id"]."&SID=".$SID."&action=add");
	
		    $tpl->insertArray($replacements,array("items"));
		}
	}

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["base"]["title"]));
	$ground_tpl->insertVar("name",$settings["base"]["name"]);

	$ground_tpl->insertVar("text",$tpl->getResult());

	//Alles ausgeben
	$retVal = $ground_tpl->getResult();

	/*--- Ausgabe --- ende --- */



	/*--- abschluss --- start --- */
	
	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$ground_tpl->clear();
	$tpl->clear();
	
	return $retVal;

	/*--- abschluss --- ende --- */
}

?>
