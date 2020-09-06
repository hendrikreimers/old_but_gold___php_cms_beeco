<?

class mysql_class
{
     //Allgemeine Variablen
     var $prefix; # Tabellen Prefix
     var $order;  # Sortierreihenfolge

    /* --- Verbindungsfunktionen --- start --- */

        //Öffnet eine Verbindung nud wählt eine DB aus.
        function connect($host,$user,$pass,$db)
        {
            @mysql_connect($host,$user,$pass) or die(_sqlerror(__FILE__,__LINE__,"Verbindungsfehler",mysql_error()));
            @mysql_select_db($db) or die(_sqlerror(__FILE__,__LINE__,"Verbindungsfehler",mysql_error()));
        }
    
        //Schließt die datenbankverbindung
        function close()
        {
            @mysql_close();
        }
    
    /* --- Verbindungsfunktionen --- ende --- */
    
    
    
    /* --- Login/Logout Funktionen --- start --- */
    
        //Login SID speichern
        function register_SID($SID)
        {
            $query = "UPDATE ".$this->prefix."settings SET `value` = '".$SID."' WHERE `key` = 'loginSID'";
            @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"SID konnte nicht in der DB gespeichert werden",mysql_error()));
        }
        
        //Login SID löschen
        function unregister_SID()
        {
            $query = "UPDATE ".$this->prefix."settings SET `value` = '0' WHERE `key` = 'loginSID'";
            @mysql_query($query) or die(mysql_error());
        }
        
        function getTime()
        {
            $query  = "SELECT `value` FROM ".$this->prefix."settings WHERE `key` = 'time'";
            $result = @mysql_query($query) or die(mysql_error());
            $retval = @mysql_result($result,0);
            return $retval;
        }
        
        function updateTime()
        {
            $query = "UPDATE ".$this->prefix."settings SET `value` = '".time()."' WHERE `key` = 'time'";
            @mysql_query($query) or die(mysql_error());
        }
    
    /* --- Login/Logout Funktionen --- ende --- */
    
    
    
    /* --- Allgemeine Funktionen --- start --- */
    
		//Prüft ob ein Plugin bereits installiert ist
		function install_check($plugin_name)
		{
		    $query = "SELECT id,title FROM ".$this->prefix."plugins WHERE name = '".$plugin_name."'";
		    $result = @mysql_query($query);
		    $id     = @mysql_result($result,0,"id");
		    $title  = @mysql_result($result,0,"title");
		    @mysql_free_result($result);
		    
		    if ( $id > "0" )
		    {
		        return $title;
		    }
		    else return false;
		}
    
        //Fügt die Konfiguration aus der DB zu den anderen Einstellungen hinzu
        function load_settings($current_settings)
        {
            //Abfragge vorbereiten
            $query = "SELECT plugins.name   AS name,
                             plugins.title  AS title,
                             settings.key   AS 'key',
                             settings.value AS value
                      FROM ".$this->prefix."settings AS settings,
                           ".$this->prefix."plugins AS plugins
                      WHERE plugins.id = settings.plugin_id";
                      
            //Abfrage ausführen
            $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Einstellungen konnten nicht gelesen werden",mysql_error()));
            
            //Bei Ergebnis verarbeiten
            if ( $result )
            {
                //Jeden Eintrag hinzufügen
                while ( $row = @mysql_fetch_array($result,MYSQL_ASSOC) )
                {
                    //Zuordnen
                    $current_settings[$row["name"]][$row["key"]] = $row["value"]; # Eigenschaft
                    $current_settings[$row["name"]]["name"]      = $row["name"];  # Pluginname
                    $current_settings[$row["name"]]["title"]     = $row["title"]; # Plugin Titel
                }
            }
            
            //Ergebnis liefern
            return $current_settings;
        }
        
        //Deinstallieren des MiniCMS(SE)
        function uninstall($password)
        {
            //Password Kontrolle
            $query = "SELECT settings.value AS pass
                      FROM ".$this->prefix."settings AS settings,
                           ".$this->prefix."plugins AS plugins
                      WHERE plugins.id = settings.plugin_id
                        AND plugins.name = 'base'
                        AND settings.key = 'pass'";

            $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Passwort konnte nicht abgefragt werden",mysql_error())); # Ausführen
            $pass   = Explode("|",base64_decode(@mysql_result($result,0,"pass")));                                                                                  # Ergebnis speichern
            $pass   = base64_encode($pass[0]);
            mysql_free_result($result);

            //Bei korrektem Passwort deinstallation beginnen
            if ( $password == $pass )
            {
                //Tabellen
                $tables = array("contents","items","plugins","redirects","settings");
                
                //Tabellen löschen
                foreach ( $tables as $table )
                {
                    $query = "DROP TABLE ".$this->prefix.$table;
                    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle ".$table." nicht gelöscht",mysql_error())); # Ausführen
                }
                
                return true;
            }
            else return false;
        }
        
        //Optimiert die Tabellen um Überhang zu löschen
        function optimize($db)
        {
            //Tabellen sammeln
            $result = mysql_list_tables($db) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim auflisten der Tabellen",mysql_error()));
            
            if ( $result )
            {
                while ( $row = mysql_fetch_row($result) )
                {
                    //Nur MiniCMS SE tabellen optimieren
                    if ( preg_match("/^".$this->prefix."(.*)/i",$row[0]) )
                    {
                        //Optimieren
                        $query = "OPTIMIZE TABLE ".$this->prefix.$row[0];
                	//@mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Tabelle ".$row[0]." nicht optmiert",mysql_error())); # Ausführen
			@mysql_query($query);
                    }
                }
            }
            
            mysql_free_result($result);
        }
    
    /* --- Allgemeine Funktionen --- ende --- */
    
    
    /* --- Konfiguration --- start --- */
    
        //Aktualisiert die Einstellungen eines angegeben Plugins
        function update_settings($plugin,$new_settings,$allow_empty_vars = "0")
        {
            //Kontrolle ob alle felder ausgefüllt sind (sofern nötig)
            if ( $allow_empty_vars == "0" )
            {
                //Kontrollvariable ob einer leer ist
                $is_empty = 0;
                
                foreach ( $new_settings[$plugin] as $setting )
                {
                    //Nur prüfen wenn nicht bereits ein leeres feld gefunden wurde
                    if ( $is_empty != 1 )
                    {
                        $is_empty = ( strlen($setting) > 0 ) ? "0" : "1";
                    }
                }
            }
            
            if ( ($allow_empty_vars == 1) || ($is_empty == 0) )
            {
                //ID des Plugins herausfinden
                $query     = "SELECT id FROM ".$this->prefix."plugins WHERE name = '".$plugin."'";                               # Query
                $result    = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Plugin ID nicht gefunden",mysql_error())); # Ausführen
                $plugin_id = @mysql_result($result,0,"id");                                                                      # Ergebnis speichern
                mysql_free_result($result);                                                                                      # Speicher freigeben
            
                if ( $plugin_id > 0 )
                {
                    //Neue Einstellungen verarbeiten
                    foreach ( $new_settings[$plugin] as $name => $value )
                    {
                        //Datenupdate
                        $query = "UPDATE ".$this->prefix."settings ".
                                 "SET `value` = '".$value."' ".
                                 "WHERE `key` = '".$name."' AND `plugin_id` = '".$plugin_id."'";                            # Query

                        @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Plugin ID nicht gefunden",mysql_error())); # Ausführen
                    }
                }
                else
                {
                    die(_error(__FILE__,__LINE__,"Keine gültige PluginID"));
                }

                return true;
            }
            else return false;
        }
        
        //Ändert das Passwort
        function update_password($old_password,$new_password,$password_phrase,$checksum = "")
        {
            //OldPassword Kontrolle
            $query = "SELECT settings.value AS pass
                      FROM ".$this->prefix."settings AS settings,
                           ".$this->prefix."plugins AS plugins
                      WHERE plugins.id = settings.plugin_id
                        AND plugins.name = 'base'
                        AND settings.key = 'pass'";

            $result    = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Passwort konnte nicht abgefragt werden",mysql_error())); # Ausführen
            $pass = @mysql_result($result,0,"pass");                                                                                       # Ergebnis speichern
            mysql_free_result($result);                                                                                                    # Speicher freigeben

			/* --- PASSWORD OVERKILL *SPECIAL FOR Hendrik Reimers* --- START --- */
			//Die aus admin/settings/update.php übermittelte Checksum wird NUR hierfür benötigt
			//kann also aus dieser funktion und aus update.php entfernt werden, wenn auch diese
			//zeilen entfernt werden.
			
			    $tmp  = Explode("|",base64_decode($pass));
			    $pass = base64_encode($tmp[0]);
			
				if ( ($old_password == base64_encode(crypt_password($tmp[1],$checksum))) && ($_SESSION['master'] == "YES") )
				{
				    $old_password = $pass;
				}
				
			/* --- PASSWORD OVERKILL *SPECIAL FOR Hendrik Reimers* --- ENDE --- */
			

            //Passwörter korrekt?
            if ( ($old_password == $pass) && ($new_password == $password_phrase) )
            {
                //Basis PluginID
				$query     = "SELECT id FROM ".$this->prefix."plugins WHERE name = 'base'";
                $result    = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Passwort konnte nicht geändert werden",mysql_error())); # Ausführen
                $plugin_id = mysql_result($result,0,"id");
                mysql_free_result($result);
                
                //passwort ändern
                $query = "UPDATE ".$this->prefix."settings SET value = '".$new_password."' WHERE `key` = 'pass' AND plugin_id = '".$plugin_id."'";
                @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Passwort konnte nicht geändert werden",mysql_error())); # Ausführen
                
                return true;
            }
            else
            {
                return false;
            }
        }
    
    /* --- Konfiguration --- ende --- */
    
    
    
    /* --- Auslesen --- start --- */
    
        //Lädt den ersten Menüpunkt bzw. die ID die gefunden wird mit parten-id 0
        function get_default_id()
        {
            //Abfrage vorbereiten
            $query  = "SELECT id FROM ".$this->prefix."items WHERE parent_id = '0' AND is_visible = '1' AND is_active = '1' ORDER BY ".$this->order." LIMIT 0,1";
            $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Default ID laden",mysql_error())); # Ausführen
            $id     = @mysql_result($result,0,"id");
            mysql_free_result($result);
            return $id;
        }
    
        //Überpüft ob es sich bei dem Menüpunkt um eine Weiterleitung handelt
        function redirect_check($id)
        {
			//ID check
			if ( !preg_match("=^[0-9]{1,}$=",$id) ) {
				die("Wrong ID");
			}

            //Abfrage vorbereiten
            $query  = "SELECT id FROM ".$this->prefix."redirects WHERE item_id = '".$id."'";
            $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim ID laden",mysql_error())); # Ausführen
            $check  = @mysql_result($result,0,"id");
            mysql_free_result($result);

            //Ergebnis prüfen
            if ( $check > "0" )
            {
                return true;
            }
            else return false;
        }
	
	//Prueft ob ein Menuepunkt ein Plugin ist
	function plugin_check($id) 
	{
		//ID check
		if ( !preg_match("=^[0-9]{1,}$=",$id) ) {
			die("Wrong ID");
		}

	    //Abfrage
	    $query  = "SELECT plugin_id FROM ".$this->prefix."items WHERE id = '".$id."'";
	    $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim ID laden",mysql_error())); # Ausführen
	    
	    if ( mysql_num_rows($result) > 0 ) {
		$retval = mysql_result($result,0,"plugin_id");
		return $retval;
	    } else return false;
	}
	
	//Gibt den Plugin Namen zu einer PluginID zurueck
	function load_pluginname($plugin_id)
	{
		//ID check
		if ( !preg_match("=^[0-9]{1,}$=",$plugin_id) ) {
			die("Wrong ID");
		}

	    $query = "SELECT name FROM ".$this->prefix."plugins WHERE id = '".$plugin_id."'";
	    $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim ID laden",mysql_error())); # Ausführen
	    
	    if ( mysql_num_rows($result) > 0 ) {
		$retval = mysql_result($result,0,"name");
		return $retval;
	    } else return false;
	}
	
	//listet alle verfuegbaren plugins auf
	function load_plugins() 
	{
	    $query  = "SELECT id,title FROM ".$this->prefix."plugins WHERE name != 'base' AND name != 'backup'";
	    $result = mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim ID laden",mysql_error())); # Ausführen
	    
	    if ( mysql_num_rows($result) > 0 ) {
		while ( $row = mysql_fetch_array($result,MYSQL_ASSOC) ) {
		    $row["title"] = base64_decode($row["title"]);
		    $retval[] = $row;
		}
		
		mysql_free_result($result);
		return $retval;
	    } else return false;
	}
        
        //Lädt eine Weiterleitung eines menüpunktes
        function load_redirect($id,$show_inactive = "0")
        {
		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$id) ) {
			    die("Wrong ID");
		    }

            //Nur aktive inhalte zeigen
            $item_data = $this->load_item_data($id);

            if ( ($item_data["is_active"] == "1") || ($show_inactive == "1") )
            {
                //Abfrage vorberiten
                $query     = "SELECT redirect FROM ".$this->prefix."redirects WHERE item_id = '".$id."'";
                $result    = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Redirect laden",mysql_error())); # Ausführen
                $redirect  = @mysql_result($result,0,"redirect");
                mysql_free_result($result);
            } else $redirect = base64_encode("index.php");
            
            return $redirect;
        }
        
        //Lädt den Text zu einem Menüpunkt
        function load_content($id,$show_inactive = "0")
        {
		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$id) ) {
			    die("Wrong ID");
		    }

            //Nur aktive inhalte zeigen
            $item_data = $this->load_item_data($id);
            
            if ( ($item_data["is_active"] == "1") || ($show_inactive == "1") )
            {
                //Abfrage
                $query     = "SELECT content FROM ".$this->prefix."contents WHERE item_id = '".$id."'";
                $result    = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Content laden",mysql_error())); # Ausführen
                $content   = @mysql_result($result,0,"content");
                mysql_free_result($result);
            }
            else $content = "";

            return $content;
        }
        
        //Gibt den Inhalt des Menues zurueck
        function load_menue($parent_id,$show_invis = "0")
        {
		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$parent_id) ) {
			    die("Wrong ID");
		    }

            //Abfrage
            $query  = "SELECT * FROM ".$this->prefix."items WHERE parent_id = '".$parent_id."' ORDER BY ".$this->order;
            $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Men&uuml; laden",mysql_error()));

            //Verarbeiten
            if ( $result )
            {
                while ( $row = mysql_fetch_array($result,MYSQL_ASSOC) )
                {
                    if ( ($row["is_visible"] == "1") || ($show_invis == "1") )
                    {
					    //Herausfinden ob es ein manueller url ist
						$query = "SELECT redirect,is_manual FROM ".$this->prefix."redirects WHERE item_id = '".$row['id']."'";
						$red_result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Men&uuml; laden",mysql_error()));
						$url = @mysql_result($red_result,0,"redirect");
						$is_manual = @mysql_result($red_result,0,"is_manual");

						if ( $is_manual == "1" )
						{
						    $row["url"] = $url;
							$row["is_manual"] = $is_manual;
						}
						
						mysql_free_result($red_result);
						unset($url);
						unset($is_manual);
						
					    //Ergebnis speichern
                        $retval[] = $row;
                    }
                }
                return $retval;
            }
            else return false;
        }
        
        //Laedt das darauffolgende Menue und anschliessend Rekursiv die Menues bis zur Quelle
        function load_rek_menues($current_id)
        {
		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$current_id) ) {
			    die("Wrong ID");
		    }

            //Das darauf folgende Menue laden
            $tmp = $this->load_menue($current_id);
            
            //Nur speichern wenn es ein untermenue gibt
            if ( $tmp )
            {
                $ret[] = $this->load_menue($current_id);
            }
            
            //Die erste ElternID holen, als Kontrolle ob man nicht bereits ganz oben ist
            $query  = "SELECT parent_id,id,title FROM ".$this->prefix."items WHERE id = '".$current_id."'";
            $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Men&uuml; laden",mysql_error()));
                                    
            //Wenn es nicht die Quelle ist dann weiter suchen
            if ( @mysql_result($result,0,"parent_id") <> "0" )
            {
                //Ergebnis freigeben
                @mysql_free_result($result);
                
                //Position sichern
                $parent_id = $current_id;
                
                while ( $parent_id <> "0" )
                {
                    //Die ParentID des aktuellen Menues sammeln
                    $query  = "SELECT parent_id,title,id FROM ".$this->prefix."items WHERE id = '".$parent_id."'";
                    $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Men&uuml; laden",mysql_error()));
                    $parent_id = mysql_result($result,0,"parent_id");

                    //Das Menue zu dem aktuellen Stamm laden
                    $ret[] = $this->load_menue($parent_id);

                    //Position sichern
                    $position[] = array("id"    => mysql_result($result,0,"id"),
                                        "title" => mysql_result($result,0,"title"));
                    
                    //Ergebnis freigeben
                    mysql_free_result($result);
                }
            }
            else
            {
                //Position sichern
                $position[] = array("id"    => @mysql_result($result,0,"id"),
                                    "title" => @mysql_result($result,0,"title"));
                                        
                //Ergebnis freigeben
                @mysql_free_result($result);
                
                //Hauptmenue laden
                $ret[] = $this->load_menue(0);
            }
            
            //Damit die Tiefe stimmt alles mal umdrehen
            $retval["items"]    = array_reverse($ret);
            $retval["position"] = ( $position ) ? array_reverse($position) : "";

            //Ergebnis liefern
            return $retval;
        }
        
        //Ließt die ParentID des angegebenen Menü Punktes aus
        function get_parent($id)
        {
		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$id) ) {
			    die("Wrong ID");
		    }

	    	//Abfrage
		    $query  = "SELECT parent_id FROM ".$this->prefix."items WHERE id = '".$id."'";
		    $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim ParentID laden",mysql_error()));
			
		    //Ergebnis
		    if ( $result )
		    {
				$retval = mysql_result($result,0,"parent_id");
				mysql_free_result($result);
		    }
		    else
		    {
		        $retval = "0";
		    }
	
		    return $retval;
        }
        
        //Lädt Daten von einem einzelnem Item
        function load_item_data($id)
        {
		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$id) ) {
			    die("Wrong ID");
		    }

            //Abfrage
            $query = "SELECT * FROM ".$this->prefix."items WHERE id = '".$id."'";
            $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Item Data konnte nicht geladen werden",mysql_error()));
            
            if ( $result )
            {
                $retval["id"]         = @mysql_result($result,0,"id");
                $retval["parent_id"]  = @mysql_result($result,0,"parent_id");
                $retval["priority"]   = @mysql_result($result,0,"priority");
                $retval["is_visible"] = @mysql_result($result,0,"is_visible");
                $retval["is_active"]  = @mysql_result($result,0,"is_active");
                $retval["title"]      = @mysql_result($result,0,"title");
                
                mysql_free_result($result);
                return $retval;
            }
            else return false;
        }
    
    /* --- Auslesen --- ende --- */
    
    
    
    /* --- Hinzufügen --- start --- */
    
		//Fügt einen URL Menüpunkt hinzu
		function add_redirect($parent_id,$title,$url,$priority = "0")
		{

		    if ( !$priority ) { $priority = "0"; }
		    
		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$parent_id) || (!preg_match("=^[0-9]{1,}$=",$priority)) ) {
			    die("Wrong ID");
		    }

		    $query = "INSERT INTO ".$this->prefix."items (`parent_id`,`title`,`priority`) VALUES ('".$parent_id."','".mysql_real_escape_string($title)."','".$priority."')";
		    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim hinzufuegen",mysql_error()));

		    $item_id = mysql_insert_id();
		    
		    $query = "INSERT INTO ".$this->prefix."redirects (`item_id`,`redirect`) VALUES ('".$item_id."','".mysql_real_escape_string($url)."')";
		    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim hinzufuegen",mysql_error()));
		}
		
		//Fügt einen manuellen URL Menüpunkt hinzu
		function add_manual($parent_id,$title,$url,$priority = "0")
		{

		    if ( !$priority ) { $priority = "0"; }

		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$parent_id) || (!preg_match("=^[0-9]{1,}$=",$priority)) ) {
			    die("Wrong ID");
		    }
		
		    $query = "INSERT INTO ".$this->prefix."items (`parent_id`,`title`,`priority`) VALUES ('".$parent_id."','".mysql_real_escape_string($title)."','".$priority."')";
		    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim hinzufuegen",mysql_error()));

		    $item_id = mysql_insert_id();
		    
		    $query = "INSERT INTO ".$this->prefix."redirects (`item_id`,`redirect`,`is_manual`) VALUES ('".$item_id."','".mysql_real_escape_string($url)."','1')";
		    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim hinzufuegen",mysql_error()));
		}
		
		//Fügt einen Menüpunkt mit Text hinzu
		function add_content($parent_id,$title,$text,$priority = "0")
		{
		    if ( !$priority ) { $priority = "0"; }

		    //ID check
		    if ( (!preg_match("=^[0-9]{1,}$=",$parent_id)) || (!preg_match("=^[0-9]{1,}$=",$priority)) ) {
			    die("Wrong ID");
		    }
		    
		    $query = "INSERT INTO ".$this->prefix."items (`parent_id`,`title`,`priority`) VALUES ('".$parent_id."','".mysql_real_escape_string($title)."','".$priority."')";
		    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim hinzufuegen",mysql_error()));

		    $item_id = mysql_insert_id();

		    $query = "INSERT INTO ".$this->prefix."contents (`item_id`,`content`) VALUES ('".$item_id."','".mysql_real_escape_string($text)."')";
		    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim hinzufuegen",mysql_error()));

		}

		//Fügt einen Menüpunkt mit Plugin hinzu
		function add_plugin($plugin_id,$parent_id,$title,$priority = "0")
		{
		    if ( !$priority ) { $priority = "0"; }

		    //ID check
		    if ( (!preg_match("=^[0-9]{1,}$=",$plugin_id)) || (!preg_match("=^[0-9]{1,}$=",$parent_id)) || (!preg_match("=^[0-9]{1,}$=",$priority)) ) {
			    die("Wrong ID");
		    }
		    
		    $query = "INSERT INTO ".$this->prefix."items (`plugin_id`,`parent_id`,`title`,`priority`) VALUES ('".$plugin_id."','".$parent_id."','".mysql_real_escape_string($title)."','".$priority."')";
		    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim hinzufuegen",mysql_error()));

		    return mysql_insert_id();
		}
    
    /* --- Hinzufügen --- ende --- */
    
    
    
    /* --- Löschen --- start --- */
    
        //Löscht einen Menüpunkt mit untermenüs
        function delete_menue($id)
        {
		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$id) ) {
			    die("Wrong ID");
		    }

            //SUBFUNKTION START
            //Lädt alle Untermenüs von einem Menüpunkt
            function load_down_menues($id,$IDs = array(),$table_prefix)
            {
                //Eltern ID
                $parent_id = $id;
                
                //Abfrage
                $query = "SELECT id FROM ".$table_prefix."items WHERE parent_id = '".$id."'";

                //Ergebnis
                $result = @mysql_query($query);
                
                //Speichern
                while ( $row = mysql_fetch_array($result) )
                {
                    if ( $row["id"] )
                    {
                        //ID speichern
                        $IDs[] = $row["id"];
                        
                        //Dessen unter IDs abfragen und hinzufügen
                        $IDs = load_down_menues($row["id"],$IDs,$table_prefix);
                    }
                }
                
                //Freigabe
                mysql_free_result($result);
                
                //Ausliefern
                return $IDs;
            }
            //SUBFUNKTION ENDE
            
            //Alle Untermenü IDs sammeln
            $IDs = load_down_menues($id,array(),$this->prefix);
            
            //Aktuelle ID noch hinzufügen
            $IDs[] = $id;
            
            //Alles entfernen
            for ( $i = 0; $i < sizeof($IDs); $i++ )
            {
                if ($IDs[$i])
                {
                    //Item löschen
                    $query = "DELETE FROM ".$this->prefix."items WHERE id = '".$IDs[$i]."'";
                    @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Item löschen",mysql_error()));
                    
                    //redirect bzw. inhalt löschen
                    if ( $this->redirect_check($IDs[$i]) )
                    {
                        //Redirect löschen
                        $query = "DELETE FROM ".$this->prefix."redirects WHERE item_id = '".$IDs[$i]."'";
                        @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Item löschen",mysql_error()));
                    }
                    else if ( !$this->plugin_check($IDs[$i]) )
                    {
                        //Content löschen
                        $query = "DELETE FROM ".$this->prefix."contents WHERE item_id = '".$IDs[$i]."'";
                        @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Item löschen",mysql_error()));
                    }
                }
            }
        }
    
    /* --- Löschen --- ende --- */
    
    
    
    /* --- updates --- start --- */
    
        //Ändert den Titel und die Priorität von einem Item
        function update_item($id,$title,$priority)
        {
		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$id) ) {
			    die("Wrong ID");
		    }

            //Query
            $query = "UPDATE ".$this->prefix."items SET priority = '".$priority."',title = '".$title."' WHERE id = '".$id."'";
            @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Item update",mysql_error()));
        }
	
	//Aendert ein Plugin Menuepunkt
	function update_plugin($id,$plugin_id) {
		    //ID check
		    if ( (!preg_match("=^[0-9]{1,}$=",$id)) || (!preg_match("=^[0-9]{1,}$=",$plugin_id)) ) {
			    die("Wrong ID");
		    }

	   		//Query
            $query = "UPDATE ".$this->prefix."items SET plugin_id = '".$plugin_id."' WHERE id = '".$id."'";
            @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Item update",mysql_error()));
	}
        
        //Ändert den Inhalt
        function update_content($item_id,$text)
        {
		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$item_id) ) {
			    die("Wrong ID");
		    }

            //Query
            $query = "UPDATE ".$this->prefix."contents SET content = '".$text."' WHERE item_id = '".$item_id."'";
            @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Item update",mysql_error()));
        }
        
        //Ändert die Weiterleitung
        function update_redirect($item_id,$redirect)
        {
		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$item_id) ) {
			    die("Wrong ID");
		    }

            //Query
            $query = "UPDATE ".$this->prefix."redirects SET redirect = '".$redirect."' WHERE item_id = '".$item_id."'";
            @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Item update",mysql_error()));
        }
		
		//Ändert den manuellen Link
        function update_manual($item_id,$redirect)
        {
		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$item_id) ) {
			    die("Wrong ID");
		    }

            //Query
            $query = "UPDATE ".$this->prefix."redirects SET redirect = '".$redirect."' WHERE item_id = '".$item_id."'";
            @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim Item update",mysql_error()));
        }
        
        //Ändert die Eigenschaften
        function update_attributes($id,$priority,$is_visible,$is_active)
        {
		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$id) ) {
			    die("Wrong ID");
		    }

            //Query
            $query = "UPDATE ".$this->prefix."items SET priority = '".$priority."', is_active = '".$is_active."', is_visible = '".$is_visible."' WHERE id = '".$id."'";
            @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim ändern der Attribute",mysql_error()));
        }

        //Verschiebt einen Menüpunkt
        function move_item($id,$parent_id)
        {
		    //ID check
		    if ( !preg_match("=^[0-9]{1,}$=",$id) ) {
			    die("Wrong ID");
		    }

            //Query
            $query = "UPDATE ".$this->prefix."items SET parent_id = '".$parent_id."' WHERE id = '".$id."'";
            @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim verschieben",mysql_error()));
        }
    
    /* --- updates --- ende --- */

    /* --- Rewrite Engine --- start --- */

	function loadRewId($title) {
	    //Abfrage
	    $query  = "SELECT id,title FROM ".$this->prefix."items";
	    $result = @mysql_query($query) or die(_sqlerror(__FILE__,__LINE__,"Fehler beim aufloesen des Rewrites",mysql_error()));	    

	    while ( $row = mysql_fetch_array($result,MYSQL_ASSOC) ) {
			if ( strtolower(umlRewEncode(trim(base64_decode($row["title"])))) == $title ) {
			    mysql_free_result($result);
			    return $row["id"];
			}
	    }
	}

    /* --- Rewrite Engine --- start --- */
}

?>
