<?

/*
   BESCHREIBUNG:

   Zeigt die Men�s an um dort einen Men�punkt zu verschieben.
   Ignoriert dabei das angegebene Men� welches ausgeschnitten wurde

*/

/*--- defaults --- start --- */

function initialize($dObj)
{
	//Einstellungen usw wiederherstellen
	$ground_tpl = &$dObj["ground"];
	$tpl        = &$dObj["tpl"];
	$sql        = &$dObj["sql"];
	$settings   = &$dObj["settings"];
	$SID        = &$dObj["SID"];

	//Standard Aktionen laden
	$tpl->path .= "pluginlist/";                                 # Template pfad (f�r den inhalt)
	$path    = REL_PATH;                                      # Pfad zu den einzubindenden Scripten

	/*--- defaults --- ende --- */



	/*--- Ausgabe --- start --- */

	//Templates laden
	$ground_tpl->load("ground");
	$tpl->load("index",1);

	//Standard Informationen einsetzen
	$tpl->insertVar("path",$path);
	$tpl->insertVar("SID",$SID);
	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["base"]["title"]));
	$ground_tpl->insertVar("name",$settings["base"]["name"]);

	//Verzeichniss auslesen und Installation pr�fen
	$path = USER_PATH."/templates/plugins";
	$dir  = dir($path);

	while ( $entry = $dir->read() )
	{
	    //Nur Ordner
	    if ( is_dir($path."/".$entry) )
	    {
	        //Nur g�ltige
	        if ( ($entry != ".") && ($entry != "..") )
	        {
	            //Installation pr�fen
	            if ( $sql->install_check(strtolower($entry)) )
	            {
	                //Werte einf�gen
	                $replacement = array("title" => base64_decode($sql->install_check(strtolower($entry))),
	                                     "url"   => "?SID=".$SID."&action=main&plugin=".$entry);
	
	                //Ausgabe als installiert
	                $tpl->insertArray($replacement,array("installed"));
	            }
	            else
	            {
	                //Ausgabe als nicht installiert
	                $replacement = array("title" => $entry,
	                                     "url"   => "?SID=".$SID."&action=install&plugin=".$entry);
	
	                $tpl->insertArray($replacement,array("notinstalled"));
	            }
	        }
	    }
	}
	
	$dir->Close();

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