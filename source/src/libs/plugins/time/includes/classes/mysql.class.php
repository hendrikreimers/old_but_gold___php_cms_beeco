<?

/* MySQL Funktionen um die Eintr�ge zu laden, einzuf�gen, usw. */

class time_mysql_class
{
	//Tabellen prefix
	var $prefix;

    /* --- AUSLESEN --- START --- */
	
	  //L�dt einen einzelnen Eintrag
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
	  
	  //Alle Eintr�ge ausw�hlen
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
                   
          $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Auswahl der Eintr�ge",mysql_error()));
          
          if ( $result )
          {
              //Datenr�ckgabe vorbereiten
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
	
	
	
	/* --- HINZUF�GEN --- START --- */
	
	  function add_entry($date,$title,$text = "")
	  {
	      //Beschreibung hinzuf�gen
	      if ( strlen($text) > 0 )
	      {
	          $desc_id = $this->add_desc($text);
	      }
	      else $desc_id = "0";
	
	  	  //Hinzuf�gen
	  	  $query = "INSERT INTO ".$this->prefix."time VALUES ('','".$date."','".$title."','".$desc_id."')";
	  	  @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim hinzuf�gen",mysql_error()));
	  }
	  
	  function add_desc($text)
	  {
	      $query = "INSERT INTO ".$this->prefix."time_desc VALUES ('','".$text."')";
	      @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim hinzuf�gen",mysql_error()));
	      $desc_id = mysql_insert_id();
	      
	      return $desc_id;
	  }
	
	/* --- HINZUF�GEN --- ENDE --- */
	
	
	
	/* --- L�SCHEN --- START --- */
	
	  //L�scht einen Eintrag
	  function delete_entry($id)
	  {
	      //Beschreibung ggf l�schen
	      $query   = "SELECT desc_id FROM ".$this->prefix."time WHERE id = '".$id."'";
	      $result  = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim l�schen des Eintrages",mysql_error()));
	      $desc_id = @mysql_result($result,"0","desc_id");
	      @mysql_free_result($result);
	      
	      //Beschreibung l�schen wenn vorhanden
	      if ( $desc_id > 0 )
	      {
	          $this->delete_desc($desc_id);
	      }
	
	  	  //L�schen
	  	  $query = "DELETE FROM ".$this->prefix."time WHERE id = '".$id."'";
	  	  mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim l�schen des Eintrages",mysql_error()));
	  	  
	  	  return true;
	  }
	  
	  //L�scht eine Beschreibung
	  function delete_desc($id)
	  {
	      $query = "DELETE FROM ".$this->prefix."time_desc WHERE id = '".$id."'";
	  	  mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim l�schen des Textes",mysql_error()));
	  	  
	  	  return true;
	  }
	
	/* --- L�SCHEN --- ENDE --- */
	
	
	
	/* --- UPDATE --- START --- */
	
	  //Aktualisiert einen Eintrag
	  function update_entry($id,$date,$title,$text = "")
	  {
	  	  $title    = base64_encode($title);
	  	  $text     = base64_encode($text);
	  	  
	  	  //Eintrag aktualisieren
	  	  $query = "UPDATE ".$this->prefix."time SET title = '$title',date = '$date' WHERE id = '$id'";
	  	  mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim �ndern des Eintrages",mysql_error()));
	  	  
	  	  //ID der Beschreibung?
	  	  $query   = "SELECT desc_id FROM ".$this->prefix."time WHERE id = '".$id."'";
	      $result  = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim lesen des Eintrages",mysql_error()));
	      $desc_id = @mysql_result($result,"0","desc_id");
	      @mysql_free_result($result);
	      
	      //Beschreibung hinzuf�gen bzw. l�schen
	      //folgendes wenn es bereits eine beschreibung gibt
	      if ( $desc_id > 0 )
	      {
	          //Text aktualisieren
	          if ( strlen($text) > 0 )
	          {
	              //Aktualisieren
	              $query = "UPDATE ".$this->prefix."time_desc SET text = '$text' WHERE id = '$desc_id'";
	              mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim �ndern des Eintrages",mysql_error()));
	          }
	          //Text l�schen wenn inhalt leer
	          else
	          {
	              $this->delete_desc($desc_id);
	              $query = "UPDATE ".$this->prefix."time SET desc_id = '0' WHERE id = '$id'";
	  	          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim �ndern des Eintrages",mysql_error()));
	          }
	      }
	      //folgendes wenn es noch keine beschreibung gibt
	      else
          {
              if ( strlen($text) > 0 )
	          {
      	          $desc_id = $this->add_desc($text);
      	          $query = "UPDATE ".$this->prefix."time SET desc_id = '$desc_id' WHERE id = '$id'";
	  	          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim �ndern des Eintrages",mysql_error()));
	          }
	      }
	  	  
	  	  return true;
	  }
	
	/* --- UPDATE --- ENDE --- */
}

?>