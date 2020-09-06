//Load the Image and shows it...
function callback_showPic(xmlDoc) {
	//IMG Data
	var xmlImg = xmlDoc.getElementsByTagName('result').item(0).getElementsByTagName('img').item(0);
	
	//Get the Tags to modify
	var imgTag      = document.getElementById('picsDetailImgAJAX');      //The Image
	var imgTitleTag = document.getElementById('picsDetailImgTitleAJAX'); //The Title
	var imgDescTag  = document.getElementById('picsDetailImgDescAJAX');  //The Description
	
	//Set the Image
	imgTag.src          = xmlImg.firstChild.data;
	imgTag.style.width  = xmlImg.getAttribute('width')+'px';
	imgTag.style.height = xmlImg.getAttribute('height')+'px';
	
	//Modify the Title and Desc
	if ( imgTitleTag ) {
	    imgTitleTag.innerHTML = xmlImg.getAttribute('title');
	}
	if ( imgDescTag ) {
	    imgDescTag.innerHTML = xmlImg.getAttribute('desc');
	}
	
	//Center the Image
	var imgWidth  = xmlImg.getAttribute('width');
	var imgHeight = xmlImg.getAttribute('height');
	centerImage(imgWidth,imgHeight);

	//Preload the Image
	var imgObj = new Image();
	imgObj.src = imgTag.src;
	imgObj.onload = function() { swapImage(); }
}

//Load's the Template into the Body
function callback_loadTpl(xmlDoc) {
    //Select the Body and the Template
    var body = document.getElementsByTagName('body')[0];
    var layer = xmlDoc.getElementsByTagName('result').item(0).getElementsByTagName('tpl').item(0).firstChild.data;

	//Insert
    body.innerHTML = '<div id="picsBodyLayerAJAX">'+body.innerHTML+'</div>'+layer;
    
	setCSS();
	
	//Display the Background with loading the Image
    displayBackground("1");
}
