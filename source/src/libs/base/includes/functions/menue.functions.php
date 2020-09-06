<?

function insert_menueArray($settings,$tpl,$objects,$preview_modus,$curTpl,$deep = array()) {

	while ( list($layerName,$layerNodes) = each($curTpl) ) {
		
		if ( preg_match("=^menue\[([0-9]{1,})(\+?)\]$=i",$layerName,$matches) ) {
			
			$layerKey = $matches[1];
			$isDynamic = ( $matches[2] == "+" ) ? true : false;

			// Aktuelle Speichertiefe ermitteln
			$curDeep = array();
			if ( sizeof($deep) > 0 ) {
				foreach ( $deep as $dp ) {
					$curDeep[] = $dp['layerName'];
					$curDeep[] = 'item';
				}
			}

			$curDeep[] = $layerName;
			$curDeep[] = 'item';

			// Aktuelle anzuzeigende Ebene auswählen anhand der Dynamischen oder Statischen Menüs
			$deepPoint = ($isDynamic === false) ? $layerKey : ((sizeof($objects)-1 >= $layerKey) ? sizeof($objects)-1 : -1);

			for ( $i = 0; $i < sizeof($objects[$deepPoint]); $i++ ) {

				$item = &$objects[$deepPoint][$i];

				if ( ($item['parent_id'] == $deep[count($deep)-1]['parent_id']) || (sizeof($deep) <= 0) ) {

					$hasParents = true;

					$replacements = array("title" => stripslashes(base64_decode($item["title"])),
										  "id"    => $item["id"],
										  "path"  => REL_USER_PATH,
										  "url"   => (( $preview_modus == "0" ) ? (($item["is_manual"] == "1") ? stripslashes(base64_decode($item["url"])) : REL_PATH."/index.php?id=".$item["id"]) : "#"));

					if ( $settings["base"]["template_override"] == "1" ) {
						$replacements["url"] .= "&style=".$_GET['style'];
					}

					//Rewrite Engine (URL anpassen)
					if ( ($settings['display']['rewrite'] == "1") && ($item["is_manual"] != "1") ) {
						if ( $preview_modus == "0" ) {
							$replacements["url"] = REL_PATH."/";
							$replacements["url"] .= strtolower(umlRewEncode(trim($replacements["title"]))).".html";
						} else $replacements["url"] = "#";
					}

					$tpl->insertArray($replacements,$curDeep);

					if ( (sizeof($layerNodes['children']['item']['children']) > 0) && (sizeof($objects[$layerKey+1]) > 0) ) {
						$deep[] = array(
					                	'layerName'  => $layerName,
										'parent_id' => $item['id']
										);

						insert_menueArray($settings,$tpl,$objects,$preview_modus,$layerNodes['children']['item']['children'],$deep);
					}

					unset($deep);
				} else $hasParents = false;
			}
			
			if ( $hasParents == true ) {
				$tpl->insertVar("path",REL_USER_PATH,array_slice($curDeep,0,count($curDeep)-1));
			}
		}

	}
}

function insert_menue($settings,$tpl,$objects,$positions,$preview_modus = "0") {

	// Prüfen ob Template korrekt
	if ( sizeof($tpl->template['MAIN']['children']) <= 0 ) {
		return false;
	}
	
	// Prüfen ob Items verfügbar
	if ( sizeof($objects) <= 0 ) {
		return false;
	}

	insert_menueArray($settings,$tpl,$objects,$preview_modus,$tpl->template['MAIN']['children']);

	// ------------------------------------------------------------------------------------------------------------------------------
	// Die aktuelle Position einfügen
	if ( $positions ) {

		// Alle Menuetiefen abarbeiten und ausgeben
		foreach ( $positions as $position ) {

			// Abschnittsvariablen vorbereiten
			$replacements = array("title" => stripslashes(base64_decode($position["title"])),
								  "id"    => $position["id"],
								  "path"  => REL_USER_PATH,
								  "url"   => (( $preview_modus == "0" ) ? REL_PATH."/index.php?id=".$position["id"] : "#"));

			if ( $settings['display']['rewrite'] == "1" ) {
				if ( $preview_modus == "0" ) {
					$replacements["url"] = REL_PATH."/";
					$replacements["url"] .= strtolower(umlRewEncode(trim($replacements["title"]))).".html";
				} else $replacements["url"] = "#";
			}

			// Einsetzen
			$tpl->insertArray($replacements,array("position"));

		} # END OF: Alle Menuetiefen abgarbeiten und ausgeben

	} # END OF: Die Aktuelle Position einfügen

}

// ------------------------------------------------------------------------------------------------------------------------------------------------------------

//Sortieren von Menüpunkten
function sort_menues($items)
{
	//Benötigte Variable initialisieren
	$tmp2_items = array();

    if ( $items[0] )
	{
		//Vergleichsfunktion
	    $cmp = create_function('$a,$b','if (strtolower(base64_decode($a["title"])) == strtolower(base64_decode($b["title"]))) return 0; return (strtolower(base64_decode($a["title"])) > strtolower(base64_decode($b["title"]))) ? -1 : 1;');

		//Alle Elemente abarbeiten
	    foreach ( $items as $deep => $items )
	    {
			if ( $items[0] ) {
		        foreach ( $items as $item )
		        {
		            $tmp[$item["priority"]][] = $item;
		        }

				//Array neu initailisieren
				$tmp2_items = array();

		        foreach ( $tmp as $tmp_items )
		        {
		            //Array sortieren
		            usort($tmp_items,$cmp);
		            $tmp2_items = array_merge($tmp2_items,array_reverse($tmp_items));
		        }
			}

	        $retval[$deep] = $tmp2_items;
	        unset($tmp);
	        unset($tmp2_items);
	    }
	}

	return $retval;
}

?>
