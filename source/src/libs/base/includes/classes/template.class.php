<?

/* www.kern23.de Project */

/*
	TEMPLATE KLASSE v3.0
	von Hendrik Reimers
	
	Internet: www.kern23.de
	E-Mail    info@kern23.de
*/

class template_class
{
    /* --- PUBLIC SETTINGS --- BEGIN --- */
    
	//Datei Einstellungen
	var $fsuffix  = ".temp.html";   # Dateiendung (Standard: ".temp.html")
	var $path     = "./templates/"; # Template Pfad (Standard: "./templates/")
	var $fpBuffer = 2048;           # Puffergr��e f�r Datei Aktionen (Standard: 2048)
	
	//Erlaubte Zeichen der Bereichsmarkierungen (Standard: "\[\]\+a-z0-9_-")
	var $allowed_chars = "\[\]\+a-z0-9_-";
	
	//Aussehen der Elemente der Bereichsmarkierungen (Standard: "<!--{platz}--> ... <!--{/platz}-->)
	var $sector_suffix = array( "start" => array(0 => "<!--{", 1 => "}-->"),
	                            "end"   => array(0 => "<!--{/", 1 => "}-->") );
	
	//Aussehen der Elemente der Variablen (Standard: "%variable%")
	var $var_suffix = array( "start" => "%",
							 "end"   => "%" );
							 
	//Marker Elemente, um die Position beim zusammenf�gen wieder zu finden
	var $marker_suffix = array( "start" => "#####",
								"end"   => "#####" );
				 
    /* --- PUBLIC SETTINGS --- END --- */
    
    
    
    /* --- PRIVATE VARIABLES --- BEGIN --- */
    
	//Speicher Variablen
	var $template; # Das Template
	var $result;   # Ergebnisspeicher
	    
    /* --- PRIVATE VARIABLES --- END --- */
    
    
    
    /* --- PUBLIC FUNCTIONS --- END --- */
    
	//Setzt alle Einstellungen auf Standard zur�ck
	//ACHTUNG: L�scht auch den Speicher!!!
	// Aufruf: setDefaults();
	
	function setDefaults() {
	
    	    //Datei Einstellungen
	    $this->fsuffix  = '.temp.html';   # Dateiendung (Standard: ".temp.html")
	    $this->path     = './templates/'; # Template Pfad (Standard: "./templates/")
	    $this->fpBuffer = 2048;           # Puffergr��e f�r Datei Aktionen (Standard: 2048)
	
	    //Erlaubte Zeichen der Bereichsmarkierungen (Standard: "\[\]\+a-z0-9_-")
	    $this->allowed_chars = '\[\]\+a-z0-9_-';
	
	    //Aussehen der Elemente der Bereichsmarkierungen (Standard: "<!--{platz}--> ... <!--{/platz}-->)
	    $this->sector_suffix = array( "start" => array(0 => '<!--{", 1 => "}-->'),
	                        	 	  "end"   => array(0 => '<!--{\/", 1 => "}-->') );
	
	    //Aussehen der Elemente der Variablen (Standard: "%variable%")
	    $this->var_suffix = array( "start" => '%',
				       			   "end"   => '%' );
				       			   
		//Marker Elemente, um die Position beim zusammenf�gen wieder zu finden
		$this->marker_suffix = array( "start" => "#####",
									  "end"   => "#####" );
				       
	    //Speicher freigeben
	    $this->___clearBuffer();
	    
	    //Abschlie�en
	    return true;
	}
    
    
	//L�dt ein Template in den Buffer
	// Aufruf: load("myTemplate");
	// 	L�dt ein Template in den Buffer
	// Alternativ: load("myTemplate",1);
	//	L�dt ein Template und trennt es nach den Bereichen auf
	
	function load($template,$explode = 0) {
	
	    //Dateipfad zusammenstellen
	    $file = $this->path.$template.$this->fsuffix;
	    
	    //Datei existenz pr�fen
	    if ( (!file_exists($file)) && (!is_readable($file)) ) {
			return false;
	    }
	    
	    //Datei �ffnen
	    $fp = fopen($file,"r");
	    
	    //Einlesen
	    if ( $explode === 0 ) {
			$this->___loadFile($fp);
	    }
	    
	    if ( $explode === 1 ) {
			$this->___loadFileParts($fp);
	    }
	    
	    //Datei schlie�en
	    fclose($fp);
	    
	    //Abschlie�en
	    return true;
	}
	
	
	//Gibt das Ergebnis an den Benutzer zur�ck
	// Aufruf: getResult();
	
	function getResult() {
	
	    //Ist Ergebnis Variable oder Array mit Begrenzungen
	    if ( is_array($this->template) ) {
			//Ergebnis zusammenf�gen und liefern
			$this->result = $this->template["MAIN"]["content"];        # Ergebnisspeicher f�llen
			$this->___impResult($this->template["MAIN"]["children"]);  # Buffer zusammenfassen
	    } else {
			//Ergebnis liefern
			$this->result = $this->___Result();
	    }

		//Abschlie�en
		$this->___clearResult(); # Unn�tige Zeichen entfernen
		return $this->result;    # Ergebnis liefern
	}
	
	
	//Gibt den Speicher wieder frei
	// Aufruf: clear();
	
	function clear() {
	
	    //Speicher freigeben
	    return $this->___clearBuffer();
	}
	
	
	//F�gt eine Variable in einen Bereich ein
	//Wenn kein Bereich angegeben landet es in dem nicht definiertem Bereich
	//Durch Angaben von den unter BEreichen landet es in dem untersten Bereich
	//�hnlich wie bei einem Array $myAr[part][subpart][subpart]...
	// Aufruf: insert("templateVariable",$yourVar,[$part],[$subPart],[$subPart],[...]);
	
	function insertVar($tplVar,$var) {
		
		//�bergebene Argumente filtern
		$args   = func_get_args();
	    $args   = array_slice($args,2,sizeof($args));

	    //Funktionsaufruf
	    $this->___insert(array($tplVar => $var),$args[0]);
	    
	    //Abschlie�en
	    return true;
	}
	
	
	//F�gt mehrere Variablen in einem Bereich ein in Form eines Arrays
	//Das Array sollte wie folgt in etwa aussehen: $myAr["var"] = "wert", usw...
	//Der KEY des Arrays wird als Platzhalter gleichgestellt mit dem Template Platzhalter
	// Aufruf: insertArray($myAr,[$part],[$subPart],[$subPart],[...]);
	
	function insertArray($tplAr) {
		
		//�bergebene Argumente filtern
	    $args   = func_get_args();
	    $args   = array_slice($args,1,sizeof($args));
	    
	    //Funktionsaufruf
	    $this->___insert($tplAr,$args[0]);
	    
	    //Abschlie�en
	    return true;
	}
    
    /* --- PUBLIC FUNCTIONS --- END --- */
    
    
    
    /* --- PRIVATE FUNCTIONS --- BEGIN --- */
    
	//Lie�t eine Datei in den Speicher ($this->template)
	// Aufruf: $this->___loadFile($fp);
	
	function ___loadFile($fp) {
	
	    //Speicher leeren
	    $this->___clearBuffer();
	
	    //Einlesen
	    while ( $line = fgets($fp,$this->fpBuffer) ) {
			$this->template .= $line;
	    }

	    //Abschlie�en
	    return true;
	}
	
	
	//Lie�t eine Datei und trennt Sie auf, sofern m�glich
	// Aufruf: $this->___loadFileParts($fp);
	
	function ___loadFileParts($fp) {
	
	    //Speicher leeren
	    $this->___clearBuffer();

		//Aussehen der Markierungen festlegen
		$sectorStart  = $this->sector_suffix["start"][0]."([".$this->allowed_chars."]*)".$this->sector_suffix["start"][1];
		$sectorEnd    = $this->sector_suffix["end"][0]."([".$this->allowed_chars."]*)".$this->sector_suffix["end"][1];
	    
	    //Regul�re Ausdr�cke
	    $regexpStart  = "=(.*?)".$sectorStart."(.*?)=siU";                        # Anfang eines Bereiches
	    $regexpEnd    = "=(.*?)".$sectorEnd."(.*?)=siU";                          # Ende eines Bereiches
		$regexpMiddle = "=(.*?)(".$sectorStart.")(.*?)(".$sectorEnd.")(.*?)=siU"; # Anfang und Ende eines Bereiches in einer Zeile

	    //Ergebnis initialisieren
	    $this->templates = array(); # Elemente
	    $stack           = array(); # Zwischenspeicher
	    
	    //Hauptelement festlegen
	    $this->template["MAIN"] = array( "content"  => "",
					    				 "marker"   => array( "begin" => "", "end" => "" ),
					     				 "children" => array(),
										 "buffer"   => array() );                 # Element aussehen
					     
	    $stack[count($stack)] = &$this->template;                                 # Zwischenspeicher
	    $elements             = &$this->template["MAIN"];                         # Referenzierung
	    
	    //Einlesen
	    while ( $line = fgets($fp,$this->fpBuffer) ) {
			//Gucken ob ein Markierungsanfang zu finden ist
			if ( preg_match($regexpStart,$line,$match) ) {
				//Text vor dem Marker noch speichern
				$elements["content"] .= $match[1];

				//Marker setzen um die Position beim zusammenf�gen wieder zu finden
				$elements["content"] .= $this->marker_suffix["start"].$match[2].$this->marker_suffix["end"];

				//Anfangsmarkierung definieren
				$markerBegin = $this->sector_suffix["start"][0].$match[2].$this->sector_suffix["start"][1];				

				//Neues Element vorbereiten
		    	$elements["children"][$match[2]] = array( "content"  => "",
								      					  "marker"   => array( "begin" => $markerBegin, "end" => "" ),
		            	                                  "children" => array(),
														  "buffer"   => array() ); # Element initialisieren
							      
		    	$stack[count($stack)] = &$elements;                                # Referenzposition
		    	$elements = &$elements["children"][$match[2]];                     # Element Referenzieren
			}

			//Inhalt anh�ngen (aber aufpassen dass nicht zu viel genommen wird)
			if ( preg_match($regexpMiddle,$line,$tmpMatch) ) {
				//Markierungen f�r Anfang und Ende stehen in einer Zeile
				$elements["content"] .= $tmpMatch[4];
			} else if ( preg_match($regexpStart,$line,$tmpMatch) ) {
				//Die Start-Markierung steht in dieser Zeile, also NUR den Text dahinter mitnehmen
				$elements["content"] .= $tmpMatch[3];
			} else if ( preg_match($regexpEnd,$line,$tmpMatch) ) {
				//Die End-Markierung steht in dieser Zeile, also NUR den Text vor dem Ende mitnehmen
				$elements["content"] .= $tmpMatch[1];
			} else {
				//Es steht gar nix in der Zeile also einfach nur speichern
				$elements["content"] .= $line;
			}

			//Endmarkierung erreicht
			if ( preg_match($regexpEnd,$line,$match) ) {
			    //Endmarkierung definieren
				$markerEnd   = $this->sector_suffix["end"][0].$match[2].$this->sector_suffix["end"][1]; # Marker f�r Startposition erstellen

		    	//Markierung im Content l�schen
				$elements["content"] = preg_replace("=".$this->sector_suffix["start"][0]."([".$this->allowed_chars."]*)".$this->sector_suffix["start"][1]."=siU","",$elements["content"]);
				$elements["content"] = preg_replace("=".$this->sector_suffix["end"][0]."([".$this->allowed_chars."]*)".$this->sector_suffix["end"][1]."=siU","",$elements["content"]);

		    	//End Marker speichern
		    	$elements["marker"]["end"] = $markerEnd;
		    
			    //Zur�ck zum vorherigen Wert
		    	$elements = &$stack[count($stack)-1]; # Zur�ckkehren zum vorherigen Element
		    	unset($stack[count($stack)-1]);       # Speicher freigeben

				//An das hintere Ende noch den restlichen Text anh�ngen
				$elements["content"] .= $match[3];
			}
	    }
	    
	    //Hauptelement abschlie�en
	    $elements = &$stack[count($stack)-1]; # Zum Hauptelemen zur�ckkehren
	    unset($stack[count($stack)-1]);       # Speicher freigeben

	    //Den Main Content schonmal in den Buffer laden
	    $this->buffer["MAIN"] = $this->template["MAIN"]["content"];

	    //Abschlie�en
	    return true;
	}


	//Leert den Speicher
	// Aufruf: $this->___clearBuffer();
	
	function ___clearBuffer() {
	
	    //Speicher freigeben
	    unset($this->template);
	    unset($this->result);

	    //Abschlie�en
	    return true;
	}
	
	
	//F�gt ein Array in das Template ein. Der Array KEY wird als TplVar benutzt
	// Aufruf: $this->___insert($dataAr,$partAr)
	function ___insert($dataAr,$partAr) {

	    //Je nach Template Variante einf�gen
	    if ( is_array($this->template) ) {
			//Wurde ein Subpart angegeben
			if ( sizeof($partAr) > 0 ) {
		    	//Auf einzuf�gendem Bereich referenzieren
		    	$elements = &$this->template["MAIN"]["children"];

		    	//In die Tiefe der Abschnitte gehen
		    	for ( $i = 0; $i < sizeof($partAr); $i++ ) {
					if ( $i < (sizeof($partAr)-1) ) {
						$bufferID = count($elements[$partAr[$i]]["buffer"])-1;
			    		$elements = &$elements[$partAr[$i]]["children"];
					} else $elements = &$elements[$partAr[$i]];
		    	}

				//Zwischenspeicher
				$tmpBuffer = $elements["content"];    	

		    	//Template Variablen verarbeiten
		    	foreach ( $dataAr as $tplVar => $var ) {
	    			//Template Variable ab�ndern
	    			$tplVar = $this->var_suffix["start"].$tplVar.$this->var_suffix["end"];
	    			
		    		//Inhalt einf�gen
		    		$tmpBuffer = str_replace($tplVar,$var,$tmpBuffer);
		    	}

				//Ergebnis noch etwas ab�ndern
				$bufferID = ( $bufferID > 0 ) ? $bufferID : 0;
				$regexp = "=(".$this->marker_suffix["start"].")(.*)(".$this->marker_suffix["end"].")=siU";
				$tmpBuffer = preg_replace($regexp,"\\1\\2#".(sizeof($elements["buffer"]))."\\3",$tmpBuffer);

				//Ergebnis aus Zwischenspeicher einf�gen
				$elements["buffer"][] = array("parentID" => $bufferID, "buffer" => $tmpBuffer);
				unset($tmpBuffer);

			} else {
				/* Wenn kein Bereich angegeben landet alles in MAIN bereich */
				
				//Template Variablen verarbeiten
				foreach ( $dataAr as $tplVar => $var ) {
	    			//Template Variable ab�ndern
	    			$tplVar = $this->var_suffix["start"].$tplVar.$this->var_suffix["end"];
	    			
		    		//In MAIN einf�gen
		    		$this->template["MAIN"]["content"] = str_replace($tplVar,$var,$this->template["MAIN"]["content"]);
				}
			}
	    } else {
	    	/* Wenn Template nicht aufgetrennt wurde, landet alles im Hauptbereich */
	    	
	    	//Array abarbeiten
	    	foreach ( $dataAr as $tplVar => $var ) {
	    		//Template Variable ab�ndern
	    		$tplVar = $this->var_suffix["start"].$tplVar.$this->var_suffix["end"];
	    		
				//Einfaches ersetzen
				$this->template = str_replace($tplVar,$var,$this->template);
	    	}
	    }

	    //Abschlie�en
	    return true;
	}
	
	
	//F�gt das Template Array wieder zusammen
	// Aufruf: $this->___impResult($this->template["MAIN"]["children"]);
	
	function ___impResult($result) {

		//Startwerte festlegen
		$elements             = &$this->template; # Startelement
		$stack[count($stack)] = &$this->template; # Positionsmarker
		$deep                 = 0;                # Tiefenmarkierung

		//Alle Elemente abarbeiten
		while ( $element = &current($elements) ) {

			//Name des aktuellen Array Elements
			$key = key($elements);

			//Inhalt einf�gen
			$search  = $this->marker_suffix["start"].$key.$this->marker_suffix["end"];

			//Den Buffer f�r das entsprechende Element zuweisen
			if ( sizeof($element["buffer"]) > 0 ) {
				foreach ( $element["buffer"] as $buffer ) {
					$replaces[$buffer["parentID"]][$key] .= $buffer["buffer"];
				}
			}

			//Ersetzen?
			if ( sizeof($replaces) > 0 ) {
				foreach ( $replaces as $rKey => $replace ) {
					//Inhalt einf�gen
					if ( $deep > 1 ) {
						$search = $this->marker_suffix["start"].$key."#".$rKey.$this->marker_suffix["end"];
					} else $search = $this->marker_suffix["start"].$key.$this->marker_suffix["end"];

					//Ergebnis speichern bzw. einsetzen
					$this->result = str_replace($search,$replace[$key],$this->result);
				}
			}

			//Buffer leeren um doppelte Eintr�ge zu vermeiden
			unset($replaces);

			//N�chstes Element
			$back = next($elements);

			//N�chstes untergeordnete Element anfangen
			if ( sizeof($element["children"]) > 0 ) {
				$stack[count($stack)] = &$elements;
				$elements = &$element["children"];
				$deep++;
			}

			//Zur�ck zum vorherigen Element
			if ( (!$back) && (sizeof($element["children"]) == 0) ) {
				$elements = &$stack[count($stack)-1];
				unset($stack[count($stack)-1]);
				$deep--;
			}
		}
	}
	
	//Entfernt nicht verwendete Marker und Variablen
	// Aufruf: $this->___clearResult();

	function ___clearResult() {
		//Variablen
		$regexp = "=".$this->var_suffix["start"]."([".$this->allowed_chars."]*)".$this->var_suffix["end"]."=siU";
		$this->result = preg_replace($regexp,"",$this->result);

		//Marker
		$regexp = "=".$this->marker_suffix["start"]."([".$this->allowed_chars."]*)".$this->marker_suffix["end"]."=siU";
		$this->result = preg_replace($regexp,"",$this->result);

		//SubMarker
		$regexp = "=".$this->marker_suffix["start"]."([".$this->allowed_chars."]*#[0-9]*)".$this->marker_suffix["end"]."=siU";
		$this->result = preg_replace($regexp,"",$this->result);
	}


	//Gibt das Ergebnis des Templates zur�ck
	// Aufruf: $this->___Result();
	
	function ___Result() {

	    //Abschlie�en
	    return $this->template;
	}
    
    /* --- PRIVATE FUNCTIONS --- END --- */
}

?>