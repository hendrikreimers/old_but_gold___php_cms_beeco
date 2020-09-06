<?

/*
   BESCHREIBUNG:
   
   Enthaelt Funktionen fuer die Text Darstellung
   Des weiteren auch die Funktion um die Menues darzustellen
   Haben ja auch irgendwie was mit Text zu tun ;-)
   
*/

//Wandelt Umlaute um
function umlEncode($text) {
	//Umlaute ändern
	$text = str_replace("ä","&auml;",$text);
	$text = str_replace("Ä","&Auml;",$text);
	$text = str_replace("ö","&ouml;",$text);
	$text = str_replace("Ö","&Ouml;",$text);
	$text = str_replace("ü","&uuml;",$text);
	$text = str_replace("Ü","&Uuml;",$text);

	return $text;
}

//Umlaute fuer die Rewrite Engine umschreiben
function umlRewEncode($text) {
	//Umlaute ändern
	$text = str_replace("ä","ae",$text);
	$text = str_replace("Ä","Ae",$text);
	$text = str_replace("ö","oe",$text);
	$text = str_replace("Ö","Oe",$text);
	$text = str_replace("ü","ue",$text);
	$text = str_replace("Ü","Ue",$text);

	$text = str_replace("&auml;","ae",$text);
	$text = str_replace("&Auml;","Ae",$text);
	$text = str_replace("&ouml;","oe",$text);
	$text = str_replace("&Ouml;","Oe",$text);
	$text = str_replace("&uuml;","ue",$text);
	$text = str_replace("&Uuml;","Ue",$text);

	$text = str_replace(" ","_",strip_tags($text));

	return $text;
}



//Wandelt je Nach Einstellungen die Zeilenumbrueche im Text um
function auto_nl2br($text,$modus,$filter)
{
    //Bei Bedarf den Zeilenumbruch in BR Tags umwandeln oder automatik
    if ( $modus == "1" )
    {
        $text = str_replace("\r","",$text);
        $text = str_replace("\n","<br />\r\n",$text);
    }

	if ( $modus == "2" )
    {
        //Kontrolle ob HTML Tags gefunden werden, wenn nicht Zeilenumbruch
        if ( !preg_match_all("/<(.*[^>])>/siU",$text,$matches) )
        {
            $text = str_replace("\r","",$text);
            $text = str_replace("\n","<br />\r\n",$text);
        }

        //Wenn doch gucken ob nur die erlaubten vorkommen, dann zeilenumbruch
        else
        {
            //Filter vorbereiten
            $filters = Explode(",",strtolower($filter)); # Erlaubte HTML Tags
            $found   = 0;                    # Variable zur Kontrolle ob nicht erlaubte Tags enthalten sind

            //Alle HTML Tags abarbeiten
            foreach ( $matches[1] as $tmp )
            {
                //Parameter herausfiltern, so dass nur der Tag übrig bleibt
                preg_match("/^([a-z0-9\/]*[^ ])(.*)$/i",$tmp,$match);

                //Filter vergleichen mit Tag
                foreach ( $filters as $filter )
                {
                    //Ergebnis speichern
                    $tmpresult[] = ( ($filter != strtolower($match[1])) && ("/".$filter != strtolower($match[1])) ) ? "0" : "1";
                }

                //Alle Ergebnisse zusammenfügen und wenn in allen nichts gefunden wurde ist ein ungültiger Tag enthalten
                if ( Implode("",$tmpresult) == 0 )
                {
                    $found = 1;
                }

                //Speicher freigeben
                unset($tmpresult);
            }

            //Wenn alles OK ist Zeilenumbruch!
            if ( $found == 0 )
            {
                $text = str_replace("\r","",$text);
                $text = str_replace("\n","<br />\r\n",$text);
            }
        }
    }

    //Ergebnis zurückliefern
    return $text;
}

//DirectADD (einfügen von plugins direct in den text)
function directadd($content,$path = ".",$nl2br_modus,$tagfilter,$settings)
{
	//Alle DirectADD Positionen finden
	preg_match_all("/{([a-z]*):([a-z0-9\,\-\+]*)}/siU",$content,$matches);

	if ( $matches )
	{
		//Ergebnisse verarbeiten
		for ( $i = 0; $i < sizeof($matches[1]); $i++ )
		{
			if ( file_exists($path."/plugins/".$matches[1][$i]."/includes/settings/directadd.settings.php") )
			{
			    //DirectADD Funktion einbinden
			    require($path."/plugins/".$matches[1][$i]."/includes/settings/directadd.settings.php");
		
			    //DirectADD Funktion erstellen
			    $directadd_func = create_function('$params,$path = "./",$nl2br_modus,$tagfilter,$settings = array()',$directadd_code);

			    //Aufruf
			    $content = str_replace("{".$matches[1][$i].":".$matches[2][$i]."}",$directadd_func($matches[2][$i],$path."/",$nl2br_modus,base64_decode($tagfilter),$settings),$content);
		
			    //Speicher freigeben
			    unset($directadd_code);
			    unset($directadd_func);
			}
			else
			{
			    $content = str_replace("{".$matches[1][$i].":".$matches[2][$i]."}","",$content);
			}
		}
	}
	
	return stripslashes($content);
}

//Schützt mehr oder weniger eine E-Mail Adresse indem diese in ASCII Zeichen umgewandelt wird
function modifyStr($mail)
{
	$retval = "";

	for ( $i = 0; $i < strlen($mail); $i++ )
	{
		$retval .= '&#'.ord($mail[$i]).';';
	}

	return $retval;
}

//Findet jede E-Mail Adresse im Text und modifiziert sie als ASCII
function findAndModifyMail($text)
{
	//Regulärer Ausdruck für E-Mail Adressen
	$regexp = '[a-z0-9.!?#$&%*+-/\=~^_`\'\|{}]{1,64}@[a-z0-9-.]{4,255}';

	//Alle MAILTOs umwandeln
	$mod = modifyStr("mailto:");
	$text = str_replace("mailto:",$mod,$text);

	//E-Mail Adressen
	if ( preg_match_all("=".$regexp."=si",$text,$matches) ) {
		foreach ( $matches[0] as $match )
		{
			$mod = modifyStr($match);
			$text = str_replace($match,$mod,$text);
		}
	}

	return $text;
}

//Wandelt jeden noch nicht verlinkten Verweis in einen Link um
function findAndModifyLinks($text) {
	//Alle klar definierten Seiten umwandeln (außer http://)
	$regexp = '=((<a .*</a>)|ftp://|mailto:|news:)([a-z0-9.-]*)(/[a-z0-9.!?#$&%*+-/\=~^_`\'|{}]*|)=smei';
	$text = stripslashes(preg_replace($regexp,'"\2"=="\1" ? "\1" : "<a href=\"\1\3\4\" onclick=\"window.open(this.href); return false;\">\1\3".(substr("\4",0,1) == "/" ? "/..." : "")."</a>"',$text));

	//Alle Webseiten mit/ohne http:// umwandeln
	$regexp = '=((<(a|input|textarea|iframe) .*(</a>|>|</textarea>))|www\.|http://)([a-z0-9.-]*)(/[a-z0-9.!?#$&%*+-/\=~^_`\'|{}]*|)=ei';
	$text = stripslashes(preg_replace($regexp,'"\2" == "\1" ? "\1" : "<a href=\"".( "\1" == "http://" ? "\1\3\5\6" : "http://\1\3\5\6")."\" onclick=\"window.open(this.href); return false;\">\1\3\5".(substr("\6",0,1) == "/" ? "/..." : "")."</a>"',$text));

	//Alle E-Mail Adressen ohne mailto: umwandeln
	$regexp = '=((<(a|input|textarea|iframe) .*(</a>|>|</textarea>))|[a-z0-9.!?#$&%*+-/\=~^_`\'|{}]{1,64}@)([a-z0-9\.-]*)=ei';
	$text = stripslashes(preg_replace($regexp,'"\2"=="\1" ? "\1" : "<a href=\"mailto:\1\3\5\">\1\3\5</a>"',$text));

	return $text;
}

//Fügt nach dem Body Tag definierten Text ein
function insertAfterBody($text,$insert) {
	return preg_replace("/(<body[^>]*>)/siU","$1".$insert,$text);
}

?>