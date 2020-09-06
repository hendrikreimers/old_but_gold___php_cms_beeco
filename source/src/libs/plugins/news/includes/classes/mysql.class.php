<?

/* MySQL Funktionen um die Eintr�ge zu laden, einzuf�gen, usw. */

class news_mysql_class
{
	//Tabellen prefix
	var $prefix;

    /* --- AUSLESEN --- START --- */
	
	  //L�dt einen einzelnen Eintrag
	  function load_entry($id)
	  {
	  	  //Abfrage
	  	  $query = "SELECT * FROM ".$this->prefix."news WHERE id = '".$id."'";
	  	  $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Auswahl des Eintrags",mysql_error()));
	  	  
	  	  if ( $result )
	  	  {
	  	  	  $retval["id"]       = @mysql_result($result,"0","id");
	  	  	  $retval["date"]     = preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\3.\\2.\\1",@mysql_result($result,"0","date"));
	  	  	  $retval["time"]     = substr(@mysql_result($result,"0","time"),0,5);
	  	  	  $retval["title"]    = base64_decode(@mysql_result($result,"0","title"));
	  	  	  $retval["text"]     = base64_decode(@mysql_result($result,"0","text"));
	  	  	  	  	  	  
	  	  	  @mysql_free_result($result);
	  	  	  return $retval;
	  	  }
		  else
		  {
			  @mysql_free_result($result);
			  return false;
		  } 
	  }
	
	  //L�dt die Eintr�ge einer bestimmten Seite
  	  function news_load_page($page,$max_entries,$nl2br,$tagfilter,$textcut = "1",$max_words = "15")
	  {
	      //Sondereinstellung f�r die Vorschau
	      require(USER_PATH."/settings/plugins/news/other.settings.php");
		  $strip_tags = $settings["news"]["preview_strip_tags"];
	  
		  //Berechnen der auszuw�hlenden LIMITs
		  $position = ($page*$max_entries)-$max_entries;
		
		  //Setzen des Z�hlers f�r die darstellung
		  $count = $this->get_max_entries()-$position;
		
		  //Abfrage
		  $query  = "SELECT * FROM ".$this->prefix."news ORDER BY CONCAT(date,time) DESC LIMIT ".$position.",".$max_entries;
		  $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Auswahl der Eintr�ge",mysql_error()));  
		
		  //Ergebnis?
		  if ( $result )
		  {
			  //Verarbeiten
			  while ( $row = mysql_fetch_array($result,MYSQL_ASSOC) )
			  {
			  	  //Text entschl�sseln
  				  $row["title"]    = stripslashes(base64_decode($row["title"]));
				  $row["text"]     = stripslashes(base64_decode($row["text"]));
				  
				  //Text k�rzen?
				  if ( $textcut == "1" )
				  {
				  	  $tmp = Explode(" ",( ($strip_tags == "1") ? strip_tags($row["text"]) : $row["text"] ));
				  	  
					  for ( $i = 0; $i < $max_words; $i++ )
				  	  {
				  	  	  $new_text .= $tmp[$i]." ";
				  	  }
				  	  
					  $row["text"] = $new_text;
				  	  
				  	  unset($new_text);
				  	  unset($tmp);
				  }

  				  //Datum versch�nern
		          $row["date"] = preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\3.\\2.\\1",$row["date"]);
		
        		  //Uhrzeit verk�rzen
		          $row["time"] = substr($row["time"],0,5);
		        
		          //Gegebenfalls HTML abschalten
				  if ( $enable_html == "0" )
				  {
					  $row["text"] = htmlentities($row["text"]);
				  }
				
				  //Textumbruch (nur wenn vom experten eingeschaltet)
				  if ( $settings["news"]["preview_auto_nl2br"] == "1" )
				  {
				      $row["text"] = auto_nl2br($row["text"],$nl2br,base64_decode($tagfilter));
				  }
  		
                  //Ver�ndertes Ergebnis zum zur�ckliefern verarbeiten
				  $row["count"] = $count; # Z�hler anh�ngen
				  $retval[]     = $row;   # Speichern
				  $count--;               # Z�hler verkleinern
			  }
			
			  @mysql_free_result($result);
			  return $retval;
		  }
		  else
		  {
			  @mysql_free_result($result);
			  return false;
		  } 
	  }
	
	  //Zeigt wieviele Seite verf�gbar sind
	  function get_max_pages($max_entries)
  	  {
		  //Eintr�ge z�hlen
		  $query  = "SELECT COUNT(id) as `count` FROM ".$this->prefix."news";
		  $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim z�hlen",mysql_error()));
		
		  if ( $result )
		  {
			  $count = @mysql_result($result,"0","count");
			  @mysql_free_result($result);
			
			  return ceil($count/$max_entries);
		  }
		  else
		  {
			  @mysql_free_result($result);
			  return false;
		  } 
	  }
	
	  //Zeigt wieviele Seite verf�gbar sind
	  function get_max_entries()
	  {
		  //Eintr�ge z�hlen
		  $query  = "SELECT COUNT(id) as `count` FROM ".$this->prefix."news";
		  $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim z�hlen",mysql_error()));
		
		  if ( $result )
		  {
			  $count = @mysql_result($result,"0","count");
			  @mysql_free_result($result);
			
			  return $count;
		  }
		  else
		  {
  			  @mysql_free_result($result);
			  return false;
		  } 
	  }
	
	/* --- AUSLESEN --- ENDE --- */
	
	
	
	/* --- HINZUF�GEN --- START --- */
	
	  function add_entry($date,$time,$title,$text)
	  {
	  	  //Hinzuf�gen
	  	  $query = "INSERT INTO ".$this->prefix."news VALUES ('','".$date."','".$time."','".$title."','".$text."')";
	  	  mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim hinzuf�gen",mysql_error()));
	  }
	
	/* --- HINZUF�GEN --- ENDE --- */
	
	
	
	/* --- L�SCHEN --- START --- */
	
	  //L�scht einen Eintrag
	  function delete_entry($id)
	  {
	  	  //L�schen
	  	  $query = "DELETE FROM ".$this->prefix."news WHERE id = '".$id."'";
	  	  mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim l�schen des Eintrages",mysql_error()));
	  	  return true;
	  }
	
	/* --- L�SCHEN --- ENDE --- */
	
	
	
	/* --- UPDATE --- START --- */
	
	  //Aktualisiert einen Eintrag
	  function update_entry($id,$date,$time,$title,$text)
	  {
	  	  $title    = base64_encode($title);
	  	  $text     = base64_encode($text);
	  	
	  	  $query = "UPDATE ".$this->prefix."news SET title = '$title',text = '$text',date = '$date',time = '$time' WHERE id = '$id'";
	  	  mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim �ndern des Eintrages",mysql_error()));
	  	  
	  	  return true;
	  }
	
	/* --- UPDATE --- ENDE --- */
}

?>
