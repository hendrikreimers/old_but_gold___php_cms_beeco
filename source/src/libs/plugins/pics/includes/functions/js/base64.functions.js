function utf8t2d(t)
{
    t = t.replace(/\r\n/g,"\n");
    var d = new Array;
    var test = String.fromCharCode(237);
    if (test.charCodeAt(0) < 0) 
	for(var n=0; n<t.length; n++) {
	    var c=t.charCodeAt(n);
	    if (c>0)
		d[d.length]= c;
	    else {
		d[d.length]= (((256+c)>>6)|192);
		d[d.length]= (((256+c)&63)|128);}
	    } else
		for(var n=0; n<t.length; n++) {
		    var c=t.charCodeAt(n);

		    // all the signs of asci => 1byte
		    if (c<128)
			d[d.length]= c;

		    // all the signs between 127 and 2047 => 2byte
		    else if((c>127) && (c<2048)) {
			d[d.length]= ((c>>6)|192);
			d[d.length]= ((c&63)|128);}
		    // all the signs between 2048 and 66536 => 3byte
		    else {
			d[d.length]= ((c>>12)|224);
			d[d.length]= (((c>>6)&63)|128);
			d[d.length]= ((c&63)|128);}
		}
    return d;
}

function utf8d2t(d) {
    var r = new Array;
    var i=0;
    
    while(i<d.length) {
	if (d[i]<128) {
	    r[r.length]= String.fromCharCode(d[i]); i++;}
	else if((d[i]>191) && (d[i]<224)) {
	    r[r.length]= String.fromCharCode(((d[i]&31)<<6) | (d[i+1]&63)); i+=2;}
	else {
	    r[r.length]= String.fromCharCode(((d[i]&15)<<12) | ((d[i+1]&63)<<6) | (d[i+2]&63)); i+=3;}
    }

    return r.join("");
}

function b64arrays() {
    var b64s='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    b64 = new Array();
    f64 =new Array();

    for (var i=0; i<b64s.length ;i++) {
	b64[i] = b64s.charAt(i);
	f64[b64s.charAt(i)] = i;
    }
}

function b64d2t(d) {
    var r = new Array;
    var i = 0; var dl = d.length;

    // this is for the padding
    if ((dl%3) == 1) {
	d[d.length] = 0; d[d.length] = 0;}

    if ((dl%3) == 2)
	d[d.length] = 0;

    // from here conversion
    while (i<d.length) {
	r[r.length] = b64[d[i]>>2];
	r[r.length] = b64[((d[i]&3)<<4) | (d[i+1]>>4)];
	r[r.length] = b64[((d[i+1]&15)<<2) | (d[i+2]>>6)];
	r[r.length] = b64[d[i+2]&63];

	if ((i%57)==54)
		r[r.length] = "\n";

	i+=3;
    }

    // this is again for the padding
    if ((dl%3) == 1)
	r[r.length-1] = r[r.length-2] = "=";

    if ((dl%3) == 2)
	r[r.length-1] = "=";
    
    // we join the array to return a textstring
    var t=r.join("");

    return t;
}

function b64t2d(t) {
    var d=new Array; var i=0;

    // here we fix this CRLF sequenz created by MS-OS; arrrgh!!!
    t=t.replace(/\n|\r/g,""); t=t.replace(/=/g,"");

    while (i<t.length) {
	d[d.length] = (f64[t.charAt(i)]<<2) | (f64[t.charAt(i+1)]>>4);
	d[d.length] = (((f64[t.charAt(i+1)]&15)<<4) | (f64[t.charAt(i+2)]>>2));
  	d[d.length] = (((f64[t.charAt(i+2)]&3)<<6) | (f64[t.charAt(i+3)]));
  	i+=4;
    }

    if (t.length%4 == 2)
	d = d.slice(0, d.length-2);

    if (t.length%4 == 3)
	d = d.slice(0, d.length-1);

    return d;
}
