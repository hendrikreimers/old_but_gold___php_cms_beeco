<?

/* MySQL Funktionen um die Einträge zu laden, einzufügen, usw. */

class pics_mysql_class
{
	//Tabellen prefix
	var $prefix;

    /* --- AUSLESEN --- START --- */
    
      //Lädt die Gruppen
      function load_groups($order)
      {
	      //Gruppen abfragen
          $query  = "SELECT * FROM ".$this->prefix."pics_groups";
          $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Abfrage",mysql_error()));
          
          if ( $result )
          {
              //Verarbeiten
              while ( $row = mysql_fetch_array($result,MYSQL_ASSOC) )
              {
                  //Anzahl der Bilder
                  $query   = "SELECT COUNT(id) AS `count` FROM ".$this->prefix."pics_items WHERE group_id = '".$row["id"]."'";
                  $cresult = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Biler zählen",mysql_error()));
                  $count   = @mysql_result($cresult,0,"count");
                  @mysql_free_result($cresult);

                  $retval[] = array("id"    => $row["id"],
                                    "mode"  => $row["mode"],
                                    "title" => base64_decode($row["title"]),
                                    "desc"  => base64_decode($row["desc"]),
                                    "count" => (($count > "0") ? $count : "0"));
              }
              
              if ( sizeof($retval) > 0 )
              {
                  //Array sortieren
                  $cmp = create_function('$a,$b','if (strtolower($a["title"]) == strtolower($b["title"])) return 0; return (strtolower($a["title"]) > strtolower($b["title"])) ? -1 : 1;');
                  usort($retval,$cmp);
                  unset($cmp);
                  
				  //Sortierung anpassen
				  if ( $order == "ASC" )
				  {
				      $retval = array_reverse($retval);
				  }
              }
              
              mysql_free_result($result);
              return $retval;
          }
          else
          {
              @mysql_free_result($result);
              return false;
          }
      }
      
      //Lädt die infos zu einer Gruppe
      function load_group($id)
      {
          //Query
          $query  = "SELECT * FROM ".$this->prefix."pics_groups WHERE id = '".$id."'";
          $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Abfrage",mysql_error()));

          if ( $result )
          {
              //Verarbeiten
              $retval["id"]    = $id;
              $retval["mode"]  = mysql_result($result,"0","mode");
              $retval["title"] = mysql_result($result,"0","title");
              $retval["desc"]  = mysql_result($result,"0","desc");

              mysql_free_result($result);
              return $retval;
          }
          else
          {
              @mysql_free_result($result);
              return false;
          }
      }
      
      //Lädt alle bidler zu einer gruppe
      function load_items($group_id,$order = "ASC")
      {
          $query  = "SELECT items.id    AS id,
                            items.title AS title,
                            items.desc  AS `desc`,
                            groups.mode AS mode,
                            items.group_id AS `group`
                     FROM ".$this->prefix."pics_items        AS items
                     INNER JOIN ".$this->prefix."pics_groups AS groups
                             ON groups.id = items.group_id
                     WHERE group_id = '".$group_id."' ORDER BY id";
                            
          $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Abfrage",mysql_error()));
          
          while ( $row = mysql_fetch_array($result,MYSQL_ASSOC) )
          {
              $retval[] = array("id"    => $row["id"],
                                "title" => base64_decode($row["title"]),
                                "desc"  => base64_decode($row["desc"]),
                                "group" => $row["group"],
                                "mode"  => $row["mode"]);
          }
          
          if ( sizeof($retval) > 0 )
          {
              //Array sortieren
              $cmp = create_function('$a,$b','if ( strtolower( ((strlen($a["title"]) > 0) ? $a["title"] : $a["id"]) ) == strtolower( ((strlen($b["title"]) > 0) ? $b["title"] : $b["id"]) ) ) return 0; return (strtolower( ((strlen($a["title"]) > 0) ? $a["title"] : $a["id"]) ) > strtolower( ((strlen($b["title"]) > 0) ? $b["title"] : $b["id"]) )) ? -1 : 1; ');
              usort($retval,$cmp);
              unset($cmp);
			  
			  if ( $order == "ASC" )
			  {
                  $retval = array_reverse($retval);
			  }
          }

          mysql_free_result($result);
          return $retval;
      }
      
      //Lädt ein einzelnes Bild
      function load_item($id)
      {
          $query = "SELECT items.id    AS id,
                           items.title AS title,
                           items.desc  AS `desc`,
                           groups.mode AS mode,
                           items.group_id AS `group`
                    FROM ".$this->prefix."pics_items        AS items
                    INNER JOIN ".$this->prefix."pics_groups AS groups
                            ON groups.id = items.group_id
                    WHERE items.id = '".$id."'";
                    
          $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler bei der Abfrage",mysql_error()));

          if ( $result )
          {
              $retval["id"]    = $id;
              $retval["mode"]  = mysql_result($result,0,"mode");
              $retval["title"] = base64_decode(mysql_result($result,0,"title"));
              $retval["desc"]  = base64_decode(mysql_result($result,0,"desc"));
              $retval["group"] = mysql_result($result,0,"group");
              
              mysql_free_result($result);
              return $retval;
          }
          else return false;
      }
    
      //Zählt die Einträge einer gruppe
      function get_item_count($id)
      {
          $query  = "SELECT COUNT(id) AS `count` FROM ".$this->prefix."pics_items WHERE group_id = '".$id."'";
          $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Zählen fehlgeschlagen",mysql_error()));
          $count  = @mysql_result($result,"0","count");
          @mysql_free_result($result);
          return ( $count > 0 ) ? $count : 0;
      }
    
    /* --- AUSLESEN --- ENDE --- */
    
    
    /* --- HINZUFÜGEN --- START --- */
    
      //Fügt eine neue Gruppe hinzu
      function add_group($title,$desc)
      {
          $query = "INSERT INTO ".$this->prefix."pics_groups VALUES ('','','".base64_encode($title)."','".base64_encode($desc)."')";
          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Hinzufügen fehlgeschlagen",mysql_error()));
          
          return true;
      }
      
      //Fügt ein Bild hinzu
      function add_item($group_id,$title,$desc)
      {
          $query = "INSERT INTO ".$this->prefix."pics_items VALUES ('','".$group_id."','".base64_encode($title)."','".base64_encode($desc)."')";
          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Hinzufügen fehlgeschlagen",mysql_error()));
          
          return mysql_insert_id();
      }

    /* --- HINZUFÜGEN --- ENDE --- */
    
    
    /* --- LÖSCHEN --- START --- */
    
      //Löscht eine Gruppe aus der DB
      function del_group($id)
      {
          $query = "DELETE FROM ".$this->prefix."pics_groups WHERE id = '".$id."'";
          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Löschen fehlgeschlagen",mysql_error()));
      }
      
      //Löscht ein Bild aus der DB
      function del_item($id)
      {
          $query = "DELETE FROM ".$this->prefix."pics_items WHERE id = '".$id."'";
          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Löschen fehlgeschlagen",mysql_error()));
      }
    
    /* --- LÖSCHEN --- ENDE --- */
    
    
    /* --- UPDATE --- START --- */
    
      //Aktualisiert ein Item (bild)
      function upd_item($id,$group_id,$title,$desc)
      {
          $query = "UPDATE ".$this->prefix."pics_items SET group_id = '".$group_id."',title = '".base64_encode($title)."',`desc` = '".base64_encode($desc)."' WHERE id = '".$id."'";
          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Update fehlgeschlagen",mysql_error()));
      }
      
      //Aktualisiert eine Gruppe
      function upd_group($id,$title,$desc)
      {
          $query = "UPDATE ".$this->prefix."pics_groups SET title = '".base64_encode($title)."',`desc` = '".base64_encode($desc)."' WHERE id = '".$id."'";
          mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Update fehlgeschlagen",mysql_error()));
      }
      
      //Aktualisiert die Eigenschaften einer Gruppe
      function upd_attributes($id,$mode)
      {
      	  $query = "UPDATE ".$this->prefix."pics_groups SET mode = '".$mode."' WHERE id = '".$id."'";
      	  mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Update fehlgeschlagen",mysql_error()));
      }
    
    /* --- UPDATE --- ENDE --- */
}

?>
