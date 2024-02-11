function encode_utf8( s )
{
  return unescape( encodeURIComponent( s ) );
}

function decode_utf8( s )
{
  return decodeURIComponent( escape( s ) );
}
	
function isLike(search, text)
{
	if (text.indexOf(search) + 1) 
	{
		return true;
	}else{
		return false;
	}
}

function pause(n)
{
	today = new Date()
	today2 = today
	while((today2-today)<=n)
	{
		today2 = new Date()
	}
}

function getClientWidth()
{
  return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientWidth:document.body.clientWidth;  
}

function getClientHeight()
{
  return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientHeight:document.body.clientHeight;
}

function gotoScrollIntoView(id)
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	obj.scrollIntoView(true);
}

function gotoEndPage()
{
	window.scroll(0, document.body.offsetHeight);
}

function gotoBeginPage()
{
	window.scroll(0, 0);
}

function replaceTo(id, find, replace, r) 
{ 
	var obj = document.getElementById(id);
	var b = true;
	if (!obj)
	{
		return;
	}
	var text = obj.value;
	var len = text.length;

	if (len < 1)
	{
		return;
	}
	if (r)
	{
		if (text.indexOf(find) == -1)
		{
			b = false;
		}
	}
	if (b)
	{
		var selection1 = obj.selectionStart;
		var selection2 = obj.selectionEnd;

		obj.value = text.replace(find, replace);

		obj.selectionStart = selection1;
		obj.selectionEnd = selection2;
	}
}

function showhideObject(id) 
{ 
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	if (obj.style.display == 'none') 
	{ 
		obj.style.display = 'block'; 
	}else { 
		obj.style.display = 'none'; 
	} 
}

function showhideTable(id) 
{ 
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	if (obj.style.display == 'none') 
	{ 
		obj.style.display = ''; 
	}else { 
		obj.style.display = 'none'; 
	} 
} 

function hideObject(id) 
{ 
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	obj.style.display = 'none'; 
} 

function hideObjectPos(id, width, height) 
{ 
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	width = width || -1000;
	height = height || -1000;
	obj.style.position = 'absolute'; 
	obj.style.left = width + 'px'; 
	obj.style.top = height + 'px'; 
} 

function getShowObjectState(id) 
{ 
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	if (obj.style.display == 'none')
	{
		return false;
	}else{
		return true;
	}
} 

function showObject(id) 
{ 
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	obj.style.display = 'block'; 
} 

function showObjectPos(id, width, height) 
{ 
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	width = width || 0;
	height = height || 0;
	obj.style.position = 'inherit'; 
	obj.style.left = width + 'px'; 
	obj.style.top = height + 'px'; 
} 

function getObjectPosDisplay(id) 
{ 
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	if (obj.offsetLeft < 0)
	{
		return false;
	}else{
		return true;
	}
} 

function setValue(id, value) 
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	obj.value = value;
}

function getValue(id) 
{
	var obj = document.getElementById(id);
	if (obj)
	{
		return obj.value;
	}
}

function setFormValue(form, name, value) 
{
	document[form][name].value = value;
}

function sendSubmit(form) 
{
	document[form].submit();
}

function setInnerHTML(id, text) 
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	obj.innerHTML = text;
}

function setFocus(id) 
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	obj.focus();
	if (typeof obj.select != 'undefined')
	{
		obj.select();
	}
}

function setSelectRange(id, begin, end) 
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	obj.setSelectionRange(begin, end);
}

function setRangeFocus(id) 
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	var end = obj.value.length;
	obj.setSelectionRange(end, end);
	obj.focus();
}

function setRadio(id)
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	obj.checked = true;
}

function getRadioArrayValue(form, radioindex)
{
	var value = '';
	var obj = document[form][radioindex];
	
	if (!obj)
	{
		return value;
	}
	
	if (typeof obj.length == 'number')
	{
		for (var i = 0; i < obj.length; i++) 
		{
			if (obj[i].checked)
			{
				value = obj[i].value;
				break;
			}
		}
	}else{
		value = obj.value;
	}
	return value;
}

function setCheckBoxValue(id, value)
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	obj.checked=value;
}

function getCheckBoxValue(id)
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	return obj.checked;
}

function setCheckBoxInverseValue(id)
{
	
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	
	if (obj.checked)
	{
		obj.checked=false;
	}else{
		obj.checked=true;
	}
}

function getCheckBoxArrayCount(form, checkbox, value)
{
	var count = 0;
	if (typeof value != 'boolean')
	{
		value = true;
	}
	var obj = document[form][checkbox];
	if (obj)
	{
		if (typeof obj.length == 'number')
		{

			for (var i = 0; i < obj.length; i++) 
			{
				if (obj[i].checked == value)
				{
					count = count + 1;
				}
			}
		}else{
			if (obj.checked == value)
			{
				count = 1;
			}else{
				count = 0;
			}
		}
	}
	return count;
}

function setCheckBoxArrayValue(form, checkbox, value)
{
	var obj = document[form][checkbox];
	if (!obj)
	{
		return;
	}

	if (typeof obj.length == 'number')
	{
		for (var i = 0; i < obj.length; i++) 
		{
			obj[i].checked = value;
		}
	}else{
		obj.checked = value;
	}
}

function setCheckBoxArrayInverseValue(form, checkbox)
{
	var obj = document[form][checkbox];
	if (!obj)
	{
		return;
	}
	if (typeof obj.length == 'number')
	{
		for (var i = 0; i < obj.length; i++) 
		{
			if (obj[i].checked)
			{
				obj[i].checked = false;
			}else{
				obj[i].checked = true;
			}
		}
	}else{
		if (obj.checked)
		{
			obj.checked = false;
		}else{
			obj.checked = true;
		}
	}
}

function getPassword(len)
{
    var len=len?len:14;
    var pass = '';
    var rnd = 0;
    var c = '';
    for (i = 0; i < len; i++) {
        rnd = rand2(0, 2);
        if (rnd == 0) {
            c = String.fromCharCode(rand2(48, 57));
        }
        if (rnd == 1) {
            c = String.fromCharCode(rand2(65, 90));
        }
        if (rnd == 2) {
            c = String.fromCharCode(rand2(97, 122));
        }
        pass += c;
    }
    return pass;
}

function getHref(locValue)
{
	return document.location.href;
}

function setHref(value)
{
	document.location.href = value;
}

function openHref(value)
{
	window.open(value);
}

function addEvent(object, event, handler, useCapture) 
{
	var result = true;
	if (object.addEventListener) 
	{
		object.addEventListener(event, handler, useCapture ? useCapture : false);
	}else{
		if (object.attachEvent) 
		{
			object.attachEvent('on' + event, handler);
		}else{
			result = false;
		}
	}
	return result;
}

function delEvent(object, event, handler) 
{
	var result = true;
	if (object.removeEventListener) 
	{
		object.removeEventListener(event, handler, false);
	}else{
		if (object.detachEvent) 
		{
			object.detachEvent('on' + event, handler);
		}else{
			result = false;
		}
	}
	return result;
}

function playSound(id, src)
{
	setInnerHTML(id, '<![if !IE]><audio autoplay src="' + src + '"></audio><![endif]>');
}

function intToBool(value)
{
	if (value == 0)
	{
		return false;
	}else{
		return true;
	}
}

function boolToInt(value)
{
	if (value == false)
	{
		return 0;
	}else{
		return 1;
	}
}

function getDisabled(id)
{
	var obj = document.getElementById(id);
	if (obj)
	{
		return intToBool(obj.disabled);
	}
}

function setDisabled(id, value)
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	obj.disabled = boolToInt(value);
}

function getSelectText(id)
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}	
	return obj.options[obj.selectedIndex].text
}