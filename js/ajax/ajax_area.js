	var ajaxBox_offsetX = 0;
	var ajaxBox_offsetY = 0;

	var ajax_area_id = 0;
	var ajax_city_id = 0;

	var minimumLettersBeforeLookup = 1;	

	var ajax_list_objects = new Array();
	var ajax_list_cachedLists = new Array();
	var ajax_list_activeInput = false;
	var ajax_list_activeItem;
	var ajax_list_optionDivFirstItem = false;
	var ajax_list_optionDivLastItem = false;
	var ajax_list_currentLetters = new Array();
	var ajax_optionDiv = false;
	var ajax_optionDiv_iframe = false;

	var ajax_key13 = false;

	var ajax_list_MSIE = false;
	
	var ajax_optionDiv_heightAutoSize = true;
	var ajax_optionDiv_maxHeight = 175;
	var ajax_optionDiv_maxCount = 7;
	
	var ajax_optionDiv_widthAutoSize = true;
	var ajax_optionDiv_maxWidth = 1030;	
	var ajax_optionDiv_width = 248;	
	
	if(navigator.userAgent.indexOf('MSIE')>=0 && navigator.userAgent.indexOf('Opera')<0)ajax_list_MSIE=true;

	var currentListIndex = 0;

	function setAjaxFocus(id) 
	{ 
		if (!id)
		{
			return;
		}
		if (ajax_key13) 
		{
			ajax_key13 = false; 
			setFocus(id);
		}
	} 	
	
	function ajax_getTopPos(inputObj)
	{

	  var returnValue = inputObj.offsetTop;
	  while((inputObj = inputObj.offsetParent) != null){
	  	returnValue += inputObj.offsetTop;
	  }
	  return returnValue;
	}
	
	function ajax_list_cancelEvent()
	{
		return false;
	}

	function ajax_getLeftPos(inputObj)
	{
	  var returnValue = inputObj.offsetLeft;
	  while((inputObj = inputObj.offsetParent) != null)returnValue += inputObj.offsetLeft;

	  return returnValue;
	}

	function ajax_option_setValue(e,inputObj)
	{
		if(!inputObj)inputObj=this;
		var tmpValue = inputObj.innerHTML;
		if(ajax_list_MSIE)tmpValue = inputObj.innerText;else tmpValue = inputObj.textContent;
		if(!tmpValue)tmpValue = inputObj.innerHTML;
		ajax_list_activeInput.value = tmpValue;
		var end = ajax_list_activeInput.value.length;
		ajax_list_activeInput.setSelectionRange(end, end);
		ajax_list_activeInput.focus();
		
		if(document.getElementById(ajax_list_activeInput.name + '_hidden')){document.getElementById(ajax_list_activeInput.name + '_hidden').value = inputObj.id;

			}; 
		ajax_options_hide();
	}

	function ajax_options_hide()
	{
		if(ajax_optionDiv)ajax_optionDiv.style.display='none';
		if(ajax_optionDiv_iframe)ajax_optionDiv_iframe.style.display='none';
	}

	function ajax_options_rollOverActiveItem(item,fromKeyBoard)
	{
		if(ajax_list_activeItem)ajax_list_activeItem.className='optionDiv';
		item.className='optionDivSelected';
		ajax_list_activeItem = item;

		if(fromKeyBoard){
			if(ajax_list_activeItem.offsetTop>ajax_optionDiv.offsetHeight){
				ajax_optionDiv.scrollTop = ajax_list_activeItem.offsetTop - ajax_optionDiv.offsetHeight + ajax_list_activeItem.offsetHeight + 2 ;
			}
			if(ajax_list_activeItem.offsetTop<ajax_optionDiv.scrollTop)
			{
				ajax_optionDiv.scrollTop = 0;
			}
		}
	}

	function getScrlWidth(e) 
	{
        var eStyle = window.getComputedStyle ? getComputedStyle(e, null)
                : e.currentStyle;
        var eRightBordWidth = parseInt(eStyle.borderRightWidth);
        var eLeftBordWidth = parseInt(eStyle.borderLeftWidth);
        return e.offsetWidth - e.clientWidth - eRightBordWidth - eLeftBordWidth;
    }	
	
	function ajax_option_list_buildList(letters,externalFile)
	{
		ajax_optionDiv.innerHTML = '';
		ajax_list_activeItem = false;
		if(ajax_list_cachedLists[externalFile][letters.toLowerCase()].length<=1){
			ajax_options_hide();
			return;
		}
		ajax_list_optionDivFirstItem = false;
		var optionsAdded = false;
		var maxLimit = false;
		var count = 1;
		var maxHeight = 0;
		for(var no=0;no<ajax_list_cachedLists[externalFile][letters.toLowerCase()].length;no++){
			if(ajax_list_cachedLists[externalFile][letters.toLowerCase()][no].length==0)continue;
			optionsAdded = true;
			var div = document.createElement('DIV');
			var items = ajax_list_cachedLists[externalFile][letters.toLowerCase()][no].split(/###/gi);
			if(ajax_list_cachedLists[externalFile][letters.toLowerCase()].length==1 && ajax_list_activeInput.value == items[0]){
				ajax_options_hide();
				return;
			}
			div.innerHTML = items[items.length-1];
			div.id = items[0];
			div.className='optionDiv';
			div.onmouseover = function(){ ajax_options_rollOverActiveItem(this,false) }
			div.onclick = ajax_option_setValue;
			if(!ajax_list_optionDivFirstItem)ajax_list_optionDivFirstItem = div;
			ajax_optionDiv.appendChild(div);
			if (count <= ajax_optionDiv_maxCount)
			{
				maxHeight = maxHeight + div.offsetHeight;
			}
			if (count == ajax_optionDiv_maxCount)
			{
				maxLimit = true;
			}			
			count = count + 1;
		}	
		
		if (maxLimit && maxHeight != 0)
		{
			ajax_optionDiv_maxHeight = maxHeight;
		}

		ajax_list_optionDivLastItem = div;
		if(optionsAdded){
			
			ajax_optionDiv.style.display='block';
			if(ajax_optionDiv_iframe)ajax_optionDiv_iframe.style.display='';
			ajax_options_rollOverActiveItem(ajax_list_optionDivFirstItem,true);
		}

		ajax_optionDiv.style.width = 'auto';
		var scrollHeight = 0;
		if (ajax_optionDiv_heightAutoSize)
		{
			ajax_optionDiv.style.height = 'auto';
			if (ajax_optionDiv.offsetHeight > ajax_optionDiv_maxHeight)
			{
				ajax_optionDiv.style.height = ajax_optionDiv_maxHeight + 'px';
				scrollHeight = getScrlWidth(ajax_optionDiv);
			}else{
				ajax_optionDiv.style.height = 'auto';
			}
		}
		if (ajax_optionDiv_widthAutoSize)
		{
			ajax_optionDiv.style.width = 'auto';
			if (ajax_optionDiv.offsetWidth + scrollHeight > ajax_optionDiv_width)
			{
				ajax_optionDiv.style.width = ajax_optionDiv.offsetWidth + scrollHeight + 'px';
			}else{
				ajax_optionDiv.style.width = ajax_optionDiv_width  + 'px';
			}
			if (ajax_optionDiv.offsetWidth + scrollHeight > ajax_optionDiv_maxWidth)
			{
				ajax_optionDiv.style.width = ajax_optionDiv_maxWidth + 'px';
			}			
		}		
	}

	function ajax_option_list_showContent(ajaxIndex,inputObj,externalFile,whichIndex)
	{
		if(whichIndex!=currentListIndex)return;
		var letters = inputObj.value;
		var content = ajax_list_objects[ajaxIndex].response;
		var elements = content.split('|');
		ajax_list_cachedLists[externalFile][letters.toLowerCase()] = elements;
		ajax_option_list_buildList(letters,externalFile);
	}

	function ajax_option_resize(inputObj)
	{
		ajax_optionDiv.style.top = (ajax_getTopPos(inputObj) + inputObj.offsetHeight + ajaxBox_offsetY) + 'px';
		ajax_optionDiv.style.left = (ajax_getLeftPos(inputObj) + ajaxBox_offsetX) + 'px';
		if(ajax_optionDiv_iframe){
			ajax_optionDiv_iframe.style.left = ajax_optionDiv.style.left;
			ajax_optionDiv_iframe.style.top = ajax_optionDiv.style.top;
		}
	}

	function ajax_addParam(param, name, value)
	{
		var result = param;
		if (result != '')
		{
			result = result + '&';
		}
		result = result + name + '=' + encodeURIComponent(value);
		return result;
	}		
	
	function ajax_showContentInfoCenter(ajaxIndex, whichIndex)
	{
		if(whichIndex!=currentListIndex)return;
		var content = ajax_list_objects[ajaxIndex].response;

		showInfoCenter(content);
	}

	function ajax_showInfoCenter()
	{
		var externalFile = 'ajax_infocenter.php';
		var url = externalFile;
		var ajaxIndex = ajax_list_objects.length;
		ajax_list_objects[ajaxIndex] = new sack();
		currentListIndex++;
		var tmpIndex=currentListIndex / 1;
		ajax_list_objects[ajaxIndex].requestFile = url;
		ajax_list_objects[ajaxIndex].onCompletion = function(){ ajax_showContentInfoCenter(ajaxIndex, tmpIndex); };
		ajax_list_objects[ajaxIndex].runAJAX();
	}
	
	function ajax_showContentDirectoryPhoneOperator(ajaxIndex, idoperator, idcheck, whichIndex)
	{	
		if(whichIndex!=currentListIndex)return;
		var content = ajax_list_objects[ajaxIndex].response;
		content = explode('@', content);
	
		var result = '';
			
		if (content[0])
		{			
			if (idcheck != '')
			{
				setFocus(idcheck);
			}			
			result = content[1];
		}
		
		setInnerHTML(idoperator, result);
	}		
	
	function ajax_showDirectoryPhoneOperator(idprefix, idnumber, idoperator, isprefix)
	{
		var param = '';

		param = ajax_addParam(param, 'prefix', getValue(idprefix));
		param = ajax_addParam(param, 'number', getValue(idnumber));

		var idcheck = (isprefix) ? idnumber : '';
		
		var externalFile = 'ajax_directoryphoneoperator.php';
		var url = externalFile + '?' + param;
		
		var ajaxIndex = ajax_list_objects.length;
		ajax_list_objects[ajaxIndex] = new sack();
		currentListIndex++;
		var tmpIndex=currentListIndex / 1;
		ajax_list_objects[ajaxIndex].requestFile = url;

		ajax_list_objects[ajaxIndex].onCompletion = function(){ ajax_showContentDirectoryPhoneOperator(ajaxIndex, idoperator, idcheck, tmpIndex); };
		ajax_list_objects[ajaxIndex].runAJAX();
	}		
	
	function ajax_showContentDirectoryAddressZip(ajaxIndex, inputObj, id, whichIndex)
	{
		showObject(inputObj.id);
		if(whichIndex!=currentListIndex)return;
		var content = ajax_list_objects[ajaxIndex].response;
		setValue(id, content);
		setFocus(id);
	}	
	
	function ajax_showDirectoryAddressZip(inputObj, id, regionName, areaName, cityName, townName, streetName, homeNumber, caseNumber, buildNumber)
	{
		var param = '';
		param = ajax_addParam(param, 'region', regionName);
		param = ajax_addParam(param, 'area', areaName);
		param = ajax_addParam(param, 'city', cityName);
		param = ajax_addParam(param, 'town', townName);
		param = ajax_addParam(param, 'street', streetName);
		param = ajax_addParam(param, 'home', homeNumber);
		param = ajax_addParam(param, 'case', caseNumber);
		param = ajax_addParam(param, 'build', buildNumber);

		var externalFile = 'ajax_directoryaddresszip.php';
		var url = externalFile + '?' + param;
		
		var ajaxIndex = ajax_list_objects.length;
		ajax_list_objects[ajaxIndex] = new sack();
		currentListIndex++;
		var tmpIndex=currentListIndex / 1;
		ajax_list_objects[ajaxIndex].requestFile = url;
		ajax_list_objects[ajaxIndex].onCompletion = function(){ ajax_showContentDirectoryAddressZip(ajaxIndex, inputObj, id, tmpIndex); };
		
		hideObject(inputObj.id);
		
		ajax_list_objects[ajaxIndex].runAJAX();
	}	
	
	function ajax_showListDirectoryAddressCountry(inputObj, e, idfocus)
	{
		ajax_showList(inputObj, e, 'ajax_directoryaddresscountry.php');
		setAjaxFocus(idfocus);
	}	
	function ajax_showListDirectoryAddressRregion(inputObj, e, idfocus)
	{
		ajax_showList(inputObj, e, 'ajax_directoryaddressregion.php');
		setAjaxFocus(idfocus);
	}
	function ajax_showListDirectoryAddressArea(inputObj, e, regionName, idfocus)
	{
		var param = '';
		param = ajax_addParam(param, 'region', regionName);
		ajax_showList(inputObj, e, 'ajax_directoryaddressarea.php', param);
		setAjaxFocus(idfocus);
	}
	function ajax_showListDirectoryAddressCity(inputObj, e, regionName, areaName, idfocus)
	{
		var param = '';
		param = ajax_addParam(param, 'region', regionName);
		param = ajax_addParam(param, 'area', areaName);
		ajax_showList(inputObj, e, 'ajax_directoryaddresscity.php', param);
		setAjaxFocus(idfocus);
	}
	function ajax_showListDirectoryAddressTown(inputObj, e, regionName, areaName, cityName, idfocus)
	{
		var param = '';
		param = ajax_addParam(param, 'region', regionName);
		param = ajax_addParam(param, 'area', areaName);
		param = ajax_addParam(param, 'city', cityName);
		ajax_showList(inputObj, e, 'ajax_directoryaddresstown.php', param);
		setAjaxFocus(idfocus);
	}
	function ajax_showListDirectoryAddressStreet(inputObj, e, regionName, areaName, cityName, townName, idfocus)
	{
		var param = '';
		param = ajax_addParam(param, 'region', regionName);
		param = ajax_addParam(param, 'area', areaName);
		param = ajax_addParam(param, 'city', cityName);
		param = ajax_addParam(param, 'town', townName);
		ajax_showList(inputObj, e, 'ajax_directoryaddressstreet.php', param);
		setAjaxFocus(idfocus);
	}
	function ajax_showListDirectoryName(inputObj, e, idfocus)
	{
		ajax_showList(inputObj, e, 'ajax_directoryname.php');
		setAjaxFocus(idfocus);
	}
	function ajax_showListDirectoryPatronymic(inputObj, e, idfocus)
	{
		ajax_showList(inputObj, e, 'ajax_directorypatronymic.php');
		setAjaxFocus(idfocus);
	}
	function ajax_showListDirectoryBirthPlace(inputObj, e, idfocus)
	{
		ajax_showList(inputObj, e, 'ajax_directorybirthplace.php');
		setAjaxFocus(idfocus);
	}
	function ajax_showListDirectoryIdentDocumentPlace(inputObj, e, idfocus)
	{
		ajax_showList(inputObj, e, 'ajax_directoryidentdocumentplace.php');
		setAjaxFocus(idfocus);
	}
	function ajax_showListDirectoryProxyDocumentPlace(inputObj, e, idfocus)
	{
		ajax_showList(inputObj, e, 'ajax_directoryproxydocumentplace.php');
		setAjaxFocus(idfocus);
	}	
	function ajax_showListDirectoryLegalForm(inputObj, e, full, idfocus)
	{
		var param = '';
		full = (full) ? 1 : 0;
		param = ajax_addParam(param, 'full', full);	
		ajax_showList(inputObj, e, 'ajax_directorylegalform.php', param);
		setAjaxFocus(idfocus);
	}
	function ajax_showListDirectoryWorkPost(inputObj, e, type, idfocus)
	{
		var param = '';
		param = ajax_addParam(param, 'type', type);
		ajax_showList(inputObj, e, 'ajax_directoryworkpost.php', param);
		setAjaxFocus(idfocus);
	}
	
	function ajax_showList(inputObj, e, externalFile, param)
	{
		param = param || '';				
		if (param != '')
		{
			param = '&' + param;
		}
		if (e.keyCode == 13 || e.keyCode == 9)
		{
			return;
		}
		if (ajax_list_currentLetters[inputObj.name] == inputObj.value)
		{	
			return;
		}
		ajax_list_cachedLists[externalFile] = new Array();
		ajax_list_currentLetters[inputObj.name] = inputObj.value;
		if (!ajax_optionDiv)
		{
			ajax_optionDiv = document.createElement('DIV');
			ajax_optionDiv.id = 'ajax_listOfOptions';
			document.body.appendChild(ajax_optionDiv);

			var allInputs = document.getElementsByTagName('INPUT');
			for (var no = 0; no < allInputs.length; no++)
			{
				if(!allInputs[no].onkeyup)allInputs[no].onfocus = ajax_options_hide;
			}
			var allSelects = document.getElementsByTagName('SELECT');
			for (var no = 0; no < allSelects.length; no++)
			{
				allSelects[no].onfocus = ajax_options_hide;
			}
			var oldonkeydown=document.body.onkeydown;
			if (typeof oldonkeydown != 'function')
			{
				document.body.onkeydown = ajax_option_keyNavigation;
			}else{
				document.body.onkeydown = function(){oldonkeydown();ajax_option_keyNavigation();};
			}
			var oldonresize=document.body.onresize;
			if (typeof oldonresize != 'function')
			{
				document.body.onresize = function(){ajax_option_resize(inputObj);};
			}else{
				document.body.onresize = function(){oldonresize();ajax_option_resize(inputObj);};
			}
		}

		if (inputObj.value.length < minimumLettersBeforeLookup)
		{
			ajax_options_hide();
			return;
		}

		ajax_optionDiv.style.top = (ajax_getTopPos(inputObj) + inputObj.offsetHeight + ajaxBox_offsetY) + 'px';
		ajax_optionDiv.style.left = (ajax_getLeftPos(inputObj) + ajaxBox_offsetX) + 'px';
		
		ajax_optionDiv_width = inputObj.offsetWidth - 2;
		ajax_optionDiv.style.width = ajax_optionDiv_width + 'px';
		
		if (ajax_optionDiv_iframe)
		{
			ajax_optionDiv_iframe.style.left = ajax_optionDiv.style.left;
			ajax_optionDiv_iframe.style.top = ajax_optionDiv.style.top;
		}

		ajax_list_activeInput = inputObj;
		ajax_optionDiv.onselectstart =  ajax_list_cancelEvent;
		currentListIndex++;
		if (ajax_list_cachedLists[externalFile][inputObj.value.toLowerCase()])
		{
			ajax_option_list_buildList(inputObj.value,externalFile,currentListIndex);
		}else{
			var tmpIndex=currentListIndex / 1;
			ajax_optionDiv.innerHTML = '';
			var ajaxIndex = ajax_list_objects.length;
			ajax_list_objects[ajaxIndex] = new sack();

			var url = externalFile + '?letters=' + encodeURIComponent(inputObj.value) + param;

			ajax_list_objects[ajaxIndex].requestFile = url;
			ajax_list_objects[ajaxIndex].onCompletion = function(){ajax_option_list_showContent(ajaxIndex,inputObj,externalFile,tmpIndex);};
			ajax_list_objects[ajaxIndex].runAJAX();
		}
	}

	function ajax_option_keyNavigation(e)
	{
		ajax_key13 = false;
		if (document.all)
		{
			e = event;
		}
		if (!ajax_optionDiv)
		{
			return;
		}
		if (ajax_optionDiv.style.display == 'none')
		{
			return;
		}
		if (e.keyCode == 38) // Up arrow
		{
			if (!ajax_list_activeItem)
			{
				return;
			}
			if (ajax_list_activeItem && !ajax_list_activeItem.previousSibling)
			{
				return;
			}
			ajax_options_rollOverActiveItem(ajax_list_activeItem.previousSibling, true);
		}
		if (e.keyCode == 40) // Down arrow
		{
			if (!ajax_list_activeItem)
			{
				
				ajax_options_rollOverActiveItem(ajax_list_optionDivFirstItem, true);
			}else{
				if (!ajax_list_activeItem.nextSibling)
				{
					return;
				}
				ajax_options_rollOverActiveItem(ajax_list_activeItem.nextSibling, true);
			}
		}
		if (e.keyCode == 33) // PageUp arrow
		{
			ajax_options_rollOverActiveItem(ajax_list_optionDivFirstItem, true);
		}	
		if (e.keyCode == 34) // PageDown arrow
		{	
			ajax_options_rollOverActiveItem(ajax_list_optionDivLastItem, true);
		}	
		if (e.keyCode == 13 || e.keyCode == 9)
		{
			if (ajax_list_activeItem && ajax_list_activeItem.className == 'optionDivSelected')
			{
				ajax_option_setValue(false,ajax_list_activeItem);
			}
			if (e.keyCode == 13)
			{
				ajax_key13 = true;
				return false;
			}else{
				return true;
			}
		}
		if(e.keyCode == 27)
		{
			ajax_options_hide();
		}
	}

	document.documentElement.onclick = autoHideList;

	function autoHideList(e)
	{
		if(document.all)e = event;

		if (e.target) source = e.target;
			else if (e.srcElement) source = e.srcElement;
			if (source.nodeType == 3) 
				source = source.parentNode;
		if(source.tagName.toLowerCase()!='input' && source.tagName.toLowerCase()!='textarea')ajax_options_hide();
	}