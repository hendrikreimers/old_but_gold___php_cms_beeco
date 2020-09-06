<?

//Betreffzeile für die Nachricht
$settings["kontakt"]["subject"] = 'Nachricht über "'.$_SERVER['HTTP_HOST'].'" am '.date("d.m.Y");

//Reguläre Ausdrücke zum überprüfen der Eingaben
$settings["kontakt"]["regexp"]["default"] = '^([a-z0-9\ \._\-`´\'()\/&]{1,})$';
$settings["kontakt"]["regexp"]["email"]   = '^([a-z0-9.!?#$&%*+-/\=~^_`\'|{}]{1,64})@([a-z0-9-.]{4,255})$';
$settings["kontakt"]["regexp"]["message"] = '^(.*)$';

?>