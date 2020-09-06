<?

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
