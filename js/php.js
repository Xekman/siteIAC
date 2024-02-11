function chr(ascii) 
{
	return String.fromCharCode(ascii);
}

function explode( delimiter, string ) 
{
	var emptyArray = { 0: '' };

	if ( arguments.length != 2
		|| typeof arguments[0] == 'undefined'
		|| typeof arguments[1] == 'undefined' )
	{
		return null;
	}

	if ( delimiter === ''
		|| delimiter === false
		|| delimiter === null )
	{
		return false;
	}

	if ( typeof delimiter == 'function'
		|| typeof delimiter == 'object'
		|| typeof string == 'function'
		|| typeof string == 'object' )
	{
		return emptyArray;
	}

	if ( delimiter === true ) {
		delimiter = '1';
	}

	return string.toString().split ( delimiter.toString() );
}

function strpos(haystack, needle, offset)
{
	var i = haystack.indexOf( needle, offset );
	return i >= 0 ? i : false;
}

function substr(str, index, len)
{
	return str.substr(index, len);
}

function preg_match(regexp, str)
{
	var rexp = new RegExp(substr(regexp, 1, regexp.length - 2));
	return rexp.test(str);
}

function base_convert(number, frombase, tobase) 
{
	return parseInt(number + '', frombase | 0).toString(tobase | 0);
}

function str_replace (search, replace, subject ) 
{
	if(!(replace instanceof Array)){
		replace=new Array(replace);
		if(search instanceof Array){
			while(search.length>replace.length){
				replace[replace.length]=replace[0];
			}
		}
	}
	if(!(search instanceof Array))search=new Array(search);
	while(search.length>replace.length){
		replace[replace.length]='';
	}
	if(subject instanceof Array){
		for(k in subject){
			subject[k]=str_replace(search,replace,subject[k]);
		}
		return subject;
	}
	for(var k=0; k<search.length; k++){
		var i = subject.indexOf(search[k]);
		while(i>-1){
			subject = subject.replace(search[k], replace[k]);
			i = subject.indexOf(search[k],i);
		}
	}
	return subject;
}

function strlen(str) 
{
	return str.length;
}

function strtolower(str) 
{
	return str.toLowerCase();
}

function strtoupper(str) 
{
	return str.toUpperCase();
}

function strtr(str, from, to) 
{
    if (typeof from === 'object') {
    	var cmpStr = '';
    	for (var j=0; j < str.length; j++){
    		cmpStr += '0';
    	}
    	var offset = 0;
    	var find = -1;
    	var addStr = '';
        for (fr in from) {
        	offset = 0;
        	while ((find = str.indexOf(fr, offset)) != -1){
				if (parseInt(cmpStr.substr(find, fr.length)) != 0){
					offset = find + 1;
					continue;
				}
				for (var k =0 ; k < from[fr].length; k++){
					addStr += '1';
				}
				cmpStr = cmpStr.substr(0, find) + addStr + cmpStr.substr(find + fr.length, cmpStr.length - (find + fr.length));
				str = str.substr(0, find) + from[fr] + str.substr(find + fr.length, str.length - (find + fr.length));
				offset = find + from[fr].length + 1;
				addStr = '';
        	}
        }
        return str;
    }

	for(var i = 0; i < from.length; i++) {
		str = str.replace(new RegExp(from.charAt(i),'g'), to.charAt(i));
	}

    return str;
}

function is_numeric(s) 
{
	if (!isNaN(s))
	{
		return true;
	}else{
		return false;
	}
}

function is_numericaff(s) 
{
	var result = is_numeric(s);
	if (result)
	{
		if (parseInt(s) >= 0)
		{
			return true;
		}else{
			return false;
		}
	}
	return result;
}

function ltrim(s) 
{
	var ptrn = /\s*((\S+\s*)*)/;
	return s.replace(ptrn, "$1");
}

function rtrim(s) 
{
	var ptrn = /((\s*\S+)*)\s*/;
	return s.replace(ptrn, "$1");
}

function trim(s) 
{
	return ltrim(rtrim(s));
}

function rand2(min, max)
{
    var range = max - min + 1;
    var n = Math.floor(Math.random() * range) + min;
    return n;
}

function is_array(input)
{
    return typeof(input)=='object'&&(input instanceof Array);
}

function str_pad( input, pad_length, pad_string, pad_type ) {	// Pad a string to a certain length with another string
	// 
	// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// + namespaced by: Michael White (http://crestidg.com)

	var half = '', pad_to_go;

	var str_pad_repeater = function(s, len){
			var collect = '', i;

			while(collect.length < len) collect += s;
			collect = collect.substr(0,len);

			return collect;
		};

	if (pad_type != 'STR_PAD_LEFT' && pad_type != 'STR_PAD_RIGHT' && pad_type != 'STR_PAD_BOTH') { pad_type = 'STR_PAD_RIGHT'; }
	if ((pad_to_go = pad_length - input.length) > 0) {
		if (pad_type == 'STR_PAD_LEFT') { input = str_pad_repeater(pad_string, pad_to_go) + input; }
		else if (pad_type == 'STR_PAD_RIGHT') { input = input + str_pad_repeater(pad_string, pad_to_go); }
		else if (pad_type == 'STR_PAD_BOTH') {
			half = str_pad_repeater(pad_string, Math.ceil(pad_to_go/2));
			input = half + input + half;
			input = input.substr(0, pad_length);
		}
	}

	return input;
}

function base64_encode( data ) 
{	// Encodes data with MIME base64
	// 
	// +   original by: Tyler Akins (http://rumkin.com)
	// +   improved by: Bayron Guevara

	var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
	var o1, o2, o3, h1, h2, h3, h4, bits, i=0, enc='';

	do { // pack three octets into four hexets
		o1 = data.charCodeAt(i++);
		o2 = data.charCodeAt(i++);
		o3 = data.charCodeAt(i++);

		bits = o1<<16 | o2<<8 | o3;

		h1 = bits>>18 & 0x3f;
		h2 = bits>>12 & 0x3f;
		h3 = bits>>6 & 0x3f;
		h4 = bits & 0x3f;

		// use hexets to index into b64, and append result to encoded string
		enc += b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
	} while (i < data.length);

	switch( data.length % 3 ){
		case 1:
			enc = enc.slice(0, -2) + '==';
		break;
		case 2:
			enc = enc.slice(0, -1) + '=';
		break;
	}

	return enc;
}

function base64_decode( data ) 
{	// Decodes data encoded with MIME base64
	// 
	// +   original by: Tyler Akins (http://rumkin.com)


	var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
	var o1, o2, o3, h1, h2, h3, h4, bits, i=0, enc='';

	do {  // unpack four hexets into three octets using index points in b64
		h1 = b64.indexOf(data.charAt(i++));
		h2 = b64.indexOf(data.charAt(i++));
		h3 = b64.indexOf(data.charAt(i++));
		h4 = b64.indexOf(data.charAt(i++));

		bits = h1<<18 | h2<<12 | h3<<6 | h4;

		o1 = bits>>16 & 0xff;
		o2 = bits>>8 & 0xff;
		o3 = bits & 0xff;

		if (h3 == 64)	  enc += String.fromCharCode(o1);
		else if (h4 == 64) enc += String.fromCharCode(o1, o2);
		else			   enc += String.fromCharCode(o1, o2, o3);
	} while (i < data.length);

	return enc;
}