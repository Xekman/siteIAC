var waitTimeout = 1000;

function isINN(value)
{
	if (!is_numeric(value))
	{
		return false;
	}
	if (value < 0)
	{
		return false;
	}
	len = strlen(value);
	if (len != 10 && len != 12)
	{
		return false;
	}
	if (len == 10)
	{
		return (value[9] == (((2 * value[0] + 4 * value[1] + 10 * value[2] + 3 * value[3] + 5 * value[4] + 9 * value[5] + 4 * value[6] + 6 * value[7] + 8 * value[8]) % 11) % 10))
	}
	if (len == 12)
	{
		num10 = (((7 * value[0] + 2 * value[1] + 4 * value[2] + 10 * value[3] + 3 * value[4] + 5 * value[5] + 9 * value[6] + 4 * value[7] + 6 * value[8] + 8 * value[9]) % 11) % 10);
		num11 = (((3 * value[0] + 7 * value[1] + 2 * value[2] + 4 * value[3] + 10 * value[4] + 3 * value[5] + 5 * value[6] + 9 * value[7] + 4 * value[8] + 6 * value[9] + 8 * value[10]) % 11) % 10);
		return (value[11] == num11 && value[10] == num10);
	}
}

function isOGRN(value)
{
	var len = strlen(value);
	if (len != 13 && len != 15)
	{
		return false;
	}	
	var k = len - 2;
	var x = len - 1;
	var val = substr(value, 0, x);
	

	
	var ml = val - (Math.floor(val / k) * k);
	ml = ml + '';
	if (ml[strlen(ml) - 1] == substr(value, x, 1))
	{
		return true;
	}else{
		return false;
	}
}	

function isKPP(value)
{
	if (!is_numeric(value))
	{
		return false;
	}
	if (value < 0)
	{
		return false;
	}
	if (strlen(value) != 9)
	{
		return false;
	}
	return true;
}

function isSNILS(value)
{
	if (!is_numeric(value))
	{
		return;
	}
	if (value < 0)
	{
		return;
	}
	if (strlen(value) != 11)
	{
		return;
	}
	var end_num = substr(value, -2, 2);
	var sum = 0;
	for (var i = 1; i < 10; i++)
	{
		sum = sum + value[(i - 1)] * (10 - i);
	}
	if (((sum % 101) % 100) == end_num)
	{
		return true;
	}else{
		return false;
	}
}

function isTemproraryCertificate(value)
{
	if (!is_numeric(value))
	{
		return false;
	}
	if (value < 0)
	{
		return false;
	}
	if (strlen(value) != 9)
	{
		return false;
	}
	return true;
}

function isDate(value)
{
	var date = explode('.', value);
	
	if (date.length != 3)
	{
		return false;
	}
	
	if (date[2].length != 4)
	{
		return false;
	}
	
	var temp = new Date(date[2] + '-' + date[1] + '-' + date[0]);
	
	if (parseInt(temp.valueOf()))
	{
		return true;
	}else{
		return false;
	}
}

function isTime(value)
{
	if (preg_match('/^([0-1][0-9]|[2][0-3]):([0-5][0-9]):([0-5][0-9])$/', value))
	{
		return true;
	}else{
		return false;
	}
}

function formatTime(id, idfocus) 
{ 
	var separator = ':';

	replaceTo(id, ' ', '.');
	replaceTo(id, ',', '.');
	replaceTo(id, /[^\d:]/g, '', false);

	var value = explode(separator, getValue(id));

	var result = '';

	if (value.length == 1)
	{
		if (value[0].length == 2)
		{
			result = value[0] + separator;
		}else{
			result = value[0];
		}
	}

	if (value.length == 2)
	{
		if (value[1].length == 2)
		{
			result = value[0] + separator + value[1] + separator;
		}else{
			result = value[0] + separator + value[1];
		}
	}
	if (value.length > 2)
	{
		result = value[0] + separator + value[1] + separator + value[2];
	}

	if (substr(result, -2) == separator + separator)
	{
		result = substr(result, 0, result.length - 1);
	}

	setValue(id, result);

	if (isTime(result))
	{
		setFocus(idfocus);
	}
} 

function formatDate(id, idfocus) 
{ 
	const separator = '.';
	
	replaceTo(id, ' ', '.');
	replaceTo(id, ',', '.');
	replaceTo(id, /[^\d.]/g, '', false);

	var value = explode(separator, getValue(id));

	var result = '';

	if (value.length == 1)
	{
		if (value[0].length == 2)
		{
			result = value[0] + separator;
		}else{
			result = value[0];
		}
	}

	if (value.length == 2)
	{
		if (value[1].length == 2)
		{
			result = value[0] + separator + value[1] + separator;
		}else{
			result = value[0] + separator + value[1];
		}
	}
	if (value.length > 2)
	{
		result = value[0] + separator + value[1] + separator + value[2];
	}

	if (substr(result, -2) == separator + separator)
	{
		result = substr(result, 0, result.length - 1);
	}

	setValue(id, result);

	if (isDate(result))
	{
		setFocus(idfocus);
	}
} 

function setAjaxFocus(id) 
{ 
	if (ajax_key13) 
	{
		ajax_key13 = false; 
		setFocus(id);
	}
} 

function formatDocumentNumber(id) 
{ 
	replaceTo(id, '*', 'I');
} 

function clearSpace(id) 
{ 
	replaceTo(id, ' ', '');
} 

function onlyNumber(id) 
{ 
	replaceTo(id, /[^\d]/g, '', false);
}

function showhideIdentDocument(idsource, idtype, idseria, iddateexpired) 
{ 
	var src = explode('@', getValue(idsource));
	var type = getValue(idtype);
	var data = [];
	for (var i = 0; i < src.length; i++)
	{
		data = explode('#', src[i]);
		if (data[0] == type)
		{
			if (data[3] == 3)
			{
				hideObjectPos(idseria);
			}else{
				showObjectPos(idseria);
			}
			if (data[4] == 3)
			{
				hideObjectPos(iddateexpired);
			}else{
				showObjectPos(iddateexpired);
			}			
			break;
		}
	}	
}

function formatDocumentOnMask(source, idfieldset, idfocus) 
{ 
	var value = getValue(idfieldset);
	if (source != '')
	{
		if (preg_match(source, value))
		{
			setFocus(idfocus);
		}
	}
}

function formatIdentDocument(idsource, idtype, idfieldset, idfocus, isseria) 
{ 
	formatDocumentNumber(idfieldset);
	clearSpace(idfieldset);
	
	var value = getValue(idfieldset);
	var src = explode('@', getValue(idsource));
	var data = [];
	var type = getValue(idtype);
	var pm = '';

	var field = (isseria) ? 1 : 2;
	
	for (var i = 0; i < src.length; i++)
	{
		data = explode('#', src[i]);
		if (data[0] == type && data[field] != '')
		{
			pm = data[field];
			break;
		}
	}
	if (pm != '')
	{
		if (preg_match(pm, value))
		{
			setFocus(idfocus);
		}
	}
}

function formatAddressZip(id, idfocus) 
{ 
	onlyNumber(id);
	if (strlen(getValue(id)) == 6)
	{
		setFocus(idfocus);
	}
} 

function formatPhone(idprefix, idphone, idfocus)
{
	var len = strlen(getValue(idprefix));

	if (len >= 3 && len <= 5 && (len + strlen(getValue(idphone))) == 10)
	{
		setFocus(idfocus);
	}	
}

function showhidePhone(idprefix, idphone, idprefix2, idphone2, idshowhide)
{
	var len = strlen(getValue(idprefix));

	if (getValue(idprefix2) != '' || getValue(idphone2) != '')
	{
		showObject(idshowhide);
	}else{
		if (strlen(getValue(idprefix) + getValue(idphone)) >= 10)
		{
			showObject(idshowhide);
		}else{
			hideObject(idshowhide);
		}
	}
}

function formatSNILS(idfieldset, idfocus)
{
	onlyNumber(idfieldset);
	
	if (isSNILS(getValue(idfieldset)))
	{
		setFocus(idfocus);
	}
}

function formatOGRN(idfieldset, idfocus)
{
	onlyNumber(idfieldset);
	
	if (isOGRN(getValue(idfieldset)))
	{
		setFocus(idfocus);
	}
}

function formatKPP(idfieldset, idfocus)
{
	onlyNumber(idfieldset);
	
	if (isKPP(getValue(idfieldset)))
	{
		setFocus(idfocus);
	}
}

function formatINN(idfieldset, idfocus)
{
	onlyNumber(idfieldset);
	
	if (isINN(getValue(idfieldset)))
	{
		setFocus(idfocus);
	}	
}

function formatTemproraryCertificate(idfieldset, idfocus)
{
	onlyNumber(idfieldset);
	
	if (isTemproraryCertificate(getValue(idfieldset)))
	{
		setFocus(idfocus);
	}	
}

function setView(id1, id2, show) 
{ 
	var obj1 = document.getElementById(id1);
	if (!obj1)
	{
		return;
	}
	if (typeof show != 'boolean')
	{
		show = (obj1.className == 'view_caption_show_b') ? false : true;
	}
	obj1.className = (show) ? 'view_caption_show_b' : 'view_caption_show_a';	

	var obj2 = document.getElementById(id2);
	if (!obj2)
	{
		return;
	}
	
	var children = obj2.childNodes[1].childNodes[1].childNodes;

	var count = 0;
	var hide = 0;
	var border = false;
	for (var i = 0; i < children.length; i++) 
	{		
		if (children[i].nodeType == 1)
		{
			if (children[i].className != 'view_show')
			{
				if (show)
				{
					children[i].style.position = 'inherit'; 
					children[i].style.left = '0px'; 			
				}else{
					children[i].style.position = 'absolute'; 
					children[i].style.left = '-1000px'; 
					hide = hide + 1;
				}
				children[i].style.top = children[i].style.left; 			
			}
			if (children[i].className == 'view_border')
			{
				border = true;
			}
			count = count + 1;
		}
	}	

	if (count == hide)
	{
		hideObjectPos(id2);
		if (border)
		{
			obj1.className = 'view_caption_show_a2';
		}
	}else{
		showObjectPos(id2); 
	}
}	

function setSort(index, element, $name) 
{ 
	element = element * 2;
	var url = getHref();
	if (index == element)
	{
		index = element + 1;
	}else{
		index = element;
	}
	url = insertParamEx(url, $name, index);
	setHref(url);
}

function showWebcameraImage(path, title) 
{
	width = 240 + 20;
	height = 320 + 20;

	w = window.open('', '', 'width=' + width + ',height=' + height + ',left=' + ((window.innerWidth - width) / 2) + ',top=' + ((window.innerHeight - height)/2) );

	w.document.write('<html><title>' + title + '</title><body><table width="100%" height="100%"><tr><td width="100%" align="center" valign="middle"><img src="' + path + '?uniq=' + Math.random() + '"></td></tr></table></body></html>');

	w.document.close();
}

function setFocusDefault() 
{
	setFocus('objectfocus');
}

function insertParamEx(url, key, value) 
{ 
	key = escape(key); 
	value = escape(value); 
	if (strpos(url, '?') == false)
	{
		url = url + '?';
	}
	var kvp = url.substr(0).split('&'); 
	if (kvp == '') 
	{ 
		return key + "=" + value;
	}else{ 
		var i = kvp.length; 
		var x; 
		while (i--) 
		{ 
			x = kvp[i].split('='); 
			if (x[0] == key) 
			{ 
				x[1] = value; 
				kvp[i] = x.join('='); 
				break; 
			} 
		} 
		if (i < 0) 
		{ 
			kvp[kvp.length] = [key, value].join('='); 
		} 
		return kvp.join('&');
	} 
} 

function submitParamEx(value)
{
	setFormValue('mainform', 'flag', value);
	sendSubmit('mainform');
}

function getRadioIndexEx()
{
	return getRadioArrayValue('mainform', 'radioindex');
}

function setFileNameFromFile(myFile, myFileName, isNew)
{
	var idmyfile = document.getElementById(myFile);
	var idmyfilename = document.getElementById(myFileName);

	if (!isNew)
	{
		if (idmyfilename.value != 0)
		{
			return;
		}
	}
	var s = idmyfile.value;

	if (s.length == 0)
	{
		return;
	}
	var d = s.split('.');

	idmyfilename.value = d[0];
}

function checkTime(i)
{
	if (i < 10)
	{
		i = '0' + i;
	}
	return i;
}

function startTime()
{
	var tm=new Date();
	var h=tm.getHours();
	var m=tm.getMinutes();
	var s=tm.getSeconds();
	h=checkTime(h);
	m=checkTime(m);

	var value = '';

	if (s%2 == 0)
	{
		value = h + ':' + m;
	}else{
		value = h + ' ' + m;
	}
	setInnerHTML('time', value);
	setTimeout('startTime()', 1000);
}

function showNotificationWindow() 
{
	var k = 1.6;
	var width = (window.innerWidth / k) + 20;
	var height = (window.innerHeight / k) + 20;

	var w = window.open('', '', 'toolbar=no,menubar=no,location=no,resizable=yes,scrollbars=yes,width=' + width + ',height=' + height + ',left=' + ((window.innerWidth - width) / 2) + ',top=' + ((window.innerHeight - height)/2) );
	
	w.document.location.href = 'notification.php';
	
	w.document.close();
	
	hideObject('infocenter_notification');
}

function alertInfoCenter()
{
	if (getShowObjectState('infocenter_message'))
	{
		showhideObject('infocenter_info_message');
	}
	if (getShowObjectState('infocenter_notification'))
	{
		showhideObject('infocenter_info_notification');
	}
	
	setTimeout('alertInfoCenter()', 500);
}

function ajaxShowInfoCenter()
{
	ajax_showInfoCenter();	
	setTimeout('ajaxShowInfoCenter()', 5000);
}

function startInfoCenter()
{
	alertInfoCenter();
	ajaxShowInfoCenter();
}

function beepInfoCenter(type, sound)
{
	if (sound == '')
	{
		return;
	}
	if (type == 1)
	{
		playSound('sound', sound);
	}else{
		BeepNewMessage(sound);
	}
}

function getUpdateMessagepage(users)
{
	var uid = getValue('infocenter_message_user');
	if (uid == 'list')
	{
		return true;
	}else{
		if (is_numericaff(uid))
		{
			var user = explode('@', users);

			for (var i = 0; i < user.length; i++)
			{
				if (user[i] == uid)
				{
					return true;
				}
			}
		}
		return false;
	}
}

function showInfoCenter(data)
{
	var value = explode('|', data);
	if (value.length != 3)
	{
		return;
	}
	
	var message = 0;
	var updatepage = false;
	
	var messages = explode('&', value[0]);
	if (messages.length == 2)
	{
		message = messages[0];
		if (message > 0)
		{
			updatepage = getUpdateMessagepage(messages[1]);
		}
	}	
	
	if (message > 0)
	{
		setInnerHTML('infocenter_text_message', message);
		showObject('infocenter_message');
	}else{
		hideObject('infocenter_message');
	}

	if (value[1] > 0)
	{
		setInnerHTML('infocenter_text_notification', value[1]);
		showObject('infocenter_notification');
		showNotificationWindow();
	}else{
		hideObject('infocenter_notification');
	}
	
	if (value[2] > 0)
	{
		if (updatepage)
		{
			submitParamEx('newmessage');
		}else{
			beepInfoCenter(getValue('infocenter_beep_type'), getValue('infocenter_beep'));
		}		
	}
}

function setValueEx(id, text)
{
	setValue(id, text);
	setRangeFocus(id);
}

function setRadioEx(id)
{
	setRadio(id);
	gotoEndPage();
}

function _getCheckBoxArrayCount()
{
	return getCheckBoxArrayCount('mainform', 'checkindex[]');
}

function _setCheckBoxArrayValue(value)
{
	setCheckBoxArrayValue('mainform', 'checkindex[]', value);
}
function _setCheckBoxArrayInverseValue()
{
	return setCheckBoxArrayInverseValue('mainform', 'checkindex[]');
}

function openHrefEx(path, name)
{
	openHref('open.php?path=' + path + '&name=' + name + '&uniq=' + Math.floor(8 * Math.random()));
}

function readBarCode(id)
{
	var result = false;
	if (!eventOnKeyPressEnter)
	{
		result = true;
		return result;
	}
	
	var obj = document.getElementById(id);
	if (!obj)
	{
		return result;
	}
	
	var bc = trim(obj.value);
	obj.value = '';
	eventOnKeyPressEnter = false;

	var id;

	if (isBarCode35(bc))
	{
		id = getCheckBoxIdFromCheckBoxArrayOnBarCode35('mainform', 'checkindex[]', extractBarCode35(bc));
	}else{
		id = getHiddenBoxIdFromHiddenBoxArrayOnBarCodeText('mainform', 'hiddenindex[]', strtoupper(bc));
		
	}
	if (id != "")
	{
		result = true;
		k = 'label' + id;
		setInnerHTML('notification_value', getValue(k));
	}
	setCheckBoxValue(id, true);
	//gotoScrollIntoView('pagemark' + id);
	setInnerHTML('cbcv', _getCheckBoxArrayCount());
	
	return result;
}

function getAutoSexType(id1, id2)
{
	var pat = trim(getValue(id1));

	var pat_end_a = strtolower(substr(pat, -3));
	var pat_end_b = strtolower(substr(pat, -2));

	if (pat_end_a == 'вна' || pat_end_a == 'чна')
	{
		setValue(id2, 1);
	}
	if (pat_end_a == 'вич' || pat_end_b == 'ыч' || pat_end_b == 'ич')
	{
		setValue(id2, 2);
	}
}

function setOptionColorOnGroupColor(id)
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	var value = getValue(id);
	for (var i = 0; i < obj.options.length; i++)
	{
		if (value == obj.options[i].value)
		{
			obj.style.color = obj.options[i].style.color;
			break;
		}
	}
}

function showhideWebcamera(id)
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	if (obj.style.position == '')
	{
		obj.style.position = 'absolute';
		obj.style.left = -1000 + 'px';
		obj.style.top = -1000 + 'px';
	}else{
		obj.style.position = '';
		obj.style.left = 0 + 'px';
		obj.style.top = 0 + 'px';
	}

	gotoEndPage();
}

function openDialogBox(id)
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	if (obj.type == 'file')
	{
		obj.click();
	}
}

function showhideProxy(idproxytype, idvisitertype, idproxy)
{
	if (getValue(idproxytype) == 2 || getValue(idvisitertype) == 2)
	{
		showObjectPos(idproxy);
	}else{
		hideObjectPos(idproxy);	
	}
}	

function showWait()
{
	showObject('wait');
}

function displayWait()
{
	setTimeout(showWait, waitTimeout);
}

function setHrefWait(value)
{
	displayWait();
	setHref(value);
}

function showhideOnChecked(id, idshowhide, idfocus)
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	if (obj.checked)
	{
		showObjectPos(idshowhide);	
		setFocus(idfocus);
	}else{
		hideObjectPos(idshowhide);	
	}
}

function isCheckBoxObject(id)
{
	var obj = document.getElementById(id);
	if (!obj)
	{
		return;
	}
	if (obj.type == 'checkbox')
	{
		return true;
	}else{
		return false;
	}
}

function showhideOnValue(id, value, idshowhide, idfocus, forward)
{
	if (typeof(forward) != 'boolean')
	{
		forward = true;
	}
	
	var values = explode(';', value);
	
	if (forward)
	{
		hideObjectPos(idshowhide);
	}else{
		showObjectPos(idshowhide);
	}
	
	var data;
	if (isCheckBoxObject(id))
	{
		data = String(getCheckBoxValue(id));
	}else{
		data = getValue(id);
	}
	for (var i = 0; i < values.length; i++)
	{
		if (data == values[i])
		{
			if (forward)
			{
				showObjectPos(idshowhide);	
				setFocus(idfocus);
			}else{
				hideObjectPos(idshowhide);
			}					
			return;
		}		
	}
}

function showhidePaste(id1, id2)
{
	if (getValue(id1) != '')
	{
		hideObject(id2);
	}else{
		showObject(id2);
	}
}

function initShowPaste(id1, id2, addevent1, addevent2)
{
	var obj1 = document.getElementById(id2);
	if (!obj1)
	{
		return;
	}
	var obj2 = document.getElementById(id1);
	if (!obj2)
	{
		return;
	}
	obj1.style.left = obj2.offsetWidth - obj2.offsetHeight + 'px';
	obj1.style.top = 0 + 'px';
	obj1.style.width = obj2.offsetHeight + 'px';
	obj1.style.height = obj2.offsetHeight + 'px';
	if (typeof(addevent1) != 'boolean')
	{
		addevent1 = true;
	}
	if (addevent1)
	{
		addEvent(obj2, 'keyup', function(){showhidePaste(id1, id2)});
	}
	
	if (typeof(addevent2) != 'boolean')
	{
		addevent2 = true;
	}
	if (addevent2)
	{
		addEvent(obj1, 'click', function(){showhidePaste(id1, id2)});
	}	
	if (addevent1 || addevent2)
	{
		showhidePaste(id1, id2);
	}
}

/*iac*/

function initShowClose(id, id2)
{
	var obj1 = document.getElementById(id2);
	if (!obj1)
	{
		return;
	}
	var obj2 = document.getElementById(id);
	if (!obj2)
	{
		return;
	}
	obj1.style.left = obj2.offsetWidth - obj2.offsetHeight + 'px';
	obj1.style.top = 0 + 'px';
	obj1.style.width = obj2.offsetHeight + 'px';
	obj1.style.height = obj2.offsetHeight + 'px';
}

function showhideEquipmentName()
{
	if (getValue('equipmentname1') == '-1')
	{
		hideObject('equipmentname1');
		showObject('equipmentname2'); 
		showObject('close1'); 
		setFocus('equipmentname2');	
	}else{
		showObject('equipmentname1'); 
		hideObject('equipmentname2');
		hideObject('close1');
		setFocus('equipmentname1');	
	}
}

function showhideDivisionList(did, crossid, addressid, agencyid)
{
	if (getValue(did) < 0)
	{
		
		hideObject(did);
		showObject(addressid);
		showObject(agencyid);
		showObject(crossid); 
		setFocus(addressid);	
	}else{
		showObject(did); 
		hideObject(addressid);
		hideObject(agencyid);
		hideObject(crossid);
		setFocus(did);	
	}
}

function showhideEquipmentManufacturer()
{
	if (getValue('equipmentmanufacturer1') == '-1')
	{
		hideObject('equipmentmanufacturer1');
		showObject('equipmentmanufacturer2'); 
		showObject('close2'); 
		setFocus('equipmentmanufacturer2');	
	}else{
		showObject('equipmentmanufacturer1'); 
		hideObject('equipmentmanufacturer2');
		hideObject('close2');
		setFocus('equipmentmanufacturer1');	
	}
}
function deleteFile(file_name)
{
	$.ajax({
			type:'post',
			url: 'ajax_delete_file.php',
			data: {'file' : file_name},
			dataType:"text"
		});
 }