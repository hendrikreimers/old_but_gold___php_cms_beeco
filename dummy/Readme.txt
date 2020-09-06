
	Beeco - Content Management System
		von Hendrik Reimers

	www.beeco.de
	www.kern23.de





	Version: 	 Beeco ONE (v1)
	Interne Version: MiniCMS v26

	INSTALLATIONS ANLEITUNG
	Das Dummy Paket stellt wie bei Typo3 das Design
	und spezielle Konfigurationen zur Verfügung.
	Das Paket einfach in den gewünschten Ordner kopieren
	in dem die neue Beeco Seite laufen soll.

	Danach die Sources bzw. den ordner "src" ebenfalls
	dort reinkopieren oder verlinken (symbolic link).

	Im Browser die Seite aufrufen anhängend /install/
	und den installations anweisungen folgen.

	Alternativ kann auch die Hilfe benutzt werden über
	http://www.ihreDomain.de/dummy/manual/

	Das geht aber erst wenn die SRC (Sources) vorhanden sind
	
	-----------------------------------------------------------
	
	Sollten die Sources verlinkt sein ber Symbolic Links oder so,
	muss unter umstnden die Apache Konfiguration angepasst werden
	je nach PHP Konfiguration.
	
	Sollte PHP nicht im Safe Mode sein und keine Open Basedir Restriction
	haben, reicht eine HTACCESS die der verlinkung folgt (FollowSymLinks)
	siehe htaccess-sample-2
	
	Im anderen Fall muss ueber den VirtualHost oder in der PHP.ini
	der OpenBasedir sowie der Include Pfad auf die Quellen und evtl.
	auf das Dummy Paket verweisen (sofern teile des dummys ebenfalls
	verlinkt worden sind).
	
	Ein Beispiel fr einen Virtual Host liegt bei vhost-sample
	Wenn die Rechte der User nicht bereinstimmen muss safe_mode leider aus