<?

/*

   BESCHREIBUNG:

   Enthält die Konfigurationsdaten um den Zugriff auf eine MySQL 
   Datenbank zu ermöglichen. Des weiteren den Tabellen prefix 
   (table_prefix) um mehrere MiniCMS Systeme auf einer Datenbank 
   auseinander zu halten. Dazu die Sortierung der einzelnen 
   Menüpunkte (order_by).

*/

$settings["mysql"]["host"]         = 'localhost';      # Host

$settings["mysql"]["user"]         = 'tester';         # Benutzername
$settings["mysql"]["pass"]         = 'test';           # Kennwort

$settings["mysql"]["db"]           = 'phptestings';    # Datenbank

$settings["mysql"]["table_prefix"] = 'beeco_';         # Tabellen Prefix (Standard: minicmsSE_)
$settings["mysql"]["order_by"]     = 'priority,title'; # Sortierung der Menüs (Standard: priority,title)

?>
