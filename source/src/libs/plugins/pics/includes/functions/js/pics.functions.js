
//Temp URL of the Image
var imgUrl = false;

//Sends the AJAX Request to the Callback Function
function loadImg(url) {
	//Temp. save the url
	imgUrl = url;

	//Checks whether the Template loaded
	if ( !document.getElementById('picsDetailTransparentImageAJAX') ) {
		//Check whether the Template loaded
		loadTpl(url);
	} else {		
		//Display the Background
		displayBackground("0");

		//Send the Image Request
		sendGETRequest(url,"callback_showPic");
	}
}

//Close the Layer and go back to the normal Site
function hidePic() {
	//The Background
	var bgImg = document.getElementById('picsDetailTransparentImageAJAX');
	bgImg.style.display = 'none';
	
	//The Background
	var indTag = document.getElementById('picsDetailIndicatorAJAX');
	indTag.style.display = 'none';
	
	//The Data
	var imgDivTag = document.getElementById('picsDetailImgLayerAJAX');
	imgDivTag.style.display = 'none';

	//The Image
	var imgTag = document.getElementById('picsDetailImgAJAX');
	imgTag.src = '';
}

//Checks whether the Tpl currently loaded
function loadTpl(url) {
	//Load the Template into the body
	if ( !document.getElementById('picsDetailTransparentImageAJAX') ) {
		//Modify the URL
		var result = url.match(/ajaxDetails[0-9]+\.html/g);
		if (result) {
			url = url.replace(result[0],"ajaxTpl.html");
		} else {
			url = url+'&init=ajaxTpl';
		}

	    sendGETRequest(url,"callback_loadTpl");
	}
}

//IE Workaround
function getInnerSize() {
	if ( (window.innerWidth != undefined) && (window.innerHeight != undefined) && (window.innerWidth > 0) && (window.innerHeight > 0) ) {
	    var innerWidth  = window.innerWidth;
	    var innerHeight = window.innerHeight;
	} else if ( (document.body.clientWidth != undefined) && (document.body.clientHeight != undefined) && (document.body.clientWidth > 0) && (document.body.clientHeight > 0) ) {
	    var innerWidth  = document.body.clientWidth;
	    var innerHeight = document.body.clientHeight;
	} else if ( (document.documentElement.clientWidth != undefined) && (document.documentElement.clientHeight != undefined) && (document.documentElement.clientWidth > 0) && (document.documentElement.clientHeight > 0) ) {
	    var innerWidth  = document.documentElement.clientWidth;
	    var innerHeight = document.documentElement.clientHeight;
	}

	var retval = Array();
	retval["innerHeight"] = innerHeight;
	retval["innerWidth"]  = innerWidth;

	return retval;
}

function getScrollPos() {
    var retval = Array();

    if ( (window.pageXOffset != undefined) && (window.pageXOffset > 0) ) {
		retval["x"] = window.pageXOffset;
    } else if ( (document.body.scrollLeft != undefined) && (document.body.scrollLeft > 0) ) {
		retval["x"] = document.body.scrollLeft;
    } else if ( (document.documentElement.scrollLeft != undefined) && (document.documentElement.scrollLeft > 0) ) {
		retval["x"] = document.documentElement.scrollLeft;
    } else {
		retval["x"] = 0;
    }

    if ( (window.pageYOffset != undefined) && (window.pageYOffset > 0) ) {
		retval["y"] = window.pageYOffset;
    } else if ( (document.body.scrollTop != undefined) && (document.body.scrollTop > 0) ) {
		retval["y"] = document.body.scrollTop;
    } else if ( (document.documentElement.scrollTop != undefined) && (document.documentElement.scrollTop > 0) ) {
		retval["y"] = document.documentElement.scrollTop;
    } else {
		retval["y"] = 0;
    }

    return retval;
}

//Center the Image
function centerImage(width,height) {
	var imgDivTag   = document.getElementById('picsDetailImgLayerAJAX'); //The Data
	var innerSize   = getInnerSize();
	var scrollPos   = getScrollPos();

	imgDivTag.style.left = (innerSize["innerWidth"]/2-width/2)+scrollPos["x"]+'px';   // X-Position
	imgDivTag.style.top  = (innerSize["innerHeight"]/2-height/2)+scrollPos["y"]+'px'; // Y-Position
}

//Displays the Background
function displayBackground (loadImgON) {
	var bgImg            = document.getElementById('picsDetailTransparentImageAJAX'); //The Background
	var indTag           = document.getElementById('picsDetailIndicatorAJAX'); //The Indicator Image
	var divBodyTag       = document.getElementById('picsBodyLayerAJAX');
	var innerSize        = getInnerSize();
	var scrollPos        = getScrollPos();

  	if ( innerSize["innerHeight"] >= divBodyTag.offsetHeight ) {
	    bgImg.style.height = innerSize["innerHeight"]+'px';
	} else {
	    bgImg.style.height = divBodyTag.offsetHeight+'px';
	}

	indTag.style.left = (innerSize["innerWidth"]/2-indTag.width/2)+scrollPos["x"]+'px';
	indTag.style.top  = (innerSize["innerHeight"]/2-indTag.height/2)+scrollPos["y"]+'px';

	if ( loadImgON == "1" ) {
		loadImg(imgUrl);
		imgUrl = false;
	} else {
		bgImg.style.display = 'block'; //The Style
		fadeInObj('picsDetailTransparentImageAJAX',70,80);
		indTag.style.display = 'block'; //The Style	
	}
}

function fadeInObj(objName,opacity,maxOpacity) {
	if ( opacity < maxOpacity ) {
		var obj = document.getElementById(objName).style;
		//bgObj.display = 'block';
		if ( window.navigator.userAgent.indexOf("MSIE ") > -1 ) {
			obj.filter = 'Alpha(opacity=' + opacity + ')';
		} else {
			obj.MozOpacity = opacity + '%';
			obj.opacity = '0.' + ( (opacity < 10) ? ('0' + opacity) : opacity );
		}
		
		opacity = opacity + 10;

		window.setTimeout("fadeInObj('" + objName + "'," + opacity + "," + maxOpacity + ")", 30);
	}
}

function swapImage() {
	var imgDivTag = document.getElementById('picsDetailImgLayerAJAX'); //The Data
	var indTag      = document.getElementById('picsDetailIndicatorAJAX'); //The Data

	imgDivTag.style.display = 'block';
	indTag.style.display = 'none';
	
	fadeInObj('picsDetailImgLayerAJAX',0,100);
}

// Setzt die Stylesheets
function setCSS() {
	
	if ( window.navigator.userAgent.indexOf("MSIE ") > -1 ) {
		with ( document.getElementById('picsDetailTransparentImageAJAX').style ) {
			filter = 'Alpha(opacity=0)';
		}
		with ( document.getElementById('picsDetailImgLayerAJAX').style ) {
			filter = 'Alpha(opacity=0)';
		}
	}

	with ( document.getElementById('picsDetailTransparentImageAJAX').style ) {
		backgroundColor = '#000000';
		display         = 'none';
		width           = '100%';
		height          = '100%';
	    position        = 'absolute';
		top             = '0px';
		left            = '0px';
		MozOpacity      = '0%';
		opacity         = '0';
	}
	
	with ( document.getElementById('picsDetailImgLayerAJAX').style ) {
	    display         = 'none';
    	position        = 'absolute';
	    left            = '0px';
	    top             = '0px';
	    backgroundColor = '#FFFFFF';
	    padding         = '10px';
		MozOpacity      = '0%';
		opacity         = '0';
	}
	
	with ( document.getElementById('picsDetailImgAJAX').style ) {
		border = '1px solid #000000';
	}
	
	with ( document.getElementById('picsDetailImgTitleAJAX').style ) {
		fontWeight = 'bold';
	}
	
	with ( document.getElementById('picsDetailIndicatorAJAX').style ) {
		height   = '100px';
	    width    = '100px';
	    position = 'absolute';
	    top      = '50%';
	    left     = '45%';
	}
	
	with ( document.getElementById('picsBodyLayerAJAX').style ) {
		position = 'absolute';
	    width    = '100%';
	    top      = '0px';
	    left     = '0px';
	}
}
