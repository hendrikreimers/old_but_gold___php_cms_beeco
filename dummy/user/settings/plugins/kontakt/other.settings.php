<?

//Betreffzeile f�r die Nachricht
$settings["kontakt"]["subject"] = 'Nachricht �ber "'.$_SERVER['HTTP_HOST'].'" am '.date("d.m.Y");

//Regul�re Ausdr�cke zum �berpr�fen der Eingaben
$settings["kontakt"]["regexp"]["default"] = '^([a-z0-9\ \._\-`�\'()\/&]{1,})$';
$settings["kontakt"]["regexp"]["email"]   = '^([a-z0-9.!?#$&%*+-/\=~^_`\'|{}]{1,64})@([a-z0-9-.]{4,255})$';
$settings["kontakt"]["regexp"]["message"] = '^(.*)$';

?>