<?

/*
   BESCHREIBUNG:

   Zeigt die Menüs an um dort einen Menüpunkt zu verschieben.
   Ignoriert dabei das angegebene Menü welches ausgeschnitten wurde

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
	$tpl->path .= "move/";                                        # Template pfad (für den inhalt)
	$path    = REL_PATH;                                       # Pfad zu den einzubindenden Scripten

	//Variablen definieren
	if ( !preg_match("=^([0-9]*)$=",$_GET['id']) ) { $_GET['id'] = "0"; }
	if ( !$_GET['id'] ) { $_GET['id'] = "0"; }

	//Die obere ID um zurück zu gelangen
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

	//Passenden einfüge funktion setzen
	if ( !$_SESSION['cut_id'] )
	{
		$tpl->insertVar("insert_url","javascript:alert('Sie müssen erst einen Menüpunkt ausschneiden!');");
	}
	else $tpl->insertVar("insert_url","javascript:window.location='?action=move&init=insert&parent=".$_GET['id']."&SID=".$SID."'");
	
	//Menüs einfügen
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
		    //Menüpunkt ignorieren?
		    if ( $item["id"] <> $_SESSION['cut_id'] )
		    {
		        $replacements = array("path"  => $path,
	    		                      "SID"   => $SID,
	    							  "id"    => $item["id"],
	    							  "title" => base64_decode($item["title"]),
	    							  "url"   => ( $sql->redirect_check($item["id"]) ) ? "javascript:alert('Weiterleitung: Keine Unterpunkte möglich!')" : "?action=move&id=".$item["id"]."&SID=".$SID);

				//Wenn noch kein Menüpunkt ausgeschnitten den richtigen URL einsetzen
				if ( !$_SESSION['cut_id'] )
				{
				    $replacements["cut_url"] = "javascript:window.location='?action=move&init=cut&id=".$item["id"]."&SID=".$SID."'";
				}
				else $replacements["cut_url"] = "javascript:alert('Fügen Sie erst den alten Menüpunkt wieder ein, oder drücken Sie auf Abbrechen')";
	
	    	    $tpl->insertArray($replacements,array("items"));
		    }
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