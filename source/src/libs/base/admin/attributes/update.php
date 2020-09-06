<?

/*
   BESCHREIBUNG:

   Listet die Attribute der Menü einträge auf
   und ermöglicht es so diese zu verändern

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
	$path    = REL_PATH;                                       # Pfad zu den einzubindenden Scripten
	
	/*--- defaults --- ende --- */



	/*--- Ausgabe --- start --- */
	
	//Templates laden
	$ground_tpl->load("ground");

	if ( $_POST['items'] )
	{
		//Ausgabe
	    foreach ( $_POST['items'] as $id => $attribute )
		{
		    //Eigenschaft filtern
		    if ( $attribute["mode"] == "1" )
		    {
		        $is_active  = "1";
		        $is_visible = "1";
		    }
	
		    if ( $attribute["mode"] == "2" )
		    {
		        $is_active  = "1";
		        $is_visible = "0";
		    }
	    
		    if ( $attribute["mode"] == "0" )
		    {
		        $is_active  = "0";
		        $is_visible = "0";
		    }

			//Prio kontrolle auf gültigkeit
			if ( !preg_match("/^[0-9]*$/i",$attribute["priority"]) )
			{
				$attribute["priority"] = "0";
			}
	    
			//ID Kontrolle
			if ( preg_match("/^[0-9]{1,}$/i",$id) )
			{
			    //Speichern
			    $sql->update_attributes($id,$attribute["priority"],$is_visible,$is_active);
	    
			    //Nachricht
			    $text = message(BASE_PATH,"attributes","true",$SID);
			} else $text = message(BASE_PATH,"attributes","false",$SID);
		}
	}
	else $text = message(BASE_PATH,"attributes","false",$SID);

	$ground_tpl->insertVar("path",$path);
	$ground_tpl->insertVar("title",base64_decode($settings["base"]["title"]));
	$ground_tpl->insertVar("name",$settings["base"]["name"]);
	
	$ground_tpl->insertVar("text",$text);

	//Alles ausgeben
	$retVal = $ground_tpl->getResult();

	/*--- Ausgabe --- ende --- */



	/*--- abschluss --- start --- */

	//Verbindung beenden
	$sql->close();

	//Speicher freigeben
	$ground_tpl->clear();
	
	return $retVal;

	/*--- abschluss --- ende --- */
}

?>