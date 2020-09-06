<?

/* MySQL Funktionen um die Einträge zu laden, einzufügen, usw. */

class time_mysql_class
{
	//Tabellen prefix
	var $prefix;

    /* --- AUSLESEN --- START --- */
	
	  //Lädt einen einzelnen Eintrag
	  function load_entry($id)
	  {
	  	  //Abfrage
	  	  $query = "SELECT * FROM ".$this->prefix."time WHERE id = '".$id."'";
	  	  $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Auswahl des Eintrags",mysql_error()));
	  	  
	  	  if ( $result )
	  	  {
	  	  	  $retval["id"]       = @mysql_result($result,"0","id");
	  	  	  $retval["date"]     = preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\3.\\2.\\1",@mysql_result($result,"0","date"));
	  	  	  $retval["title"]    = base64_decode(@mysql_result($result,"0","title"));
	  	  	  $retval["desc_id"]  = @mysql_result($result,"0","desc_id");
	  	  	
	  	  	  @mysql_free_result($result);
	  	
	  	  	  //Beschreibung laden
	  	  	  if ( $retval["desc_id"] > 0 )
	  	  	  {
	  	  	      //Beschreibung laden
	  	  	      $query = "SELECT text FROM ".$this->prefix."time_desc WHERE id = '".$retval["desc_id"]."'";
	  	  	      $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Auswahl des Eintrags",mysql_error()));
	  	  	      
	  	  	      $retval["text"] = @mysql_result($result,"0","text");
	  	  	      
	  	  	      @mysql_free_result($result);
	  	  	  }
	  	  	
	  	  	  return $retval;
	  	  }
		  else
		  {
			  @mysql_free_result($result);
			  return false;
		  } 
	  }
	  
	  //Alle Einträge auswählen
	  function load_entries()
	  {
	      $query = "SELECT t.id    AS id,
                           t.date  AS date,
                           t.title AS title,
                           d.text  AS text
                    FROM ".$this->prefix."time AS t
                    LEFT JOIN ".$this->prefix."time_desc AS d
                    ON t.desc_id = d.id
                    ORDER BY date ASC
                   ";
                   
          $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Auswahl der Einträge",mysql_error()));
          
          if ( $result )
          {
              //Datenrückgabe vorbereiten
              while ( $row = mysql_fetch_array($result,MYSQL_ASSOC) )
              {
                  $retval[] = array( "id"    => $row["id"],
                                     "date"  => preg_replace("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/i","\\3.\\2.\\1",$row["date"]),
                                     "title" => base64_decode($row["title"]),
                                     "text"  => base64_decode($row["text"]) );
              }
              
              mysql_free_result($result);
              
              return $retval;
          }
          else return false;
	  }
	  
	/* --- AUSLESEN --- ENDE --- */
	
	
	
	/* --- HINZUFÜGEN --- START --- */
	
	  function add_entry($date,$title,$text = "")
	  {
	      //Beschreibung hinzufügen
	      if ( strlen($text) > 0 )
	      {
	          $desc_id = $this->add_desc($text);
	      }
	      else $desc_id = "0";
	
	  	  //Hinzufügen
	  	  $query = "INSERT INTO ".$this->prefix."time VALUES ('','".$date."','".$title."','".$desc_id."')";
	  	  @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim hinzufügen",mysql_error()));
	  }
	  
	  function add_desc($text)
	  {
	      $query = "INSERT INTO ".$this->prefix."time_desc VALUES ('','".$text."')";
	      @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim hinzufügen",mysql_error()));
	      $desc_id = mysql_insert_id();
	      
	      return $desc_id;
	  }
	
	/* --- HINZUFÜGEN --- ENDE --- */
	
	
	
	/* --- LÖSCHEN --- START --- */
	
	  //Löscht einen Eintrag
	  function delete_entry($id)
	  {
	      //Beschreibung ggf löschen
	      $query   = "SELECT desc_id FROM ".$this->prefix."time WHERE id = '".$id."'";
	      $result  = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim löschen des Eintrages",mysql_error()));
	      $desc_id = @mysql_result($result,"0","desc_id");
	      @mysql_free_result($result);
	      
	      //Beschreibung löschen wenn vorhanden
	      if ( $desc_id > 0 )
	      {
	          $this->delete_desc($desc_id);
	      }
	
	  	  //Löschen
	  	  $query = "DELETE FROM ".$this->prefix."time WHERE id = '".$id."'";
	  	  mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim löschen des Eintrages",mysql_error()));
	  	  
	  	  return true;
	  }
	  
	  //Löscht eine Beschreibung
	  function delete_desc($id)
	  {
	      $query = "DELETE FROM ".$this->prefix."time_desc WHERE id = '".$id."'";
	  	  mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim löschen des Textes",mysql_error()));
	  	  
	  	  return true;
	  }
	
	/* --- LÖSCHEN --- ENDE --- */
	
	
	
	/* --- UPDATE --- START --- */
	
	  //Aktualisiert einen Eintrag
	  function update_entry($id,$date,$title,$text = "")
	  {
	  	  $title    = base64_encode($title);
	  	  $text     = base64_encode($text);
	  	  
	  	  //Eintrag aktualisieren
	  	  $query = "UPDATE ".$this->prefix."time SET title = '$title',date = '$date' WHERE id = '$id'";
	  	  mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim ändern des Eintrages",mysql_error()));
	  	  
	  	  //ID der Beschreibung?
	  	  $query   = "SELECT desc_id FROM ".$this->prefix."time WHERE id = '".$id."'";
	      $result  = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim lesen des Eintrages",mysql_error()));
	      $desc_id = @mysql_result($result,"0","desc_id");
	      @mysql_free_result($result);
	      
	      //Beschreibung hinzufügen bzw. löschen
	      //folgendes wenn es bereits eine beschreibung gibt
	      if ( $desc_id > 0 )
	      {
	          //Text aktualisieren
	          if ( strlen($text) > 0 )
	          {
	              //Aktualisieren
	              $query = "UPDATE ".$this->prefix."time_desc SET text = '$text' WHERE id = '$desc_id'";
	              mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim ändern des Eintrages",mysql_error()));
	          }
	          //Text löschen wenn inhalt leer
	          else
	          {
	              $this->delete_desc($desc_id);
	              $query = "UPDATE ".$this->prefix."time SET desc_id = '0' WHERE id = '$id'";
	  	          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim ändern des Eintrages",mysql_error()));
	          }
	      }
	      //folgendes wenn es noch keine beschreibung gibt
	      else
          {
              if ( strlen($text) > 0 )
	          {
      	          $desc_id = $this->add_desc($text);
      	          $query = "UPDATE ".$this->prefix."time SET desc_id = '$desc_id' WHERE id = '$id'";
	  	          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim ändern des Eintrages",mysql_error()));
	          }
	      }
	  	  
	  	  return true;
	  }
	
	/* --- UPDATE --- ENDE --- */
}

?>