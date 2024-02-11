var eventOnKeyPressEnter = false;
var eventOnKeyPressCtrl = false;
var eventActiveElementsID = [];

function isUseActiveElement()
{
	var obj = document.activeElement;
	if (!obj)
	{
		return false;
	}
	for (var i = 0; i < eventActiveElementsID.length; i++)
	{
		if (eventActiveElementsID[i] == obj.id)
		{
			return true;
		}
	}
	return false;
}

function getEventKeyDownCtrl(event)
{
	var use = true;
	event = event || window.event;
	if (event.keyCode == 17 || event.which == 17)
	{
		if (eventActiveElementsID.length > 0)
		{
			use = isUseActiveElement();
		}
		if (use)
		{
			event.preventDefault ? event.preventDefault() : event.returnValue = false;
			eventOnKeyPressCtrl = true;
		}
	}
}

function getEventKeyUpCtrl(event)
{
	var use = true;
	event = event || window.event;
	if (event.keyCode == 17 || event.which == 17)
	{
		if (eventActiveElementsID.length > 0)
		{
			use = isUseActiveElement();
		}
		if (use)
		{
			event.preventDefault ? event.preventDefault() : event.returnValue = false;
			eventOnKeyPressCtrl = false;
		}
	}
}

function getEventOnKeyPressEnter(event)
{
	var use = true;
	event = event || window.event;
	eventOnKeyPressEnter = false;
	if (event.keyCode == 13 || event.which == 13)
	{
		if (eventActiveElementsID.length > 0)
		{
			use = isUseActiveElement();
		}
		if (use)
		{
			event.preventDefault ? event.preventDefault() : event.returnValue = false;
			eventOnKeyPressEnter = true;			
		}
	}
}

function getEventOnKeyPressEnterReturn(event)
{
	event = event || window.event;
	if (event.keyCode == 13 || event.which == 13)
	{
		eventOnKeyPressEnter = true;
	}else{
		eventOnKeyPressEnter =  false;
	}
}
